<?php include 'header.php' ?>
<?php 
$userId = getCurrentUserId();
?>
<h2 class="text-3xl font-black mb-6 flex items-center gap-2">
    ค้นหากิจกรรม 
    <span class="material-symbols-outlined text-3xl">explore</span>
</h2>
<div class="bg-white border-2 border-black rounded-xl p-4 mb-8 shadow-[4px_4px_0px_0px_black]">
    <form method="GET" action="/explore" id="searchForm">
        <div class="flex gap-2 mb-4">
            <input type="text" name="search" value="<?= sanitize($search ?? '') ?>" placeholder="พิมพ์ชื่อกิจกรรม..." class="neo-input w-full p-2 outline-none">
            <button type="submit" class="neo-btn-small bg-[#40E0D0] px-4 py-2 font-bold">
                <span class="material-symbols-outlined">search</span>
            </button>
        </div>
        <div class="flex flex-col md:flex-row gap-4 items-center">
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="font-bold text-sm whitespace-nowrap">วันที่เริ่ม:</label>
                <input type="date" name="start_date" value="<?= sanitize($startDate ?? '') ?>" class="neo-input p-2 outline-none flex-1" onchange="document.getElementById('searchForm').submit()">
            </div>
            <span class="hidden md:block font-bold">-</span>
            <div class="flex items-center gap-2 w-full md:w-auto">
                <label class="font-bold text-sm whitespace-nowrap">วันที่สิ้นสุด:</label>
                <input type="date" name="end_date" value="<?= sanitize($endDate ?? '') ?>" class="neo-input p-2 outline-none flex-1" onchange="document.getElementById('searchForm').submit()">
            </div>
        </div>
    </form>
</div>
<?php if (empty($events)): ?>
    <div class="text-center py-12">
        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">event_busy</span>
        <p class="text-gray-500 font-bold text-lg">ไม่พบกิจกรรม</p>
        <p class="text-gray-400 text-sm">ลองค้นหาด้วยคำค้นอื่นหรือช่วงวันที่อื่น</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($events as $event): 
            $isFull = $event['approved_count'] >= $event['max_participants'];
            $isOwnEvent = $event['user_id'] == $userId;
            $isRegistered = ($event['user_registration_status'] ?? 0) > 0;
            $endDateStr = $event['end_date'] ?? $event['event_date'];
            $endDate = new DateTime($endDateStr);
            $now = new DateTime();
            $isPast = $endDate < $now;
        ?>
            <div class="neo-card overflow-hidden flex flex-col">
                <a href="/events/<?= $event['id'] ?>" class="block">
                    <div class="h-40 bg-gray-200 border-b-2 border-black relative">
                        <?php if ($event['image']): ?>
                            <img src="<?= sanitize($event['image']) ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x200'">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <span class="material-symbols-outlined text-6xl text-gray-300">event</span>
                            </div>
                        <?php endif; ?>
                        <span class="absolute top-2 right-2 bg-white border-2 border-black px-2 py-1 text-xs font-black rounded">
                            <?= formatThaiDate($event['event_date']) ?>
                        </span>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <div class="flex items-start justify-between gap-2 mb-1">
                            <h3 class="font-black text-lg"><?= sanitize($event['title']) ?></h3>
                            <?php if ($isOwnEvent): ?>
                                <span class="text-xs font-bold px-2 py-1 bg-[#FFE600] rounded border border-black">เจ้าของ</span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-500 mb-2 flex-1"><?= sanitize(substr($event['description'] ?? '', 0, 100)) ?><?= strlen($event['description'] ?? '') > 100 ? '...' : '' ?></p>
                        <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">location_on</span>
                            <?= sanitize($event['location'] ?? 'ไม่ระบุสถานที่') ?>
                        </p>
                    </div>
                </a>
                <div class="p-4 pt-0">
                    <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-gray-200">
                        <span class="text-xs font-bold bg-[#D4FF33] px-2 py-1 rounded border border-black flex items-center gap-1 <?= $isFull ? 'bg-red-100' : '' ?>">
                            <span class="material-symbols-outlined text-sm">group</span>
                            <?= formatParticipantCount($event['approved_count'], $event['max_participants']) ?>
                        </span>
                        <?php if ($isOwnEvent): ?>
                            <a href="/events/<?= $event['id'] ?>/manage" class="neo-btn-small bg-[#D4FF33] px-4 py-1 text-sm font-bold hover:bg-[#cbf531] inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">settings</span> จัดการ
                            </a>
                        <?php elseif ($isRegistered): ?>
                            <span class="text-xs font-bold px-2 py-1 bg-blue-100 rounded border border-black">ลงทะเบียนแล้ว</span>
                        <?php elseif ($isFull): ?>
                            <span class="text-xs font-bold px-2 py-1 bg-red-100 text-red-600 rounded border border-black">เต็มแล้ว</span>
                        <?php elseif ($isPast): ?>
                            <span class="text-xs font-bold px-2 py-1 bg-gray-100 text-gray-500 rounded border border-black">สิ้นสุดแล้ว</span>
                        <?php elseif ($userId === 0): ?>
                            <a href="/login" class="neo-btn-small bg-gray-200 text-gray-600 px-4 py-1 text-sm font-bold hover:bg-gray-300 inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">login</span> เข้าสู่ระบบเพื่อเข้าร่วม
                            </a>
                        <?php else: ?>
                            <a href="/events/<?= $event['id'] ?>" class="neo-btn-small bg-[#40E0D0] px-4 py-1 text-sm font-bold hover:bg-[#3dd1c2] inline-flex items-center gap-1">
                                เข้าร่วม <span class="material-symbols-outlined text-sm">arrow_forward</span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include 'footer.php' ?>
