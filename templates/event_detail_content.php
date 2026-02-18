<?php include 'header.php' ?>
<?php
$endDate = $event['end_date'] ?? $event['event_date'];
$isPast = strtotime($endDate) < time();
$isFull = $approvedCount >= $event['max_participants'];
?>
<style>
.lightbox {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.9);
}
.lightbox.active {
    display: flex;
    align-items: center;
    justify-content: center;
}
.lightbox img {
    max-width: 90%;
    max-height: 90%;
    object-fit: contain;
}
.lightbox-close {
    position: absolute;
    top: 20px;
    right: 30px;
    color: white;
    font-size: 40px;
    cursor: pointer;
}
.clickable-image {
    cursor: zoom-in;
}
.large-date-badge {
    position: absolute;
    top: 1rem;
    left: 1rem;
    background: #FFE600;
    border: 3px solid black;
    padding: 0.75rem 1.25rem;
    transform: rotate(-2deg);
    box-shadow: 4px 4px 0 0 black;
    z-index: 10;
}
.large-date-badge .day {
    font-size: 2rem;
    font-weight: 900;
    line-height: 1;
}
.large-date-badge .month {
    font-size: 0.875rem;
    font-weight: 700;
    text-transform: uppercase;
}
.large-date-badge .year {
    font-size: 0.75rem;
    font-weight: 600;
}
.cta-back-btn {
    position: absolute;
    top: 1rem;
    right: 1rem;
    z-index: 10;
}
.hero-container {
    position: relative;
}
</style>
<div class="hero-container mb-6">
    <a href="/explore" class="cta-back-btn neo-btn bg-white w-12 h-12 flex items-center justify-center hover:bg-gray-100">
        <span class="material-symbols-outlined text-2xl">arrow_back</span>
    </a>
    
    <?php if (!empty($images)): ?>
        <div class="h-64 md:h-80 bg-gray-200 rounded-xl border-3 border-black overflow-hidden">
            <?php
            $eventDate = new DateTime($event['event_date']);
            $day = $eventDate->format('j');
            $month = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'][$eventDate->format('n') - 1];
            $year = $eventDate->format('Y') + 543;
            ?>
            <div class="large-date-badge rounded-lg">
                <div class="day"><?= $day ?></div>
                <div class="month"><?= $month ?></div>
                <div class="year"><?= $year ?></div>
            </div>
            <img src="<?= sanitize($images[0]['image_path']) ?>" class="w-full h-full object-cover clickable-image" onclick="openLightbox('<?= sanitize($images[0]['image_path']) ?>')">
        </div>
    <?php else: ?>
        <div class="h-64 md:h-80 bg-gray-100 flex items-center justify-center rounded-xl border-3 border-black relative">
            <?php
            $eventDate = new DateTime($event['event_date']);
            $day = $eventDate->format('j');
            $month = ['ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'][$eventDate->format('n') - 1];
            $year = $eventDate->format('Y') + 543;
            ?>
            <div class="large-date-badge rounded-lg">
                <div class="day"><?= $day ?></div>
                <div class="month"><?= $month ?></div>
                <div class="year"><?= $year ?></div>
            </div>
            <span class="material-symbols-outlined text-8xl text-gray-300">event</span>
        </div>
    <?php endif; ?>
</div>

