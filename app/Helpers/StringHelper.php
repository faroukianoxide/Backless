<?php

namespace App\Helpers;

class StringHelper {


    public static function createRandomString($length) {

            $input = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $input_length = strlen($input);
            $random_string = '';
            for($i = 0; $i < $length; $i++) {
                $random_character = $input[random_int(0, $input_length - 1)];
                $random_string .= $random_character;
            }
        
            return $random_string;
        
        
    }

    public static function isJSON ($string){

        return is_string($string) && is_array(json_decode($string, true)) ? true : false;

    }

}