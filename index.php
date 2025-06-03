
<?php require "./includes/header.php";?>
<div class="header">
  <div class="container">
    <div class="text-center">
      <h2><i class="fas fa-graduation-cap"></i> Sri Guru Nanak Girls PG College</h2>
      <p>Affiliated to Maharaja Ganga Singh University, Bikaner (Raj.)</p>
      <h4>Admission Form Session: 20__ - 20__</h4>
    </div>
  </div>
</div>

<div class="container">
  <div class="form-container">
    <form>
      <!-- Progress Indicator -->
      <div class="d-none d-md-block mb-4">
        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
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
            <input type="text" class="form-control" placeholder="e.g. B.A. I" required>
          </div>
          <div class="col-md-3">
            <label class="form-label">Part</label>
            <input type="text" class="form-control" placeholder="Part I/II">
          </div>
          <div class="col-md-3">
            <label class="form-label required-field">Medium</label>
            <select class="form-select" required>
              <option value="" selected disabled>Select Medium</option>
              <option>English</option>
              <option>Hindi</option>
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
            <input type="text" class="form-control" placeholder="Full Name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Name of Applicant (in Hindi)</label>
            <input type="text" class="form-control" placeholder="पूरा नाम">
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Father's Name</label>
            <input type="text" class="form-control" placeholder="Father's Name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Father's Occupation</label>
            <input type="text" class="form-control" placeholder="Occupation">
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Mother's Name</label>
            <input type="text" class="form-control" placeholder="Mother's Name" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Mother's Occupation</label>
            <input type="text" class="form-control" placeholder="Occupation">
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Date of Birth</label>
            <input type="date" class="form-control" required>
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Category</label>
            <select class="form-select" required>
              <option value="" selected disabled>Select Category</option>
              <option>General</option>
              <option>SC</option>
              <option>ST</option>
              <option>OBC</option>
              <option>Other</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label required-field">Previous Marks %</label>
            <input type="number" class="form-control" placeholder="Percentage" min="0" max="100" required>
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Aadhar Number</label>
            <input type="text" class="form-control" placeholder="12-digit Aadhar" pattern="[0-9]{12}" required>
          </div>
          <div class="col-md-6">
            <label class="form-label required-field">Upload Photo</label>
            <div class="file-upload">
              <div class="file-upload-btn">
                <i class="fas fa-camera fa-2x mb-2"></i>
                <p class="mb-1">Click to upload photo</p>
                <small class="text-muted">(Max 2MB, JPG/PNG)</small>
                <input type="file" class="file-upload-input" accept="image/*" required>
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
          <textarea class="form-control" rows="3" placeholder="Full address with PIN code" required></textarea>
        </div>
        <div class="form-check mb-3">
          <input class="form-check-input" type="checkbox" id="sameAddress">
          <label class="form-check-label" for="sameAddress">Same as permanent address</label>
        </div>
        <div class="mb-3" id="localAddressField">
          <label class="form-label">Local Address (if different)</label>
          <textarea class="form-control" rows="3" placeholder="Local address details"></textarea>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label required-field">Phone / Mobile</label>
            <input type="tel" class="form-control" placeholder="With STD code" required>
          </div>
          <div class="col-md-6">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" placeholder="example@email.com">
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
            <input type="text" class="form-control" placeholder="Main subject">
          </div>
          <div class="col-md-4">
            <label class="form-label">Subject 2</label>
            <input type="text" class="form-control" placeholder="Secondary subject">
          </div>
          <div class="col-md-4">
            <label class="form-label">Subject 3</label>
            <input type="text" class="form-control" placeholder="Additional subject">
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
              <input class="form-check-input" type="checkbox" value="Elementary Computer" id="comp1">
              <label class="form-check-label" for="comp1">Elementary Computer</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="Environmental Studies" id="comp2">
              <label class="form-check-label" for="comp2">Environmental Studies</label>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="General English" id="comp3">
              <label class="form-check-label" for="comp3">General English</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="General Hindi" id="comp4">
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
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead class="table-light">
              <tr>
                <th>Course Title</th>
                <th>Year</th>
                <th>University/Board</th>
                <th>Subjects</th>
                <th>%</th>
                <th>Division</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><input type="text" class="form-control" placeholder="e.g. 12th"></td>
                <td><input type="text" class="form-control" placeholder="Passing year"></td>
                <td><input type="text" class="form-control" placeholder="Board name"></td>
                <td><input type="text" class="form-control" placeholder="Main subjects"></td>
                <td><input type="text" class="form-control" placeholder="Percentage"></td>
                <td>
                  <select class="form-select">
                    <option value="" disabled selected>Select</option>
                    <option>1st</option>
                    <option>2nd</option>
                    <option>3rd</option>
                  </select>
                </td>
              </tr>
              <tr>
                <td colspan="6" class="text-center">
                  <button type="button" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-plus"></i> Add Another Qualification
                  </button>
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
          <input type="text" class="form-control" placeholder="Full institution name" required>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Address</label>
            <input type="text" class="form-control" placeholder="Institution address">
          </div>
          <div class="col-md-6">
            <label class="form-label">Contact Number</label>
            <input type="tel" class="form-control" placeholder="Phone number">
          </div>
        </div>
      </div>

      <!-- 8. University Enrolment -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-id-card"></i> 8. University Enrollment No. (if available)
        </div>
        <input type="text" class="form-control" placeholder="Enrollment Number">
      </div>

      <!-- 9. NSS -->
      <div class="form-section">
        <div class="section-title">
          <i class="fas fa-hands-helping"></i> 9. Extra-Curricular Activities
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <label class="form-label">Is NSS Offered?</label>
            <select class="form-select">
              <option value="" selected disabled>Select</option>
              <option>Yes</option>
              <option>No</option>
            </select>
          </div>
          <div class="col-md-6">
            <label class="form-label">Other Activities</label>
            <input type="text" class="form-control" placeholder="Sports, clubs, etc.">
          </div>
        </div>
      </div>

      <!-- Declaration -->
      <div class="form-section bg-light">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="declaration" required>
          <label class="form-check-label" for="declaration">
            I hereby declare that all the information provided in this form is true and correct to the best of my knowledge.
          </label>
        </div>
      </div>

      <!-- Submit -->
      <div class="d-flex justify-content-between mt-4">
        <button type="button" class="btn btn-outline-secondary">
          <i class="fas fa-arrow-left"></i> Back
        </button>
        <button type="submit" class="btn btn-primary">
          Submit Form <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </form>
  </div>
</div>
<?php require'./includes/footer.php';?>
