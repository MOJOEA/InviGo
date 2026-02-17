<?php include 'header.php' ?>
<?php
function getStatusBadge(string $status, bool $checkedIn = false): string {
    if ($status === 'approved' && $checkedIn) {
        return '<span class="bg-blue-100 text-blue-700 border-blue-500 px-2 py-1 rounded border text-xs font-bold flex items-center gap-1"><span class="material-symbols-outlined text-sm">check_circle</span> เช็คชื่อแล้ว</span>';
    }
    return match($status) {
        'pending' => '<span class="bg-yellow-100 text-yellow-700 border-yellow-500 px-2 py-1 rounded border text-xs font-bold flex items-center gap-1"><span class="material-symbols-outlined text-sm">schedule</span> รออนุมัติ</span>',
        'approved' => '<span class="bg-green-100 text-green-700 border-green-500 px-2 py-1 rounded border text-xs font-bold flex items-center gap-1"><span class="material-symbols-outlined text-sm">check</span> อนุมัติแล้ว</span>',
        'rejected' => '<span class="bg-red-100 text-red-700 border-red-500 px-2 py-1 rounded border text-xs font-bold flex items-center gap-1"><span class="material-symbols-outlined text-sm">close</span> ปฏิเสธ</span>',
        default => '<span class="bg-gray-100 px-2 py-1 rounded border text-xs font-bold">' . $status . '</span>'
    };
}
?>
<h2 class="text-3xl font-black mb-6 flex items-center gap-2">
    การลงทะเบียนของฉัน 
    <span class="material-symbols-outlined text-3xl">confirmation_number</span>
</h2>
<?php if (empty($registrations)): ?>
    <div class="text-center py-12">
        <span class="material-symbols-outlined text-6xl text-gray-300 mb-4">inbox</span>
        <p class="text-gray-500 font-bold text-lg">ยังไม่มีการลงทะเบียน</p>
        <p class="text-gray-400 text-sm mb-6">ค้นหากิจกรรมที่น่าสนใจและลงทะเบียนเลย!</p>
        <a href="/explore" class="neo-btn inline-block bg-[#FFE600] px-6 py-3 font-bold">ค้นหากิจกรรม</a>
    </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($registrations as $reg): 
            $now = new DateTime();
            $endDateStr = $reg['end_date'] ?? $reg['event_date'];
            $eventDate = new DateTime($endDateStr);
            $isPast = $eventDate < $now;
            $isFull = $reg['approved_count'] >= $reg['max_participants'];
        ?>
            <div class="neo-box p-4 flex flex-col md:flex-row gap-4">
                <a href="/events/<?= $reg['event_id'] ?>" class="w-full md:w-32 h-24 bg-gray-200 rounded-lg border-2 border-black overflow-hidden flex-shrink-0 block">
                    <?php if ($reg['image']): ?>
                        <img src="<?= sanitize($reg['image']) ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x200'">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <span class="material-symbols-outlined text-3xl text-gray-300">event</span>
                        </div>
                    <?php endif; ?>
                </a>
                <div class="flex-1">
                    <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                        <a href="/events/<?= $reg['event_id'] ?>" class="font-black text-lg hover:text-[#40E0D0]"><?= sanitize($reg['title']) ?></a>
                        <?= getStatusBadge($reg['status'], $reg['checked_in']) ?>
                    </div>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="material-symbols-outlined text-sm inline">calendar_month</span>
                        <?= formatThaiDateTime($reg['event_date']) ?>
                    </p>
                    <p class="text-sm text-gray-500 mb-2">
                        <span class="material-symbols-outlined text-sm inline">location_on</span>
                        <?= sanitize($reg['location']) ?>
                    </p>
                    <p class="text-xs text-gray-400">
                        ผู้จัด: <?= sanitize($reg['organizer_name']) ?> • 
                        ลงทะเบียนเมื่อ <?= date('d/m/Y', strtotime($reg['created_at'])) ?>
                    </p>
                </div>
                <div class="flex flex-row md:flex-col gap-2 justify-end">
                    <?php if ($reg['status'] === 'approved' && !$reg['checked_in'] && !$isPast): ?>
                        <?php if ($reg['otp']): ?>
                            <button onclick="showOtpModal('<?= $reg['otp'] ?>', '<?= date('H:i', strtotime($reg['otp_expires'] ?? '+30 minutes')) ?>')" 
                                class="neo-btn-small bg-[#40E0D0] px-4 py-2 text-sm font-bold inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                เช็คอิน
                            </button>
                        <?php else: ?>
                            <a href="/events/<?= $reg['event_id'] ?>/otp" class="neo-btn-small bg-[#40E0D0] px-4 py-2 text-sm font-bold inline-flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">qr_code</span>
                                ขอ OTP
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                    <?php if (!$reg['checked_in'] && !$isPast): ?>
                        <a href="/events/<?= $reg['event_id'] ?>/withdraw" 
                            onclick="return confirm('ยืนยันการยกเลิกการลงทะเบียน?')"
                            class="neo-btn-small bg-red-100 text-red-600 px-4 py-2 text-sm font-bold inline-flex items-center gap-1 hover:bg-red-200">
                            <span class="material-symbols-outlined text-sm">close</span>
                            ยกเลิก
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- OTP Modal -->
<div id="otpModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border-4 border-black rounded-2xl p-6 max-w-sm w-full shadow-[8px_8px_0px_0px_black]">
        <div class="text-center">
            <h3 class="text-xl font-black mb-2">รหัส OTP เช็คอิน</h3>
            <p class="text-sm text-gray-500 mb-4">แสดงรหัสนี้ให้ผู้จัดงานสแกน</p>
            
            <div class="bg-[#D4FF33] border-4 border-black rounded-xl p-4 mb-4">
                <span id="otpCode" class="text-4xl font-black tracking-widest"></span>
            </div>
            
            <p class="text-sm text-gray-500 mb-6">
                หมดอายุ: <span id="otpExpires" class="font-bold text-red-500"></span> น.
            </p>
            
            <button onclick="closeOtpModal()" class="neo-btn bg-gray-200 px-6 py-2 font-bold w-full">
                ปิด
            </button>
        </div>
    </div>
</div>

<script>
function showOtpModal(otp, expires) {
    document.getElementById('otpCode').textContent = otp;
    document.getElementById('otpExpires').textContent = expires;
    document.getElementById('otpModal').classList.remove('hidden');
}

function closeOtpModal() {
    document.getElementById('otpModal').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('otpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOtpModal();
    }
});
</script>

<?php include 'footer.php' ?>
