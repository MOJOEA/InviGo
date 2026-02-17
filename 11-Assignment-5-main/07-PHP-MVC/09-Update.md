# 2 ขั้นตอน
- ดึงข้อมูลที่ต้องการแก้ไขขึ้นมา
- ทำการแก้ไขและบันทึก
## ดึงข้อมูลที่ต้องการแก้ไขขึ้นมา

![[Pasted image 20251219065110.png]]

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
                <a href="/students-delete?id=<?= $row->student_id ?>" onclick="return confirmSubmission()">ลบข้อมูล</a>
                <a href="/students-chgpwd?id=<?= $row->student_id ?>">เปลี่ยนรหัสผ่าน</a>

                <br>
        <?php
            }
        }
        ?>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <script>
        function confirmSubmission() {
            return confirm("ยืนยันการลบข้อมูล ?");
        }
    </script>

    <?php include 'footer.php' ?>
</body>

</html>
```

# เรียกฟังก์ชัน getStudentById

routes/students-chgpwd.php
```php
<?php


if (!isset($_GET['id'])) {
    header('Location: /students');
    exit;
} else {
    $id = (int)$_GET['id'];
    $res = getStudentById($id);
    if ($res) {
        renderView('students_chgpwd_get', array('result' => $res));
    } else {
        renderView('400',[ 'message' => 'Something went wrong! on query student']);
    }
}
```

databases/students.php
```php
function getStudentById(int $id): mysqli_result|bool
{
    global $conn;
    $sql = 'select * from students where student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result;
}
```


## แสดงข้อมูลการเปลี่ยนรหัสผ่าน

templates/students-chgpwd.php
```php
<html>

<head></head>

<body>
    <?php include 'header.php' ?>

    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>เปลี่ยนรหัสผ่าน</h1>
        <?php
        if (isset($data['result'])) {
            $row = $data['result']->fetch_object();
        ?>
            <label for="first_name"><?= $row->first_name ?></label> <label for="last_name"><?= $row->last_name ?></label><br>
            <form action="students-chgpwd?id=<?= $row->student_id ?>" method="post">
                <label for="password">Password</label><br>
                <input type="password" name="password"><br>
                <label for="confirmpassword">Confirm Password</label><br>
                <input type="password" name="confirm_password"><br>
                <button type="submit">Update</button>
            </form>
        <?php
        }
        ?>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

    <?php include 'footer.php' ?>
</body>

</html>
```

![[Pasted image 20251219070122.png]]

---

## เพิ่มฟังก์ชันเปลี่ยนรหัสผ่านโดยใช้ POST

![[Pasted image 20251219071958.png]]


- จัดการการรับ Request แบบ POST
- เข้ารหัส password

routes/students-chgpwd.php
```php
<?php


if (!isset($_GET['id'])) {
    header('Location: /students');
    exit;
} else {
    $id = (int)$_GET['id'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if ($password !== $confirm_password) {
            renderView('400', ['message' => 'Password and Confirm Password do not match']);
            exit;
        }

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $res = updateStudentPassword($id, $hashed_password);
        if ($res > 0) {
            header('Location: /students');
            exit;
        } else {
            renderView('400', ['message' => 'Something went wrong! on update password']);
            exit;
        }
    } else {        
        $res = getStudentById($id);
        if ($res) {
            renderView('students-chgpwd', array('result' => $res));
        } else {
            renderView('400', ['message' => 'Something went wrong! on query student']);
        }
    }
}

```


databases/students.php
```php
function updateStudentPassword(int $id, string $hashed_password): bool
{
    global $conn;
    $sql = 'update students set password = ? where student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('si', $hashed_password, $id);
    $stmt->execute();
    return  $stmt->affected_rows > 0;
}
```

![[Pasted image 20251219072401.png]]


## ตรวจสอบในตาราง

![[Pasted image 20251219072726.png]]

