<?php

namespace App\Core;

class JWT {

    private static $secret = "SUPER_SECRET_KEY_CHANGE_THIS_123";

    public static function encode($payload) {
        $header = self::base64Url(json_encode(["alg" => "HS256", "typ" => "JWT"]));
        $body   = self::base64Url(json_encode($payload));
        $signature = hash_hmac("sha256", "$header.$body", self::$secret, true);

        return $header . "." . $body . "." . self::base64Url($signature);
    }

    public static function decode($token) {
        $parts = explode(".", $token);
        if(count($parts) != 3) return false;

        list($header64, $body64, $signature64) = $parts;

        $validSig = self::base64Url(hash_hmac("sha256", "$header64.$body64", self::$secret, true));
        if ($signature64 !== $validSig) return false;

        return json_decode(base64_decode(strtr($body64, '-_', '+/')), true);
    }

    private static function base64Url($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }
}
