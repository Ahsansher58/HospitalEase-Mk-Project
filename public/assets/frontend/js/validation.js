document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.needs-validation');
  
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      } else {
        const emailInput = document.getElementById('email');
        const emailInput2 = document.getElementById('email2');
        const dobInput = document.getElementById('dob');
        const lastNameInput = document.getElementById('lastname');
        const firstnameInput = document.getElementById('firstName');
        const passwordInput = document.getElementById('passwordName');
        const genderInput = document.getElementById('gender');
        const heightInput = document.getElementById('heightName');
        const weightInput = document.getElementById('weightName');
        const addressInput = document.getElementById('addressName');
       
  
        if (addressInput.value.length < 7) {
          event.preventDefault();
          event.stopPropagation();
          addressInput.classList.add('is-invalid');
        }
        if (weightInput.value.length < 7) {
          event.preventDefault();
          event.stopPropagation();
          weightInput.classList.add('is-invalid');
        }
        if (heightInput.value.length < 7) {
          event.preventDefault();
          event.stopPropagation();
          heightInput.classList.add('is-invalid');
        }
        if (genderInput.value.length < 2) {
          event.preventDefault();
          event.stopPropagation();
          genderInput.classList.add('is-invalid');
        }
        if (emailInput.value.length < 2) {
          event.preventDefault();
          event.stopPropagation();
          emailInput.classList.add('is-invalid');
        }
        if (emailInput2.value.length < 2) {
          event.preventDefault();
          event.stopPropagation();
          emailInput2.classList.add('is-invalid');
        }
        if (passwordInput.value.length < 7) {
            event.preventDefault();
            event.stopPropagation();
            passwordInput.classList.add('is-invalid');
          }
        if (dobInput.value.length < 7) {
          event.preventDefault();
          event.stopPropagation();
          dobInput.classList.add('is-invalid');
        }
        if (lastNameInput.value.length < 2) {
          event.preventDefault();
          event.stopPropagation();
          lastNameInput.classList.add('is-invalid');
        }
  
        if (firstnameInput.value.length < 5) {
          event.preventDefault();
          event.stopPropagation();
          firstnameInput.classList.add('is-invalid');
        }
  
       
      }
  
      form.classList.add('was-validated');
    }, false);
  });


