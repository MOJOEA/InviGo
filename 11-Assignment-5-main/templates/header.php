<?php
$currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบลงทะเบียนเรียน</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-gradient-to-r from-emerald-600 to-teal-700 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-2 sm:px-4 lg:px-6 py-3 sm:py-4">
            <div class="flex justify-between items-center">
                <h1 class="text-lg sm:text-xl lg:text-2xl font-bold"><i class="fas fa-graduation-cap mr-1 sm:mr-2"></i><span class="hidden sm:inline">ระบบลงทะเบียนเรียน</span><span class="sm:hidden">ลงทะเบียน</span></h1>
                <button id="mobileMenuBtn" class="md:hidden text-white p-1 sm:p-2">
                    <i class="fas fa-bars text-lg sm:text-xl"></i>
                </button>
                <nav id="mainNav" class="hidden md:flex gap-2 sm:gap-3 lg:gap-4 xl:gap-6">
                    <a href="/" class="font-bold text-emerald-100 hover:text-emerald-200 transition underline-offset-4 <?= $currentPath == '/' ? 'underline' : '' ?> text-xs sm:text-sm lg:text-base"><i class="fas fa-home mr-1"></i><span class="hidden lg:inline">หน้าหลัก</span><span class="lg:hidden">หน้า</span></a>
                    <?php if (isset($_SESSION['student_id'])): ?>
                    <a href="/courses" class="text-emerald-300 hover:text-emerald-200 transition text-xs sm:text-sm lg:text-base <?= $currentPath == '/courses' ? 'underline underline-offset-4' : '' ?>"><i class="fas fa-book mr-1"></i><span class="hidden lg:inline">วิชาเรียน</span><span class="lg:hidden">วิชา</span></a>
                    <a href="/enrolled" class="text-emerald-300 hover:text-emerald-200 transition text-xs sm:text-sm lg:text-base <?= $currentPath == '/enrolled' ? 'underline underline-offset-4' : '' ?>"><i class="fas fa-list mr-1"></i><span class="hidden lg:inline">ลงทะเบียนแล้ว</span><span class="lg:hidden">ลงแล้ว</span></a>
                    <a href="#" onclick="showConfirmModal('ต้องการออกจากระบบหรือไม่?', () => window.location.href = '/logout'); return false;" class="text-emerald-300 hover:text-emerald-200 transition text-xs sm:text-sm lg:text-base"><i class="fas fa-sign-out-alt mr-1"></i><span class="hidden lg:inline">ออกจากระบบ</span><span class="lg:hidden">ออก</span></a>
                    <?php endif; ?>
                </nav>
            </div>
            <nav id="mobileNav" class="hidden md:hidden mt-3 sm:mt-4 space-y-1 sm:space-y-2">
                <a href="/" class="block py-2 sm:py-3 px-3 sm:px-4 rounded hover:bg-emerald-700 transition text-sm"><i class="fas fa-home mr-2"></i>หน้าหลัก</a>
                <?php if (isset($_SESSION['student_id'])): ?>
                <a href="/courses" class="block py-2 sm:py-3 px-3 sm:px-4 rounded hover:bg-emerald-700 transition text-sm <?= $currentPath == '/courses' ? 'underline underline-offset-4' : '' ?>"><i class="fas fa-book mr-2"></i>วิชาเรียน</a>
                <a href="/enrolled" class="block py-2 sm:py-3 px-3 sm:px-4 rounded hover:bg-emerald-700 transition text-sm <?= $currentPath == '/enrolled' ? 'underline underline-offset-4' : '' ?>"><i class="fas fa-list mr-2"></i>ลงทะเบียนแล้ว</a>
                <a href="#" onclick="showConfirmModal('ต้องการออกจากระบบหรือไม่?', () => window.location.href = '/logout'); return false;" class="block py-2 sm:py-3 px-3 sm:px-4 rounded hover:bg-emerald-700 transition text-sm"><i class="fas fa-sign-out-alt mr-2"></i>ออกจากระบบ</a>
                <?php endif; ?>
            </nav>
        </div>
    </header>
    <script>
        document.getElementById('mobileMenuBtn')?.addEventListener('click', function() {
            const mobileNav = document.getElementById('mobileNav');
            mobileNav?.classList.toggle('hidden');
        });
    </script>
