<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - InviGo</title>
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
        }
        .neo-input {
            border: 2px solid black;
            box-shadow: 2px 2px 0 0 rgba(0, 0, 0, 0.1);
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
        .error-message {
            color: #ef4444;
            font-size: 0.875rem;
            margin-top: 0.25rem;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="neo-box bg-white p-8 w-full max-w-sm text-center">
        <div class="w-16 h-16 bg-[#FFE600] border-2 border-black rounded-xl flex items-center justify-center mx-auto mb-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <span class="material-symbols-outlined text-4xl">grid_view</span>
        </div>
        <h1 class="text-3xl font-black mb-2">InviGo</h1>
        <p class="text-gray-500 font-bold mb-6">ระบบจัดการกิจกรรมสุดคูล</p>
        <?php if (!empty($errors['general'])): ?>
            <div class="bg-red-100 border-2 border-red-500 text-red-700 p-3 rounded-lg mb-4 font-bold">
                <?= sanitize($errors['general']) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="/login">
            <div class="text-left mb-4">
                <label class="font-bold ml-1">อีเมล</label>
                <input type="email" name="email" class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50" placeholder="user@example.com" required value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>">
                <?php if (!empty($errors['email'])): ?>
                    <p class="error-message"><?= sanitize($errors['email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="text-left mb-6">
                <label class="font-bold ml-1">รหัสผ่าน</label>
                <input type="password" name="password" class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50" placeholder="••••••" required>
                <?php if (!empty($errors['password'])): ?>
                    <p class="error-message"><?= sanitize($errors['password']) ?></p>
                <?php endif; ?>
            </div>
            <button type="submit" class="neo-btn w-full bg-[#FFE600] py-3 font-black text-lg hover:bg-[#ffe100]">เข้าสู่ระบบ</button>
        </form>
        <p class="mt-6 text-sm text-gray-400 font-bold">ยังไม่มีบัญชี? <a href="/register" class="text-black underline">สมัครสมาชิก</a></p>
    </div>
</body>
</html>
