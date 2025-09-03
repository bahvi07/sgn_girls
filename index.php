<?php require "./includes/header.php";
$form_no = 'S.No.' . time(); ?>
<div class="header">
  <div class="container">
    <div class="text-center">
      <h2><i class="fas fa-graduation-cap"></i> SRI GURU NANAK KHALSA LAW (P.G.) COLLEGE</h2>
      <p>SRI GANGANAGAR - 335001 (RAJ.)</p>
      <h4>Admission Form <strong><?php echo $form_no; ?></strong>
      </h4>
    </div>
  </div>
</div>

<div class="container p-0">
  <div class="form-container">
    <form id="admission_form" method="POST">
          <input type="hidden" name="form_no" value="<?php echo $form_no; ?>">
        
      <!-- Progress Indicator -->
      <div class="d-none d-md-block mb-4">
       <div class="progress">
  <div class="progress-bar" role="progressbar"  aria-valuenow="" aria-valuemin="0" aria-valuemax="100"></div>
</div>
        <div class="d-flex justify-content-between mt-2">
          <small>Personal Details</small>
          <small>Education</small>
          <small>Documents</small>
          <small>Review</small>
        </div>
      </div>

      <!-- 1. Class Details -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-book-open"></i> 1. Class to Which Admission is Sought & Basic Details
        </div>
        <div class="row g-3">
            <div class="col-md-3">
            <label class="form-label">Class to which admission is sought</label>
            <input type="text" class="form-control " placeholder=""  name="class_sought" >
          </div>
          <div class="col-md-3">
            <label class="form-label ">Class Roll No</label>
            <input type="text" class="form-control" placeholder=""  name="class_roll_no">
          </div>
          <div class="col-md-3">
            <label class="form-label">ID Card No</label>
            <input type="text" class="form-control " placeholder="(optional)"  name="id_card_no" >
          </div>
          <div class="col-md-3">
            <label class="form-label required-field">Medium</label>
            <select class="form-select" required name="medium_of_instruction" required>
              <option value="" selected disabled>Select Medium</option>
              <option value="English">English</option>
              <option value="Hindi">Hindi</option>
            </select>
          </div>
        </div>
      </div>

      <!-- 2. Personal Details -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-user-graduate"></i> 2. Student Details
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label required-field">Name of Applicant (in English)</label>
            <input type="text" class="form-control char-only" placeholder="Full Name" required name="applicant_name_english">
          </div>
          <div class="col-md-6">
            <label class="form-label">Name of Applicant (in Hindi)</label>
            <input type="text" class="form-control" placeholder="" name="applicant_name_hindi">
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Gender</label>
            <select class="form-select" required name="gender">
              <option value="" selected disabled>Select Gender</option>
              <option value="Male">Male</option>
              <option value="Female">Female</option>
              <option value="Other">Other</option>
            </select>
          </div>
          <div class="col-md-6">
        <div class="row g-2">
          <!-- Date of Birth -->
          <div class="col-12 col-sm-6">
            <label class="form-label required-field">Date of Birth</label>
            <input type="date" class="form-control" required name="date_of_birth">     
          </div>
          <!-- Category -->
          <div class="col-12 col-sm-6">
            <label class="form-label required-field">Category</label>
            <select class="form-select" required name="category">
              <option value="" selected disabled>Select Category</option>
              <option>General</option>
              <option>SC</option>
              <option>ST</option>
              <option>OBC</option>
              <option>Other</option>
            </select>
          </div>
      
         
        </div>
      </div>
       <div class="col-md-6">
            <label class="form-label required-field">Upload Photo</label>
            <div class="file-upload">
              <div class="file-upload-btn">
                <i class="fas fa-camera fa-2x mb-2"></i>
                <p class="mb-1">Click to upload photo</p>
                <small class="text-muted">(Max 2MB, JPG/PNG)</small>
                <input type="file" class="file-upload-input" accept="image/*" required name="applicant_photo">
                <span class="file-name text-primary mt-2" style="display:block;font-size:0.95em;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>
