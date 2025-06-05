<?php
require(__DIR__ . '/../includes/config.php');
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

// Collect POST data (sanitize as needed)
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

// Prepare and execute
$stmt = $conn->prepare("INSERT INTO admissions (
    form_no, class, part, medium, faculty,
    applicant_name, hindi_name, father_name, f_occupation,
    mother_name, m_occupation, dob, category, aadhar, photo, perm_address,
    local_address, phone, email, subject1, subject2, subject3,
    comp_computer, comp_env, comp_english, comp_hindi,
    prev_course_title, prev_year, prev_board, prev_subjects,
    prev_percentage, prev_division, institution_name, institution_address,
    institution_contact, university_enrollment, nss_offered, other_activities, declaration
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

// Corrected bind_param: 34 strings (s) + 5 integers (i) = 39 total
$stmt->bind_param(
    "ssssssssssssssssssssssiiiissssssssssssi",  // 34 's' + 5 'i' = 39 total
    $form_no, $class, $part, $medium, $faculty,
    $applicant_name, $hindi_name, $father_name, $f_occupation,
    $mother_name, $m_occupation, $dob, $category, $aadhar, $photo, $perm_address,
    $local_address, $phone, $email, $subject1, $subject2, $subject3,
    $compulsory_computer, $compulsory_env_studies, $compulsory_english, $compulsory_hindi,
    $prev_course_title, $prev_year, $prev_board, $prev_subjects,
    $prev_percentage, $prev_division, $institution_name, $institution_address,
    $institution_contact, $university_enrollment, $nss_offered,
    $other_activities, $declaration
);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $stmt->error]);
}
$stmt->close();
$conn->close();
?>