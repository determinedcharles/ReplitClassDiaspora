
<?php

function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function loadJsonData($filename) {
    if (!file_exists($filename)) {
        return [];
    }
    
    $data = file_get_contents($filename);
    return json_decode($data, true) ?: [];
}

function saveJsonData($filename, $data) {
    $dir = dirname($filename);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    
    return file_put_contents($filename, json_encode($data, JSON_PRETTY_PRINT));
}

function generateId() {
    return uniqid();
}

function formatDate($date) {
    return date('M j, Y', strtotime($date));
}

function timeAgo($datetime) {
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    
    return date('M j, Y', strtotime($datetime));
}
?>
