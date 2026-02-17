<?php include 'header.php' ?>
<div class="max-w-2xl mx-auto">
    <div class="bg-white border-2 border-black rounded-xl p-6 mb-6 shadow-[4px_4px_0px_0px_black]">
        <div class="flex flex-col items-center mb-6">
            <div class="w-32 h-32 rounded-full border-4 border-black overflow-hidden mb-4 bg-white">
                <img src="<?= sanitize($user['profile_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                     alt="Profile" 
                     class="w-full h-full object-cover"
                     onerror="this.src='https://api.dicebear.com/9.x/dylan/svg'">
            </div>
            <h2 class="text-2xl font-black"><?= sanitize($user['name']) ?></h2>
            <p class="text-gray-500"><?= sanitize($user['email']) ?></p>
        </div>
        
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-[#FFE600] border-2 border-black rounded-lg p-4 text-center">
                <span class="material-symbols-outlined text-3xl mb-1">calendar_month</span>
                <p class="text-2xl font-black"><?= count($events) ?></p>
                <p class="text-sm font-bold">กิจกรรมที่สร้าง</p>
            </div>
            <div class="bg-[#40E0D0] border-2 border-black rounded-lg p-4 text-center">
                <span class="material-symbols-outlined text-3xl mb-1">confirmation_number</span>
                <p class="text-2xl font-black"><?= count($registrations) ?></p>
                <p class="text-sm font-bold">การลงทะเบียน</p>
            </div>
        </div>
        
        <div class="border-t-2 border-dashed border-gray-200 pt-4">
            <h3 class="font-bold mb-3">ข้อมูลส่วนตัว</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">วันเกิด:</span>
                    <span class="font-bold"><?= $user['birth_date'] ? formatThaiDate($user['birth_date']) : 'ไม่ระบุ' ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">เพศ:</span>
                    <span class="font-bold"><?= $user['gender'] ? sanitize($user['gender']) : 'ไม่ระบุ' ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">สมัครสมาชิก:</span>
                    <span class="font-bold"><?= formatThaiDate($user['created_at'] ?? '') ?></span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex gap-3">
        <a href="/profile/edit" class="neo-btn flex-1 bg-[#FFE600] py-3 font-bold text-center inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">edit</span> แก้ไขโปรไฟล์
        </a>
    </div>
    
    <div class="mt-4">
        <a href="/logout" class="neo-btn w-full bg-red-100 text-red-600 py-3 font-bold text-center inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">logout</span> ออกจากระบบ
        </a>
    </div>
</div>

<?php include 'footer.php' ?>
