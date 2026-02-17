<?php include __DIR__ . '/../header.php'; ?>
<div class="max-w-6xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black flex items-center gap-2">
                <span class="material-symbols-outlined text-4xl text-blue-500">people</span>
                จัดการผู้ใช้
            </h1>
            <p class="text-gray-500">ดูและจัดการผู้ใช้ทั้งหมดในระบบ</p>
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
                    <th class="text-left p-4 font-black">ชื่อ</th>
                    <th class="text-left p-4 font-black">อีเมล</th>
                    <th class="text-left p-4 font-black">วันเกิด</th>
                    <th class="text-left p-4 font-black">เพศ</th>
                    <th class="text-left p-4 font-black">บทบาท</th>
                    <th class="text-left p-4 font-black">สมัครเมื่อ</th>
                    <th class="text-center p-4 font-black">จัดการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr class="border-b border-gray-200 hover:bg-gray-50">
                    <td class="p-4"><?= $user['id'] ?></td>
                    <td class="p-4 font-bold"><?= sanitize($user['name']) ?></td>
                    <td class="p-4"><?= sanitize($user['email']) ?></td>
                    <td class="p-4"><?= $user['birth_date'] ? date('d/m/Y', strtotime($user['birth_date'])) : '-' ?></td>
                    <td class="p-4"><?= $user['gender'] ? ucfirst($user['gender']) : '-' ?></td>
                    <td class="p-4">
                        <?php if ($user['role'] == 1): ?>
                            <span class="bg-red-100 text-red-700 px-2 py-1 rounded text-xs font-bold border border-red-500">Admin</span>
                        <?php else: ?>
                            <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs font-bold border border-gray-500">User</span>
                        <?php endif; ?>
                    </td>
                    <td class="p-4 text-sm"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></td>
                    <td class="p-4 text-center">
                        <form method="POST" action="/admin/users" class="inline" onsubmit="return confirm('แน่ใจหรือไม่ว่าต้องการลบผู้ใช้นี้?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                            <button type="submit" class="text-red-500 hover:text-red-700" title="ลบ">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php include __DIR__ . '/../footer.php'; ?>
