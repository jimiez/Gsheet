function showDetails(str, type) {
          
    xmlhttp = new XMLHttpRequest();
            
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("details").innerHTML = xmlhttp.responseText;
        }
    }
    if (type == 'attr') {
        xmlhttp.open("GET", "ajax.php?attr=" + str, true);
    } else if (type == 'skill') {
        xmlhttp.open("GET", "ajax.php?skill=" + str, true);    
    } else if (type == 'editAttr') {
        xmlhttp.open("GET", "ajax.php?editAttr=" + str, true);
    } else {
        xmlhttp.open("GET", "ajax.php?editSkill=" + str, true);    
    }
    xmlhttp.send();
}      
function setAttrValue(value1, value2, type){
    if (opener && !opener.closed && opener.setAttrField){
        var a = parseInt(value2);
        opener.setAttrField(targetField, value1, a, type);
    }
    window.close();
}

function setSkillValue(value1, value2, value3){
    if (opener && !opener.closed && opener.setSkillField){
        opener.setSkillField(targetField, value1, value2, value3);
    }
    window.close();
}