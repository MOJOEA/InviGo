<?php include 'header.php' ?>
<div class="flex justify-between items-center mb-6">
    <h2 class="text-3xl font-black flex items-center gap-2">
        กิจกรรมของฉัน 
        <span class="material-symbols-outlined text-3xl">calendar_month</span>
    </h2>
    <a href="/events/create" class="neo-btn-small bg-[#FFE600] px-4 py-2 font-bold inline-flex items-center gap-1 hover:bg-[#ffe100]">
        <span class="material-symbols-outlined">add</span> สร้าง
    </a>
</div>
<?php if (empty($events)): ?>
    <div class="text-center py-12">
        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">event_note</span>
        <p class="text-gray-500 font-bold text-lg">ยังไม่มีกิจกรรม</p>
        <p class="text-gray-400 text-sm mb-6">สร้างกิจกรรมแรกของคุณเลย!</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Add New Event Card -->
        <a href="/events/create" class="neo-card bg-white rounded-2xl border-2 border-black overflow-hidden opacity-50 border-dashed hover:opacity-80 transition-opacity">
            <div class="h-full flex flex-col items-center justify-center p-10 text-gray-400 gap-4 min-h-[300px]">
                <span class="material-symbols-outlined text-6xl">add_circle</span>
                <span class="font-black text-lg">เพิ่มกิจกรรมใหม่</span>
            </div>
        </a>
        <?php foreach ($events as $event): 
            $now = new DateTime();
            $endDateStr = $event['end_date'] ?? $event['event_date'];
            $eventDate = new DateTime($endDateStr);
            $isPast = $eventDate < $now;
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
                        <span class="absolute top-2 right-2 bg-white border-2 border-black px-2 py-1 text-xs font-black rounded <?= $isPast ? 'bg-gray-200' : '' ?>">
                            <?= formatThaiDate($event['event_date']) ?>
                        </span>
                        <?php if ($isPast): ?>
                            <span class="absolute top-2 left-2 bg-gray-500 text-white border-2 border-black px-2 py-1 text-xs font-black rounded">สิ้นสุดแล้ว</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="font-black text-lg mb-1"><?= sanitize($event['title']) ?></h3>
                        <p class="text-sm text-gray-500 mb-3 flex-1"><?= sanitize(substr($event['description'] ?? '', 0, 80)) ?><?= strlen($event['description'] ?? '') > 80 ? '...' : '' ?></p>
                        <div class="flex gap-2 mb-3 text-xs">
                            <?php if ($event['pending_count'] > 0): ?>
                                <span class="bg-yellow-100 px-2 py-1 rounded border border-black font-bold">รอ: <?= $event['pending_count'] ?></span>
                            <?php endif; ?>
                            <span class="bg-green-100 px-2 py-1 rounded border border-black font-bold">อนุมัติ: <?= formatParticipantCount($event['approved_count'], $event['max_participants']) ?></span>
                            <?php if ($event['checked_in_count'] > 0): ?>
                                <span class="bg-blue-100 px-2 py-1 rounded border border-black font-bold">มาแล้ว: <?= $event['checked_in_count'] ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
                <div class="p-4 pt-0">
                    <div class="flex gap-2 pt-3 border-t-2 border-dashed border-gray-200">
                        <a href="/events/<?= $event['id'] ?>/manage" class="neo-btn-small flex-1 bg-[#FFE600] py-2 text-sm font-bold hover:bg-[#ffe100] text-center inline-flex items-center justify-center gap-1">
                            <span class="material-symbols-outlined text-sm">settings</span> จัดการ
                        </a>
                        <a href="/events/<?= $event['id'] ?>/edit" class="neo-btn-small bg-[#D4FF33] px-3 py-2 hover:bg-[#cbf531]">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                        <button type="button" onclick="confirmDelete(<?= $event['id'] ?>, '<?= sanitize($event['title']) ?>')" class="neo-btn-small bg-red-100 px-3 py-2 text-red-600 hover:bg-red-200">
                            <span class="material-symbols-outlined text-sm">delete</span>
                        </button>
                    </div>
                    <form id="delete-form-<?= $event['id'] ?>" method="POST" action="/events/<?= $event['id'] ?>/delete" class="hidden"></form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include 'footer.php' ?>
