# ความปลอดภัย (Security)

## ภาพรวมความปลอดภัย

InviGo ใช้มาตรฐานความปลอดภัยสำหรับเว็บแอปพลิเคชัน PHP สมัยใหม่

```
┌─────────────────────────────────────────┐
│         ระดับความปลอดภัย               │
├─────────────────────────────────────────┤
│ ✅ Authentication & Authorization     │
│ ✅ Data Validation & Sanitization       │
│ ✅ SQL Injection Prevention             │
│ ✅ XSS (Cross-Site Scripting)         │
│ ✅ CSRF Protection                      │
│ ✅ File Upload Security                 │
│ ✅ Session Security                     │
│ ✅ Password Security                    │
└─────────────────────────────────────────┘
```

---

## 🔐 Authentication & Authorization

### ระบบล็อกอิน

**Password Hashing:**
- ใช้ **bcrypt** (PASSWORD_DEFAULT)
- Cost factor: 10 (default)
- รหัสผ่านไม่มีวัน decode กลับ

```php
// สร้าง hash
$hash = password_hash($password, PASSWORD_DEFAULT);

// ตรวจสอบ
if (password_verify($input, $hash)) {
    // ถูกต้อง
}
```

**Session Management:**
- ใช้ PHP native sessions
- Session ID สร้างใหม่หลัง login (regenerate)
- Timeout ตาม php.ini

---

### การควบคุมการเข้าถึง

```php
// ต้องล็อกอิน
requireAuth();

// ห้ามล็อกอิน (สำหรับหน้า login)
requireGuest();

// ตรวจสอบเจ้าของกิจกรรม
if ($event['user_id'] !== getCurrentUserId()) {
    http_response_code(403);
    renderView('403');
    exit;
}
```

---

## 🛡️ Data Validation & Sanitization

### Input Validation

**ตรวจสอบทุกครั้ง:**
- อีเมล: `filter_var($email, FILTER_VALIDATE_EMAIL)`
- วันที่: `DateTime::createFromFormat()`
- ตัวเลข: `filter_var($number, FILTER_VALIDATE_INT)`
- ไฟล์: MIME type + Extension

```php
// ตัวอย่าง validation
$errors = [];

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors['email'] = 'อีเมลไม่ถูกต้อง';
}

if (strlen($password) < MIN_PASSWORD_LENGTH) {
    $errors['password'] = 'รหัสผ่านต้องมีอย่างน้อย ' . MIN_PASSWORD_LENGTH . ' ตัวอักษร';
}

if (!empty($errors)) {
    // ส่งกลับพร้อม errors
}
```

---

### Output Sanitization (XSS Prevention)

**ใช้ทุกครั้งที่แสดงข้อมูล:**

```php
// ❌ ไม่ปลอดภัย
echo $user['name'];

// ✅ ปลอดภัย
echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8');

// หรือใช้ helper
function sanitize(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

echo sanitize($user['name']);
```

**รูปแบบที่ป้องกัน:**
```html
<!-- ก่อน -->
<script>alert('hack')</script>

<!-- หลัง sanitize -->
&lt;script&gt;alert(&#039;hack&#039;)&lt;/script&gt;
```

---

## 🗄️ SQL Injection Prevention

### Prepared Statements

**ใช้ทุกครั้งที่ query มี user input:**

```php
// ❌ ไม่ปลอดภัย (String concatenation)
$query = "SELECT * FROM Users WHERE email = '$email'";

// ✅ ปลอดภัย (Prepared statement)
$stmt = $conn->prepare("SELECT * FROM Users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
```

**ทุก query ในระบบใช้ prepared statements:**

```php
// databases/user.php
function getUserById(int $id): ?array {
    $stmt = $conn->prepare("SELECT * FROM Users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}
```

---

## 📝 CSRF Protection

### Cross-Site Request Forgery

**อันตราย:** ผู้ใช้ถูกหลอกให้ทำ action โดยไม่รู้ตัว

**การป้องกันในระบบ:**
- ตรวจสอบ Referer
- ใช้ POST สำหรับ action ที่เปลี่ยนแปลงข้อมูล
- ใช้ same-site cookies

```php
// ตรวจสอบ Referer
if (isset($_SERVER['HTTP_REFERER'])) {
    $referer = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
    $current = $_SERVER['HTTP_HOST'];
    if ($referer !== $current) {
        // อาจเป็น CSRF
        http_response_code(403);
        exit;
    }
}
```

**Action ที่ต้องใช้ POST:**
- ลบกิจกรรม
- อนุมัติ/ปฏิเสธลงทะเบียน
- อัปเดตสถานะ

---

## 📤 File Upload Security

### การตรวจสอบไฟล์

```php
// 1. ตรวจสอบขนาด
if ($file['size'] > MAX_UPLOAD_SIZE_BYTES) {
    return 'ไฟล์ใหญ่เกินไป';
}

// 2. ตรวจสอบ MIME type
$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
if (!in_array($file['type'], $allowed)) {
    return 'ประเภทไฟล์ไม่ถูกต้อง';
}

// 3. ตรวจสอบ Extension
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
    return 'นามสกุลไฟล์ไม่ถูกต้อง';
}

// 4. สร้างชื่อไฟล์ใหม่ (random)
$newFilename = bin2hex(random_bytes(16)) . '.' . $ext;
```

