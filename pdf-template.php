<?php
// Database connection
require_once __DIR__ . '/includes/config.php';

// Get form number from URL parameter
$form_no = $_GET['form_no'] ?? '';

if (empty($form_no)) {
    die('Form number is required');
}

// Fetch student data from database with all related information
$query = "
    SELECT 
        s.*,
        fd.father_name_english, fd.father_name_hindi, fd.father_occupation,
        fd.mother_name_english, fd.mother_name_hindi, fd.mother_occupation,
        fd.guardian_name_english, fd.guardian_name_hindi, fd.guardian_occupation, fd.guardian_relation,
        cd.permanent_address, cd.local_address, cd.pincode, cd.mobile_number,
        cd.whatsapp_number, cd.email, cd.aadhar_number, cd.is_same_address,
        au.current_status, au.status_changed_by, au.comments,
        ou.admission_status, ou.eligible_for_admission, ou.scrutinizer_name,
        ou.admission_date, ou.admission_incharge, ou.remarks
    FROM students s
    LEFT JOIN family_details fd ON s.student_id = fd.student_id
    LEFT JOIN contact_details cd ON s.student_id = cd.student_id
    LEFT JOIN application_status au ON s.student_id = au.student_id
    LEFT JOIN office_use ou ON s.student_id = ou.student_id
    WHERE s.form_no = ?
";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die('Database error: ' . $conn->error);
}

$stmt->bind_param('s', $form_no);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    die('No student found with form number: ' . htmlspecialchars($form_no));
}

// Fetch educational qualifications
$edu_query = "SELECT * FROM educational_qualifications WHERE student_id = ? ORDER BY year DESC";
$edu_stmt = $conn->prepare($edu_query);
$edu_stmt->bind_param('i', $student['student_id']);
$edu_stmt->execute();
$educations = $edu_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Store educations in student array
$student['educations'] = $educations;

// Fetch documents
$doc_query = "SELECT * FROM documents WHERE student_id = ?";
$doc_stmt = $conn->prepare($doc_query);
$doc_stmt->bind_param('i', $student['student_id']);
$doc_stmt->execute();
$documents = $doc_stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Store documents in student array
$student['documents'] = $documents;

// Set content type to HTML
header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
  <script src="assets/js/submit.js"></script>
    <link href="assets/css/pdf.css" rel="stylesheet">
