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
            bottom: 30px;
            right: 30px;
            transform: translateY(200%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 9999;
            background: white;
            border: 4px solid black;
            box-shadow: 5px 5px 0 0 black;
            padding: 1rem;
            display: flex;
            gap: 1rem;
            align-items: center;
            max-width: 400px;
            width: 100%;
        }
        .toast.show {
            transform: translateY(0);
        }
        .toast-icon {
            width: 40px;
            height: 40px;
            border: 2px solid black;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .toast-success .toast-icon {
            background: #D4FF33;
        }
        .toast-error .toast-icon {
            background: #fee2e2;
            color: #dc2626;
        }
        .toast-content {
            flex-grow: 1;
        }
        .toast-title {
            font-weight: bold;
            text-transform: uppercase;
            font-size: 0.875rem;
        }
        .toast-message {
            font-size: 0.875rem;
            color: #4b5563;
        }
        .toast-close {
            font-weight: bold;
            font-size: 1.25rem;
            padding: 0 0.5rem;
            cursor: pointer;
        }
        .toast-close:hover {
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
        
        @keyframes skeleton-pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .skeleton {
            background: linear-gradient(90deg, #e5e7eb 25%, #f3f4f6 50%, #e5e7eb 75%);
            background-size: 200% 100%;
            animation: skeleton-pulse 1.5s ease-in-out infinite;
            border-radius: 0.5rem;
        }
        .skeleton-box {
            border: 2px solid #d1d5db;
            box-shadow: 4px 4px 0 0 #d1d5db;
            border-radius: 1rem;
            background: white;
        }
        
        .empty-state {
            text-align: center;
            padding: 3rem 1.5rem;
        }
        .empty-state-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 1.5rem;
            background: #f3f4f6;
            border: 3px solid black;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 4px 4px 0 0 black;
        }
        .empty-state-icon .material-symbols-outlined {
            font-size: 4rem;
            color: #9ca3af;
        }
        
        .page-transition {
            animation: fadeInUp 0.4s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .slide-in-right {
            animation: slideInRight 0.3s ease-out;
        }
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .spinner {
            border: 4px solid #f3f4f6;
            border-top: 4px solid #FFE600;
            border-right: 4px solid black;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        #scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            height: 12px;
            background: #facc15;
            width: 0%;
            z-index: 9999;
            border-bottom: 3px solid black;
            border-right: 3px solid black;
            transition: width 0.1s ease-out;
        }
        #tour-highlighter {
            position: absolute;
            border: 4px solid #FFE600;
            border-radius: 12px;
            box-shadow: 0 0 0 9999px rgba(0, 0, 0, 0.35);
            z-index: 10003;
            pointer-events: none;
            transition: all 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
            opacity: 0;
        }
        #tour-highlighter.active {
            opacity: 1;
            animation: pulseBorder 2s infinite;
        }
        @keyframes pulseBorder {
            0% { border-color: #facc15; transform: scale(1); }
            50% { border-color: white; transform: scale(1.02); }
            100% { border-color: #facc15; transform: scale(1); }
        }
        #tour-popover {
            position: absolute;
            width: 320px;
            background: white;
            border: 4px solid black;
            border-radius: 1rem;
            padding: 1.5rem;
            z-index: 10001;
            box-shadow: 10px 10px 0 0 black;
            opacity: 0;
            transition: opacity 0.3s, transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #tour-popover.active {
            opacity: 1;
        }
        #tour-arrow {
            position: absolute;
            z-index: 10000;
            width: 60px;
            height: 60px;
            pointer-events: none;
            transition: all 0.4s ease;
            filter: drop-shadow(3px 3px 0px black);
            color: #facc15;
        }
        .step-badge {
            background: #facc15;
            border: 3px solid black;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 1.2rem;
            border-radius: 8px;
            position: absolute;
            top: -20px;
            left: -20px;
            transform: rotate(-10deg);
            box-shadow: 3px 3px 0 0 black;
        }
        .tour-highlight-target {
            position: relative;
            z-index: 10004;
        }
    </style>
</head>
<body class="flex flex-col md:flex-row min-h-screen pb-20 md:pb-0 bg-dot-pattern relative overflow-x-hidden">
    <div id="scroll-progress"></div>

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
    <main class="page-transition flex-1 p-4 md:p-8 max-w-6xl mx-auto w-full">
        <div class="md:hidden flex justify-between items-center mb-6">
            <h1 class="font-black text-xl flex items-center gap-2">
                <span class="material-symbols-outlined bg-[#FFE600] border-2 border-black rounded p-1">confirmation_number</span>
                InviGo
            </h1>
          
        </div>
        <?php if ($flash): ?>
            <div id="toast" class="toast toast-<?= $flash['type'] ?>">
                <div class="toast-icon">
                    <span class="material-symbols-outlined"><?= $flash['type'] === 'success' ? 'check_circle' : 'error' ?></span>
                </div>
                <div class="toast-content">
                    <div class="toast-title"><?= $flash['type'] === 'success' ? 'Success' : 'Error' ?></div>
                    <div class="toast-message"><?= sanitize($flash['message']) ?></div>
                </div>
                <button onclick="document.getElementById('toast').classList.remove('show')" class="toast-close">X</button>
            </div>
            <script>
                setTimeout(() => {
                    document.getElementById('toast').classList.add('show');
                }, 100);
                setTimeout(() => {
                    document.getElementById('toast').classList.remove('show');
                }, 4000);
            </script>
        <?php endif; ?>
