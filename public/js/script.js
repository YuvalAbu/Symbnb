// Jquery, hide slowly the alert message
$(document).ready(function() {
    $(".alert").fadeTo(2000, 800).slideUp(800, function(){
        $(".alert").slideUp(800);
    });
});