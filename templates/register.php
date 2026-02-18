<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สมัครสมาชิก - InviGo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;700;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <style>
        /* Neo Brutalist Background */
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
        .picker-popup {
            display: none;
            position: absolute;
            top: 110%;
            left: 0;
            width: 320px;
            background: white;
            border: 3px solid black;
            border-radius: 1rem;
            box-shadow: 8px 8px 0 0 black;
            padding: 1rem;
            z-index: 50;
            animation: popIn 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .picker-popup.active { display: block; }
        .picker-container {
            display: flex;
            height: 200px;
            gap: 8px;
            margin-bottom: 1rem;
        }
        .picker-col {
            flex: 1;
            border: 3px solid black;
            border-radius: 8px;
            overflow-y: auto;
            background: #fff;
            scroll-behavior: smooth;
        }
        .picker-col::-webkit-scrollbar { width: 6px; }
        .picker-col::-webkit-scrollbar-track { background: #eee; border-left: 2px solid black; }
        .picker-col::-webkit-scrollbar-thumb { background: black; }
        .picker-item {
            padding: 8px 4px;
            text-align: center;
            font-weight: 600;
            cursor: pointer;
            border-bottom: 2px solid #f0f0f0;
            transition: all 0.1s;
            font-size: 0.875rem;
        }
        .picker-item:hover { background: #eee; }
        .picker-item.selected {
            background: #FFE600;
            color: black;
            border: 2px solid black;
            font-weight: 700;
        }
        .col-label {
            text-align: center;
            font-size: 0.7rem;
            font-weight: 700;
            margin-bottom: 4px;
            text-transform: uppercase;
            color: #666;
        }
        @keyframes popIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .birth-trigger {
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .birth-trigger:hover, .birth-trigger.active {
            background-color: #FEF08A;
            box-shadow: 4px 4px 0 0 black;
            transform: translate(-2px, -2px);
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
<body class="flex items-center justify-center min-h-screen p-4 bg-dot-pattern relative overflow-x-hidden">
    <!-- Background Floating Shapes -->
    <div id="background-elements" class="fixed inset-0 overflow-hidden pointer-events-none" style="z-index: -1;">
        <div class="floating-shape animate-float top-[10%] right-[10%] w-16 h-16 md:w-20 md:h-20 text-[#FFE600]">
            <svg viewBox="0 0 24 24" fill="currentColor" style="filter: drop-shadow(3px 3px 0px #000);">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
        </div>
        <div class="floating-shape animate-float-slow bottom-[15%] left-[12%] w-12 h-12 md:w-16 md:h-16 bg-[#ff94c2] rounded-full border-2 border-black" style="box-shadow: 4px 4px 0 0 #000;"></div>
        <div class="floating-shape animate-float-reverse bottom-[20%] right-[15%] w-10 h-10 md:w-12 md:h-12 bg-[#a3e635] border-2 border-black rotate-12" style="box-shadow: 4px 4px 0 0 #000;"></div>
        <div class="floating-shape animate-float-slow top-[20%] left-[15%] w-8 h-8 md:w-10 md:h-10 text-[#a3e635] opacity-60">
            <svg viewBox="0 0 24 24" fill="currentColor" style="filter: drop-shadow(2px 2px 0px #000);">
                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
            </svg>
        </div>
        <div class="floating-shape animate-float top-[45%] left-[5%] w-6 h-6 md:w-8 md:h-8 bg-[#FFE600] rounded-full border-2 border-black" style="box-shadow: 4px 4px 0 0 #000;"></div>
        <div class="floating-shape animate-float-slow top-[35%] right-[5%] w-8 h-8 md:w-10 md:h-10 bg-[#ff94c2] border-2 border-black -rotate-12" style="box-shadow: 4px 4px 0 0 #000;"></div>
        <div class="floating-shape animate-float top-[15%] left-[45%] text-gray-300 font-black text-4xl opacity-40">+</div>
        <div class="floating-shape animate-float-slow bottom-[35%] left-[40%] text-gray-300 font-black text-3xl opacity-40">×</div>
        <div class="floating-shape animate-float top-[60%] right-[30%] text-gray-300 font-black text-5xl opacity-40">+</div>
    </div>
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
                    <input type="hidden" name="birth_date" id="birthDate" required value="<?= isset($_POST['birth_date']) ? sanitize($_POST['birth_date']) : '' ?>">
                    <div class="neo-input w-full p-3 mt-1 birth-trigger" id="date-trigger" onclick="togglePicker(event)">
                        <span id="selected-date-text" class="text-gray-400">เลือกวันเกิด...</span>
                        <span class="material-symbols-outlined">cake</span>
                    </div>
                    <div id="picker-popup" class="picker-popup" onclick="event.stopPropagation()">
                        <div class="flex gap-2 mb-1">
                            <div class="flex-1 col-label">วัน</div>
                            <div class="flex-1 col-label">เดือน</div>
                            <div class="flex-1 col-label">ปี (พ.ศ.)</div>
                        </div>
                        <div class="picker-container">
                            <div class="picker-col" id="col-days"></div>
                            <div class="picker-col" id="col-months"></div>
                            <div class="picker-col" id="col-years"></div>
                        </div>
                        <div class="pt-2 border-t-2 border-dashed border-gray-300 flex justify-between items-center">
                            <button type="button" class="text-sm text-red-500 font-bold hover:underline" onclick="clearDate()">ล้าง</button>
                            <button type="button" class="bg-black text-white px-4 py-2 rounded-lg font-bold border-2 border-black hover:bg-white hover:text-black transition-colors" onclick="confirmDate()">ตกลง</button>
                        </div>
                    </div>
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
        
        const monthNames = ["มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม"];
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 100;
        let tempDate = { day: new Date().getDate(), month: new Date().getMonth(), year: currentYear };
        let finalSelectedDate = null;

        function initColumns() {
            renderYears();
            renderMonths();
            renderDays();
            setTimeout(() => {
                scrollToSelection('col-days');
                scrollToSelection('col-months');
                scrollToSelection('col-years');
            }, 10);
        }

        function renderYears() {
            const container = document.getElementById('col-years');
            container.innerHTML = '';
            for (let y = currentYear; y >= startYear; y--) {
                const el = createItem(y + 543, y === tempDate.year, () => {
                    tempDate.year = y;
                    updateSelection('col-years', y + 543);
                    renderDays();
                });
                el.dataset.value = y;
                container.appendChild(el);
            }
        }

        function renderMonths() {
            const container = document.getElementById('col-months');
            container.innerHTML = '';
            monthNames.forEach((m, index) => {
                const el = createItem(m, index === tempDate.month, () => {
                    tempDate.month = index;
                    updateSelection('col-months', m);
                    renderDays();
                });
                el.dataset.value = index;
                container.appendChild(el);
            });
        }

        function renderDays() {
            const container = document.getElementById('col-days');
            const daysInMonth = new Date(tempDate.year, tempDate.month + 1, 0).getDate();
            if (tempDate.day > daysInMonth) tempDate.day = daysInMonth;
            container.innerHTML = '';
            for (let d = 1; d <= daysInMonth; d++) {
                const el = createItem(d, d === tempDate.day, () => {
                    tempDate.day = d;
                    updateSelection('col-days', d);
                });
                el.dataset.value = d;
                container.appendChild(el);
            }
        }

        function createItem(text, isSelected, onClick) {
            const el = document.createElement('div');
            el.className = `picker-item ${isSelected ? 'selected' : ''}`;
            el.innerText = text;
            el.onclick = onClick;
            return el;
        }

        function updateSelection(colId, matchText) {
            const container = document.getElementById(colId);
            Array.from(container.children).forEach(child => {
                child.classList.remove('selected');
                if (child.innerText == matchText) child.classList.add('selected');
            });
        }

        function scrollToSelection(colId) {
            const container = document.getElementById(colId);
            const selected = container.querySelector('.selected');
            if (selected) {
                const offset = selected.offsetTop - (container.clientHeight / 2) + (selected.clientHeight / 2);
                container.scrollTop = offset;
            }
        }

        function togglePicker(e) {
            e.stopPropagation();
            const popup = document.getElementById('picker-popup');
            const trigger = document.getElementById('date-trigger');
            if (popup.classList.contains('active')) {
                window.closePicker();
            } else {
                popup.classList.add('active');
                trigger.classList.add('active');
                initColumns();
            }
        }
        window.togglePicker = togglePicker;

        function closePicker() {
            document.getElementById('picker-popup').classList.remove('active');
            document.getElementById('date-trigger').classList.remove('active');
        }
        window.closePicker = closePicker;

        function confirmDate() {
            finalSelectedDate = { ...tempDate };
            updateInput();
            validateBirthDate();
            closePicker();
        }
        window.confirmDate = confirmDate;

        function updateInput() {
            if (!finalSelectedDate) {
                document.getElementById('selected-date-text').innerText = "เลือกวันเกิด...";
                document.getElementById('selected-date-text').className = "text-gray-400";
                document.getElementById('birthDate').value = '';
                return;
            }
            const d = finalSelectedDate.day;
            const m = monthNames[finalSelectedDate.month];
            const y = finalSelectedDate.year + 543;
            document.getElementById('selected-date-text').innerText = `${d} ${m} ${y}`;
            document.getElementById('selected-date-text').className = "text-black font-bold";
            const isoDate = `${finalSelectedDate.year}-${String(finalSelectedDate.month + 1).padStart(2, '0')}-${String(finalSelectedDate.day).padStart(2, '0')}`;
            document.getElementById('birthDate').value = isoDate;
        }

        function clearDate() {
            finalSelectedDate = null;
            tempDate = { day: new Date().getDate(), month: new Date().getMonth(), year: currentYear };
            updateInput();
            closePicker();
        }
        window.clearDate = clearDate;

        function validateBirthDate() {
            const birthDateInput = document.getElementById('birthDate');
            const ageDisplay = document.getElementById('ageDisplay');
            if (!birthDateInput.value) {
                ageDisplay.textContent = '';
                return;
            }
            const birthDate = new Date(birthDateInput.value);
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) age--;
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

        document.addEventListener('click', window.closePicker);

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
