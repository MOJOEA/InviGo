<?php
$flash = getFlashMessage();
if ($flash):
    $icon = match($flash['type']) {
        'success' => 'check_circle',
        'error' => 'error',
        'warning' => 'warning',
        default => 'info'
    };
    $bgClass = match($flash['type']) {
        'success' => 'bg-[#D4FF33]',
        'error' => 'bg-red-100 text-red-700',
        'warning' => 'bg-yellow-100 text-yellow-700',
        default => 'bg-blue-100 text-blue-700'
    };
?>
<div class="flash-message mb-4 p-4 rounded-xl border-2 border-black font-bold <?= $bgClass ?>">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined"><?= $icon ?></span>
        <?= sanitize($flash['message']) ?>
    </div>
</div>
<?php endif; ?>
