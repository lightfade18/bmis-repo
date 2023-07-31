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
	<h2>TRANSACTIONS SUMMARY REPORT</h2>
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
				<th>OR #</th>
                <th>Resident</th>
                <th>Date Requested</th>
                <th>Type</th>
				<th>Purpose</th>
			</tr>
		</thead>
		<tbody>
		<?php
			if($_SESSION['user']['periodspan'] == 1){
				/** setting age */
				
				$sql2 = "SELECT * FROM transactionlist_tbl WHERE status = '1' OR status = '3'";
				$d2 =  $con->query($sql2);
				foreach($d2 as $data2){
					$resi_id = $data2['resident_id'];
					$or_date = $data2['or_date'];
					$date1 = date('Y-m-d');

					$datetime2 = new DateTime($or_date);
					$datetime1 = new DateTime($date1);

					$diff = $datetime2->diff($datetime1);

					$yearsInMonths = $diff->format('%r%y') * 12;
					$months = $diff->format('%r%m');
					$totalMonths = $yearsInMonths + $months;
					if($totalMonths < 7){
						/** query to get the appropriate data from the database */ 
						$qry8 = "SELECT * FROM penresident WHERE resident_id = '$resi_id'";
						$dq8 =  $con->query($qry8);
						foreach($dq8 as $qdata8){
							$firstname8 = ($qdata8['firstname']);
							$middlename8 = ($qdata8['middlename']);
							$lastname8 = ($qdata8['lastname']);
						}
						$minicom2 = ($middlename8[0]);
							$fullnames3 = ucwords(strtolower($lastname8 . ", " . $firstname8 . " " . $minicom2));
						?>
						<tr>
							<td><?php echo $data2['or_id'];?></td>
							<td><?=$fullnames3;?></td>
							<td><?php echo $data2['or_date'];?></td>
							<td><?php echo $data2['tran_type'];?></td>
							<td><?php echo $data2['purpose'];?></td>
						</tr>
					<?php
					}
				}
            }
			elseif($_SESSION['user']['periodspan'] == 2){
				/** setting age */
				$sql2 = "SELECT * FROM transactionlist_tbl WHERE status = '1' OR status = '3'";
				$d2 =  $con->query($sql2);
				foreach($d2 as $data2){
					$resi_id = $data2['resident_id'];
					$or_date = $data2['or_date'];
					$date1 = date('Y-m-d');

					$datetime2 = new DateTime($or_date);
					$datetime1 = new DateTime($date1);

					$diff = $datetime2->diff($datetime1);

					$yearsInMonths = $diff->format('%r%y') * 12;
					$months = $diff->format('%r%m');
					$totalMonths = $yearsInMonths + $months;
					if($totalMonths < 12){
						/** query to get the appropriate data from the database */ 
						$qry8 = "SELECT * FROM penresident WHERE resident_id = '$resi_id'";
						$dq8 =  $con->query($qry8);
						foreach($dq8 as $qdata8){
							$firstname8 = ($qdata8['firstname']);
							$middlename8 = ($qdata8['middlename']);
							$lastname8 = ($qdata8['lastname']);
						}
						$minicom2 = ($middlename8[0]);
							$fullnames3 = ucwords(strtolower($lastname8 . ", " . $firstname8 . " " . $minicom2));
						?>
						<tr>
							<td><?php echo $data2['or_id'];?></td>
							<td><?=$fullnames3;?></td>
							<td><?php echo $data2['or_date'];?></td>
							<td><?php echo $data2['tran_type'];?></td>
							<td><?php echo $data2['purpose'];?></td>
						</tr>
					<?php
					}
				}
            }
			else{
				/** setting age */
				$sql2 = "SELECT * FROM transactionlist_tbl WHERE status = '1' OR status = '3'";
				$d2 =  $con->query($sql2);
				foreach($d2 as $data2){
					$resi_id = $data2['resident_id'];
					$or_date = $data2['or_date'];
					$date1 = date('Y-m-d');
					/** query to get the appropriate data from the database */ 
					$qry8 = "SELECT * FROM penresident WHERE resident_id = '$resi_id'";
					$dq8 =  $con->query($qry8);
					foreach($dq8 as $qdata8){
						$firstname8 = ($qdata8['firstname']);
						$middlename8 = ($qdata8['middlename']);
						$lastname8 = ($qdata8['lastname']);
					}
					$minicom2 = ($middlename8[0]);
						$fullnames3 = ucwords(strtolower($lastname8 . ", " . $firstname8 . " " . $minicom2));
					?>
					<tr>
						<td><?php echo $data2['or_id'];?></td>
						<td><?=$fullnames3;?></td>
						<td><?php echo $data2['or_date'];?></td>
						<td><?php echo $data2['tran_type'];?></td>
						<td><?php echo $data2['purpose'];?></td>
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
		setTimeout(function(){ window.location.href = "transactions.php" },750)
	});
</script>
</html>


