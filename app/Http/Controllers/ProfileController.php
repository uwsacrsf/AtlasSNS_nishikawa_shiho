<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $user = Auth::user();
        return view('profiles.profile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $rules = [
            'username' => ['required', 'string', 'min:2', 'max:12', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'bio' => ['nullable', 'string', 'max:150'],
            'icon_image_file' => ['nullable', 'image', 'mimes:jpeg,png,bmp,gif,svg', 'max:2048'],
            'current_password' => ['nullable', 'string', 'min:8', 'max:20'],
            'password_confirmation' => ['nullable', 'string', 'min:8', 'max:20'],
        ];

        $validatedData = $request->validate($rules);

        $user->username = $validatedData['username'];
        $user->email = $validatedData['email'];
        $user->bio = $validatedData['bio'] ?? null;


        $mainPasswordInput = $request->input('current_password');
        $confirmPasswordInput = $request->input('password_confirmation');

        if (empty($mainPasswordInput) && empty($confirmPasswordInput)) {
        }
        else if (empty($mainPasswordInput) || empty($confirmPasswordInput)) {
            return back()->withErrors(['current_password' => 'パスワードとその確認入力の両方を入力してください。']);
        }
        else {
            if ($mainPasswordInput !== $confirmPasswordInput) {
                return back()->withErrors(['current_password' => 'パスワードと確認入力が一致しません。']);
            }
            if (Hash::check($mainPasswordInput, $user->password)) {

            }
            else {
                $user->password = Hash::make($mainPasswordInput);
            }
        }

       if ($request->hasFile('icon_image_file')) {
       if ($user->icon_image && $user->icon_image !== 'default_profile_icon.png') {
        $oldImagePath = public_path('images/' . $user->icon_image);
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }
    $fileName = time() . '.' . $request->file('icon_image_file')->extension();
    $request->file('icon_image_file')->move(public_path('images'), $fileName);
    $user->icon_image = $fileName;
}

        $user->save();
        return redirect()->route('top');
    }
}
