# ฐานข้อมูล (Database)

## ภาพรวม ER Diagram

```
┌─────────────┐       ┌─────────────┐       ┌─────────────┐
│    Users    │       │   Events    │       │    Otps     │
├─────────────┤       ├─────────────┤       ├─────────────┤
│ id (PK)     │──┐    │ id (PK)     │◄──┐  │ id (PK)     │
│ email       │  │    │ user_id(FK) │──┘  │ registration│
│ password    │  │    │ title       │       │ otp_code    │
│ name        │  │    │ description │       │ expires_at  │
│ birth_date  │  │    │ event_date  │       │ used_at     │
│ gender      │  │    │ end_date    │       └─────────────┘
│ profile_img │  │    │ location    │
│ created_at  │  │    │ max_participants
└─────────────┘  │    │ status      │
                 │    │ created_at  │
                 │    └─────────────┘
                 │          ▲
                 │          │
                 │    ┌─────────────┐
                 │    │Registrations│
                 │    ├─────────────┤
                 └──►│ id (PK)     │
                      │ event_id(FK)│
                      │ user_id (FK)│
                      │ status      │
                      │ checked_in  │
                      │ checked_at  │
                      │ created_at  │
                      └─────────────┘
                            │
                            ▼
                     ┌─────────────┐
                     │ Event_Images│
                     ├─────────────┤
                     │ id (PK)     │
                     │ event_id(FK)│
                     │ image_path  │
                     │ created_at  │
                     └─────────────┘
```

## รายละเอียดตาราง

### 👤 Users (ผู้ใช้)

เก็บข้อมูลผู้ใช้ทั้งผู้จัดกิจกรรมและผู้ลงทะเบียน

| คอลัมน์ | ประเภท | คำอธิบาย | ค่าเริ่มต้น |
|---------|--------|---------|------------|
| `id` | INT (PK, AI) | รหัสผู้ใช้ | Auto |
| `email` | VARCHAR(255) | อีเมล (unique) | - |
| `password` | VARCHAR(255) | รหัสผ่าน (bcrypt hash) | - |
| `name` | VARCHAR(255) | ชื่อ-นามสกุล | - |
| `birth_date` | DATE | วันเกิด | NULL |
| `gender` | ENUM | เพศ: male, female, other | NULL |
| `profile_image` | VARCHAR(255) | ที่อยู่รูปโปรไฟล์ | NULL |
| `created_at` | TIMESTAMP | วันที่สร้าง | CURRENT_TIMESTAMP |

**คำสั่ง SQL:**
```sql
CREATE TABLE Users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    name VARCHAR(255) NOT NULL,
    birth_date DATE,
    gender ENUM('male', 'female', 'other'),
    profile_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

### 📅 Events (กิจกรรม)

เก็บข้อมูลกิจกรรมที่ผู้ใช้สร้าง

| คอลัมน์ | ประเภท | คำอธิบาย | ค่าเริ่มต้น |
|---------|--------|---------|------------|
| `id` | INT (PK, AI) | รหัสกิจกรรม | Auto |
| `user_id` | INT (FK) | ผู้สร้าง (Users.id) | - |
| `title` | VARCHAR(255) | ชื่อกิจกรรม | - |
| `description` | TEXT | รายละเอียด | - |
| `event_date` | DATETIME | วัน-เวลาเริ่ม | - |
| `end_date` | DATETIME | วัน-เวลาสิ้นสุด | NULL |
| `location` | VARCHAR(255) | สถานที่ | - |
| `max_participants` | INT | จำนวนที่รับ | 1 |
| `status` | ENUM | สถานะ: active, cancelled, completed | active |
| `created_at` | TIMESTAMP | วันที่สร้าง | CURRENT_TIMESTAMP |

**ความสัมพันธ์:**
- `user_id` → `Users.id` (ON DELETE CASCADE)

**คำสั่ง SQL:**
```sql
CREATE TABLE Events (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    event_date DATETIME NOT NULL,
    end_date DATETIME,
    location VARCHAR(255) NOT NULL,
    max_participants INT DEFAULT 1,
    status ENUM('active', 'cancelled', 'completed') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE
);
```

---

### 📝 Registrations (การลงทะเบียน)

เก็บข้อมูลการลงทะเบียนเข้าร่วมกิจกรรม

| คอลัมน์ | ประเภท | คำอธิบาย | ค่าเริ่มต้น |
|---------|--------|---------|------------|
| `id` | INT (PK, AI) | รหัสลงทะเบียน | Auto |
| `event_id` | INT (FK) | กิจกรรม (Events.id) | - |
| `user_id` | INT (FK) | ผู้ลงทะเบียน (Users.id) | - |
| `status` | ENUM | สถานะ: pending, approved, rejected | pending |
| `checked_in` | BOOLEAN | สถานะเช็คอิน | FALSE |
| `checked_in_at` | TIMESTAMP | เวลาเช็คอิน | NULL |
| `created_at` | TIMESTAMP | เวลาลงทะเบียน | CURRENT_TIMESTAMP |

**ความสัมพันธ์:**
- `event_id` → `Events.id` (ON DELETE CASCADE)
- `user_id` → `Users.id` (ON DELETE CASCADE)

**ข้อจำกัด:**
- หนึ่งผู้ใช้ลงทะเบียนกิจกรรมเดียวได้ครั้งเดียว (UNIQUE: event_id, user_id)

**คำสั่ง SQL:**
```sql
CREATE TABLE Registrations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    checked_in BOOLEAN DEFAULT FALSE,
    checked_in_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES Events(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES Users(id) ON DELETE CASCADE,
    UNIQUE KEY unique_registration (event_id, user_id)
);
```

---

### 🖼️ Event_Images (รูปภาพกิจกรรม)

เก็บรูปภาพประกอบกิจกรรม

| คอลัมน์ | ประเภท | คำอธิบาย | ค่าเริ่มต้น |
|---------|--------|---------|------------|
| `id` | INT (PK, AI) | รหัสรูป | Auto |
| `event_id` | INT (FK) | กิจกรรม (Events.id) | - |
| `image_path` | VARCHAR(255) | ที่อยู่ไฟล์ | - |
| `created_at` | TIMESTAMP | วันอัปโหลด | CURRENT_TIMESTAMP |

**ความสัมพันธ์:**
- `event_id` → `Events.id` (ON DELETE CASCADE)

**คำสั่ง SQL:**
```sql
CREATE TABLE Event_Images (
    id INT PRIMARY KEY AUTO_INCREMENT,
    event_id INT NOT NULL,
    image_path VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (event_id) REFERENCES Events(id) ON DELETE CASCADE
);
```

---

### 🔐 Otps (รหัส OTP)

เก็บรหัส OTP สำหรับเช็คอิน

| คอลัมน์ | ประเภท | คำอธิบาย | ค่าเริ่มต้น |
|---------|--------|---------|------------|
| `id` | INT (PK, AI) | รหัส OTP | Auto |
| `registration_id` | INT (FK) | การลงทะเบียน | - |
| `otp_code` | VARCHAR(6) | รหัส 6 หลัก | - |
| `expires_at` | TIMESTAMP | วันหมดอายุ | - |
| `used_at` | TIMESTAMP | วันที่ใช้ | NULL |

**ความสัมพันธ์:**
- `registration_id` → `Registrations.id` (ON DELETE CASCADE)

**คำสั่ง SQL:**
```sql
CREATE TABLE Otps (
    id INT PRIMARY KEY AUTO_INCREMENT,
    registration_id INT NOT NULL,
    otp_code VARCHAR(6) NOT NULL,
    expires_at TIMESTAMP NOT NULL,
    used_at TIMESTAMP NULL,
    FOREIGN KEY (registration_id) REFERENCES Registrations(id) ON DELETE CASCADE
);
```

---

## ดัชนี (Indexes)

### เพื่อประสิทธิภาพการค้นหา

```sql
-- ค้นหากิจกรรม
CREATE INDEX idx_event_date ON Events(event_date);
CREATE INDEX idx_event_status ON Events(status);

