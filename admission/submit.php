<?php
date_default_timezone_set('Asia/Kolkata');

// Start output buffering to prevent 'headers already sent' errors
if (ob_get_level() == 0) {
    ob_start();
}

require(__DIR__ . '/../includes/config.php');
require './vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Set JSON header only if not already set
if (!headers_sent()) {
    header('Content-Type: application/json; charset=utf-8');
}
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
    $applicant_name_hindi =$_POST['applicant_name_hindi'] ?? null;
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
    $father_name_hindi =$_POST['father_name_hindi']?? null;
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
   
    
    // Clear any previous output
    if (ob_get_level() > 0) {
        ob_clean();
    }
    
    // Output the PDF

    // Clear output buffer and end buffering
    if (ob_get_level() > 0) {
        ob_end_clean();
    }

    // 10. Commit transaction
    $conn->commit();
    
    //  Sending mail to clg with form no and student basic details 

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'help40617@gmail.com';
        $mail->Password = 'udqrtfzamiluzkpz';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('help40617@gmail.com', 'SGN Law College');
        $mail->addAddress('help40617@gmail.com', 'Admission Office');
        
        // Add student's email if available
        $studentEmail = $_POST['email'] ?? '';
        if (!empty($studentEmail)) {
            $mail->addAddress($studentEmail);
        }

        $mail->isHTML(true);
        $mail->Subject = "Admission Form Submitted - {$form_no}";
        
        // Prepare student details from form data
        $studentName = htmlspecialchars(trim($_POST['applicant_name_english'] ?? 'N/A'));
        $fatherName = htmlspecialchars(trim($_POST['father_name_english'] ?? 'N/A'));
        $motherName = htmlspecialchars(trim($_POST['mother_name_english'] ?? 'N/A'));
        $course = htmlspecialchars($_POST['class_sought'] ?? 'N/A');
        $mobile = htmlspecialchars($_POST['mobile_number'] ?? 'N/A');
        $email = htmlspecialchars($studentEmail ?: 'Not provided');
        $gender = htmlspecialchars($_POST['gender'] ?? 'Not specified');
        $category = htmlspecialchars($_POST['category'] ?? 'Not specified');
        $dob = !empty($_POST['date_of_birth']) ? date('d/m/Y', strtotime($_POST['date_of_birth'])) : 'Not specified';
        $submissionDate = date('d/m/Y h:i A');
        
        // HTML Email Body
        $mail->Body = "
            <h2>New Admission Form Submitted</h2>
            <p><strong>Form Number:</strong> {$form_no}</p>
            <p><strong>Submission Date:</strong> {$submissionDate}</p>
            
            <h3>Student Details:</h3>
            <p><strong>Form Number:</strong> {$form_no}</p>
            <p><strong>Full Name:</strong> {$studentName}</p>
            <p><strong>Father's Name:</strong> {$fatherName}</p>
            <p><strong>Mother's Name:</strong> {$motherName}</p>
            <p><strong>Date of Birth:</strong> {$dob}</p>
            <p><strong>Gender:</strong> {$gender}</p>
            <p><strong>Category:</strong> {$category}</p>
            <p><strong>Course Applied:</strong> {$course}</p>
            <p><strong>Contact:</strong> {$mobile}</p>
            <p><strong>Email:</strong> {$email}</p>
            <p>This is an automated confirmation. Please contact for more details.</p>
            
            <p>Best regards,<br>
            Admission Department<br>
            SGN Law College</p>
        ";
        
        // Plain text version for non-HTML email clients
        $mail->AltBody = "
            New Admission Form Submitted
            ---------------------------
            
            Form Number: {$form_no}
            Submission Date: {$submissionDate}
            
            Student Details:
            - Full Name: {$studentName}
            - Father's Name: {$fatherName}
            - Course Applied: {$course}
            - Mobile: {$mobile}
            - Email: {$email}
            
            This is an automated confirmation. Please log in to the admin panel for more details.
            
            Best regards,
            Admission Department
            SGN Law College
        ";

        $mail->send();
        $emailMsg = "Email sent successfully!";
    } catch (Exception $e) {
        $emailMsg = "Email failed: " . $mail->ErrorInfo;
    }
   
// Return success response
echo json_encode([
    'success' => true,
    'message' => 'Application submitted successfully!',
    'form_no' => $form_no,
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