<div class="form-section">
   <div class="section-title">
          <i class="fas fa-user-graduate"></i> 3. Family Details
        </div>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label required-field">Father's Name</label>
            <input type="text" class="form-control char-only" placeholder="Father's Name" required name="father_name_english">
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Father's Name (in Hindi)</label>
            <input type="text" class="form-control" placeholder="Father's Name" required name="father_name_hindi">
          </div>
          <div class="col-md-4">
            <label class="form-label">Father's Occupation</label>
            <input type="text" class="form-control char-only" placeholder="Occupation" name="father_occupation">
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Mother's Name</label>
            <input type="text" class="form-control char-only" placeholder="Mother's Name" required name="mother_name_english">
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Mother's Name (in Hindi)</label>
            <input type="text" class="form-control " placeholder="Mother's Name" required name="mother_name_hindi">
          </div>
          <div class="col-md-4">
            <label class="form-label">Mother's Occupation</label>
            <input type="text" class="form-control char-only" placeholder="Occupation" name="mother_occupation">
          </div>
           <!-- Guardian Details -->
        <div class="mt-4">
          <h6 class="mb-3"><i class="fas fa-user-shield"></i> Guardian Details (if different from parents)</h6>
          <div class="row g-3">
            <div class="col-md-4">
              <label class="form-label">Guardian's Name</label>
              <input type="text" class="form-control char-only" placeholder="Guardian's Name" name="guardian_name_english">
            </div>
            <div class="col-md-4">
              <label class="form-label">Guardian's Name (in Hindi)</label>
              <input type="text" class="form-control" placeholder="Guardian's Name" name="guardian_name_hindi">
            </div>
            <div class="col-md-4">
            <label class="form-label">Guardian's Occupation</label>
            <input type="text" class="form-control char-only" placeholder="Occupation" name="guardian_occupation">
          </div>
        </div>
      </div>
        </div>
      </div>
      <!-- 3. Contact & Address Details -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-address-book"></i> 4. Contact & Address Details
        </div>
        <div class="mb-3">
          <label class="form-label required-field">Permanent Postal Address</label>
          <textarea class="form-control" rows="2" placeholder="Full address" required id="perm" name="permanent_address"></textarea>
        </div>
        <div class="row g-3 mb-3">
          
          <div class="col-md-4">
            <label class="form-label required-field">PIN Code</label>
            <input type="text" class="form-control num-only" placeholder="PIN Code" required name="pincode" maxlength="6">
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Mobile Number</label>
            <input type="tel" class="form-control num-only" placeholder="10-digit Mobile No." required name="mobile_number" maxlength="10">
          </div>
          <div class="col-md-4">
            <label class="form-label">(Whatsapp No)</label>
            <input type="tel" class="form-control num-only" placeholder="" name="whatsapp_number" maxlength="10">
          </div>
          <div class="col-md-4">
            <label class="form-label">Aadhar Number</label>
            <input type="tel" class="form-control num-only" placeholder="" required name="aadhar_number" maxlength="12">
          </div>
          <div class="col-md-4">
            <label class="form-label">Blood Group</label>
            <input type="text" class="form-control " placeholder=""  name="blood_group" >
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Email</label>
            <input type="email" class="form-control" placeholder="example@email.com" name="email" required>
          </div>
        </div>
        </div>
      
       <div class="form-section">
        <div class="section-title"><i class="fas fa-book"></i> 5. Details of marks obtained in the qualifying examination</div>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="table-light">
              <tr>
                <th>Examination</th>
                <th>Roll No</th>
                <th>Year</th>
                <th>University</th>
                <th>Max Marks</th>
                <th>Marks Obtained</th>
                <th>%</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" name="marks[0][exam_type]" placeholder="e.g. 12th, Graduation, etc." required></td>
                <td><input type="text" class="form-control" name="marks[0][roll_no]" required></td>
                <td><input type="text" class="form-control" name="marks[0][year]" required></td>
                <td><input type="text" class="form-control" name="marks[0][university]" required></td>
                <td><input type="text" class="form-control" name="marks[0][max_marks]" required></td>
                <td><input type="text" class="form-control" name="marks[0][marks_obtained]" required></td>
                <td><input type="text" class="form-control" name="marks[0][percentage]" required></td>
              </tr>
            </tbody>
          </table>
          <div class="col-12 mt-3">
            <label class="form-label required-field">Name of Institution last attended</label>
            <input type="text" class="form-control required-field" placeholder="" name="institude" required>
          </div>
        </div>
      </div>
      
              <!-- 6. Service Info -->
      <div class="form-section">
        <div class="section-title"><i class="fas fa-briefcase"></i> 6. Are you in Service?</div>
        <select class="form-select" name="in_service">
          <option value="">Select</option>
          <option>Yes</option>
          <option>No</option>
        </select>
        <small>If Yes, submit employer’s No Objection Certificate</small>
      </div>
          </div>

          <div class="form-section">
  <div class="section-title"><i class="fas fa-futbol"></i> 7. Hobbies</div>
  <div class="row">
    <div class="col-md-6">
      <input type="text" class="form-control" placeholder="Games / NSS / Debate etc." name="hobbies_interests">
    </div>
    <div class="col-md-6">
      <input type="text" class="form-control" placeholder="Details" name="hobbies_details">
    </div>
  </div>
