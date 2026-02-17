# การแก้ไขปัญหา (Troubleshooting)

## ภาพรวม

คู่มือแก้ไขปัญหาที่พบบ่อยใน InviGo พร้อมวิธีแก้ไข step-by-step

```
┌─────────────────────────────────────────┐
│      ปัญหาที่พบบ่อย TOP 10             │
├─────────────────────────────────────────┤
│ 1. Database connection failed           │
│ 2. 404 Not Found errors                 │
│ 3. File upload not working              │
│ 4. Session/Login issues                 │
│ 5. Docker container problems            │
│ 6. Permission denied errors             │
│ 7. Image not displaying                 │
│ 8. Form validation errors               │
│ 9. OTP not generating                   │
│ 10. Styling/CSS issues                  │
└─────────────────────────────────────────┘
```

---

## 1. Database Connection Failed

### อาการ
```
Warning: mysqli_connect(): Connection refused
Fatal error: Uncaught Error: Call to a member function prepare() on null
```

### สาเหตุ
- MySQL container ไม่ทำงาน
- ฐานข้อมูลยังไม่ถูกสร้าง
- ข้อมูลการเชื่อมต่อผิด

### วิธีแก้ไข

```bash
# 1. ตรวจสอบ container รันหรือไม่
docker-compose ps

# ถ้าไม่รัน ให้เริ่ม
docker-compose up -d mysql

# 2. รอให้ MySQL พร้อม (30 วินาที)
sleep 30

# 3. ตรวจสอบ database มีหรือไม่
docker-compose exec mysql mysql -u root -prootpassword -e "SHOW DATABASES;"

# 4. ถ้าไม่มี ให้สร้างและนำเข้า
docker-compose exec mysql mysql -u root -prootpassword -e "CREATE DATABASE invigo;"
docker-compose exec mysql mysql -u root -prootpassword invigo < database.sql

# 5. ตรวจสอบตาราง
docker-compose exec mysql mysql -u root -prootpassword -e "SHOW TABLES;" invigo
```

### ป้องกัน
```bash
# รอให้ MySQL พร้อมก่อนเริ่ม PHP (ใน docker-compose)
docker-compose up -d
sleep 30
docker-compose exec mysql mysql -u root -prootpassword invigo < database.sql
```

---

## 2. 404 Not Found Errors

### อาการ
- URL ถูกต้องแต่ขึ้น 404
- หน้าไม่โหลด
- CSS/JS ไม่โหลด

### สาเหตุ
- mod_rewrite ไม่เปิด
- .htaccess ไม่ทำงาน
- Apache config ผิด

### วิธีแก้ไข

```bash
# 1. ตรวจสอบ .htaccess มีอยู่
ls -la public/.htaccess

# 2. ดูเนื้อหา .htaccess
cat public/.htaccess

# ควรมี:
# RewriteEngine On
# RewriteCond %{REQUEST_FILENAME} !-f
# RewriteCond %{REQUEST_FILENAME} !-d
# RewriteRule ^(.*)$ index.php [QSA,L]

# 3. ตรวจสอบ mod_rewrite
docker-compose exec php apachectl -M | grep rewrite

# ถ้าไม่มี ให้เปิด
docker-compose exec php a2enmod rewrite
docker-compose exec php service apache2 restart

# 4. ตรวจสอบ AllowOverride
docker-compose exec php cat /etc/apache2/apache2.conf | grep -A 5 "Directory /var/www"

# ควรเป็น AllowOverride All
```

---

## 3. File Upload Not Working

### อาการ
- อัปโหลดแล้วไม่เห็นไฟล์
- ข้อความ "Upload failed"
- รูปไม่แสดง

### สาเหตุ
- Permissions ไม่ถูกต้อง
- โฟลเดอร์ไม่มี
- ขนาดไฟล์เกิน

### วิธีแก้ไข

