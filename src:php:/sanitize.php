<?php
// sanitize.php - simple helper to sanitize user inputs.
function sanitize_input(string $data): string {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}
?>