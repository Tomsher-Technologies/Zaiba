<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function adminLoginView()
    {
        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            // 'g-recaptcha-response' => 'required'
            // 'g-recaptcha-response' => 'required|captcha'
        ], [
            'g-recaptcha-response.required' => 'Please complete the recaptcha'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
            'user_type' => [
                'staff',
                'admin'
            ]
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        // $user = User::where('email', $request->email)->whereIn('user_type', array('admina', 'sellera'))->first();

        // if ($user) {

        // }
        return back()->withErrors([
            'login' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
