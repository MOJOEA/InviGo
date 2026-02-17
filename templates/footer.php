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

    <script>
        function getCookie(name) {
            const value = '; ' + document.cookie;
            const parts = value.split('; ' + name + '=');
            if (parts.length === 2) return parts.pop().split(';').shift();
            return null;
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
