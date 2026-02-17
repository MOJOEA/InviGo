# การติดตั้ง (Setup Guide)

## ความต้องการระบบ

### ซอฟต์แวร์ที่ต้องมี
- **Docker** (เวอร์ชัน 20.10 หรือใหม่กว่า)
- **Docker Compose** (เวอร์ชัน 1.29 หรือใหม่กว่า)
- **Git** (สำหรับ clone โปรเจค)

### ทรัพยากรที่แนะนำ
- **RAM:** 4GB+ (8GB สำหรับการพัฒนา)
- **Disk:** 10GB+ ที่เหลือ
- **Internet:** สำหรับดาวน์โหลด images

---

## ขั้นตอนการติดตั้ง

### 1. Clone โปรเจค

```bash
# ใช้ Git clone
git clone https://github.com/K1Dev-Core/InviGo.git

# หรือถ้าใช้ SSH
git clone git@github.com:K1Dev-Core/InviGo.git

# เข้าโฟลเดอร์โปรเจค
cd InviGo
```

---

### 2. เริ่ม Docker Containers

```bash
# สร้างและเริ่ม containers ทั้งหมด
docker-compose up -d

# ตรวจสอบสถานะ
docker-compose ps

# ควรเห็น 3 services:
# - invigo-php       Up  
# - invigo-mysql     Up  
# - invigo-phpmyadmin Up
```

**คำสั่งที่ใช้บ่อย:**
```bash
docker-compose up -d      # เริ่ม containers
docker-compose down         # หยุด containers
docker-compose restart      # รีสตาร์ท
docker-compose logs -f      # ดู logs แบบ real-time
```

---

### 3. นำเข้าฐานข้อมูล

```bash
# รอให้ MySQL พร้อม (ประมาณ 30 วินาที)
sleep 30

# นำเข้า schema
docker-compose exec mysql mysql -u root -prootpassword invigo < database.sql

# หรือถ้ามี migrations อื่นๆ
docker-compose exec mysql mysql -u root -prootpassword invigo < migration_add_profile_image.sql
```

**ตรวจสอบว่าฐานข้อมูลพร้อม:**
```bash
docker-compose exec mysql mysql -u root -prootpassword -e "SHOW TABLES;" invigo

# ควรเห็น:
# Events
# Event_Images
# Otps
# Registrations
# Users
```

---

### 4. ตั้งค่า Permissions

```bash
# สร้างโฟลเดอร์ uploads ถ้ายังไม่มี
mkdir -p public/uploads/events
mkdir -p public/uploads/profiles

# ตั้ง permissions (ใน container)
docker-compose exec php chown -R www-data:www-data /var/www/html/public/uploads
docker-compose exec php chmod -R 755 /var/www/html/public/uploads
```

---

### 5. เข้าใช้งาน

เปิดเบราว์เซอร์และเข้า:

| บริการ | URL | คำอธิบาย |
|--------|-----|---------|
| **แอปพลิเคชัน** | http://localhost:8888 | หน้าหลัก |
| **phpMyAdmin** | http://localhost:8080 | จัดการฐานข้อมูล |

**phpMyAdmin Credentials:**
- Server: `mysql`
- Username: `root` หรือ `invigo`
- Password: `rootpassword` หรือ `invigopass`

---

## การตั้งค่าสภาพแวดล้อม

### สร้างไฟล์ .env (Optional)

```bash
# สร้างไฟล์ .env
cat > .env << 'EOF'
# Database
DB_HOST=mysql
DB_PORT=3306
DB_NAME=invigo
DB_USER=invigo
DB_PASS=invigopass

# Application
APP_URL=http://localhost:8888
APP_ENV=development
APP_DEBUG=true

# Upload
MAX_UPLOAD_SIZE=2097152
ALLOWED_TYPES=image/jpeg,image/png,image/gif,image/webp
EOF
```

> หมายเหตุ: ระบบใช้ค่า default ใน `includes/config.php` ถ้าไม่มี .env

---

## การอัปเดตโปรเจค

### Pull โค้ดล่าสุด
```bash
git pull origin main

# รีสตาร์ท containers
docker-compose restart
```

### ถ้ามี migrations ใหม่
```bash
# รันทุกไฟล์ migration
for file in migration_*.sql; do
    docker-compose exec mysql mysql -u root -prootpassword invigo < "$file"
done
```

---

## การสำรองข้อมูล

### Export ฐานข้อมูล
```bash
# สำรองทั้ง database
docker-compose exec mysql mysqldump -u root -prootpassword invigo > backup_$(date +%Y%m%d_%H%M%S).sql

# สำรองเฉพาะข้อมูล (ไม่มี schema)
docker-compose exec mysql mysqldump -u root -prootpassword --no-create-info invigo > backup_data_$(date +%Y%m%d_%H%M%S).sql
```

