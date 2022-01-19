<?php
date_default_timezone_set("Asia/Kolkata");
class Ini{
	private static function connectivity($db){
		$vars = trim(file_get_contents('../../7c6a180b36896a0a8c02787eeafb0e4'));
		$con = mysqli_connect("localhost","psodev",$vars,$db);
		/*if (mysqli_connect_errno())
		{
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}*/
		return $con;
	}
	
	private static $error = array();
	
	private static function checkValidity($time){
		$MonthsExtTime = strtotime("+90 days", strtotime($time));
		$crrDate = strtotime(date("Y-m-d H:i:s"));
		if($crrDate > $MonthsExtTime){
			return array("msg" => "Your Password Has Expired! Please Click On \"Forgot Password \" to Reset Your Credentials!", "status" => 0);
		}
		else{
			return array("msg" => "Make Login", "status" => 1);
		}
	}
	
	public static function getUsersSession($empid,$pass){
		$var = self::connectivity("checklist_portal");
		$kf=mysqli_query($var,"SELECT * FROM `register` where empid = '$empid' and  password = '$pass' and status='Active' ");
		$lk=mysqli_fetch_assoc($kf);
		$userExists=mysqli_num_rows($kf);
		$checkExtPass = self::checkValidity($lk['ins_date']);
		if($userExists == 0){
			return array('error'=>'0','msg'=>'Wrong Credentials! / User Blocked By PSO');
		}
		else{
			if($checkExtPass['status'] == 0){
				return array('error'=>'0','msg'=>$checkExtPass['msg']);
			}
			else{
				$_SESSION['name'] = ucwords(strtolower($lk['firstname'])).' '.$lk['lastname'][0];
				$_SESSION['multistat_id'] = $lk['empid'];
				//$_SESSION['admin_id'] = $lk['admin_id'];
				$_SESSION['multistat_pow'] = ( $lk['si_incharge'] == '' ) ? '3' : (( $lk['si_incharge'] == 'Active' ) ? '2' : '1');
				return array('error'=>'1','msg'=>'Press Enter to Continue ...');
			}	
		}
	}
	public static function geDetails($empid,$db){
		$var = self::connectivity("$db");
		$empQuery=mysqli_query($var,"SELECT * FROM $db.`register` where empid = '$empid'");
		$empDetails=mysqli_fetch_assoc($empQuery);
		$userExists=mysqli_num_rows($empQuery);
		if($userExists == 0){
			return array_merge(self::$error, array('query'=>'ERROR FETCHING DETAILS'));
		}
		else{
			return array_merge(self::$error, $empDetails);
		}
	}
	public static function geSesDet(){
		if($_SESSION['multistat_id'] != ''){
			//$xSess =  $_SESSION['multistat_id'];
			//$xSessPow =  $_SESSION['multistat_pow'];
			return array_merge(self::$error, array('error'=>'1','realSession'=>'multistat_id','realSessionPow'=>'multistat_pow'));
		}
		else{
			return array_merge(self::$error, array('error'=>'0','msg'=>'Error !! Session Expired ! Login to Continue'));
		}
	}
	public static function getregistered($db)
	{
		$var = self::connectivity($db);
		$username = $_POST['username'];
		$firstname = $_POST['firstname'];
		$lastname = $_POST['lastname'];
		$empid = $_POST['empid'];
		$pass = $_POST['password'];
		$ecnpass = md5($pass);
		$cnf = $_POST['cnfpass'];
		$email = $_POST['email'];
		if($db=='training_portal'){$arg1='power';$arg2='0';}
		else{$arg1='si_incharge';$arg2='Deactive';}
		
			if( $cnf == $pass){
				if(mysqli_num_rows(mysqli_query($var,"SELECT * FROM register WHERE empid = '$empid'") > 0)){
					return array_merge(self::$error, array('error'=>'0','msg'=>'Username Already Exists!'));
				}
				else{
					$result=mysqli_query($var,"INSERT INTO register (firstname, lastname, username, empid, password, status, email, $arg1, ins_date) VALUES ('$firstname', '$lastname', '$username', '$empid', '$ecnpass', 'Deactive',  '$email', '$arg2', NOW())");
					if($result){
						return array_merge(self::$error, array('error'=>'1','msg'=>'User Registered Succesfully !'));
					}	
				}
			}	
			else{
				return array_merge(self::$error, array('error'=>'2','msg'=>'Password Not Matched !'));
			}
	}
	public static function chgpassword($db)
	{
		$var = self::connectivity($db);
		$email=$_POST['email'];
		$empid=$_POST['empid'];
		$newpass=$_POST['newpass'];
		$new_pass=md5($newpass);
		$cnfpass=$_POST['cnfpass'];
		$querys = mysqli_query($var,"select * from register where empid='$empid'");
		$fetch_pass = mysqli_fetch_assoc($querys);
		$pre_pass = $fetch_pass['password'];
		if(mysqli_num_rows($querys) > 0){
			if($new_pass == $pre_pass){ 
				return array_merge(self::$error, array('error'=>'0','msg'=>'Your Old Password And New Password Should  Not Be same!! Please Change your password ! !'));
			}
			else{
				if($newpass == $cnfpass){ 
					$uname = $fetch_pass['username'];
					$sql = mysqli_query($var,"update register set password = '$new_pass',email='$email',ins_date=now() where empid='$empid'");
					return array_merge(self::$error, array('error'=>'1','msg'=>'Your Password Changed Sussessfully for UserName is  '.$empid.' !'));
				}
				else{
					return array_merge(self::$error, array('error'=>'0','msg'=>'Your Confirm And New Password do not Match ! !'));
				}
			}
		}
		else{
			return array_merge(self::$error, array('error'=>'2','msg'=>'Your Email ID Or Employee Id  do Not Match ! !'));
		}
		
	}
	public static function hashCode($flag){
		for($i=1;$i<7;$i++){
			$c = rand(111,999);
			$v .= $c;
		}
		return bin2hex($v.'..'.$flag);
	}
	public static function master_portal($empid){
		$var = self::connectivity('master_portal');
		$empidOverlook=explode("\n",trim(file_get_contents("https://".$_SERVER['HTTP_HOST']."/personal/includes/notMaster.txt"))); //file should be line separted at all times
		$sql = mysqli_query($var,"select * from main_form where emp_id='$empid'");
		$sql2 = mysqli_query($var,"select * from main_form where emp_id='$empid' and freeze = '0'");
		if(in_array($empid,$empidOverlook)){
			if($empid='111111'){
				echo "<script>sessionStorage.setItem('dcog','1');</script>";
				return array_merge(self::$error, array('error'=>1,'msg'=>'Welcome DCOG !! Press Enter to Continue !! Please Logout When Your Done !! '));
			}
			else{
				return array_merge(self::$error, array('error'=>1,'msg'=>'Press Enter to Continue...  Please Logout When Your Done !! '));
			}
		}
		elseif(mysqli_num_rows($sql) > 0 ){
			if(mysqli_num_rows($sql2) > 0){
				$_SESSION['profile'] = 1; 
				return array_merge(self::$error, array('error'=>0,"msg" =>'Press Enter to Continue...  Please Logout When Your Done !! ' ));
			}
			else{
				return array_merge(self::$error, array('error'=>1,'msg'=>'Press Enter to Continue...  Please Logout When Your Done !! '));
			}	
		}
		else{
			$_SESSION['profile'] = 1; 
			return array_merge(self::$error, array('error'=>0,"msg" =>'Press Enter to Continue...  Please Logout When Your Done !! ' ));
		}
	}
	
	public static function compoffAdd(){
		$var = self::connectivity('leaveportal');
		if(date('dm') == '0101')
		{
			$updatequery=mysqli_query($var,"update leave_master set comp_off='10'");
			//$updatequery=mysqli_query($var,"update leave_master set comp_off='10' where comp_off!='14'");
			if($updatequery){
				$msg= "success";
			}
			else{
				$msg= "Error";
			}
		}
		else{
			$msg= "Error";
		}
		return $msg;
	}
}	
//date_default_timezone_set("Asia/Kolkata");
?>
