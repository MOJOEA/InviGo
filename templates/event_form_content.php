<?php include 'header.php' ?>
<?php
$isEdit = $editMode ?? false;
$event = $event ?? null;
$existingImages = $images ?? [];
?>
<div class="flex items-center gap-2 mb-6">
    <a href="/my-events" class="p-1 rounded-full hover:bg-gray-100">
        <span class="material-symbols-outlined">arrow_back</span>
    </a>
    <h1 class="text-2xl font-black"><?= $isEdit ? 'แก้ไขกิจกรรม' : 'สร้างกิจกรรมใหม่' ?></h1>
    <?php if (!$isEdit): ?>
        <span id="draftStatus" class="text-sm text-gray-400 ml-auto opacity-0 transition-opacity"></span>
        <button type="button" id="loadDraftBtn" onclick="loadDraft()" class="text-sm text-blue-600 hover:text-blue-800 font-bold hidden">
            โหลดข้อมูลที่บันทึกไว้
        </button>
    <?php endif; ?>
</div>
<?php if (!empty($errors['general'])): ?>
    <div class="bg-red-100 border-2 border-red-500 text-red-700 p-3 rounded-lg mb-4 font-bold">
        <?= sanitize($errors['general']) ?>
    </div>
<?php endif; ?>
<div class="neo-box w-full max-w-2xl mx-auto p-6 md:p-8">
    <form method="POST" action="<?= $isEdit ? '/events/' . $event['id'] . '/edit' : '/events/create' ?>" enctype="multipart/form-data">
        <div class="mb-4">
            <label class="font-bold block mb-1">ชื่อกิจกรรม <span class="text-red-500">*</span></label>
            <input type="text" name="title" class="neo-input w-full p-3 outline-none focus:shadow-[4px_4px_0px_0px_rgba(255,230,0,0.5)]" 
                value="<?= isset($_POST['title']) ? sanitize($_POST['title']) : ($event['title'] ?? '') ?>" required>
            <?php if (!empty($errors['title'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= sanitize($errors['title']) ?></p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <label class="font-bold block mb-1">รายละเอียด <span class="text-red-500">*</span></label>
            <textarea name="description" class="neo-input w-full p-3 h-32 outline-none focus:shadow-[4px_4px_0px_0px_rgba(255,230,0,0.5)]" required><?= isset($_POST['description']) ? sanitize($_POST['description']) : ($event['description'] ?? '') ?></textarea>
            <?php if (!empty($errors['description'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= sanitize($errors['description']) ?></p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <label class="font-bold block mb-1">วันที่จัดกิจกรรม <span class="text-red-500">*</span></label>
            <input type="datetime-local" name="event_date" class="neo-input w-full p-3 outline-none focus:shadow-[4px_4px_0px_0px_rgba(255,230,0,0.5)]" 
                value="<?= isset($_POST['event_date']) ? sanitize($_POST['event_date']) : (isset($event['event_date']) ? date('Y-m-d\TH:i', strtotime($event['event_date'])) : '') ?>" required>
            <?php if (!empty($errors['event_date'])): ?>
                <p class="text-red-500 text-sm mt-1"><?= sanitize($errors['event_date']) ?></p>
            <?php endif; ?>
        </div>
        <div class="mb-4">
            <label class="font-bold block mb-1">วันที่สิ้นสุดกิจกรรม</label>
            <input type="datetime-local" name="end_date" class="neo-input w-full p-3 outline-none focus:shadow-[4px_4px_0px_0px_rgba(255,230,0,0.5)]" 
                value="<?= isset($_POST['end_date']) ? sanitize($_POST['end_date']) : (isset($event['end_date']) && $event['end_date'] ? date('Y-m-d\TH:i', strtotime($event['end_date'])) : '') ?>">
            <p class="text-gray-400 text-xs mt-1">ถ้าไม่ระบุ จะถือว่าสิ้นสุดในวันเดียวกัน</p>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div>
                <label class="font-bold block mb-1">สถานที่ <span class="text-red-500">*</span></label>
                <input type="text" name="location" class="neo-input w-full p-3 outline-none focus:shadow-[4px_4px_0px_0px_rgba(255,230,0,0.5)]" 
                    value="<?= isset($_POST['location']) ? sanitize($_POST['location']) : ($event['location'] ?? '') ?>" placeholder="เช่น หอประชุม..." required>
                <?php if (!empty($errors['location'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= sanitize($errors['location']) ?></p>
                <?php endif; ?>
            </div>
            <div>
                <label class="font-bold block mb-1">รับจำนวน (คน) <span class="text-red-500">*</span></label>
                <input type="number" name="max_participants" class="neo-input w-full p-3 outline-none focus:shadow-[4px_4px_0px_0px_rgba(255,230,0,0.5)]" 
                    value="<?= isset($_POST['max_participants']) ? sanitize($_POST['max_participants']) : ($event['max_participants'] ?? '') ?>" min="1" required>
                <?php if (!empty($errors['max_participants'])): ?>
                    <p class="text-red-500 text-sm mt-1"><?= sanitize($errors['max_participants']) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="mb-8">
            <label class="font-bold block mb-3">รูปภาพกิจกรรม</label>
            <div id="dropZone" class="border-3 border-dashed border-gray-400 rounded-xl p-8 text-center bg-gray-50 hover:bg-yellow-50 transition-colors cursor-pointer relative">
                <input type="file" id="fileInput" name="images[]" accept="image/*" multiple class="hidden">
                <div class="pointer-events-none">
                    <span class="material-symbols-outlined text-5xl text-gray-400 mb-2">cloud_upload</span>
                    <p class="text-gray-600 font-bold mb-1">ลากภาพมาวางที่นี่ หรือคลิกเลือก</p>
                    <p class="text-gray-400 text-sm">รองรับ: JPG, PNG, GIF (สูงสุด 2MB ต่อไฟล์)</p>
                </div>
            </div>
            <div id="previewGrid" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3 mt-4">
                <?php foreach ($existingImages as $img): ?>
                    <div class="relative group aspect-square" data-existing="true" data-id="<?= $img['id'] ?>">
                        <img src="<?= sanitize($img['image_path']) ?>" class="w-full h-full object-cover rounded-lg border-2 border-black">
                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity rounded-lg flex items-center justify-center">
                            <label class="cursor-pointer text-white text-center">
                                <input type="checkbox" name="delete_images[]" value="<?= $img['id'] ?>" class="hidden delete-checkbox">
                                <span class="material-symbols-outlined text-2xl">delete</span>
                                <p class="text-xs">ลบ</p>
                            </label>
                        </div>
                        <div class="absolute top-1 right-1 hidden delete-badge">
                            <span class="bg-red-500 text-white rounded-full p-1 material-symbols-outlined text-sm">delete</span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="flex gap-4">
            <a href="/my-events" class="neo-btn w-full bg-white py-3 font-bold text-center">ยกเลิก</a>
            <button type="submit" class="neo-btn w-full bg-[#FFE600] py-3 font-bold hover:bg-[#ffe100]"><?= $isEdit ? 'บันทึกการแก้ไข' : 'บันทึกกิจกรรม' ?></button>
        </div>
    </form>
</div>
<style>
    #dropZone {
        border-width: 3px;
    }
    #dropZone.dragover {
        border-color: #FFE600;
        background-color: #FFFBF0;
    }
    #dropZone.dragover .material-symbols-outlined {
        color: #FFE600;
    }
    .preview-item {
        position: relative;
        aspect-ratio: 1;
    }
    .preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid black;
    }
    .preview-item .remove-btn {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border: 2px solid black;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 12px;
        box-shadow: 2px 2px 0 0 black;
    }
    .preview-item .remove-btn:hover {
        background: #dc2626;
    }
    .existing-marked {
        opacity: 0.5;
        filter: grayscale(100%);
    }
    .existing-marked .delete-badge {
        display: block !important;
    }
</style>
<script>
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const previewGrid = document.getElementById('previewGrid');
    let filesArray = [];
    dropZone.addEventListener('click', () => fileInput.click());
    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        handleFiles(e.dataTransfer.files);
    });
    function handleFiles(files) {
        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                filesArray.push(file);
                createPreview(file);
            }
        });
        updateFileInput();
    }
    function createPreview(file) {
        const reader = new FileReader();
        const div = document.createElement('div');
        div.className = 'preview-item';
        reader.onload = (e) => {
            div.innerHTML = `
                <img src="${e.target.result}" alt="${file.name}">
                <button type="button" class="remove-btn" onclick="removeFile(this)">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            `;
        };
        reader.readAsDataURL(file);
        const firstExisting = previewGrid.querySelector('[data-existing="true"]');
        if (firstExisting) {
            previewGrid.insertBefore(div, firstExisting);
        } else {
            previewGrid.appendChild(div);
        }
    }
    function removeFile(btn) {
        const index = Array.from(previewGrid.querySelectorAll('.preview-item')).indexOf(btn.closest('.preview-item'));
        filesArray.splice(index, 1);
        btn.closest('.preview-item').remove();
        updateFileInput();
    }
    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        filesArray.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }
    previewGrid.addEventListener('change', (e) => {
        if (e.target.classList.contains('delete-checkbox')) {
            const container = e.target.closest('[data-existing]');
            if (e.target.checked) {
                container.classList.add('existing-marked');
            } else {
                container.classList.remove('existing-marked');
            }
        }
    });

    // Auto-save draft functionality (only for create mode)
    <?php if (!$isEdit): ?>
    const DRAFT_KEY = 'invigo_event_draft';
    const form = document.querySelector('form');
    const draftStatus = document.getElementById('draftStatus');
    const loadDraftBtn = document.getElementById('loadDraftBtn');
    let saveTimeout;

    // Check for existing draft on load
    function checkDraft() {
        const draft = localStorage.getItem(DRAFT_KEY);
        if (draft) {
            const data = JSON.parse(draft);
            const hasData = data.title || data.description || data.location;
            if (hasData) {
                loadDraftBtn.classList.remove('hidden');
            }
        }
    }

    // Load draft data
    window.loadDraft = function() {
        const draft = localStorage.getItem(DRAFT_KEY);
        if (!draft) return;
        
        const data = JSON.parse(draft);
        if (data.title) form.querySelector('[name="title"]').value = data.title;
        if (data.description) form.querySelector('[name="description"]').value = data.description;
        if (data.event_date) form.querySelector('[name="event_date"]').value = data.event_date;
        if (data.end_date) form.querySelector('[name="end_date"]').value = data.end_date;
        if (data.location) form.querySelector('[name="location"]').value = data.location;
        if (data.max_participants) form.querySelector('[name="max_participants"]').value = data.max_participants;
        
        showDraftStatus('โหลดข้อมูลแล้ว ✓', 'text-green-600');
        loadDraftBtn.classList.add('hidden');
    };

    // Save draft
    function saveDraft() {
        const data = {
            title: form.querySelector('[name="title"]').value,
            description: form.querySelector('[name="description"]').value,
            event_date: form.querySelector('[name="event_date"]').value,
            end_date: form.querySelector('[name="end_date"]').value,
            location: form.querySelector('[name="location"]').value,
            max_participants: form.querySelector('[name="max_participants"]').value,
            savedAt: new Date().toISOString()
        };
        localStorage.setItem(DRAFT_KEY, JSON.stringify(data));
        showDraftStatus('บันทึกอัตโนมัติ ✓', 'text-green-600');
    }

    // Show draft status
    function showDraftStatus(text, colorClass) {
        draftStatus.textContent = text;
        draftStatus.className = `text-sm ml-auto transition-opacity ${colorClass}`;
        draftStatus.style.opacity = '1';
        setTimeout(() => {
            draftStatus.style.opacity = '0';
        }, 2000);
    }

    // Auto-save on input (debounced)
    form.querySelectorAll('input, textarea').forEach(input => {
        input.addEventListener('input', () => {
            clearTimeout(saveTimeout);
            saveTimeout = setTimeout(saveDraft, 1000);
        });
    });

    // Clear draft on successful submit
    form.addEventListener('submit', () => {
        localStorage.removeItem(DRAFT_KEY);
    });

    // Check for draft on page load
    checkDraft();
    <?php endif; ?>
</script>
<?php include 'footer.php' ?>
