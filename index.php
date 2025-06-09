<?php require "./includes/header.php";
$form_no = 'S.No.' . time(); ?>
<div class="header">
  <div class="container">
    <div class="text-center">
      <h2><i class="fas fa-graduation-cap"></i> Sri Guru Nanak Girls PG College</h2>
      <p>Affiliated to Maharaja Ganga Singh University, Bikaner (Raj.)</p>
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
          <i class="fas fa-book-open"></i> 1. Class to Which Admission is Sought
        </div>
        <div class="row g-3">
          <div class="col-md-3">
            <label class="form-label required-field">Class</label>
            <input type="text" class="form-control" placeholder="e.g. B.A. I" required name="class">
          </div>
          <div class="col-md-3">
            <label class="form-label">Part</label>
            <input type="tel" class="form-control num-only" placeholder="Part 1/2" maxlength="1" name="part">
          </div>
          <div class="col-md-3">
            <label class="form-label required-field">Medium</label>
            <select class="form-select" required name="medium">
              <option value="" selected disabled>Select Medium</option>
              <option value="English">English</option>
              <option value="Hindi">Hindi</option>
            </select>
          </div>
          <div class="col-md-3">
            <label class="form-label required-field">Faculty</label><br>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="faculty" value="Arts" id="arts" required>
              <label class="form-check-label" for="arts">Arts</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="faculty" value="Science" id="science">
              <label class="form-check-label" for="science">Science</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="faculty" value="Commerce" id="commerce">
              <label class="form-check-label" for="commerce">Commerce</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" name="faculty" value="Computer" id="computer">
              <label class="form-check-label" for="computer">Computer</label>
            </div>
          </div>
        </div>
      </div>

      <!-- 2. Personal Details -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-user-graduate"></i> 2. Personal Details
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label required-field">Name of Applicant (in English)</label>
            <input type="text" class="form-control char-only" placeholder="Full Name" required name="applicant_name">
          </div>
          <div class="col-md-6">
            <label class="form-label">Name of Applicant (in Hindi)</label>
            <input type="text" class="form-control" placeholder="" name="hindi_name">
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Father's Name</label>
            <input type="text" class="form-control char-only" placeholder="Father's Name" required name="father_name">
          </div>
          <div class="col-md-6">
            <label class="form-label">Father's Occupation</label>
            <input type="text" class="form-control char-only" placeholder="Occupation" name="f_occupation">
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Mother's Name</label>
            <input type="text" class="form-control char-only" placeholder="Mother's Name" required name="mother_name">
          </div>
          <div class="col-md-6">
            <label class="form-label">Mother's Occupation</label>
            <input type="text" class="form-control char-only" placeholder="Occupation" name="m_occupation">
          </div>
      <div class="col-md-6">
        <div class="row g-2">
          <!-- Date of Birth -->
          <div class="col-12 col-sm-6">
            <label class="form-label required-field">Date of Birth</label>
            <input type="date" class="form-control" required name="dob">
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
          <!-- Aadhar Number -->
          <div class="col-12">
            <label class="form-label required-field">Aadhar Number</label>
            <input type="tel" maxlength="12" class="form-control num-only" placeholder="12-digit Aadhar" pattern="[0-9]{12}" required name="aadhar">
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
                <input type="file" class="file-upload-input" accept="image/*" required name="photo">
                <span class="file-name text-primary mt-2" style="display:block;font-size:0.95em;"></span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- 3. Contact Details -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-address-book"></i> 3. Contact Details
        </div>
        <div class="mb-3">
          <label class="form-label required-field">Permanent Address</label>
          <textarea class="form-control" rows="3" placeholder="Full address with PIN code" required id="perm" name="perm_address"></textarea>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="sameAddress" name="same_address">
          <label class="form-check-label" for="sameAddress">Same as permanent address</label>
        </div>
        <div class="mb-3" id="localAddressField">
          <label class="form-label">Local Address (if different)</label>
          <textarea class="form-control" rows="3" placeholder="Local address details" id="local" name="local_address"></textarea>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label required-field">Phone / Mobile</label>
            <input type="tel" class="form-control num-only" placeholder="With STD code" required name="phone">
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="example@email.com" name="email">
          </div>
        </div>
      </div>

      <!-- 4. Optional Subjects -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-list-alt"></i> 4. Subject Offered (Optional)
        </div>
        <div class="row g-3">
          <div class="col-md-4">
            <label class="form-label">Subject 1</label>
            <input type="text" class="form-control" placeholder="Main subject" name="subject1">
          </div>
          <div class="col-md-4">
            <label class="form-label">Subject 2</label>
            <input type="text" class="form-control" placeholder="Secondary subject" name="subject2">
          </div>
          <div class="col-md-4">
            <label class="form-label">Subject 3</label>
            <input type="text" class="form-control" placeholder="Additional subject" name="subject3">
          </div>
        </div>
      </div>

      <!-- 5. Compulsory Subjects -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-book"></i> 5. Compulsory Subjects (For First Year Only)
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="Elementary Computer" id="comp1" name="comp_computer">
              <label class="form-check-label" for="comp1">Elementary Computer</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="Environmental Studies" id="comp2" name="comp_env">
              <label class="form-check-label" for="comp2">Environmental Studies</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="General English" id="comp3" name="comp_english">
              <label class="form-check-label" for="comp3">General English</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="General Hindi" id="comp4" name="comp_hindi">
              <label class="form-check-label" for="comp4">General Hindi</label>
            </div>
          </div>
        </div>
      </div>

      <!-- 6. Previous Exam Details -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-history"></i> 6. Previous Examination Passed
        </div>
        
        <div class="table-responsive" id="table">
            <table class="table">
            <thead class="table-light">
              <tr>
                <th>Course Title</th>
                <th>Year</th>
                <th>University/Board</th>
                <th>Subjects</th>
                <th>%</th>
                <th class="division-col">Division</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" placeholder="e.g. 12th" name="prev_course_title"></td>
                <td><input type="text" class="form-control num-only" placeholder="year" name="prev_year"></td>
                <td><input type="text" class="form-control" placeholder="Board" name="prev_board"></td>
                <td><input type="text" class="form-control" placeholder="Main sub" name="prev_subjects"></td>
                <td><input type="text" class="form-control num-only" placeholder="Percentage" name="prev_percentage"></td>
                <td>
                  <select class="form-select" name="prev_division">
                    <option value="" disabled selected>Select</option>
                    <option>1st</option>
                    <option>2nd</option>
                    <option>3rd</option>
                  </select>
                </td>
              </tr>
           
            </tbody>
          </table>
        </div>
      </div>

      <!-- 7. Institution Last Attended -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-school"></i> 7. Institution Last Attended
        </div>
        <div class="mb-3">
          <label class="form-label required-field">School/College Name</label>
          <input type="text" class="form-control" placeholder="Full institution name" required name="institution_name">
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" placeholder="Institution address" name="institution_address">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control num-only" placeholder="Phone number" name="institution_contact">
          </div>
        </div>
      </div>

      <!-- 8. University Enrolment -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-id-card"></i> 8. University Enrollment No. (if available)
        </div>
        <input type="text" class="form-control" placeholder="Enrollment Number" name="university_enrollment">
      </div>

      <!-- 9. NSS -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-hands-helping"></i> 9. Extra-Curricular Activities
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Is NSS Offered?</label>
            <select class="form-select" name="nss_offered">
              <option value="" selected disabled>Select</option>
              <option>Yes</option>
              <option>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Other Activities</label>
            <input type="text" class="form-control" placeholder="Sports, clubs, etc." name="other_activities">
          </div>
        </div>
      </div>
   <!-- Declaration -->
<div class="form-section bg-light p-3 rounded">
  <h5 class="mb-3"><i class="fas fa-pen-nib"></i> Declaration</h5>
  <p>
    I hereby declare that the information provided above is true to the best of my knowledge. I understand that any incorrect information may result in the cancellation of my admission.
  </p>
  <div class="form-check">
    <input class="form-check-input" type="checkbox" id="declaration" required name="declaration">
    <label class="form-check-label" for="declaration">
      I agree to the above declaration
    </label>
  </div>
</div>

<!-- Submit Button -->
<div class="text-center mt-4">
  <button type="button" id="submit" class="btn btn-primary px-5 py-2">
    <i class="fas fa-paper-plane"></i> Submit Application
  </button>
</div>
<div class="text-center mt-4 ">
  <a href="./admission/user_pdf.php?form_no=<?php echo $form_no; ?>" id="download" class="btn btn-success ml-2" style="display:none;" target="_blank" onclick="setTimeout(function(){ location.reload(); }, 1000);">
        <i class="fa-solid fa-download"></i> Download Admission PDF
      </a>
</div>


    </form>
  </div>
</div>
<?php require './includes/footer.php'; ?>