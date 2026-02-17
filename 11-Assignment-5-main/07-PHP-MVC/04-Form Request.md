# การสร้าง form สำหรับ request ใน MVC

routes/contact.php (Controller)
```php
<?php

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        get();
        break;
    // กรณีอื่นๆ เช่น POST สามารถเพิ่มได้ที่นี่
}

function get(): void{
    // ประมวลผลก่อนแสดงผลหน้า
    renderView('contact', ['title' => 'Contact Us']);
}



```

templates/contact.php (View)
```php
<html>

<head></head>

<body>
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
    <header>
        <h1>WebSite Name</h1>
    </header>
    <nav>
        <a href="/">Home</a>
    </nav>
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>คิดต่อเรา</h1>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
    <footer>
        <p>
            &copy; <?= date('Y') ?>. All rights reserved by Aj.M.
        </p>
    </footer>
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
</body>

</html>
```

![[Pasted image 20251217215334.png]]

http://mvc.localhost/contact

![[Pasted image 20251217220548.png]]

# ปรับการแสดงผล header และ footer
- จะเห็นว่า code จะมีการซ้ำซ้อนกับไฟล์ home ที่ header และ footer
- ควรแยก header และ footer ออกไปเป็นไฟล์

![[Pasted image 20251217221301.png]]

templates/header.php
```php
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
    <header>
        <h1>WebSite Name</h1>
    </header>
    <nav>
        <a href="/">หน้าแรก</a>
        <a href="/contact">ติดต่อเรา</a>
    </nav>
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
```

templates/footer.php
```php
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
    <footer>
        <p>
            &copy; <?= date('Y') ?>. All rights reserved by Aj.M.
        </p>
    </footer>
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
```

templates/contact.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>ติดต่อเรา</h1>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <?php include 'footer.php' ?>

</body>

</html>
```

ผลลัพธ์ยังเหมือนเดิม

![[Pasted image 20251217221723.png]]

เปลี่ยน header ให้หน้าแรกด้วย

templates/home.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>Home</h1>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```


## Create สร้าง form
- ส่ง POST ไปยัง /contact (ส่งเข้า route เดิม)

templates/contact.php
```php
        <section>
            <h2>ติดต่อเรา</h2>
            <form method="POST">
                <label>ชื่อ</label><br>
                <input type="text" name="name" /><br>
                <label>อีเมล</label><br>
                <input type="email" name="email" /><br>
                <label>ข้อความ</label><br>
                <textarea rows="4" name="message"></textarea><br>
                <button type="submit">ส่งข้อความ</button>
            </form>
        </section>
```

![[Pasted image 20251217222027.png]]

---
# การจัดการ Request
- ตรวจสอบและประมวลข้อมูลที่ POST เข้ามา
- แล้วส่งต่อไปแสดงผลที่ view (thank.php)

routes/contact.php
```php
<?php

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        get();
        break;
    // กรณีอื่นๆ เช่น POST สามารถเพิ่มได้ที่นี่
    case 'POST':
        post();
        break;
}

function get(): void{
    // ประมวลผลก่อนแสดงผลหน้า
    renderView('contact', ['title' => 'Contact Us']);
}

function post(): void{
    // ประมวลผลคำขอแบบ POST ที่นี่ (ถ้ามี)
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    // mask email
    $email = preg_replace('/(?<=.).(?=[^@]*?.@)/', '*', $email);
    // แสดงหน้าขอบคุณหลังส่งข้อความ
    renderView('thank', ['name' => $name, 'email' => $email, 'message' => $message]);

}
```

templates/thank.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>ขอบคุณคุณ <?= $data['name'] ?></h1>
        <p><?= $data['email'] ?></p>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```

![[Pasted image 20251218061455.png]]


![[Pasted image 20251218061511.png]]

## เพิ่มการตรวจสอบข้อมูล
- ถ้ามีฟิลด์ข้อมูลว่าง ให้แจ้งเตือน และกลับไปกรอกใหม่ โดย Javascript

routes/contact.php
```php
function post(): void{
    // ประมวลผลคำขอแบบ POST ที่นี่ (ถ้ามี)
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $message = $_POST['message'] ?? '';
    // เช็คข้อมูลว่างแล้วเตือน
    if (empty($name) || empty($email) || empty($message)) {
        $message = "กรุณากรอกข้อมูลให้ครบถ้วน";
        echo "<script type='text/javascript'>alert('$message');window.history.back();</script>";
        return;
    } 
    // mask email
    $email = preg_replace('/(?<=.).(?=[^@]*?.@)/', '*', $email);
    // แสดงหน้าขอบคุณหลังส่งข้อความ
    renderView('thank', ['name' => $name, 'email' => $email, 'message' => $message]);

}
```

![[Pasted image 20251218062535.png]]

---
## การส่งต่อข้อมูลไปยัง View (Data Passing)
- เพิ่มไปยัง $data ที่จะถูกเรนเดอร์
- ไฟล์ทั้งหมดในเทมเพลตจาก renderView สามารถอ่านได้

![[Pasted image 20251218062747.png]]

![[Pasted image 20251218062837.png]]

![[Pasted image 20251218062945.png]]

![[Pasted image 20251218061511.png]]

---
