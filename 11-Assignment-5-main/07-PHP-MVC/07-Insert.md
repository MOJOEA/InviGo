- เพิ่มข้อมูลลง Database
## สร้าง route และ view

![[Pasted image 20251218104642.png]]

routes/courses-new.php
```php
<?php

renderView('courses-new');
```

templates/courses-new.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>เพิ่มข้อมูลคอร์ส</h1>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```
## เพิ่ม Form เพื่อกรอกข้อมูล

templates/courses-new.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>เพิ่มข้อมูลคอร์ส</h1>
        <section>
            <form action="courses-new" method="post">
                <label for="code">Course Code</label><br>
                <input type="text" name="code" id="code" /><br>
                <label for="name">Course Name</label><br>
                <input type="text" name="name" id="name" /><br>
                <label for="instructor">Instructor</label><br>
                <input type="text" name="instructor" id="instructor" /><br>
                <button type="submit">Submit</button>
            </form>
        </section>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```


templates/header.php
```php
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
    <header>
        <h1>WebSite Name</h1>
    </header>
    <nav>
        <a href="/">หน้าแรก</a>
        <a href="/contact">ติดต่อเรา</a>
        <a href='/students'>ข้อมูลนักเรียน</a>
        <a href='/courses-new'>เพิ่มคอร์ส</a>
    </nav>
    <!-- Header และ Footer อาจแยกออกเป็นไฟล์แยกต่างหากได้ -->
```

![[Pasted image 20251218104746.png]]

---

## ฟังก์ชันสร้างคอร์ส
- แยกไฟล์ในโฟลเดอร์

databases/courses.php
```php
<?php
function insertCourse($course): bool
{
    global $conn;
    $sql = 'insert into courses (course_name, course_code, instructor) VALUES (?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss',$course['name'], $course['code'], $course['instructor']);
    $stmt->execute();
    if ($stmt->affected_rows > 0) {
        return true;
    } else {
        return false;
    }
}
```

includes/database.php
```php
// database functions ต่างๆ
require_once DATABASES_DIR . '/students.php';
require_once DATABASES_DIR . '/courses.php';
```
## การตรวจสอบรูปแบบ HTML Method
- Get แสดงหน้า
- Post รับข้อมูลเพื่อ insert

routes/courses-new.php
```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = [
        'name' => $_POST['name'] ?? '',
        'code' => $_POST['code'] ?? '',
        'instructor' => $_POST['instructor'] ?? '',
    ];
    if (insertCourse($course)) {
        renderView('courses-new');
    } else {
        echo "Error inserting course.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    renderView('courses-new');
}
```

![[Pasted image 20251218111228.png]]

## ตรวจสอบข้อมูลใน Table

![[Pasted image 20251218111716.png]]

## Confirmation dialog
- เพิ่ม ลบ บันทึกข้อมูล ควรมีการยืนยัน
- required
- ใช้ JavaScript ใน view

templates/courses-new.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>เพิ่มข้อมูลคอร์ส</h1>
        <section>
            <form action="courses-new" method="post" onsubmit="return confirmSubmission()">
                <label for="code">Course Code</label><br>
                <input type="text" name="code" id="code" /><br>
                <label for="name">Course Name</label><br>
                <input type="text" name="name" id="name" /><br>
                <label for="instructor">Instructor</label><br>
                <input type="text" name="instructor" id="instructor" /><br>
                <button type="submit">Submit</button>
            </form>
        </section>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
    <script>
        function confirmSubmission() {
            return confirm("ต้องการเพิ่มข้อมูลจริงหรือไม่ ?");
        }
    </script>
</body>

</html>
```

![[Pasted image 20241114085121.png]]

## การส่งข้อความตอบกลับหลังทำงานเสร็จ
- ส่งข้อความไปยังหน้า โดยตัวแปร `$data`

```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = [
        'name' => $_POST['name'] ?? '',
        'code' => $_POST['code'] ?? '',
        'instructor' => $_POST['instructor'] ?? '',
    ];
    if (insertCourse($course)) {
        renderView('courses-new', ['message' => 'เพิ่มข้อมูลคอร์สเรียบร้อยแล้ว']);
    } else {
        echo "Error inserting course.";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    renderView('courses-new');
}
```

## แสดงข้อความถ้ามี

templates/courses-new.php
```php
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>เพิ่มข้อมูลคอร์ส</h1>
        <?= $data['message'] ?? '' ?>
        <section>
```


![[Pasted image 20251218113019.png]]

---
