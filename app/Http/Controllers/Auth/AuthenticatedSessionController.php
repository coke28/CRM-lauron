<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        
        $request->authenticate();
        $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();
        if (!empty($user)) {
            $user->online = "1";
            $user->save();
        } else {
            return "something went wrong";
        }
        $request->session()->regenerate();
        //add logged in user's info to session
        Session::put('id', auth()->user()->id);
        Session::put('username', auth()->user()->username);
        Session::put('level', auth()->user()->level);
        Session::put('first_name', auth()->user()->first_name);
        Session::put('last_name', auth()->user()->last_name);
        Session::put('email', auth()->user()->email);
        Session::put('groupe', auth()->user()->groupe);
        Session::put('product', auth()->user()->product);
        Session::put('logged', 'true');
        Session::put('limited', auth()->user()->product);
        Session::put('timer', 0);
        Session::put('agentNum', auth()->user()->agentNum);
        Session::put('extension', auth()->user()->extension);
        Session::put('salesmanID', auth()->user()->salesmanID);
        Session::put('endToEnd', auth()->user()->endToEnd);

        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Logged in";
        $auditLog->table = "";
        $auditLog->nID = "";
        $auditLog->ip = \Request::ip();
        $auditLog->save();

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $auditLog = new AuditLog();
        $auditLog->agent = auth()->user()->id;
        $auditLog->action = "Logged Out";
        $auditLog->table = "";
        $auditLog->nID = "";
        $auditLog->ip = \Request::ip();
        $auditLog->save();

        $user = User::where('id', auth()->user()->id)->where('deleted', '0')->first();
        if (!empty($user)) {
            $user->online = "0";
            $user->save();
        } else {
            return "something went wrong";
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
