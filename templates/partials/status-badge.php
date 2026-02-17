<?php
$checkedIn = $checkedIn ?? false;
$badgeClass = match($status) {
    'pending' => 'bg-yellow-100 text-yellow-700 border-yellow-500',
    'approved' => $checkedIn ? 'bg-blue-100 text-blue-700 border-blue-500' : 'bg-green-100 text-green-700 border-green-500',
    'rejected' => 'bg-red-100 text-red-700 border-red-500',
    default => 'bg-gray-100 text-gray-700 border-gray-500'
};
$label = match($status) {
    'pending' => 'รออนุมัติ',
    'approved' => $checkedIn ? '✓ เช็คชื่อแล้ว' : 'อนุมัติแล้ว',
    'rejected' => 'ปฏิเสธ',
    default => $status
};
?>
<span class="<?= $badgeClass ?> px-2 py-1 rounded border text-xs font-bold">
    <?= $label ?>
</span>
