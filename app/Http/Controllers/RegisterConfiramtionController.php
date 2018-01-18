<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class RegisterConfiramtionController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    // to create thread you must confirm your email.
    // create thread then send token by email to client and client click link
    // and compare token and confirmed it then user can make Thread.
    // to do this action , follow this steps:
    // 1- make middleware 'must-be-confirmed'
    // 2- make column 'confirmed' in user table, it mean the email is confirmed
    // 3-  i used ready event Registred() located Auth/RegisterController/register, look register() function
    // it is in trait ,and i make listner SendEmailConfirmationRequest() for this event
    // 4- run command event:generate
    // 5- make email by : make:mail PleaseConfirmYourEmail --markdown=emails.confirm-email
    // 6- i used PleaseConfirmYourEmail() in listner SendEmailConfirmationRequest()
    // and passed variable $user and make public $user variable in PleaseConfirmYourEmail()
    // 7- in RegisterController i changed User::create to User::forceCreate()
    // to get red of from massasignment exception
    //8- make column confiramtion_token in user table, this for compare token in link and user table, must be the same
    // and after confirm token(confiramtion_token) , must delete it from user table
    public function index()
    {
//        try {
//            User::where('confirmation_token', request()->token)
//                ->firstOrFail()
//                ->confirm();
//            
//            session()->push('m','success');
//            session()->push('m','you have successfully confrim your E-mail.');
//            
//        } catch (\Exception $ex) {
//            session()->push('m','warning');
//            session()->push('m','unknown token.');
//            
//        }
        
          // or
        $user= User::where('confirmation_token', request()->token)->first();
        
        if(!$user){
            session()->push('m','warning');
            session()->push('m','unknown token.');
        }else{
            $user->confirm();
            session()->push('m','success');
            session()->push('m','you have successfully confrim your E-mail.');
        }
        return redirect('/home');    
    }
}