```bash
# 1. สร้างโฟลเดอร์ uploads
mkdir -p public/uploads/events
mkdir -p public/uploads/profiles

# 2. ตั้ง permissions
docker-compose exec php chown -R www-data:www-data /var/www/html/public/uploads
docker-compose exec php chmod -R 755 /var/www/html/public/uploads

# หรือ 777 ถ้ายังไม่ได้ (ชั่วคราว)
docker-compose exec php chmod -R 777 /var/www/html/public/uploads

# 3. ตรวจสอบว่าโฟลเดอร์มีจริง
docker-compose exec php ls -la /var/www/html/public/uploads/

# 4. ตรวจสอบ PHP upload limits
docker-compose exec php php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;"
docker-compose exec php php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"

# ควรเป็น 2M หรือมากกว่า
```

---

## 4. Session/Login Issues

### อาการ
- Login แล้วกลับมาหน้า login
- Session หาย
- Cookie ไม่ทำงาน

### สาเหตุ
- Session path ไม่มี permissions
- Cookie settings ผิด
- Session timeout

### วิธีแก้ไข

```bash
# 1. ตรวจสอบ session path
docker-compose exec php php -r "echo 'session.save_path: ' . ini_get('session.save_path') . PHP_EOL;"

# 2. ตั้ง permissions ให้ session path
docker-compose exec php chmod 777 /var/lib/php/sessions

# 3. ล้าง cookies ใน browser
# (เปิด DevTools → Application → Cookies → Clear)

# 4. ตรวจสอบ session ใน PHP
<?php
session_start();
echo session_id();
print_r($_SESSION);
?>
```

---

## 5. Docker Container Problems

### อาการ
- Container ไม่สตาร์ท
- Port ถูกใช้แล้ว
- Image ไม่มี

### วิธีแก้ไข

```bash
# 1. ตรวจสอบ port ว่างไหม
lsof -i :8888  # ถ้ามี process ให้ kill หรือเปลี่ยน port

# 2. รีบิลด์ images
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# 3. ดู logs
docker-compose logs -f

# 4. รีเซ็ตทั้งหมด (ระวัง: ข้อมูลจะหาย)
docker-compose down -v  # ลบ volumes
docker-compose up -d

# 5. ตรวจสอบ disk space
df -h
docker system df
docker system prune -a  # ลบ images ที่ไม่ใช้
```

---

## 6. Permission Denied Errors

### อาการ
```
Warning: mkdir(): Permission denied
Warning: fopen(filename): failed to open stream: Permission denied
```

### วิธีแก้ไข

```bash
# 1. หา user ที่รัน Apache
docker-compose exec php ps aux | grep apache

# ควรเป็น www-data

# 2. ตั้ง permissions ให้ถูกต้อง
docker-compose exec php chown -R www-data:www-data /var/www/html
docker-compose exec php chmod -R 755 /var/www/html

# 3. สำหรับโฟลเดอร์ที่ต้องเขียน
docker-compose exec php chmod -R 777 /var/www/html/public/uploads
docker-compose exec php chmod -R 777 /var/lib/php/sessions
```

---

## 7. Image Not Displaying

### อาการ
- รูปภาพแตก
- ไอคอนไม่แสดง
- รูปโปรไฟล์ไม่เห็น

### สาเหตุ
- Path ผิด
- ไฟล์ไม่มี
- Permissions

### วิธีแก้ไข

```php
// 1. ตรวจสอบ path ในฐานข้อมูล
echo $user['profile_image'];

// 2. ตรวจสอบไฟล์มีอยู่จริง
file_exists('/var/www/html/public/' . $user['profile_image']);

// 3. ใช้ onerror fallback
<img src="<?= $image ?>" onerror="this.src='default.jpg'">

// 4. ตรวจสอบ permissions
chmod 644 /path/to/image.jpg
```

---

## 8. Form Validation Errors

### อาการ
- กรอกข้อมูลถูกแต่ error
- ไม่สามารถสมัครสมาชิก
- ไม่สามารถสร้างกิจกรรม

### Debug

