<?php include 'header.php' ?>

<main class="container mx-auto px-2 sm:px-4 lg:px-6 py-4 sm:py-6 lg:py-12 flex-grow">
    <div class="bg-white rounded-lg shadow-xl overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white py-4 sm:py-6 lg:py-8 px-3 sm:px-4 lg:px-6">
            <h2 class="text-xl sm:text-2xl lg:text-3xl font-bold text-center">เข้าสู่ระบบ</h2>
        </div>

        <div class="p-3 sm:p-4 lg:p-8">
            <form action="/login" method="post" class="max-w-md mx-auto">
                <?php if (isset($data['error'])) { ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <?= $data['error'] ?>
                    </div>
                <?php } ?>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">อีเมล</label>
                    <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">รหัสผ่าน</label>
                    <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" required>
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        เข้าสู่ระบบ
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include 'footer.php' ?>
