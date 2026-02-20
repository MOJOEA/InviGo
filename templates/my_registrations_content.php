<?php include 'header.php' ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<?php
function getStatusBadge(string $status, bool $checkedIn = false): string {
    if ($status === 'approved' && $checkedIn) {
        return '<span class="bg-blue-500 text-white border-2 border-black px-3 py-1.5 rounded-lg text-xs font-black inline-flex items-center gap-1 shadow-[2px_2px_0px_0px_black]"><span class="material-symbols-outlined text-sm">check_circle</span> เช็คชื่อแล้ว</span>';
    }
    return match($status) {
        'pending' => '<span class="bg-yellow-400 text-black border-2 border-black px-3 py-1.5 rounded-lg text-xs font-black inline-flex items-center gap-1 shadow-[2px_2px_0px_0px_black]"><span class="material-symbols-outlined text-sm">schedule</span> รออนุมัติ</span>',
        'approved' => '<span class="bg-green-400 text-black border-2 border-black px-3 py-1.5 rounded-lg text-xs font-black inline-flex items-center gap-1 shadow-[2px_2px_0px_0px_black]"><span class="material-symbols-outlined text-sm">check</span> อนุมัติแล้ว</span>',
        'rejected' => '<span class="bg-red-400 text-white border-2 border-black px-3 py-1.5 rounded-lg text-xs font-black inline-flex items-center gap-1 shadow-[2px_2px_0px_0px_black]"><span class="material-symbols-outlined text-sm">close</span> ปฏิเสธ</span>',
        default => '<span class="bg-gray-200 text-gray-700 border-2 border-black px-3 py-1.5 rounded-lg text-xs font-black">' . $status . '</span>'
    };
}
?>
<h2 class="text-3xl font-black mb-6 flex items-center gap-2">
    การลงทะเบียนของฉัน 
    <span class="material-symbols-outlined text-3xl">confirmation_number</span>
</h2>
<?php if (empty($registrations)): ?>
    <div class="empty-state">
            <div class="empty-state-icon">
                <span class="material-symbols-outlined">confirmation_number</span>
            </div>
            <p class="text-gray-500 font-bold text-lg mb-2">ยังไม่มีการลงทะเบียน</p>
            <p class="text-gray-400 text-sm mb-6">ค้นหากิจกรรมที่น่าสนใจและลงทะเบียนเลย!</p>
            <a href="/explore" class="neo-btn inline-block bg-[#FFE600] px-6 py-3 font-bold">ค้นหากิจกรรม</a>
        </div>
