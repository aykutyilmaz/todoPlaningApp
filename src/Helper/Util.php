<?php

namespace App\Helper;

class Util {
    public static function makeRequest($url, $jsonResponse = false)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);

        curl_close($curl);
        if ($jsonResponse){
            $result = json_decode($result,true);
        }

        return $result;
    }

    public static function getImplementingClasses( $interfaceName ): array
    {
        return array_filter(
            get_declared_classes(),
            function( $className ) use ( $interfaceName ) {
                return in_array( $interfaceName, class_implements( $className ) );
            }
        );
    }
}