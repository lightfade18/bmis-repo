<?php
	/** creating connection to db */
	require 'dbcon.php';

	/** start session */
	session_start();
	date_default_timezone_set('Asia/Manila');
	$showModal = 0;

	if($_SESSION['user']['transprint'] == 0){
		header("Location: individuals.php");
	}
	else{
		$_SESSION['user']['transprint'] = 0;
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
	<h2>RESIDENTS SUMMARY REPORT</h2>
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
				<th>Name</th>
                <th>Age</th>
                <th>Gender</th>
                <th>Street</th>
                <th>Class</th>
			</tr>
		</thead>
		<tbody>
		<?php
			if($_SESSION['user']['agespan'] == 1){
				/** setting age */
				$sql1 = "SELECT * FROM penresident WHERE status = 'Registered' ORDER BY resident_code";
            	$d1 =  $con->query($sql1);

				foreach($d1 as $data1){
					$date3 = date('Y-m-d');
					$datetime5 = new DateTime($date3);
					$datetime6 = new DateTime($data1['birthdate']);
					$difference3 = $datetime6->diff($datetime5);
					$age4 = ($difference3->y);
					if($age4 < 18){
						?>
						<tr>
							<td><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</td>
							<td><?php echo $age4?></td>
							<td><?php echo $data1['gender'];?></td>
							<td><?php echo $data1['street'];?></td>
							<td>Minor</td>
						</tr>
						<?php
					}
					?>
					
					<?php
            		}
			}
			elseif($_SESSION['user']['agespan'] == 2){
				/** setting age */
				$sql1 = "SELECT * FROM penresident WHERE status = 'Registered' ORDER BY resident_code";
            	$d1 =  $con->query($sql1);

				foreach($d1 as $data1){
					$date3 = date('Y-m-d');
					$datetime5 = new DateTime($date3);
					$datetime6 = new DateTime($data1['birthdate']);
					$difference3 = $datetime6->diff($datetime5);
					$age4 = ($difference3->y);
					if($age4 > 18 && $age4 < 60){
						?>
						<tr>
							<td><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</td>
							<td><?php echo $age4?></td>
							<td><?php echo $data1['gender'];?></td>
							<td><?php echo $data1['street'];?></td>
							<td>Adult</td>
						</tr>
						<?php
					}
				}
			}
			elseif($_SESSION['user']['agespan'] == 3){
				/** setting age */
				$sql1 = "SELECT * FROM penresident WHERE status = 'Registered' ORDER BY resident_code";
            	$d1 =  $con->query($sql1);
				foreach($d1 as $data1){
					$date3 = date('Y-m-d');
					$datetime5 = new DateTime($date3);
					$datetime6 = new DateTime($data1['birthdate']);
					$difference3 = $datetime6->diff($datetime5);
					$age4 = ($difference3->y);
					if($age4 > 59){
						?>
						<tr>
							<td><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</td>
							<td><?php echo $age4?></td>
							<td><?php echo $data1['gender'];?></td>
							<td><?php echo $data1['street'];?></td>
							<td>Senior</td>
						</tr>
						<?php
					}
				}
			}
			else{
				/** setting age */
				$sql1 = "SELECT * FROM penresident WHERE status = 'Registered' ORDER BY resident_code";
            	$d1 =  $con->query($sql1);
				foreach($d1 as $data1){
					$date3 = date('Y-m-d');
					$datetime5 = new DateTime($date3);
					$datetime6 = new DateTime($data1['birthdate']);
					$difference3 = $datetime6->diff($datetime5);
					$age4 = ($difference3->y);
					?>
					<tr>
						<td><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</td>
						<td><?php echo $age4?></td>
						<td><?php echo $data1['gender'];?></td>
						<td><?php echo $data1['street'];?></td>
						<?php
							if($age4 < 18){
								echo '<td>Minor</td>';
							}
							elseif($age4 > 59){
								echo '<td>Senior</td>';
							}
							else{
								echo '<td>Adult</td>';
							}
						?>
					</tr>
					<?php
				}
			}
		?>
		</tbody>	
	</table>
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
		setTimeout(function(){ window.location.href = "individuals.php" },750)
	});
</script>
</html>


