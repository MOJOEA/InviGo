# Model in MVC
- เข้าถึงและจัดการแหล่งข้อมูล
# การกำหนดค่าฐานข้อมูล

# MySQL ใน XAMPP
- Start MySQL Service 

![[Pasted image 20241113195343.png]]

# PhpMyAdmin โปรแกรมจัดการฐานข้อมูล MySQL
- http://localhost/phpmyadmin/
- default user: root (no password) 

![[Pasted image 20241113195248.png]]

# สร้าง User ใหม่

## **ไม่ให้ใช้ root ในการเขียนโปรแกรม โดยเด็ดขาด ถ้าใช้ จะหักคะแนน**

## New User 

![[Pasted image 20241113201734.png]]

![[Pasted image 20241113202105.png]]

User name: **demo**
Host name: **localhost**
Password: **abc123**

![[Pasted image 20241113202209.png]]

## New Database

![[Pasted image 20241113202332.png]]

## Grant permissions for Database

![[Pasted image 20241113202410.png]]

![[Pasted image 20241113202429.png]]

![[Pasted image 20241113202453.png]]

![[Pasted image 20241113202517.png]]

## Check created user

![[Pasted image 20241113202537.png]]


![[Pasted image 20241113202601.png]]

## Create Tables

![[Pasted image 20241113202825.png]]

![[Pasted image 20241113202743.png]]

## Create tables and data

```sql
	CREATE TABLE students (
	    student_id INT PRIMARY KEY AUTO_INCREMENT,
	    first_name VARCHAR(50) NOT NULL,
	    last_name VARCHAR(50) NOT NULL,
	    password VARCHAR(255) NULL,
	    image VARCHAR(1000) NOT NULL,
	    date_of_birth DATE,
	    email VARCHAR(100) UNIQUE,
	    phone_number VARCHAR(20)
	);
```

```sql
CREATE TABLE courses (
    course_id INT PRIMARY KEY AUTO_INCREMENT,
    course_name VARCHAR(100) NOT NULL,
    course_code VARCHAR(20) UNIQUE,
    instructor VARCHAR(50)
);
```

```sql
CREATE TABLE enrollment (
    enrollment_id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT,
    course_id INT,
    enrollment_date DATE,
    FOREIGN KEY (student_id) REFERENCES students(student_id),
    FOREIGN KEY (course_id) REFERENCES courses(course_id)
);
```

## Insert data

```sql
-- Insert 10 Courses
INSERT INTO courses (course_name, course_code, instructor)
VALUES
('Introduction to Programming', 'CS101', 'Prof. Smith'),
('Database Systems', 'DB201', 'Prof. Johnson'),
('Web Development', 'WEB301', 'Prof. Lee'),
('Data Structures and Algorithms', 'DSA401', 'Prof. Patel'),
('Artificial Intelligence', 'AI501', 'Prof. Kim'),
('Machine Learning', 'ML601', 'Prof. Chen'),
('Cybersecurity', 'CYB701', 'Prof. Harris'),
('Network Security', 'NS801', 'Prof. Wilson'),
('Software Engineering', 'SE901', 'Prof. Taylor'),
('Cloud Computing', 'CC1001', 'Prof. Garcia');

-- Insert 10 Students (without passwords)
INSERT INTO students (first_name, last_name, image, date_of_birth, email, phone_number)
VALUES
('Alice', 'Johnson', 'student1.jpg', '2000-01-01', 'alice.johnson@example.com', '123-456-7890'),
('Bob', 'Smith', 'student2.jpg', '2001-02-02', 'bob.smith@example.com', '987-654-3210'),
('Charlie', 'Brown', 'student3.jpg', '2002-03-03', 'charlie.brown@example.com', '555-555-5555'),
('David', 'Lee', 'student4.jpg', '2003-04-04', 'david.lee@example.com', '111-222-3333'),
('Emily', 'Davis', 'student5.jpg', '2004-05-05', 'emily.davis@example.com', '999-888-7777'),
('Frank', 'Miller', 'student6.jpg', '2005-06-06', 'frank.miller@example.com', '444-333-2222'),
('Grace', 'Wilson', 'student7.jpg', '2006-07-07', 'grace.wilson@example.com', '777-666-5555'),
('Henry', 'Moore', 'student8.jpg', '2007-08-08', 'henry.moore@example.com', '222-111-0000'),
('Isabella', 'Taylor', 'student9.jpg', '2008-09-09', 'isabella.taylor@example.com', '333-222-1111'),
('Jack', 'Anderson', 'student10.jpg', '2009-10-10', 'jack.anderson@example.com', '666-555-4444');

-- Insert Enrollment Data
INSERT INTO enrollment (student_id, course_id, enrollment_date)
VALUES
(1, 1, '2023-09-01'),
(1, 2, '2023-09-01'),
(2, 1, '2023-09-01'),
(2, 3, '2023-09-01'),
(3, 2, '2023-09-01'),
(3, 4, '2023-09-01'),
(4, 3, '2023-09-01'),
(4, 5, '2023-09-01'),
(5, 4, '2023-09-01'),
(5, 6, '2023-09-01'),
(6, 5, '2023-09-01'),
(6, 7, '2023-09-01');
```

## ตรวจสอบข้อมูล

![[Pasted image 20241113205202.png]]

## ตั้งค่าไฟล์เชื่อมต่อ
- database.php (เตรียมไว้ให้แล้ว)
- จุดเชื่อมต่อเพียงจุดเดียว
- เชื่อมต่อฐานข้อมูลโดยใช้ **mysqli**

![[Pasted image 20251218063900.png]]


includes/database.php
```php
<?php

$hostname = 'localhost';
$dbName = 'enrollment';
$username = 'demo';
$password = 'abc123';
$conn = new mysqli($hostname, $username, $password, $dbName);

function getConnection(): mysqli
{
    global $conn;
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
```


## ทดสอบการเชื่อมต่อ
- หากไม่พบข้อผิดพลาด ถือว่าสำเร็จ

routes/ping.php
```php
<?php
$conn = getConnection();
// แสดงผลการตอบสนองสำหรับเส้นทาง /ping
echo "Pong!!!";

```

## **ในกรณีที่เกิดข้อผิดพลาด**
- PHP ใน XAMPP จะไม่แสดงข้อผิดพลาด
	- HTTP Error 500 (ข้อผิดพลาดบนเซิร์ฟเวอร์)

![[Pasted image 20241113210339.png]]

## แสดงข้อผิดพลาดสำหรับนักพัฒนา

public/index.php
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
```

![[Pasted image 20251218065959.png]]

## เชื่อมต่อสำเร็จ
- ไม่แสดงข้อความ Error ใด

![[Pasted image 20251218070230.png]]

---
