<?php
class API {
    private static $base = "https://yourdomain.com/api/";

    public static function get($endpoint, $params = []) {
        $url = self::$base . $endpoint . "?" . http_build_query($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }

    public static function post($endpoint, $data = []) {
        $url = self::$base . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }
}
