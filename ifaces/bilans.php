
<?php session_start();?>
<?php include "tete.php";?>

   <head>
      
      <link href="../css/bootstrap.min.css" rel="stylesheet">
      
      <link href="../fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
      <link rel="stylesheet" type="text/css" media="all" href="../css/daterangepicker-bs3.css" />
      <script type="text/javascript" src="../js/jquery-2.0.3.min.js"></script>
      <script type="text/javascript" src="../js/bootstrap.min.js"></script>
      <script type="text/javascript" src="../js/moment.js"></script>
      <script type="text/javascript" src="../js/daterangepicker.js"></script>
   </head>
 

      <div class="container">
         

          
<div class"row">
  <div class="col-md-3 " >
<h1>Bilan global</h1>

         
  </div>
   <div class="col-md-4 col-md-offset-5" ><br>
<label for="reportrange">choisisez la periode a inspecter:</label><br>

           

                      

            

            

               <div id="reportrange" class="pull-left" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc">
                  <i class="glyphicon glyphicon-calendar"></i>
                  <span></span> <b class="caret"></b>
               </div>

               <script type="text/javascript">
               $(document).ready(function() {

                  var cb = function(start, end, label) {
                    console.log(start.toISOString(), end.toISOString(), label);
                    $('#reportrange span').html(start.format('DD, MMMM, YYYY') + ' - ' + end.format('DD, MMMM, YYYY'));
                    //alert("Callback has fired: [" + start.format('MMMM D, YYYY') + " to " + end.format('MMMM D, YYYY') + ", label = " + label + "]");
                  }

                  var optionSet1 = {
                    startDate: moment(),
                    endDate: moment(),
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
                        daysOfWeek: ['Lu', 'Ma', 'Me', 'Je', 'Ve', 'Sa','Di'],
                        monthNames: ['Janvier', 'Fevrier', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Aout', 'Septembre', 'Octobre', 'Novembre', 'Decembre'],
                        firstDay: 1
                    }
                  };

                  

                  $('#reportrange span').html(moment().format('D, MMMM ,YYYY') + ' - ' + moment().format('D, MMMM,YYYY'));

                  $('#reportrange').daterangepicker(optionSet1, cb);

                  $('#reportrange').on('show.daterangepicker', function() { console.log("show event fired"); });
                  $('#reportrange').on('hide.daterangepicker', function() { console.log("hide event fired"); });
                  $('#reportrange').on('apply.daterangepicker', function(ev, picker) { 
                    console.log("apply event fired, start/end dates are " 
                      + picker.startDate.format('DD MM, YYYY') 
                      + " to " 
                      + picker.endDate.format('DD MM, YYYY')                      
                    ); 
                    window.location.href = "bilans.php?date1="+picker.startDate.format('DD-MM-YYYY')+"&date2="+picker.endDate.format('DD-MM-YYYY');
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
               </script>

            

</div>
      </div>    

 
  
      </div>
      <hr />
<?php echo $_GET['date1'] ?>
<br>
<?php echo $_GET['date2'] ?>
<?php include "pied_bilan.php";?>
