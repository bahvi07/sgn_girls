<?php
require(__DIR__ . '/../includes/config.php');
require_once 'C:\xampp\vendor\autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-type:application/json');

// Helper function for safe file upload
function uploadPhoto($file) {
    $targetDir = "../uploads/photos/";
    if (!is_dir($targetDir)) {
        mkdir($targetDir, 0777, true);
    }
    $fileName = uniqid('photo_', true) . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
    $targetFile = $targetDir . $fileName;
    if (move_uploaded_file($file['tmp_name'], $targetFile)) {
        return $fileName;
    }
    return '';
}

// Collect POST data (same as your working code)
$form_no = $_POST['form_no'];
$class = $_POST['class'];
$part = $_POST['part'];
$medium = $_POST['medium'];
$faculty = $_POST['faculty'];
$applicant_name = $_POST['applicant_name'] ?? '';
$hindi_name = $_POST['hindi_name'] ?? '';
$father_name = $_POST['father_name'] ?? '';
$f_occupation = $_POST['f_occupation'] ?? '';
$mother_name = $_POST['mother_name'] ?? '';
$m_occupation = $_POST['m_occupation'] ?? '';
$dob = $_POST['dob'] ?? '';
$category = $_POST['category'] ?? '';
$aadhar = $_POST['aadhar'] ?? '';
$perm_address = $_POST['perm_address'] ?? '';
$same_address = isset($_POST['same_address']) ? 1 : 0; // ADDED THIS
$local_address = $_POST['local_address'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$subject1 = $_POST['subject1'] ?? '';
$subject2 = $_POST['subject2'] ?? '';
$subject3 = $_POST['subject3'] ?? '';
$compulsory_computer = isset($_POST['comp_computer']) ? 1 : 0;
$compulsory_env_studies = isset($_POST['comp_env']) ? 1 : 0;
$compulsory_english = isset($_POST['comp_english']) ? 1 : 0;
$compulsory_hindi = isset($_POST['comp_hindi']) ? 1 : 0;
$prev_course_title = $_POST['prev_course_title'] ?? '';
$prev_year = $_POST['prev_year'] ?? '';
$prev_board = $_POST['prev_board'] ?? '';
$prev_subjects = $_POST['prev_subjects'] ?? '';
$prev_percentage = $_POST['prev_percentage'] ?? '';
$prev_division = $_POST['prev_division'] ?? '';
$institution_name = $_POST['institution_name'] ?? '';
$institution_address = $_POST['institution_address'] ?? '';
$institution_contact = $_POST['institution_contact'] ?? '';
$university_enrollment = $_POST['university_enrollment'] ?? '';
$nss_offered = $_POST['nss_offered'] ?? '';
$other_activities = $_POST['other_activities'] ?? '';
$declaration = isset($_POST['declaration']) ? 1 : 0;

// Handle file upload
$photo = '';
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo = uploadPhoto($_FILES['photo']);
}

