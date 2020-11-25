<?php
if (!current_user_can('manage_options')) {
    return;
}

include_once __DIR__ . '/new.php';
?>
