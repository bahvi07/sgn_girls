<?php
// This file will be included by test-pdf.php
// $student variable is available here with all student data
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <style>
        @page { margin: 20px; }
        @font-face {
            font-family: 'Noto Sans Devanagari';
            font-style: normal;
            font-weight: normal;
            src: url('file://<?php echo str_replace('\\', '/', __DIR__) ?>/assets/fonts/NotoSansDevanagari-Regular.ttf') format('truetype');
        }
        body { 
            font-family: 'Noto Sans Devanagari', Arial, sans-serif; 
            line-height: 1.6;
            direction: ltr;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .header { text-align: center; margin-bottom: 20px; }
        .section { margin-bottom: 30px; }
        .section-title { 
            background-color: #f8f9fa; 
            padding: 8px; 
            font-weight: bold;
            margin: 20px 0 10px 0;
        }
        .signature-line { 
            border-bottom: 1px solid #000; 
            display: inline-block;
            min-width: 200px;
            margin: 20px 0;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .mb-4 { margin-bottom: 1.5rem; }
        .mt-4 { margin-top: 1.5rem; }
        .declaration { font-size: 11px; text-align: justify; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h2>Sri Guru Nanak Khalsa Law (P.G.) College</h2>
         <p>SRI GANGANAGAR - 335001 (RAJ.)</p>
        <h4>Application Form</h4>
        <p>Form No: <?= htmlspecialchars($student['form_no'] ?? '') ?></p>
    </div>

    <!-- Class & Admission Details Section -->
   <div class="section container">
    <div class="section-title h5 mb-3">Admission Details</div>

    <!-- First Row -->
    <div class="row mb-2">
        <div class="col-md-4">
            <p><strong>Form No:</strong> <?= htmlspecialchars($student['form_no'] ?? '') ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Class Roll No:</strong> <?= htmlspecialchars($student['class_roll_no'] ?? '') ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>ID Card No:</strong> <?= htmlspecialchars($student['id_card_no'] ?? '') ?></p>
        </div>
    </div>

    <!-- Second Row -->
    <div class="row">
        <div class="col-md-4">
            <p><strong>Class Sought:</strong> <?= htmlspecialchars($student['class_sought'] ?? '') ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Medium:</strong> <?= htmlspecialchars($student['medium_of_instruction'] ?? '') ?></p>
        </div>
        <div class="col-md-4">
            <p><strong>Category:</strong> <?= htmlspecialchars($student['category'] ?? '') ?></p>
        </div>
    </div>
</div>


    <!-- Student Details Section -->
    <div class="section">
        <div class="section-title">Personal Information</div>
        <p><strong>Name (English):</strong> <?= htmlspecialchars($student['applicant_name_english'] ?? '') ?></p>
        <p><strong>Name (Hindi):</strong> <?= htmlspecialchars($student['applicant_name_hindi'] ?? '') ?></p>
        <p><strong>Gender:</strong> <?= htmlspecialchars($student['gender'] ?? '') ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($student['date_of_birth'] ?? '') ?></p>
        <p><strong>Photo:</strong> <?= !empty($student['applicant_photo_path']) ? 'Attached' : 'Not Provided' ?></p>
    </div>

    <!-- Family Details Section -->
    <div class="section">
        <div class="section-title">Family Details</div>
        <p><strong>Father's Name (English):</strong> <?= htmlspecialchars($student['father_name_english'] ?? '') ?></p>
        <p><strong>Father's Name (Hindi):</strong> <?= htmlspecialchars($student['father_name_hindi'] ?? '') ?></p>
        <p><strong>Father's Occupation:</strong> <?= htmlspecialchars($student['father_occupation'] ?? '') ?></p>
        <p><strong>Mother's Name (English):</strong> <?= htmlspecialchars($student['mother_name_english'] ?? '') ?></p>
        <p><strong>Mother's Name (Hindi):</strong> <?= htmlspecialchars($student['mother_name_hindi'] ?? '') ?></p>
        <p><strong>Mother's Occupation:</strong> <?= htmlspecialchars($student['mother_occupation'] ?? '') ?></p>
        <p><strong>Guardian's Name (English):</strong> <?= htmlspecialchars($student['guardian_name_english'] ?? '') ?></p>
        <p><strong>Guardian's Name (Hindi):</strong> <?= htmlspecialchars($student['guardian_name_hindi'] ?? '') ?></p>
        <p><strong>Guardian's Occupation:</strong> <?= htmlspecialchars($student['guardian_occupation'] ?? '') ?></p>
        <p><strong>Guardian's Relation:</strong> <?= htmlspecialchars($student['guardian_relation'] ?? '') ?></p>
    </div>

    <!-- Contact Details Section -->
    <div class="section">
        <div class="section-title">Contact Details</div>
        <p><strong>Permanent Address:</strong> <?= htmlspecialchars($student['permanent_address'] ?? '') ?></p>
        <p><strong>PIN Code:</strong> <?= htmlspecialchars($student['pincode'] ?? '') ?></p>
        <p><strong>Mobile Number:</strong> <?= htmlspecialchars($student['mobile_number'] ?? '') ?></p>
        <p><strong>WhatsApp Number:</strong> <?= htmlspecialchars($student['whatsapp_number'] ?? '') ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($student['email'] ?? '') ?></p>
        <p><strong>Aadhar Number:</strong> <?= htmlspecialchars($student['aadhar_number'] ?? '') ?></p>
        <p><strong>Blood Group:</strong> <?= htmlspecialchars($student['blood_group'] ?? '') ?></p>
    </div>

    <!-- Educational Qualifications Section -->
    <div class="section">
        <div class="section-title">Educational Qualifications</div>
        <?php if (!empty($student['qualifications'])): ?>
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 20px;">
                <thead>
                    <tr style="background-color: #f8f9fa;">
                        <th style="border: 1px solid #ddd; padding: 8px;">Exam</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Roll No</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Year</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Board/University</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Max Marks</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Marks Obtained</th>
                        <th style="border: 1px solid #ddd; padding: 8px;">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($student['qualifications'] as $qual): ?>
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($qual['exam_type'] ?? '') ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($qual['roll_no'] ?? '') ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($qual['year'] ?? '') ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px;"><?= htmlspecialchars($qual['university'] ?? '') ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?= htmlspecialchars($qual['max_marks'] ?? '') ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?= htmlspecialchars($qual['marks_obtained'] ?? '') ?></td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: right;"><?= htmlspecialchars($qual['percentage'] ?? '') ?>%</td>
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
        <p><strong>Hobbies/Interests:</strong> <?= htmlspecialchars($student['hobbies_interests'] ?? '') ?></p>
        <p><strong>Hobbies Details:</strong> <?= htmlspecialchars($student['hobbies_details'] ?? '') ?></p>
    </div>

    <!-- Enclosures -->
    <div class="section">
        <div class="section-title">Enclosures</div>
        <p><strong>Document 1:</strong> <?= htmlspecialchars($student['enclosure1'] ?? '') ?></p>
        <p><strong>Document 2:</strong> <?= htmlspecialchars($student['enclosure2'] ?? '') ?></p>
        <p><strong>Document 3:</strong> <?= htmlspecialchars($student['enclosure3'] ?? '') ?></p>
        <p><strong>Document 4:</strong> <?= htmlspecialchars($student['enclosure4'] ?? '') ?></p>
    </div>

    <!-- Declaration Section -->
    <div class="section" style="page-break-before: always;">
        <div class="section-title">DECLARATION</div>
        <div class="declaration">
            <ol>
            <li>I .............................................................................. (name) S/o  ...................................................... …….. Hereby 
    Declare on oath/solemnly affirm that the entries made in application form are correct. </li>
    <li>I have completed each entry of the application form and have attached the attested true copies of marks sheet 
    of Part-I, Part-ll & Part-Ill of degree/P.G.,LLB I,II,III ,Diploma & LLM PART-I examination.</li>
    <li>I shall bring the original mark sheets at the time of interview (if called for). If there is any lapse on my part, I 
    shall be personally responsible for rejection of the application form.</li>
    <li>I hereby declare that neither any criminal case is pending against me in any court of law nor I am convicted by 
    a court of law for any offence.</li>
    <li>I hereby declare that I have gone through the 144,145 and 145A for compulsory attendance, given in the 
prospectus. I hereby declare that I shall be personally responsible for maintaining requisite attendance to 
enable myself to appear in University examination. </li>
    <li>I promise not to take part in Political activity of any kind or in agitation whatsoever directly or indirectly and 
undertake to abide by the rules, framed by the Principal from time to time and I also hold myself responsible 
for prompt payment of college dues. </li>
    <li>I will abide by the rights of the principal of detaining me in any class in case of negligence of studies or 
    expulsion for gross misconduct </li>
    <li>All disputes regarding admission, examination and other academic matters shall be subject to the jurisdiction 
    of Sri Ganganagar court/District forum.</li>
    <li>Admission is given subject to approval of <b> Dr. Bhimrao Ambedkar Law University, Jaipur</b> </li>
            </ol>
            
            <div style="margin-top: 50px;">
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
        <div class="section-title mb-4">FOR OFFICE USE</div>
        
        <div class="mb-4">
            <strong>Eligible for admission to: ...............................................................</strong> 
        </div>

        <div class="text-right mb-4">
            <strong>Scrutinizer</strong><br>
        </div>

        <div class="mb-4">
            <strong>Admitted to: ...............................................................</strong>
            
        </div>

        <div class="mt-5" style="display: flex; justify-content: space-between;">
            <div>Date:</div>
            <div class="text-right">
                <div>Admission Incharge</div>
            </div>
        </div>
    </div>
</body>
</html>