<?php
	/** creating connection to db */
	require 'dbcon.php';

	/** start session */
	session_start();
	date_default_timezone_set('Asia/Manila');
	$showModal = 0;

	if($_SESSION['user']['transprint'] == 0){
		header("Location: complaints.php");
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
	<h2>COMPLAINTS SUMMARY REPORT</h2>
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
                <th>Complainant</th>
                <th>Complainee</th>
				<th>In-charge</th>
                <th>Status</th>
			</tr>
		</thead>
		<tbody>
		<?php
		if($_SESSION['user']['inchargespan'] == '1'){
			$sql2 = "SELECT * FROM complaint_tbl WHERE incharge = 'VAWC' AND status != '4'";
			$d2 =  $con->query($sql2);
			foreach($d2 as $data2){
				?>
				<tr>
					<td><?php echo $data2['complainant_lname'];?>, <?php echo $data2['complainant_fname'];?> <?php echo substr($data2['complainant_mname'], 0, 1);?>.</td>
					<td><?php echo $data2['complainee_lname'];?>, <?php echo $data2['complainee_fname'];?> <?php echo substr($data2['complainee_mname'], 0, 1);?>.</td>
					<td><?php echo $data2['incharge'];?></td>   
				<?php
					if($data2['status'] == 1){
						echo '<td>Pending</td>';
					}
					else{
						echo '<td>On-going</td>';
					}
					?>
				</tr>
			<?php
			}
		}
		elseif($_SESSION['user']['inchargespan'] == '2'){
			$sql2 = "SELECT * FROM complaint_tbl WHERE incharge = 'Tanod' AND status != '4'";
			$d2 =  $con->query($sql2);
			foreach($d2 as $data2){
				?>
				<tr>
					<td><?php echo $data2['complainant_lname'];?>, <?php echo $data2['complainant_fname'];?> <?php echo substr($data2['complainant_mname'], 0, 1);?>.</td>
					<td><?php echo $data2['complainee_lname'];?>, <?php echo $data2['complainee_fname'];?> <?php echo substr($data2['complainee_mname'], 0, 1);?>.</td>
					<td><?php echo $data2['incharge'];?></td>   
				<?php
					if($data2['status'] == 1){
						echo '<td>Pending</td>';
					}
					else{
						echo '<td>On-going</td>';
					}
					?>
				</tr>
			<?php
			}
		}
		else{
			$sql2 = "SELECT * FROM complaint_tbl WHERE status != '4'";
			$d2 =  $con->query($sql2);
			foreach($d2 as $data2){
				?>
				<tr>
					<td><?php echo $data2['complainant_lname'];?>, <?php echo $data2['complainant_fname'];?> <?php echo substr($data2['complainant_mname'], 0, 1);?>.</td>
					<td><?php echo $data2['complainee_lname'];?>, <?php echo $data2['complainee_fname'];?> <?php echo substr($data2['complainee_mname'], 0, 1);?>.</td>
					<td><?php echo $data2['incharge'];?></td>   
				<?php
					if($data2['status'] == 1){
						echo '<td>Pending</td>';
					}
					else{
						echo '<td>On-going</td>';
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
</body>
<script type="text/javascript">
	function PrintPage() {
		window.print();
	}
	document.loaded = function(){
		
	}
	window.addEventListener('DOMContentLoaded', (event) => {
   		PrintPage()
		setTimeout(function(){ window.location.href = "complaints.php" },750)
	});
</script>
</html>


