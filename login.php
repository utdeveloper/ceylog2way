<!DOCTYPE html>


<html>
<head>
	<title>LawEnvoy Login</title>
	 <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="login.css">



</head>
<body>


	<div class="row"  style="text-align: center ;">
		<div class="col-sm-12">
			<div class="col-sm-4">

			</div>

			<div class="col-sm-4">
				<div class="box">
						<div class="row">
							<h1>Login</h1>
						</div>

						<div class="row">
							<img src="no-user-image.png" style="width: 40%; margin-left: auto; margin-right: auto;border-radius:100px;">
						</div>
						<div class="row">
							<form class="lgform" method="post" action="">
								<p><label class="labe" style="text-align: left;">User Name</label><input class="value" type="text" name="uname" required=""></input></p>
								<p><label class="labe" style="text-align: left;">Password</label><input class="value" type="password" name="psaa" required=""></input></p>

								<p><input type="submit" name="loginbtn" class="btn"></input></p>
								<a href="#" style="color: white;">Forget password</a>

							</form>
							<?php
	
								if (isset($_POST['loginbtn'])) {

									include 'connect.php';
									$uname=$_POST['uname'];
									$upass=$_POST['psaa'];
									
									$stmt = $conn->prepare("SELECT count(userid) as no  FROM user where username='$uname' and password='$upass'");
									$stmt->execute();

    								$re=$stmt->fetchAll();    						
									//var_dump($re);
									//var_dump($re[0]['no']);

									if($re[0]['no']==1){

										//get user phone no
										$stmt2 = $conn->prepare("SELECT Phone, userid FROM user where username='$uname' and password='$upass'");
										$stmt2->execute();
    									$re=$stmt2->fetchAll(); 
    									$phoneNo=$re[0]['Phone'];
    									//uid
    									$uid=$re[0]['userid'];
    									//echo $uid;
    									//generate code 
    									$code=rand(111111,999999);

    									//get ip
    									$ip=$_SERVER['REMOTE_ADDR'];
    									//echo $ip;

    									//sms code
										$sms="sudo gammu sendsms TEXT $phoneNo -text '$code is your LawEnvoy verification code'";
										$res=exec($sms);
										
										//Sending SMS 1/1....waiting for network answer..OK, message reference=118

										//echo substr("Sending SMS 1/1....waiting for network answer..OK, message reference=118Message send sucessfully",72);

										
										if(substr("$res",0,49) == "Sending SMS 1/1....waiting for network answer..OK"){
											//echo "Message send sucessfully";
											

											//update database
											$smsdb = "INSERT INTO sms (userid, ip, code)VALUES ('$uid', '$ip', '$code')";
											$conn->query($smsdb);

											//redirect to code page
											header("Location: http://localhost/cey2wayAutontication/code.php");
										 }
										 else {

										 	echo  "Message not send <br> $res"  ;


										 }
									}
									else{
										echo  "Wrong User name & Password combination";
									}

								}

								?>


							
						</div>
							
				</div>
			</div>

			<div class="col-sm-4">
							<div id="message" >
							  <p id="demo">No User Found</p>
							</div>
			</div>		
		</div>
	</div>
</body>
			<script type="text/javascript">
				public function clear(){
					document.getElementById("demo").style.visibility="visible";
				}
			</script>				
</html>





