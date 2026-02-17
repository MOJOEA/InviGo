<?php include __DIR__ . '/../header.php'; ?>
<div class="max-w-6xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-black flex items-center gap-2">
            <span class="material-symbols-outlined text-4xl text-red-500">admin_panel_settings</span>
            แผงควบคุมระบบ (Admin)
        </h1>
        <p class="text-gray-500">จัดการผู้ใช้ กิจกรรม และข้อมูลระบบทั้งหมด</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="neo-box bg-white p-6 border-2 border-black shadow-[4px_4px_0px_0px_black]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-bold">ผู้ใช้ทั้งหมด</p>
                    <p class="text-3xl font-black"><?= number_format($stats['users']) ?></p>
                </div>
                <div class="w-14 h-14 bg-blue-100 border-2 border-black rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-blue-600">people</span>
                </div>
            </div>
            <a href="/admin/users" class="mt-4 block text-sm font-bold text-blue-600 hover:underline">จัดการผู้ใช้ →</a>
        </div>

        <div class="neo-box bg-white p-6 border-2 border-black shadow-[4px_4px_0px_0px_black]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-bold">กิจกรรมทั้งหมด</p>
                    <p class="text-3xl font-black"><?= number_format($stats['events']) ?></p>
                </div>
                <div class="w-14 h-14 bg-green-100 border-2 border-black rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-green-600">event</span>
                </div>
            </div>
            <a href="/admin/events" class="mt-4 block text-sm font-bold text-green-600 hover:underline">จัดการกิจกรรม →</a>
        </div>

        <div class="neo-box bg-white p-6 border-2 border-black shadow-[4px_4px_0px_0px_black]">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-bold">การลงทะเบียน</p>
                    <p class="text-3xl font-black"><?= number_format($stats['registrations']) ?></p>
                </div>
                <div class="w-14 h-14 bg-yellow-100 border-2 border-black rounded-xl flex items-center justify-center">
                    <span class="material-symbols-outlined text-3xl text-yellow-600">confirmation_number</span>
                </div>
            </div>
            <p class="mt-4 text-sm text-gray-500">ทั้งหมดในระบบ</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="neo-box bg-white p-6 border-2 border-black shadow-[4px_4px_0px_0px_black]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-black flex items-center gap-2">
                    <span class="material-symbols-outlined">person_add</span>
                    ผู้ใช้ล่าสุด
                </h2>
                <a href="/admin/users" class="text-sm font-bold text-blue-600 hover:underline">ดูทั้งหมด</a>
            </div>
            <div class="space-y-3">
                <?php foreach ($recentUsers as $user): ?>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="w-10 h-10 rounded-full border-2 border-black overflow-hidden bg-gray-100 flex-shrink-0">
                        <img src="<?= sanitize($user['profile_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                             alt="Profile" 
                             class="w-full h-full object-cover"
                             onerror="this.src='https://api.dicebear.com/9.x/dylan/svg'">
                    </div>
                    <div class="flex-1">
                        <p class="font-bold"><?= sanitize($user['name']) ?></p>
                        <p class="text-xs text-gray-500"><?= sanitize($user['email']) ?></p>
                    </div>
                    <span class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($user['created_at'])) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Recent Events -->
        <div class="neo-box bg-white p-6 border-2 border-black shadow-[4px_4px_0px_0px_black]">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-black flex items-center gap-2">
                    <span class="material-symbols-outlined">event_note</span>
                    กิจกรรมล่าสุด
                </h2>
                <a href="/admin/events" class="text-sm font-bold text-green-600 hover:underline">ดูทั้งหมด</a>
            </div>
            <div class="space-y-3">
                <?php foreach ($recentEvents as $event): ?>
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                        <span class="material-symbols-outlined text-green-600">event</span>
                    </div>
                    <div class="flex-1">
                        <p class="font-bold"><?= sanitize($event['title']) ?></p>
                        <p class="text-xs text-gray-500">โดย <?= sanitize($event['creator']) ?></p>
                    </div>
                    <span class="text-xs text-gray-400"><?= date('d/m/Y', strtotime($event['event_date'])) ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
