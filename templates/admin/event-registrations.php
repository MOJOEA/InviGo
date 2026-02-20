<?php include __DIR__ . '/../header.php'; ?>
<?php
function genderLabel(string $gender): string {
    return match($gender) {
        'male' => 'ชาย',
        'female' => 'หญิง',
        'other' => 'อื่นๆ',
        default => 'ไม่ระบุ'
    };
}
function getStatusBadge(string $status, bool $checkedIn = false): string {
    if ($status === 'approved' && $checkedIn) {
        return '<span class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-xs font-bold border border-blue-500">เช็คชื่อแล้ว</span>';
    }
    return match($status) {
        'pending' => '<span class="bg-yellow-100 text-yellow-700 px-2 py-1 rounded text-xs font-bold border border-yellow-500">รออนุมัติ</span>',
        'approved' => '<span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold border border-green-500">อนุมัติแล้ว</span>',
        'rejected' => '<span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold border border-red-500">ปฏิเสธ</span>',
        default => '<span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold border border-gray-500">' . $status . '</span>'
    };
}
?>
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black flex items-center gap-2">
                <span class="material-symbols-outlined text-4xl text-blue-500">manage_accounts</span>
                จัดการการลงทะเบียน
            </h1>
            <p class="text-gray-500">กิจกรรม: <?= sanitize($event['title']) ?></p>
        </div>
        <a href="/admin/events" class="neo-btn bg-gray-100 py-2 px-4 font-bold inline-flex items-center gap-2">
            <span class="material-symbols-outlined">arrow_back</span>
            กลับ
        </a>
    </div>

    <?php if (!empty($success)): ?>
        <div class="mb-4 p-4 bg-green-100 border-2 border-green-500 text-green-700 rounded-lg font-bold">
            <?= sanitize($success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <div class="mb-4 p-4 bg-red-100 border-2 border-red-500 text-red-700 rounded-lg">
            <?php foreach ($errors as $error): ?>
                <p class="font-bold"><?= sanitize($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="neo-box bg-white border-2 border-black shadow-[4px_4px_0px_0px_black] p-4 mb-4">
        <div class="flex flex-wrap gap-4 text-sm">
            <span><strong>วันที่:</strong> <?= date('d/m/Y H:i', strtotime($event['event_date'])) ?></span>
            <span><strong>สถานที่:</strong> <?= sanitize($event['location']) ?></span>
            <span><strong>ผู้จัด:</strong> <?= sanitize($event['creator']) ?></span>
            <span><strong>จำนวนผู้ลงทะเบียน:</strong> <?= count($registrations) ?> คน</span>
        </div>
    </div>

    <?php if (empty($registrations)): ?>
        <div class="neo-box p-8 text-center">
            <span class="material-symbols-outlined text-5xl text-gray-300 mb-3">group_off</span>
            <p class="text-gray-500 font-bold">ยังไม่มีผู้ลงทะเบียน</p>
        </div>
    <?php else: ?>
        <div class="neo-box bg-white border-2 border-black shadow-[4px_4px_0px_0px_black] overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-100 border-b-2 border-black">
                    <tr>
                        <th class="text-left p-4 font-black">ผู้ลงทะเบียน</th>
                        <th class="text-left p-4 font-black">ข้อมูล</th>
                        <th class="text-left p-4 font-black">สถานะ</th>
                        <th class="text-left p-4 font-black">วันที่ลงทะเบียน</th>
                        <th class="text-center p-4 font-black">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($registrations as $reg): 
                        $age = $reg['birth_date'] ? calculateAge($reg['birth_date']) : null;
                    ?>
                    <tr class="border-b border-gray-200 hover:bg-gray-50">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full border-2 border-black overflow-hidden bg-gray-100 flex-shrink-0">
                                    <img src="<?= sanitize($reg['profile_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                                         alt="Profile" 
                                         class="w-full h-full object-cover"
                                         onerror="this.src='https://api.dicebear.com/9.x/dylan/svg'">
                                </div>
                                <div>
                                    <p class="font-bold"><?= sanitize($reg['name']) ?></p>
                                    <p class="text-xs text-gray-500"><?= sanitize($reg['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4">
                            <p class="text-sm"><?= genderLabel($reg['gender'] ?? '') ?> 
                                <?php if ($age): ?>
                                    <span class="text-gray-500">(<?= $age ?> ปี)</span>
                                <?php endif; ?>
                            </p>
                        </td>
                        <td class="p-4">
                            <?= getStatusBadge($reg['status'], $reg['checked_in']) ?>
                        </td>
                        <td class="p-4">
                            <?= date('d/m/Y H:i', strtotime($reg['created_at'])) ?>
                        </td>
                        <td class="p-4 text-center">
                            <form method="POST" action="/admin/event-registrations?id=<?= $event['id'] ?>" class="inline" onsubmit="return confirm('แน่ใจหรือไม่ว่าต้องการลบการลงทะเบียนของ <?= sanitize($reg['name']) ?>?');">
                                <input type="hidden" name="action" value="delete_registration">
                                <input type="hidden" name="registration_id" value="<?= $reg['id'] ?>">
                                <button type="submit" class="text-red-500 hover:text-red-700" title="ลบการลงทะเบียน">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
