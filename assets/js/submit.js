// Toggle local address visibility
document.getElementById('sameAddress').addEventListener('change', function() {
  const localAddressField = document.getElementById('localAddressField');
  if (this.checked) {
    localAddressField.style.display = 'none';
  } else {
    localAddressField.style.display = 'block';
  }
});

// Initialize same address checkbox
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('sameAddress').dispatchEvent(new Event('change'));
});

const inputs=document.querySelectorAll('#admission_form input, #admission_form textarea');
inputs.forEach((input,index)=>{
input.addEventListener('keydown',(e)=>{

if(e.key==='Enter'){
e.preventDefault();
let nextInput=inputs[index+1];
if(nextInput){
  nextInput.focus();
}
}
});
});


// Is Same Address
const chkAddress = document.getElementById('sameAddress');
chkAddress.addEventListener('change', function() {
const permAd = document.getElementById('perm');
const localAd = document.getElementById('local');
const localAddressField = document.getElementById('localAddressField');
if (this.checked) {
  localAd.value = permAd.value;
  localAd.setAttribute('readonly', true);
  localAddressField.style.display = 'none';
} else {
  localAd.removeAttribute('readonly');
  localAd.value = '';
  localAddressField.style.display = 'block';
}
});

// Progress Bar Fill
const progressBar = document.querySelector('.progress-bar');
function updateProgress(step) {
const percent = (step / 4) * 100;
progressBar.style.width = percent + '%';
progressBar.setAttribute('aria-valuenow', percent);
}


// Validate Form + Posting Data to PHP backend
const submitBtn = document.getElementById('submit');
if(submitBtn){
submitBtn.addEventListener('click', async (e) => {
e.preventDefault();
const form = document.getElementById('admission_form');

// Highlight empty required fields
const firstInvalid = highlightEmptyRequiredFields(form);
if (firstInvalid) {
  firstInvalid.focus();
  Swal.fire("Validation Error", "Please fill all required fields.", "warning");
  return;
}

// Disable button and show waiting text
submitBtn.disabled = true;
submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm mr-2"></span> Please wait...';

// Get required fields
const applicantName = form.querySelector('[name="applicant_name"]');
const motherName = form.querySelector('[name="mother_name"]');
const dob = form.querySelector('[name="dob"]');
const category = form.querySelector('[name="category"]');
const aadhar = form.querySelector('[name="aadhar"]');
const permAddress = form.querySelector('[name="perm_address"]');
const phone = form.querySelector('[name="phone"]');
const photo = form.querySelector('[name="photo"]');

// Validation
if (!applicantName.value.trim()) {
  Swal.fire("Validation Error", "Please enter Applicant Name", "warning");
  return;
}
if (!motherName.value.trim()) {
  Swal.fire("Validation Error", "Please enter Mother's Name", "warning");
  return;
}
if (!dob.value) {
  Swal.fire("Validation Error", "Please select Date of Birth", "warning");
  
  return;
}
if (!category.value) {
  Swal.fire("Validation Error", "Please select Category", "warning");
  return;
}
if (!aadhar.value.trim() || !/^\d{12}$/.test(aadhar.value.trim())) {
  Swal.fire("Validation Error", "Please enter a valid 12-digit Aadhar Number", "warning");
  return;
}

if (!permAddress.value.trim()) {
  Swal.fire("Validation Error", "Please enter Permanent Address", "warning");
  return;
}
if (!phone.value.trim()) {
  Swal.fire("Validation Error", "Please enter Phone/Mobile", "warning");
  return;
}
if (!photo.value) {
  Swal.fire("Validation Error", "Please upload a Photo", "warning");
  return;
}

if (!form.checkValidity()) {
  form.reportValidity();
  return;
}

 const formData = new FormData(form);

 try{
  const response=await fetch('admission/submit.php',{
    method:'POST',
    body:formData
  });

  const result = await response.text();
  try {
    const data = JSON.parse(result);
    if (data.success === true) {
      Swal.fire("Success", "Form submitted successfully!", "success").then(() => {
        // Optionally reset the form or redirect
        document.getElementById('download').style.display='block';
        form.reset();
        progressBar.style.width = '0%';
        progressBar.setAttribute('aria-valuenow', 0);
      });
    } else {
      throw new Error(data.message || "Unknown error occurred");
    }
  } catch (e) {
    console.error("Non-JSON response:", result);
    Swal.fire("Server Error", "Unexpected server response.", "error");
  }
} catch (error) {
  console.error("Error:", error);
  Swal.fire("Failed", "Failed to submit Form" + error.message, "error");
} finally {
  submitBtn.disabled = false;
  submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';
}
});
}


const requiredFields = document.querySelectorAll('.form-section input[required], .form-section select[required], .form-section textarea[required]');
requiredFields.forEach(field => {
field.addEventListener('input', () => {
  let filled = 0;
  requiredFields.forEach(f => { if (f.value) filled++; });
  const percent = (filled / requiredFields.length) * 100;
  progressBar.style.width = percent + '%';
  progressBar.setAttribute('aria-valuenow', percent);
});
});

document.addEventListener('DOMContentLoaded', function() {
const fileInput = document.querySelector('.file-upload-input');
const fileNameSpan = document.querySelector('.file-upload-btn .file-name');
if(fileInput && fileNameSpan) {
  fileInput.addEventListener('change', function() {
    if(this.files && this.files.length > 0) {
      fileNameSpan.textContent = this.files[0].name;
    } else {
      fileNameSpan.textContent = '';
    }
  });
}
});

// Allow only letters and spaces in char-only fields
document.querySelectorAll('.char-only').forEach(input => {
input.addEventListener('input', function() {
  if (/[^a-zA-Z\s]/.test(this.value)) {
    Swal.fire("Invalid Input", "Only letters and spaces are allowed.", "warning");
    this.value = this.value.replace(/[^a-zA-Z\s]/g, '');
  }
});
});

// Allow only numbers in num-only fields
document.querySelectorAll('.num-only').forEach(input => {
input.addEventListener('input', function() {
  if (/[^0-9]/.test(this.value)) {
    Swal.fire("Invalid Input", "Only numbers are allowed.", "warning");
    this.value = this.value.replace(/[^0-9]/g, '');
  }
});
});

// Add red border to empty required fields on submit and remove on input
function highlightEmptyRequiredFields(form) {
const requiredFields = form.querySelectorAll('[required]');
let firstInvalid = null;
requiredFields.forEach(field => {
  if (!field.value.trim()) {
    field.classList.add('border', 'border-danger');
    if (!firstInvalid) firstInvalid = field;
  } else {
    field.classList.remove('border', 'border-danger');
  }
  // Remove red border on input/change
  field.addEventListener('input', function() {
    if (this.value.trim()) {
      this.classList.remove('border', 'border-danger');
    }
  });
});
return firstInvalid;
}