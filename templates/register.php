<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - InviGo</title>
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
        /* Password Toggle */
        .password-container {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #666;
            transition: color 0.2s;
        }
        .password-toggle:hover {
            color: #000;
        }
        .neo-input.with-toggle {
            padding-right: 45px;
        }
        .birth-date-container {
            position: relative;
        }
        .birth-date-container input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
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
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <div class="neo-box bg-white p-8 w-full max-w-md text-center">
        <div class="w-16 h-16 bg-[#FFE600] border-2 border-black rounded-xl flex items-center justify-center mx-auto mb-4 shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            <span class="material-symbols-outlined text-4xl">person_add</span>
        </div>
        <h1 class="text-3xl font-black mb-2">สมัครสมาชิก</h1>
        <p class="text-gray-500 font-bold mb-6">เริ่มต้นใช้งาน InviGo</p>
        <?php if (!empty($errors['general'])): ?>
            <div class="bg-red-100 border-2 border-red-500 text-red-700 p-3 rounded-lg mb-4 font-bold">
                <?= sanitize($errors['general']) ?>
            </div>
        <?php endif; ?>
        <form method="POST" action="/register">
            <div class="text-left mb-4">
                <label class="font-bold ml-1">ชื่อ <span class="text-red-500">*</span></label>
                <input type="text" name="name" class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50" placeholder="ชื่อ-นามสกุล" required value="<?= isset($_POST['name']) ? sanitize($_POST['name']) : '' ?>">
                <?php if (!empty($errors['name'])): ?>
                    <p class="error-message"><?= sanitize($errors['name']) ?></p>
                <?php endif; ?>
            </div>
            <div class="text-left mb-4">
                <label class="font-bold ml-1">อีเมล <span class="text-red-500">*</span></label>
                <input type="email" name="email" class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50" placeholder="user@example.com" required value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : '' ?>">
                <?php if (!empty($errors['email'])): ?>
                    <p class="error-message"><?= sanitize($errors['email']) ?></p>
                <?php endif; ?>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="text-left">
                    <label class="font-bold ml-1">รหัสผ่าน <span class="text-red-500">*</span></label>
                    <div class="password-container">
                        <input type="password" name="password" id="passwordInput" class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50 with-toggle" placeholder="••••••" required>
                        <span class="password-toggle material-symbols-outlined" onclick="togglePassword(this)">visibility_off</span>
                    </div>
                    <?php if (!empty($errors['password'])): ?>
                        <p class="error-message"><?= sanitize($errors['password']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-left">
                    <label class="font-bold ml-1">ยืนยันรหัสผ่าน <span class="text-red-500">*</span></label>
                    <input type="password" name="confirm_password" id="confirmPasswordInput" class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50" placeholder="••••••" required>
                    <p id="matchStatus" class="text-xs mt-1"></p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="text-left birth-date-container">
                    <label class="font-bold ml-1">วันเกิด <span class="text-red-500">*</span></label>
                    <input type="date" id="birthDate" name="birth_date" max="" min=""
                           class="neo-input w-full p-3 mt-1 outline-none focus:bg-yellow-50" 
                           required value="<?= isset($_POST['birth_date']) ? sanitize($_POST['birth_date']) : '' ?>">
                    <p id="ageDisplay" class="age-display"></p>
                    <?php if (!empty($errors['birth_date'])): ?>
                        <p class="error-message"><?= sanitize($errors['birth_date']) ?></p>
                    <?php endif; ?>
                </div>
                <div class="text-left">
                    <label class="font-bold ml-1">เพศ <span class="text-red-500">*</span></label>
                    <input type="hidden" name="gender" id="genderInput" value="<?= isset($_POST['gender']) ? sanitize($_POST['gender']) : '' ?>">
                    <div class="grid grid-cols-3 gap-2 mt-1">
                        <button type="button" class="gender-btn neo-input p-3 flex flex-col items-center gap-1 transition-all" data-gender="male">
                            <span class="material-symbols-outlined text-2xl">man</span>
                            <span class="text-xs font-bold">ชาย</span>
                        </button>
                        <button type="button" class="gender-btn neo-input p-3 flex flex-col items-center gap-1 transition-all" data-gender="female">
                            <span class="material-symbols-outlined text-2xl">woman</span>
                            <span class="text-xs font-bold">หญิง</span>
                        </button>
                        <button type="button" class="gender-btn neo-input p-3 flex flex-col items-center gap-1 transition-all" data-gender="other">
                            <span class="material-symbols-outlined text-2xl">wc</span>
                            <span class="text-xs font-bold">อื่นๆ</span>
                        </button>
                    </div>
                    <?php if (!empty($errors['gender'])): ?>
                        <p class="error-message"><?= sanitize($errors['gender']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <button type="submit" class="neo-btn w-full bg-[#FFE600] py-3 font-black text-lg hover:bg-[#ffe100]">สมัครสมาชิก</button>
        </form>
        <p class="mt-6 text-sm text-gray-400 font-bold">มีบัญชีแล้ว? <a href="/login" class="text-black underline">เข้าสู่ระบบ</a></p>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const birthDateInput = document.getElementById('birthDate');
        const ageDisplay = document.getElementById('ageDisplay');
        
        // Set min/max dates (10-120 years ago)
        const today = new Date();
        const maxDate = new Date(today.getFullYear() - 10, today.getMonth(), today.getDate());
        const minDate = new Date(today.getFullYear() - 120, today.getMonth(), today.getDate());
        
        birthDateInput.max = maxDate.toISOString().split('T')[0];
        birthDateInput.min = minDate.toISOString().split('T')[0];
        
        function validateBirthDate() {
            const birthDate = new Date(birthDateInput.value);
            const today = new Date();
            
            if (!birthDateInput.value) {
                ageDisplay.textContent = '';
                return;
            }
            
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            
            if (birthDate > today) {
                ageDisplay.textContent = 'วันเกิดไม่ถูกต้อง';
                ageDisplay.classList.add('invalid');
            } else if (age < 10) {
                ageDisplay.textContent = 'อายุ ' + age + ' ปี (ต้องไม่ต่ำกว่า 10 ปี)';
                ageDisplay.classList.add('invalid');
            } else if (age > 120) {
                ageDisplay.textContent = 'อายุ ' + age + ' ปี (ต้องไม่เกิน 120 ปี)';
                ageDisplay.classList.add('invalid');
            } else {
                ageDisplay.textContent = 'อายุ ' + age + ' ปี';
                ageDisplay.classList.remove('invalid');
            }
        }
        
        birthDateInput.addEventListener('change', validateBirthDate);
        
        if (birthDateInput.value) {
            validateBirthDate();
        }
        // Gender selection
        const genderBtns = document.querySelectorAll('.gender-btn');
        const genderInput = document.getElementById('genderInput');
        
        genderBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                genderBtns.forEach(b => b.classList.remove('selected'));
                btn.classList.add('selected');
                genderInput.value = btn.dataset.gender;
            });
        });
        
        // Set initial selected gender
        if (genderInput.value) {
            const initialBtn = document.querySelector('.gender-btn[data-gender="' + genderInput.value + '"]');
            if (initialBtn) {
                initialBtn.classList.add('selected');
            }
        }
        
        // Password toggle function - toggles both fields
        let isPasswordVisible = false;
        window.togglePassword = function(icon) {
            const passwordInput = document.getElementById('passwordInput');
            const confirmInput = document.getElementById('confirmPasswordInput');
            
            isPasswordVisible = !isPasswordVisible;
            
            if (isPasswordVisible) {
                passwordInput.type = 'text';
                confirmInput.type = 'text';
                icon.textContent = 'visibility';
            } else {
                passwordInput.type = 'password';
                confirmInput.type = 'password';
                icon.textContent = 'visibility_off';
            }
        };
        
        // Real-time password validation
        const passwordInput = document.getElementById('passwordInput');
        const confirmInput = document.getElementById('confirmPasswordInput');
        const matchStatus = document.getElementById('matchStatus');
        
        function validatePasswords() {
            const password = passwordInput.value;
            const confirm = confirmInput.value;
            
            if (!password && !confirm) {
                matchStatus.textContent = '';
                return;
            }
            
            // Check minimum length
            if (password.length < 6) {
                matchStatus.textContent = 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร';
                matchStatus.className = 'text-xs mt-1 text-red-500';
                return;
            }
            
            // Check if passwords match
            if (confirm && password !== confirm) {
                matchStatus.textContent = 'รหัสผ่านไม่ตรงกัน';
                matchStatus.className = 'text-xs mt-1 text-red-500';
                return;
            }
            
            if (password === confirm && password.length >= 6) {
                matchStatus.textContent = 'รหัสผ่านตรงกัน ✓';
                matchStatus.className = 'text-xs mt-1 text-green-500';
            }
        }
        
        passwordInput.addEventListener('input', validatePasswords);
        confirmInput.addEventListener('input', validatePasswords);
    });
    </script>
</body>
</html>
