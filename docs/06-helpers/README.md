# Helper Functions

## ภาพรวม Helpers

ฟังก์ชันอรรถประโยชน์ที่ใช้ซ้ำทั่วระบบ แยกตามหมวดหมู่ใน `includes/helpers/`

```
includes/helpers/
├── auth.php        # ตรวจสอบสิทธิ์
├── date.php        # จัดการวันที่
├── flash.php       # ข้อความแจ้งเตือน
├── format.php      # จัดรูปแบบข้อมูล
├── password.php    # รหัสผ่าน
└── sanitize.php    # ทำความสะอาดข้อมูล
```

---

## 🔐 auth.php - การตรวจสอบสิทธิ์

### getCurrentUser(): ?array

**คำอธิบาย:** ดึงข้อมูลผู้ใช้ที่ล็อกอินอยู่  
**Return:** Array ข้อมูลผู้ใช้ หรือ null ถ้าไม่ได้ล็อกอิน

```php
$user = getCurrentUser();
if ($user) {
    echo "สวัสดี " . $user['name'];
}
```

**ข้อมูลที่ return:**
```php
[
  'id' => 1,
  'email' => 'user@example.com',
  'name' => 'สมชาย',
  'birth_date' => '2000-01-01',
  'gender' => 'male',
  'profile_image' => '...',
  'created_at' => '...'
]
```

---

### getCurrentUserId(): ?int

**คำอธิบาย:** ดึง ID ผู้ใช้ที่ล็อกอิน  
**Return:** int (user_id) หรือ null

```php
$userId = getCurrentUserId() ?? 0;
```

---

### requireAuth(): void

**คำอธิบาย:** บังคับให้ล็อกอิน ถ้าไม่ได้ล็อกอินจะ redirect ไปหน้า login  
**ใช้ใน:** Routes ที่ต้องการ login

```php
<?php
declare(strict_types=1);
requireAuth();  // ถ้าไม่ได้ล็อกอิน → redirect /login

// ต่อถ้าล็อกอินแล้ว
$userId = getCurrentUserId();
```

**ที่ใช้:**
- /my-events
- /my-registrations
- /profile
- /events/create
- /events/{id}/manage

---

### requireGuest(): void

**คำอธิบาย:** บังคับให้ยังไม่ล็อกอิน ถ้าล็อกอินแล้วจะ redirect ไปหน้าหลัก  
**ใช้ใน:** หน้า login/register (ป้องกัน logged-in user เข้า)

```php
<?php
requireGuest();  // ถ้าล็อกอินแล้ว → redirect /explore

// แสดงฟอร์ม login
```

---

### isLoggedIn(): bool

**คำอธิบาย:** ตรวจสอบว่าล็อกอินอยู่หรือไม่  
**Return:** true/false

```php
<?php if (isLoggedIn()): ?>
    <a href="/logout">ออกจากระบบ</a>
<?php else: ?>
    <a href="/login">เข้าสู่ระบบ</a>
<?php endif; ?>
```

---

## 📅 date.php - จัดการวันที่

### formatThaiDate(string $date): string

**คำอธิบาย:** แปลงวันที่เป็นรูปแบบไทย  
**Input:** YYYY-MM-DD  
**Output:** วันที่ภาษาไทย

```php
formatThaiDate('2024-03-15');
// ผลลัพธ์: "15 มีนาคม 2567"

formatThaiDate('2024-03-15', short: true);
// ผลลัพธ์: "15 มี.ค. 67"
```

---

### formatThaiDateTime(string $datetime): string

**คำอธิบาย:** แปลงวัน-เวลาเป็นรูปแบบไทย  
**Input:** YYYY-MM-DD HH:MM:SS  
**Output:** วัน-เวลาภาษาไทย

```php
formatThaiDateTime('2024-03-15 09:30:00');
// ผลลัพธ์: "15 มีนาคม 2567 เวลา 09:30 น."
```

---

### calculateAge(string $birthDate): int

**คำอธิบาย:** คำนวณอายุจากวันเกิด  
**Input:** YYYY-MM-DD

```php
calculateAge('2000-06-15');
// ผลลัพธ์: 23 (ปี 2024)
```

**ใช้ใน:**
- แสดงอายุในหน้าโปรไฟล์
- ตรวจสอบอายุขั้นต่ำ (MIN_AGE, MAX_AGE)

---

### formatShortDate(string $date): string

**คำอธิบาย:** แสดงวันที่แบบสั้น  
**Output:** 15 มี.ค.

```php
formatShortDate('2024-03-15');
// ผลลัพธ์: "15 มี.ค."
```

---

### isPastEvent(string $date, ?string $endDate = null): bool

**คำอธิบาย:** ตรวจสอบว่ากิจกรรมหมดเวลาแล้วหรือยัง  
**Logic:** ใช้ end_date ถ้ามี ไม่มีใช้ event_date

```php
isPastEvent('2024-03-15', '2024-03-15 12:00:00');
// ตรวจสอบกับ 12:00

isPastEvent('2024-03-15');
// ตรวจสอบกับ 00:00 ของวันที่ 15
```

---

