<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PAYMENT RESPONSE PANEL</title>
    <?php require('inc/links.php'); 
	 	require('admin/inc/scripts.php'); ?>
</head>
<body class="bg-light">
	<script>
		
	setTimeout(function () {
   		window.location.href= 'index.php'; // the redirect goes here

	},6000);
	</script>
<?php require('inc/header.php'); ?>
<?php

	// following files need to be included
	require_once("./lib/config_paytm.php");
	require_once("./lib/encdec_paytm.php");

	$sqlxd = "SELECT * FROM `sessions` LIMIT 1";
	$con = $GLOBALS['con'];
	if($stmt = mysqli_prepare($con,$sqlxd))
	{
		if(mysqli_stmt_execute($stmt)){
			$ufetch = mysqli_stmt_get_result($stmt);
			mysqli_stmt_close($stmt);
			$u_res = mysqli_fetch_assoc($ufetch);	
			
			$u_exist = select("SELECT * FROM `user_cred` WHERE `id`=? LIMIT 1 ",
			[$u_res['userID']],"i");
			
			if(mysqli_num_rows($u_exist)==0){
				echo 'inv_email_mob';
			}
			else{
				$u_fetch = mysqli_fetch_assoc($u_exist);
			
						$_SESSION['login'] = true;
						$_SESSION['uId'] = $u_fetch['id'];
						$_SESSION['uName'] = $u_fetch['name'];
						$_SESSION['uPic'] = $u_fetch['profile'];
						$_SESSION['uPhone'] = $u_fetch['phonenum'];
						$_SESSION['uEmail']= $u_fetch['email'];
						$_SESSION['uStartDate'] = $u_res['start_date'];
						$_SESSION['uEndDate'] = $u_res['end_date'];
						$_SESSION['totalAmount'] = $u_res['total_amount'];
						$_SESSION['roomId'] = $u_res['room_id'];	
						
					
			}
			
			$del_session = delete("DELETE FROM `sessions` WHERE `id`=?",[$u_res['id']],'i');

		}
		else{
			mysqli_stmt_close($stmt);
			die("Query cannot be executed - Select");
		}


		
	}
	else{
		echo "ERROR";
	}




	$paytmChecksum = "";
	$paramList = array();
	$isValidChecksum = "FALSE";

	$paramList = $_POST;


	$paytmChecksum = isset($_POST["CHECKSUMHASH"]) ? $_POST["CHECKSUMHASH"] : ""; //Sent by Paytm pg

	//Verify all parameters received from Paytm pg to your application. Like MID received from paytm pg is same as your application�s MID, TXN_AMOUNT and ORDER_ID are same as what was sent by you to Paytm PG for initiating transaction etc.
	$isValidChecksum = verifychecksum_e($paramList, PAYTM_MERCHANT_KEY, $paytmChecksum); //will return TRUE or FALSE string.


	if($isValidChecksum == "TRUE") {
		echo "<div class='container-fluid px-lg-4 mt-4' style='position:relative;left: 500px;'>";
		echo "<b>Checksum matched and following are the transaction details:</b>" . "<br/>";
		if ($_POST["STATUS"] == "TXN_SUCCESS") {
			echo "<b>Transaction status is success</b>" . "<br/>";
			//Process your transaction here as success transaction.
			//Verify amount & order id received from Payment gateway with your application's order id and amount.

			$q1 = "INSERT INTO `bookings`(`room_id`, `user_id`, `start_date`, `end_date`, `total_amount`) VALUES (?,?,?,?,?)";
			$values = [$_SESSION['roomId'],$_SESSION['uId'],$_SESSION['uStartDate'],$_SESSION['uEndDate'],$_SESSION['totalAmount']];
			
			insert($q1,$values,'iissi');


			$res1 = select("SELECT * FROM `rooms` WHERE `id`=?",[$_SESSION['roomId']],'i');


			$room_data = mysqli_fetch_assoc($res1);

			$room_data['quantity'] = ($room_data['quantity'] -1);
			$q2 = "UPDATE `rooms` SET  `quantity`=?  WHERE  `id`=?";

			$values2 = [$room_data['quantity'],$room_data['id']];

			update($q2,$values2,'ii');
			echo "<script>
			setTimeout(function () {
				alert('success','Booking Succesful');
		
			},1000);
					</script>";
			
		}
		else {
			echo "<b>Transaction status is failure</b>" . "<br/>";
			echo "<script>
			let timer = 5;

				alert('error','Booking Failed');
			setInterval(function () { 
				{alert('error','take Screenshot within ' +timer + ' Seconds');
				timer = timer-1;}
		
			},1000);
					</script>";
		}

		if (isset($_POST) && count($_POST)>0 )
		{ 
			foreach($_POST as $paramName => $paramValue) {
				if($paramName == 'CHECKSUMHASH')
				{
					continue;
				}
					echo "<br/>" . $paramName . " = " . $paramValue;
			}
		}
		echo "</div>";

	}
	else {
		echo "<b>Checksum mismatched.</b>";
		//Process transaction as suspicious.
	}

?>
<?php require('inc/footer.php'); ?>
</body>
</html>