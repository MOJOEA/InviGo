-- Test Users Data for InviGo System
-- ผู้ใช้ทดสอบ 3 คนสำหรับระบบ InviGo

-- คนที่ 1: ผู้จัดกิจกรรม (Organizer)
INSERT INTO Users (email, password, name, birth_date, gender, profile_image, created_at) 
VALUES (
    'organizer@test.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'สมชาย ใจดี',
    '1990-05-15',
    'male',
    NULL,
    NOW()
);

-- คนที่ 2: ผู้เข้าร่วมคนที่ 1 (Participant 1)
INSERT INTO Users (email, password, name, birth_date, gender, profile_image, created_at) 
VALUES (
    'user1@test.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'สมหญิง รักกีฬา',
    '1995-08-20',
    'female',
    NULL,
    NOW()
);

-- คนที่ 3: ผู้เข้าร่วมคนที่ 2 (Participant 2)
INSERT INTO Users (email, password, name, birth_date, gender, profile_image, created_at) 
VALUES (
    'user2@test.com',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password: 'password'
    'อนนท์ ชอบเที่ยว',
    '1992-12-10',
    'male',
    NULL,
    NOW()
);

-- ข้อมูลสำหรับการทดสอบ:
-- Organizer: organizer@test.com / password
-- User 1:    user1@test.com / password  
-- User 2:    user2@test.com / password
