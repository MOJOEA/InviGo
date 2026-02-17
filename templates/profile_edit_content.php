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
    </h2>
    
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
            <input type="date" name="birth_date" value="<?= sanitize($user['birth_date'] ?? '') ?>" 
                   class="neo-input w-full p-2">
        </div>
        
        <div class="mb-4">
            <label class="block font-bold mb-1">เพศ</label>
            <select name="gender" class="neo-input w-full p-2">
                <option value="">ไม่ระบุ</option>
                <option value="ชาย" <?= ($user['gender'] ?? '') === 'ชาย' ? 'selected' : '' ?>>ชาย</option>
                <option value="หญิง" <?= ($user['gender'] ?? '') === 'หญิง' ? 'selected' : '' ?>>หญิง</option>
                <option value="อื่นๆ" <?= ($user['gender'] ?? '') === 'อื่นๆ' ? 'selected' : '' ?>>อื่นๆ</option>
            </select>
        </div>
        
        <div class="mb-6">
            <label class="block font-bold mb-1">รหัสผ่านใหม่ (เว้นว่างหากไม่ต้องการเปลี่ยน)</label>
            <input type="password" name="password" 
                   class="neo-input w-full p-2" 
                   placeholder="••••••••">
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
    </script>
</div>
<?php include 'footer.php' ?>
