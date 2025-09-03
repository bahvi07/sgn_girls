<?php
// This file will be included by test-pdf.php
// $student variable is available here with all student data
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
        @page { 
            margin: 20px; /* Increased from 8px */
            size: A4;
        }
        @font-face {
    font-family: "NotoSansDevanagari";
    src: url("<?php echo __DIR__ . '/../assets/fonts/NotoSansDevanagari-Regular.ttf'; ?>") format("truetype");
    font-weight: normal;
    font-style: normal;
}
        * {
            font-family: "NotoSansDevanagari", Arial, sans-serif !important;
        }
        body { 
            font-family: 'Noto Sans Devanagari', Arial, sans-serif; 
            font-size: 11px;
            line-height: 1.5;
            direction: ltr;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            text-rendering: optimizeLegibility;
            -webkit-font-feature-settings: "kern" 1;
            font-feature-settings: "kern" 1;
            -webkit-font-kerning: normal;
            font-kerning: normal;
            text-size-adjust: 100%;
        }
        
        /* Ensure Devanagari text renders properly */
        .hindi-text {
            font-family: 'Noto Sans Devanagari', Arial, sans-serif;
            font-weight: normal;
            unicode-bidi: embed;
        }
        .header { 
            text-align: center; 
            margin-bottom: 10px;
            position: relative;
        }
        .header h2 { 
            font-size: 16px !important; 
            margin: 0; 
            color: #492c00;
        }
        .header h4 { 
            font-size: 13px !important; 
            margin: 6px 0 0 0; 
            color: #492c00;
        }
        .header p  { 
            font-size: 10px !important; 
            margin: 3px 0; 
        }
        .section { 
            margin-bottom: 12px;
            page-break-inside: avoid;
        }
        .section-title { 
            background-color: #f0f0f0; 
            padding: 5px 8px;  /* Increased padding */
            font-size: 12px;   /* Increased from 10px */
            font-weight: bold;
            margin: 12px 0 6px 0;
            color: #492c00;
            border-left: 3px solid #492c00;
        }
        .signature-line { 
            border-bottom: 1px solid #000; 
            display: inline-block;
            min-width: 160px;
            margin: 6px 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mt-2 { margin-top: 0.5rem; }
        .declaration { 
            font-size: 9.5px; 
            line-height: 1.3;
            text-align: justify;
        }
        .photo-box {
            border: 1px solid #999;
            width: 80px; /* Bigger photo */
            height: 80px;
            position: absolute;
            right: 12px;
            top: 12px;
            padding: 2px;
        }
        .photo-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;  /* Increased from 8.5px */
            table-layout: fixed;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 4px 5px; /* Increased padding */
            text-align: left;
            word-wrap: break-word;
            overflow-wrap: anywhere;
        }
        .section table, 
        .section table td, 
        .section table th {
            font-size: 10px !important;
        }
        .no-break {
            page-break-inside: avoid;
        }
    </style>
</head>
<body>
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
        <?php if (!empty($student['qualifications'])): ?>
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
                    <?php foreach ($student['qualifications'] as $qual): ?>
                        <tr>
                            <td><?= htmlspecialchars($qual['exam_type'] ?? '') ?></td>
                            <td><?= htmlspecialchars($qual['roll_no'] ?? '') ?></td>
                            <td><?= htmlspecialchars($qual['year'] ?? '') ?></td>
                            <td><?= htmlspecialchars($qual['university'] ?? '') ?></td>
                            <td style="text-align: right;"><?= htmlspecialchars($qual['max_marks'] ?? '') ?></td>
                            <td style="text-align: right;"><?= htmlspecialchars($qual['marks_obtained'] ?? '') ?></td>
                            <td style="text-align: right;"><?= htmlspecialchars($qual['percentage'] ?? '') ?>%</td>
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

    <!-- Document List Section -->
    <?php if (!empty($student['document_list'])): ?>
    <div class="section">
        <div class="section-title">Required Documents</div>
        <div style="white-space: pre-line;"><?= nl2br(htmlspecialchars($student['document_list'])) ?></div>
    </div>
    <?php endif; ?>

    <!-- Service Info -->
    <div class="section">
        <div class="section-title">Service Information</div>
        <p><strong>Are you in service?:</strong> <?= htmlspecialchars($student['in_service'] ?? '') ?></p>
    </div>

    <!-- Hobbies -->
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
 <!-- Enclosures (SECOND PAGE) -->
    <div class="section">
        <div class="section-title">Enclosures</div>
        <table>
            <tr>
                <td><strong>Document 1:</strong></td>
                <td><?= htmlspecialchars($student['enclosure1'] ?? '') ?></td>
                <td><strong>Document 2:</strong></td>
                <td><?= htmlspecialchars($student['enclosure2'] ?? '') ?></td>
            </tr>
            <tr>
                <td><strong>Document 3:</strong></td>
                <td><?= htmlspecialchars($student['enclosure3'] ?? '') ?></td>
                <td><strong>Document 4:</strong></td>
                <td><?= htmlspecialchars($student['enclosure4'] ?? '') ?></td>
            </tr>
        </table>
    </div>
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

   
</body>
</html>
