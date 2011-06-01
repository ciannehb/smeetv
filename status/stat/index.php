<?
    require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    connect2db();
   
    $query="select num from stat order by id desc limit 0,9";
    $go=mysql_query($query);
    for($i=0;$i<mysql_num_rows($go);$i++) {
        $get=mysql_fetch_array($go);
        $arr[]=$get[0];
    }



    $n1=$arr[0]-$arr[1];
    $n2=$arr[1]-$arr[2];
    $n3=$arr[2]-$arr[3];
    $n4=$arr[3]-$arr[4];
    $n5=$arr[4]-$arr[5];
    $n6=$arr[5]-$arr[6];
    $n7=$arr[6]-$arr[7];
    $n8=$arr[7]-$arr[8];



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
      

        data.addRow(["4 hr", <?=$n8?>]);
        data.addRow(["3.5 hr", <?=$n7?>]);
        data.addRow(["3 hr", <?=$n6?>]);
        data.addRow(["2.5 hr", <?=$n5?>]);
        data.addRow(["2 hr", <?=$n4?>]);
        data.addRow(["1.5 hr", <?=$n3?>]);
        data.addRow(["1 hr", <?=$n2?>]);
        data.addRow(["30 min", <?=$n1?>]);
      
        // Create and draw the visualization.
        new google.visualization.LineChart(document.getElementById('visualization')).
            draw(data, {curveType: "function",
                        width: 500, height: 180,
                        }
                );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-22892692-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>


  </head>
  <body style="font-family: Arial;border: 0 none;">
    <div id="visualization" style="width: 500px; height: 400px;"></div>
  </body>
</html>


