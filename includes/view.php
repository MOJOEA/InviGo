<?php
function renderView(string $template, array $data = []): void
{
    $templatePath = TEMPLATES_DIR . '/' . $template . '.php';
    if (file_exists($templatePath)) {
        extract($data);
        require $templatePath;
    } else {
        echo "Template not found: $template";
    }
}
