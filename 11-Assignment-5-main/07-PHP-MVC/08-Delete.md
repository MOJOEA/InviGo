## ต้องยืนยันทุกครั้งก่อนลบข้อมูล

![[Pasted image 20251219062920.png]]

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

![[Pasted image 20251219062956.png]]

## ตรวจสอบและลบข้อมูล

routes/students-delete.php
```php
<?php

declare(strict_types=1);

if (!isset($_GET['id'])) {
    header('Location: /students');
    exit;
} else {
    $id = (int)$_GET['id'];    
    $res = deleteStudentsById($id);
    if ($res > 0) {
        header('Location: /students');
    } else {
        renderView('400', ['message' => 'Something went wrong! on delete student']);
    }
    
}
```

databases/students.php
```php
function deleteStudentsById(int $id): bool
{
    global $conn;
    $sql = 'delete from students where student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
    return $stmt->affected_rows > 0;
}
```

templates/400.php
```php
<html>

<head></head>

<body>
    <!-- ส่วนแสดงผลหลักของหน้า -->
    <main>
        <h1>Bad Request (Error:400)</h1>
        <p><?= $data['message'] ?? 'Your request is invalid.' ?></p>
    </main>
    <!-- ส่วนแสดงผลหลักของหน้า -->

</body>

</html>
```
## Add deletion function

**databases/students.php**
```php

function deleteStudentsById(int $id): bool
{
    $conn = getConnection();
    $sql = 'delete from students where student_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    try {
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {
        return false;
    }
}
```

# ทดสอบลบข้อมูล
- Referenced records cannot be delete

![[Pasted image 20251219063716.png]]

![[Pasted image 20251219063744.png]]


## ทดสอบเรียกการลบจาก URL (ที่ไม่มี id)


![[Pasted image 20251219063848.png]]

![[Pasted image 20251219064626.png]]
---
