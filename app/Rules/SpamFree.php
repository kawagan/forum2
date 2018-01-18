<?php

namespace App\Rules;

use App\Spames\Spam;

class SpamFree {
   
    public function passes($attribute,$value)
    {
        // spam class return exeption,so we handle it to return boolean
        try {
           return !resolve(Spam::class)->detect($value);
        } catch (\Exception $ex) {
            return false;
        }
    }
}
