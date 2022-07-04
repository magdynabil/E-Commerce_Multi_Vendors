<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;

class LoginController extends Controller
{
    public function login()
    {
        return view('dashboard.auth.login');

    }

    public function postLogin(AdminLoginRequest $request)
    {
        $remember_me = ($request->has('remember_me')) ? true : false;
        if ($this->getguard()->attempt(
            [
                'email' => $request->input('email'),
                'password' => $request->input('password')
            ],
            $remember_me
        )) {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->back()->with(['error' => 'هناك خطأ في البينات']);
        }

    }

    public function logout()
    {
        $guard=$this->getguard();
        $guard->logout();
        return redirect()->route('admin.login');
    }
    private function getguard()
    {
        return auth()->guard('admin');
    }
}
