<?php
	/** creating connection to db */
	require 'dbcon.php';

	/** start session */
	session_start();
	date_default_timezone_set('Asia/Manila');
	$showModal = 0;

	if($_SESSION['user']['transprint'] == 0){
		header("Location: households.php");
	}
	else{
		$_SESSION['user']['transprint'] = 0;
		$slctdstreet = $_SESSION['user']['streetspan'];
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
		}
		.content{
			margin: 50px;
		}
		.title{
			font-size: 20px;
		}
		.table {
			width: 100%;
			margin-bottom: 20px;
		}	
		
		td.alter{
			padding: 5px;
			text-align: left;
		}
		th{
			border-bottom: 2px solid black;
			background-color: maroon;
			color: white;
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
	<img src= 'images/logo.jpg' width='90' height="90">
	<div class="header">
		<p>
		Republic of the Philippines<br>
		<b class="title">Barangay Concepcion Dos</b><br>
		City of Marikina<br>
		</p>
	</div>
	<br /> <br />
	<h2>HOUSEHOLDS SUMMARY REPORT</h2>
	<h3> For the Month of <?php
		$date = date("F", strtotime("+6 HOURS"));
		echo $date;
	?> </h3>
	</center>
	<br /> <br />
	<div class = "content">
	<div style="float: left">
	<b style="color:red;">Date of Report:</b>
	<?php
		$date = date("Y-m-d", strtotime("+6 HOURS"));
		echo $date;
	?>
	</div>
	<div style="float: right">
	<b style="color:red;">
	Time of Report:</b>
	<?php
		$time = date("h:m:s a");
		echo $time;
	?>
	</div>
	<br/><br/><br/>
	<table class="table table-striped">
		<thead>
			<tr style="border: 2px solid black">
				<th>Household Head</th>
				<th>Resident Code</th>
                <th>Street St.</th>
				<th>Members</th>
                <th>Old St. Number</th>
				<th>New St. Number</th>
				<th>Village</th>
			</tr>
		</thead>
		<tbody>
			<?php
			if($slctdstreet == 'All'){
				$sql1 = "SELECT * FROM penresident WHERE family_position = 'Head' AND status = 'Registered'";
				$d1 =  $con->query($sql1);
				foreach($d1 as $data1){
				?>
				<tr>
					<td><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</td>
					<td><?php echo $data1['resident_code'];?></td>
					<td><?php echo $data1['street'];?></td>
					<?php
						$housecode = $data1['resident_code'];
						$sql7 = "SELECT COUNT(resident_id) FROM penresident WHERE resident_code = '$housecode'";
						$res1 = $con->query($sql7);
						$cnt1 = $res1->fetchColumn();
					?>
					<td style="padding-left: 35px;"><?php echo $cnt1;?></td>
					<td><?php echo $data1['old'];?></td>
					<td><?php echo $data1['new'];?></td>
					<td><?php echo $data1['village'];?></td>
				</tr>
				<?php
				}
			}
			else{
				$sql1 = "SELECT * FROM penresident WHERE family_position = 'Head' AND status = 'Registered' AND street = '$slctdstreet'";
				$d1 =  $con->query($sql1);
				foreach($d1 as $data1){
				?>
				<tr>
					<td><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</td>
					<td><?php echo $data1['resident_code'];?></td>
					<td><?php echo $data1['street'];?></td>
					<?php
						$housecode = $data1['resident_code'];
						$sql7 = "SELECT COUNT(resident_id) FROM penresident WHERE resident_code = '$housecode'";
						$res1 = $con->query($sql7);
						$cnt1 = $res1->fetchColumn();
					?>
					<td style="padding-left: 35px;"><?php echo $cnt1;?></td>
					<td><?php echo $data1['old'];?></td>
					<td><?php echo $data1['new'];?></td>
					<td><?php echo $data1['village'];?></td>
				</tr>
				<?php
				}
			}
			?>
		</tbody>	
	</table>
	</div>
</body>
<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	document.loaded = function(){
		
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.location.href = "households.php" },750)
	});
</script>
</html>


