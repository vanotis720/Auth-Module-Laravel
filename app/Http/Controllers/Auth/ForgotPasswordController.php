<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{
    public function sendLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);

        $token = Str::random(64);

        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        Mail::send('password.email', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password Notification');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }

    public function getPassword($token) { 
        return view('password.reset', ['token' => $token]);
    }
   
    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'bail|required|confirmed',
            'token' => 'required',
        ]);
   
        $updatePassword = DB::table('password_resets')
                            ->where('token', $request->token)
                            ->first();
   
        if(!$updatePassword) {
            return back()->withInput()->with('error', 'Invalid token!');
        }
        User::where('email', $updatePassword->email)
                ->update(['password' => Hash::make($request->password)]);
        
        DB::table('password_resets')
            ->where(['email'=> $updatePassword->email])
            ->delete();
        
        return redirect('/login')->with('message', 'Your password has been changed!');
    
    }
}
