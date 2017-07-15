<?php
	include("configure.php");
	$user = $_REQUEST["q"];
	$query="SELECT * FROM users WHERE username='".$user."'";
	$qresult=mysqli_query($conn,$query);
	if (!preg_match("/^[a-z0-9_.]*$/",$user)) 
	{
	echo "Only lower alphanumeric, _ and . allowed";
	}
	else if(mysqli_num_rows($qresult)>0)
	{	echo "Username already exists";
		$usernameErr=1;
	}
	else
	{echo "Username can be used";
	$usernameErr=0;
	}
?>
