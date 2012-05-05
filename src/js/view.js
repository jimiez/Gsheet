/*
 * Pääfunktio jolla kutsutaan kaikkia muita päivitettäviä elementtejä
 */
function updateAll(status) {
    updateSpeed();
    updateDamage();
    updateDodge();
    updateTotals();
    updateItemTotals();
    calculateSkills();
    if (status == "load") {
        updateSaveStatus("saved");
    } else {
        updateSaveStatus();
    }
}

/*
 * Kasvattaa valitun kentän arvoa yhdellä
 */
function increaseValue(field) {
    document.baseform[field].value++;
}

/*
 * Pienentää valitun kentän arvoa yhdellä
 */
function decreaseValue(field) {
    document.baseform[field].value--;
}

/*
 * Laskee ja asettaa hahmon nopeuden
 */
function updateSpeed() {
    var a = parseInt(document.baseform.htField.value);
    var b = parseInt(document.baseform.dxField.value);
    var c = (a+b)/4;
    var baseMove = Math.floor(c);
    document.baseform.basicSpeedField.value = c;
     
    
    var movePenalty = updateEncumbrance();
    
    if (movePenalty == 0) {
        document.baseform.moveField.value = c; 
        document.baseform.moveField.style.backgroundColor = "#FFFFFF";
    } else {
        document.baseform.moveField.value = c - movePenalty;
        document.baseform.moveField.style.backgroundColor = "#FF0000";
    }
}

/*
 * Asettaa hahmon väistön
 */
function updateDodge() {
    var move = parseInt(document.baseform.moveField.value);
    document.baseform.dodgeField.value = move;
}

/*
 * Laskee hahmon voiman perusteella vahinkomääriä
 */
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

/*
 * Laskee kaikkien pisteiden summat
 */
function updateTotals() {
    var adv = sumOfFormArray('advantagePoints[]');
    var dadv = sumOfFormArray('disadvantagePoints[]') + calculateQuirks();
    
    var skills = sumOfFormArray('skillPts[]');
    var base = calculateBasic();
    var total = adv + dadv + skills + base;
    document.baseform.advantageTotalField.value = adv;
    document.baseform.disadvantageTotalField.value = dadv;
    document.baseform.skillTotalField.value = skills;
    document.baseform.attributeTotalField.value = base;
    document.baseform.totalPointsField.value = total;
    document.baseform.unusedPointsField.value = document.baseform.campTotalPointsField.value - total;
}

/*
 * Päivittää encumbrancen
 */
function updateEncumbrance() {
    var totalWeight = parseInt(document.baseform.totalWeight.value = sumOfFormArray('itemWeight[]'));
    var str = parseInt(document.baseform.stField.value);

    var movement;
    var enc;
    if (totalWeight < str * 2) {
        enc = "None";
        movement = 0;
    } else if (totalWeight < str * 4) {
        enc = "Light";
        movement = 1;
    } else if (totalWeight < str * 6) {
        enc = "Medium";
        movement = 2;
    } else if (totalWeight < str * 12) {
        enc = "Heavy";
        movement = 3;
    } else if (totalWeight > str * 20) {
        enc = "Xtra-Heavy";
        movement = 4;
    }     
    
    document.baseform.encumbranceField.value = enc;
    
    return movement;
}

/*
 * Laskee perusominaisuuksien (ST, DX, IQ, HT) pistearvot
 */
function calculateBasic() {
   
    var attributeValue = Array(20);

    attributeValue[1] = -80;
    attributeValue[2] = -70;
    attributeValue[3] = -60;
    attributeValue[4] = -50;
    attributeValue[5] = -40;
    attributeValue[6] = -30;
    attributeValue[7] = -20;
    attributeValue[8] = -15;
    attributeValue[9] = -10;
    attributeValue[10] = 0;
    attributeValue[11] = 10;
    attributeValue[12] = 20;
    attributeValue[13] = 30;
    attributeValue[14] = 45;
    attributeValue[15] = 60;
    attributeValue[16] = 80;
    attributeValue[17] = 100;
    attributeValue[18] = 125;
    attributeValue[19] = 150;
    attributeValue[20] = 175;   
    
    var total = 0;
    
    total += attributeValue[parseInt(document.baseform.stField.value)];
    total += attributeValue[parseInt(document.baseform.dxField.value)];
    total += attributeValue[parseInt(document.baseform.iqField.value)];
    total += attributeValue[parseInt(document.baseform.htField.value)];
    
    return total;   

}

/*
 * Laskee valitun form - taulukon summan
 */
function sumOfFormArray(array) {
    var sum = 0;
    var a = document.baseform.elements[array]; 
    for (i = 0; i < a.length; i++) {
        if (parseInt(a[i].value)) {                    
            sum += parseInt(a[i].value);
        }
    }
    return sum;
}
      
/*
 * Avaa jotain form - kenttää klikatessa valitsijaikkunan
 */      
