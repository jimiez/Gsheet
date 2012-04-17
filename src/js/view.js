function updateAll() {
    updateSpeed();
    updateDamage();
    updateDodge();
}

function increaseValue(field) {
    document.baseform[field].value++;
}

function decreaseValue(field) {
    document.baseform[field].value--;
}

function increaseSkill(field) {
    document.baseform[field].value++;
}

function decreaseSkill(field) {
    document.baseform[field].value--;
}

function updateSpeed() {
    var a = parseInt(document.baseform.htField.value);
    var b = parseInt(document.baseform.dxField.value);
    var c = (a+b)/4;
    document.baseform.basicSpeedField.value = c;
    document.baseform.moveField.value = Math.floor(c)
}

function updateDodge() {
    var move = parseInt(document.baseform.moveField.value);
    document.baseform.dodgeField.value = move;
}

function updateDamage() {
    var thrust = Array(20);
    var slash = Array(20);

    thrust[1] = "1d-9";
    thrust[2] = "1d-8";
    thrust[3] = "1d-7";
    thrust[4] = "1d-6";
    thrust[5] = "1d-5";
    thrust[6] = "1d-4";
    thrust[7] = "1d-3";
    thrust[8] = "1d-3";
    thrust[9] = "1d-2";
    thrust[10] = "1d-2";
    thrust[11] = "1d-1";
    thrust[12] = "1d-1";
    thrust[13] = "1d";
    thrust[14] = "1d";
    thrust[15] = "1d+1";
    thrust[16] = "1d+1";
    thrust[17] = "1d+2";
    thrust[18] = "1d+2";
    thrust[19] = "2d-1";
    thrust[20] = "2d-1";
    
    slash[1] = "1d-9";
    slash[2] = "1d-8";
    slash[3] = "1d-7";
    slash[4] = "1d-6";
    slash[5] = "1d-5";
    slash[6] = "1d-4";
    slash[7] = "1d-3";
    slash[8] = "1d-2";
    slash[9] = "1d-1";
    slash[10] = "1d";
    slash[11] = "1d+1";
    slash[12] = "1d+2";
    slash[13] = "2d-1";
    slash[14] = "2d";
    slash[15] = "2d+1";
    slash[16] = "2d+2";
    slash[17] = "3d-1";
    slash[18] = "3d";
    slash[19] = "3d+1";
    slash[20] = "3d+2";
    
    var ind = parseInt(document.baseform.stField.value);
    document.baseform.dmgThrustField.value = thrust[ind];
    document.baseform.dmgSlashField.value = slash[ind];
}

function calculateSkills() {
    
    
    
}

function openSelector(targetField){
    var w = window.open('selector.php','selector','width=610,height=550,scrollbars=0');
    w.targetField = targetField;
    w.focus();
    return false;
}
          
function setTargetField(targetField, value){
    if (targetField){
        targetField.value = value;
    }
    window.focus();
}