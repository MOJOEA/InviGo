<?php
$isFull = ($event['approved_count'] ?? 0) >= ($event['max_participants'] ?? 1);
$endDate = $event['end_date'] ?? $event['event_date'] ?? null;
$isPast = $endDate && strtotime($endDate) < time();
$isOwnEvent = isset($event['user_id']) && isset($_SESSION['user_id']) && $event['user_id'] == $_SESSION['user_id'];
$isRegistered = ($event['status'] ?? '') !== '' || ($event['user_registration_status'] ?? 0) > 0;
?>
<div class="neo-card overflow-hidden flex flex-col h-full">
    <a href="/events/<?= $event['id'] ?>" class="block">
        <div class="h-40 bg-gray-200 border-b-2 border-black relative">
            <?php if (!empty($event['image'])): ?>
                <img src="<?= sanitize($event['image']) ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x200'">
            <?php else: ?>
                <div class="w-full h-full flex items-center justify-center bg-gray-100">
                    <span class="material-symbols-outlined text-6xl text-gray-300">event</span>
                </div>
            <?php endif; ?>
            <span class="absolute top-2 right-2 bg-white border-2 border-black px-2 py-1 text-xs font-black rounded <?= $isPast ? 'bg-gray-200' : '' ?>">
                <?= formatThaiDate($event['event_date'] ?? '') ?>
            </span>
            <?php if ($isPast): ?>
                <span class="absolute top-2 left-2 bg-gray-500 text-white border-2 border-black px-2 py-1 text-xs font-black rounded">สิ้นสุดแล้ว</span>
            <?php endif; ?>
        </div>
        <div class="p-4 flex-1 flex flex-col">
            <h3 class="font-black text-lg mb-1"><?= sanitize($event['title'] ?? '') ?></h3>
            <p class="text-sm text-gray-500 mb-2 flex-1"><?= sanitize(substr($event['description'] ?? '', 0, 100)) ?><?= strlen($event['description'] ?? '') > 100 ? '...' : '' ?></p>
            <?php if (!empty($event['location'])): ?>
                <p class="text-xs text-gray-400 mb-3 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm">location_on</span>
                    <?= sanitize($event['location']) ?>
                </p>
            <?php endif; ?>
            <?php if (isset($event['organizer_name'])): ?>
                <p class="text-xs text-gray-400 mb-3">
                    โดย: <?= sanitize($event['organizer_name']) ?>
                </p>
            <?php endif; ?>
            <?php if (isset($event['pending_count'])): ?>
                <div class="flex gap-2 mb-3 text-xs">
                    <?php if ($event['pending_count'] > 0): ?>
                        <span class="bg-yellow-100 px-2 py-1 rounded border border-black font-bold">รอ: <?= $event['pending_count'] ?></span>
                    <?php endif; ?>
                    <span class="bg-green-100 px-2 py-1 rounded border border-black font-bold">อนุมัติ: <?= formatParticipantCount($event['approved_count'], $event['max_participants']) ?></span>
                    <?php if ($event['checked_in_count'] > 0): ?>
                        <span class="bg-blue-100 px-2 py-1 rounded border border-black font-bold">มาแล้ว: <?= $event['checked_in_count'] ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <div class="flex justify-between items-center pt-3 border-t-2 border-dashed border-gray-200">
                <?php if (!isset($event['pending_count'])): ?>
                    <span class="text-xs font-bold bg-[#D4FF33] px-2 py-1 rounded border border-black flex items-center gap-1 <?= $isFull ? 'bg-red-100' : '' ?>">
                        <span class="material-symbols-outlined text-sm">group</span>
                        <?= formatParticipantCount($event['approved_count'] ?? 0, $event['max_participants'] ?? 0) ?>
                    </span>
                <?php else: ?>
                    <span></span>
                <?php endif; ?>
                <span class="text-xs text-gray-400">ดูรายละเอียด →</span>
            </div>
        </div>
    </a>
    <?php if ($isOwnEvent || isset($event['registration_status']) || $isRegistered || (!$isFull && !$isPast)): ?>
        <div class="p-3 pt-0">
            <?php if ($isOwnEvent): ?>
                <a href="/events/<?= $event['id'] ?>/manage" class="neo-btn-small bg-[#FFE600] px-4 py-1 text-sm font-bold hover:bg-[#ffe100] inline-flex items-center gap-1 w-full justify-center">
                    <span class="material-symbols-outlined text-sm">settings</span> จัดการ
                </a>
            <?php elseif (isset($event['registration_status']) || $isRegistered): ?>
                <?php 
                    $status = $event['registration_status'] ?? ($event['status'] ?? 'pending');
                    include TEMPLATES_DIR . '/partials/status-badge.php';
                ?>
            <?php elseif (!$isFull && !$isPast): ?>
                <form method="POST" action="/events/<?= $event['id'] ?>/join" class="inline w-full">
                    <button type="submit" class="neo-btn-small bg-[#40E0D0] px-4 py-1 text-sm font-bold hover:bg-[#3dd1c2] w-full">เข้าร่วม</button>
                </form>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
