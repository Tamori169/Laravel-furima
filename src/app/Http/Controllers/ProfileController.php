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

        $imagePath = null; // 初期値は”空” //

        if ($request->hasFile('image')) {
        $file = $request->file('image');
        $fileName = $file->getClientOriginalName();
        $file->storeAs('images/profiles', $fileName, 'public');
        $imagePath = '/storage/images/profiles/' . $fileName;
    }

        Profile::create([
            'user_id'        => $user_id,
            'name'           => $request->name,
            'image'          => $imagePath,
            'postal_code'    => $request->postal_code,
            'address'        => $request->address,
            'building'       => $request->building,
        ]);

        return redirect()->route('item.index');
    }

    public function edit($id)
    {
        $profile = Profile::findOrFail($id);
        return view('profiles.form', compact('profile'));
    }
}
