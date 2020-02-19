// Jquery, hide slowly the alert message
$(document).ready(function() {
    $("#closeAlert").fadeTo(2000, 1000).slideUp(1000, function(){
        $("#closeAlert").slideUp(1000);
    });
});