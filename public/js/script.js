// Jquery, hide slowly the alert message
$(document).ready(function() {
    $("#closeAlert").fadeTo(3000, 2000).slideUp(2000, function(){
        $("#closeAlert").slideUp(2000);
    });
});