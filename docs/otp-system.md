# เอกสารระบบ OTP

## ภาพรวม

ระบบ OTP (One-Time Password) รองรับ 2 โหมด:
- **โหมด Database**: เก็บรหัส OTP ในฐานข้อมูลพร้อมเวลาหมดอายุ
- **โหมด Stateless**: สร้าง OTP แบบกำหนดได้ด้วย HMAC โดยไม่ต้องเก็บในฐานข้อมูล

## การตั้งค่า

แก้ไขที่ไฟล์ `includes/config.php`:

```php
const OTP_MODE = 'stateless';        // 'database' หรือ 'stateless'
const OTP_LENGTH = 6;               // จำนวนหลัก
const OTP_EXPIRY_MINUTES = 30;        // ระยะเวลาที่ใช้ได้
const OTP_EXPIRY_SECONDS = 1800;     // 30 นาทีเป็นวินาที
const OTP_SECRET_KEY = 'your-secret-key-here';  // จำเป็นสำหรับโหมด stateless
```

## โครงสร้างไฟล์

```
includes/
├── helpers/
│   ├── otp.php          # ฟังก์ชันสร้างและตรวจสอบ OTP
│   └── password.php     # การเข้ารหัสรหัสผ่าน (แยกจาก OTP)
├── config.php           # ค่าคงที่การตั้งค่า OTP
databases/
└── otp.php              # ฟังก์ชันฐานข้อมูลสำหรับโหมด database
routes/
└── events/
    ├── otp.php          # Endpoint สร้าง OTP
    └── manage.php       # Endpoint ตรวจสอบ OTP
templates/
└── my_registrations_content.php  # แสดง modal OTP พร้อม QR code
```

## วิธีทำงาน

### โหมด Database
1. ผู้ใช้ขอ OTP → สร้าง record ในตาราง `Otp_Codes`
2. OTP ถูกเก็บพร้อม timestamp หมดอายุ
3. ตรวจสอบโดย query ฐานข้อมูลหา OTP ที่ยังใช้ได้และไม่ถูกใช้แล้ว
4. Mark OTP ว่าใช้แล้วหลังเช็คอินสำเร็จ

### โหมด Stateless
1. OTP สร้างโดยใช้ HMAC-SHA256 hash ของ:
   - `registration_id:event_id:time_window`
   - Time window = เวลาปัจจุบัน / 30 นาที
2. ข้อมูลเดิมจะได้ OTP เดียวกันภายใน time window เดียวกัน
3. ตรวจสอบโดยสร้าง OTP ที่คาดหวังใหม่และเปรียบเทียบ
4. ไม่ต้องเก็บในฐานข้อมูล

## หมายเหตุด้านความปลอดภัย

- **โหมด Stateless**: เก็บ `OTP_SECRET_KEY` ให้ปลอดภัยและไม่ซ้ำกันในแต่ละ deployment
- การยอมรับ time window: ตรวจสอบทั้ง window ปัจจุบันและก่อนหน้า (grace period)
- OTP หมดอายุที่จบ time window ไม่ใช่จากเวลาที่สร้าง

## การใช้งาน

### สร้าง OTP (ฝั่งผู้เข้าร่วม)
```php
// ใน routes/events/otp.php
if (OTP_MODE === 'stateless') {
    $otpData = generateStatelessOtp($registration['id'], $eventId);
    // เก็บใน session ไม่ต้องเขียน DB
} else {
    $otpCode = generateOTP(6);
    createOtp($registration['id'], $otpCode, $expiresAt);
}
```

### ตรวจสอบ OTP (ฝั่งผู้จัด)
```php
// ใน routes/events/manage.php
if (OTP_MODE === 'stateless') {
    // หา registration ที่ตรงกันโดยตรวจสอบ OTP
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

## การแสดง QR Code

Modal OTP แสดง:
- QR Code (สร้างด้วย library: `qrcode.js`)
- รหัส OTP ตัวใหญ่
- นับถอยหลังเวลาหมดอายุ

อยู่ที่: `templates/my_registrations_content.php`
