# โครงสร้างโปรเจค (Project Structure)

## ภาพรวมโครงสร้างไฟล์

```
InviGo/
├── 📁 databases/              # ฟังก์ชันฐานข้อมูล
├── 📁 includes/             # ฟังก์ชันหลักของระบบ
│   ├── 📁 helpers/          # ฟังก์ชันช่วยเหลือ
│   └── 📁 utils/            # อรรถประโยชน์
├── 📁 public/               # โฟลเดอร์สาธารณะ (Web Root)
│   └── 📁 uploads/          # ไฟล์ที่อัปโหลด
├── 📁 routes/               # ตัวจัดการเส้นทาง (Controllers)
│   ├── 📁 events/           # Routes เกี่ยวกับกิจกรรม
│   └── 📁 profile/          # Routes โปรไฟล์
├── 📁 templates/            # เทมเพลต (Views)
│   └── 📁 partials/         # ส่วนประกอบย่อย
├── 📁 docs/                 # เอกสารประกอบ
├── 🐳 docker-compose.yml    # การตั้งค่า Docker
├── 🐳 Dockerfile            # การสร้าง PHP Container
├── 🗄️ database.sql          # สคีมาฐานข้อมูล
└── 📄 README.md             # ไฟล์อ่านฉบับย่อ
```

## รายละเอียดแต่ละโฟลเดอร์

### 📁 databases/ - ฟังก์ชันฐานข้อมูล

เก็บฟังก์ชันที่เกี่ยวข้องกับการ Query ฐานข้อมูลทั้งหมด แยกตาม entity

| ไฟล์ | คำอธิบาย |
|------|---------|
| `user.php` | จัดการข้อมูลผู้ใช้ (CRUD) |
| `event.php` | จัดการกิจกรรม |
| `registration.php` | จัดการการลงทะเบียน |
| `otp.php` | สร้างและตรวจสอบ OTP |
| `stats.php` | คำนวณสถิติต่างๆ |
| `event_image.php` | จัดการรูปภาพกิจกรรม |

**ตัวอย่างการใช้งาน:**
```php
// ดึงข้อมูลผู้ใช้
$user = getUserById(1);

// สร้างกิจกรรมใหม่
$eventId = createEvent($userId, $data);

// ดึงรายการลงทะเบียน
$regs = getRegistrationsByEvent($eventId);
```

---

### 📁 includes/ - ฟังก์ชันหลัก

เก็บไฟล์ที่จำเป็นสำหรับการทำงานของระบบ

#### ไฟล์หลัก

| ไฟล์ | คำอธิบาย |
|------|---------|
| `config.php` | ค่าคงที่และการตั้งค่าระบบ |
| `database.php` | การเชื่อมต่อฐานข้อมูล |
| `router.php` | ระบบจัดการเส้นทาง URL |
| `view.php` | ระบบแสดงผลเทมเพลต |
| `auth.php` | ฟังก์ชันเกี่ยวกับการเข้าสู่ระบบ |

#### 📁 helpers/ - ฟังก์ชันช่วยเหลือ

| ไฟล์ | คำอธิบาย |
|------|---------|
| `auth.php` | ตรวจสอบสิทธิ์ |
| `date.php` | จัดการวันที่ (ภาษาไทย) |
| `flash.php` | ข้อความแจ้งเตือนแบบชั่วคราว |
| `format.php` | จัดรูปแบบข้อมูล |
| `password.php` | เข้ารหัสลับรหัสผ่าน |
| `sanitize.php` | ทำความสะอาดข้อมูล |

---

### 📁 public/ - โฟลเดอร์สาธารณะ

โฟลเดอร์ที่ Apache ใช้เป็น Web Root

```
public/
├── 📄 index.php          # จุดเริ่มต้นแอปพลิเคชัน
├── 📄 .htaccess          # กฎการเขียน URL
└── 📁 uploads/           # ไฟล์ที่ผู้ใช้อัปโหลด
    ├── 📁 events/        # รูปกิจกรรม
    └── 📁 profiles/      # รูปโปรไฟล์
```

---

### 📁 routes/ - ตัวจัดการเส้นทาง

เก็บไฟล์ที่จัดการ Request ตาม URL แยกตามหมวดหมู่

