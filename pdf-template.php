<?php
// This file will be included by test-pdf.php
// $student variable is available here with all student data
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; }
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

    <!-- Student Details Section -->
    <div class="section">
        <div class="section-title">Personal Information</div>
        <p><strong>Name:</strong> <?= htmlspecialchars($student['applicant_name_english'] ?? '') ?></p>
        <p><strong>Date of Birth:</strong> <?= htmlspecialchars($student['date_of_birth'] ?? '') ?></p>
        <!-- Add more student details as needed -->
    </div>

    <!-- Add other sections like Contact Details, Educational Qualifications, etc. -->

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