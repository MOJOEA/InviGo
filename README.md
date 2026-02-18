# InviGo 🎫

Final-Project-WebDevelopment

demo : https://invigo.k1god.com


figma: https://www.figma.com/design/Z4eBHhrhVZYCcl3CfG1nMd/Event?node-id=0-1&t=x3cgNM9N2hFLIUPc-1

ระบบจัดการลงทะเบียนกิจกรรมแบบ Neo-brutalism

## เริ่มต้นใช้งาน

```bash
git clone https://github.com/K1Dev-Core/InviGo.git
cd InviGo
docker-compose up -d
docker-compose exec mysql mysql -u root -prootpassword invigo < database.sql
```

เข้าใช้งาน: http://localhost:8888

## ฟีเจอร์หลัก

- 🎯 สร้างและจัดการกิจกรรม
- 👥 ระบบลงทะเบียน + อนุมัติ
- 🔐 OTP เช็คอิน
- 📊 สถิติแดชบอร์ด
- 📱 รองรับมือถือ

## เทคโนโลยี

- PHP 8.2 + MySQL 8.0
- Tailwind CSS + Neo-brutalism Design
- Docker

## เอกสาร

📚 [ดูเอกสารฉบับเต็ม](./docs/)

## License

MIT License
