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
            <h2 class="text-2xl font-black flex items-center gap-2">
                <?= sanitize($user['name']) ?>
                <?php if (($user['role'] ?? 0) == 1): ?>
                <span class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center" title="Admin">
                    <span class="material-symbols-outlined text-white text-sm">check</span>
                </span>
                <?php endif; ?>
            </h2>
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
        
        <div class="border-t-2 border-dashed border-gray-200 pt-4 mt-4">
            <h3 class="font-bold mb-3">การตั้งค่า</h3>
            <div class="flex items-center justify-between mb-3">
                <span class="text-sm text-gray-500">เสียงเอฟเฟกต์</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" id="soundToggle" class="sr-only peer">
                    <div class="w-14 h-8 bg-gray-200 border-2 border-black peer-checked:bg-[#fb7185] transition-colors duration-200"></div>
                    <div class="absolute left-[4px] top-[4px] bg-white border-2 border-black h-6 w-6 transition-all duration-200 peer-checked:translate-x-full"></div>
                </label>
            </div>
            <button onclick="showTutorial()" class="w-full text-left text-sm text-gray-500 hover:text-black flex items-center gap-2 py-2">
                <span class="material-symbols-outlined">school</span>
                ดูการแนะนำใช้งานอีกครั้ง
            </button>
        </div>
    </div>
    
    <div class="bg-white border-2 border-black rounded-xl p-6 mb-6 shadow-[4px_4px_0px_0px_black]">
        <h3 class="font-bold mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined">history</span> ประวัติการเข้าร่วมกิจกรรม
        </h3>
        <style>
        .accordion-item { border-bottom: 2px solid black; }
        .accordion-item:last-child { border-bottom: none; }
        .accordion-header {
            width: 100%;
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: bold;
            background: white;
            transition: background 0.2s;
        }
        .accordion-header:hover { background: #f9fafb; }
        .accordion-icon {
            width: 32px;
            height: 32px;
            border: 2px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            box-shadow: 2px 2px 0 0 black;
            transition: transform 0.3s;
        }
        .accordion-item.active .accordion-icon { transform: rotate(180deg); }
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s cubic-bezier(0, 1, 0, 1);
        }
        .accordion-item.active .accordion-content {
            max-height: 2000px;
            transition: max-height 0.3s cubic-bezier(1, 0, 1, 0);
        }
        </style>
        <div class="border-2 border-black rounded-xl overflow-hidden shadow-[4px_4px_0px_0px_black]">
            <div class="accordion-item border-b-2 border-black">
                <button class="accordion-header" onclick="toggleAccordion(this)">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-green-500">event_upcoming</span>
                        กำลังจะมาถึง (<?= count(array_filter($registrations, fn($r) => strtotime($r['event_date'] ?? 'now') >= time())) ?>)
                    </span>
                    <span class="accordion-icon"><span class="material-symbols-outlined">expand_more</span></span>
                </button>
                <div class="accordion-content">
                    <div class="p-4">
                        <?php
                        $upcomingRegs = array_filter($registrations, fn($r) => strtotime($r['event_date'] ?? 'now') >= time());
                        if (empty($upcomingRegs)): ?>
                            <p class="text-gray-400 text-center py-4">ไม่มีกิจกรรมที่กำลังจะมาถึง</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($upcomingRegs as $reg): ?>
                                    <a href="/events/<?= $reg['event_id'] ?>" class="block border-2 border-black rounded-lg p-3 hover:bg-gray-50 transition-colors">
                                        <p class="font-bold"><?= sanitize($reg['title'] ?? 'ไม่ระบุชื่อ') ?></p>
                                        <p class="text-sm text-gray-500"><?= formatThaiDate($reg['event_date'] ?? '') ?></p>
                                        <span class="inline-block mt-2 text-xs font-bold px-2 py-1 rounded border-2 <?= match($reg['status']) { 'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-500', 'approved' => $reg['checked_in'] ? 'bg-blue-100 text-blue-700 border-blue-500' : 'bg-green-100 text-green-700 border-green-500', 'rejected' => 'bg-red-100 text-red-700 border-red-500', default => 'bg-gray-100' } ?>">
                                            <?= match($reg['status']) { 'pending' => 'รออนุมัติ', 'approved' => $reg['checked_in'] ? 'CHECKED IN' : 'อนุมัติแล้ว', 'rejected' => 'ปฏิเสธ', default => $reg['status'] } ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <button class="accordion-header" onclick="toggleAccordion(this)">
                    <span class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-gray-500">event_busy</span>
                        ที่ผ่านมา (<?= count(array_filter($registrations, fn($r) => strtotime($r['event_date'] ?? 'now') < time())) ?>)
                    </span>
                    <span class="accordion-icon"><span class="material-symbols-outlined">expand_more</span></span>
                </button>
                <div class="accordion-content">
                    <div class="p-4">
                        <?php
                        $pastRegs = array_filter($registrations, fn($r) => strtotime($r['event_date'] ?? 'now') < time());
                        if (empty($pastRegs)): ?>
                            <p class="text-gray-400 text-center py-4">ไม่มีประวัติการเข้าร่วม</p>
                        <?php else: ?>
                            <div class="space-y-3">
                                <?php foreach ($pastRegs as $reg): ?>
                                    <a href="/events/<?= $reg['event_id'] ?>" class="block border-2 border-black rounded-lg p-3 hover:bg-gray-50 transition-colors opacity-75">
                                        <p class="font-bold"><?= sanitize($reg['title'] ?? 'ไม่ระบุชื่อ') ?></p>
                                        <p class="text-sm text-gray-500"><?= formatThaiDate($reg['event_date'] ?? '') ?></p>
                                        <span class="inline-block mt-2 text-xs font-bold px-2 py-1 rounded border-2 <?= match($reg['status']) { 'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-500', 'approved' => $reg['checked_in'] ? 'bg-blue-100 text-blue-700 border-blue-500' : 'bg-green-100 text-green-700 border-green-500', 'rejected' => 'bg-red-100 text-red-700 border-red-500', default => 'bg-gray-100' } ?>">
                                            <?= match($reg['status']) { 'pending' => 'รออนุมัติ', 'approved' => $reg['checked_in'] ? 'CHECKED IN' : 'อนุมัติแล้ว', 'rejected' => 'ปฏิเสธ', default => $reg['status'] } ?>
                                        </span>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="flex gap-3">
        <a href="/profile/edit" class="neo-btn flex-1 bg-[#FFE600] py-3 font-bold text-center inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">edit</span> แก้ไขโปรไฟล์
        </a>
        <?php if (($user['role'] ?? 0) == 1): ?>
        <a href="/admin" class="neo-btn flex-1 bg-blue-600 text-white py-3 font-bold text-center inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">admin_panel_settings</span>
            จัดการระบบ
        </a>
        <?php endif; ?>
    </div>
    
    <div class="mt-4">
        <a href="/logout" class="neo-btn w-full bg-red-100 text-red-600 py-3 font-bold text-center inline-flex items-center justify-center gap-2">
            <span class="material-symbols-outlined">logout</span> ออกจากระบบ
        </a>
    </div>
</div>

<script>
function toggleAccordion(button) {
    const item = button.parentElement;
    document.querySelectorAll('.accordion-item').forEach(otherItem => {
        if (otherItem !== item) otherItem.classList.remove('active');
    });
    item.classList.toggle('active');
}

function getCookie(name) {
    const value = '; ' + document.cookie;
    const parts = value.split('; ' + name + '=');
    if (parts.length === 2) return parts.pop().split(';').shift();
    return null;
}

function setCookie(name, value, days) {
    const expires = new Date(Date.now() + days * 864e5).toUTCString();
    document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
}

const soundToggle = document.getElementById('soundToggle');
const soundEnabled = getCookie('soundEnabled') !== 'false';
soundToggle.checked = soundEnabled;

soundToggle.addEventListener('change', function() {
    setCookie('soundEnabled', this.checked, 365);
});
</script>

<?php include 'footer.php' ?>
