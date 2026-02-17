
## Database functions
- การเข้าถึงและจัดการข้อมูลในฐานข้อมูล
  
## สร้างฟังก์ชันเพื่อจัดการข้อมูลใน table Students

- เรียกใช้ฟังก์ชันและส่งคืนผลลัพธ์
- สามารถเรียกใช้ได้ทุกที่

![[Pasted image 20251218082557.png]]

databases/students.php
```php
<?php
// ฟังก์ชันสำหรับดึงข้อมูลนักเรียนจากฐานข้อมูล
function getStudents(): mysqli_result|bool
{
    global $conn;
    $sql = 'select * from students';
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}
```

## กำหนดค่าในไฟล์ database เพื่อโหลด database function ต่างๆ

includes/database.php
```php
<?php

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

// database functions ต่างๆ
require_once DATABASES_DIR . '/students.php';
```

---
# ทดลองดึงข้อมูลจาก database

templates/header.php
```php
<html>

<head></head>

<body>
    <header>
        <h1>WebSite Name</h1>
    </header>
    <nav>
        <a href="/">หน้าแรก</a>
        <a href="/contact">ติดต่อเรา</a>
        <a href='/students'>ข้อมูลนักเรียน</a>
    </nav>
```

## สร้าง route และ  view

![[Pasted image 20251218084116.png]]

routes/students.php
```php
<?php

renderView('students', ['title' => 'Student Information']);
```

templates/students.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1><?= $data['title'] ?></h1>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```

![[Pasted image 20251218084640.png]]

## เรียกข้อมูลจากฐานข้อมูล โดยใช้ database functions
- Query ข้อมูลก่อนเรนเดอร์วิว
- วัตถุประสงค์หลักสำหรับ controller

routes/students.php
```php
<?php
$result = getStudents();
renderView('students_get', array('result' => $result));
```

## แสดงข้อมูลที่ได้รับเป็น JSON

templates/students.php
```php
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1><?= $data['title'] ?></h1>
        <?php
        if ($data['result'] != []) {
	        while ($row = $data['result']->fetch_object()) {
	        ?>
	            <?= json_encode($row) ?>
	            <br>
	     <?php
	        }
        }
        ?>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->
```

![[Pasted image 20251218084858.png]]

---
## แสดงข้อมูลที่ได้รับเป็น HTML
- fetch as object, associative array

templates/students.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1><?= $data['title'] ?></h1>
        <form action="students" method="get">
            <input type="text" name="keyword" />
            <button type="submit">Search</button>
        </form>
        <?php
        if ($data['result'] != []) {
            while ($row = $data['result']->fetch_object()) {
        ?>
                <?= $row->student_id ?>
                <?= $row->first_name ?>
                <?= $row->last_name ?>
                <?= $row->phone_number ?>
                <?= $row->email ?>
                <br>
        <?php
            }
        }
        ?>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>

```

![[Pasted image 20251218085108.png]]

---
## ค้นหาด้วย keyword
- แก้ไขมุมมองนักเรียนโดยการเพิ่มช่องค้นหา
- ส่ง keyword ไปยัง GET /students

- สร้าง Form สำหรับส่งข้อมูล
templates/students.php
```php
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1><?= $data['title'] ?></h1>
        <form action="students" method="get">
            <input type="text" name="keyword" />
            <button type="submit">Search</button>
        </form>
        <?php
        while ($row = $data['result']->fetch_object()) {
        ?>
            <?= $row->student_id ?>
            <?= $row->first_name ?>
            <?= $row->last_name ?>
            <?= $row->phone_number ?>
            <?= $row->email ?>
            <br>
        <?php
        }
        ?>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->
```

- ตั้งค่าเริ่มต้นของข้อมูลเป็น array ว่าง
routes/students.php
```php
<?php

$result = [];
renderView('students', ['title' => 'Student Information', 'result' => $result]);
```

![[Pasted image 20251218091445.png]]

routes/students.php
```php
<?php

$result = [];
$keyword = $_GET['keyword'] ?? '';
if ($keyword == '') {    
    // ดึงข้อมูลนักเรียนทั้งหมด
    $result = getStudents();
}else {
    // ค้นหาข้อมูลนักเรียนตามคำค้นหา

}
renderView('students', ['title' => 'Student Information', 'result' => $result]);
```


![[Pasted image 20241113231246.png]]

## เพิ่มฟังก์ชันค้นหานักเรียนด้วยคีย์เวิร์ด

databases/students.php
```php
<?php
// ฟังก์ชันสำหรับดึงข้อมูลนักเรียนจากฐานข้อมูล
function getStudents(): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'select * from students';
    $result = $conn->query($sql);
    $conn->close();
    return $result;
}

function getStudentsByKeyword(string $keyword): mysqli_result|bool
{
    $conn = getConnection();
    $sql = 'select * from students where first_name like ? or last_name like ?';
    $stmt = $conn->prepare($sql);
    $keyword = '%'. $keyword .'%';
    $stmt->bind_param('ss',$keyword, $keyword);
    $res = $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
```

## ตรวจสอบ request ไปยัง /students หากมี keyword
- เรียกใช้ฟังก์ชันที่กำหนดไว้

routes/students.php
```php
<?php

$result = [];
$keyword = $_GET['keyword'] ?? '';
if ($keyword == '') {    
    // ดึงข้อมูลนักเรียนทั้งหมด
    $result = getStudents();
}else {
    // ค้นหาข้อมูลนักเรียนตามคำค้นหา
    $result = getStudentsByKeyword($keyword);

}
renderView('students', ['title' => 'Student Information', 'result' => $result]);
```

![[Pasted image 20251218092433.png]]

---