```php
// เพิ่ม debug ใน route
print_r($_POST);
print_r($_FILES);
print_r($errors);
exit;

// ตรวจสอบ validation rules
echo MIN_AGE;  // 10
echo MAX_AGE;  // 120
echo MAX_UPLOAD_SIZE_MB;  // 2

// ตรวจสอบ date format
echo date('Y-m-d');  // ควรเป็น 2024-02-18
```

---

## 9. OTP Not Generating

### อาการ
- ไม่เห็น OTP
- OTP ไม่ทำงาน
- Expired ทันที

### วิธีแก้ไข

```bash
# 1. ตรวจสอบ timezone
docker-compose exec php php -r "echo date('Y-m-d H:i:s');"

# ควรตรงกับเวลาปัจจุบัน

# 2. ตั้ง timezone ใน PHP
docker-compose exec php cat /usr/local/etc/php/php.ini | grep timezone

# ควรเป็น Asia/Bangkok

# 3. ตรวจสอบตาราง Otps
docker-compose exec mysql mysql -u root -prootpassword -e "SELECT * FROM Otps ORDER BY id DESC LIMIT 5;" invigo

# 4. ล้าง OTP เก่า
docker-compose exec mysql mysql -u root -prootpassword -e "DELETE FROM Otps WHERE expires_at < NOW();" invigo
```

---

## 10. Styling/CSS Issues

### อาการ
- หน้าเว็บไม่มีสี
- Layout พัง
- Font ไม่โหลด

### วิธีแก้ไข

```bash
# 1. ตรวจสอบ Tailwind CDN
curl -I https://cdn.tailwindcss.com

# ควรได้ HTTP 200

# 2. ตรวจสอบ Google Fonts
curl -I https://fonts.googleapis.com

# 3. ตรวจสอบ Material Icons
curl -I https://fonts.gstatic.com

# 4. ดู DevTools Console
# เปิด F12 → Console ดู error

# 5. ล้าง cache browser
Ctrl + Shift + R (Windows/Linux)
Cmd + Shift + R (Mac)
```

---

## 🔧 เครื่องมือ Debug

### PHP Error Reporting

```php
// เปิด debug (development)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ปิด debug (production)
error_reporting(0);
ini_set('display_errors', 0);
```

### View Logs

```bash
# Apache logs
docker-compose logs php

# MySQL logs
docker-compose logs mysql

# ดู error log ล่าสุด
docker-compose exec php tail -f /var/log/apache2/error.log
```

### Check Environment

```bash
# PHP info
docker-compose exec php php -i

# Loaded extensions
docker-compose exec php php -m

# MySQL status
docker-compose exec mysql mysql -u root -prootpassword -e "STATUS;"
```

---

## 🆘 การขอความช่วยเหลือ

### รายงานปัญหา

1. **GitHub Issues:** https://github.com/K1Dev-Core/InviGo/issues
2. **ข้อมูลที่ต้องระบุ:**
   - อาการที่พบ
   - ขั้นตอนการ reproduce
   - Logs ที่เกี่ยวข้อง
   - Environment (OS, Docker version)

### ตัวอย่างการรายงาน

```
Title: 404 error หลัง login

Description:
- เข้า http://localhost:8888/login
- กรอก email/password
- กด login
- ขึ้น 404 Not Found

Expected: ไปหน้า /explore
Actual: 404 error

Logs:
[Thu Feb 18 10:00:00] [error] File does not exist: /var/www/html/explore

Environment:
- OS: macOS 14
- Docker: 24.0.7
- Browser: Chrome 121
```

---

## 📋 Checklist ก่อนถาม

- [ ] Restart Docker: `docker-compose restart`
- [ ] ล้าง browser cache
- [ ] ตรวจสอบ logs: `docker-compose logs`
- [ ] ตรวจสอบ permissions
- [ ] ตรวจสอบ .env/config
- [ ] รีบิลด์: `docker-compose build --no-cache`
- [ ] รีเซ็ต DB: `docker-compose down -v && docker-compose up -d`
