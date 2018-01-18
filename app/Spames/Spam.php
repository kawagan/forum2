<?php
namespace App\Spames;

use App\Spames\InvalidKeyWords;
use App\Spames\KeyHeldDown;
class Spam
{
    // we can make tow function in Spam class InvalidKeyWords() and KeyHeldDown()
    // but after refactor:
    // we make tow spam classes and the bothes we use it here, easily we can make third class also
    // we can use interface class or implicit class,
    // here will use implicit
    
    protected $inspections=[
        InvalidKeyWords::class,
        KeyHeldDown::class,
    ];


    public function detect($body)
    {
        foreach($this->inspections as $inspection){
            // app() or resolve() for depedency injection
            app($inspection)->detect($body);
        }
        
      // when throw exception, then not goint to return false  
     // $this->detectInvalidKeywords($body); 
      
      return false;
    }
    
}

