</main>

<div id="tour-highlighter"></div>
<svg id="tour-arrow" viewBox="0 0 24 24" fill="currentColor" style="opacity: 0;">
    <path d="M12 2l-10 20h20L12 2z"/>
</svg>
<div id="tour-popover" style="display: none;">
    <div class="step-badge" id="tour-step-num">1/4</div>
    <h3 id="tour-title" class="text-xl font-black mb-2 mt-2">Title</h3>
    <p id="tour-desc" class="text-gray-600 mb-4">Description</p>
    <div class="flex justify-end">
        <button id="tour-next-btn" onclick="nextStep()" class="neo-btn bg-black text-white px-4 py-2 font-bold inline-flex items-center gap-2">
            ถัดไป <span class="material-symbols-outlined text-sm">arrow_forward</span>
        </button>
    </div>
</div>

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
            document.addEventListener('keydown', handleDeleteKeydown);
        }
        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            deleteEventId = null;
            document.removeEventListener('keydown', handleDeleteKeydown);
        }
        function submitDelete() {
            if (deleteEventId) {
                document.getElementById('delete-form-' + deleteEventId).submit();
            }
        }
        function handleDeleteKeydown(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                submitDelete();
            } else if (e.key === 'Escape') {
                e.preventDefault();
                closeDeleteModal();
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

        function setCookie(name, value, days) {
            const expires = new Date(Date.now() + days * 864e5).toUTCString();
            document.cookie = name + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/';
        }

        const tourSteps = [
            { target: 'a[href="/explore"]', title: 'ค้นหากิจกรรม', desc: 'ค้นหากิจกรรมที่น่าสนใจและลงทะเบียนเข้าร่วม', placement: 'bottom' },
            { target: 'a[href="/my-events"]', title: 'กิจกรรมของฉัน', desc: 'สร้างและจัดการกิจกรรมของคุณ', placement: 'bottom' },
            { target: 'a[href="/my-registrations"]', title: 'การลงทะเบียน', desc: 'ดูสถานะการเข้าร่วมกิจกรรม', placement: 'bottom' },
            { target: 'a[href="/profile"]', title: 'โปรไฟล์', desc: 'แก้ไขข้อมูลส่วนตัวและการตั้งค่า', placement: 'bottom' }
        ];
        let currentTourStep = 0;
        let isTourActive = false;

        function startTour() {
            if (isTourActive) return;
            isTourActive = true;
            currentTourStep = 0;
            document.getElementById('tour-highlighter').classList.add('active');
            renderTourStep();
        }
        window.startTour = startTour;

        function endTour() {
            isTourActive = false;
            document.getElementById('tour-highlighter').classList.remove('active');
            document.getElementById('tour-popover').style.display = 'none';
            document.getElementById('tour-arrow').style.opacity = '0';
            // Remove active from all nav items and tour highlights
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
            document.querySelectorAll('.tour-highlight-target').forEach(el => el.classList.remove('tour-highlight-target'));
            setCookie('tourSeen', 'true', 365);

            setTimeout(() => {
                window.location.href = '/explore';
            }, 100);
        }
        window.endTour = endTour;

        function nextStep() {
            if (currentTourStep < tourSteps.length - 1) {
                currentTourStep++;
                renderTourStep();
            } else {
                endTour();
            }
        }
        window.nextStep = nextStep;

        function renderTourStep() {
            const step = tourSteps[currentTourStep];
            const targetEl = document.querySelector(step.target);
            if (!targetEl) return;
            
            // Remove highlight from previous targets
            document.querySelectorAll('.tour-highlight-target').forEach(el => el.classList.remove('tour-highlight-target'));
            // Add highlight to current target (brings it above highlighter)
            targetEl.classList.add('tour-highlight-target');
            
            // Remove active from all nav items first
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active'));
            // Add active to current target if it's a nav-item
            if (targetEl.classList.contains('nav-item')) {
                targetEl.classList.add('active');
            }
            
            const highlighter = document.getElementById('tour-highlighter');
            const popover = document.getElementById('tour-popover');
            const arrow = document.getElementById('tour-arrow');
            const rect = targetEl.getBoundingClientRect();
            const padding = 10;
            highlighter.style.top = (rect.top - padding) + 'px';
            highlighter.style.left = (rect.left - padding) + 'px';
            highlighter.style.width = (rect.width + padding * 2) + 'px';
            highlighter.style.height = (rect.height + padding * 2) + 'px';
            document.getElementById('tour-title').innerText = step.title;
            document.getElementById('tour-desc').innerText = step.desc;
            document.getElementById('tour-step-num').innerText = `${currentTourStep + 1}/${tourSteps.length}`;
            const nextBtn = document.getElementById('tour-next-btn');
            if (currentTourStep === tourSteps.length - 1) {
                nextBtn.innerHTML = 'เสร็จสิ้น <span class="material-symbols-outlined text-sm">check</span>';
                nextBtn.classList.remove('bg-black', 'text-white');
                nextBtn.classList.add('bg-[#FFE600]', 'text-black');
            } else {
                nextBtn.innerHTML = 'ถัดไป <span class="material-symbols-outlined text-sm">arrow_forward</span>';
                nextBtn.classList.add('bg-black', 'text-white');
                nextBtn.classList.remove('bg-[#FFE600]', 'text-black');
            }
            const popoverWidth = 320;
            const gap = 20;
            let popTop, popLeft;
            if (step.placement === 'right') {
                popLeft = rect.right + gap + 40;
                popTop = rect.top;
                arrow.style.left = (rect.right + 5) + 'px';
                arrow.style.top = (rect.top + 20) + 'px';
                arrow.style.transform = 'rotate(180deg)';
            } else if (step.placement === 'left') {
                popLeft = rect.left - popoverWidth - gap - 40;
                popTop = rect.top;
                arrow.style.left = (rect.left - 65) + 'px';
                arrow.style.top = (rect.top + 20) + 'px';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                popLeft = rect.left;
                popTop = rect.bottom + gap + 40;
                arrow.style.left = (rect.left + rect.width / 2 - 30) + 'px';
                arrow.style.top = (rect.bottom + 5) + 'px';
                arrow.style.transform = 'rotate(-90deg)';
            }
            if (popLeft + popoverWidth > window.innerWidth) popLeft = window.innerWidth - popoverWidth - 20;
            if (popTop + 200 > window.innerHeight) popTop = window.innerHeight - 200 - 20;
            popover.style.top = popTop + 'px';
            popover.style.left = popLeft + 'px';
            popover.style.display = 'block';
            popover.classList.remove('active');
            void popover.offsetWidth;
            popover.classList.add('active');
            arrow.style.opacity = '1';
            targetEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }

        window.addEventListener('resize', () => { if (isTourActive) renderTourStep(); });

        if (getCookie('tourSeen') !== 'true') {
            setTimeout(startTour, 1500);
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

        window.addEventListener('scroll', () => {
            const scrollTotal = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollCurrent = document.documentElement.scrollTop;
            const scrollPercentage = (scrollCurrent / scrollTotal) * 100;
            const progressBar = document.getElementById('scroll-progress');
            if (progressBar) {
                progressBar.style.width = scrollPercentage + '%';
            }
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</body>
</html>
