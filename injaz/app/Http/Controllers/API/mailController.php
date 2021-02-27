<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\testMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Models\User;
use Illuminate\Support\Facades\Validator;

class mailController extends Controller
{

    public function sendEmail(request $request)

    {
        $input = $request->all();
        $validator = validator::make($input, [
            'email' => 'required|email',
        ]);

        /*
        if ($validator->fails()) {
            return $this->sendError('All fields are required', $validator->errors());
        }
        */

        $details = [
            'title' => 'Mail from Task App - Game Development',
            'body' => 'This is for testing email using smtp'
        ];

        Mail::to($input)->send(new testMail($details));
        return "Email sent";
    }
}
