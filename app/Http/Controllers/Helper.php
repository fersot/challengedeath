<?php

namespace App\Http\Controllers;

use App\Http\Models\Entities\Item;
use Illuminate\Routing\Controller as BaseController;


class Helper extends BaseController
{

    public static function generate_code()
    {
        $code = self::GenerateCode('ITEM', 7);
        $item = Item::where('code',$code)->first();
        while(!is_null($item)){
            $code = self::GenerateCode('M', 7);
            $item = Item::where('code',$code)->first();
        }
        return $code;
    }

    public static function GenerateCode($type, $longitud)
    {
        $key = 'ITEM-';
        $pattern = '1234567890';
        $max = strlen($pattern) - 1;
        for ($i = 0; $i < $longitud; $i++) $key .= $pattern{mt_rand(0, $max)};
        return $key;
    }

}
