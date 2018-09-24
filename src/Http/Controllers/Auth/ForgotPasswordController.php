<?php

namespace Versatile\Core\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Versatile\Core\Http\Controllers\Controller;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('versatile.guest');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('versatile::auth.request');
    }

    protected function sendResetLinkResponse($response)
    {
        return back()->with('status', trans('versatile::auth.password_sent'));
    }
}
