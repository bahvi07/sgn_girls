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

  // Validate Form + Posting Data to PHP backend
  const submitBtn=document.getElementById('submit');
  submitBtn.addEventListener('click',async(e)=>{
e.preventDefault();
const form=document.getElementById('admission_form');

if(!form.applicant_name.vale){
  Swal.fire("validation Error","Plaese Enter Applicant Name","Warning");
}
if(!form.)
  });