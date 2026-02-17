<?php
function uploadEventImages(array $files, string $uploadDir = null): array
{
    $uploadedPaths = [];
    if ($uploadDir === null) {
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/events/';
    }
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }
    foreach ($files['tmp_name'] as $index => $tmpName) {
        if (empty($tmpName)) continue;
        $originalName = $files['name'][$index];
        $fileType = $files['type'][$index];
        $fileSize = $files['size'][$index];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        if (!in_array($fileType, $allowedTypes)) {
            continue;
        }
        if ($fileSize > 2 * 1024 * 1024) {
            continue;
        }
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        $newFilename = uniqid('event_') . '.' . $extension;
        $destination = $uploadDir . $newFilename;
        if (move_uploaded_file($tmpName, $destination)) {
            $uploadedPaths[] = '/uploads/events/' . $newFilename;
        }
    }
    return $uploadedPaths;
}

function uploadProfileImage(array $file): string
{
    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (!in_array($file['type'], $allowedTypes)) {
        return '';
    }
    if ($file['size'] > 2 * 1024 * 1024) {
        return '';
    }
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/profiles/';
    if (!is_dir($uploadDir)) {
        @mkdir($uploadDir, 0777, true);
    }
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $newFilename = uniqid('profile_') . '.' . $extension;
    $destination = $uploadDir . $newFilename;
    if (move_uploaded_file($file['tmp_name'], $destination)) {
        return '/uploads/profiles/' . $newFilename;
    }
    return '';
}
