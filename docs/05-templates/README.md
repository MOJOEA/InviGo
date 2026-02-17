# Templates (Views)

## ภาพรวม Template System

ระบบใช้ Template Rendering แบบง่ายโดยแยก Layout, Content และ Partials

### การทำงาน

```
Route → renderView() → layout.php + [content_template] + partials
```

### ฟังก์ชันหลัก

```php
// includes/view.php
function renderView(string $view, array $data = []): void {
    extract($data);
    require TEMPLATES_DIR . "/{$view}.php";
}
```

---

## โครงสร้าง Template

### ระดับชั้น (Hierarchy)

```
┌─────────────────────────────────────┐
│           layout.php                │  ← โครงร่างหลัก
│  ┌─────────────────────────────┐   │
│  │        header.php           │   │  ← CSS, JS, Meta
│  └─────────────────────────────┘   │
│                                    │
│  ┌─────────────────────────────┐   │
│  │    [content_template]       │   │  ← เนื้อหาเปลี่ยนได้
│  │    (explore_content.php)    │   │
│  └─────────────────────────────┘   │
│                                    │
│  ┌─────────────────────────────┐   │
│  │        footer.php           │   │  ← Modal, Mobile Nav
│  └─────────────────────────────┘   │
└─────────────────────────────────────┘
```

---

## Main Layouts

### layout.php - เค้าโครงหลัก

**หน้าที่:** โครงสร้าง HTML หลักที่ครอบทุกหน้า

**โครงสร้าง:**
```php
<!DOCTYPE html>
<html>
<head>
    <?php require TEMPLATES_DIR . '/header.php'; ?>  // CSS, Fonts
</head>
<body>
    // Sidebar (Desktop)
    <aside class="hidden md:flex...">
        // Logo, Navigation, Profile Card
    </aside>
    
    // Main Content
    <main class="flex-1...">
        // Mobile Header
        // Flash Messages (Toast)
        // $content (Dynamic)
    </main>
    
    // Mobile Navigation
    <?php require TEMPLATES_DIR . '/footer.php'; ?>
</body>
</html>
```

**ตัวแปรที่รับ:**
- `$content` (string): เนื้อหา HTML
- `$title` (string): ชื่อหน้า
- `$activePage` (string): เมนูที่ active

---

### header.php - ส่วนหัว

**หน้าที่:** เก็บ CSS, JavaScript, Fonts

**สิ่งที่โหลด:**
- Tailwind CSS (CDN)
- Google Fonts (Kanit)
- Material Symbols Outlined
- Custom CSS (Neo-brutalism styles)
- Utility JavaScript

---

### footer.php - ส่วนท้าย

**หน้าที่:** Modal และ Mobile Navigation

**สิ่งที่มี:**
- Delete Confirmation Modal
- Mobile Bottom Navigation
- JavaScript for modals

---

## Content Templates

### 📋 Authentication Templates

#### login.php
**หน้า:** เข้าสู่ระบบ  
**Data ที่รับ:**
- `$errors` (array): ข้อผิดพลาดการ validate
- `$email` (string): ค่าอีเมลที่กรอกไว้

**ฟอร์ม:**
- Email input
- Password input
- Submit button
- Link ไปหน้าสมัครสมาชิก

#### register.php
**หน้า:** สมัครสมาชิก  
**Data ที่รับ:**
- `$errors` (array): ข้อผิดพลาด
- ค่าที่กรอกก่อนหน้า (preserved)

**ฟอร์มพิเศษ:**
- Icon buttons สำหรับเลือกเพศ
- Real-time age validation
- Password strength indicator

---

### 📅 Event Templates

#### explore_content.php
**หน้า:** สำรวจกิจกรรมทั้งหมด  
**Data ที่รับ:**
```php
[
  'events' => [...],      // รายการกิจกรรม
  'search' => '...',      // คำค้นหา
  'startDate' => '...',   // วันเริ่ม filter
  'endDate' => '...',     // วันจบ filter
  'activePage' => 'explore'
]
```

