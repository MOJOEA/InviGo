<?php include 'header.php' ?>
<?php
function genderLabel(string $gender): string {
    return match($gender) {
        'male' => 'ชาย',
        'female' => 'หญิง',
        'other' => 'อื่นๆ',
        default => 'ไม่ระบุ'
    };
}
$totalApproved = $stats['approved'] ?? 0;
$maleCount = $genderStats['male'] ?? 0;
$femaleCount = $genderStats['female'] ?? 0;
$otherCount = $genderStats['other'] ?? 0;
$malePct = $totalApproved > 0 ? round(($maleCount / $totalApproved) * 100) : 0;
$femalePct = $totalApproved > 0 ? round(($femaleCount / $totalApproved) * 100) : 0;
$otherPct = $totalApproved > 0 ? round(($otherCount / $totalApproved) * 100) : 0;
$age1825 = $ageStats['18-25'] ?? 0;
$age2635 = $ageStats['26-35'] ?? 0;
$age36plus = $ageStats['36+'] ?? 0;
$age1825Pct = $totalApproved > 0 ? round(($age1825 / $totalApproved) * 100) : 0;
$age2635Pct = $totalApproved > 0 ? round(($age2635 / $totalApproved) * 100) : 0;
$age36plusPct = $totalApproved > 0 ? round(($age36plus / $totalApproved) * 100) : 0;
?>
<a href="/my-events" class="flex items-center gap-1 font-bold text-gray-500 hover:text-black mb-4">
    <span class="material-symbols-outlined">arrow_back</span> กลับหน้ารวม
</a>
<div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
    <div>
        <h1 class="text-3xl font-black"><?= sanitize($event['title']) ?></h1>
        <p class="text-gray-500 font-bold">Dashboard & Check-in</p>
    </div>
    <div class="bg-white border-2 border-black p-2 rounded-xl flex gap-2 shadow-[4px_4px_0px_0px_rgba(0,0,0,0.1)]">
        <form method="POST" action="/events/<?= $event['id'] ?>/manage" class="flex gap-2">
            <input type="text" name="otp_code" placeholder="กรอก OTP 6 หลัก" maxlength="6"
                class="border-2 border-black rounded-lg px-3 py-2 w-32 text-center font-bold tracking-widest outline-none">
            <button type="submit" class="neo-btn-small bg-[#40E0D0] px-4 py-2 font-bold flex items-center gap-2 hover:bg-[#3dd1c2]">
                <span class="material-symbols-outlined">qr_code_scanner</span> ตรวจสอบ
            </button>
        </form>
    </div>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="neo-box p-4 text-center">
        <p class="text-xs font-black text-gray-400 uppercase">ทั้งหมด</p>
        <p class="text-3xl font-black"><?= $stats['total'] ?? 0 ?></p>
    </div>
    <div class="neo-box p-4 text-center bg-yellow-50">
        <p class="text-xs font-black text-yellow-600 uppercase">รออนุมัติ</p>
        <p class="text-3xl font-black"><?= $stats['pending'] ?? 0 ?></p>
    </div>
    <div class="neo-box p-4 text-center bg-green-50">
        <p class="text-xs font-black text-green-600 uppercase">อนุมัติแล้ว</p>
        <p class="text-3xl font-black"><?= $stats['approved'] ?? 0 ?></p>
    </div>
    <div class="neo-box p-4 text-center bg-blue-50">
        <p class="text-xs font-black text-blue-600 uppercase">มางานแล้ว</p>
        <p class="text-3xl font-black"><?= $stats['checked_in'] ?? 0 ?></p>
    </div>
