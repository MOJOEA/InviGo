<?php
$isLoggedInMobile = !empty(getCurrentUser());
?>
<nav class="md:hidden fixed bottom-0 w-full bg-white border-t-2 border-black p-2 flex justify-around rounded-t-xl z-50">
    <a href="/explore" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'explore' ? 'text-black' : 'text-gray-400' ?>">
        <span class="material-symbols-outlined text-xl">search</span>
        <span class="text-[10px] font-bold">ค้นหา</span>
    </a>
    <?php if ($isLoggedInMobile): ?>
    <a href="/my-events" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'my-events' ? 'text-black' : 'text-gray-400' ?>">
        <span class="material-symbols-outlined text-xl">calendar_month</span>
        <span class="text-[10px] font-bold">กิจกรรม</span>
    </a>
    <a href="/my-registrations" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'my-registrations' ? 'text-black' : 'text-gray-400' ?>">
        <span class="material-symbols-outlined text-xl">confirmation_number</span>
        <span class="text-[10px] font-bold">ลงทะเบียน</span>
    </a>
    <a href="/profile" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'profile' ? 'text-black' : 'text-gray-400' ?>">
        <span class="material-symbols-outlined text-xl">person</span>
        <span class="text-[10px] font-bold">โปรไฟล์</span>
    </a>
    <?php else: ?>
    <div class="flex flex-col items-center p-2 text-gray-400 opacity-50" title="กรุณาเข้าสู่ระบบ">
        <span class="material-symbols-outlined text-xl">calendar_month</span>
        <span class="text-[10px] font-bold">กิจกรรม</span>
    </div>
    <div class="flex flex-col items-center p-2 text-gray-400 opacity-50" title="กรุณาเข้าสู่ระบบ">
        <span class="material-symbols-outlined text-xl">confirmation_number</span>
        <span class="text-[10px] font-bold">ลงทะเบียน</span>
    </div>
    <a href="/login" class="flex flex-col items-center p-2 text-gray-600">
        <span class="material-symbols-outlined text-xl">login</span>
        <span class="text-[10px] font-bold">เข้าสู่ระบบ</span>
    </a>
    <?php endif; ?>
</nav>