#### Routes หลัก
```
routes/
├── 📄 login.php           # เข้าสู่ระบบ
├── 📄 register.php        # สมัครสมาชิก
├── 📄 logout.php          # ออกจากระบบ
├── 📄 explore.php         # สำรวจกิจกรรม
├── 📄 my-events.php       # กิจกรรมของฉัน
├── 📄 my-registrations.php # การลงทะเบียนของฉัน
├── 📁 profile/            # โปรไฟล์
│   ├── 📄 index.php
│   └── 📄 edit.php
└── 📁 events/             # กิจกรรม
    ├── 📄 create.php
    ├── 📄 edit.php
    ├── 📄 delete.php
    ├── 📄 join.php
    ├── 📄 withdraw.php
    ├── 📄 manage.php
    └── 📄 otp.php
```

---

### 📁 templates/ - เทมเพลต

เก็บไฟล์ HTML/PHP ที่แสดงผลหน้าเว็บ

```
templates/
├── 📄 layout.php              # เค้าโครงหลัก
├── 📄 header.php              # ส่วนหัว (CSS, JS)
├── 📄 footer.php              # ส่วนท้าย (Modal, Nav)
│
├── 📄 login.php               # หน้าเข้าสู่ระบบ
├── 📄 register.php            # หน้าสมัครสมาชิก
│
├── 📄 explore_content.php     # สำรวจกิจกรรม
├── 📄 event_detail_content.php # รายละเอียดกิจกรรม
├── 📄 event_form_content.php  # ฟอร์มกิจกรรม
│
├── 📄 my_events_content.php   # กิจกรรมของฉัน
├── 📄 my_registrations_content.php # การลงทะเบียน
├── 📄 manage_event_content.php     # จัดการกิจกรรม
│
├── 📄 profile_content.php     # โปรไฟล์
├── 📄 profile_edit_content.php    # แก้ไขโปรไฟล์
│
├── 📄 otp_display_content.php # แสดง OTP
│
├── 📄 401.php                 # ไม่มีสิทธิ์ (พร้อมฟอร์มล็อกอิน)
├── 📄 403.php                 # ห้ามเข้า
├── 📄 404.php                 # ไม่พบ
├── 📄 500.php                 # ข้อผิดพลาดเซิร์ฟเวอร์
│
└── 📁 partials/               # ส่วนประกอบย่อย
    ├── 📄 event-card.php     # การ์ดกิจกรรม
    ├── 📄 status-badge.php    # ป้ายสถานะ
    ├── 📄 flash.php          # ข้อความแจ้งเตือน
    ├── 📁 mobile-nav.php     # เมนูมือถือ
    └── 📁 sidebar.php        # เมนูด้านข้าง
```

---

## กฎการตั้งชื่อไฟล์

| ประเภท | รูปแบบ | ตัวอย่าง |
|--------|--------|---------|
| Controller | `verb-noun.php` | `create-event.php` |
| Template | `noun_content.php` | `explore_content.php` |
| Partial | `descriptive-name.php` | `event-card.php` |
| Database | `entity.php` | `event.php` |
| Helper | `category.php` | `date.php` |

---

## Design Principles

### 1. Separation of Concerns
- **Routes:** จัดการ Request/Response
- **Templates:** แสดงผลอย่างเดียว
- **Databases:** Query ฐานข้อมูล
- **Helpers:** ฟังก์ชันอรรถประโยชน์

### 2. DRY (Don't Repeat Yourself)
- ใช้ `partials/` สำหรับส่วนที่ใช้ซ้ำ
- Helpers สำหรับฟังก์ชันที่ใช้บ่อย

### 3. Convention over Configuration
- ชื่อไฟล์บอกฟังก์ชัน
- โครงสร้างตามมาตรฐาน MVC แบบง่าย

---

## การเพิ่มฟีเจอร์ใหม่

### ขั้นตอน
1. **สร้าง Route** ใน `routes/`
2. **สร้าง Template** ใน `templates/`
3. **เพิ่ม Database Function** (ถ้าจำเป็น)
4. **เพิ่ม Helper** (ถ้าจำเป็น)
5. **อัปเดตเอกสาร**

### ตัวอย่าง: เพิ่มหน้า "เกี่ยวกับเรา"

```
1. สร้าง routes/about.php
2. สร้าง templates/about_content.php
3. เพิ่มลิงก์ใน sidebar (templates/partials/sidebar.php)
```

---

## Security Considerations

| โฟลเดอร์ | ความปลอดภัย |
|---------|------------|
| `public/` | เปิดให้เข้าถึงได้ |
| `includes/` | **ห้าม** เข้าถึงโดยตรง |
| `databases/` | **ห้าม** เข้าถึงโดยตรง |
| `routes/` | **ห้าม** เข้าถึงโดยตรง |
| `templates/` | **ห้าม** เข้าถึงโดยตรง |

> หมายเหตุ: `.htaccess` ในโฟลเดอร์อื่นๆ ป้องกันการเข้าถึงโดยตรงแล้ว
