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
    $otp = substr($hash, 0, OTP_LENGTH);
    $otp = preg_replace('/[^0-9]/', '', $otp);
    while (strlen($otp) < OTP_LENGTH) {
        $otp .= random_int(0, 9);
    }
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
        $expectedOtp = substr($hash, 0, OTP_LENGTH);
        $expectedOtp = preg_replace('/[^0-9]/', '', $expectedOtp);
        while (strlen($expectedOtp) < OTP_LENGTH) {
            $expectedOtp .= '0';
        }
        if (hash_equals($expectedOtp, $otpCode)) {
            return true;
        }
    }
    return false;
}
