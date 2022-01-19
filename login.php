<?php
error_reporting(1);
session_start();
include("config.php");
include("includes/ini.main.php");
if(isset($_SESSION[Ini::geSesDet()['realSession']])){
	if($_GET['status'] != '' ){
		header("location:conso/index.php?status=".$_GET['status']);
	}
	else{
		header("location:conso/index.php");
	}
}
else
{
	if(isset($_POST['sub_multi'])){
		$x = Ini::getUsersSession($_POST['username'],md5($_POST['Password'])); 
		if($x['error'] == 0){ 
			//echo "<script>alert('".$x['msg']."')</script>";
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>'.$x['msg'].'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		}
		else{
			if($_GET['status'] == 'feedback'){
				echo "<script>alert('".$x['msg']."'); window.location.assign('../kedb/index.php')</script>";	
			}
			elseif($_GET['status'] == 'master_portal')
			{
				echo "<script>alert('".$x['msg']."'); window.location.assign('../master_portal/index.php')</script>";
			}
			else{
				if(Ini::master_portal($_POST['username'])['error'] == 0){
					echo "<script>alert('".Ini::master_portal($_POST['username'])['msg']."');window.location.assign(window.location.origin+'/master_portal/index.php');</script>";
				}
				else{
					//Ultimate Success Condition logging IN
					$username=ucwords($_SESSION['name']);
					$empid=$_SESSION['multistat_id'];
					$ip_sys=$_SERVER['REMOTE_ADDR'];
					mysql_query("INSERT INTO master_portal.login_details (ip_sys,name,empid,login_time,ins_date) VALUES ('$ip_sys','$username','$empid',NOW(),NOW())");
				
					if($_GET['status'] != '' ){//URL redirection if required like ULTIMATIX 
						header("location:conso/index.php?status=".$_GET['status']);
					}
					else{//NORMAL login most of the cases
						echo "<script>window.location.assign('conso/index.php')</script>";
					}
					//echo "<script>alert('".Ini::master_portal($_POST['username'])['msg']."'); window.location.assign('conso/index.php?status='".$_GET['status']."')</script>";
				}
				//header("location:conso/index.php");
			}
		}
	}
	if (isset($_POST['chng_submit'])) 
	{
		$z = Ini::chgpassword("checklist_portal");
		//$z = Ini::chgpassword("assigned_task");
		//$z = Ini::chgpassword("leaveportal");
			echo "<script>alert('".$z['msg']."')</script>";
	}
	
	if(isset($_POST['register']))
	{
		$y = Ini::getregistered("checklist_portal");
		$y = Ini::getregistered("assigned_task");
		$y = Ini::getregistered("crmd_live");
		$y = Ini::getregistered("training_portal");
		//$y = Ini::getregistered("leaveportal");
		$y = Ini::getregistered("saifreports");
		$y = Ini::getregistered("ofsaa");
		$y = Ini::getregistered("handover_test");
		$y = Ini::getregistered("cab_portal");
		if($y['error'] == 0){ 
			echo "<script>alert('".$y['msg']."')</script>";
		}
		elseif($y['error'] == 1) {
			echo "<script>alert('".$y['msg']."')</script>";
		}
		else{
			echo "<script>alert('".$y['msg']."')</script>";
		}
	}
	//for leaveportal to assign or update compoff 
	//Ini::compoffAdd();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Multistatus Portal | Login</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="conso/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="conso/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="conso/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <style>
	.tcsimg
	{
		position: absolute;
		left: 0px;
		top: 0px;
		z-index: -1;
		width:auto;
		height:100%;
	}
	.tataimg
	{
		position: absolute;
		left: 10%;
		top: 10%;
		z-index: -1;
	}
	@media only screen and (max-width: 996px) {
		.tcsimg {
			display:none;
		}
		.tataimg
		{
			position: inherit;
			z-index: -1;
	  }
	}
	.login-page
	{
		margin-left:20%;
	}
  </style>
</head>
<body class="hold-transition login-page">
<img src="images/tcsweb.png" class="img-fluid float-left tcsimg" alt="Responsive image" style="position:absolute">
<div class="login-box">
  <div class="login-logo">
  <img class="img-fluid float-left tataimg" alt="Responsive image" src="images/tata.png" alt="tcs50 logo"/>
	<img src="images/m1.png" width="100" height="50" class="img-fluid"><br>
    <a href="index.php"><b>Multistatus </b>Portal Login</a>
	
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Sign in to start your session</p>
		
      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="user form-control" name="username" placeholder="Employee Id" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control lock" name="Password" placeholder="Password" required autofocus>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <!--<div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>-->
          <!-- /.col -->
		  <div class="col-5">
			<input type="submit" name="sub_multi" class="btn btn-primary btn-block logingrad" value="Login">
          </div>
          <!-- /.col -->
      <!--  </div>-->
      </form>
	  <br>
	  <br>
		<div class="row">
		<p class="offset-2"></p>
      <p class="mb-1 col-5">
        <a href="#" data-toggle="modal"  data-target="#myModall">I forgot my <br>password</a>
      </p>
      <p class="mb-0 col-5 text-center">
        <a href="register.php" class="text-center" onclick="createCaptcha()">Create new account</a>
      </p>
	  </div>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<div id="myModall" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		 <h4 style="color:black" class="panel-heading modal-title">Change Password</h4>
	  </div>
	  <div class="modal-body text-center">
		<form method="post">
			<input type="text" class="form-control" name="empid" placeholder="Enter Employee Id" required>	<br>
			<input type="email" class="form-control" name="email" placeholder="Enter Your TCS/SBI Mail Id" required>	<br>
			<input type="password" class="form-control" name="newpass" placeholder="Enter New Password" required>	<br>
			<input type="password" class="form-control" name="cnfpass" placeholder="Confirm New Password" required>	<br>
	  </div>
	  <div class="modal-footer">
		<div class="alert" role="alert" id="message"></div>
		<input type="submit" name="chng_submit" class="btn btn-success" value="Submit"></form>
		<button type="button" class="btn btn-danger" onclick="reload()" data-dismiss="modal">Close</button>
	  </div>
	</div>
<!-- jQuery -->
<script src="conso/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="conso/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="conso/dist/js/adminlte.min.js"></script>
</body>
</html>
