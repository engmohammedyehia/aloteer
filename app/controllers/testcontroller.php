<?php
namespace PHPMVC\Controllers;

use PHPMVC\Lib\Database\DatabaseHandler;

class TestController extends AbstractController
{
    public function defaultAction()
    {
        $numbers = [1 => '',2 => '',3 => '',4 => '',5 => '',6 => '',7 => '',8 => '',9 => '',10 => '',11 => '', 12 => '',];
        $number = '1';




//        DatabaseHandler::factory()->exec('');
//        $str = 'ABCDEFG';
//
//        for ( $i = 0, ($ii = strlen($str) - 1); $i <= $ii; $i++) {
//            $left = $str[$i];
//            $right = $str[$ii];
//            $str[$i] = $right;
//            $str[$ii] = $left;
//            $ii--;
//        }
//
//        echo $str;
    }
}