function confirmDelete(){
    
    var character = document.charDelete.deletee.value;
    
    if (confirm("Are you sure you wish to delete " + character + "?")) {
        document.charDelete.submit();
    }
}
