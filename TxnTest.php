<?php
	header("Pragma: no-cache");
	header("Cache-Control: no-cache");
	header("Expires: 0");
?>
<!DOCTYPE html >
<html>
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require('inc/links.php');
	require('admin/inc/scripts.php');
	?>
    
    <title><?php echo $settings_r['site_title'] ?>-Book Now</title>
</head>
<body class="bg-light">
<?php require('inc/header.php'); ?>	

<?php 

if(!(isset($_SESSION['uId']))){
	echo"<script>
	setTimeout(function () {
		
		window.location.href='index.php';
	},2000);
		
		alert('error','Not Logged in');
	</script>";        
	exit;
}

?>
<div class="container-fluid px-lg-4 mt-4" style="margin-left: 500px;">
	<h1>Booking Payment Page</h1>
	<pre>
	</pre>
	<form method="post" action="pgRedirect.php">
		<table border="1">
			<tbody>
				<tr>
					<th>S.No</th>
					<th>Label</th>
					<th>Value</th>
				</tr>
				<tr>
					<td>1</td>
					<td><label>ORDER_ID::*</label></td>
					<td><input id="ORDER_ID" tabindex="1" maxlength="20" size="20"
						name="ORDER_ID" autocomplete="off"
						value="<?php echo  "ORDS" . rand(10000,99999999)?>" readonly>
					</td>
				</tr>
				<tr>
					<td>2</td>
					<td><label>CUSTID ::*</label></td>
					<td><input id="CUST_ID" tabindex="2" maxlength="12" size="12" name="CUST_ID" autocomplete="off" value="<?php 
						$returnString ="CUST";
						$returnString.=$_SESSION['uId'];
						echo $returnString;

					?>" readonly></td>
				</tr>
				<tr>
					<td>3</td>
					<td><label>INDUSTRY_TYPE_ID ::*</label></td>
					<td><input id="INDUSTRY_TYPE_ID" tabindex="4" maxlength="12" size="12" name="INDUSTRY_TYPE_ID" autocomplete="off" value="HOTEL" readonly></td>
				</tr>
				<tr>
					<td>4</td>
					<td><label>Channel ::*</label></td>
					<td><input id="CHANNEL_ID" tabindex="4" maxlength="12"
						size="12" name="CHANNEL_ID" autocomplete="off" value="WEB" readonly>
					</td>
				</tr>
				<tr>
					<td>5</td>
					<td><label>txnAmount*</label></td>
					<td><input id="inputAmount" title="TXN_AMOUNT" tabindex="10"
						type="text" name="TXN_AMOUNT"
						value="1" readonly>
					</td>
				</tr>
				<tr>
					<td>6</td>
					<td><label>Email*</label></td>
					<td><input title="TXN_AMOUNT" tabindex="10"
						type="text" name="EMAIL"
						required value = "<?php echo $_SESSION['uEmail'] ?>" readonly>
					</td>
				</tr>
				<tr>
					<td>7</td>
					<td><label>Room Name</label></td>
					<td><input id="inputRoom" title="TXN_AMOUNT" tabindex="10"
						type="text" name="EMAIL"
						required>
					</td>
				</tr>
				<tr>
					<td></td>
					<td></td>
					<td><input id="checkoutBtn" value="CheckOut" type="submit"	onclick=""></td>
				</tr>
			</tbody>
		</table>
		* - Mandatory Fields 
	</form>
	<script>

	let sendData3 = new FormData();
	let totalDays = 1;
	sendData3.append('getDatesCount','xd');
	 
	let xhr3 = new XMLHttpRequest();
    xhr3.open("POST","roomInfo.php",false);
 

    xhr3.onload = function(){
        
		console.log(this.responseText);
        
      if(this.responseText !="")
        { 
			if(parseInt(this.responseText)<1)
			{
				alert('error','Invalid Check-in/Check-out Dates');
				window.location = "rooms.php";
				return;
			}
			totalDays = parseInt(this.responseText);
        }
        else{
            alert('error','Booking Error');
        }
}
    xhr3.send(sendData3);


</script>
<script>
	let totalAmount;
	let pathName = document.location.hash;
	pathName = pathName.substr(1);

	let sendData = new FormData();
	sendData.append('BookRoom','xd');
	sendData.append('id',pathName);
	 
	let xhr = new XMLHttpRequest();
    xhr.open("POST","roomInfo.php",true);
 

    xhr.onload = function(){
        
        
      if(this.responseText !="")
        { let amountHolder = document.getElementById("inputAmount");
			totalAmount=( parseInt(totalDays)*parseInt(this.responseText));
			amountHolder.value =totalAmount;


			
	let sendData4 = new FormData();
	sendData4.append('getTotalAmount','xd');
	sendData4.append('totalAmount',totalAmount);
	sendData4.append('roomId',pathName);
	let xhr4 = new XMLHttpRequest();
    xhr4.open("POST","roomInfo.php",true);
	

    xhr4.onload = function(){
        
        
      if(this.responseText !="")
        { 
			console.log(this.responseText);
        }
        else{
            alert('error','total days error');
        }
}
    xhr4.send(sendData4);
        }
        else{
            alert('error','Booking Error');
        }
}
    xhr.send(sendData);


</script>
<script>

	let sendData2 = new FormData();
	sendData2.append('BookRoom2','xd');
	sendData2.append('id',pathName);
	 
	let xhr2 = new XMLHttpRequest();
    xhr2.open("POST","roomInfo.php",true);
 

    xhr2.onload = function(){
        
        
      if(this.responseText !="")
        { let roomHolder = document.getElementById("inputRoom");
        roomHolder.value = this.responseText;
        }
        else{
            alert('error','No Rooms Found');
        }
}
    xhr2.send(sendData2);


</script>

</div>
<?php require('inc/footer.php');
		
?>
</body>
</html>