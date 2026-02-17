</main>

<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white p-6 rounded-xl border-2 border-black shadow-[6px_6px_0px_0px_black] max-w-sm w-full mx-4">
            <div class="w-16 h-16 bg-red-100 border-2 border-red-500 rounded-xl flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-4xl text-red-500">delete</span>
            </div>
            <h2 class="text-xl font-black text-center mb-2">ยืนยันการลบ</h2>
            <p class="text-gray-600 text-center mb-2">คุณต้องการลบกิจกรรม</p>
            <p class="text-center font-bold mb-4" id="deleteEventTitle"></p>
            <p class="text-red-500 text-sm text-center mb-6">การดำเนินการนี้ไม่สามารถย้อนกลับได้</p>
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteModal()" class="neo-btn w-full bg-white py-3 font-bold">ยกเลิก</button>
                <button type="button" onclick="submitDelete()" class="neo-btn w-full bg-red-100 text-red-600 py-3 font-bold hover:bg-red-200">ลบกิจกรรม</button>
            </div>
        </div>
    </div>
    <script>
        let deleteEventId = null;
        function confirmDelete(eventId, eventTitle) {
            deleteEventId = eventId;
            document.getElementById('deleteEventTitle').textContent = '"' + eventTitle + '" ใช่หรือไม่?';
            document.getElementById('deleteModal').classList.remove('hidden');
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteEventId = null;
        }
        function submitDelete() {
            if (deleteEventId) {
                document.getElementById('delete-form-' + deleteEventId).submit();
            }
        }
    </script>
    <?php $isLoggedInFooter = !empty(getCurrentUser()); ?>
    <nav class="md:hidden fixed bottom-0 w-full bg-white border-t-2 border-black p-2 flex justify-around rounded-t-xl z-50">
        <a href="/explore" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'explore' ? 'text-black' : 'text-gray-400' ?>">
            <span class="material-symbols-outlined text-xl">search</span>
            <span class="text-[10px] font-bold">ค้นหา</span>
        </a>
        <?php if ($isLoggedInFooter): ?>
        <a href="/my-events" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'my-events' ? 'text-black' : 'text-gray-400' ?>">
            <span class="material-symbols-outlined text-xl">calendar_month</span>
            <span class="text-[10px] font-bold">กิจกรรม</span>
        </a>
        <a href="/my-registrations" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'my-registrations' ? 'text-black' : 'text-gray-400' ?>">
            <span class="material-symbols-outlined text-xl">confirmation_number</span>
            <span class="text-[10px] font-bold">ลงทะเบียน</span>
        </a>
        <a href="/profile" class="flex flex-col items-center p-2 <?= ($activePage ?? '') === 'profile' ? 'text-black' : 'text-gray-400' ?>">
            <span class="material-symbols-outlined text-xl">person</span>
            <span class="text-[10px] font-bold">โปรไฟล์</span>
        </a>
        <?php else: ?>
        <div class="flex flex-col items-center p-2 text-gray-400 opacity-50" title="กรุณาเข้าสู่ระบบ">
            <span class="material-symbols-outlined text-xl">calendar_month</span>
            <span class="text-[10px] font-bold">กิจกรรม</span>
        </div>
        <div class="flex flex-col items-center p-2 text-gray-400 opacity-50" title="กรุณาเข้าสู่ระบบ">
            <span class="material-symbols-outlined text-xl">confirmation_number</span>
            <span class="text-[10px] font-bold">ลงทะเบียน</span>
        </div>
        <a href="/login" class="flex flex-col items-center p-2 text-gray-600">
            <span class="material-symbols-outlined text-xl">login</span>
            <span class="text-[10px] font-bold">เข้าสู่ระบบ</span>
        </a>
        <?php endif; ?>
    </nav>

    <div id="tutorialModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white border-4 border-black rounded-2xl p-6 max-w-md w-full shadow-[8px_8px_0px_0px_black]">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-[#FFE600] border-2 border-black rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="material-symbols-outlined text-3xl">school</span>
                </div>
                <h3 class="text-xl font-black mb-2">ยินดีต้อนรับสู่ InviGo!</h3>
                <p class="text-gray-500 text-sm">ระบบจัดการลงทะเบียนกิจกรรม</p>
            </div>
            
            <div class="space-y-4 mb-6">
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border-2 border-black">
                    <div class="w-10 h-10 bg-[#40E0D0] border-2 border-black rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">search</span>
                    </div>
                    <div>
                        <p class="font-bold">ค้นหากิจกรรม</p>
                        <p class="text-xs text-gray-500">ดูกิจกรรมที่น่าสนใจและลงทะเบียน</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border-2 border-black">
                    <div class="w-10 h-10 bg-[#FFE600] border-2 border-black rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">calendar_month</span>
                    </div>
                    <div>
                        <p class="font-bold">กิจกรรมของฉัน</p>
                        <p class="text-xs text-gray-500">สร้างและจัดการกิจกรรมของคุณ</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border-2 border-black">
                    <div class="w-10 h-10 bg-[#D4FF33] border-2 border-black rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">confirmation_number</span>
                    </div>
                    <div>
                        <p class="font-bold">การลงทะเบียน</p>
                        <p class="text-xs text-gray-500">ดูสถานะการเข้าร่วมกิจกรรม</p>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border-2 border-black">
                    <div class="w-10 h-10 bg-white border-2 border-black rounded-lg flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">person</span>
                    </div>
                    <div>
                        <p class="font-bold">โปรไฟล์</p>
                        <p class="text-xs text-gray-500">แก้ไขข้อมูลและการตั้งค่า</p>
                    </div>
                </div>
            </div>
            
            <button onclick="closeTutorial()" class="neo-btn w-full bg-[#FFE600] py-3 font-bold">
                เข้าใจแล้ว เริ่มใช้งาน!
            </button>
        </div>
    </div>

    <script>
        function getCookie(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
        }

        function setCookie(name, value, days) {
            const expires = new Date(Date.now() + days * 864e5).toUTCString();
            document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
        }

        function closeTutorial() {
            setCookie('tutorialSeen', 'true', 365);
            document.getElementById('tutorialModal').classList.add('hidden');
        }

        function showTutorial() {
            document.getElementById('tutorialModal').classList.remove('hidden');
        }

        if (getCookie('tutorialSeen') !== 'true') {
            setTimeout(showTutorial, 500);
        }

        const clickSound = new Audio('/sfx/click4_11.wav');
        clickSound.volume = 0.3;
        
        document.addEventListener('click', function(e) {
            const soundEnabled = getCookie('soundEnabled') !== 'false';
            if (!soundEnabled) return;
            
            const target = e.target.closest('button, a, .neo-btn, .neo-btn-small, .gender-btn, .nav-item, [onclick]');
            if (target) {
                clickSound.currentTime = 0;
                clickSound.play().catch(() => {});
            }
        });
    </script>
</body>
</html>
