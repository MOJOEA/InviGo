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
        /* Debug Modal */
        .debug-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background: #FFE600;
            border: 2px solid black;
            box-shadow: 3px 3px 0 0 black;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1000;
        }
        .debug-btn:hover {
            transform: translate(1px, 1px);
            box-shadow: 2px 2px 0 0 black;
        }
        .debug-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1001;
            align-items: center;
            justify-content: center;
        }
        .debug-modal.show {
            display: flex;
        }
        .debug-content {
            background: white;
            border: 2px solid black;
            box-shadow: 6px 6px 0 0 black;
            border-radius: 1rem;
            padding: 20px;
            width: 90%;
            max-width: 300px;
        }
        .debug-user-btn {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            background: #f3f4f6;
            border: 2px solid black;
            box-shadow: 3px 3px 0 0 black;
            border-radius: 0.5rem;
            cursor: pointer;
            font-weight: bold;
            text-align: left;
        }
        .debug-user-btn:hover {
            background: #FFE600;
        }
        .debug-user-btn:active {
            transform: translate(2px, 2px);
            box-shadow: 1px 1px 0 0 black;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Debug Button -->
    <div class="debug-btn" onclick="openDebugModal()" title="Debug Login">
        <span class="material-symbols-outlined text-sm">code</span>
    </div>
    
    <!-- Debug Modal -->
    <div id="debugModal" class="debug-modal" onclick="closeDebugModal(event)">
        <div class="debug-content" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-black text-sm text-gray-500 uppercase tracking-wider">Debug</h3>
                <button onclick="closeDebugModal()" class="text-gray-400 hover:text-black">
                    <span class="material-symbols-outlined text-lg">close</span>
                </button>
            </div>
            <p class="text-xs text-gray-400 mb-3">Quick login for testing:</p>
            <button class="debug-user-btn" onclick="fillLogin('organizer@test.com', 'password')">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">admin_panel_settings</span>
                    <div>
                        <div class="font-bold">ผู้ใช้ 1</div>
                        <div class="text-xs text-gray-500">organizer@test.com</div>
                    </div>
                </div>
            </button>
            <button class="debug-user-btn" onclick="fillLogin('user1@test.com', 'password')">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">person</span>
                    <div>
                        <div class="font-bold">ผู้ใช้ 2</div>
                        <div class="text-xs text-gray-500">user1@test.com</div>
                    </div>
                </div>
            </button>
            <button class="debug-user-btn" onclick="fillLogin('user2@test.com', 'password')">
                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">person</span>
                    <div>
                        <div class="font-bold">ผู้ใช้ 3</div>
                        <div class="text-xs text-gray-500">user2@test.com</div>
                    </div>
                </div>
            </button>
        </div>
    </div>

    <div class="neo-box bg-white p-8 w-full max-w-sm text-center">
        <div class="w-16 h-16 bg-[#FFE600] border-2 border-black rounded-xl flex items-center justify-center mx-auto mb-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <span class="material-symbols-outlined text-4xl">confirmation_number</span>
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
    
    <script>
        function openDebugModal() {
            document.getElementById('debugModal').classList.add('show');
        }
        
        function closeDebugModal(event) {
            if (!event || event.target.id === 'debugModal') {
                document.getElementById('debugModal').classList.remove('show');
            }
        }
        
        function fillLogin(email, password) {
            document.querySelector('input[name="email"]').value = email;
            document.querySelector('input[name="password"]').value = password;
            closeDebugModal();
            // Optional: Auto submit
            // document.querySelector('form').submit();
        }
    </script>
</body>
</html>