<?php else: ?>
    <div class="space-y-4">
        <?php foreach ($registrations as $reg): 
            $now = new DateTime();
            $endDateStr = $reg['end_date'] ?? $reg['event_date'];
            $eventDate = new DateTime($endDateStr);
            $isPast = $eventDate < $now;
            $isFull = $reg['approved_count'] >= $reg['max_participants'];
        ?>
            <div class="neo-box p-4 flex flex-col md:flex-row gap-4 relative" data-reg-id="<?= $reg['id'] ?>">
                <a href="/events/<?= $reg['event_id'] ?>" class="w-full md:w-32 h-24 bg-gray-200 rounded-lg border-2 border-black overflow-hidden flex-shrink-0 block">
                    <?php if ($reg['image']): ?>
                        <img src="<?= sanitize($reg['image']) ?>" class="w-full h-full object-cover" onerror="this.src='https://placehold.co/400x200'">
                    <?php else: ?>
                        <div class="w-full h-full flex items-center justify-center bg-gray-100">
                            <span class="material-symbols-outlined text-3xl text-gray-300">event</span>
                        </div>
                    <?php endif; ?>
                </a>
                <div class="flex-1 flex flex-col">
                    <div class="flex flex-wrap items-start justify-between gap-2 mb-2">
                        <a href="/events/<?= $reg['event_id'] ?>" class="font-black text-lg hover:text-[#40E0D0] leading-tight"><?= sanitize($reg['title']) ?></a>
                        <div class="flex-shrink-0 status-badge">
                            <?= getStatusBadge($reg['status'], $reg['checked_in']) ?>
                        </div>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-500 mb-1">
                            <span class="material-symbols-outlined text-sm inline">calendar_month</span>
                            <?= formatThaiDateTime($reg['event_date']) ?>
                        </p>
                        <p class="text-sm text-gray-500 mb-1">
                            <span class="material-symbols-outlined text-sm inline">location_on</span>
                            <?= sanitize($reg['location']) ?>
                        </p>
                        <p class="text-xs text-gray-400">
                            ผู้จัด: <?= sanitize($reg['organizer_name']) ?> • 
                            ลงทะเบียนเมื่อ <?= date('d/m/Y', strtotime($reg['created_at'])) ?>
                        </p>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3 pt-3 border-t border-gray-200">
                        <?php if ($reg['status'] === 'approved' && !$reg['checked_in'] && !$isPast): ?>
                            <?php if ($reg['otp']): ?>
                                <button onclick="showOtpModal('<?= $reg['otp']['otp_code'] ?>', <?= strtotime($reg['otp']['expires_at']) ?>)" 
                                    class="checkin-btn neo-btn-small bg-[#40E0D0] px-4 py-2 text-sm font-bold inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">check_circle</span>
                                    เช็คอิน
                                </button>
                            <?php else: ?>
                                <a href="/events/<?= $reg['event_id'] ?>/otp" class="neo-btn-small bg-[#40E0D0] px-4 py-2 text-sm font-bold inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-sm">qr_code</span>
                                    ขอ OTP
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                        <?php if (!$reg['checked_in'] && !$isPast && $reg['status'] !== 'approved'): ?>
                            <button onclick="showWithdrawModal('<?= $reg['event_id'] ?>', '<?= sanitize($reg['title']) ?>')" 
                                class="neo-btn-small bg-red-100 text-red-600 px-4 py-2 text-sm font-bold inline-flex items-center gap-1 hover:bg-red-200">
                                <span class="material-symbols-outlined text-sm">close</span>
                                ยกเลิก
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<div id="withdrawModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border-4 border-black rounded-2xl p-6 max-w-sm w-full shadow-[8px_8px_0px_0px_black]">
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 border-2 border-black rounded-full flex items-center justify-center mx-auto mb-4">
                <span class="material-symbols-outlined text-3xl text-red-600">warning</span>
            </div>
            <h3 class="text-xl font-black mb-2">ยืนยันการยกเลิก</h3>
            <p class="text-gray-500 mb-2">คุณต้องการยกเลิกการลงทะเบียนกิจกรรม</p>
            <p id="withdrawEventTitle" class="font-bold text-black mb-6 text-lg"></p>
            <div class="flex gap-3">
                <button onclick="closeWithdrawModal()" class="neo-btn flex-1 bg-gray-200 py-3 font-bold">
                    ไม่ยกเลิก
                </button>
                <a id="withdrawConfirmLink" href="#" class="neo-btn flex-1 bg-red-400 text-white py-3 font-bold text-center inline-flex items-center justify-center gap-1">
                    <span class="material-symbols-outlined text-sm">delete</span>
                    ยืนยัน
                </a>
            </div>
        </div>
    </div>
</div>


<?php if ($otpData): ?>
<div id="otpDisplayModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white border-4 border-black rounded-2xl p-6 max-w-sm w-full shadow-[8px_8px_0px_0px_black]">
        <div class="text-center">
            <h3 class="text-xl font-black mb-2">รหัส OTP เช็คอิน</h3>
            <p class="text-sm text-gray-500 mb-4">แสดงรหัสนี้ให้ผู้จัดงานสแกน</p>
            
            <div class="bg-[#D4FF33] border-4 border-black rounded-xl p-6 mb-4">
                <p id="displayOtpCode" class="text-5xl font-black tracking-widest font-mono"><?= $otpData['code'] ?></p>
            </div>
            
            <p class="text-sm text-gray-500 mb-6">
                หมดอายุ: <span id="displayOtpCountdown" class="font-bold text-red-500"></span> น.
            </p>
            
            <button onclick="closeOtpDisplayModal()" class="neo-btn bg-gray-200 px-6 py-3 font-bold w-full">
                ปิด
            </button>
        </div>
    </div>
