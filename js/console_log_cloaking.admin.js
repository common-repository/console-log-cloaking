jQuery(function() {
    optionsToggle();
    jQuery('input[name="lo_enabled[]"').change(function() {
        optionsToggle();
    });
});

var optionsOff = function() {
    jQuery('input[name="lo_roles[]"]').prop('disabled', true);
    jQuery('input[name="lo_logs[]"]').prop('disabled', true);
}

var optionsOn = function() {
    jQuery('input[name="lo_roles[]"]').prop('disabled', false);
    jQuery('input[name="lo_logs[]"]').prop('disabled', false);
}

var optionsToggle = function() {
    var e =jQuery('input[name="lo_enabled[]"]:checked').val();
    if (e === 'disable') {
        optionsOn();
    } else {
        optionsOff();
    }
}