## ⚡ flash.php - ข้อความแจ้งเตือน

### setFlashMessage(string $type, string $message): void

**คำอธิบาย:** ตั้งค่าข้อความแจ้งเตือนแบบชั่วคราว (Session)  
**Type:** success, error, warning, info

```php
setFlashMessage('success', 'ลงทะเบียนสำเร็จ!');
setFlashMessage('error', 'เกิดข้อผิดพลาด');
setFlashMessage('warning', 'กรุณาตรวจสอบข้อมูล');
```

**ที่ใช้:**
- หลัง create/update/delete
- แจ้งผลการทำงาน

---

### getFlashMessage(): ?array

**คำอธิบาย:** ดึงข้อความแจ้งเตือนและลบออกจาก Session  
**Return:** ['type' => 'success', 'message' => '...'] หรือ null

```php
$flash = getFlashMessage();
if ($flash) {
    // แสดง toast notification
    echo "<div class='toast-{$flash['type']}'>{$flash['message']}</div>";
}
// ครั้งถัดไป getFlashMessage() จะ return null
```

**ลักษณะพิเศษ:** Flash message จะหายไปหลังแสดงครั้งแรก (อ่านครั้งเดียว)

---

## 🔒 password.php - รหัสผ่าน

### hashPassword(string $password): string

**คำอธิบาย:** เข้ารหัสลับรหัสผ่านด้วย bcrypt  
**Algorithm:** PASSWORD_DEFAULT (bcrypt)

```php
$hashed = hashPassword('myPassword123');
// ผลลัพธ์: $2y$10$... (60 ตัวอักษร)
```

---

### verifyPassword(string $password, string $hash): bool

**คำอธิบาย:** ตรวจสอบรหัสผ่านกับ hash  
**Return:** true (ถูกต้อง) / false (ผิด)

```php
if (verifyPassword('myPassword123', $user['password'])) {
    // รหัสผ่านถูกต้อง → ล็อกอิน
} else {
    // รหัสผ่านผิด → แจ้ง error
}
```

**ใช้ใน:** routes/login.php

---

## 🧼 sanitize.php - ทำความสะอาดข้อมูล

### sanitize(string $text): string

**คำอธิบาย:** ป้องกัน XSS โดยแปลง special characters  
**ฟังก์ชัน:** htmlspecialchars()

```php
sanitize('<script>alert("hack")</script>');
// ผลลัพธ์: &lt;script&gt;alert(&quot;hack&quot;)&lt;/script&gt;

// ใช้ในแสดงผล
echo sanitize($user['name']);
echo sanitize($event['title']);
```

**ต้องใช้เสมอเมื่อ:**
- แสดงข้อมูลจาก Database
- แสดงข้อมูลที่ผู้ใช้กรอก
- Output ข้อความใดๆ ที่มาจากภายนอก

---

### sanitizeArray(array $data): array

**คำอธิบาย:** Sanitize ทุกค่าใน array

```php
$clean = sanitizeArray($_POST);
// ทุกค่าใน $_POST ถูก sanitize แล้ว
```

---

## 📐 format.php - จัดรูปแบบ

### formatNumber(int $number): string

**คำอธิบาย:** จัดรูปแบบตัวเลข (ใส่ comma)

```php
formatNumber(1000000);
// ผลลัพธ์: "1,000,000"
```

---

### truncate(string $text, int $length = 50): string

**คำอธิบาย:** ตัดข้อความถ้ายาวเกิน

```php
truncate('นี่คือข้อความที่ยาวมาก...', 20);
// ผลลัพธ์: "นี่คือข้อความที่ยา..."
```

---

## ตารางสรุป Helpers

| Helper | จำนวนฟังก์ชัน | ใช้บ่อยที่สุด |
|--------|--------------|---------------|
| auth | 5 | getCurrentUser(), requireAuth() |
| date | 5 | formatThaiDate(), calculateAge() |
| flash | 2 | setFlashMessage(), getFlashMessage() |
| password | 2 | hashPassword(), verifyPassword() |
| sanitize | 1 | sanitize() |
| format | 2 | formatNumber(), truncate() |
| **รวม** | **17** | - |

---

## การสร้าง Helper ใหม่

### ขั้นตอน

1. **สร้างไฟล์**
```php
// includes/helpers/new.php
<?php
declare(strict_types=1);

function myHelper(string $param): string {
    // Logic
    return $result;
}
```

2. **โหลดในไฟล์ที่ใช้**
```php
require_once INCLUDES_DIR . '/helpers/new.php';

$result = myHelper('test');
```

3. **หรือโหลดอัตโนมัติ (ถ้ามี autoloader)**

---

## Best Practices

### ✅ ควรทำ
- ใช้ sanitize() ทุกครั้งที่แสดงข้อมูล
- ใช้ flash message แจ้งผลการทำงาน
- ใช้ helper แทนเขียนโค้ดซ้ำ

### ❌ ไม่ควรทำ
- แสดงข้อมูลโดยไม่ sanitize
- เก็บรหัสผ่าน plain text
- ใช้ requireAuth() ใน template (ควรใช้ใน route)