</head>
<body>
    <div id="pdfjs" class="pdf-container">
        <!-- First Page -->
        <div class="page">
    <!-- Header -->
    <div class="header">
        <h2>Sri Guru Nanak Khalsa Law (P.G.) College</h2>
        <p>SRI GANGANAGAR - 335001 (RAJ.)</p>
        <h4>Application Form</h4>
        <p>Form No: <?= htmlspecialchars($student['form_no'] ?? '') ?></p>
        <!-- Photo Box -->
        <?php 
        if (!empty($student['applicant_photo_path'])) {
            $photoPath = __DIR__ . '/uploads/photos/' . $student['applicant_photo_path'];
            
            // Check if file exists and is readable
            if (file_exists($photoPath) && is_readable($photoPath)) {
                // Get image data and convert to base64
                $imageData = file_get_contents($photoPath);
                $imageInfo = getimagesizefromstring($imageData);
                $mimeType = $imageInfo ? $imageInfo['mime'] : 'image/png';
                $base64 = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                
                // Output the image directly in the HTML
                echo '<div class="photo-box">';
                echo '<img src="' . $base64 . '" alt="Applicant Photo" class="photo-img" style="max-width: 120px; max-height: 150px;">';
                echo '</div>';
            } else {
                echo '<!-- DEBUG: Photo exists but could not be read: ' . htmlspecialchars($photoPath) . ' -->';
            }
        } else {
            echo '<!-- DEBUG: No photo path provided in student data -->';
        }
        ?>
    </div>

    <!-- Class & Admission Details Section -->
    <div class="section">
        <div class="section-title">Admission Details</div>
        <table>
            <tr>
                <td style="width: 20%;"><strong>Form No:</strong></td>
                <td style="width: 30%;"><?= htmlspecialchars($student['form_no'] ?? '') ?></td>
                <td style="width: 20%;"><strong>Class Roll No:</strong></td>
                <td style="width: 30%;"><?= htmlspecialchars($student['class_roll_no'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>ID Card No:</strong></td>
                <td><?= htmlspecialchars($student['id_card_no'] ?? '') ?></td>
                <td><strong>Class Sought:</strong></td>
                <td><?= htmlspecialchars($student['class_sought'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>Medium:</strong></td>
                <td><?= htmlspecialchars($student['medium_of_instruction'] ?? '') ?></td>
                <td><strong>Category:</strong></td>
                <td><?= htmlspecialchars($student['category'] ?? '') ?></td>
            </tr>
        </table>
    </div>

    <!-- Student Details Section -->
    <div class="section">
        <div class="section-title">Personal Information</div>
        <table>
            <tr>
                <td><strong>Name (English):</strong></td>
                <td><?= htmlspecialchars($student['applicant_name_english'] ?? '') ?></td>
                <td><strong>Name (Hindi):</strong></td>
                <td><?= htmlspecialchars($student['applicant_name_hindi'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>Gender:</strong></td>
                <td><?= htmlspecialchars($student['gender'] ?? '') ?></td>
                <td><strong>Date of Birth:</strong></td>
                <td><?= htmlspecialchars($student['date_of_birth'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>Category:</strong></td>
                <td><?= htmlspecialchars($student['category'] ?? '') ?></td>
                <td><strong>Blood Group:</strong></td>
                <td><?= htmlspecialchars($student['blood_group'] ?? '') ?></td>
            </tr>
        </table>
    </div>

    <!-- Family Details Section -->
    <div class="section">
        <div class="section-title">Family Details</div>
        <table>
            <tr>
                <td><strong>Father's Name (English):</strong></td>
                <td><strong>Father's Name (Hindi):</strong></td>
                <td><strong>Father's Occupation:</strong></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($student['father_name_english'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['father_name_hindi'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['father_occupation'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>Mother's Name (English):</strong></td>
                <td><strong>Mother's Name (Hindi):</strong></td>
                <td><strong>Mother's Occupation:</strong></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($student['mother_name_english'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['mother_name_hindi'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['mother_occupation'] ?? '') ?></td>
            </tr>
            <tr>
                <td colspan="3" class="section-title">Guardian Details (if different from parents)</td>
            </tr>
            <tr>
                <td><strong>Guardian's Name (English):</strong></td>
                <td><strong>Guardian's Name (Hindi):</strong></td>
                <td><strong>Guardian's Occupation:</strong></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($student['guardian_name_english'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['guardian_name_hindi'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['guardian_occupation'] ?? '') ?></td>
            </tr>
        </table>
    </div>

    <!-- Contact Details Section -->
    <div class="section">
        <div class="section-title">Contact Details</div>
        <table>
            <tr>
                <td><strong>Permanent Address:</strong></td>
                <td><strong>PIN Code:</strong></td>
                <td><strong>Mobile Number:</strong></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($student['permanent_address'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['pincode'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['mobile_number'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>WhatsApp Number:</strong></td>
                <td><strong>Email:</strong></td>
                <td><strong>Aadhar Number:</strong></td>
            </tr>
            <tr>
                <td><?= htmlspecialchars($student['whatsapp_number'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['email'] ?? '') ?></td>
                <td><?= htmlspecialchars($student['aadhar_number'] ?? '') ?></td>
            </tr>
        </table>
    </div>

    <!-- Educational Qualifications Section -->
    <div class="section">
        <div class="section-title">Educational Qualifications</div>
        <?php if (!empty($student['educations'])): ?>
            <table>
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th>Exam</th>
                        <th>Roll No</th>
                        <th>Year</th>
                        <th>Board/University</th>
                        <th>Max Marks</th>
                        <th>Marks Obtained</th>
                        <th>Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student['educations'] as $edu): ?>
                        <tr>
                            <td><?= htmlspecialchars($edu['exam_type'] ?? '') ?></td>
                            <td><?= htmlspecialchars($edu['roll_no'] ?? '') ?></td>
                            <td><?= htmlspecialchars($edu['year'] ?? '') ?></td>
                            <td><?= htmlspecialchars($edu['university'] ?? '') ?></td>
                            <td style="text-align: right;"><?= htmlspecialchars($edu['max_marks'] ?? '') ?></td>
                            <td style="text-align: right;"><?= htmlspecialchars($edu['marks_obtained'] ?? '') ?></td>
                            <td style="text-align: right;"><?= htmlspecialchars($edu['percentage'] ?? '') ?>%</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No educational qualifications provided.</p>
        <?php endif; ?>
        
        <?php if (!empty($student['institution_last_attended'])): ?>
            <p><strong>Institution Last Attended:</strong> <?= htmlspecialchars($student['institution_last_attended']) ?></p>
        <?php endif; ?>
    </div>

    <div class="section">
            <div class="section-title">Service Information</div>
            <p><strong>Are you in service?:</strong> <?= htmlspecialchars($student['in_service'] ?? '') ?></p>
        </div>
        <div class="section">
            <div class="section-title">Hobbies</div>
            <table>
                <tr>
                    <td style="width: 20%;"><strong>Hobbies/Interests:</strong></td>
                    <td colspan="2"><?= htmlspecialchars($student['hobbies_interests'] ?? '') ?></td>
                    <td style="width: 20%;"><strong>Hobbies Details:</strong></td>
                    <td colspan="2"><?= htmlspecialchars($student['hobbies_details'] ?? '') ?></td>
                </tr>
            </table>
        </div>
        </div> <!-- Close first page content -->
        
        <!-- Second Page -->
        <div class="page" style="page-break-before: always;">
        

        <!-- Hobbies -->
        
 <!-- Document List Section -->
    <?php if (!empty($student['documents'])): ?>
        <div class="section">
    <div class="section-title">Enclosures</div>
    <table>
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th>Document Name</th>
                <th>Document Name</th> <!-- Second column header added -->
            </tr>
        </thead>
        <tbody>
            <?php
            $docs = $student['documents'];
            $total = count($docs);
            for ($i = 0; $i < $total; $i += 2): ?>
                <tr>
                    <td><?= htmlspecialchars($docs[$i]['document_name'] ?? '') ?></td>
                    <td><?= isset($docs[$i + 1]) ? htmlspecialchars($docs[$i + 1]['document_name']) : '' ?></td>
                </tr>
            <?php endfor; ?>
        </tbody>
    </table>
</div>

    <?php endif; ?>

    <?php if (!empty($student['document_list'])): ?>
    <div class="section">
        <div class="section-title">Required Documents List</div>
        <div style="white-space: pre-line;"><?= nl2br(htmlspecialchars($student['document_list'])) ?></div>
    </div>
    <?php endif; ?>
   
    <!-- Declaration Section -->
    <div class="section">
        <div class="section-title">DECLARATION</div>
        <div class="declaration" >
            <ol style="font-size: 12px;">
                <li>I .............................................................................. (name) S/o  ...................................................... …….. Hereby Declare on oath/solemnly affirm that the entries made in application form are correct.</li>
                <li>I have completed each entry of the application form and have attached the attested true copies of marks sheet of Part-I, Part-ll & Part-Ill of degree/P.G.,LLB I,II,III ,Diploma & LLM PART-I examination.</li>
                <li>I shall bring the original mark sheets at the time of interview (if called for). If there is any lapse on my part, I shall be personally responsible for rejection of the application form.</li>
                <li>I hereby declare that neither any criminal case is pending against me in any court of law nor I am convicted by a court of law for any offence.</li>
                <li>I hereby declare that I have gone through the 144,145 and 145A for compulsory attendance, given in the prospectus. I hereby declare that I shall be personally responsible for maintaining requisite attendance to enable myself to appear in University examination.</li>
                <li>I promise not to take part in Political activity of any kind or in agitation whatsoever directly or indirectly and undertake to abide by the rules, framed by the Principal from time to time and I also hold myself responsible for prompt payment of college dues.</li>
                <li>I will abide by the rights of the principal of detaining me in any class in case of negligence of studies or expulsion for gross misconduct.</li>
                <li>All disputes regarding admission, examination and other academic matters shall be subject to the jurisdiction of Sri Ganganagar court/District forum.</li>
                <li>Admission is given subject to approval of <b> Dr. Bhimrao Ambedkar Law University, Jaipur</b>.</li>
            </ol>
            
            <div style="margin-top: 30px;">
                <div style="float: right; text-align: center;">
                    <div class="signature-line"></div><br>
                    <strong>Signature of Applicant</strong>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>

    <!-- Office Use Section -->
    <div class="section">
        <div class="section-title">FOR OFFICE USE</div>
        
        <div class="mb-4" style="margin-top: 20px;">
            <strong>Eligible for admission to: ...............................................................</strong> 
        </div>

        <div class="text-right mb-4" style="margin-top: 20px;">
            <strong>Scrutinizer</strong><br>
        </div>

        <div class="mb-4" style="margin-top: 20px;">
            <strong>Admitted to: ...............................................................</strong>
        </div>

        <div class="mt-5" style="display: flex; justify-content: space-between;" style="margin-top: 20px;">
            <div>Date:</div>
            <div class="text-right">
                <div>Admission Incharge</div>
            </div>
        </div>
    </div>
           
        </div> <!-- Close second page -->
    </div> <!-- Close pdfjs container -->
    
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .page {
                margin: 0;
                padding: 15mm;
                border: none;
            }
        }
        button{
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</div>
<button onclick="HTMLToPDF()">Click to Download PDF</button>
<script>
function HTMLToPDF() {
    try {
        const { jsPDF } = window.jspdf;
        const pdfjs = document.getElementById("pdfjs");
        if (!pdfjs) {
            throw new Error('Could not find the PDF container');
        }

        // Show loading indicator
        const button = document.querySelector('button');
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = 'Generating PDF...';

        // Create a new PDF with A4 dimensions (210mm x 297mm)
        const pdf = new jsPDF({
            orientation: 'portrait',
            unit: 'mm',
            format: 'a4'
        });

        // Set document properties
        pdf.setProperties({
            title: 'Student Application Form',
            subject: 'Admission Form',
            author: 'SGN Law College',
            creator: 'SGN Law College'
        });

        // Get all pages
        const pages = document.querySelectorAll('.page');
        let currentPage = 0;

        // Function to process each page
        const processPage = (index) => {
            return new Promise((resolve, reject) => {
                const page = pages[index];
                const options = {
                    scale: 2,
                    useCORS: true,
                    logging: true,
                    allowTaint: true,
                    scrollX: 0,
                    scrollY: 0,
                    windowWidth: page.scrollWidth,
                    windowHeight: page.scrollHeight
                };

                html2canvas(page, options).then(canvas => {
                    try {
                        const imgData = canvas.toDataURL('image/png');
                        const imgWidth = 190; // A4 width - margins (210 - 20)
                        const imgHeight = (canvas.height * imgWidth) / canvas.width;

                        // Add new page if not the first page
                        if (index > 0) {
                            pdf.addPage();
                        }

                        // Add image to PDF
                        pdf.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);

                        // Add page number
                        const pageNumber = index + 1;
                        pdf.setPage(pageNumber);
                        pdf.setFontSize(8);
                        pdf.text('Page ' + pageNumber + ' of ' + pages.length, 180, 287);

                        resolve();
                    } catch (e) {
                        reject(e);
                    }
                }).catch(error => {
                    console.error('Error in html2canvas for page ' + (index + 1) + ':', error);
                    reject(error);
                });
            });
        };

        // Process all pages sequentially
        const processAllPages = async () => {
            try {
                for (let i = 0; i < pages.length; i++) {
                    await processPage(i);
                }
                
                // Save the PDF after all pages are processed
                pdf.save('Admission_Form_' + new Date().toISOString().slice(0, 10) + '.pdf');
                
                // Reset button state
                button.disabled = false;
                button.textContent = originalText;
            } catch (error) {
                console.error('Error processing pages:', error);
                alert('Error generating PDF: ' + error.message);
                button.disabled = false;
                button.textContent = originalText;
            }
        };

        // Start processing pages
        processAllPages();

    } catch (error) {
        console.error('Fatal error in HTMLToPDF:', error);
        alert('Failed to generate PDF: ' + error.message);
        const button = document.querySelector('button');
        if (button) {
            button.disabled = false;
            button.textContent = 'Generate PDF';
        }
    }
}
</script>
</script>
</body>
</html>
