<?php
class API {
    private static $base = "https://topmost.in/api/";

    public static function get($endpoint, $params = [], $token = null) {
        $url = self::$base . $endpoint;
        if (!empty($params)) {
            $url .= "?" . http_build_query($params);
        }

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = ['Content-Type: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }

    public static function post($endpoint, $data = [], $token = null) {
        $url = self::$base . $endpoint;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $headers = ['Content-Type: application/json'];
        if ($token) {
            $headers[] = 'Authorization: Bearer ' . $token;
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $res = curl_exec($ch);
        curl_close($ch);

        return json_decode($res, true);
    }

    public static function getToken() {
        return $_SESSION['token'] ?? null;
    }

    public static function setToken($token) {
        $_SESSION['token'] = $token;
    }

    public static function getUser() {
        return $_SESSION['user'] ?? null;
    }

    public static function setUser($user) {
        $_SESSION['user'] = $user;
    }

    public static function getUserRole() {
        return $_SESSION['role'] ?? null;
    }

    public static function setUserRole($role) {
        $_SESSION['role'] = $role;
    }

    public static function logout() {
        session_destroy();
    }

    public static function isLoggedIn() {
        return !empty($_SESSION['token']);
    }
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
