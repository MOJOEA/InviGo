<?php
$flash = getFlashMessage();
$currentUser = getCurrentUser();
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? sanitize($title) . ' - ' : '' ?>InviGo</title>
    
    <!-- Favicon - Ticket Icon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect x='3' y='6' width='18' height='12' rx='2' fill='%23FFE600' stroke='black' stroke-width='2'/><path d='M9 6v12M15 6v12' stroke='black' stroke-width='2'/><circle cx='12' cy='10' r='1' fill='black'/><circle cx='12' cy='14' r='1' fill='black'/></svg>">
    <link rel="apple-touch-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'><rect x='3' y='6' width='18' height='12' rx='2' fill='%23FFE600' stroke='black' stroke-width='2'/><path d='M9 6v12M15 6v12' stroke='black' stroke-width='2'/><circle cx='12' cy='10' r='1' fill='black'/><circle cx='12' cy='14' r='1' fill='black'/></svg>">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="InviGo - ระบบจัดการลงทะเบียนกิจกรรมแบบ Neo-brutalism สร้างกิจกรรม จัดการผู้เข้าร่วม OTP เช็คอิน">
    <meta name="keywords" content="กิจกรรม, ลงทะเบียน, event, registration, check-in, OTP">
    <meta name="author" content="InviGo">
    <meta name="robots" content="index, follow">
    
    <!-- Open Graph -->
    <meta property="og:title" content="InviGo - ระบบจัดการลงทะเบียนกิจกรรม">
    <meta property="og:description" content="สร้างและจัดการกิจกรรม พร้อมระบบ OTP เช็คอิน">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="InviGo">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
      
        @keyframes float {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            33% { transform: translate(20px, -40px) rotate(5deg); }
            66% { transform: translate(-15px, 15px) rotate(-3deg); }
            100% { transform: translate(0px, 0px) rotate(0deg); }
        }
        @keyframes float-slow {
            0% { transform: translate(0px, 0px) rotate(0deg); }
            50% { transform: translate(-30px, 30px) rotate(-10deg); }
            100% { transform: translate(0px, 0px) rotate(0deg); }
        }
        .animate-float { animation: float 12s ease-in-out infinite; }
        .animate-float-slow { animation: float-slow 15s ease-in-out infinite; }
        .animate-float-reverse { animation: float 18s ease-in-out infinite reverse; }
        .floating-shape {
            position: absolute;
            pointer-events: none;
            z-index: -1;
        }
        #background-elements {
            z-index: -1;
        }
        .bg-dot-pattern {
            background-color: #FFFBF0;
            background-image: radial-gradient(#cbd5e1 1px, transparent 1px);
            background-size: 32px 32px;
        }
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
        .gender-btn {
            cursor: pointer;
            background: white;
        }
        .gender-btn:hover {
            background: #FFFBF0;
        }
        .gender-btn.selected {
            background: #FFE600;
            box-shadow: 2px 2px 0 0 black;
            transform: translate(0, 0);
        }
        .gender-btn.selected .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }
        .age-display {
            font-size: 0.875rem;
            color: #10b981;
            margin-top: 0.25rem;
            font-weight: bold;
        }
        .age-display.invalid {
            color: #ef4444;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row min-h-screen pb-20 md:pb-0 bg-dot-pattern relative overflow-x-hidden">

    <div id="background-elements" class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index: -1;">
        <div class="floating-shape animate-float top-[10%] right-[10%] w-16 h-16 md:w-20 md:h-20 text-[#FFE600]">
            <svg viewBox="0 0 24 24" fill="currentColor" style="filter: drop-shadow(3px 3px 0px #000);">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
        </div>
        <div class="floating-shape animate-float-slow bottom-[15%] left-[12%] w-12 h-12 md:w-16 md:h-16 bg-[#ff94c2] rounded-full border-2 border-black neo-shadow"></div>
        <div class="floating-shape animate-float-reverse bottom-[20%] right-[15%] w-10 h-10 md:w-12 md:h-12 bg-[#a3e635] border-2 border-black neo-shadow rotate-12"></div>
        <div class="floating-shape animate-float-slow top-[20%] left-[15%] w-8 h-8 md:w-10 md:h-10 text-[#a3e635] opacity-60">
            <svg viewBox="0 0 24 24" fill="currentColor" style="filter: drop-shadow(2px 2px 0px #000);">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
        </div>
        <div class="floating-shape animate-float top-[45%] left-[5%] w-6 h-6 md:w-8 md:h-8 bg-[#FFE600] rounded-full border-2 border-black neo-shadow"></div>
        <div class="floating-shape animate-float-slow top-[35%] right-[5%] w-8 h-8 md:w-10 md:h-10 bg-[#ff94c2] border-2 border-black neo-shadow -rotate-12"></div>
        <div class="floating-shape animate-float top-[15%] left-[45%] text-gray-300 font-black text-4xl opacity-40">+</div>
        <div class="floating-shape animate-float-slow bottom-[35%] left-[40%] text-gray-300 font-black text-3xl opacity-40">×</div>
        <div class="floating-shape animate-float top-[60%] right-[30%] text-gray-300 font-black text-5xl opacity-40">+</div>
    </div>
    <!-- Sidebar -->
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
