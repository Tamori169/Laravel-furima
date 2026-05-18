<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $profile = new Profile();
        return view('profiles.form', compact('user','profile'));
    }

    public function store(ProfileRequest $request)
    {
        $user = Auth::user();

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->image->store('images/profiles', 'public');
        }

        Profile::create([
            'user_id'        => $user->id,
            'image'          => $imagePath,
            'postal_code'    => $request->postal_code,
            'address'        => $request->address,
            'building'       => $request->building,
        ]);

        $user->update([
            'name'           => $request->name,
        ]);

        return redirect()->route('item.index');
    }

    public function show(Request $request)
    {
        $user = auth()->user();
        $profile = $user->profile;

        $page = $request->query('page', 'sell');

        if ($page === 'buy') {
            $items = $user->orders()
                        ->with('item')
                        ->get()
                        ->pluck('item');
        } elseif ($page === 'sell') {
            $items = $user->items()->latest()->get();
        } else {
            $items = collect();
        }

        return view('profiles.show', compact('user', 'profile', 'items', 'page'));
    }

    public function edit()
    {
        $user = auth()->user();
        $profile = $user->profile;

        return view('profiles.form', compact('user','profile'));
    }

    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        $profile = $user->profile;

        $imagePath = $profile->image;

        if ($request->hasFile('image')) {
            $imagePath = $request->image->store('images/profiles', 'public');
        }

        $profile->update([
            'image'          => $imagePath,
            'postal_code'    => $request->postal_code,
            'address'        => $request->address,
            'building'       => $request->building,
        ]);

        $user->update([
            'name'           => $request->name,
        ]);

        return redirect()->route('profile.show');
    }

}
