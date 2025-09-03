<?php
require './includes/config.php';
require './admission/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

$previewUrl = '';
$formNo = $_GET['form_no'] ?? '';

if ($formNo) {
    // Check if we're just generating the PDF
    if (isset($_GET['download'])) {
        generatePdf($formNo, true);
        exit;
    }
    
    // Check if we're previewing
    if (isset($_GET['preview'])) {
        generatePdf($formNo, false);
        exit;
    }
    
    // For the initial form submission, we'll just show the preview in an iframe
    $previewUrl = "test-pdf.php?form_no=" . urlencode($formNo) . "&preview=1";
}

function generatePdf($formNo, $download = false) {
    global $conn;
    
    // Fetch student data
    $stmt = $conn->prepare("SELECT * FROM students WHERE form_no = ?");
    $stmt->bind_param("s", $formNo);
    $stmt->execute();
    $student = $stmt->get_result()->fetch_assoc();
    
    if (!$student) {
        die("No application found with form number: " . htmlspecialchars($formNo));
    }
    
    // Generate PDF
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    
    $dompdf = new Dompdf($options);
    
    // Start output buffering
    ob_start();
    include 'pdf-template.php';
    $html = ob_get_clean();
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    if ($download) {
        // Force download
        $dompdf->stream("application_{$formNo}.pdf", ["Attachment" => true]);
    } else {
        // Show in browser
        $dompdf->stream("application_{$formNo}.pdf", ["Attachment" => false]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>PDF Preview</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-container {
            position: relative;
            width: 100%;
            height: 80vh;
            border: 1px solid #ddd;
            margin-top: 20px;
        }
        .preview-toolbar {
            background: #f8f9fa;
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        iframe {
            width: 100%;
            height: calc(100% - 50px);
            border: none;
        }
    </style>
</head>
<body class="container mt-4">
    <div class="card">
      
        
        <div class="card-body">
            <form method="get" class="mb-4">
                <div class="input-group">
                    <input type="text" 
                           name="form_no" 
                           class="form-control" 
                           placeholder="Enter Form Number" 
                           value="<?= htmlspecialchars($formNo) ?>"
                           required>
                    <button type="submit" class="btn btn-primary">Preview PDF</button>
                </div>
            </form>
            
            <?php if ($previewUrl): ?>
                <div class="preview-container">
                    <div class="preview-toolbar">
                        <span>Previewing: Application #<?= htmlspecialchars($formNo) ?></span>
                        <a href="<?= $previewUrl ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                            Open in New Tab
                        </a>
                    </div>
                    <iframe src="<?= $previewUrl ?>" title="PDF Preview"></iframe>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Enter a form number and click "Preview PDF" to view the application.
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/your-code.js" crossorigin="anonymous"></script>
</body>
</html>