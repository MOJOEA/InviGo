<?php
$flash = getFlashMessage();
$currentUser = getCurrentUser();
$isLoggedIn = !empty($currentUser);
if (!$currentUser) {
    $currentUser = ['name' => 'Guest', 'email' => '', 'profile_image' => 'https://api.dicebear.com/9.x/dylan/svg'];
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? sanitize($title) . ' - ' : '' ?>InviGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #FFFBF0;
        }
        .neo-box {
            border: 2px solid black;
            box-shadow: 6px 6px 0 0 black;
            border-radius: 1rem;
            background: white;
        }
        .neo-card {
            border: 2px solid black;
            box-shadow: 4px 4px 0 0 black;
            border-radius: 1rem;
            transition: transform 0.2s;
            background: white;
        }
        .neo-card:hover {
            transform: translateY(-4px);
        }
        .neo-input {
            border: 2px solid black;
            border-radius: 0.75rem;
        }
        .neo-btn {
            border: 2px solid black;
            box-shadow: 4px 4px 0 0 black;
            border-radius: 0.75rem;
            transition: all 0.1s;
        }
        .neo-btn:active {
            transform: translate(2px, 2px);
            box-shadow: none;
        }
        .neo-btn-small {
            border: 2px solid black;
            box-shadow: 2px 2px 0 0 black;
            border-radius: 0.5rem;
            transition: all 0.1s;
        }
        .neo-btn-small:active {
            transform: translate(1px, 1px);
            box-shadow: none;
        }
        .nav-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            border-radius: 0.75rem;
            font-weight: 700;
            border: 2px solid transparent;
            color: #4b5563;
            transition: all 0.2s;
        }
        .nav-item .material-symbols-outlined {
            font-size: 1.5rem;
            color: #6b7280;
            transition: color 0.2s;
        }
        .nav-item.active {
            background-color: #FFE600;
            border: 2px solid black;
            box-shadow: 3px 3px 0 0 black;
            color: black;
        }
        .nav-item.active .material-symbols-outlined {
            color: black;
        }
        .nav-item:hover:not(.active) {
            background-color: #f3f4f6;
            color: #111827;
        }
        .nav-item:hover:not(.active) .material-symbols-outlined {
            color: #111827;
        }
        .toast {
            position: fixed;
            bottom: 100px;
            left: 50%;
            right: auto;
            transform: translateX(-50%) translateY(100px);
            padding: 12px 24px;
            border-radius: 12px;
            border: 2px solid black;
            box-shadow: 4px 4px 0 0 black;
            font-weight: bold;
            z-index: 9999;
            opacity: 0;
            transition: all 0.3s ease-out;
            min-width: 300px;
            max-width: 90%;
            text-align: center;
            margin: 0;
        }
        .toast.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .toast-success {
            background-color: #D4FF33;
        }
        .toast-error {
            background-color: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row min-h-screen pb-20 md:pb-0">
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
            <?php if ($isLoggedIn): ?>
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
            <?php else: ?>
            <div class="nav-item opacity-50 cursor-not-allowed" title="กรุณาเข้าสู่ระบบ">
                <span class="material-symbols-outlined">calendar_month</span> กิจกรรมของฉัน
            </div>
            <div class="nav-item opacity-50 cursor-not-allowed" title="กรุณาเข้าสู่ระบบ">
                <span class="material-symbols-outlined">confirmation_number</span> การลงทะเบียน
            </div>
            <div class="nav-item opacity-50 cursor-not-allowed" title="กรุณาเข้าสู่ระบบ">
                <span class="material-symbols-outlined">person</span> โปรไฟล์
            </div>
            <?php endif; ?>
        </nav>
        <div class="mt-auto">
            <?php if ($isLoggedIn): ?>
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
   
            <?php else: ?>
            <a href="/login" class="block mb-4 p-3 bg-[#FFE600] rounded-xl border-2 border-black shadow-[3px_3px_0px_0px_black] hover:shadow-[1px_1px_0px_0px_black] hover:translate-x-[2px] hover:translate-y-[2px] transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full border-2 border-black overflow-hidden bg-gray-100 flex-shrink-0">
                        <img src="https://api.dicebear.com/9.x/dylan/svg" alt="Guest" class="w-full h-full object-cover">
                    </div>
                    <div class="flex-1 flex flex-col">
                        <p class="text-sm font-black text-black">เข้าสู่ระบบ</p>
                        <p class="text-xs text-gray-600 mt-1">เพื่อใช้งานเต็มรูปแบบ</p>
                    </div>
                    <span class="material-symbols-outlined text-gray-500">chevron_right</span>
                </div>
            </a>
            <?php endif; ?>
        </div>
    </aside>
    <main class="flex-1 p-4 md:p-8 max-w-6xl mx-auto w-full">
        <div class="md:hidden flex justify-between items-center mb-6">
            <h1 class="font-black text-xl flex items-center gap-2">
                <span class="material-symbols-outlined bg-[#FFE600] border-2 border-black rounded p-1">confirmation_number</span>
                InviGo
            </h1>
        </div>
        <?php if ($flash): ?>
            <div id="toast" class="toast toast-<?= $flash['type'] ?>">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined"><?= $flash['type'] === 'success' ? 'check_circle' : 'error' ?></span>
                    <span><?= sanitize($flash['message']) ?></span>
                </div>
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('toast').classList.add('show');
                }, 100);
                setTimeout(() => {
                    document.getElementById('toast').classList.remove('show');
                }, 3000);
            </script>
        <?php endif; ?>
        <?= $content ?? '' ?>
    </main>
    <nav class="md:hidden fixed bottom-0 w-full bg-white border-t-2 border-black p-2 flex justify-around rounded-t-xl z-50">
        <a href="/explore" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'explore' ? 'text-black' : 'text-gray-400' ?>">
            <span class="material-symbols-outlined text-xl">search</span>
            <span class="text-[10px] font-bold">ค้นหา</span>
        </a>
        <?php if ($isLoggedIn): ?>
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
</body>
</html>
