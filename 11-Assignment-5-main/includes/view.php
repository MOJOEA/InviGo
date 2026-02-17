<?php

function renderView(string $template, array $data = []): void
{
    $data = $data; // Make data available in templates
    $templatePath = TEMPLATES_DIR . '/' . $template . '.php';

    if (file_exists($templatePath)) {
        require_once $templatePath;
    } else {
        echo "Template not found: $template";
    }
}
?>
