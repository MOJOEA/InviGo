<?php
$currentUser = getCurrentUser();
?>
<aside class="hidden md:flex flex-col w-64 bg-white border-r-2 border-black p-6 h-screen sticky top-0">
    <div class="flex items-center gap-2 mb-10">
        <div class="w-10 h-10 bg-[#FFE600] border-2 border-black rounded flex items-center justify-center">
            <span class="material-symbols-outlined">confirmation_number</span>
        </div>
        <h1 class="font-black text-xl">InviGo</h1>
    </div>
    <nav class="space-y-2">
        <a href="/explore" class="nav-item <?= ($activePage ?? '') === 'explore' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">search</span> ค้นหา
        </a>
        <a href="/my-events" class="nav-item <?= ($activePage ?? '') === 'my-events' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">calendar_month</span> กิจกรรมของฉัน
        </a>
        <a href="/my-registrations" class="nav-item <?= ($activePage ?? '') === 'my-registrations' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">confirmation_number</span> การลงทะเบียน
        </a>
        <a href="/profile" class="nav-item <?= ($activePage ?? '') === 'profile' ? 'active' : '' ?>">
            <span class="material-symbols-outlined">person</span> โปรไฟล์
        </a>
        <?php if (isAdmin()): ?>
        <a href="/admin" class="nav-item <?= ($activePage ?? '') === 'admin' ? 'active' : '' ?>">
            <span class="material-symbols-outlined text-red-500">admin_panel_settings</span> 
            <span class="text-red-500">จัดการระบบ</span>
        </a>
        <?php endif; ?>
    </nav>
    <div class="mt-auto">
        <a href="/profile" class="block mb-4 p-3 bg-white rounded-xl border-2 border-black shadow-[3px_3px_0px_0px_black] hover:shadow-[1px_1px_0px_0px_black] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-full border-2 border-black overflow-hidden bg-gray-100 flex-shrink-0">
                    <img src="<?= sanitize($currentUser['profile_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                         alt="Profile" 
                         class="w-full h-full object-cover"
                         onerror="this.src='https://api.dicebear.com/9.x/dylan/svg'">
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-black truncate"><?= sanitize($currentUser['name'] ?? '') ?></p>
                    <p class="text-xs text-gray-500 truncate"><?= sanitize($currentUser['email'] ?? '') ?></p>
                </div>
                <span class="material-symbols-outlined text-gray-400">chevron_right</span>
            </div>
        </a>
     
    </div>
</aside>