**ส่วนประกอบ:**
- Search bar
- Filter inputs
- Event cards grid

#### event_detail_content.php
**หน่า:** รายละเอียดกิจกรรม  
**Data ที่รับ:**
```php
[
  'event' => [...],           // ข้อมูลกิจกรรม
  'isOwner' => true/false,    // เป็นเจ้าของมั้ย
  'isRegistered' => true,   // ลงทะเบียนแล้วมั้ย
  'registration' => [...]     // ข้อมูลการลงทะเบียน
]
```

**ส่วนประกอบ:**
- Image gallery
- Event details
- Action buttons (Join/Manage)
- Registration status

#### event_form_content.php
**หน้า:** สร้าง/แก้ไขกิจกรรม  
**Data ที่รับ:**
```php
[
  'event' => [...],     // ถ้า edit (optional)
  'errors' => [...],    // ข้อผิดพลาด
  'isEdit' => true      // โหมดแก้ไขมั้ย
]
```

**ฟอร์ม:**
- Title
- Description (textarea)
- Date/Time pickers
- Location
- Max participants
- Image upload (multiple)

---

### 👤 User Templates

#### my_events_content.php
**หน้า:** กิจกรรมที่ฉันสร้าง  
**Data ที่รับ:**
```php
[
  'events' => [...],      // กิจกรรมทั้งหมด
  'activePage' => 'my-events'
]
```

**ส่วนประกอบ:**
- Create button
- Event list with status
- Edit/Delete buttons
- Quick stats

#### my_registrations_content.php
**หน้า:** การลงทะเบียนของฉัน  
**Data ที่รับ:**
```php
[
  'registrations' => [...],   // รายการลงทะเบียน
  'otpData' => [...],         // OTP ถ้ามี (optional)
  'activePage' => 'my-registrations'
]
```

**ส่วนประกอบ:**
- Registration cards
- Status badges
- OTP display modal
- Withdraw button

#### profile_content.php
**หน้า:** ดูโปรไฟล์  
**Data ที่รับ:**
```php
[
  'user' => [...],      // ข้อมูลผู้ใช้
  'stats' => [...]      // สถิติ
]
```

**แสดง:**
- Profile image
- User info
- Registration stats
- Created events count

#### profile_edit_content.php
**หน้า:** แก้ไขโปรไฟล์  
**Data ที่รับ:**
```php
[
  'user' => [...],      // ข้อมูลปัจจุบัน
  'errors' => [...]     // ข้อผิดพลาด
]
```

**ฟอร์ม:**
- Profile image upload (clickable)
- Name
- Birth date + Age calculator
- Gender icon buttons
- Email (readonly)

---

### 📊 Management Templates

#### manage_event_content.php
**หน้า:** จัดการกิจกรรม (Dashboard)  
**Data ที่รับ:**
```php
[
  'event' => [...],           // กิจกรรม
  'registrations' => [...],   // รายการลงทะเบียน
  'stats' => [
    'total' => 50,
    'approved' => 30,
    'pending' => 15,
    'rejected' => 5,
    'checkedIn' => 20,
    'gender' => ['male'=>15, 'female'=>15],
    'age' => ['18-25'=>10, '26-35'=>20]
  ],
  'activePage' => 'my-events'
]
```

**ส่วนประกอบ:**
- Statistics cards
- Charts (Gender, Age)
- Registration table
- Approval buttons
- OTP check-in
- Export CSV button

#### otp_display_content.php
**หน้า:** แสดง OTP สำหรับผู้ลงทะเบียน  
**Data ที่รับ:**
```php
[
  'otp_code' => '123456',
  'expires_at' => '2024-03-15 10:30:00',
  'event' => [...],
  'activePage' => 'my-registrations'
]
```

**แสดง:**
- Large OTP digits
- Countdown timer
- Event info

---

## Error Templates

