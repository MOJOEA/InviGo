<?php include 'header.php' ?>

<main class="container mx-auto px-2 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-12 flex-grow">
    <?php if (isset($data['message'])) { ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= $data['message'] ?>
        </div>
    <?php } ?>
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center">วิชาที่ลงทะเบียนแล้ว</h2>
        </div>

        <div class="p-3 sm:p-4 lg:p-8">
            <?php if ($data['enrollments']->num_rows == 0) { ?>
                <p class="text-center text-gray-600">ยังไม่ได้ลงทะเบียนวิชาใดๆ</p>
            <?php } else { ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php while ($enrollment = $data['enrollments']->fetch_assoc()) { ?>
                        <div class="bg-white border-2 border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                            <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= $enrollment['course_name'] ?></h3>
                            <p class="text-sm text-gray-600 mb-2">รหัสวิชา: <?= $enrollment['course_code'] ?></p>
                            <p class="text-sm text-gray-600 mb-4">อาจารย์: <?= $enrollment['instructor'] ?></p>
                            <p class="text-sm text-gray-600 mb-4">วันที่ลงทะเบียน: <?= $enrollment['enrollment_date'] ?></p>
                            <a href="#" onclick="showConfirmModal('ต้องการถอนวิชานี้หรือไม่?', () => window.location.href = '/withdraw/<?= $enrollment['course_id'] ?>'); return false;" class="block w-full bg-gradient-to-r from-red-600 to-red-700 text-white py-2 px-4 rounded-lg hover:from-red-700 hover:to-red-800 transition text-center">ถอนวิชา</a>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<?php include 'footer.php' ?>
