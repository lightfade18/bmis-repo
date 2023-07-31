<!DOCTYPE html>
<?php
	require 'dbcon.php';
?>
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
			padding-right: 350px;
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
	</head>
<body>
<?php
  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  date_default_timezone_set('Asia/Manila');
  $showModal = 0;
	  ?>
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
			<td><img class="logo" src='images/logo.jpg'> <img class="profile" src='images/pic.jpg'></td>
		</tr>	
		<tr>
			<td style="font-size: large; padding-left: 270px"><b>ID NO. 123456</b></td>
		<tr>
		<tr>
			<td class="name"><b>Nathan M. Lim</b></td>
		</tr>
		<tr>
			<td class="constituent"><b>Constituent</b></td>
		</tr>
		<tr>
		<center>
			<td class="sign"> <img src='images/puno sign.png'> </td>
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
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	<table style="border: 2px solid black">
		<tbody>
			<tr>
				<td class="title2">Birthdate:</td>
			</tr>
			<tr>
				<td class="title2">Address:</td>
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
				<br><br><br><b> GUARDIAN 
				<br>CONTACT NO. 
				<br><br><br>VALID UNTIL: </b> 
				</td>
			</tr>
		</tbody>		
	</table>

	<br/><br/><br/>
			<?php
				require 'conn.php';
				$query = $conn->query("SELECT * FROM `penresident`");
				while($fetch = $query->fetch_array()){
			?>
			<?php
				}
			?>
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
		setTimeout(function(){ window.close() },750)
	});
</script>
</html>