### การเก็บไฟล์

```php
// เก็บนอก public_html (ดีที่สุด)
// หรือใน public/uploads/ แต่ป้องกัน execution

// ป้องกัน .htaccess
$uploadDir = 'public/uploads/events/';
```

---

## 🍪 Session Security

### การตั้งค่า

```php
// php.ini หรือ runtime
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1); // HTTPS only
ini_set('session.cookie_samesite', 'Strict');
ini_set('session.use_strict_mode', 1);
```

### Best Practices

```php
// สร้าง session ID ใหม่หลัง login
session_regenerate_id(true);

// เก็บข้อมูลผู้ใช้ใน session (ไม่ใช่ password)
$_SESSION['user_id'] = $user['id'];
$_SESSION['user_name'] = $user['name'];

// ล้าง session หลัง logout
$_SESSION = [];
session_destroy();
```

---

## 🔒 Password Security

### นโยบายรหัสผ่าน

```php
const MIN_PASSWORD_LENGTH = 6;

function validatePassword(string $password): array {
    $errors = [];
    
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        $errors[] = 'ต้องมีอย่างน้อย ' . MIN_PASSWORD_LENGTH . ' ตัวอักษร';
    }
    
    return $errors;
}
```

### Password Hashing

```php
// ใช้ bcrypt (แนะนำ)
$hash = password_hash($password, PASSWORD_DEFAULT);

// ตรวจสอบต้องใช้เวลา ~100ms (ป้องกัน brute force)
```

---

## 🚫 การป้องกัน Brute Force

### Rate Limiting (แนะนำเพิ่ม)

```php
// จำกัดจำนวน login attempts
if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
    $_SESSION['last_attempt'] = time();
}

if ($_SESSION['login_attempts'] >= 5) {
    if (time() - $_SESSION['last_attempt'] < 300) {
        // ล็อก 5 นาที
        $errors[] = 'ล็อกอินผิดพลาดหลายครั้ง กรุณารอ 5 นาที';
    } else {
        // Reset
        $_SESSION['login_attempts'] = 0;
    }
}
```

---

## 🛡️ Security Headers

### แนะนำเพิ่มใน .htaccess

```apache
# XSS Protection
Header always set X-Content-Type-Options "nosniff"
Header always set X-Frame-Options "SAMEORIGIN"
Header always set X-XSS-Protection "1; mode=block"

# Content Security Policy
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' cdn.tailwindcss.com; style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.tailwindcss.com; font-src 'self' fonts.gstatic.com; img-src 'self' data: https://api.dicebear.com;"

# HTTPS
Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"
```

---

## 🔍 Security Checklist

### ก่อน Deploy

- [ ] เปลี่ยน default passwords ทั้งหมด
- [ ] ปิด debug mode (APP_DEBUG=false)
- [ ] ใช้ HTTPS only
- [ ] ตั้งค่า security headers
- [ ] ตรวจสอบ file permissions
- [ ] สำรองข้อมูล
- [ ] อัปเดต dependencies

### การตรวจสอบประจำ

- [ ] ตรวจสอบ logs ผิดปกติ
- [ ] อัปเดต Docker images
- [ ] ตรวจสอบ file uploads
- [ ] ตรวจสอบ SQL queries
- [ ] ทดสอบ penetration testing

---

## 🚨 การตอบสนองต่อเหตุการณ์

### ถ้าพบช่องโหว่

1. **ประเมิน** - ความรุนแรงของช่องโหว่
2. **แก้ไข** - ปิดช่องโหว่ทันที
3. **ตรวจสอบ** - ข้อมูลที่อาจรั่วไหล
4. **แจ้ง** - ผู้ใช้ที่ได้รับผลกระทบ
5. **ป้องกัน** - ไม่ให้เกิดซ้ำ

### การรายงานปัญหา

แจ้งผ่าน GitHub Issues พร้อมข้อมูล:
- รายละเอียดช่องโหว่
- ขั้นตอนการ reproduce
- ผลกระทบ
- ข้อมูลติดต่อ

---

## ข้อมูลเพิ่มเติม

### OWASP Top 10 ที่ป้องกันแล้ว

| อันดับ | ช่องโหว่ | สถานะ |
|--------|----------|-------|
| 1 | Injection (SQL) | ✅ ป้องกัน |
| 2 | Broken Authentication | ✅ ป้องกัน |
| 3 | Sensitive Data Exposure | ✅ ป้องกัน |
| 4 | XXE | N/A |
| 5 | Broken Access Control | ✅ ป้องกัน |
| 6 | Security Misconfiguration | ⚠️ ต้องตั้งค่า |
| 7 | XSS | ✅ ป้องกัน |
| 8 | Insecure Deserialization | N/A |
| 9 | Known Vulnerabilities | ⚠️ ต้องอัปเดต |
| 10 | Insufficient Logging | ⚠️ แนะนำเพิ่ม |

---

**อ้างอิง:**
- [OWASP Cheat Sheet](https://cheatsheetseries.owasp.org/)
- [PHP Security Best Practices](https://phpsecurity.readthedocs.io/)
