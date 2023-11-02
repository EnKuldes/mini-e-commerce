<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/';
    protected function redirectTo()
    {
        return route('redirect-first-page');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showResetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'active' => '1'])) {
            $request->session()->regenerate();

            return redirect()->route('redirect-first-page');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function registerUser(Request $request)
    {
        $data = $request->validate([
            'name' => 'required'
            , 'email' => 'required|unique:users,email|email'
            , 'password' => 'required|min:6|confirmed'
        ]);

        $data = \App\Models\User::create($data);

        if ($data->exists) {
            return redirect()->route('login')->with('status', 'Successfully registering!');;
        }

        return back()->withErrors($data)->withInput();
    }

    public function resetPassword(Request $request)
    {
        // Simpel dulu caranya dg minta email dan new password
        $validator = $request->validate([
            'email' => 'required|email'
            , 'password' => 'required|min:6|confirmed'
        ]);

        try {
            $data = \App\Models\User::where('email', '=', $validator['email'])->firstOrFail();
            if ($data->active == 0) {
                return back()->withErrors(['email' => 'Account was disabled.']);
            }
            $data->password = $validator['password'];
            $data->save();
        }  catch (\Illuminate\Database\QueryException $e) {
            return back()->withErrors(['email' => 'Failed to reset Password']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return back()->withErrors(['email' => 'Email Not Found!']);
        }

        return redirect()->route('login')->with('status', 'Successfully Reset Password!');;
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('redirect-first-page');
    }
}
