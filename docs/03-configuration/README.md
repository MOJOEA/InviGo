# การตั้งค่า (Configuration)

## ไฟล์ config.php

ไฟล์หลักสำหรับเก็บค่าคงที่และการตั้งค่าระบบทั้งหมด

### ตำแหน่งไฟล์
```
includes/config.php
```

## รายการค่าคงที่

### 📁 Paths - เส้นทางโฟลเดอร์

```php
const INCLUDES_DIR = __DIR__;
const ROUTE_DIR = __DIR__ . '/../routes';
const TEMPLATES_DIR = __DIR__ . '/../templates';
const DATABASES_DIR = __DIR__ . '/../databases';
```

| ค่าคงที่ | คำอธิบาย | ตัวอย่างค่า |
|---------|---------|------------|
| `INCLUDES_DIR` | โฟลเดอร์ includes | `/var/www/html/includes` |
| `ROUTE_DIR` | โฟลเดอร์ routes | `/var/www/html/routes` |
| `TEMPLATES_DIR` | โฟลเดอร์ templates | `/var/www/html/templates` |
| `DATABASES_DIR` | โฟลเดอร์ databases | `/var/www/html/databases` |

**การใช้งาน:**
```php
require_once INCLUDES_DIR . '/database.php';
require_once ROUTE_DIR . '/login.php';
```

---

### 🔓 Security - ความปลอดภัย

```php
const PUBLIC_ROUTES = ['/', '/login', '/register', '/explore'];
const ALLOW_METHODS = ['GET', 'POST'];
const INDEX_URI = '';
const INDEX_ROUTE = 'explore';
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `PUBLIC_ROUTES` | Routes ที่ไม่ต้องล็อกอิน | `['/explore', '/login', '/register']` |
| `ALLOW_METHODS` | HTTP Methods ที่อนุญาต | `['GET', 'POST']` |
| `INDEX_ROUTE` | Route เริ่มต้น | `'explore'` |

**การใช้งานใน Router:**
```php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (!in_array($currentPath, PUBLIC_ROUTES) && !isLoggedIn()) {
    header('Location: /login');
    exit;
}
```

---

### 👤 Age Validation - การตรวจสอบอายุ

```php
const MIN_AGE = 10;
const MAX_AGE = 120;
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `MIN_AGE` | อายุขั้นต่ำ | 10 ปี |
| `MAX_AGE` | อายุสูงสุด | 120 ปี |

**การใช้งาน:**
```php
if ($age < MIN_AGE) {
    $errors['birth_date'] = 'อายุต้องไม่ต่ำกว่า ' . MIN_AGE . ' ปี';
}
```

---

### 🔐 OTP Settings - การตั้งค่า OTP

```php
const OTP_LENGTH = 6;
const OTP_EXPIRY_MINUTES = 30;
const OTP_EXPIRY_SECONDS = 1800; // 30 * 60
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `OTP_LENGTH` | จำนวนตัวเลข | 6 หลัก |
| `OTP_EXPIRY_MINUTES` | อายุ (นาที) | 30 นาที |
| `OTP_EXPIRY_SECONDS` | อายุ (วินาที) | 1800 วินาที |

**การใช้งาน:**
```php
$otpCode = generateOTP(OTP_LENGTH);
$expiresAt = date('Y-m-d H:i:s', strtotime('+' . OTP_EXPIRY_MINUTES . ' minutes'));
```

---

### 📤 Upload Settings - การตั้งค่าอัปโหลด

```php
const MAX_UPLOAD_SIZE_MB = 2;
const MAX_UPLOAD_SIZE_BYTES = 2097152; // 2 * 1024 * 1024
const ALLOWED_IMAGE_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `MAX_UPLOAD_SIZE_MB` | ขนาดสูงสุด (MB) | 2 MB |
| `MAX_UPLOAD_SIZE_BYTES` | ขนาดสูงสุด (bytes) | 2097152 |
| `ALLOWED_IMAGE_TYPES` | ประเภทไฟล์ที่อนุญาต | JPG, PNG, GIF, WebP |

**การใช้งาน:**
```php
if ($file['size'] > MAX_UPLOAD_SIZE_BYTES) {
    return 'ขนาดไฟล์ต้องไม่เกิน ' . MAX_UPLOAD_SIZE_MB . 'MB';
}

if (!in_array($file['type'], ALLOWED_IMAGE_TYPES)) {
    return 'รองรับเฉพาะ ' . implode(', ', ALLOWED_IMAGE_TYPES);
}
```

---

### 🔑 Password Policy - นโยบายรหัสผ่าน

```php
const MIN_PASSWORD_LENGTH = 6;
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `MIN_PASSWORD_LENGTH` | ความยาวขั้นต่ำ | 6 ตัวอักษร |

**การใช้งาน:**
```php
if (strlen($password) < MIN_PASSWORD_LENGTH) {
    $errors['password'] = 'รหัสผ่านต้องมีอย่างน้อย ' . MIN_PASSWORD_LENGTH . ' ตัวอักษร';
}
```

---

### ⚧ Gender Values - ค่าเพศ

```php
const GENDER_MALE = 'male';
const GENDER_FEMALE = 'female';
const GENDER_OTHER = 'other';
const GENDER_LABELS = [
    GENDER_MALE => 'ชาย',
    GENDER_FEMALE => 'หญิง',
    GENDER_OTHER => 'อื่นๆ'
];
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `GENDER_MALE` | ค่าชาย | `'male'` |
| `GENDER_FEMALE` | ค่าหญิง | `'female'` |
| `GENDER_OTHER` | ค่าอื่นๆ | `'other'` |
| `GENDER_LABELS` | แผนที่ค่า→ชื่อ | `['male'=>'ชาย', ...]` |

