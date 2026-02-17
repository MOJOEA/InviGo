# Routes & API Endpoints

## ภาพรวม Routing

ระบบใช้ Router แบบ Front Controller Pattern โดยมี `public/index.php` เป็นจุดเริ่มต้น และแยกตาม URL pattern

## โครงสร้าง URL

```
http://localhost:8888/{route}

ตัวอย่าง:
- /explore         → สำรวจกิจกรรม
- /events/5        → ดูกิจกรรมที่ 5
- /events/5/manage → จัดการกิจกรรมที่ 5
```

## Routes สาธารณะ (ไม่ต้องล็อกอิน)

| Method | Route | ไฟล์ | คำอธิบาย |
|--------|-------|------|---------|
| GET | `/` | `routes/explore.php` | ไปหน้าสำรวจ |
| GET | `/explore` | `routes/explore.php` | ดูกิจกรรมทั้งหมด |
| GET/POST | `/login` | `routes/login.php` | เข้าสู่ระบบ |
| GET/POST | `/register` | `routes/register.php` | สมัครสมาชิก |
| GET | `/events/{id}` | `routes/events/detail.php` | ดูรายละเอียดกิจกรรม |

### Parameters สำหรับ /explore

```
/explore?search=คำค้นหา&start_date=2024-02-01&end_date=2024-02-28
```

| Parameter | ประเภท | คำอธิบาย |
|-----------|--------|---------|
| `search` | string | ค้นหาชื่อหรือรายละเอียด |
| `start_date` | date | วันเริ่มต้น (YYYY-MM-DD) |
| `end_date` | date | วันสิ้นสุด (YYYY-MM-DD) |

---

## Routes ที่ต้องล็อกอิน (requireAuth)

### การจัดการบัญชี

| Method | Route | ไฟล์ | คำอธิบาย |
|--------|-------|------|---------|
| GET | `/logout` | `routes/logout.php` | ออกจากระบบ |
| GET | `/profile` | `routes/profile/index.php` | ดูโปรไฟล์ |
| GET/POST | `/profile/edit` | `routes/profile/edit.php` | แก้ไขโปรไฟล์ |

### กิจกรรมของฉัน

| Method | Route | ไฟล์ | คำอธิบาย |
|--------|-------|------|---------|
| GET | `/my-events` | `routes/my-events.php` | กิจกรรมที่สร้าง |
| GET | `/my-registrations` | `routes/my-registrations.php` | กิจกรรมที่ลงทะเบียน |

### จัดการกิจกรรม

| Method | Route | ไฟล์ | คำอธิบาย |
|--------|-------|------|---------|
| GET/POST | `/events/create` | `routes/events/create.php` | สร้างกิจกรรม |
| GET/POST | `/events/{id}/edit` | `routes/events/edit.php` | แก้ไขกิจกรรม |
| POST | `/events/{id}/delete` | `routes/events/delete.php` | ลบกิจกรรม |
| GET | `/events/{id}/manage` | `routes/events/manage.php` | แดชบอร์ดจัดการ |

### การลงทะเบียน

| Method | Route | ไฟล์ | คำอธิบาย |
|--------|-------|------|---------|
| GET | `/events/{id}/join` | `routes/events/join.php` | ลงทะเบียน |
| GET | `/events/{id}/withdraw` | `routes/events/withdraw.php` | ยกเลิกลงทะเบียน |

### OTP Check-in

| Method | Route | ไฟล์ | คำอธิบาย |
|--------|-------|------|---------|
| GET | `/events/{id}/otp` | `routes/events/otp.php` | สร้าง OTP |
| POST | `/events/{id}/checkin` | `routes/events/checkin.php` | เช็คอิน (ผู้จัด) |

---

## รายละเอียด Routes

### 🔐 Authentication Routes

#### POST /login
**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
}
```

**Responses:**
- 302 → /explore (สำเร็จ)
- 200 + error message (ล้มเหลว)

#### POST /register
**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123",
  "name": "สมชาย ใจดี",
  "birth_date": "2000-01-01",
  "gender": "male"
}
```

**Validation:**
- อีเมลต้องไม่ซ้ำ
- รหัสผ่าน ≥ 6 ตัวอักษร
- อายุ 10-120 ปี

---

### 📅 Event Routes

#### GET /explore
**Query Parameters:**
```
GET /explore?search=กีฬา&start_date=2024-03-01&end_date=2024-03-31
```

**Response Data:**
```php
[
  'events' => [...],
  'search' => 'กีฬา',
  'startDate' => '2024-03-01',
  'endDate' => '2024-03-31',
  'activePage' => 'explore'
]
```

#### POST /events/create
**Request Body (multipart/form-data):**
```
title: ชื่อกิจกรรม
description: รายละเอียด
event_date: 2024-03-15 09:00:00
end_date: 2024-03-15 12:00:00
location: สถานที่
max_participants: 50
images[]: [ไฟล์รูป1, ไฟล์รูป2]
```

