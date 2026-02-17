<?php include 'header.php' ?>
<div class="max-w-2xl mx-auto">
    <div class="mb-4">
        <a href="/profile" class="inline-flex items-center gap-1 text-gray-500 hover:text-black font-bold">
            <span class="material-symbols-outlined">arrow_back</span> กลับ
        </a>
    </div>
    
    <h2 class="text-2xl font-black mb-6 flex items-center gap-2">
        แก้ไขโปรไฟล์
        <span class="material-symbols-outlined">edit</span>
        <?php if (($user['role'] ?? 0) == 1): ?>
        <span class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center" title="Admin">
            <span class="material-symbols-outlined text-white text-sm">check</span>
        </span>
        <?php endif; ?>
    </h2>
    
    <?php if (($user['role'] ?? 0) == 1): ?>
    <div class="mb-4">
        <a href="/admin" class="neo-btn w-full bg-red-100 py-3 font-bold inline-flex items-center justify-center gap-2 text-red-600 border-red-300">
            <span class="material-symbols-outlined">admin_panel_settings</span>
            จัดการระบบ (Admin)
        </a>
    </div>
    <?php endif; ?>
    
    <?php if (!empty($errors['general'])): ?>
        <div class="mb-4 p-4 rounded-xl border-2 border-black bg-red-100 text-red-700 font-bold">
            <?= sanitize($errors['general']) ?>
        </div>
    <?php endif; ?>
    
    <form method="POST" action="/profile/edit" enctype="multipart/form-data" class="bg-white border-2 border-black rounded-xl p-6 shadow-[4px_4px_0px_0px_black]">
        <div class="flex flex-col items-center mb-6">
            <div class="w-24 h-24 rounded-full border-4 border-black overflow-hidden mb-3 bg-white relative cursor-pointer group" onclick="document.getElementById('profile-input').click()">
                <img id="preview-image" src="<?= sanitize($user['profile_image'] ?? 'https://api.dicebear.com/9.x/dylan/svg') ?>" 
                     alt="Profile" 
                     class="w-full h-full object-cover transition-all group-hover:brightness-75">
                <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-3xl text-white drop-shadow-lg">photo_camera</span>
                </div>
            </div>
            <label class="text-sm font-bold text-gray-500 mb-2">รูปโปรไฟล์</label>
            <input type="file" id="profile-input" name="profile_image" accept="image/*" 
                   class="hidden"
                   onchange="previewFile()">
            <p class="text-xs text-gray-400 mt-1">กดที่รูปเพื่อเปลี่ยนรูปโปรไฟล์ (JPG, PNG, GIF, WebP สูงสุด 2MB)</p>
        </div>
        
        <div class="mb-4">
            <label class="block font-bold mb-1">ชื่อ <span class="text-red-500">*</span></label>
            <input type="text" name="name" value="<?= sanitize($user['name'] ?? '') ?>" 
                   class="neo-input w-full p-2" required>
            <?php if (!empty($errors['name'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= sanitize($errors['name']) ?></p>
            <?php endif; ?>
        </div>
        
        <div class="mb-4">
            <label class="block font-bold mb-1">อีเมล</label>
            <input type="email" value="<?= sanitize($user['email'] ?? '') ?>" 
                   class="neo-input w-full p-2 bg-gray-100" disabled readonly>
            <p class="text-xs text-gray-400 mt-1">ไม่สามารถแก้ไขอีเมลได้</p>
        </div>
        
        <div class="mb-4">
            <label class="block font-bold mb-1">วันเกิด</label>
            <input type="date" id="birthDate" name="birth_date" value="<?= sanitize($user['birth_date'] ?? '') ?>" 
                   class="neo-input w-full p-2">
            <p id="ageDisplay" class="age-display"></p>
        </div>
        
        <div class="mb-4">
            <label class="block font-bold mb-1">เพศ</label>
            <input type="hidden" name="gender" id="genderInput" value="<?= sanitize($user['gender'] ?? '') ?>">
            <div class="grid grid-cols-3 gap-2">
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
        </div>
        
        <div class="flex gap-3">
            <button type="submit" class="neo-btn flex-1 bg-[#FFE600] py-3 font-bold inline-flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">save</span> บันทึก
            </button>
            <a href="/profile" class="neo-btn flex-1 bg-gray-100 py-3 font-bold text-center inline-flex items-center justify-center gap-2">
                ยกเลิก
            </a>
        </div>
    </form>
    
    <script>
    function previewFile() {
        const file = document.querySelector('input[type=file]').files[0];
        const preview = document.getElementById('preview-image');
        if (file) {
            const reader = new FileReader();
            reader.onloadend = function() {
                preview.src = reader.result;
            }
            reader.readAsDataURL(file);
        }
    }
    
    // Age validation
    const birthDateInput = document.getElementById('birthDate');
    const ageDisplay = document.getElementById('ageDisplay');
    
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
    if (birthDateInput.value) validateBirthDate();
    
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
    
    if (genderInput.value) {
        const initialBtn = document.querySelector('.gender-btn[data-gender="' + genderInput.value + '"]');
        if (initialBtn) initialBtn.classList.add('selected');
    }
    </script>
</div>
<?php include 'footer.php' ?>
