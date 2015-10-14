"use strict";

$(document).ready(function() {
  const url = parse_current_url();
  const re = /-/g;
  console.log(url);

  function cb (start, end, label) {
    console.log(start.toISOString(), end.toISOString(), label);
    $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));
    // alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
  }

  const optionSet1 = {
    format: 'DD/MM/YYYY',
    startDate: url.date1.replace(re, '/'),
    endDate:   url.date2.replace(re, '/'),
    minDate: '01/01/2010',
    maxDate: '12/31/2020',
    dateLimit: { days: 60 },
    showDropdowns: true,
    showWeekNumbers: true,
    timePicker: false,
    timePickerIncrement: 1,
    timePicker12Hour: true,
    ranges: {
      "Aujoud'hui": [moment(), moment()],
      'hier': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
      '7 derniers jours': [moment().subtract(6, 'days'), moment()],
      '30 derniers jours': [moment().subtract(29, 'days'), moment()],
      'Ce mois': [moment().startOf('month'), moment().endOf('month')],
      'Le mois deriner': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
    },
    opens: 'left',
    buttonClasses: ['btn btn-default'],
    applyClass: 'btn-small btn-primary',
    cancelClass: 'btn-small',
    format: 'DD/MM/YYYY',
    separator: ' to ',
    locale: {
      applyLabel: 'Appliquer',
      cancelLabel: 'Anuler',
      fromLabel: 'Du',
      toLabel: 'Au',
      customRangeLabel: 'Période libre',
      daysOfWeek: ['Di','Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa'],
      monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
      firstDay: 1
    }
  };
  $('#reportrange').daterangepicker(optionSet1, cb);
  $('#reportrange span').html(moment().format('D, MMMM, YYYY') + ' - ' + moment().format('D, MMMM, YYYY'));
  $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
  $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
  $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
    console.log("apply event fired, start/end dates are " 
        + picker.startDate.format('DD MM, YYYY')
        + " to "
        + picker.endDate.format('DD MM, YYYY')
        );
    window.location.href = "bilanv.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY')+"&numero=" + url.numero;
  });
  $('#reportrange').on('cancel.daterangepicker', function(ev, picker) { console.log("cancel event fired"); });

  $('#options1').click(function() {
    $('#reportrange').data('daterangepicker').setOptions(optionSet1, cb);
  });

  $('#options2').click(function() {
    $('#reportrange').data('daterangepicker').setOptions(optionSet2, cb);
  });

  $('#destroy').click(function() {
    $('#reportrange').data('daterangepicker').remove();
  });
});
