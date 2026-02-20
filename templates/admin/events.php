<?php include __DIR__ . '/../header.php'; ?>
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black flex items-center gap-2">
                <span class="material-symbols-outlined text-4xl text-green-500">event</span>
                จัดการกิจกรรม
            </h1>
            <p class="text-gray-500">ดูและจัดการกิจกรรมทั้งหมดในระบบ</p>
        </div>
        <a href="/admin" class="neo-btn bg-gray-100 py-2 px-4 font-bold inline-flex items-center gap-2">
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

    <div class="neo-box bg-white border-2 border-black shadow-[4px_4px_0px_0px_black] overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b-2 border-black">
                <tr>
                    <th class="text-left p-4 font-black">ID</th>
                    <th class="text-left p-4 font-black">ชื่อกิจกรรม</th>
                    <th class="text-left p-4 font-black">วันที่</th>
                    <th class="text-left p-4 font-black">สถานที่</th>
                    <th class="text-left p-4 font-black">ผู้จัด</th>
                    <th class="text-left p-4 font-black">สถานะ</th>
                    <th class="text-center p-4 font-black">ลงทะเบียน</th>
                    <th class="text-center p-4 font-black">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($events as $event): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="p-4"><?= $event['id'] ?></td>
                    <td class="p-4 font-bold"><?= sanitize($event['title']) ?></td>
                    <td class="p-4"><?= date('d/m/Y H:i', strtotime($event['event_date'])) ?></td>
                    <td class="p-4"><?= sanitize($event['location']) ?></td>
                    <td class="p-4"><?= sanitize($event['creator']) ?></td>
                    <td class="p-4">
                        <?php if ($event['status'] === 'active'): ?>
                            <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs font-bold border border-green-500">Active</span>
                        <?php elseif ($event['status'] === 'cancelled'): ?>
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold border border-red-500">Cancelled</span>
                        <?php else: ?>
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold border border-gray-500"><?= ucfirst($event['status']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-center">
                        <span class="font-bold"><?= $event['total_registrations'] ?></span>
                        <?php if ($event['total_registrations'] > 0): ?>
                            <a href="/admin/event-registrations?id=<?= $event['id'] ?>" class="inline-block ml-2 text-blue-500 hover:text-blue-700" title="จัดการการลงทะเบียน">
                                <span class="material-symbols-outlined text-sm">manage_accounts</span>
                            </a>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-center">
                        <div class="flex justify-center gap-2">
                            <a href="/events/<?= $event['id'] ?>" class="text-blue-500 hover:text-blue-700" title="ดู">
                                <span class="material-symbols-outlined">visibility</span>
                            </a>
                            <form method="POST" action="/admin/events" class="inline" onsubmit="return confirm('แน่ใจหรือไม่ว่าต้องการลบกิจกรรมนี้?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
                                <button type="submit" class="text-red-500 hover:text-red-700" title="ลบ">
                                    <span class="material-symbols-outlined">delete</span>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
