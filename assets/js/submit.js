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