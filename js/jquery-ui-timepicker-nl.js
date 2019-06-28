(function($) {
    $.timepicker.regional['nl'] = {
        timeOnlyTitle: 'Tijdstip',
        timeText: 'Tijd',
        hourText: 'Uur',
        minuteText: 'Minuut',
        timezoneText: 'Tijdzone',
        currentText: 'Nu',
        closeText: 'Sluiten',
        timeFormat: 'HH:mm',
        timeSuffix: '',
        monthNames: ['Januari','Februari','Maart','April','Mei','Juni',
            'Juli','Augustus','September','Oktober','November','December'],
        monthNamesShort: ['Jan','Feb','Mar','Apr','Mei','Jun',
            'Jul','Aug','Sep','Okt','Nov','Dec'],
        dayNames: ['Zondag','Maandag','Dinsdag','Woensdag','Donderdag','Vrijdag','Zaterdag'],
        dayNamesMin: ['Zo','Ma','Di','Wo','Do','Vr','Za'],
        firstDay: 1,
        amNames: ['AM', 'A'],
        pmNames: ['PM', 'P'],
        isRTL: false,
    };
    $.timepicker.setDefaults($.timepicker.regional['nl']);
})(jQuery);