### Import ฐานข้อมูล
```bash
# นำเข้าจากไฟล์สำรอง
docker-compose exec mysql mysql -u root -prootpassword invigo < backup_20240218_120000.sql
```

### สำรองไฟล์อัปโหลด
```bash
# บีบอัด uploads
tar -czvf uploads_backup_$(date +%Y%m%d).tar.gz public/uploads/

# แตกไฟล์
tar -xzvf uploads_backup_20240218.tar.gz
```

---

## การแก้ไขปัญหาเบื้องต้น

### Container ไม่สตาร์ท
```bash
# ดู logs
docker-compose logs mysql
docker-compose logs php

# รีบิลด์
docker-compose down
docker-compose build --no-cache
docker-compose up -d
```

### ฐานข้อมูลเชื่อมต่อไม่ได้
```bash
# ตรวจสอบ MySQL รันหรือไม่
docker-compose ps mysql

# รีสตาร์ท MySQL
docker-compose restart mysql

# รอให้พร้อมแล้วนำเข้า schema ใหม่
docker-compose exec mysql mysql -u root -prootpassword -e "CREATE DATABASE IF NOT EXISTS invigo;"
docker-compose exec mysql mysql -u root -prootpassword invigo < database.sql
```

### ไฟล์อัปโหลดไม่ได้
```bash
# ตรวจสอบ permissions
docker-compose exec php ls -la /var/www/html/public/uploads/

# แก้ไข permissions
docker-compose exec php chown -R www-data:www-data /var/www/html/public/uploads
docker-compose exec php chmod -R 777 /var/www/html/public/uploads
```

### 404 Not Found
```bash
# ตรวจสอบ .htaccess มีอยู่
cat public/.htaccess

# ตรวจสอบ mod_rewrite
docker-compose exec php apachectl -M | grep rewrite

# รีสตาร์ท Apache
docker-compose exec php service apache2 restart
```

---

## การพัฒนา (Development)

### Hot Reload
ไฟล์ในโฟลเดอร์ root จะ sync เข้า container โดยอัตโนมัติ (volume mount)

### ดู Logs แบบ Real-time
```bash
# PHP logs
docker-compose logs -f php

# MySQL logs
docker-compose logs -f mysql

# ทุก service
docker-compose logs -f
```

### เข้าไปใน Container
```bash
# เข้า PHP container
docker-compose exec php bash

# เข้า MySQL container
docker-compose exec mysql bash

# MySQL CLI
docker-compose exec mysql mysql -u root -prootpassword invigo
```

### รัน Command ใน Container
```bash
# ติดตั้ง composer package
docker-compose exec php composer install

# รัน PHP script
docker-compose exec php php script.php
```

---

## การ Deploy ขึ้น Production

### 1. ตั้งค่า Environment
```bash
# เปลี่ยนเป็น production
cat > .env << 'EOF'
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# เปลี่ยนรหัสผ่านที่แข็งแกร่ง
DB_PASS=your_strong_password_here
MYSQL_ROOT_PASSWORD=your_strong_root_password
EOF
```

### 2. SSL/HTTPS
```bash
# ใช้ Let's Encrypt หรือ SSL ที่มี
# แนะนำใช้ Reverse Proxy (Nginx/Caddy)
```

### 3. ตั้งค่า Backup อัตโนมัติ
```bash
# Cron job สำรับทุกวัน
0 2 * * * cd /path/to/InviGo && docker-compose exec mysql mysqldump -u root -prootpassword invigo > /backups/invigo_$(date +\%Y\%m\%d).sql
```

### 4. Security Hardening
- เปลี่ยน default passwords
- ปิด port ที่ไม่ใช้
- ใช้ Firewall
- อัปเดต Docker images regularly

---

## Port ที่ใช้

| Port | Service | เปลี่ยนได้ที่ |
|------|---------|--------------|
| 8888 | PHP/Apache | docker-compose.yml |
| 3307 | MySQL | docker-compose.yml |
| 8080 | phpMyAdmin | docker-compose.yml |

**เปลี่ยน Port:**
```yaml
# docker-compose.yml
services:
  php:
    ports:
      - "3000:80"  # เปลี่ยนจาก 8888 เป็น 3000
```

---

## Quick Reference

```bash
# เริ่มต้นใหม่ทั้งหมด
docker-compose down -v && docker-compose up -d

# รีเซ็ตฐานข้อมูล
docker-compose exec mysql mysql -u root -prootpassword -e "DROP DATABASE invigo; CREATE DATABASE invigo;"
docker-compose exec mysql mysql -u root -prootpassword invigo < database.sql

# ดู resource usage
docker stats

# Clean up
docker system prune -a
```
