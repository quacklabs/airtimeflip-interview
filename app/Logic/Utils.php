<?php
namespace App\Logic;

use stdClass;

class Utils {

    public static function array2obj(array $array): Object {
        $obj = new stdClass();
        foreach($array as $key => $value) {
            $obj->$key = $value;
        }

        return $obj;
    }
}

?>
