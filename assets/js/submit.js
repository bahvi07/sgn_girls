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