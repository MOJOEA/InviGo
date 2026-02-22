<?php
function hashPassword(string $password): string
{
    return password_hash($password, PASSWORD_BCRYPT);
}
function verifyPassword(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}
function generateOTP(int $length = 6): string
{
    $otp = '';
    for ($i = 0; $i < $length; $i++) {
        $otp .= random_int(0, 9);
    }
    return $otp;
}
function generateStatelessOtp(int $registrationId, int $eventId): array
{
    $timeWindow = floor(time() / OTP_EXPIRY_SECONDS);
    $data = "{$registrationId}:{$eventId}:{$timeWindow}";
    $hash = hash_hmac('sha256', $data, OTP_SECRET_KEY);
    $decimal = hexdec(substr($hash, 0, 16));
    $otp = str_pad((string)($decimal % 1000000), OTP_LENGTH, '0', STR_PAD_LEFT);
    $expiresAt = time() + OTP_EXPIRY_SECONDS;
    return [
        'code' => $otp,
        'expires_at' => date('Y-m-d H:i:s', $expiresAt),
        'expires_timestamp' => $expiresAt
    ];
}
function verifyStatelessOtp(string $otpCode, int $registrationId, int $eventId): bool
{
    $otpCode = strtolower(trim($otpCode));
    $currentWindow = floor(time() / OTP_EXPIRY_SECONDS);
    $windows = [$currentWindow, $currentWindow - 1];
    
    foreach ($windows as $window) {
        $data = "{$registrationId}:{$eventId}:{$window}";
        $hash = hash_hmac('sha256', $data, OTP_SECRET_KEY);
        $decimal = hexdec(substr($hash, 0, 16));
        $expectedOtp = str_pad((string)($decimal % 1000000), OTP_LENGTH, '0', STR_PAD_LEFT);
        if (hash_equals($expectedOtp, $otpCode)) {
            return true;
        }
    }
    return false;
}
