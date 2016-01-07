<html>
  <head>


    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>DataTables example - Zero configuration</title>

    
    
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.10/css/jquery.dataTables.min.css">
    <style type="text/css" class="init">
    
    </style>


   
   <script src="../js/jquery-2.0.3.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script type="text/javascript" language="javascript" src="../js/jquery.dataTables.min.js">
    </script>

   
    <script type="text/javascript" class="init">
    

$(document).ready(function() {
    $('#example').DataTable();
} );


    </script>

  </head>
<body>
<table id="example" class="display">
    <thead>
        <tr>
            <th>Column 1</th>
            <th>Column 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Row 1 Data 1</td>
            <td>Row 1 Data 2</td>
        </tr>
        <tr>
            <td>Row 2 Data 1</td>
            <td>Row 2 Data 2</td>
        </tr>
    </tbody>
</table>

</body>
</html>