</div>
<?php if ($totalApproved > 0): ?>
<h3 class="font-black text-xl mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">analytics</span> สถิติผู้เข้าร่วม
</h3>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
    <div class="neo-box p-4">
        <h4 class="font-bold mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-500">wc</span> เพศ
        </h4>
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold w-16">ชาย</span>
                <div class="flex-1 bg-gray-200 rounded-full h-4 border border-black overflow-hidden">
                    <div class="bg-blue-400 h-full" style="width: <?= $malePct ?>%"></div>
                </div>
                <span class="text-sm font-bold w-8"><?= $maleCount ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold w-16">หญิง</span>
                <div class="flex-1 bg-gray-200 rounded-full h-4 border border-black overflow-hidden">
                    <div class="bg-pink-400 h-full" style="width: <?= $femalePct ?>%"></div>
                </div>
                <span class="text-sm font-bold w-8"><?= $femaleCount ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold w-16">อื่นๆ</span>
                <div class="flex-1 bg-gray-200 rounded-full h-4 border border-black overflow-hidden">
                    <div class="bg-purple-400 h-full" style="width: <?= $otherPct ?>%"></div>
                </div>
                <span class="text-sm font-bold w-8"><?= $otherCount ?></span>
            </div>
        </div>
    </div>
    <div class="neo-box p-4">
        <h4 class="font-bold mb-3 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-500">cake</span> ช่วงอายุ
        </h4>
        <div class="space-y-2">
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold w-20">18-25</span>
                <div class="flex-1 bg-gray-200 rounded-full h-4 border border-black overflow-hidden">
                    <div class="bg-green-400 h-full" style="width: <?= $age1825Pct ?>%"></div>
                </div>
                <span class="text-sm font-bold w-8"><?= $age1825 ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold w-20">26-35</span>
                <div class="flex-1 bg-gray-200 rounded-full h-4 border border-black overflow-hidden">
                    <div class="bg-yellow-400 h-full" style="width: <?= $age2635Pct ?>%"></div>
                </div>
                <span class="text-sm font-bold w-8"><?= $age2635 ?></span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-sm font-bold w-20">36+</span>
                <div class="flex-1 bg-gray-200 rounded-full h-4 border border-black overflow-hidden">
                    <div class="bg-orange-400 h-full" style="width: <?= $age36plusPct ?>%"></div>
                </div>
                <span class="text-sm font-bold w-8"><?= $age36plus ?></span>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
<div class="flex gap-2 mb-6">
    <a href="/events/<?= $event['id'] ?>/edit" class="neo-btn-small bg-[#D4FF33] px-4 py-2 font-bold flex items-center gap-2 hover:bg-[#cbf531]">
        <span class="material-symbols-outlined">edit</span> แก้ไขกิจกรรม
    </a>
</div>
<h3 class="font-black text-xl mb-4 flex items-center gap-2">
    <span class="material-symbols-outlined">group</span> รายชื่อผู้เข้าร่วม (<?= count($registrations) ?>)
</h3>
<?php if (empty($registrations)): ?>
    <div class="neo-box p-8 text-center">
        <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">group_off</span>
        <p class="text-gray-500 font-bold">ยังไม่มีผู้ลงทะเบียน</p>
    </div>
<?php else: ?>
    <div class="space-y-3">
        <?php foreach ($registrations as $reg): 
            $age = $reg['birth_date'] ? calculateAge($reg['birth_date']) : null;
            $statusClass = match($reg['status']) {
                'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-500',
                'approved' => $reg['checked_in'] ? 'bg-blue-100 text-blue-700 border-blue-500' : 'bg-green-100 text-green-700 border-green-500',
                'rejected' => 'bg-red-100 text-red-700 border-red-500',
                default => 'bg-gray-100'
            };
            $statusLabel = match($reg['status']) {
                'pending' => 'รออนุมัติ',
                'approved' => $reg['checked_in'] ? 'CHECKED IN' : 'อนุมัติแล้ว',
                'rejected' => 'ปฏิเสธ',
                default => $reg['status']
            };
        ?>
            <div class="neo-box p-3 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full border-2 border-black overflow-hidden bg-gray-100 flex-shrink-0">
                        <img src="<?= sanitize($reg['profile_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                             alt="Profile" 
                             class="w-full h-full object-cover"
                             onerror="this.src='https://api.dicebear.com/9.x/dylan/svg'">
                    </div>
                    <div>
                        <p class="font-bold text-sm"><?= sanitize($reg['name']) ?> 
                            <?php if ($age): ?>
                                <span class="text-xs text-gray-400">(<?= $age ?> ปี)</span>
                            <?php endif; ?>
                        </p>
                        <p class="text-xs text-gray-500"><?= sanitize($reg['email']) ?></p>
                        <p class="text-xs text-gray-400">
                            <?= genderLabel($reg['gender'] ?? '') ?> • 
                            สมัคร <?= date('d/m/Y', strtotime($reg['created_at'])) ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <?php if ($reg['status'] === 'pending'): ?>
                        <a href="/events/<?= $event['id'] ?>/manage?action=approve&registration_id=<?= $reg['id'] ?>" 
                            class="p-2 bg-green-200 border-2 border-black rounded hover:bg-green-300" title="อนุมัติ">
                            <span class="material-symbols-outlined text-sm">check</span>
                        </a>
                        <a href="/events/<?= $event['id'] ?>/manage?action=reject&registration_id=<?= $reg['id'] ?>" 
                            class="p-2 bg-red-200 border-2 border-black rounded hover:bg-red-300" title="ปฏิเสธ">
                            <span class="material-symbols-outlined text-sm">close</span>
                        </a>
                    <?php else: ?>
                        <span class="text-xs font-bold px-2 py-1 rounded border-2 <?= $statusClass ?>">
                            <?= $statusLabel ?>
                        </span>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
<?php include 'footer.php' ?>