</div>

<script>

<?php if ($otpData && isset($otpData['expires'])): ?>
let otpExpiryTime = <?= strtotime($otpData['expires']) * 1000 ?>;
<?php else: ?>
let otpExpiryTime = 0;
<?php endif; ?>
let displayOtpTimer = null;
const displayOtpCountdownEl = document.getElementById('displayOtpCountdown');

function updateDisplayCountdown() {
    if (!displayOtpCountdownEl) return;
    
    const now = Date.now();
    const remaining = Math.max(0, Math.floor((otpExpiryTime - now) / 1000));
    
    if (remaining > 0) {
        const hours = Math.floor(remaining / 3600);
        const mins = Math.floor((remaining % 3600) / 60);
        const secs = remaining % 60;
        
        let timeStr = '';
        if (hours > 0) {
            timeStr = String(hours).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        } else {
            timeStr = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
        }
        displayOtpCountdownEl.textContent = timeStr;
    } else {
        displayOtpCountdownEl.textContent = 'หมดอายุแล้ว';
        const otpCodeEl = document.getElementById('displayOtpCode');
        if (otpCodeEl) otpCodeEl.textContent = '------';
        if (displayOtpTimer) {
            clearInterval(displayOtpTimer);
            displayOtpTimer = null;
        }
    }
}

function startDisplayCountdown() {
    if (displayOtpTimer) {
        clearInterval(displayOtpTimer);
    }
    if (otpExpiryTime > Date.now()) {
        displayOtpTimer = setInterval(updateDisplayCountdown, 1000);
        updateDisplayCountdown();
    }
}

function closeOtpDisplayModal() {
    const modal = document.getElementById('otpDisplayModal');
    if (modal) modal.classList.add('hidden');
    if (displayOtpTimer) {
        clearInterval(displayOtpTimer);
        displayOtpTimer = null;
    }
}

document.addEventListener('click', function(e) {
    const modal = document.getElementById('otpDisplayModal');
    if (modal && e.target === modal) {
        closeOtpDisplayModal();
    }
});

if (otpExpiryTime > 0) {
    startDisplayCountdown();
}
<?php endif; ?>
</script>

<div id="otpModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white border-4 border-black rounded-2xl p-6 max-w-sm w-full shadow-[8px_8px_0px_0px_black]">
        <div class="text-center">
            <h3 class="text-xl font-black mb-2">รหัส OTP เช็คอิน</h3>
            <p class="text-sm text-gray-500 mb-4">แสดงรหัสนี้ให้ผู้จัดงานสแกน</p>
            
            <div class="bg-[#D4FF33] border-4 border-black rounded-xl p-4 mb-4">
                <div id="qrcode" class="flex justify-center mb-3"></div>
                <p class="text-xs text-gray-600 mb-1">หรือกรอกรหัส:</p>
                <span id="otpCode" class="text-4xl font-black tracking-widest"></span>
            </div>
            
            <p class="text-sm text-gray-500 mb-6">
                หมดอายุ: <span id="otpExpires" class="font-bold text-red-500"></span> น.
            </p>
            
            <button onclick="closeOtpModal()" class="neo-btn bg-gray-200 px-6 py-2 font-bold w-full">
                ปิด
            </button>
        </div>
    </div>
</div>

<script>
function showWithdrawModal(eventId, eventTitle) {
    document.getElementById('withdrawEventTitle').textContent = eventTitle;
    document.getElementById('withdrawConfirmLink').href = '/events/' + eventId + '/withdraw';
    document.getElementById('withdrawModal').classList.remove('hidden');
    document.addEventListener('keydown', handleWithdrawKeydown);
}

function closeWithdrawModal() {
    document.getElementById('withdrawModal').classList.add('hidden');
    document.removeEventListener('keydown', handleWithdrawKeydown);
}

