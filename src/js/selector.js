$(document).ready(function(){
    $('#selected').change(function(){
        getQuery($(this).val());                   
    });
            
});
function getQuery(str){
    $.post("ajax.php",{
        getQuery: str
    },
    function(data){
        $('#name').html(data.attrName);
        $('#points').html(data.attrPoints);
        $('#desc').html(data.attrDesc);
    }, "json");      
                
}
        
function setValue(value){
    if (opener && !opener.closed && opener.setTargetField){
        opener.setTargetField(targetField, value);
    }
    window.close();
}

function setMultiValue(value1, value2, value3){
    if (opener && !opener.closed && opener.setTargetField){
        opener.setTargetField(targetField, value1, value2, value3);
    }
    window.close();
}