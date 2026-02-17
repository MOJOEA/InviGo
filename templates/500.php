<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - เกิดข้อผิดพลาด</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            background-color: #FFFBF0;
        }
        .neo-box {
            border: 2px solid black;
            box-shadow: 8px 8px 0 0 black;
            border-radius: 1rem;
            background: white;
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
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="neo-box p-12 text-center max-w-md">
        <div class="w-24 h-24 bg-red-100 border-2 border-red-500 rounded-xl flex items-center justify-center mx-auto mb-6">
            <span class="material-symbols-outlined text-5xl text-red-500">warning</span>
        </div>
        <h1 class="text-6xl font-black mb-2">500</h1>
        <p class="text-xl font-bold text-gray-600 mb-4">เกิดข้อผิดพลาด</p>
        <p class="text-gray-400 mb-8">ระบบเกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง</p>
        <a href="/explore" class="neo-btn inline-block bg-[#FFE600] px-8 py-3 font-black hover:bg-[#ffe100]">กลับหน้าหลัก</a>
    </div>
</body>
</html>
