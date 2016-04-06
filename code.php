<!DOCTYPE html>
<html>
<head>
	<title>2 step Verification</title>
	 <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="code.css">
 
</head>
<body>
 <script src="smssend.js"></script>
	<?php

		$ip=$_SERVER['REMOTE_ADDR'];
		//verify cookiees
		if(!isset($_COOKIE["usr"]) || !isset($_COOKIE["pho"])){
			header("Location: http://localhost/cey2wayAutontication/login.php");
		}
		else{
				//check truested pc or not
				$uid=$_COOKIE["usr"];
				$phoneNo=$_COOKIE["pho"];		
    				
    			
    		}

				
	?>


	<div class="row" >
		<div class="col-sm-12">

			<div class="row" >
				<div class="col-sm-12">
					<h2 class="headber">2 step Verification</h2>
				</div>
			</div>
			<div class="row" >
				<div class="col-sm-12">
					<div class="col-sm-1">

					</div>
					<div class="col-sm-5">

						<div class="box">
							
							<h4>Enter the verification code sent to your phone number ending in <?php echo substr($phoneNo, 10); ?></h4>

							<form action="" method="POST">
								<p><label class="labelfo">Enter Code</label><input type="text" class="valuecode" required="" name="scode" autocomplete="off"></input><input type="submit" class="btncode" name="codesubmit"  onmouseup="teest()"></input></p>
								<p><input type="checkbox" name="trustcom" value="true"> Trust this computer</input></p>
								<p>We won't ask you for a code again when we recognize one of your truested computers <a href="#">learn more</a></p>
							</form>

							<p><form action="" method="POST"><button style="margin-right: 20px; background-color: transparent;border: none; color:#2e8ece; " name="resend" >Resend Code</button> <a href="http://localhost/cey2wayAutontication/login.php">Go back to login page</a> </form> </p>


							<?php


							if(isset($_POST['codesubmit'])){

								
								$subCode=$_POST['scode'];
								$tru=isset($_POST['trustcom']);
								//echo "ip = $ip  ";echo "id= $uid <br>  tru=$tru ";
								//getcode
								include 'connect.php';
								$stmt2 = $conn->prepare("SELECT `code` FROM `sms` WHERE userid='$uid' and ip='$ip' and useage='notused' ORDER BY smsid DESC LIMIT 1");
								$stmt2->execute();
    							$re2=$stmt2->fetchAll(); 
    							//var_dump($re2);

    							$dbcode=$re2[0]['code'];
    							
    							//echo  "db=$dbcode";
    							//echo  "sub=$subCode";
    							//action
    							if(!is_numeric($subCode)){
    								echo "Not valied Format";
    							}
    							else if($subCode==$dbcode && $tru=='1'){

    								//update database
    								
    								$supdateusage2 = "UPDATE sms SET useage='used' , trust='Yes' WHERE userid='$uid' and ip='$ip' ORDER BY smsid DESC LIMIT 1";
									$conn->query($supdateusage2); 							
    								//echo "sdfasdfasdf";
    								header("Location: http://www.lawenvoy.com");

    							}
    							else if($subCode==$dbcode){

    								//update database
    								$supdateusage = "UPDATE sms SET useage='used' WHERE userid='$uid' and ip='$ip' ORDER BY smsid DESC LIMIT 1";
									$conn->query($supdateusage);
    								header("Location: http://www.lawenvoy.com");
    								//echo "go wrong";
    							}
    							else if($subCode!=$dbcode){
    								echo "Wrong code. Try again. ";
    							}
    							    							
    							else{
    								echo "The code you entered had an incorrect number of digits.";
    							}

							}



							if(isset($_POST['resend'])){
								$uid=$_COOKIE["usr"];
								$phoneNo=$_COOKIE["pho"];									
								$ip=$_SERVER['REMOTE_ADDR'];

								include 'connect.php';
								$stmt2 = $conn->prepare("SELECT code  FROM `sms` WHERE userid='$uid' and ip='$ip' and `useage`='notused' ORDER BY smsid DESC LIMIT 1");
								$stmt2->execute();
								 $re2=$stmt2->fetchAll(); 	//var_dump($re2);

								$dbres=$re2[0]['code'];
								    			
								    			//send sms
								$sms2="sudo gammu sendsms TEXT $phoneNo -text '$dbres is your LawEnvoy verification code'";
								$res=exec($sms2);
							}

							?>
				
						</div>

					</div>
					<div class="col-sm-5">
						<img src="mobile-marketing3.png" style="width: 100%;height: auto;">
					</div>


					<div class="col-sm-1">
					</div>
				</div>
			</div>
			<div class="row" >
				<div class="col-sm-12">

				</div>
			</div>

		</div>
	</div>
</body>


</html>

