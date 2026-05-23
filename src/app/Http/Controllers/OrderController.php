<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class OrderController extends Controller
{
    public function create(Request $request, $item_id)
    {
        $user = Auth::user();
        $profile = $user->profile;
        $item = Item::findOrFail($item_id);

        if ($item->is_owner) {
            abort(403);
        }

        $address = (object)session('custom_address', [
            'postal_code' => optional($profile)->postal_code ?? '',
            'address'     => optional($profile)->address ?? '',
            'building'    => optional($profile)->building ?? '',
        ]);

        $paymentMethod = session('payment_method', '');

        return view('orders.create', compact('item', 'profile', 'address', 'item_id', 'paymentMethod'));
    }

    public function edit(Request $request, $item_id)
    {
        $user = Auth::user();
        $item = Item::findOrFail($item_id);

        if ($request->filled('payment_method')) {
            $paymentMethod = $request->payment_method;
            session(['payment_method' => $paymentMethod]);
        }

        return view('orders.edit-address', compact('user', 'item'));
    }

    public function update(AddressRequest $request, $item_id)
    {
        $address = $request->only(['postal_code', 'address', 'building']);
        session(['custom_address' => $address]);


        return redirect()->route('order.create', ['item_id' => $item_id]);
    }

    public function store(PurchaseRequest $request, $item_id)
    {
        $item = Item::findOrFail($item_id);

        if ($item->is_owner) {
            abort(403);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethodType = $request->payment_method === 'コンビニ払い' ? 'konbini' : 'card';

        $sessionParams = [
            'payment_method_types' => [$paymentMethodType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',

            'metadata' => [
                'item_id'        => $item->id,
                'user_id'        => auth()->id(),
                'postal_code'    => $request->postal_code,
                'address'        => $request->address,
                'building'       => $request->building,
                'payment_method' => $request->payment_method,
            ],

            'success_url' => route('order.complete'),
            'cancel_url' => url('/'),
        ];

        if ($paymentMethodType === 'konbini') {
            $sessionParams['customer_email'] = 'succeed_immediately@test.com';
            $sessionParams['payment_method_options'] = [
                'konbini' => [
                    'expires_after_days' => 3,
                ],
            ];
        }

        $session = Session::create($sessionParams);

        return redirect($session->url);
    }

    public function complete()
    {
        return redirect()->route('item.index')->with('message', '商品の購入が完了しました');
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException $e) {
            return response('Invalid payload', 400);
        } catch (SignatureVerificationException $e) {
            return response('Invalid signature', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                if (($session->payment_status ?? null) === 'paid') {
                    $this->createOrderIfNotExists($session);
                }
                break;

            case 'checkout.session.async_payment_succeeded':
                $session = $event->data->object;
                $this->createOrderIfNotExists($session);
                break;
        }

        return response('OK', 200);
    }

    private function createOrderIfNotExists($session)
    {
        $data = $session->metadata;

        if (Order::where('item_id', $data->item_id)->exists()) {
            return;
        }

        Order::create([
            'user_id'        => $data->user_id,
            'item_id'        => $data->item_id,
            'postal_code'    => $data->postal_code,
            'address'        => $data->address,
            'building'       => $data->building,
            'payment_method' => $data->payment_method,
        ]);
    }
}
