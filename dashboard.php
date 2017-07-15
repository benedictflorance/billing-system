<!DOCTYPE html>
	<html>
  	<head>
    <title>Dashboard - BillAdvisor &copy</title>
    <link href="dashboard.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'></head>
<?php
   ini_set('display_errors', 1); 
   ini_set('log_errors',1); 
   error_reporting(E_ALL); 
   mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
   include("configure.php");
  session_start();
  $submitErr=$incomeErr=$dateErr=$outcomeErr=$IDerr=$remarksErr='';
if(isset($_SESSION['username'])&&!empty($_SESSION['username']))
{ 
  if(isset($_POST['submit'])){
    $errors=0;
    $date=$_POST['date'];
    $inflow=$_POST["inflow"];
    $outflow=$_POST["outflow"];
    $billid=$_POST["billid"];
    $remarks=$_POST["remarks"];
    $username=$_SESSION['username'];
    if(!preg_match('/^[0-9#\-]*$/',$billid))
    {
      $IDerr="Only numbers,# and - allowed";
      $errors++;
    }
    if(!preg_match('/^[0-9]*$/',$inflow))
    {
      $incomeErr="Invalid Amount";
      $errors++;
    }
    if(!preg_match('/^[0-9]*$/',$outflow))
    {
      $outcomeErr="Only numbers,# and - allowed";
      $errors++;
    }
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
     if(empty($inflow))
    {$incomeErr="Inflow should not be empty";
     $errors++;}
     if(empty($outflow))
    {$outcomeErr="Outflow should not be empty";
     $errors++;}
     if(empty($billid))
    {$IDerr="Bill ID should not be empty";
     $errors++;}
     if(!empty($inflow)&&!empty($outflow))
     {
    if($outflow>$inflow)
    {$outcomeErr="Outflow cannot be greater than inflow";
     $errors++;}
     }
  if($errors==0)
  {   try{
  $sql =$conn->prepare("INSERT INTO bills(username,date,inflow,outflow,billid,remarks) VALUES(?,?,?,?,?,?)");
  $sql->bind_param("ssssss",$username,$date,$inflow,$outflow,$billid,$remarks);
  $result=$sql->execute();
  $submitErr="Bill {$billid} added successfully!";
  }
    catch(mysqli_sql_exception $e){
    $IDerr="Bill already exists";
    }
  }
  }
  echo "<a href =\"logout.php\" id=\"button\" class=\"green left\">Logout</a><a href =\"view.php\" id=\"button\" class=\"green\">View your Bills</a><a href =\"graph.php\" id=\"button\" class=\"green right\">Analytics</a>
  <h1>BillAdvisor &copy</h1><h5>#1 billing tool since 2017</h5><br><h2 >Welcome, ".ucwords($_SESSION['name'])."!</h2><h2>Add a new bill!</h2>
  <form action=\"";echo htmlentities($_SERVER["PHP_SELF"]);echo "\" method=\"post\">
  <span class=\"success\">";echo $submitErr;echo "</span><br>
  <span style=\"color:red\">All * fields are mandatory</span><br>
  <label>Date:<span style=\"color:red\">*</span><input type = \"text\" name = \"date\"/></label><br><span class=\"error\">";echo $dateErr;echo"</span><br>
  <label>Inflow:<span style=\"color:red\">*</span><input type = \"text\" name = \"inflow\"/></label><br><span class=\"error\">";echo $incomeErr;echo"</span><br>
  <label>Outflow:<span style=\"color:red\">*</span><input type = \"text\" name = \"outflow\"/></label><br><span class=\"error\">";echo $outcomeErr;echo"</span><br>
  <label>Bill ID:<span style=\"color:red\">*</span><input type = \"text\" name = \"billid\"/></label><br><span class=\"error\">";echo $IDerr;echo"</span><br>
  Remarks? (if any)
  <textarea spellcheck=\"false\" onkeyup=\"this.style.height='24px'; this.style.height = this.scrollHeight + 12 + 'px';\" name=\"remarks\"></textarea><br>
  <span class=\"error\">";echo $remarksErr;echo"</span><br>
  <input id=\"button\"class=\"red\" type =\"submit\" class=\"red\" name=\"submit\" value = \"Add Bill\"/><br>
  </form><br>";
}
else
  echo "</head><body><h1>Access Denied</h2><br><a id=\"button\" class=\"green\" href=\"login.php\">Click here to log in</a></div></div></body></html>";
?>
