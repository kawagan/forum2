<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // view  composer
        // replace \App\Channel::all() to channel in tow file
        /*
        View::composer(['layouts.app','threads.create'],function ($view){
            $view->with('channels',\App\Channel::all());
        });
         */ // or

        // we can share channel variable in all views in tow way:
        View::composer('*',function($view){
            // cache for reduce select also
            $channels= Cache::rememberForever('channels',function(){
                return \App\Channel::all();
            });
            $view->with('channels',$channels);
        });     
       
        // without cache
        /*View::composer('*',function($view){
            $view->with('channels',\App\Channel::all());
        });*/
         // or

        //View::share('channels',\App\Channel::all());
     
//*********************************************************************
        // notifications
        // user must be subscriber to make notifications
            View::composer('includes.nav',function($view){
                if(auth()->check()){
                    $notifications=auth()->user()->unreadNotifications;
                    $view->with('notifications',$notifications);
                }
            });
            
            
            // custome validation explained in ReplyController
            validator()->extend('spamfree','App\Rules\Spamfree@passes');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // if we work localy . run debuggger
        if($this->app->isLocal()){
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }
}