function handleWithdrawKeydown(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        document.getElementById('withdrawConfirmLink').click();
    } else if (e.key === 'Escape') {
        e.preventDefault();
        closeWithdrawModal();
    }
}

document.getElementById('withdrawModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeWithdrawModal();
    }
});

function showOtpModal(otp, expiresAt) {
    document.getElementById('otpCode').textContent = otp;
    document.getElementById('otpModal').classList.remove('hidden');
    document.addEventListener('keydown', handleOtpKeydown);
    
    // Generate QR Code
    const qrContainer = document.getElementById('qrcode');
    qrContainer.innerHTML = '';
    new QRCode(qrContainer, {
        text: otp,
        width: 180,
        height: 180,
        colorDark: '#000000',
        colorLight: '#D4FF33',
        correctLevel: QRCode.CorrectLevel.M
    });
    
    const otpExpiresEl = document.getElementById('otpExpires');
    let otpModalTimer = null;
    
    function updateOtpModalCountdown() {
        const now = Date.now();
        const expiryMs = expiresAt * 1000;
        const remaining = Math.max(0, Math.floor((expiryMs - now) / 1000));
        
        if (remaining > 0) {
            const hours = Math.floor(remaining / 3600);
            const mins = Math.floor((remaining % 3600) / 60);
            const secs = remaining % 60;
            
            let timeStr = '';
            if (hours > 0) {
                timeStr = String(hours).padStart(2, '0') + ':' + String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            } else {
                timeStr = String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
            }
            otpExpiresEl.textContent = timeStr;
        } else {
            otpExpiresEl.textContent = 'หมดอายุแล้ว';
            document.getElementById('otpCode').textContent = '------';
            if (otpModalTimer) {
                clearInterval(otpModalTimer);
                otpModalTimer = null;
            }
        }
    }
    
    if (window.otpModalTimer) {
        clearInterval(window.otpModalTimer);
    }
    updateOtpModalCountdown();
    window.otpModalTimer = setInterval(updateOtpModalCountdown, 1000);
}

function closeOtpModal() {
    document.getElementById('otpModal').classList.add('hidden');
    document.removeEventListener('keydown', handleOtpKeydown);
    if (window.otpModalTimer) {
        clearInterval(window.otpModalTimer);
        window.otpModalTimer = null;
    }
}

function handleOtpKeydown(e) {
    if (e.key === 'Enter' || e.key === 'Escape') {
        e.preventDefault();
        closeOtpModal();
    }
}

document.getElementById('otpModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeOtpModal();
    }
});

setInterval(async () => {
    try {
        const res = await fetch('/api/registrations-status');
        const data = await res.json();
        let shouldReload = false;
        data.forEach(reg => {
            const card = document.querySelector(`[data-reg-id="${reg.id}"]`);
            if (card) {
                const badge = card.querySelector('.status-badge');
                const oldStatus = badge?.dataset?.status;
                const newStatus = reg.status + (reg.checked_in ? '-checked_in' : '');
                
                if (oldStatus && oldStatus !== newStatus) {
                    shouldReload = true;
                }
                
                if (badge && !oldStatus) {
                    badge.dataset.status = newStatus;
                }
            }
        });
        if (shouldReload) location.reload();
    } catch (e) {}
}, 3000);

function showToast(message, type = 'success') {
    const existing = document.querySelector('.toast-notification');
    if (existing) existing.remove();
    
    const toast = document.createElement('div');
    toast.className = `toast-notification fixed top-4 right-4 z-50 px-4 py-3 rounded-xl border-2 border-black shadow-[4px_4px_0px_0px_black] font-bold animate-bounce ${type === 'success' ? 'bg-[#D4FF33]' : 'bg-red-100 text-red-600'}`;
    toast.innerHTML = `<span class="material-symbols-outlined inline mr-1">${type === 'success' ? 'check_circle' : 'error'}</span>${message}`;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transition = 'opacity 0.5s';
        setTimeout(() => toast.remove(), 500);
    }, 3000);
}
</script>

<?php include 'footer.php' ?>