**การใช้งาน:**
```php
$genderLabel = GENDER_LABELS[$user['gender']] ?? 'ไม่ระบุ';
```

---

### 📊 Registration Status - สถานะการลงทะเบียน

```php
const STATUS_PENDING = 'pending';
const STATUS_APPROVED = 'approved';
const STATUS_REJECTED = 'rejected';
```

| ค่าคงที่ | คำอธิบาย | ค่า |
|---------|---------|-----|
| `STATUS_PENDING` | รออนุมัติ | `'pending'` |
| `STATUS_APPROVED` | อนุมัติแล้ว | `'approved'` |
| `STATUS_REJECTED` | ปฏิเสธ | `'rejected'` |

**การใช้งาน:**
```php
if ($registration['status'] === STATUS_APPROVED) {
    showOtpButton();
}
```

---

### 🎨 Brand Colors - สีประจำแบรนด์

```php
const COLOR_PRIMARY = '#FFE600';     // สีเหลือง
const COLOR_SECONDARY = '#D4FF33';   // สีเขียวมะนาว
const COLOR_ACCENT = '#40E0D0';     // สีฟ้า
const COLOR_SUCCESS = '#22c55e';     // สีเขียวสำเร็จ
const COLOR_WARNING = '#eab308';     // สีเหลืองเตือน
const COLOR_DANGER = '#ef4444';      // สีแดงอันตราย
const COLOR_BG = '#FFFBF0';          // สีพื้นหลังครีม
```

| ค่าคงที่ | สี | ใช้สำหรับ |
|---------|-----|----------|
| `COLOR_PRIMARY` | `#FFE600` (เหลือง) | ปุ่มหลัก, ไฮไลท์ |
| `COLOR_SECONDARY` | `#D4FF33` (เขียวมะนาว) | ปุ่มรอง, OTP |
| `COLOR_ACCENT` | `#40E0D0` (ฟ้า) | ลิงก์, การกระทำ |
| `COLOR_SUCCESS` | `#22c55e` (เขียว) | สำเร็จ, อนุมัติ |
| `COLOR_WARNING` | `#eab308` (เหลือง) | เตือน, รอดำเนินการ |
| `COLOR_DANGER` | `#ef4444` (แดง) | อันตราย, ลบ, ปฏิเสธ |
| `COLOR_BG` | `#FFFBF0` (ครีม) | พื้นหลังหน้า |

**การใช้งานใน CSS:**
```css
.btn-primary {
    background-color: <?= COLOR_PRIMARY ?>;
}
.status-approved {
    background-color: <?= COLOR_SUCCESS ?>;
}
```

---

## การเพิ่มค่าคงที่ใหม่

### ขั้นตอน
1. เพิ่มใน `includes/config.php`
2. ใช้ตัวพิมพ์ใหญ่ทั้งหมด
3. คอมเมนต์อธิบาย
4. อัปเดตเอกสาร

### ตัวอย่าง
```php
// === New Feature Settings ===
const FEATURE_ENABLED = true;
const MAX_SOMETHING = 100;
```

---

## การตั้งค่า Docker Environment

### ไฟล์ docker-compose.yml

```yaml
version: '3.8'
services:
  php:
    build: .
    ports:
      - "8888:80"          # แก้ไขพอร์ตได้
    volumes:
      - .:/var/www/html
  
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_ROOT_PASSWORD: rootpassword    # เปลี่ยนใน production
      MYSQL_DATABASE: invigo
      MYSQL_USER: invigo
      MYSQL_PASSWORD: invigopass           # เปลี่ยนใน production
    ports:
      - "3307:3306"        # แก้ไขพอร์ตได้
  
  phpmyadmin:
    image: phpmyadmin/phpmyadmin
    ports:
      - "8080:80"          # แก้ไขพอร์ตได้
```

### ไฟล์ .env (สร้างเองถ้าต้องการ)

```env
DB_HOST=mysql
DB_PORT=3306
DB_NAME=invigo
DB_USER=invigo
DB_PASS=invigopass

APP_URL=http://localhost:8888
APP_ENV=development
```

---

## การตั้งค่า PHP (php.ini)

### ค่าที่แนะนำ

```ini
; อัปโหลดไฟล์
upload_max_filesize = 2M
post_max_size = 8M
max_file_uploads = 20

; เซสชัน
session.gc_maxlifetime = 14400
session.cookie_lifetime = 0

; ข้อผิดพลาด (development)
display_errors = On
error_reporting = E_ALL

; โซนเวลา
date.timezone = Asia/Bangkok
```

---

## การตั้งค่า Apache (.htaccess)

### ไฟล์ public/.htaccess

```apache
# เปิดใช้ mod_rewrite
RewriteEngine On

# ถ้าไม่ใช่ไฟล์หรือโฟลเดอร์ที่มีอยู่จริง
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# ส่งทุกอย่างไปที่ index.php
RewriteRule ^(.*)$ index.php [QSA,L]

# ป้องกันการเข้าถึงโฟลเดอร์อื่น
RewriteRule ^(includes|databases|routes|templates)/ - [F,L]
```

---

## ตารางสรุปค่าคงที่ทั้งหมด

| หมวดหมู่ | จำนวน | รายละเอียด |
|---------|-------|-----------|
| Paths | 4 | เส้นทางโฟลเดอร์ |
| Security | 4 | ความปลอดภัย |
| Age | 2 | อายุ |
| OTP | 3 | รหัส OTP |
| Upload | 3 | อัปโหลดไฟล์ |
| Password | 1 | รหัสผ่าน |
| Gender | 4 | เพศ |
| Status | 3 | สถานะ |
| Colors | 7 | สี |
| **รวม** | **31** | - |
