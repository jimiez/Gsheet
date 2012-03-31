function checkPasswords() {
    var pass1 = document.adminCreation.adminPass.value;
    var pass2 = document.adminCreation.adminPassConfirm.value;
    var user = document.adminCreation.adminUser.value;
       
    if (pass1 != pass2) {
        alert('Please make sure the passwords match!');
    } else if (user.length == 0) {
        alert('Username field cannot be empty!');
    } else if (pass1.length == 0) {
        alert('Password field cannot be empty!')
    } else {
        document.adminCreation.submit();
    }
}
