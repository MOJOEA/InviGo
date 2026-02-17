<?php include 'header.php' ?>
<?php
$expiresTime = strtotime($expires_at);
$now = time();
$remaining = max(0, $expiresTime - $now);
$minutes = floor($remaining / 60);
$seconds = $remaining % 60;
?>
<div class="max-w-md mx-auto">
    <a href="/my-registrations" class="flex items-center gap-1 font-bold text-gray-500 hover:text-black mb-4">
        <span class="material-symbols-outlined">arrow_back</span> กลับ
    </a>
    <div class="neo-box p-8 text-center">
        <div class="w-20 h-20 bg-[#FFE600] border-2 border-black rounded-xl flex items-center justify-center mx-auto mb-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <span class="material-symbols-outlined text-5xl">qr_code</span>
        </div>
        <h1 class="text-2xl font-black mb-2">รหัส OTP ของคุณ</h1>
        <p class="text-gray-500 font-bold mb-6"><?= sanitize($event['event_title']) ?></p>
        <div class="bg-black text-white rounded-xl p-6 mb-6">
            <p class="text-sm text-gray-400 mb-2">รหัส 6 หลัก</p>
            <p class="text-5xl font-black tracking-widest font-mono"><?= $otp_code ?></p>
        </div>
        <div class="bg-red-50 border-2 border-red-200 rounded-xl p-4 mb-6">
            <p class="text-red-600 font-bold flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">timer</span>
                <span>หมดอายุใน <span id="countdown" class="font-mono"><?= sprintf('%02d:%02d', $minutes, $seconds) ?></span></span>
            </p>
        </div>
        <div class="text-sm text-gray-500 space-y-1">
            <p><span class="font-bold">วันที่:</span> <?= formatThaiDateTime($event['event_date']) ?></p>
            <p><span class="font-bold">สถานที่:</span> <?= sanitize($event['location']) ?></p>
            <p><span class="font-bold">ผู้จัด:</span> <?= sanitize($event['organizer_name']) ?></p>
        </div>
        <div class="mt-6 p-4 bg-yellow-50 rounded-xl border-2 border-yellow-300">
            <p class="text-sm font-bold text-yellow-800">
                <span class="material-symbols-outlined inline-block mr-1">info</span>
                แสดงรหัสนี้ให้ผู้จัดงานเพื่อเช็คชื่อเข้างาน
            </p>
        </div>
    </div>
</div>
<script>
    let remaining = <?= $remaining ?>;
    const countdownEl = document.getElementById('countdown');
    const timer = setInterval(() => {
        remaining--;
        if (remaining <= 0) {
            clearInterval(timer);
            countdownEl.textContent = 'หมดอายุแล้ว';
            countdownEl.parentElement.classList.add('text-red-600');
        } else {
            const mins = Math.floor(remaining / 60);
            const secs = remaining % 60;
            countdownEl.textContent = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }
    }, 1000);
</script>
<?php include 'footer.php' ?>