<div class="neo-box overflow-hidden">
    <div class="p-6">
        <div class="flex flex-wrap gap-2 mb-3">
            <span class="bg-[#D4FF33] border-2 border-black px-3 py-1 text-sm font-bold rounded inline-flex items-center gap-1">
                <span class="material-symbols-outlined text-sm">event</span>
                <?= formatThaiDateTime($event['event_date']) ?>
                <?php if ($event['end_date'] && $event['end_date'] !== $event['event_date']): ?>
                    <span class="mx-1">-</span>
                    <span class="bg-[#40E0D0] px-2 py-0.5 rounded"><?= formatThaiDateTime($event['end_date']) ?></span>
                <?php endif; ?>
            </span>
            <?php if ($isPast): ?>
                <span class="bg-gray-500 text-white border-2 border-black px-3 py-1 text-sm font-bold rounded">สิ้นสุดแล้ว</span>
            <?php elseif ($isFull && !$isOwnEvent): ?>
                <span class="bg-red-100 text-red-600 border-2 border-black px-3 py-1 text-sm font-bold rounded">เต็มแล้ว</span>
            <?php endif; ?>
        </div>
        <h1 class="text-2xl md:text-3xl font-black mb-2"><?= sanitize($event['title']) ?></h1>
        <div class="flex items-center gap-3 mb-4">
            <div class="w-12 h-12 rounded-full border-2 border-black overflow-hidden bg-gray-100 flex-shrink-0">
                <img src="<?= sanitize($event['organizer_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                     alt="Organizer" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://api.dicebear.com/9.x/dylan/svg'">
            </div>
            <div class="flex items-center gap-2">
                <p class="text-sm text-gray-500">จัดโดย</p>
                <p class="font-bold text-lg flex items-center gap-1">
                    <?= sanitize($event['organizer_name']) ?>
                    <?php if (($event['organizer_role'] ?? 0) == 1): ?>
                        <span class="w-5 h-5 bg-blue-500 rounded-full flex items-center justify-center" title="Admin">
                            <span class="material-symbols-outlined text-white text-xs">check</span>
                        </span>
                    <?php endif; ?>
                </p>
            </div>
        </div>
        <div class="flex flex-wrap gap-4 mb-6 text-sm">
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined">location_on</span>
                <?= sanitize($event['location']) ?>
            </div>
            <div class="flex items-center gap-1">
                <span class="material-symbols-outlined">group</span>
                <?= formatParticipantCount($approvedCount, $event['max_participants']) ?> คน
            </div>
        </div>
        <div class="border-t-2 border-dashed border-gray-200 pt-4 mb-6">
            <h2 class="font-bold mb-2">รายละเอียด</h2>
            <p class="text-gray-600 whitespace-pre-line"><?= sanitize($event['description']) ?></p>
        </div>
        <?php if (count($images) > 1): ?>
            <div class="border-t-2 border-dashed border-gray-200 pt-4 mb-6">
                <h2 class="font-bold mb-3">รูปภาพเพิ่มเติม</h2>
                <div class="grid grid-cols-3 md:grid-cols-4 gap-2">
                    <?php for ($i = 1; $i < count($images); $i++): ?>
                        <img src="<?= sanitize($images[$i]['image_path']) ?>" class="w-full h-24 object-cover rounded-lg border-2 border-black clickable-image" onclick="openLightbox('<?= sanitize($images[$i]['image_path']) ?>')">
                    <?php endfor; ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="border-t-2 border-dashed border-gray-200 pt-4">
            <?php if ($isOwnEvent): ?>
                <div class="flex gap-3">
                    <a href="/events/<?= $event['id'] ?>/manage" class="neo-btn bg-[#FFE600] px-6 py-3 font-bold inline-flex items-center gap-2">
                        <span class="material-symbols-outlined">settings</span> จัดการกิจกรรม
                    </a>
                    <a href="/events/<?= $event['id'] ?>/edit" class="neo-btn bg-white px-6 py-3 font-bold inline-flex items-center gap-2">
                        <span class="material-symbols-outlined">edit</span> แก้ไข
                    </a>
                </div>
            <?php elseif ($registration): ?>
                <?php 
                    $status = $registration['status'];
                    include TEMPLATES_DIR . '/partials/status-badge.php';
                ?>
                <p class="text-sm text-gray-500 mt-2">
                    <?php if ($status === 'pending'): ?>
                        รอการอนุมัติจากผู้จัด
                    <?php elseif ($status === 'approved'): ?>
                        คุณได้รับอนุมัติแล้ว
                    <?php else: ?>
                        คำขอถูกปฏิเสธ
                    <?php endif; ?>
                </p>
            <?php elseif (!$isPast && !$isFull): ?>
                <form method="POST" action="/events/<?= $event['id'] ?>/join">
                    <button type="submit" class="neo-btn bg-[#40E0D0] px-8 py-3 font-bold inline-flex items-center gap-2">
                        <span class="material-symbols-outlined">add</span> เข้าร่วมกิจกรรม
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

<div id="lightbox" class="lightbox" onclick="closeLightbox()">
    <span class="lightbox-close">&times;</span>
    <img id="lightbox-img" src="">
</div>

<script>
function openLightbox(src) {
    document.getElementById('lightbox-img').src = src;
    document.getElementById('lightbox').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
    document.body.style.overflow = 'auto';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
<?php include 'footer.php' ?>
