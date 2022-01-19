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
				echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>'.$x['msg']."'); window.location.assign('../kedb/index.php')</script>";	
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
			echo '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong>'.$y['msg'].'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		}
		elseif($y['error'] == 1) {
			echo '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong>'.$y['msg'].'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
		}
		else{
			echo '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>'.$y['msg'].'</strong><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
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
  <title>Multistatus Portal | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="conso/plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="conso/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="conso/dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page" onload="createCaptcha();">
<div id="showError" class="modal" role="dialog">
  <div class="modal-dialog modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		 <h4 class="panel-heading modal-title "></h4>
	  </div>
	  <div class="modal-body text-center">
	  </div>
  </div>
 </div>
</div>
<div class="login-box">
  <div class="login-logo">
  <img class="round" src="images/tata.png" alt="tcs50 logo" width="200"/>
  <br>
    <a href="index.php"><b>Multistatus </b>Portal Register</a>
	
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Registration Form. <a href="login.php" class="btn btn-info text-center btn-sm">Already registered? Login here!</a></p>
		
      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" name="username" placeholder="Enter Username" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
          <input type="text" class="form-control" name="firstname" placeholder="Enter Firstname" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-id-card"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
          <input type="text" class="form-control" name="lastname" placeholder="Enter Lastname" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-address-card"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
          <input type="number" class="form-control" name="empid" placeholder="Enter Employee Id" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-id-badge"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
          <input type="email" class="form-control" name="email" placeholder="Enter SBI Mail Id or TCS mail id" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
		<div class="row">
		<div class="col-4"><img src="images/recaptcha.gif" width="60" height="60" /></div>
		<div style="background-color:white; color:red;" id="captcha" class="col-8"></div>
		</div>
		<div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Captcha" onkeyup="validateCaptcha()" id="captchaTextBox">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-retweet"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control" name="password" placeholder="Enter Password" required id="password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
          <input type="password" class="form-control" name="cnfpass" placeholder="Confirm New Password" required id="cnfpass">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
		<div class="input-group mb-3">
			<div class="alert" role="alert" id="message"></div>
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
			<input type="submit" name="register" style="display:none;" class="btn btn-primary submitshow" value="Register">
          </div>
          <!-- /.col -->
      <!--  </div>-->
      </form>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="conso/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="conso/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="conso/dist/js/adminlte.min.js"></script>

<script  type="text/javascript" src="js/jQuery-2.1.3.min.js"></script>
	<script src="js/bootstrap.min.js" type="text/javascript"></script>
	<!--<script src="js/hideCode.js" type="text/javascript"></script>-->
	<script type="text/javascript">
	//window.location = "under_construction/";
	$(document).ready(function(){
		//$('body').attr('oncontextmenu','return false;');
		if(navigator.userAgent.split('Chrome/')[1].split(".")[0] < "44" ) {
			var newStr = " <p>Log A ticket To Helpdesk Team  <a class='btn btn-success' href='https://itsm.sbi.co.in/arsys' target='_blank' >Help Desk</a> with your Desktop Credentials <p>";
			$("#showError").modal('show');
			$("#showError").find('.modal-content').css("background-color","#fff");
			$("#showError").find('.modal-title').css("color","red").text('!!Warning!!')
			$("#showError").find('.modal-body').html("<h1 style='color:#000'>Please Install Latest Version of Chrome Your Current version is"+navigator.userAgent.split('Chrome/')[1]+"</h1>"+newStr);
			 setTimeout(
				function(){
					window.location = 'https://itsm.sbi.co.in/arsys' 
				},
			5000);
		}
	})
	$("input[type='text']").each(function(){
		$(this).attr("autocomplete","off");
		//console.log("attribute changed");
	})
	function reload() {
		window.location.href=window.location.href;
	}
	var code;
	function createCaptcha() {
	  //clear the contents of captcha div first 
	  document.getElementById('captcha').innerHTML = "";
	  var charsArray =
	  "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ@!#$%^&*";
	  var lengthOtp = 6;
	  var captcha = [];
	  for (var i = 0; i < lengthOtp; i++) {
		//below code will not allow Repetition of Characters
		var index = Math.floor(Math.random() * charsArray.length + 1); //get the next character from the array
		if (captcha.indexOf(charsArray[index]) == -1)
		  captcha.push(charsArray[index]);
		else i--;
	  }
	  var canv = document.createElement("canvas");
	  canv.id = "captcha";
	  canv.width = 120;
	  canv.height = 50;
	  var ctx = canv.getContext("2d");
	  ctx.font = "25px Georgia";
	  ctx.strokeText(captcha.join(""), 0, 30);
	  //storing captcha so that can validate you can save it somewhere else according to your specific requirements
	  code = captcha.join("");
	  document.getElementById("captcha").appendChild(canv); // adds the canvas to the body element
	}
	function validateCaptcha(){
	  if (document.getElementById("captchaTextBox").value == code) {
		console.log("Valid Captcha");
		$('.submitshow').show();
	  }else{
		console.log("Invalid Captcha");
		$('.submitshow').hide();
	  }
	}
	$('#message').hide();
	$('#password, #cnfpass').on('keyup', function () {
  if ($('#password').val() == $('#cnfpass').val()) {
	 $('#message').removeClass("alert-danger");
    $('#message').html('Password Matching').addClass('alert-success');
  } else {
	  $('#message').show();
    $('#message').html('Password Not Matching').addClass('alert-danger');
  }
});
	</script>
</body>
</html>
