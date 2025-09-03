<?php
date_default_timezone_set('Asia/Kolkata');
require(__DIR__ . '/../includes/config.php');
require './vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-type:application/json');

// Start transaction
$conn->begin_transaction();

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
    
    // 3. Insert into students table
    $stmt = $conn->prepare("
        INSERT INTO students (
            form_no, class_sought, class_roll_no, id_card_no, medium_of_instruction,
            applicant_name_english, applicant_name_hindi, gender, date_of_birth, 
            category, applicant_photo_path, blood_group, hobbies_interests
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    // Create variables for binding
    $form_no = $_POST['form_no'];
    $class_sought = $_POST['class_sought'] ?? null;
    $id_card_no = $_POST['id_card_no'] ?? null;
    $medium_of_instruction = $_POST['medium_of_instruction'] ?? 'English';
    $applicant_name_english = $_POST['applicant_name_english'] ?? '';
    $applicant_name_hindi = $_POST['applicant_name_hindi'] ?? null;
    $gender = $_POST['gender'] ?? '';
    $date_of_birth = $_POST['date_of_birth'] ?? null;
    $category = $_POST['category'] ?? 'General';
    $blood_group = $_POST['blood_group'] ?? null;
    $hobbies_interests = $_POST['hobbies_interests'] ?? null;
    
    $stmt->bind_param(
        "sssssssssssss",
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
        $hobbies_interests
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
    $father_name_hindi = $_POST['father_name_hindi'] ?? null;
    $father_occupation = $_POST['father_occupation'] ?? null;
    $mother_name_english = $_POST['mother_name_english'] ?? '';
    $mother_name_hindi = $_POST['mother_name_hindi'] ?? null;
    $mother_occupation = $_POST['mother_occupation'] ?? null;
    $guardian_name_english = $_POST['guardian_name_english'] ?? null;
    $guardian_name_hindi = $_POST['guardian_name_hindi'] ?? null;
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

    // 5. Insert educational qualifications if provided
    if (!empty($_POST['qualifications'])) {
        $stmt = $conn->prepare("
            INSERT INTO educational_qualifications (
                student_id, qualification_type, board_university, institution_name,
                passing_year, percentage, division, subject
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        foreach ($_POST['qualifications'] as $qual) {
            $stmt->bind_param(
                "issssdss",
                $studentId,
                $qual['type'],
                $qual['board'],
                $qual['institution'],
                $qual['year'],
                $qual['percentage'] ?? null,
                $qual['division'] ?? null,
                $qual['subject'] ?? null
            );
            
            if (!$stmt->execute()) {
                throw new Exception("Failed to save educational qualification: " . $stmt->error);
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
    if (!$stmt->execute()) {
        throw new Exception("Failed to update application status: " . $stmt->error);
    }
    $stmt->close();

    // 8. Handle document uploads if any
    if (!empty($_FILES['documents'])) {
        $stmt = $conn->prepare("
            INSERT INTO documents (student_id, document_type, document_path)
            VALUES (?, ?, ?)
        ");
        
        foreach ($_FILES['documents']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['documents']['error'][$key] === UPLOAD_ERR_OK) {
                $targetDir = "../uploads/documents/";
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }
                
                $fileName = uniqid('doc_', true) . '_' . basename($_FILES['documents']['name'][$key]);
                $targetFile = $targetDir . $fileName;
                
                if (move_uploaded_file($tmpName, $targetFile)) {
                    $docType = $_FILES['documents']['type'][$key];
                    $stmt->bind_param("iss", $studentId, $docType, $fileName);
                    
                    if (!$stmt->execute()) {
                        throw new Exception("Failed to save document record: " . $stmt->error);
                    }
                }
            }
        }
        $stmt->close();
    }

    // 9. Generate PDF (simplified example)
    $options = new Options();
    $options->set('defaultFont', 'Arial');
    $dompdf = new Dompdf($options);
    
    $html = "
        <h1>Application Form</h1>
        <p>Form No: {$_POST['form_no']}</p>
        <p>Name: {$_POST['applicant_name_english']}</p>
        <!-- Add more fields as needed -->
    ";
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    // Save PDF to server
    $pdfPath = "../uploads/forms/{$studentId}_application.pdf";
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