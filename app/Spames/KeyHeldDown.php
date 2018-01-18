<?php

namespace App\Spames;
use Exception;
class KeyHeldDown {
    
    public function detect($body)
    {
        if( preg_match('/(.)\\1{4,}/', $body) ){
            throw new Exception('the Reply has Spam!!!, repeat the same characters');
        }
    }
}
