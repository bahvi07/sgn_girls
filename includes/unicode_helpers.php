<?php
// Unicode helper functions
function normalizeUnicodeText($text) {
    if (empty($text)) {
        return '';
    }
    
    if (class_exists('Normalizer')) {
        $text = Normalizer::normalize(trim($text), Normalizer::FORM_C);
    } else {
        $text = trim($text);
    }
    
    // Convert to UTF-8 if not already
    if (!mb_check_encoding($text, 'UTF-8')) {
        $text = mb_convert_encoding($text, 'UTF-8', 'auto');
    }
    
    return $text;
}

function validateUnicodeText($text) {
    if (empty($text)) {
        return true;
    }
    return mb_check_encoding($text, 'UTF-8');
}

function sanitizeUnicodeText($text) {
    if (empty($text)) {
        return '';
    }
    
    $text = normalizeUnicodeText($text);
    
    // Remove any non-printable characters except spaces and common punctuation
    $text = preg_replace('/[^\p{L}\p{N}\p{P}\p{Z}\p{Sc}]/u', '', $text);
    
    // Remove control characters
    $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);
    
    // Normalize multiple spaces and trim
    $text = preg_replace('/\s+/u', ' ', $text);
    
    return trim($text);
}

// Function to safely output Hindi/Unicode text in HTML
function safeUnicodeOutput($text, $isHindi = false) {
    $text = htmlspecialchars($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    if ($isHindi) {
        return '<span class="hindi-text">' . $text . '</span>';
    }
    return $text;
}
?>
