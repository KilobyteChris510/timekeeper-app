<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function login()
    {
        return view('admin.login');
    }

    public function authenticate(Request $request)
    {
        if (!hash_equals('timekeeperadmin', (string) $request->input('password'))) {
            return back()->withErrors(['password' => 'The password is incorrect.']);
        }

        $request->session()->regenerate();
        $request->session()->put('admin_authenticated', true);

        return redirect()->route('admin.index');
    }

    public function index()
    {
        return view('admin.index');
    }
}