-- ค้นหาการลงทะเบียน
CREATE INDEX idx_reg_status ON Registrations(status);
CREATE INDEX idx_reg_event ON Registrations(event_id);
CREATE INDEX idx_reg_user ON Registrations(user_id);

-- ค้นหา OTP
CREATE INDEX idx_otp_code ON Otps(otp_code);
CREATE INDEX idx_otp_expires ON Otps(expires_at);
```

---

## ตัวอย่าง Query ที่ใช้บ่อย

### 1. ดึงกิจกรรมพร้อมจำนวนผู้ลงทะเบียน
```sql
SELECT e.*, 
       COUNT(r.id) as total_registrations,
       SUM(CASE WHEN r.status = 'approved' THEN 1 ELSE 0 END) as approved_count
FROM Events e
LEFT JOIN Registrations r ON e.id = r.event_id
WHERE e.status = 'active'
GROUP BY e.id;
```

### 2. ดึงรายชื่อผู้ลงทะเบียนกิจกรรม
```sql
SELECT u.name, u.email, u.profile_image, r.status, r.checked_in
FROM Registrations r
JOIN Users u ON r.user_id = u.id
WHERE r.event_id = ?
ORDER BY r.created_at DESC;
```

### 3. ตรวจสอบ OTP ที่ยังไม่หมดอายุ
```sql
SELECT * FROM Otps
WHERE otp_code = ?
  AND registration_id = ?
  AND expires_at > NOW()
  AND used_at IS NULL;
```

### 4. สถิติเพศผู้เข้าร่วม
```sql
SELECT u.gender, COUNT(*) as count
FROM Registrations r
JOIN Users u ON r.user_id = u.id
WHERE r.event_id = ? AND r.status = 'approved'
GROUP BY u.gender;
```

---

## การสำรองและกู้คืนข้อมูล

### Export ฐานข้อมูล
```bash
docker-compose exec mysql mysqldump -u root -p invigo > backup.sql
```

### Import ฐานข้อมูล
```bash
docker-compose exec mysql mysql -u root -p invigo < backup.sql
```

---

## หลักการออกแบบ

1. **Normalization:** ลดการซ้ำซ้อนข้อมูล
2. **Foreign Keys:** รักษาความสอดคล้องของข้อมูล
3. **Indexes:** เพิ่มความเร็วการค้นหา
4. **ON DELETE CASCADE:** ลบข้อมูลที่เกี่ยวข้องอัตโนมัติ
5. **Timestamps:** บันทึกเวลาทุกการเปลี่ยนแปลง