### 401.php - Unauthorized
**สถานการณ์:** ต้องเข้าสู่ระบบ  
**พิเศษ:** มีฟอร์มล็อกอินในหน้าเลย

### 403.php - Forbidden
**สถานการณ์:** ไม่มีสิทธิ์เข้าถึง

### 404.php - Not Found
**สถานการณ์:** ไม่พบหน้าที่ต้องการ

### 500.php - Server Error
**สถานการณ์:** เกิดข้อผิดพลาดในเซิร์ฟเวอร์

**ลักษณะร่วม:**
- Icon ขนาดใหญ่
- Error code
- Message อธิบาย
- ปุ่มกลับหน้าหลัก

---

## Partials (ส่วนประกอบย่อย)

### 📁 templates/partials/

#### event-card.php
**การใช้งาน:**
```php
<?php
$event = [...]; // ข้อมูลกิจกรรม
require TEMPLATES_DIR . '/partials/event-card.php';
?>
```

**แสดง:**
- รูปกิจกรรม
- ชื่อ + วันที่
- สถานที่
- จำนวนที่เหลือ
- ปุ่มเข้าร่วม/จัดการ

#### status-badge.php
**รับค่า:** `$status` (pending/approved/rejected/checked_in)

**แสดง:**
- สีตามสถานะ
- Icon
- ข้อความภาษาไทย

#### flash.php
**การใช้งาน:**
```php
<?php if ($flash): ?>
    <?php require TEMPLATES_DIR . '/partials/flash.php'; ?>
<?php endif; ?>
```

**แสดง:** Toast notification ที่มุมล่าง

#### mobile-nav.php
**หน้าที่:** Navigation bar สำหรับมือถือ

**เมนู:**
1. ค้นหา (explore)
2. กิจกรรม (my-events)
3. ลงทะเบียน (my-registrations)
4. โปรไฟล์ (profile)

**Guest mode:** แสดง disabled items สีเทา

#### sidebar.php
**หน้าที่:** Navigation สำหรับ Desktop

**โครงสร้าง:**
- Logo
- Navigation links
- Profile/Login card
- Logout (ถ้า logged in)

---

## Design System

### CSS Classes ที่ใช้บ่อย

#### Layout
```css
.neo-box        /* กล่องมีเงาดำ */
.neo-card       /* การ์ดมี hover effect */
.neo-btn        /* ปุ่มมีเงา */
.neo-btn-small  /* ปุ่มขนาดเล็ก */
.neo-input      /* input มีกรอบดำ */
```

#### Spacing
```css
/* Padding */
p-2, p-3, p-4, p-6, p-8, p-12

/* Margin */
mb-4, mt-4, mx-auto

/* Gap */
gap-2, gap-3, gap-4
```

#### Colors
```css
text-black, text-gray-400, text-red-500
bg-[#FFE600], bg-[#D4FF33], bg-[#40E0D0]
border-2 border-black
```

---

## การสร้าง Template ใหม่

### ขั้นตอน

1. **สร้างไฟล์**
```php
// templates/new_content.php
<div class="neo-box p-6">
    <h1 class="font-black text-2xl mb-4"><?= $title ?></h1>
    <!-- Content -->
</div>
```

2. **ใช้ใน Route**
```php
renderView('new_content', [
    'title' => 'หัวข้อ',
    'data' => [...]
]);
```

3. **รวมใน Layout**
```php
// อัตโนมัติผ่าน renderView()
```

---

## ตารางสรุป Templates

| หมวดหมู่ | จำนวน | Templates |
|---------|-------|-----------|
| Layout | 3 | layout, header, footer |
| Auth | 2 | login, register |
| Event | 3 | explore, detail, form |
| User | 4 | my-events, my-registrations, profile, profile-edit |
| Manage | 2 | manage-event, otp-display |
| Error | 4 | 401, 403, 404, 500 |
| Partials | 5 | event-card, status-badge, flash, mobile-nav, sidebar |
| **รวม** | **23** | - |
