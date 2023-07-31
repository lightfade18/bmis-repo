<?php
	/** creating connection to db */
	require 'dbcon.php';

	/** start session */
	session_start();
	date_default_timezone_set('Asia/Manila');

	if($_SESSION['user']['transprint'] == 0){
		header("Location: transactions.php");
	}
	else{
		$_SESSION['user']['transprint'] = 0;
		$resident_idp = $_SESSION['user']['resi_id'];
		$or_id = $_SESSION['user']['or_id'];
	}
	$date = date('Y-m-d');
	$valid_until = date('F d, Y', strtotime($date . ' + 1 year'));
	$showModal = 0;

	

	$sql2 = "SELECT * FROM penresident WHERE resident_id = '$resident_idp'";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $firstname = ($data2['firstname']);
      $middlename = ($data2['middlename']);
      $lastname = ($data2['lastname']);
	  $profile_id = ($data2['profile_id']);
	  $family_position = ($data2['family_position']);
	  $family_role = ($data2['family_role']);
	  $resident_code = ($data2['resident_code']);
	  $birthdate = ($data2['birthdate']);
	  $birthdate = date_create($birthdate);
      $birthdate = date_format($birthdate, 'F d, Y');
	  $new = ($data2['new']);
	  $street = ($data2['street']);
	  $village = ($data2['village']);
    }

	
	if(isset($_POST['update_transaction'])){
		$data = $_POST['update_transaction'];
		$status2 = 3;
		try{
			$qry4 = $con->prepare("UPDATE transactionlist_tbl SET status=:status WHERE or_id = :or_id");
			$qry4->execute( [':status' => $status2, ':or_id' => $or_id]);

		}
		catch(PDOException $e){
			$pdoError = $e->getMesage();
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<style>	
		body{
			font-family: Arial, Helvetica, sans-serif;
		}
		.header{
			line-height: 25px;
			background-color: red;
			color: white;
		}
		.profile{
			width: 200px;
			height: 200px;
			border: 1px solid black;
			margin-top: 10px;
			margin-bottom: 10px;
			margin-right: 15px;
		}
		.logo{
			width: 200px;
			height: 200px;
			margin-top: 10px;
			margin-bottom: 10px;
			margin-right: 10px;
			margin-left: 10px;
		}
		.title{
			font-size: 20px;
		}
		.title2{
			font-size: 20px;
			padding-right: 10px;
			padding-left: 10px;
			font-weight: bold;
			padding-top: 10px;
		}
		.title3{
			text-align: center;
			font-size: 20px;
		}
		.table {
			width: 100%;
			margin-bottom: 20px;
		}	
		.name{
			font-size: larger; 
			text-align: center; 
			background-color: yellow; 
			padding-top: 10px;
			padding-bottom: 10px;
			border-top: 2px solid black;
			border-bottom: 2px solid black;
		}
		.constituent{
			font-size: larger; 
			text-align: center; 
			background-color: red; 
			padding-top: 10px;
			padding-bottom: 10px;
			border-bottom: 2px solid black;
		}
		.sign{
			width: 80px;
			height: 80px;
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
		<script>
			$(document).ready(function(){
				$('#crop_and_upload').click(function(){
					canvas = cropper.getCroppedCanvas({
						width:300,
						height:300
					});
					canvas.toBlob(function(blob){
						url = URL.createObjectURL(blob);
						var reader = new FileReader();
						reader.readAsDataURL(blob);
						reader.onloadend = function(){
							var base64data = reader.result; 
							$.ajax({
								url:'profile_resident.php',
								method:'POST',
								data:{crop_image:base64data},
								success:function(data)
								{
								$modal.modal('hide');
								$("#imagechangemod").modal("show");
								}
							});
						};
					});
				});
			});
		</script>
	</head>
<body>
	<center>
	<br><br>
	<table style="border: 2px solid black">
		<th style="border-bottom: 2px solid black" class="header">
			<p>
			<b class="title"><u>Barangay Concepcion Dos</u></b><br>
			Pio Del Pilar St., Concepcion Dos<br>
			Marikina City<br>
			</p>
		</th>
		<tr>
			<?php
			if($profile_id == ''){
				?>
				<td><img class="logo" src='images/logo.jpg'> <img class="profile" src='images/tempo.jpg'></td>
				<?php
			}
			else{
				?>
				<td><img class="logo" src='images/logo.jpg'> <img class="profile" src='../resident/images/<?=$profile_id?>'></td>
				<?php
			}
			?>
		</tr>	
		<tr>
			<td style="font-size: large; padding-left: 270px"><b>ID NO. <?=$resident_idp?></b></td>
		<tr>
		<tr>
			<td class="name"><b>Nathan M. Lim</b></td>
		</tr>
		<tr>
			<td class="constituent"><b>Constituent</b></td>
		</tr>
		<tr>
		<center>
			<td class="sign"> <img src='images/puno_sign.png'> </td>
		</center>
		</tr>
		
		<tr> 
			<td style="font-size: larger; text-align: center;"> 
			<b>Mary Jane Zubiri-Dela Rosa </b>
			</td> 
		</tr>
		<tr> 
			<td style="font-size: large; padding-bottom: 15px; font-style: bold; text-align: center;"> 
			<b>Punong Barangay </b>
			</td> 
		</tr>
		</b>
	</table>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br/><br/><br/><br><br><br>
	<table style="border: 2px solid black">
		<tbody>
			<tr>
				<td class="title2">Birthdate: <?=$birthdate?></td>
			</tr>
			<tr>
				<td class="title2">Address: <?=$new?> <?=$street?> St. <?=$village?></td>
			</tr>
			<tr>
				<td class="title2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Concepcion Dos, Marikina City</td>
			</tr>
			<tr>
				<td style="padding-top: 80px;" class="title3">--------------------------</td>
			</tr>
			<tr>
				<td class="title3"> <b> Signature </b> </td>
			</tr>
			<tr>
				<td style="padding-top: 30px;" class="title3"> <b> IF FOUND PLEASE RETURN THIS ID </b> </td>
			</tr>
			<tr>
				<td class="title3"> <b> TO BARANGAY CONCEPCION DOS HALL </b> </td>
			</tr>
			<tr>
				<td style="padding-bottom: 30px;" class="title3"> <b> OR CALL 942-0561 </b> </td>
			</tr>
			<tr>
				<td class="constituent" style="color: white;" class="title3"> <b> IN CASE OF EMERGENCY 
				<br> CONTACT NO. </b> 
				<br><br>
				<br><br><br>VALID UNTIL: <?=$valid_until?></b> 
				</td>
			</tr>
		</tbody>		
	</table>
	
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