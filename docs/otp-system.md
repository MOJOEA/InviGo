# OTP System Documentation

## Overview

The OTP (One-Time Password) system supports two modes:
- **Database Mode**: Stores OTP codes in the database with expiration
- **Stateless Mode**: Generates deterministic OTPs using HMAC without database storage

## Configuration

Edit `includes/config.php`:

```php
const OTP_MODE = 'stateless';        // 'database' or 'stateless'
const OTP_LENGTH = 6;               // Number of digits
const OTP_EXPIRY_MINUTES = 30;        // Validity period
const OTP_EXPIRY_SECONDS = 1800;     // 30 minutes in seconds
const OTP_SECRET_KEY = 'your-secret-key-here';  // Required for stateless mode
```

## File Structure

```
includes/
├── helpers/
│   ├── otp.php          # OTP generation and verification functions
│   └── password.php     # Password hashing (separate from OTP)
├── config.php           # OTP configuration constants
databases/
└── otp.php              # Database functions for database mode
routes/
└── events/
    ├── otp.php          # Generate OTP endpoint
    └── manage.php       # Verify OTP endpoint
templates/
└── my_registrations_content.php  # Display OTP modal with QR code
```

## How It Works

### Database Mode
1. User requests OTP → Creates record in `Otp_Codes` table
2. OTP stored with expiration timestamp
3. Verification queries database for valid, unused OTP
4. Mark OTP as used after successful check-in

### Stateless Mode
1. OTP generated using HMAC-SHA256 hash of:
   - `registration_id:event_id:time_window`
   - Time window = current time / 30 minutes
2. Same inputs always produce same OTP within time window
3. Verification regenerates expected OTP and compares
4. No database storage needed

## Security Notes

- **Stateless Mode**: Keep `OTP_SECRET_KEY` secure and unique per deployment
- Time window tolerance: Checks current and previous window (grace period)
- OTP expires at end of time window, not from generation time

## Usage

### Generate OTP (Participant)
```php
// In routes/events/otp.php
if (OTP_MODE === 'stateless') {
    $otpData = generateStatelessOtp($registration['id'], $eventId);
    // Store in session, no DB write
} else {
    $otpCode = generateOTP(6);
    createOtp($registration['id'], $otpCode, $expiresAt);
}
```

### Verify OTP (Organizer)
```php
// In routes/events/manage.php
if (OTP_MODE === 'stateless') {
    // Find matching registration by verifying OTP
    foreach ($registrations as $reg) {
        if (verifyStatelessOtp($otpCode, $reg['id'], $eventId)) {
            checkInRegistration($reg['id']);
            break;
        }
    }
} else {
    $otpData = verifyOtp($otpCode, $eventId);
    if ($otpData) {
        markOtpUsed($otpData['id']);
        checkInRegistration($otpData['reg_id']);
    }
}
```

## QR Code Display

The OTP modal displays:
- QR Code (generated via API: `api.qrserver.com`)
- OTP code in large text
- Countdown timer

Located in: `templates/my_registrations_content.php`
