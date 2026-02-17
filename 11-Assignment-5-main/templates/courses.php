<?php include 'header.php' ?>

<main class="container mx-auto px-2 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-12 flex-grow">
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center">ค้นหาวิชาเรียน</h2>
        </div>

        <div class="p-3 sm:p-4 lg:p-8">
            <form action="/courses" method="get" class="mb-6">
                <div class="flex">
                    <input type="text" name="keyword" value="<?= htmlspecialchars($data['keyword']) ?>" placeholder="ค้นหาชื่อวิชาหรือรหัสวิชา" class="flex-grow shadow appearance-none border rounded-l py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">
                        ค้นหา
                    </button>
                </div>
            </form>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <?php
                $enrolledCourseIds = [];
                while ($enrollment = $data['enrollments']->fetch_assoc()) {
                    $enrolledCourseIds[] = $enrollment['course_id'];
                }
                $data['enrollments']->data_seek(0); // Reset pointer

                while ($course = $data['courses']->fetch_assoc()) {
                    $isEnrolled = in_array($course['course_id'], $enrolledCourseIds);
                    ?>
                    <div class="bg-white border-2 border-gray-200 rounded-lg p-4 hover:shadow-lg transition">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2"><?= $course['course_name'] ?></h3>
                        <p class="text-sm text-gray-600 mb-2">รหัสวิชา: <?= $course['course_code'] ?></p>
                        <p class="text-sm text-gray-600 mb-4">อาจารย์: <?= $course['instructor'] ?></p>
                        <?php if ($isEnrolled) { ?>
                            <span class="block w-full bg-gray-500 text-white py-2 px-4 rounded-lg text-center">ลงทะเบียนแล้ว</span>
                        <?php } else { ?>
                            <a href="#" onclick="showConfirmModal('ต้องการลงทะเบียนวิชานี้หรือไม่?', () => window.location.href = '/enroll/<?= $course['course_id'] ?>'); return false;" class="block w-full bg-gradient-to-r from-emerald-600 to-teal-600 text-white py-2 px-4 rounded-lg hover:from-emerald-700 hover:to-teal-700 transition text-center">ลงทะเบียน</a>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php' ?>