// FIXED: Added same_address to your working INSERT
$stmt = $conn->prepare("INSERT INTO admissions (
    form_no, class, part, medium, faculty,
    applicant_name, hindi_name, father_name, f_occupation,
    mother_name, m_occupation, dob, category, aadhar, photo, perm_address,
    same_address, local_address, phone, email, subject1, subject2, subject3,
    comp_computer, comp_env, comp_english, comp_hindi,
    prev_course_title, prev_year, prev_board, prev_subjects,
    prev_percentage, prev_division, institution_name, institution_address,
    institution_contact, university_enrollment, nss_offered, other_activities, declaration
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// FIXED: Added 'i' for same_address to your working bind_param
$stmt->bind_param(
    "ssssssssssssssssissssssiiiissssssssssssi", 
    $form_no, $class, $part, $medium, $faculty,
    $applicant_name, $hindi_name, $father_name, $f_occupation,
    $mother_name, $m_occupation, $dob, $category, $aadhar, $photo, $perm_address,
    $same_address, $local_address, $phone, $email, $subject1, $subject2, $subject3,
    $compulsory_computer, $compulsory_env_studies, $compulsory_english, $compulsory_hindi,
    $prev_course_title, $prev_year, $prev_board, $prev_subjects,
    $prev_percentage, $prev_division, $institution_name, $institution_address,
    $institution_contact, $university_enrollment, $nss_offered,
    $other_activities, $declaration
);

if ($stmt->execute()) {
    // Fetch the inserted data for PDF generation
    $stmt2 = $conn->prepare("SELECT * FROM admissions WHERE form_no = ?");
    $stmt2->bind_param("s", $form_no);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $data = $result->fetch_assoc();
    $stmt2->close();

    // Generate PDF
    $options = new Options();
    $options->set('defaultFont', 'Helvetica');
    $options->set('isRemoteEnabled', true);
    $dompdf = new Dompdf($options);

    $logoPath = $_SERVER['DOCUMENT_ROOT'] . '/sgn-girl-admission/assets/images/logo.png';
    $base64 = '';
    if (file_exists($logoPath)) {
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $imageData = file_get_contents($logoPath);
        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
    }

    // Get applicant photo if exists
    $photoPath = '';
    if (!empty($data['photo'])) {
        $photoFile = $_SERVER['DOCUMENT_ROOT'] . '/sgn-girl-admission/uploads/photos/' . $data['photo'];
        if (file_exists($photoFile)) {
            $type = pathinfo($photoFile, PATHINFO_EXTENSION);
            $imageData = file_get_contents($photoFile);
            $photoPath = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        }
    }

    // THEME COLORS
    $primary = '#1a237e'; // Deep blue
    $accent = '#fbc02d';  // Yellow accent

    // Build info table rows only for non-empty fields
    $infoRows = '';
    if (!empty($data['form_no']) || !empty($data['class'])) {
        $infoRows .= '<tr>';
        if (!empty($data['form_no'])) {
            $infoRows .= '<td class="label">Form No</td><td>' . htmlspecialchars($data['form_no']) . '</td>';
        }
        if (!empty($data['class'])) {
            $infoRows .= '<td class="label">Class</td><td>' . htmlspecialchars($data['class']) . '</td>';
        }
        $infoRows .= '</tr>';
    }
    if (!empty($data['faculty']) || !empty($data['category'])) {
        $infoRows .= '<tr>';
        if (!empty($data['faculty'])) {
            $infoRows .= '<td class="label">Faculty</td><td>' . htmlspecialchars($data['faculty']) . '</td>';
        }
        if (!empty($data['category'])) {
            $infoRows .= '<td class="label">Category</td><td>' . htmlspecialchars($data['category']) . '</td>';
        }
        $infoRows .= '</tr>';
    }
    if (!empty($data['applicant_name'])) {
        $infoRows .= '<tr><td class="label">Student Name</td><td colspan="3">' . htmlspecialchars($data['applicant_name']) . '</td></tr>';
    }
    if (!empty($data['father_name']) || !empty($data['mother_name'])) {
        $infoRows .= '<tr>';
        if (!empty($data['father_name'])) {
            $infoRows .= '<td class="label">Father Name</td><td>' . htmlspecialchars($data['father_name']) . '</td>';
        }
        if (!empty($data['mother_name'])) {
            $infoRows .= '<td class="label">Mother Name</td><td>' . htmlspecialchars($data['mother_name']) . '</td>';
        }
        $infoRows .= '</tr>';
    }
    if (!empty($data['dob']) || !empty($data['phone'])) {
        $infoRows .= '<tr>';
        if (!empty($data['dob'])) {
            $infoRows .= '<td class="label">DOB</td><td>' . htmlspecialchars($data['dob']) . '</td>';
        }
        if (!empty($data['phone'])) {
            $infoRows .= '<td class="label">Phone</td><td>' . htmlspecialchars($data['phone']) . '</td>';
        }
        $infoRows .= '</tr>';
    }
    if (!empty($data['perm_address'])) {
        $infoRows .= '<tr><td class="label">Address</td><td colspan="3">' . htmlspecialchars($data['perm_address']) . '</td></tr>';
    }

    $html = '
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 0; padding: 0; background: #f7f7fa; }
        .header { background: ' . $primary . '; color: #fff; padding: 20px 30px 20px 30px; border-bottom: 4px solid ' . $accent . '; display: flex; align-items: center; justify-content: space-between; }
        .header-left { display: flex; align-items: center; }
        .logo { height: 60px; margin-right: 18px; }
        .college-info { }
        .college-name { font-size: 20px; font-weight: bold; margin-bottom: 2px; letter-spacing: 1px; }
        .form-title { font-size: 16px; font-weight: bold; margin-top: 8px; color: ' . $accent . '; }
        .photo-box { width: 90px; height: 110px; border: 2px solid ' . $accent . '; border-radius: 8px; overflow: hidden; background: #fff; display: flex; align-items: center; justify-content: center; }
        .photo-box img { width: 100%; height: 100%; object-fit: cover; }
        .section-header { background: ' . $accent . '; color: #222; padding: 8px 12px; font-weight: bold; font-size: 14px; margin: 20px 0 10px 0; border-radius: 4px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; background: #fff; }
        .info-table td { padding: 7px 8px; border: 1px solid #e0e0e0; vertical-align: top; }
        .info-table .label { font-weight: bold; background: #f5f5f5; width: 30%; }
        .exam-table { width: 100%; border-collapse: collapse; margin: 10px 0; background: #fff; }
        .exam-table th, .exam-table td { border: 1px solid #bdbdbd; padding: 6px; text-align: center; }
        .exam-table th { background: #e3e3e3; font-weight: bold; }
    </style>

    <div class="header">
        <div class="header-left">
            ' . ($base64 ? '<img src="' . $base64 . '" alt="College Logo" class="logo">' : '') . '
            <div class="college-info">
                <div class="college-name">SRI GURU NANAK GIRLS P.G. COLLEGE</div>
                <div>Affiliated to Maharaja Ganga Singh University, Bikaner (Raj.)</div>
                <div class="form-title">ADMISSION FORM</div>
            </div>
        </div>
        <div class="photo-box">' .
            ($photoPath ? '<img src="' . $photoPath . '" alt="Applicant Photo">' : '<span style="color:#888;font-size:11px;">No Photo</span>') .
        '</div>
    </div>

    <table class="info-table">'
        . $infoRows .
    '</table>

    <div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        Form submitted on: ' . date('Y-m-d H:i:s') . '
    </div>';

    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $pdfOutput = $dompdf->output();

    // Save PDF to server
    $uploadsDir = __DIR__ . "/../uploads/";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0777, true);
    }
    $pdfFile = $uploadsDir . "admission_{$form_no}.pdf";
    file_put_contents($pdfFile, $pdfOutput);

    // Send Email
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'help40617@gmail.com';
        $mail->Password = 'lrmuluhlzrohwvoq';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom('help40617@gmail.com', 'SGN Girls College');
        $mail->addAddress('bhavishyakushwha123@gmail.com');
        
        if (!empty($data['email'])) {
            $mail->addAddress($data['email']);
        }

        $mail->isHTML(true);
        $mail->Subject = "Admission Form - {$form_no}";
        $mail->Body = "Admission form submitted for " . htmlspecialchars($data['applicant_name']);
        $mail->addStringAttachment($pdfOutput, "Admission_Form_{$form_no}.pdf");

        $mail->send();
        $emailMsg = "Email sent successfully!";
    } catch (Exception $e) {
        $emailMsg = "Email failed: " . $mail->ErrorInfo;
    }

    echo json_encode([
        'success' => true,
        'pdf_url' => "uploads/admission_{$form_no}.pdf",
        'message' => "Form submitted successfully! {$emailMsg}"
    ]);

} else {
    echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
?>