<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()     //not exist
    // {
    //     $this->middleware('guest');
    // }
    //send case success
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return response(['message'=> trans($response)]);

    }

//send case fail

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response(['error'=> trans($response)], 422);

    }
}
