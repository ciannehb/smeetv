<?
    require_once($_SERVER["DOCUMENT_ROOT"].'/smeetv/func.php');
    $smeetvdb=connect2db();
   
    $query="select num from stat order by id desc limit 0,9";
    $go=mysql_query($query);
    for($i=0;$i<mysql_num_rows($go);$i++) {
        $get=mysql_fetch_array($go);
        $arr[]=$get[0];
    }


    $back_24=time()-100800; // 86400 == 24 hrs ; 14400 + 4 hrs more back
    $back_24p=$back_24+100;

    $back_48=time()-187200; // + 4 hrs more back
    $back_48p=$back_48+100;


    $back_1week=time()-590400;
    $back_1weekp=$back_1week+100;

    $query="select num from stat where timestamp < $back_24 and timestamp < $back_24p order by id desc limit 0,9";
    $go=mysql_query($query);
    for($i=0;$i<mysql_num_rows($go);$i++) {
        $get=mysql_fetch_array($go);
        $arr2[]=$get[0];
    }

    $query="select num from stat where timestamp < $back_48 and timestamp < $back_48p order by id desc limit 0,9";
    $go=mysql_query($query);
    for($i=0;$i<mysql_num_rows($go);$i++) {
        $get=mysql_fetch_array($go);
        $arr3[]=$get[0];
    }


    $query="select num from stat where timestamp < $back_1week and timestamp < $back_1weekp order by id desc limit 0,9";
    $go=mysql_query($query);
    for($i=0;$i<mysql_num_rows($go);$i++) {
        $get=mysql_fetch_array($go);
        $arr4[]=$get[0];
    }



    $n1=$arr[0]-$arr[1];
    $n2=$arr[1]-$arr[2];
    $n3=$arr[2]-$arr[3];
    $n4=$arr[3]-$arr[4];
    $n5=$arr[4]-$arr[5];
    $n6=$arr[5]-$arr[6];
    $n7=$arr[6]-$arr[7];
    $n8=$arr[7]-$arr[8];

    $f1=$arr2[0]-$arr2[1];
    $f2=$arr2[1]-$arr2[2];
    $f3=$arr2[2]-$arr2[3];
    $f4=$arr2[3]-$arr2[4];
    $f5=$arr2[4]-$arr2[5];
    $f6=$arr2[5]-$arr2[6];
    $f7=$arr2[6]-$arr2[7];
    $f8=$arr2[7]-$arr2[8];

    $e1=$arr3[0]-$arr3[1];
    $e2=$arr3[1]-$arr3[2];
    $e3=$arr3[2]-$arr3[3];
    $e4=$arr3[3]-$arr3[4];
    $e5=$arr3[4]-$arr3[5];
    $e6=$arr3[5]-$arr3[6];
    $e7=$arr3[6]-$arr3[7];
    $e8=$arr3[7]-$arr3[8];


    $g1=$arr4[0]-$arr4[1];
    $g2=$arr4[1]-$arr4[2];
    $g3=$arr4[2]-$arr4[3];
    $g4=$arr4[3]-$arr4[4];
    $g5=$arr4[4]-$arr4[5];
    $g6=$arr4[5]-$arr4[6];
    $g7=$arr4[6]-$arr4[7];
    $g8=$arr4[7]-$arr4[8];




$output="

<html xmlns=\"http://www.w3.org/1999/xhtml\">
  <head>
    <meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\"/>
    <title>
    </title>
    <script type=\"text/javascript\" src=\"http://www.google.com/jsapi\"></script>
    <script type=\"text/javascript\">
      google.load('visualization', '1', {packages: ['corechart']});
    </script>
    <script type=\"text/javascript\">
      function drawVisualization() {
        // Create and populate the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'x');
        data.addColumn('number', 'Pics');
        data.addColumn('number', '-24hr');
        data.addColumn('number', '-48hr');
        data.addColumn('number', '-1wk');
      

        data.addRow([\"4 hr\", $n8, $f8, $e8, $g8]);
        data.addRow([\"3.5 hr\", $n7, $f7, $e7, $g7]);
        data.addRow([\"3 hr\", $n6, $f6, $e6, $g6]);
        data.addRow([\"2.5 hr\", $n5, $f5, $e5, $g5]);
        data.addRow([\"2 hr\", $n4, $f4, $e4, $g4]);
        data.addRow([\"1.5 hr\", $n3, $f3, $e3, $g3]);
        data.addRow([\"1 hr\", $n2, $f2, $e2, $g2]);
        data.addRow([\"30 min\", $n1, $f1, $e1, $g1]);
      
        // Create and draw the visualization.
        new google.visualization.LineChart(document.getElementById('visualization')).
            draw(data, {curveType: \"function\",
                        width: 500, height: 180,
                        }
                );
      }
      

      google.setOnLoadCallback(drawVisualization);
    </script>

<script type=\"text/javascript\">

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
  <body style=\"font-family: Arial;border: 0 none;\">
    <div id=\"visualization\" style=\"width: 500px; height: 400px;\"></div>
  </body>
</html>
";


$f = fopen("_incl.txt", "w");
fwrite($f, $output);
fclose($f);
disconnectFromDb($smeetvdb);


?>

