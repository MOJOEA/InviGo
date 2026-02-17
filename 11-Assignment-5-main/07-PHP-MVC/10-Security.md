# เฉพาะผู้ใช้ที่ผ่านการยืนยันตัวตนเท่านั้นที่จะเรียกเส้นทางได้
- ยกเว้น /home and /login

## สร้าง route login และ view

![[Pasted image 20251219153905.png]]

routes/login.php
```php
<?php

renderView('login');
```


templates/login.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>เข้าสู่ระบบ</h1>
        <form action="login" method="post">
            <label for="username">อีเมลผู้ใช้</label><br>
            <input type="text" name="email" id="email" /><br>
            <label for="password">รหัสผ่าน</label><br>
            <input type="password" name="password" id="password" /><br>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```

- รับการ login

routes/login.php
```php
<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($username === 'email' && $password === 'password') {
        header('Location: /students');
        exit;
    } else {
        renderView('login', ['error' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง']);
    }
} else {
    renderView('login');
}

```


![[Pasted image 20251219154828.png]]

ตรวจสอบ email และ (hashed) password

databases/students.php
```php
function checkLogin(string $email, string $password): bool
{
    global $conn;
    $sql = 'select password from students where email = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return password_verify($password, $row['password']);
    }
    return false;
}
```

![[Pasted image 20251219155652.png]]
## สมมติว่า Login สำเร็จ
- เพื่อให้ใช้งานระบบได้ อาจเก็บข้อมูลบางอย่างไว้ใน Session เช่น Timestamp เพื่อตรวจสอบการเข้าใช้งานของผู้ใช้

**routes/login_post.php**
```php
<?php

declare(strict_types=1);


// Assume that login success
$unix_timestamp = time();
$_SESSION['timestamp'] = $unix_timestamp;

header('Location: /');


```

![[Pasted image 20241114153947.png]]

## การตรวจสอบสถานะเข้าสู่ระบบ
- ตรวจสอบใน index.php
- อนุญาตเฉพาะ / และ /login
	- timestamp ที่จัดเก็บไว้หมายถึงเข้าสู่ระบบแล้ว
	- ถ้า timestamp ที่จัดเก็บไว้มีอายุมากกว่า xxx วินาที
		- ลบ timestamp และกลับไปยัง /home

public/index.php
```php
// ทุกครั้งที่มีการร้องขอเข้ามา ให้เรียกใช้ฟังก์ชัน dispatch
// dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);

// ควบคุมการเข้าถึงหน้าเว็บด้วย session (ตัวอย่างการใช้งาน)
const PUBLIC_ROUTES = ['/', '/login'];

if (in_array(strtolower($_SERVER['REQUEST_URI']), PUBLIC_ROUTES)) {
    dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
    exit;
} elseif (isset($_SESSION['timestamp']) && time() - $_SESSION['timestamp'] < 10) {
    // 10 Sec.
    $unix_timestamp = time();
    $_SESSION['timestamp'] = $unix_timestamp;
    dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
} else {
    unset($_SESSION['timestamp']);
    header('Location: /');
    exit;
}
```