</div>

<div class="form-section">
        <div class="section-title"><i class="fas fa-paperclip"></i> 8. Enclosures</div>
        <input type="text" class="form-control mb-2" name="enclosure1" placeholder="Document 1 (Write the doc name and attach the copy with printed form)">
        <input type="text" class="form-control mb-2" name="enclosure2" placeholder="Document 2">
        <input type="text" class="form-control mb-2" name="enclosure3" placeholder="Document 3">
        <input type="text" class="form-control mb-2" name="enclosure4" placeholder="Document 4">
      </div>

      <!-- Signature -->
      <div class="mt-4 mb-5 p-2 d-flex justify-content-between">
        <b>Date: .................</b>
        <b>Signature of Applicant</b>
      </div>

        <!-- Declaration -->
<div class="form-section bg-light p-3 rounded">
  <h5 class="mb-3"><i class="fas fa-pen-nib"></i> Declaration</h5>
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
  <div class="p-3 d-flex justify-content-between">
    <b>Date...............</b>
    <b>Signature of Applicant</b>
  </div>
  <div class="container mt-4">
  <h4 class="mb-4">FOR OFFICE USE</h4>

  <div class="mb-3">
    <label class="form-label fw-bold">Eligible for admission to</label>
    <div class="office-line"></div>
  </div>

  <div class="mb-3 text-end">
    <label class="form-label fw-bold">Scrutinizer</label>
  </div>

  <div class="mb-3">
    <label class="form-label fw-bold">Admitted to:</label>
    <div class="office-line"></div>
  </div>

  <div class="mb-5 mt-4 d-flex justify-content-between">
    <b class="">Date...............</b>
    <b>Admission Incharge</b>
  </div>
</div>
  
</div>
        </div>
        
      
 

<!-- Submit Button -->
<div class="text-center mt-4">
  <button type="button" id="submit" class="btn btn-primary px-5 py-2">
    <i class="fas fa-paper-plane"></i> Submit Application
  </button>
</div>
<div class="text-center mt-4">
  <a href="./admission/user_pdf.php?form_no=<?php echo $form_no; ?>" id="download" class="btn btn-success ml-2" style="display:none;" download="Admission_Form_<?php echo $form_no; ?>.pdf">
    <i class="fa-solid fa-download"></i> Download Admission PDF
  </a>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var downloadBtn = document.getElementById('download');
  if(downloadBtn) {
    downloadBtn.addEventListener('click', function(e) {
      // Let the default download behavior happen
      // The download attribute will handle the download
      setTimeout(function() {
        window.location.href = 'index.php';
      }, 1000); // 1 second delay to allow download to start
    });
  }
});
</script>


    </form>
  </div>
</div>
<?php require './includes/footer.php'; ?>