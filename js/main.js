$(document).ready(function() {
    $.datepicker.setDefaults( $.extend({'dateFormat':'dd-mm-yy'}, $.datepicker.regional['nl']));
    $( ".datepicker" ).datepicker({ maxDate: '0' });
    $( ".datepicker-future" ).datepicker({ minDate: '1' });
    $('.select').select2();
    $('input[type=file]').change(function(){
        $in=$(this);
        $in.next().html($in.val());
    });
});
