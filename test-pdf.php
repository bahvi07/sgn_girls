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
    
    // Fetch family details
    $stmt = $conn->prepare("SELECT * FROM family_details WHERE student_id = ?");
    $stmt->bind_param("i", $student['student_id']);
    $stmt->execute();
    $family = $stmt->get_result()->fetch_assoc();
    if ($family) $student = array_merge($student, $family);
    
    // Fetch contact details
    $stmt = $conn->prepare("SELECT * FROM contact_details WHERE student_id = ?");
    $stmt->bind_param("i", $student['student_id']);
    $stmt->execute();
    $contact = $stmt->get_result()->fetch_assoc();
    if ($contact) $student = array_merge($student, $contact);
    
    // Fetch educational qualifications
    $stmt = $conn->prepare("SELECT * FROM educational_qualifications WHERE student_id = ? ORDER BY year DESC");
    $stmt->bind_param("i", $student['student_id']);
    $stmt->execute();
    $qualifications = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $student['qualifications'] = $qualifications;
    
    // Map education fields for template compatibility with old field names
    if (!empty($qualifications[0])) {
        $edu = $qualifications[0];
        $student['exam_name'] = $edu['exam_type'] ?? '';
        $student['roll'] = $edu['roll_no'] ?? '';
        $student['year'] = $edu['year'] ?? '';
        $student['univ'] = $edu['university'] ?? '';
        $student['max'] = $edu['max_marks'] ?? '';
        $student['marks'] = $edu['marks_obtained'] ?? '';
        $student['percent'] = $edu['percentage'] ?? '';
        $student['institude'] = $student['institution_last_attended'] ?? '';
    }
    
    // Fetch enclosures/documents (as array)
    $stmt = $conn->prepare("SELECT document_name FROM documents WHERE student_id = ? ORDER BY id ASC");
    $stmt->bind_param("i", $student['student_id']);
    $stmt->execute();
    $docs = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    for ($i = 0; $i < 4; $i++) {
        $student['enclosure'.($i+1)] = $docs[$i]['document_name'] ?? '';
    }
    
    // Generate PDF with UTF-8 and Hindi support
    $options = new Options();
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isRemoteEnabled', true);
    $options->set('defaultFont', 'DejaVu Sans');
    $dompdf = new Dompdf($options);
    
    // Start output buffering
    ob_start();
    include 'pdf-template.php';
    $html = ob_get_clean();
    
    // Ensure proper UTF-8 encoding
    $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    
    // Clean (end) all output buffers before streaming PDF
    while (ob_get_level() > 0) {
        ob_end_clean();
    }

    try {
        // Add proper HTML structure with CSS for the font
        $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <style>
            body {
                font-family: "Noto Sans Devanagari", sans-serif;
                direction: ltr;
            }
        </style>
    </head>
    <body>' . $html . '</body></html>';
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    
    // Set PDF rendering options
    $dompdf->set_option('isRemoteEnabled', true);
    $dompdf->set_option('isHtml5ParserEnabled', true);
    $dompdf->set_option('isPhpEnabled', true);
    $dompdf->set_option('defaultFont', 'Noto Sans Devanagari');
    
    $dompdf->render();
    
    if ($download) {
        // Force download
        $dompdf->stream("application_{$formNo}.pdf", ["Attachment" => true]);
    } else {
        // Show in browser
        $dompdf->stream("application_{$formNo}.pdf", ["Attachment" => false]);
    }
    } catch (Throwable $e) {
        // Log error to a file
        file_put_contents(__DIR__ . '/pdf_error.log', date('c') . ' ' . $e->getMessage() . "\n", FILE_APPEND);
        header('Content-Type: text/plain; charset=utf-8');
        http_response_code(500);
        echo "PDF generation failed. Please check pdf_error.log for details.";
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