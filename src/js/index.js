function confirmDelete(){
    
    var character = document.charDelete.deletee.value;
    
    if (confirm("Are you sure you wish to delete " + character + "?")) {
        document.charDelete.submit();
    }
}

function checkPasswords() {

    var pass1 = document.changePass.newPass.value;
    var pass2 = document.changePass.newPassConfirm.value;
           
    if (pass1 != pass2) {
        alert('Please make sure the passwords match!');
    } else if (pass1.length == 0) {
        alert('New password cannot be empty!');
    } else {
        document.changePass.submit();
    }
}

function fetchForm(str) {
              
    xmlhttp = new XMLHttpRequest();
            
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("fetchedForm").innerHTML = xmlhttp.responseText;
        }
    }
    
    xmlhttp.open("GET", "ajax.php?form=" + str.value, true);

    xmlhttp.send();
}     