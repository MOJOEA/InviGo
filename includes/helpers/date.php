<?php
function formatThaiDate(string $date): string
{
    $months = [
        '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.',
        '04' => 'เม.ย.', '05' => 'พ.ค.', '06' => 'มิ.ย.',
        '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.',
        '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'
    ];
    $timestamp = strtotime($date);
    $day = date('j', $timestamp);
    $month = $months[date('m', $timestamp)];
    $year = date('Y', $timestamp) + 543;
    return "$day $month $year";
}
function formatThaiDateTime(string $datetime): string
{
    return formatThaiDate($datetime) . ' ' . date('H:i', strtotime($datetime)) . ' น.';
}
function calculateAge(?string $birthDate): ?int
{
    if (!$birthDate) return null;
    $birth = new DateTime($birthDate);
    $today = new DateTime();
    return $today->diff($birth)->y;
}
