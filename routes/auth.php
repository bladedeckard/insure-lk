<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::get('login', fn()=>view('auth.login'))->middleware('guest')->name('login');
Route::post('login', function(Request $r){
    $cred = $r->validate(['email'=>'required|email','password'=>'required']);
    if (Auth::attempt($cred, $r->boolean('remember'))) {
        $r->session()->regenerate();
        if (!Auth::user()->is_active || (Auth::user()->intermediary && !Auth::user()->intermediary->is_active)) {
            Auth::logout(); return back()->withErrors(['email'=>'Аккаунт деактивирован']);
        }
        return redirect()->intended('/dashboard');
    }
    return back()->withErrors(['email'=>'Неверные данные']);
})->middleware('guest');

Route::post('logout', function(Request $r){ Auth::logout(); $r->session()->invalidate(); $r->session()->regenerateToken(); return redirect('/login');})->name('logout');
