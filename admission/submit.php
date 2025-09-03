<?php
date_default_timezone_set('Asia/Kolkata');
require(__DIR__ . '/../includes/config.php');
require './vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-type:application/json');
header('Content-Type: text/html; charset=utf-8');
// Start transaction
$conn->begin_transaction();

// unicode helper function
function normalizeUnicodeText($text) {
    if(class_exists('Normalizer')) {
        return Normalizer::normalize(trim($text),Normalizer::FORM_C);
    }
    return trim($text); // Fallback if intl extension is not available
}


function validateUnicodeText($text) {
    // Check if text contains valid Unicode characters
    return mb_check_encoding($text, 'UTF-8');
}

function sanitizeUnicodeText($text) {
    $text = normalizeUnicodeText($text);
    // Remove any non-printable characters except spaces
    $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $text);
    return $text;
}

try {
    // 1. Handle file uploads
    $photoPath = '';
    if (isset($_FILES['applicant_photo']) && $_FILES['applicant_photo']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "../uploads/photos/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $fileName = uniqid('photo_', true) . '.' . pathinfo($_FILES['applicant_photo']['name'], PATHINFO_EXTENSION);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['applicant_photo']['tmp_name'], $targetFile)) {
            $photoPath = $fileName;
        }
    }

    // 2. Handle class_roll_no - check for duplicates and generate a new one if needed
    $class_roll_no = $_POST['class_roll_no'] ?? null;
    if (!empty($class_roll_no)) {
        $checkStmt = $conn->prepare("SELECT student_id FROM students WHERE class_roll_no = ?");
        $checkStmt->bind_param("s", $class_roll_no);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        $checkStmt->close();
        
        // If roll number exists, append a random 2-digit number to make it unique
        if ($checkResult->num_rows > 0) {
            $class_roll_no = $class_roll_no . rand(10, 99);
        }
    }
    
    // 1. Insert into students table
    $stmt = $conn->prepare("
        INSERT INTO students (
            form_no, class_sought, class_roll_no, id_card_no, medium_of_instruction,
            applicant_name_english, applicant_name_hindi, gender, date_of_birth, 
            category, applicant_photo_path, blood_group, hobbies_interests,
            document_list, institution_last_attended, in_service
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Create variables for binding
    $form_no = $_POST['form_no'];
    $class_sought = $_POST['class_sought'] ?? null;
    $id_card_no = $_POST['id_card_no'] ?? null;
    $medium_of_instruction = $_POST['medium_of_instruction'] ?? 'English';
    $applicant_name_english = $_POST['applicant_name_english'] ?? '';
    $applicant_name_hindi =sanitizeUnicodeText($_POST['applicant_name_hindi']) ?? null;
    $gender = $_POST['gender'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $category = $_POST['category'] ?? 'General';
    $blood_group = $_POST['blood_group'] ?? null;
    $hobbies_interests = $_POST['hobbies_interests'] ?? null;
    $document_list = $_POST['document_list'] ?? null;
    $institution_last_attended = $_POST['institution_last_attended'] ?? null;
    $in_service = $_POST['in_service'] ?? 'No';
    
    $stmt->bind_param(
        "ssssssssssssssss",
        $form_no,
        $class_sought,
        $class_roll_no,
        $id_card_no,
        $medium_of_instruction,
        $applicant_name_english,
        $applicant_name_hindi,
        $gender,
        $date_of_birth,
        $category,
        $photoPath,
        $blood_group,
        $hobbies_interests,
        $document_list,
        $institution_last_attended,
        $in_service
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to save student data: " . $stmt->error);
    }
    
    $studentId = $conn->insert_id;
    $stmt->close();

    // 3. Insert into family_details
    $stmt = $conn->prepare("
        INSERT INTO family_details (
            student_id, father_name_english, father_name_hindi, father_occupation,
            mother_name_english, mother_name_hindi, mother_occupation,
            guardian_name_english, guardian_name_hindi, guardian_occupation, guardian_relation
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Create variables for family details binding
    $father_name_english = $_POST['father_name_english'] ?? '';
    $father_name_hindi =sanitizeUnicodeText($_POST['father_name_hindi']) ?? null;
    $father_occupation = $_POST['father_occupation'] ?? null;
    $mother_name_english = $_POST['mother_name_english'] ?? '';
    $mother_name_hindi = sanitizeUnicodeText( $_POST['mother_name_hindi'] )?? null;
    $mother_occupation = $_POST['mother_occupation'] ?? null;
    $guardian_name_english = $_POST['guardian_name_english'] ?? null;
    $guardian_name_hindi = sanitizeUnicodeText($_POST['guardian_name_hindi']) ?? null;
    $guardian_occupation = $_POST['guardian_occupation'] ?? null;
    $guardian_relation = $_POST['guardian_relation'] ?? null;
    
    $stmt->bind_param(
        "issssssssss",
        $studentId,
        $father_name_english,
        $father_name_hindi,
        $father_occupation,
        $mother_name_english,
        $mother_name_hindi,
        $mother_occupation,
        $guardian_name_english,
        $guardian_name_hindi,
        $guardian_occupation,
        $guardian_relation
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to save family details: " . $stmt->error);
    }
    $stmt->close();

    // 4. Insert into contact_details
    $stmt = $conn->prepare("
        INSERT INTO contact_details (
            student_id, permanent_address, local_address, pincode,
            mobile_number, whatsapp_number, email, aadhar_number, is_same_address
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Create variables for contact details binding
    $isSameAddress = isset($_POST['is_same_address']) ? 1 : 0;
    $permanent_address = $_POST['permanent_address'] ?? '';
    $localAddress = $isSameAddress ? $permanent_address : ($_POST['local_address'] ?? '');
    $pincode = $_POST['pincode'] ?? '';
    $mobile_number = $_POST['mobile_number'] ?? '';
    $whatsapp_number = $_POST['whatsapp_number'] ?? null;
    $email = $_POST['email'] ?? null;
    $aadhar_number = $_POST['aadhar_number'] ?? null;
    
    $stmt->bind_param(
        "isssssssi",
        $studentId,
        $permanent_address,
        $localAddress,
        $pincode,
        $mobile_number,
        $whatsapp_number,
        $email,
        $aadhar_number,
        $isSameAddress
    );
    
    if (!$stmt->execute()) {
        throw new Exception("Failed to save contact details: " . $stmt->error);
    }
    $stmt->close();

    // 5. Insert educational qualifications
    if (!empty($_POST['marks']) && is_array($_POST['marks'])) {
        $stmt = $conn->prepare("
            INSERT INTO educational_qualifications (
                student_id, exam_type, roll_no, year, university,
                max_marks, marks_obtained, percentage
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        foreach ($_POST['marks'] as $mark) {
            if (!empty($mark['exam_type']) && !empty($mark['year'])) {
                $examType = $mark['exam_type'] ?? '';
                $rollNo = $mark['roll_no'] ?? '';
                $year = $mark['year'] ?? 0;
                $university = $mark['university'] ?? '';
                $maxMarks = $mark['max_marks'] ?? 0;
                $marksObtained = $mark['marks_obtained'] ?? 0;
                $percentage = $mark['percentage'] ?? 0;
                $stmt->bind_param(
                    "issisddd",
                    $studentId,
                    $examType,
                    $rollNo,
                    $year,
                    $university,
                    $maxMarks,
                    $marksObtained,
                    $percentage
                );
                if (!$stmt->execute()) {
                    throw new Exception("Failed to save educational qualification: " . $stmt->error);
                }
            }
        }
        $stmt->close();
    }

    // 8. Store enclosure/document names (no file upload)
    $enclosures = [];
    for ($i = 1; $i <= 4; $i++) {
        $enc = trim($_POST['enclosure' . $i] ?? '');
        if ($enc !== '') {
            $enclosures[] = $enc;
        }
    }
    if (!empty($enclosures)) {
        $stmt = $conn->prepare("INSERT INTO documents (student_id, document_name) VALUES (?, ?)");
        foreach ($enclosures as $docName) {
            $stmt->bind_param("is", $studentId, $docName);
            if (!$stmt->execute()) {
                throw new Exception("Failed to save document record: " . $stmt->error);
            }
        }
        $stmt->close();
    }

    // 6. Insert into office_use with default values
    $stmt = $conn->prepare("
        INSERT INTO office_use (student_id, admission_status)
        VALUES (?, 'Pending')
    ");
    $stmt->bind_param("i", $studentId);
    if (!$stmt->execute()) {
        throw new Exception("Failed to initialize office use record: " . $stmt->error);
    }
    $stmt->close();

    // 7. Insert into application_status
    $stmt = $conn->prepare("
        INSERT INTO application_status (student_id, current_status)
        VALUES (?, 'Submitted')
    ");
    $stmt->bind_param("i", $studentId);
    $options = new Options();
    $options->set('defaultFont', 'NotoSansDevanagari');
    $options->set('isRemoteEnabled', true);
    $options->set('isHtml5ParserEnabled', true);
    $options->set('isPhpEnabled', true);
    
    // Set font directory
    $fontDir = $_SERVER['DOCUMENT_ROOT'] . '/sgn_law_clg/assets/fonts/static/';
    $options->set('fontDir', $fontDir);
    $options->set('fontCache', $fontDir . 'cache/');
    
    $dompdf = new Dompdf($options);
    
    // Prepare the HTML with proper font configuration
    $html = '
    <!DOCTYPE html>
    <html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <style>
           @font-face {
    font-family: "NotoSansDevanagari";
    src: url("file://" . __DIR__ . "/../assets/fonts/NotoSansDevanagari-Regular.ttf") format("truetype");
    font-weight: normal;
    font-style: normal;
}
            body {
                font-family: "NotoSansDevanagari", Arial, sans-serif;
                line-height: 1.6;
                padding: 20px;
            }
            .hindi {
                font-family: "NotoSansDevanagari", Arial, sans-serif;
                direction: ltr;
                unicode-bidi: bidi-override;
            }
            .header {
                text-align: center;
                margin-bottom: 20px;
            }
            .section {
                margin-bottom: 25px;
            }
            .field-row {
                margin-bottom: 10px;
                display: flex;
            }
            .field-label {
                font-weight: bold;
                width: 200px;
            }
            .field-value {
                flex: 1;
            }
        </style>
    </head>
    <body>
        <div class="header">
            <h1>Application Form</h1>
            <p>Form No: ' . htmlspecialchars($form_no) . '</p>
        </div>
        
        <div class="section">
            <h2>Personal Information</h2>
            <div class="field-row">
                <div class="field-label">Name (English):</div>
                <div class="field-value">' . htmlspecialchars($applicant_name_english) . '</div>
            </div>
            <div class="field-row">
                <div class="field-label">Name (Hindi):</div>
                <div class="field-value hindi">' . htmlspecialchars($applicant_name_hindi) . '</div>
            </div>
            <div class="field-row">
                <div class="field-label">Date of Birth:</div>
                <div class="field-value">' . htmlspecialchars($date_of_birth) . '</div>
            </div>
            <div class="field-row">
                <div class="field-label">Gender:</div>
                <div class="field-value">' . htmlspecialchars($gender) . '</div>
            </div>
        </div>
        
        <div class="section">
            <h2>Contact Information</h2>
            <div class="field-row">
                <div class="field-label">Mobile Number:</div>
                <div class="field-value">' . htmlspecialchars($mobile_number) . '</div>
            </div>
            <div class="field-row">
                <div class="field-label">Email:</div>
                <div class="field-value">' . htmlspecialchars($email) . '</div>
            </div>
            <div class="field-row">
                <div class="field-label">Address:</div>
                <div class="field-value">' . nl2br(htmlspecialchars($permanent_address)) . '</div>
            </div>
        </div>
    </body>
    </html>';
    
    // Set encoding and load HTML
    $dompdf->loadHtml($html, 'UTF-8');
    $dompdf->setPaper('A4', 'portrait');
    
    // Render the PDF
    $dompdf->render();
    
    // Create directory if it doesn't exist
    $pdfDir = "../uploads/forms/";
    if (!file_exists($pdfDir)) {
        mkdir($pdfDir, 0777, true);
    }
    
    // Save PDF to server
    $pdfPath = $pdfDir . $studentId . '_application.pdf';
    file_put_contents($pdfPath, $dompdf->output());

    // 10. Commit transaction
    $conn->commit();
    
    echo json_encode([
        'success' => true,
        'message' => 'Application submitted successfully!',
        'form_no' => $_POST['form_no'],
        'student_id' => $studentId
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Error: ' . $e->getMessage()
    ]);
}

$conn->close();