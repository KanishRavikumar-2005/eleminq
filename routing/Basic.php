<?php
class Basic{
    public static function random($lowercase = 'abcdefghijklmnopqrstuvwxyz', $digits = '0123456789', $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', $special = '!@#%^*()*+', $length = 10){
        
        if($lowercase == false){
            $lowercase = "";
        }

        if($digits == false){
            $digits = "";
        }

        if($uppercase == false){
            $uppercase = "";
        }

        if($special == false){
            $special = "";
        }

        $characters = $digits .$lowercase. $uppercase . $special;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function group(...$uiqvals){
        $mstring = '';
        $delim = array_pop($uiqvals);
        foreach($uiqvals as $uvs){
            if($mstring == ''){
                $mstring = $uvs;
            }else{
                $mstring .= $delim . $uvs;
            }
        }
        return $mstring;
    }
}

?>