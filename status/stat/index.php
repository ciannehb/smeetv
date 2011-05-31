<?
    require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    connect2db();
   
    $query="select num from stat order by id desc limit 0,6";
    $go=mysql_query($query);
    for($i=0;$i<mysql_num_rows($go);$i++) {
        $get=mysql_fetch_array($go);
        $arr[]=$get[0];
    }



    $f=$arr[0]-$arr[1];
    $s=$arr[1]-$arr[2];
    $t=$arr[2]-$arr[3];
    $ff=$arr[3]-$arr[4];
    $fff=$arr[4]-$arr[5];



?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>
    </title>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'x');
        data.addColumn('number', 'Pics');
      
        data.addRow(["2 1/2", <?=$fff?>]);
        data.addRow(["2 hr", <?=$ff?>]);
        data.addRow(["1 1/2 hr", <?=$t?>]);
        data.addRow(["1 hr", <?=$s?>]);
        data.addRow(["30 min", <?=$f?>]);
      
        // Create and draw the visualization.
        new google.visualization.LineChart(document.getElementById('visualization')).
            draw(data, {curveType: "function",
                        width: 400, height: 200,
                        }
                );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>
  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="width: 500px; height: 400px;"></div>
  </body>
</html>


