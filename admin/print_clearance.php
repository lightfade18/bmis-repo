<?php
	/** creating connection to db */
	require 'dbcon.php';

	/** start session */
	session_start();
	date_default_timezone_set('Asia/Manila');
	$showModal = 0;

	if($_SESSION['user']['transprint'] == 0){
		header("Location: transactions.php");
	}
	else{
		$_SESSION['user']['transprint'] = 0;
		$resident_idp = $_SESSION['user']['resi_id'];
		$or_id = $_SESSION['user']['or_id'];
	}

	$sql2 = "SELECT * FROM penresident WHERE resident_id = '$resident_idp'";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $firstname = ($data2['firstname']);
      $middlename = ($data2['middlename']);
      $lastname = ($data2['lastname']);
	  $profile_id = ($data2['profile_id']);
	  $new = ($data2['new']);
	  $street = ($data2['street']);
	  $village = ($data2['village']);
	  $birthdate = ($data2['birthdate']);
	  $birthdate = date_create($birthdate);
      $birthdate = date_format($birthdate, 'F d, Y');
	  $birthplace = ($data2['birthplace']);
    }

	$sql3 = "SELECT * FROM transactionlist_tbl WHERE or_id = '$or_id'";
    $d3 =  $con->query($sql3);
    foreach($d3 as $data3){
	  $purpose = ($data3['purpose']);
    }
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<style>	
		body{
			font-family: Arial, Helvetica, sans-serif;
			margin-top: 0px;
		}
		.content{
			margin: 50px;
		}
		.header{
			line-height: 25px;
		}
		.title{
			font-size: 20px;
		}
		.table {
			width: 100%;
			margin-bottom: 20px;
		}	
		@media print{
			#print {
				display:none;
			}
		}
		@media print {
			#PrintButton {
				display: none;
			}
		}
		
		@page {
			size: auto;   /* auto is the initial value */
			margin: 0;  /* this affects the margin in the printer settings */
		}
	</style>
	</head>
<body>
	<center>
	<br><br>
	<div class="header">
		<img src= 'images/logo.jpg' style="align: left; float: left; padding-left: 80px;" width='100' height="100">
		<img src= 'images/marikinalogo.png' style="align: right; float: right; padding-right: 70px; " width='120' height="100">
		<p style="padding-right: 100px;">
		Republic of the Philippines<br>
		<b class="title">Barangay Concepcion Dos</b><br>
		City of Marikina<br>
		Office of Punong Barangay 
		</p>
		<p style="align: right; float: right; padding-right: 70px; "> <b> No. 2022-01259 </b> </p>
	</div>
	<br /> <br /><br /><br />
	<h2>BARANGAY CLEARANCE/CERTIFICATE</h2>
	<br />
	</center>
	<div style="padding-right: 50px; padding-left: 50px; line-height:25px; font-size: 15px;">
		<p> 
			<b> TO WHOM IT MAY CONCERN: </b> <br> <br>
			This is to certify that the person whose name, right thumbmark and picture appear 
			hereon has requested for a record and Baranagay Clearance from this Office and 
			the result/s are listed below. <br> <br> 
			<?php
			if($profile_id == ''){
				?>
				<img src= 'images/tempo.png' style="border: 2px solid black; align: right; float: right;  margin-right: 20px; " width='160' height="160">
				<?php
			}
			else{
				?>
				<img src= '../resident/images/<?=$profile_id?>' style="border: 2px solid black; align: right; float: right;  margin-right: 20px; " width='160' height="160">
				<?php
			}
			?>
			
			<b> NAME: </b> <?=$firstname?> <?=$middlename?> <?=$lastname?> <br>
			<b> ADDRESS: </b> <?=$new?> <?=$street?> St. <?=$village?>, Concepcion Dos, Marikina City <br><br>
			<b> DOB: </b> <?=$birthdate?> <br>
			<b> POB: </b> <?=$birthplace?> <br>
			<b> PURPOSE: </b> <?=$purpose?> <br><br><br>
			This is to further certify that he/she is a bonafide 
			resident of this Barangay since ______ and that he/she has 
			no derogatory record on file with this office. <br><br>
			Issued upon the request of the above named person this day, <b> <?php $date = date("d");
				echo $date; ?> </b> of <b> <?php $date = date("F");
				echo $date; ?>, </b> <b> <?php $date = date("Y");
				echo $date; ?> </b>
			 at Barangay Concepcion Dos, Marikina City and is valid for six (6) months hereof.
			<br>
			<table>
			<tr>
				<th style="margin-right: 50px; font-size: 12px; ">
					<img src= 'images/tempo.png' style="border: 2px solid black; align: left; float: left; " width='140' height="140"> <br><br>
					Right thumbmark 
				</th>
				<th style="text-align: left; padding-right: 5px; padding-left: 25px; font-size: 12px;">
					OR#: <?=$or_id?> <br> 
					OR DATE: <?php $date = date("F d, Y");
					echo $date; ?>  <br>
					CTC#:  <br>
					DATE Issued: <?php $date = date("F d, Y");
					echo $date; ?> <br>
					PLACE Issued:  <br>
					TIN: 
				</th>
				<th style="padding-left: 20px; text-alignment: center; ">
					<br>
					_____________________________ <br>
					Applicant's Signature <br>
					<br>
					<img src="images/puno_sign.png" style="width: 150px; height: 80px;"> <br>
					HON. MARY JANE ZUBIRI-DELA ROSA <br>
					Punong Barangay
				</th>
			</tr>
			</table>
		</p>
		
	</div>
	</div>
	<center><button id="PrintButton" onclick="PrintPage()">Print</button></center>
</body>
<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	document.loaded = function(){
		
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.location.href = "transactions.php" },750)
	});
</script>
</html>


