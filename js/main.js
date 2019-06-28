$(document).ready(function() {

    // Change datapicker date and format to Dutch
    $.datepicker.setDefaults( $.extend({'dateFormat':'dd-mm-yy'}, $.datepicker.regional['nl']));

    // Apply datepicker element to all inputs with the datepicker ID
    $( ".datepicker" ).datepicker({ maxDate: '0' });
    $( ".datepicker-future" ).datepicker({ minDate: '1' });


    // Apply simple select2 element to all inputs with the select class
    $('.select').select2({});

    $('input[type=file]').change(function(){
        $in=$(this);
        $in.next().html($in.val());
    });

    $(document).ready(function() {
        $('.select_mul').select2();
    });

    $( function() {
        $(".datetimepicker").datetimepicker({
            controlType: 'select',
            oneLine: true,
            timeFormat: 'HH:mm',
            showButtonPanel: false,
            hourMin: 9,
            hourMax: 19,
            minDate: 1,
            stepMinute: 10
        });
    });
    
});
