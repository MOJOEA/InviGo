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

    <div id="tutorialOverlay" class="fixed inset-0 bg-black bg-opacity-70 z-50 hidden">
        <div id="tutorialHighlight" class="absolute border-4 border-[#FFE600] rounded-xl shadow-[0_0_20px_rgba(255,230,0,0.5)] transition-all duration-300 pointer-events-none"></div>
        
        <div id="tutorialTooltip" class="absolute bg-white border-2 border-black rounded-xl p-4 shadow-[4px_4px_0px_0px_black] max-w-xs w-full transition-all duration-300">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-bold bg-[#FFE600] px-2 py-1 rounded border border-black" id="tutorialStep">1/4</span>
                <button onclick="skipTutorial()" class="text-gray-400 hover:text-black">
                    <span class="material-symbols-outlined text-sm">close</span>
                </button>
            </div>
            <p class="font-bold mb-2" id="tutorialTitle">ค้นหากิจกรรม</p>
            <p class="text-sm text-gray-500 mb-4" id="tutorialDesc">ค้นหากิจกรรมที่น่าสนใจและลงทะเบียนเข้าร่วมได้ที่นี่</p>
            <button onclick="nextTutorialStep()" class="neo-btn w-full bg-[#FFE600] py-2 font-bold text-sm">
                ถัดไป
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

        const tutorialSteps = [
            {
                selector: 'a[href="/explore"]',
                title: 'ค้นหากิจกรรม',
                desc: 'ค้นหากิจกรรมที่น่าสนใจและลงทะเบียนเข้าร่วม',
                placement: 'bottom'
            },
            {
                selector: 'a[href="/my-events"]',
                title: 'กิจกรรมของฉัน',
                desc: 'สร้างและจัดการกิจกรรมของคุณ',
                placement: 'bottom'
            },
            {
                selector: 'a[href="/my-registrations"]',
                title: 'การลงทะเบียน',
                desc: 'ดูสถานะการเข้าร่วมกิจกรรม',
                placement: 'bottom'
            },
            {
                selector: 'a[href="/profile"]',
                title: 'โปรไฟล์',
                desc: 'แก้ไขข้อมูลส่วนตัวและการตั้งค่า',
                placement: 'bottom'
            }
        ];
        
        let currentStep = 0;
        
        function showTutorial() {
            currentStep = 0;
            document.getElementById('tutorialOverlay').classList.remove('hidden');
            updateTutorialStep();
        }
        
        function nextTutorialStep() {
            currentStep++;
            if (currentStep >= tutorialSteps.length) {
                skipTutorial();
                return;
            }
            updateTutorialStep();
        }
        
        function updateTutorialStep() {
            const step = tutorialSteps[currentStep];
            const element = document.querySelector(step.selector);
            
            if (!element) {
                skipTutorial();
                return;
            }
            
            document.querySelectorAll('.nav-item, nav a').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
            
            const rect = element.getBoundingClientRect();
            const highlight = document.getElementById('tutorialHighlight');
            const tooltip = document.getElementById('tutorialTooltip');
            
            highlight.style.left = (rect.left - 8) + 'px';
            highlight.style.top = (rect.top - 8) + 'px';
            highlight.style.width = (rect.width + 16) + 'px';
            highlight.style.height = (rect.height + 16) + 'px';
            
            document.getElementById('tutorialStep').textContent = (currentStep + 1) + '/' + tutorialSteps.length;
            document.getElementById('tutorialTitle').textContent = step.title;
            document.getElementById('tutorialDesc').textContent = step.desc;
            
            const btnText = currentStep === tutorialSteps.length - 1 ? 'เสร็จสิ้น' : 'ถัดไป';
            document.querySelector('#tutorialTooltip button').textContent = btnText;
            
            let tooltipTop = rect.bottom + 20;
            let tooltipLeft = rect.left;
            
            if (window.innerWidth <= 768) {
                tooltipLeft = 20;
                tooltip.style.maxWidth = (window.innerWidth - 40) + 'px';
            } else {
                tooltipLeft = Math.min(rect.left, window.innerWidth - 340);
            }
            
            if (tooltipTop + 200 > window.innerHeight) {
                tooltipTop = rect.top - 220;
            }
            
            tooltip.style.left = tooltipLeft + 'px';
            tooltip.style.top = tooltipTop + 'px';
            
            element.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        function skipTutorial() {
            document.querySelectorAll('.nav-item, nav a').forEach(el => el.classList.remove('active'));
            setCookie('tutorialSeen', 'true', 365);
            document.getElementById('tutorialOverlay').classList.add('hidden');
            window.location.href = '/explore';
        }

        if (getCookie('tutorialSeen') !== 'true') {
            setTimeout(showTutorial, 1000);
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
