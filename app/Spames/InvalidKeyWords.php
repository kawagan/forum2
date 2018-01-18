<?php

namespace App\Spames;
use Exception;
class InvalidKeyWords {
    
    protected $keywords=[
        'yahoo customer support'
    ];
    public function detect($body)
    {
        foreach($this->keywords as $spam){
           if( stripos($body, $spam) !==false ){
               throw new Exception('the reply has spam');
           }
        }
    }
}