function openSelector(targetField, type){
    var w = window.open("selector.php?type=" + type , "Selector", "scrollbars=auto,toolbar=no,menubar=no,status=no, width=640, height=400");
    w.targetField = targetField;
    w.focus();
    return false;
}

/*
 * Asettaa valitsjaikkunan paluuarvot advantage/disadvantage - kenttiin
 */      
function setAttrField(targetField, value1, value2, type){
    var a;
    if (type == 'A') {
        a = findIndex(targetField, "adv");
    } else {
        a = findIndex(targetField, "disadv");
    }
    
    if (targetField){
        targetField.value = value1;
        if (type == 'A'){
            document.baseform.elements["advantagePoints[]"][a].value = value2;
        } else {
            document.baseform.elements["disadvantagePoints[]"][a].value = value2;    
        }
    }
    window.focus();
}

/*
 * Asettaa valitsjaikkunan paluuarvot skills - kenttiin
 */       
function setSkillField(targetField, value1, value2, value3){
    var a = findIndex(targetField, "skill");
    if (targetField){
        targetField.value = value1;
        document.baseform.elements["skillType[]"][a].value = value2;
        document.baseform.elements["skillDiff[]"][a].value = value3;
    }
    window.focus();
}

/*
 * Etsii jonkun klikatun taulukkomuotoisen form - elementin järjestysnumeron
 */      
function findIndex(field, element) {
    var a;
    if (element == 'skill') {
        a = document.baseform.elements["skillName[]"];
    } else if (element == 'adv') {
        a = document.baseform.elements["advantageName[]"];
    } else {
        a = document.baseform.elements["disadvantageName[]"];
    }
     
    for (i = 0; i < a.length; i++) {
        if (a[i] == field) {
            return i;
        }
    }
    return -1;
}

/*
 * Laskee taitoihin asetettujen pisteiden perusteella ko. taidon lopullisen heiton
 */      
function calculateSkills() {
                
    var skillPts = document.baseform.elements["skillPts[]"];
                            
    for (i = 0; i < skillPts.length; i++) {
        pts = parseFloat(document.baseform.elements["skillPts[]"][i].value);
        diff = document.baseform.elements["skillDiff[]"][i].value;
        if (document.baseform.elements["skillType[]"][i].value == "P") {
            result = calculatePhysicalSkill(diff, pts);
            dx = parseInt(document.baseform.dxField.value);
            document.baseform.elements["skillCheck[]"][i].value = result + dx;
        } else if (document.baseform.elements["skillType[]"][i].value == "M"){
            result = calculateMentalSkill(diff, pts);
            iq = parseInt(document.baseform.iqField.value);
            document.baseform.elements["skillCheck[]"][i].value = result + iq;
        }
    }
}

/*
 * Laskee fyysisisen skillin heiton vaikeuden ja pistemäärän perusteella
 */      
function calculatePhysicalSkill(difficulty, points) {
  
    if (difficulty == 'Easy') {
        base = -1;
    } else if (difficulty == 'Average') {
        base = -2;
    } else {
        base = -3;
    }
    
    if (points < 1) {
        return base;
    } else if (points < 2) { 
        return base + 1;
    } else if (points < 4) {
        return base + 2;
    } else if (points < 8) {
        return base + 3;
    } else {
        return Math.floor(points / 8) + 3 + base;
    }
}

/*
 * Laskee henkisen skillin heiton vaikeuden ja pistemäärän perusteella
 */      
function calculateMentalSkill(difficulty, points) {
  
    if (difficulty == 'Easy') {
        base = -1;
    } else if (difficulty == 'Average') {
        base = -2;
    } else if (difficulty == 'Hard') {
        base = -3;
    } else {
        base = -4;
    }
    
    if (points < 1) {
        return base;
    } else if (points < 2) { 
        return base + 1;
    } else if (points < 4) {
        return base + 2;
    
    } else {
        if (difficulty == 'Very Hard') {
            return Math.floor(points / 4) - 2;  
        } else {
            return Math.floor(points / 2) + 1 + base;
        }
    }
}

/*
 * Laskee quirkkien pistearvon
 */   
function calculateQuirks() {

    var a = document.baseform.elements["quirks[]"]; 
    var total = 0;
    for (i = 0; i < a.length; i++) {
        if (a[i].value != "") {                    
            total -= 1;
        }
    }
    return total;
}

/*
 * Ilmoittaa tallennustatuksen 
 */   
function updateSaveStatus(status) {
    if (status == "saved") {
        document.getElementById("saveStatus").innerHTML = "No changes";
    } else {
        document.getElementById("saveStatus").innerHTML = "<font color=red size=+1>Changes not saved!</font>";
    }
}

/*
 * Laskee repun esineiden kokonaispainon ja arvon
 */ 
function updateItemTotals() {
    document.baseform.totalWeight.value = sumOfFormArray('itemWeight[]');
    document.baseform.totalValue.value = sumOfFormArray('itemValue[]');
}