<!DOCTYPE html>
	<html>
  	<head>
    <title>Bill Analytics- BillAdvisor &copy</title>
    <link href="dashboard.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  </head>
  <a href ="logout.php" id="button" class="green left">Logout</a><a href ="dashboard.php" id="button" class="green right">Dashboard</a>
  <h1>BillAdvisor &copy</h1><h5>#1 billing tool since 2017</h5><br>
<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   include("configure.php");
   session_start();
   $errors=0;
   $submitErr=$IDerr='';
if(isset($_SESSION['username'])&&!empty($_SESSION['username']))
{ 
  $submitErr=$dateErr='';
  if(isset($_POST['submit'])){
    $date=$_POST["date"];
    $username=$_SESSION['username'];
   if (!preg_match("/^\d{1,2}\/\d{1,2}\/\d{4}$/",$date)) 
  {
  $dateErr="Invalid Date Format";
  $errors++;
  }
  else{
    $datearr= explode('/', $date);
  if (!checkdate($datearr[1], $datearr[0], $datearr[2])) {
  $dateErr="Invalid Date";
  $errors++;
  }
  }
    if(empty($date))
    {$dateErr="Date Cannot be Empty";
    $errors++;}
if($errors==0)
{ 
  $string='';
  $totalin=$totalout=0;
  $query=$conn->prepare("SELECT * FROM bills WHERE date=? AND username=?");
  $query->bind_param("ss",$date,$username);
  $query->execute();
  $result=$query->get_result();
  if(mysqli_num_rows($result)>0){
  $string.="[['Bill ID', 'Inflow', 'Outflow'],";
  while($rows=mysqli_fetch_array($result))
  {
    $billid=$rows["billid"];
    $inflow=$rows["inflow"];
    $outflow=$rows["outflow"];
    $date=$rows["date"];
    $string.="['{$billid}', {$inflow}, {$outflow}],";
    $totalin+=$inflow;
    $totalout+=$outflow;
  }
  $string.="['Total', {$totalin}, {$totalout}]]";
  echo "<div id=\"chart_div\"></div><script>
  google.charts.load('current', {packages: ['corechart', 'bar']});
google.charts.setOnLoadCallback(drawAxisTickColors);
  function drawAxisTickColors() {
      var data = google.visualization.arrayToDataTable(".$string.");
        var options = {
        title: 'Analysis of bills on {$date}',
        chartArea: {width: '50%'},
        hAxis: {
          title: 'Amount',
          minValue: 0,
          textStyle: {
            bold: true,
            fontSize: 12,
            color: '#4d4d4d'
          },
          titleTextStyle: {
            bold: true,
            fontSize: 18,
            color: '#4d4d4d'
          }
        },
        vAxis: {
          title: 'Bill ID',
          textStyle: {
            fontSize: 14,
            bold: true,
            color: '#848484'
          },
          titleTextStyle: {
            fontSize: 14,
            bold: true,
            color: '#848484'
          }
        }
      };
      var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    }
  </script>";
  }
  else
    $submitErr="You do not have any bills on the specified date";
  }
}
  echo "<h2 class=\"space\">Analyze the expenses for a Date</h2><span class=\"error\">";echo $submitErr;echo"</span><form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
  <label>Date:<span style=\"color:red\">*</span><input type = \"text\" name = \"date\"/></label><br><span class=\"error\">";echo $dateErr;echo"</span><br>
  <input id=\"button\"class=\"red\" type =\"submit\" class=\"red\" name=\"submit\" value = \"View Analytics\"/><br>
  </form>";
}
else
  echo "<body><h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
?>