<!DOCTYPE html>
	<html>
  	<head>
    <title>View Bills- BillAdvisor &copy</title>
    <link href="dashboard.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
  <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Satisfy" rel="stylesheet">
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
  if(isset($_POST['submit'])){
  $billid=$_POST['billid'];
  $username=$_SESSION['username'];
  if(empty($billid))
    {$IDerr="Bill ID should not be empty";
     $errors++;}
  if(!preg_match('/^[0-9#\-]*$/',$billid))
    {
      $IDerr="Only numbers,# and - allowed";
      $errors++;
    }
  if($errors==0){
  $query=$conn->prepare("SELECT * FROM bills WHERE billid=? AND username=?");
  $query->bind_param("ss",$billid,$username);
  $query->execute();
  $result=$query->get_result();
  if(mysqli_num_rows($result)>0){
  while($rows=mysqli_fetch_array($result))
  {
    echo "<div class=\"box\"><h1>Bill Number {$rows["billid"]}</h1><br>
    <h2>Dated {$rows["date"]}</h2>
    <p> <span style=\"font-weight:bold\">Inflow:</span> {$rows["inflow"]} </p>
    <p> <span style=\"font-weight:bold\">Outflow:</span>{$rows["outflow"]} </p>
    <p> <span style=\"font-weight:bold\">Remarks:</span> {$rows["remarks"]}</p>
    </div>";
  }
  }
  else
    $submitErr="Either you're trying to view another client's bill or the bill doesn't exist";
  }
}
  echo "<h2 class=\"space\">Wanna view journals?</h2><span class=\"error\">";echo $submitErr;echo"</span><form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
  <label>Bill ID:<span style=\"color:red\">*</span><input type = \"text\" name = \"billid\"/></label><br><span class=\"error\">";echo $IDerr;echo"</span><br>
  <input id=\"button\"class=\"red\" type =\"submit\" class=\"red\" name=\"submit\" value = \"View Bill\"/><br>
  </form>";
}
else
  echo "<body><h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
?>