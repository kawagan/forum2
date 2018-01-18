<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }
    
    public function store()
    {
        $rules=[
            'avatar'=>['required','image'],
        ];
        
        $this->validate(request(), $rules);
        
        // generally , see UploadFile() and Storage() classes in laravel .
        // i store file in foler 'avatars' in public driver.
        // saved in storage/app/public.
        // run this comand storage:link, then it will created symbolic link from
        // public/storage to stoage/app/public, becs we need it avaiable in public also, so
        // we have the same image in tow place storage and public
        // file saved with hashname.
        auth()->user()->update([
            'avatar_path'=> request()->file('avatar')->store('avatars','public')
        ]);
        return redirect()->back();
    }
}
