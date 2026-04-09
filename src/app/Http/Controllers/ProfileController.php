<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $user_id = Auth::id();
        Profile::create([
            'user_id'        => $user_id,
            'image'          => $request->image,
            'postal_code'    => $request->postal_code,
            'address'        => $request->address,
            'building'       => $request->building,
        ]);
        User::update([
            'name'           => $request->name,
        ]);

        return redirect()->route('item.index');
    }

    public function edit($id)
    {
        $profile = Profile::findOrFail($id);
        return view('profiles.form', compact('profile'));
    }
}
