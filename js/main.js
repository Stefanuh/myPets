$(document).ready(function() {

    // Change datapicker date and format to Dutch
    $.datepicker.setDefaults( $.extend({'dateFormat':'dd-mm-yy'}, $.datepicker.regional['nl']));

    // Apply datepicker element to all inputs with the datepicker ID
    $( function() { $( "#datepicker" ).datepicker({ maxDate: '0' });});

    // Apply simple select2 element to all inputs with the select class
    $('.select').select2({});


});