**Validation:**
- ชื่อ: ต้องกรอก
- วันที่: ต้องเป็นอนาคต
- end_date: ต้องหลัง event_date
- รูป: สูงสุด 2MB, JPG/PNG/GIF/WebP

#### GET /events/{id}/manage
**Parameters:**
- `id` (int): รหัสกิจกรรม

**Authorization:**
- ต้องเป็นเจ้าของกิจกรรมเท่านั้น

**Response Data:**
```php
[
  'event' => [...],
  'registrations' => [...],
  'stats' => [
    'total' => 50,
    'approved' => 30,
    'pending' => 15,
    'rejected' => 5,
    'gender' => [...],
    'age' => [...]
  ],
  'activePage' => 'my-events'
]
```

---

### 📝 Registration Routes

#### GET /events/{id}/join
**Flow:**
1. ตรวจสอบ login
2. ตรวจสอบว่าไม่ใช่เจ้าของ
3. ตรวจสอบว่ายังไม่เต็ม
4. สร้าง registration สถานะ `pending`
5. Redirect กลับไปหน้ากิจกรรม

#### GET /events/{id}/withdraw
**Flow:**
1. ตรวจสอบ login
2. ค้นหา registration
3. ลบออกจาก database
4. Redirect กลับไปหน้าการลงทะเบียน

---

### 🔐 OTP Routes

#### GET /events/{id}/otp
**Authorization:**
- ต้องเป็นผู้ลงทะเบียนที่ได้รับอนุมัติ

**Response:**
```php
[
  'otp_code' => '123456',
  'expires_at' => '2024-03-15 10:30:00',
  'event' => [...],
  'activePage' => 'my-registrations'
]
```

#### POST /events/{id}/checkin
**Request Body:**
```json
{
  "otp_code": "123456",
  "registration_id": 42
}
```

**Authorization:**
- ต้องเป็นเจ้าของกิจกรรม

**Validation:**
- OTP ต้องตรง
- ต้องยังไม่หมดอายุ
- ต้องยังไม่ถูกใช้

**Response:**
- 302 → /events/{id}/manage พร้อม flash message

---

## Error Responses

### 401 Unauthorized
```
Location: /login
Flash: "กรุณาเข้าสู่ระบบก่อน"
```

### 403 Forbidden
```
HTTP/1.1 403 Forbidden
Template: 403.php
```

### 404 Not Found
```
HTTP/1.1 404 Not Found
Template: 404.php
```

### 405 Method Not Allowed
```
HTTP/1.1 405 Method Not Allowed
Flash: "Method ไม่ถูกต้อง"
```

---

## Route Parameters

### Pattern Matching

```php
// router.php
$routes = [
    '/events/(\d+)' => 'events/detail.php',           // {id} = number
    '/events/(\d+)/edit' => 'events/edit.php',        // {id} = number
    '/events/(\d+)/manage' => 'events/manage.php',    // {id} = number
];

// ดึงค่า id
$id = (int)$matches[1];
```

---

## Flash Messages

ระบบใช้ Session สำหรับข้อความแจ้งเตือน:

```php
// ตั้งค่า
setFlashMessage('success', 'ลงทะเบียนสำเร็จ!');
setFlashMessage('error', 'เกิดข้อผิดพลาด');

// ดึงค่า (จะลบหลังแสดงครั้งแรก)
$flash = getFlashMessage();
// ['type' => 'success', 'message' => 'ลงทะเบียนสำเร็จ!']
```

---

## URL Rewriting (.htaccess)

```apache
RewriteEngine On

# ถ้าไม่ใช่ไฟล์/โฟลเดอร์จริง
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d

# ส่งไป index.php
RewriteRule ^(.*)$ index.php [QSA,L]
```

---

## การเพิ่ม Route ใหม่

### ขั้นตอน

1. **สร้างไฟล์ Route**
```php
// routes/new-feature.php
<?php
declare(strict_types=1);
requireAuth(); // ถ้าต้องการ

// Logic ที่นี่
$data = [...];
renderView('new_feature_content', $data);
```

2. **เพิ่มใน Router**
```php
// includes/router.php
case '/new-feature':
    require ROUTE_DIR . '/new-feature.php';
    break;
```

3. **สร้าง Template**
```php
// templates/new_feature_content.php
<div class="neo-box">
    <h1>ฟีเจอร์ใหม่</h1>
</div>
```

---

## ตารางสรุป Routes ทั้งหมด

| หมวดหมู่ | จำนวน | Routes |
|---------|-------|--------|
| Public | 4 | /, /explore, /login, /register |
| Account | 3 | /logout, /profile, /profile/edit |
| My Content | 2 | /my-events, /my-registrations |
| Event CRUD | 4 | /events/create, /edit, /delete, /{id} |
| Event Manage | 1 | /events/{id}/manage |
| Registration | 2 | /events/{id}/join, /withdraw |
| OTP | 2 | /events/{id}/otp, /checkin |
| **รวม** | **18** | - |
