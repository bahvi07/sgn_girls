<?php
// Test script to check photo access and permissions
echo "<h1>Photo Access Test</h1>";

// Check if we have a photo parameter
$photoName = $_GET['photo'] ?? 'photo_68b7ee3e521442.01716668.png';
$photoPath = __DIR__ . '/uploads/photos/' . $photoName;
$webPath = '/sgn-law-admission/uploads/photos/' . $photoName;

// Debug information
$debugInfo = [
    'photo_name' => $photoName,
    'full_path' => $photoPath,
    'web_path' => $webPath,
    'file_exists' => file_exists($photoPath) ? 'Yes' : 'No',
    'is_readable' => is_readable($photoPath) ? 'Yes' : 'No',
    'permissions' => substr(sprintf('%o', fileperms($photoPath)), -4),
    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Not set',
    'current_dir' => __DIR__
];

// Output debug information
echo "<h2>Debug Information</h2>";
echo "<pre>" . print_r($debugInfo, true) . "</pre>";

// Try to display the image if it exists
if (file_exists($photoPath)) {
    echo "<h2>Image Preview</h2>";
    echo "<img src='$webPath' alt='Test Photo' style='max-width: 300px; border: 1px solid #ccc;'>";
    
    // Try to get image info
    $imageInfo = @getimagesize($photoPath);
    if ($imageInfo !== false) {
        echo "<h3>Image Information</h3>";
        echo "<pre>" . print_r($imageInfo, true) . "</pre>";
    } else {
        echo "<p>Error: Could not read image information.</p>";
    }
} else {
    echo "<p>Error: The specified photo does not exist at the expected location.</p>";
    
    // Try to list the contents of the uploads directory
    $uploadDir = __DIR__ . '/uploads/photos/';
    if (is_dir($uploadDir)) {
        echo "<h3>Available Photos in uploads/photos/</h3>";
        $files = scandir($uploadDir);
        echo "<ul>";
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                echo "<li>$file</li>";
            }
        }
        echo "</ul>";
    } else {
        echo "<p>Error: The uploads directory does not exist or is not accessible.</p>";
    }
}
?>

<h2>Test Another Photo</h2>
<form method="get">
    <label for="photo">Photo filename:</label>
    <input type="text" id="photo" name="photo" value="<?= htmlspecialchars($photoName) ?>" style="width: 300px;">
    <button type="submit">Test</button>
</form>
