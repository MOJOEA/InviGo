# InviGo - ระบบจัดการลงทะเบียนกิจกรรม

## ภาพรวมโครงการ

InviGo เป็นแพลตฟอร์มจัดการลงทะเบียนเข้าร่วมกิจกรรมที่พัฒนาด้วย PHP + MySQL พร้อมดีไซน์แบบ Neo-brutalism ที่โดดเด่นและทันสมัย

### ฟีเจอร์หลัก
- 🎯 สร้างและจัดการกิจกรรมได้ไม่จำกัด
- 👥 ระบบลงทะเบียนเข้าร่วมพร้อมการอนุมัติ
- 🔐 OTP เช็คอินแบบเรียลไทม์
- 📊 แดชบอร์ดสถิติและการวิเคราะห์
- 📱 รองรับทุกอุปกรณ์ (Responsive Design)
- 🎨 ดีไซน์โดดเด่นแบบ Neo-brutalism

### สถาปัตยกรรมระบบ
- **Backend:** PHP 8.2 + Apache
- **Database:** MySQL 8.0
- **Frontend:** Tailwind CSS + Google Fonts (Kanit)
- **Icons:** Material Symbols Outlined
- **Container:** Docker + Docker Compose

### ลิงก์สำคัญ
- [📁 โครงสร้างโปรเจค](./01-project-structure/)
- [💾 ฐานข้อมูล](./02-database/)
- [⚙️ การตั้งค่า](./03-configuration/)
- [🛣️ Routes & API](./04-routes/)
- [🎨 Templates](./05-templates/)
- [🔧 Helpers](./06-helpers/)
- [✨ ฟีเจอร์](./07-features/)
- [🚀 การติดตั้ง](./08-setup/)
- [🔒 ความปลอดภัย](./09-security/)
- [🔧 แก้ไขปัญหา](./10-troubleshooting/)

---

## เริ่มต้นใช้งานอย่างรวดเร็ว

```bash
# 1. Clone โปรเจค
git clone https://github.com/K1Dev-Core/InviGo.git
cd InviGo

# 2. เริ่ม Docker
docker-compose up -d

# 3. นำเข้าฐานข้อมูล
docker-compose exec mysql mysql -u root -p invigo < database.sql

# 4. เข้าใช้งาน
# http://localhost:8888
```

---

## ติดต่อและสนับสนุน

- **GitHub:** https://github.com/K1Dev-Core/InviGo
- **Issues:** แจ้งปัญหาผ่าน GitHub Issues
- **License:** MIT License

---

**จัดทำโดย:** K1Dev | **วันที่:** กุมภาพันธ์ 2024
