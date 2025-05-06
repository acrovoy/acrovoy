<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
          
        ]);

        // Обновляем имя и email
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

       

        return back()->with('status', 'Saved');
    }


    public function updatePassword(Request $request)
    {
        // Валидация данных
        $request->validate([
            'current_password' => ['required', 'string', 'min:8'],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ]);

        // Проверка текущего пароля
        if (! Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Обновление пароля
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        // Сообщение об успешном обновлении
        return back()->with('status_password', 'Password updated successfully!');
    }




}
