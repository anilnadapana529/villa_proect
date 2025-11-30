<?php

namespace App\Helpers;

class JWT
{
    private static string $secret = "YOUR_SUPER_SECRET_KEY";
    private static int $expiry = 86400; // 24 hours

    /** Create token */
    public static function encode(array $payload): string
    {
        $header = json_encode(["alg" => "HS256", "typ" => "JWT"]);
        $payload["exp"] = time() + self::$expiry;

        $base64Header  = rtrim(strtr(base64_encode($header), '+/', '-_'), '=');
        $base64Payload = rtrim(strtr(base64_encode(json_encode($payload)), '+/', '-_'), '=');

        $signature = hash_hmac("sha256", "$base64Header.$base64Payload", self::$secret, true);
        $base64Signature = rtrim(strtr(base64_encode($signature), '+/', '-_'), '=');

        return "$base64Header.$base64Payload.$base64Signature";
    }

    /** Decode token */
    public static function decode(string $token): ?array
    {
        $parts = explode(".", $token);
        if (count($parts) !== 3) return null;

        [$header, $payload, $signature] = $parts;

        $validSignature = rtrim(strtr(
            base64_encode(
                hash_hmac("sha256", "$header.$payload", self::$secret, true)
            ), '+/', '-_'
        ), '=');

        if ($signature !== $validSignature) return null;

        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        if (!isset($data["exp"]) || $data["exp"] < time()) {
            return null;
        }

        return $data;
    }

    /** Validate token */
    public static function validate(string $token): bool
    {
        return self::decode($token) !== null;
    }
}
