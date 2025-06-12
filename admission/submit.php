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

    // --- PDF GENERATION: Use the same format as user_pdf.php ---
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

    $photoPath = '';
    if (!empty($data['photo'])) {
        $photoFile = $_SERVER['DOCUMENT_ROOT'] . '/sgn-girl-admission/uploads/photos/' . $data['photo'];
        if (file_exists($photoFile)) {
            $type = pathinfo($photoFile, PATHINFO_EXTENSION);
            $imageData = file_get_contents($photoFile);
            $photoPath = 'data:image/' . $type . ';base64,' . base64_encode($imageData);
        }
    }

    $primary = '#1a237e';
    $accent = '#fbc02d';

    $html = '
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; margin: 0; padding: 0; background: #f7f7fa; }
        .header-table { width: 100%; border-collapse: collapse; background: ' . $primary . '; color: #fff; border-bottom: 4px solid ' . $accent . '; }
        .header-table td { vertical-align: top; padding: 18px 20px; }
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

    <table class="header-table">
        <tr>
            <td style="width:70%;">
                ' . ($base64 ? '<img src="' . $base64 . '" alt="College Logo" class="logo">' : '') . '
                <div class="college-info">
                    <div class="college-name">SRI GURU NANAK GIRLS P.G. COLLEGE</div>
                    <div>Affiliated to Maharaja Ganga Singh University, Bikaner (Raj.)</div>
                    <div class="form-title">ADMISSION FORM</div>
                </div>
            </td>
            <td style="width:30%; text-align:right;">
                <div class="photo-box" style="margin-left:auto;">
                    ' . ($photoPath ? '<img src="' . $photoPath . '" alt="Applicant Photo">' : '<span style="color:#888;font-size:11px;">No Photo</span>') . '
                </div>
            </td>
        </tr>
    </table>
    ';

    // Admission/Class Details
    $html .= '<table class="info-table" style="margin-bottom:10px;">';
    if (!empty($data['form_no']) || !empty($data['class'])) {
        $html .= '<tr>';
        if (!empty($data['form_no'])) {
            $html .= '<td class="label">Form No</td><td>' . htmlspecialchars($data['form_no']) . '</td>';
        }
        if (!empty($data['class'])) {
            $html .= '<td class="label">Class</td><td>' . htmlspecialchars($data['class']) . '</td>';
        }
        $html .= '</tr>';
    }
    if (!empty($data['part']) || !empty($data['medium'])) {
        $html .= '<tr>';
        if (!empty($data['part'])) {
            $html .= '<td class="label">Part</td><td>' . htmlspecialchars($data['part']) . '</td>';
        }
        if (!empty($data['medium'])) {
            $html .= '<td class="label">Medium</td><td>' . htmlspecialchars($data['medium']) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Admission Details
    if (!empty($data['faculty']) || !empty($data['category'])) {
        $html .= '<div class="section-header">ADMISSION DETAILS</div>
        <table class="info-table"><tr>';
        if (!empty($data['faculty'])) {
            $html .= '<td class="label">Faculty</td><td>' . htmlspecialchars($data['faculty']) . '</td>';
        }
        if (!empty($data['category'])) {
            $html .= '<td class="label">Category</td><td>' . htmlspecialchars($data['category']) . '</td>';
        }
        $html .= '</tr></table>';
    }

    // Personal Details
    if (!empty($data['applicant_name']) || !empty($data['hindi_name']) || !empty($data['dob']) ||
        !empty($data['father_name']) || !empty($data['f_occupation']) ||
        !empty($data['mother_name']) || !empty($data['m_occupation']) ||
        !empty($data['aadhar']) || !empty($data['phone']) || !empty($data['email'])) {

        $html .= '<div class="section-header">PERSONAL DETAILS</div><table class="info-table">';

        if (!empty($data['applicant_name'])) {
            $html .= '<tr><td class="label">Name (English)</td><td colspan="3">' . htmlspecialchars($data['applicant_name']) . '</td></tr>';
        }
        if (!empty($data['hindi_name']) || !empty($data['dob'])) {
            $html .= '<tr>';
            if (!empty($data['hindi_name'])) {
                $html .= '<td class="label">Name (Hindi)</td><td>' . htmlspecialchars($data['hindi_name']) . '</td>';
            }
            if (!empty($data['dob'])) {
                $html .= '<td class="label">Date of Birth</td><td>' . htmlspecialchars($data['dob']) . '</td>';
            }
            $html .= '</tr>';
        }
        if (!empty($data['father_name']) || !empty($data['f_occupation'])) {
            $html .= '<tr>';
            if (!empty($data['father_name'])) {
                $html .= '<td class="label">Father\'s Name</td><td>' . htmlspecialchars($data['father_name']) . '</td>';
            }
            if (!empty($data['f_occupation'])) {
                $html .= '<td class="label">Occupation</td><td>' . htmlspecialchars($data['f_occupation']) . '</td>';
            }
            $html .= '</tr>';
        }
        if (!empty($data['mother_name']) || !empty($data['m_occupation'])) {
            $html .= '<tr>';
            if (!empty($data['mother_name'])) {
                $html .= '<td class="label">Mother\'s Name</td><td>' . htmlspecialchars($data['mother_name']) . '</td>';
            }
            if (!empty($data['m_occupation'])) {
                $html .= '<td class="label">Occupation</td><td>' . htmlspecialchars($data['m_occupation']) . '</td>';
            }
            $html .= '</tr>';
        }
        if (!empty($data['aadhar']) || !empty($data['phone'])) {
            $html .= '<tr>';
            if (!empty($data['aadhar'])) {
                $html .= '<td class="label">Aadhar Number</td><td>' . htmlspecialchars($data['aadhar']) . '</td>';
            }
            if (!empty($data['phone'])) {
                $html .= '<td class="label">Phone</td><td>' . htmlspecialchars($data['phone']) . '</td>';
            }
            $html .= '</tr>';
        }
        if (!empty($data['email'])) {
            $html .= '<tr><td class="label">Email</td><td colspan="3">' . htmlspecialchars($data['email']) . '</td></tr>';
        }
        $html .= '</table>';
    }

    // Address Details
    if (!empty($data['perm_address']) || !empty($data['local_address'])) {
        $html .= '<div class="section-header">ADDRESS DETAILS</div><table class="info-table"><tr>';
        if (!empty($data['perm_address'])) {
            $html .= '<td class="label">Permanent Address</td><td>' . nl2br(htmlspecialchars($data['perm_address'])) . '</td>';
        }
        if (!empty($data['local_address'])) {
            $html .= '<td class="label">Local Address</td><td>' . nl2br(htmlspecialchars($data['local_address'])) . '</td>';
        }
        $html .= '</tr></table>';
    }

    // Subjects Offered
    if (!empty($data['subject1']) || !empty($data['subject2']) || !empty($data['subject3'])) {
        $html .= '<div class="section-header">SUBJECTS OFFERED</div>
        <table class="info-table">';
        if (!empty($data['subject1']) || !empty($data['subject2'])) {
            $html .= '<tr>';
            if (!empty($data['subject1'])) {
                $html .= '<td class="label">Subject 1</td><td>' . htmlspecialchars($data['subject1']) . '</td>';
            }
            if (!empty($data['subject2'])) {
                $html .= '<td class="label">Subject 2</td><td>' . htmlspecialchars($data['subject2']) . '</td>';
            }
            $html .= '</tr>';
        }
        if (!empty($data['subject3'])) {
            $html .= '<tr><td class="label">Subject 3</td><td colspan="3">' . htmlspecialchars($data['subject3']) . '</td></tr>';
        }
        $html .= '</table>';
    }

    // Compulsory Subjects
    $compulsory = [];
    if (!empty($data['comp_computer'])) $compulsory[] = 'Elementary Computer';
    if (!empty($data['comp_env'])) $compulsory[] = 'Environmental Studies';
    if (!empty($data['comp_english'])) $compulsory[] = 'General English';
    if (!empty($data['comp_hindi'])) $compulsory[] = 'General Hindi';
    if (count($compulsory) > 0) {
        $html .= '<div class="section-header">COMPULSORY SUBJECTS</div>
        <table class="info-table">
            <tr>
                <td class="label">Selected Subjects</td>
                <td colspan="3">' . implode(', ', $compulsory) . '</td>
            </tr>
        </table>';
    }

    // Previous Examination
    if (!empty($data['prev_course_title']) || !empty($data['prev_year']) || !empty($data['prev_board']) ||
        !empty($data['prev_subjects']) || !empty($data['prev_percentage']) || !empty($data['prev_division'])) {
        $html .= '<div class="section-header">PREVIOUS EXAMINATION</div>
        <table class="exam-table">
            <thead>
                <tr>
                    ' . (!empty($data['prev_course_title']) ? '<th>Course</th>' : '') . '
                    ' . (!empty($data['prev_year']) ? '<th>Year</th>' : '') . '
                    ' . (!empty($data['prev_board']) ? '<th>Board</th>' : '') . '
                    ' . (!empty($data['prev_subjects']) ? '<th>Subjects</th>' : '') . '
                    ' . (!empty($data['prev_percentage']) ? '<th>Percentage</th>' : '') . '
                    ' . (!empty($data['prev_division']) ? '<th>Division</th>' : '') . '
                </tr>
            </thead>
            <tbody>
                <tr>
                    ' . (!empty($data['prev_course_title']) ? '<td>' . htmlspecialchars($data['prev_course_title']) . '</td>' : '') . '
                    ' . (!empty($data['prev_year']) ? '<td>' . htmlspecialchars($data['prev_year']) . '</td>' : '') . '
                    ' . (!empty($data['prev_board']) ? '<td>' . htmlspecialchars($data['prev_board']) . '</td>' : '') . '
                    ' . (!empty($data['prev_subjects']) ? '<td>' . htmlspecialchars($data['prev_subjects']) . '</td>' : '') . '
                    ' . (!empty($data['prev_percentage']) ? '<td>' . htmlspecialchars($data['prev_percentage']) . '</td>' : '') . '
                    ' . (!empty($data['prev_division']) ? '<td>' . htmlspecialchars($data['prev_division']) . '</td>' : '') . '
                </tr>
            </tbody>
        </table>';
    }

    // Institution Last Attended
    if (!empty($data['institution_name']) || !empty($data['institution_address']) || !empty($data['institution_contact']) || !empty($data['university_enrollment'])) {
        $html .= '<div class="section-header">INSTITUTION LAST ATTENDED</div>
        <table class="info-table"><tr>';
        if (!empty($data['institution_name'])) {
            $html .= '<td class="label">Institution Name</td><td>' . htmlspecialchars($data['institution_name']) . '</td>';
        }
        if (!empty($data['institution_address'])) {
            $html .= '<td class="label">Address</td><td>' . htmlspecialchars($data['institution_address']) . '</td>';
        }
        $html .= '</tr><tr>';
        if (!empty($data['institution_contact'])) {
            $html .= '<td class="label">Contact</td><td>' . htmlspecialchars($data['institution_contact']) . '</td>';
        }
        if (!empty($data['university_enrollment'])) {
            $html .= '<td class="label">University Enrollment</td><td>' . htmlspecialchars($data['university_enrollment']) . '</td>';
        }
        $html .= '</tr></table>';
    }

    // Extra-Curricular Activities
    if (!empty($data['nss_offered']) || !empty($data['other_activities'])) {
        $html .= '<div class="section-header">EXTRA-CURRICULAR ACTIVITIES</div>
        <table class="info-table"><tr>';
        if (!empty($data['nss_offered'])) {
            $html .= '<td class="label">NSS Offered</td><td>' . htmlspecialchars($data['nss_offered']) . '</td>';
        }
        if (!empty($data['other_activities'])) {
            $html .= '<td class="label">Other Activities</td><td>' . htmlspecialchars($data['other_activities']) . '</td>';
        }
        $html .= '</tr></table>';
    }

    $html .= '<div style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
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

    // Send Email (same PDF format as download)
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