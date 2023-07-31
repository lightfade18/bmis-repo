<?php
  /** creating connection for email sender */
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  $showModal = 0;
  $checker = 0;

  /** code for couting to be deliver transactions */
  $sql5 = "SELECT COUNT(or_id) FROM transactionlist_tbl WHERE status = '1'";
  $res1 = $con->query($sql5);
  $cnt1 = $res1->fetchColumn();

  /** code for couting pending transactions */
  $sql6 = "SELECT COUNT(or_id) FROM transactionlist_tbl WHERE status = '2'";
  $res2 = $con->query($sql6);
  $cnt2 = $res2->fetchColumn();

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  /** resident id validation codes */
  if(isset($_REQUEST['addresid'])){
    $resident_id = strtolower(filter_var($_REQUEST['resident_id'],FILTER_SANITIZE_STRING));
    /** variable use to decide in the if statement */
    $selector = 0;
    try{
      /** sql query in pdo format that check if the resident code input is already existing in the db */
      $slct_stmt1 = $con->prepare("SELECT resident_id FROM penresident WHERE resident_id = :resident_id LIMIT 1");
      $slct_stmt1->execute([
        ':resident_id' => $resident_id
      ]);
      $row1 = $slct_stmt1->fetch(PDO::FETCH_ASSOC);
      if($slct_stmt1->rowCount() > 0){
        $showModal = 1;
      }
      else{
        $errorMSG = "Invalid Resident ID.";
        $showModal = 3;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }

  /** adding new transaction codes */
  if(isset($_REQUEST['addtrans'])){
    $tran_id = $_REQUEST['tran_id'];
    $resident_id = $_REQUEST['resident_id'];
    $quantity = $_REQUEST['quantity'];

    /** query to get the appropriate data from the database */ 
    $sql2 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $firstname = ($data2['firstname']);
      $middlename = ($data2['middlename']);
      $lastname = ($data2['lastname']);
    }

    /** query to get the appropriate data from the database */ 
    $sql4 = "SELECT * FROM transaction_tbl WHERE tran_id = '$tran_id'";
    $d4 =  $con->query($sql4);
    foreach($d4 as $data4){
      $price = ($data4['price']);
    }

    // $qry1 = "SELECT * FROM transaction_tbl WHERE tran_id = '7'";
    // $dq1 =  $con->query($qry1);
    // foreach($dq1 as $qdata1){
    //   $dfee = ($qdata1['price']);
    // }

    /** calculate the total amount of the transaction */
    $total_amount = $price * $quantity;

    /** formatting the names to display in the page */
    $minicom1 = ($middlename[0]);
    $fullname = ucwords(strtolower($lastname . ", " . $firstname . " " . $minicom1));
    /** if statement that set what type of transaction to display */
    if($tran_id == 1){
      $tran_name = "Barangay Clearance";
    }
    elseif($tran_id == 2){
      $tran_name = "Barangay Certificate";
    }
    elseif($tran_id == 3){
      $tran_name = "Barangay I.D.";
    }
    else{
      $tran_name = "Community Tax Certificate";
    }
    /** dowhile loop that prevent the duplication of or_id in the db */
    $check = true;
    $cts = 0;
    do{
      $cts++;
      /** creating resident id by randoming numbers*/
      $or_id = rand(100000, 999999);
      $slct_stmt2 = $con->prepare("SELECT or_id FROM transactionlist_tbl WHERE or_id = :or_id");
      $slct_stmt2->execute([':or_id' => $or_id]);
      $row2 = $slct_stmt2->fetch(PDO::FETCH_ASSOC);
      if(isset($row2['or_id']) == $or_id){
        $check = false;
      }
      else{
        $check = true;
      }
    }while($check == false && $cts != 1000000);

    /** an if statement that check if the capacity of residents that the system can handle */
    if($check){
      /** setting the timezone of the system */
      date_default_timezone_set('Asia/Manila');
      $or_date = date('Y-m-d H:i:s');
      $or_datedis = date_create($or_date);
      $or_datedis = date_format($or_datedis, 'F d, Y (h:i:s a)');
      $status = "1";
      $showModal = 8;
    }
    else{
      $showModal = 1;
    }
    
  }

  /** confirming adding transaction codes */
  if(isset($_REQUEST['confirmadd'])){
    $tran_id = $_REQUEST['tran_id'];
    $quantity = $_REQUEST['quantity'];
    $resident_id = $_REQUEST['resident_id'];
    $tran_name = $_REQUEST['tran_name'];
    $or_id = $_REQUEST['or_id'];
    $or_date = $_REQUEST['or_date'];
    $purpose = ucfirst(trim(filter_var($_REQUEST['purpose'],FILTER_SANITIZE_STRING)));
    $status = $_REQUEST['status'];
    $total_amount = $_REQUEST['total_amount'];
    try{
      /** preparing and executing a pdo query that adds values to the db */
      $sql3 = $con->prepare("INSERT INTO transactionlist_tbl (or_id, resident_id, tran_id, tran_type, total_amount, or_date, purpose, status, quantity)
      VALUES (:or_id, :resident_id, :tran_id, :tran_type, :total_amount, :or_date, :purpose, :status, :quantity)");
      $sql3->execute([
        ':or_id' => $or_id,
        ':resident_id' => $resident_id,
        ':tran_id' => $tran_id,
        ':tran_type' => $tran_name,
        ':total_amount' => $total_amount,
        ':or_date' => $or_date,
        ':purpose' => $purpose,
        ':status' => $status,
        ':quantity' => $quantity
      ]);
      $showModal = 4;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }
  
  /** adding transaction codes*/
  if(isset($_REQUEST['detailmod'])){
    $or_id = $_REQUEST['detailmod'];
    /** query to get the appropriate data from the database */ 
    $sql8 = "SELECT * FROM transactionlist_tbl WHERE or_id = '$or_id'";
    $d8 =  $con->query($sql8);
    foreach($d8 as $data8){
      $tran_name =  ($data8['tran_type']);
      $fullname = ($data8['name']);
      $total_amount = ($data8['total_amount']);
      $or_date = ($data8['or_date']);
      $purpose = ($data8['purpose']);
      $tran_id = ($data8['tran_id']);
    }
    $sql9 = "SELECT * FROM transaction_tbl WHERE tran_id = '$tran_id'";
    $d9 =  $con->query($sql9);
    foreach($d9 as $data9){
      $price =  ($data9['price']);
    }
    $quantity = $total_amount / $price;
    $showModal = 2;
  }

  /** approving transaction on detail codes*/
  if(isset($_REQUEST['detailapprove'])){
    $tran_id = $_REQUEST['tran_id'];
    $resident_id = $_REQUEST['resident_id'];
    $fullname = $_REQUEST['fullname'];
    $tran_name = $_REQUEST['tran_name'];
    $or_id = $_REQUEST['or_id'];
    $or_date = $_REQUEST['or_date'];
    $purpose = $_REQUEST['purpose'];
    $status = $_REQUEST['status'];
    $total_amount = $_REQUEST['total_amount'];
    date_default_timezone_set('Asia/Manila');
    $date_received = date('Y-m-d H:i:s');
    $showModal = 6;
  }

  /** adding transaction codes*/
  if(isset($_REQUEST['addtransac'])){
    $purpose = filter_var($_REQUEST['purpose'],FILTER_SANITIZE_STRING);
    $tran_id = $_REQUEST['tran_id'];
    $resident_id = $_REQUEST['resident_id'];
    $fullname = $_REQUEST['fullname'];
    $tran_name = $_REQUEST['tran_name'];
    $fullname = $_REQUEST['fullname'];
    $or_id = $_REQUEST['or_id'];
    $or_date = $_REQUEST['or_date'];
    $status = $_REQUEST['status'];
    $price = $_REQUEST['price'];
    $quantity = $_REQUEST['quantity'];
    $total_amount = $_REQUEST['total_amount'];
    $showModal = 5;
  }

  if(isset($_REQUEST['pendingtransopen'])){
    $or_id = $_REQUEST['pendingtransopen'];
    $showModal = 9;
  }

  if(isset($_REQUEST['verifytrans'])){
    $or_id = $_REQUEST['verifytrans'];
    $showModal = 10;
  }

  if(isset($_REQUEST['transactionapprove'])){
    $or_id = $_REQUEST['transactionapprove'];
    $status2 = 1;
    try{
      $qry4 = $con->prepare("UPDATE transactionlist_tbl SET status=:status WHERE or_id = :or_id");
      # execute query
      if($qry4->execute( [':status' => $status2, ':or_id' => $or_id])){
        $qry6 = "SELECT * FROM transactionlist_tbl WHERE or_id = '$or_id'";
        $dq6 =  $con->query($qry6);
        foreach($dq6 as $qdata6){
          $resident_id = $qdata6['resident_id'];
          $trantype1 = $qdata6['tran_type'];
        }
        $sql18 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
        $d18 =  $con->query($sql18);
        foreach($d18 as $data18){
          $comnum = $data18['contact'];
          $contact = ('+63'.$data18['contact']);
          $email3f = $data18['email'];
        }

        # sending email of approval to complainant
        $mail1 = new PHPMailer(true);
        $mail1->isSMTP();
        $mail1->Host = "smtp.gmail.com";
        $mail1->SMTPAuth = true;
        $mail1->Username = 'pjjumawan18@gmail.com';
        $mail1->Password = 'lfvjckzasfrpzxzf';
        $mail1->SMTPSecure = 'ssl';
        $body1 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Transaction Request</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                  We are here to inform you that your request for <b>'.$trantype1.'</b> has been approved.<br>
                  Please wait for the transaction code to be sent to you, so you can claim your request.</p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concecion Dos Management.</b></p>';
        
        $mail1->Port = 465;

        $mail1->setFrom('pjjumawan18@gmail.com');

        $mail1->addAddress($email3f);

        $mail1->isHTML(true);

        $mail1->Subject = 'Transaction Request';
        $mail1->Body = $body1;

        $mail1->send();

        $checker = 1;
        $showModal = 4;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  if(isset($_REQUEST['verifydecline'])){
    $or_id = $_REQUEST['verifydecline'];
    $showModal = 11;
  }

  if(isset($_REQUEST['transactiondecline'])){
    $or_id = $_REQUEST['transactiondecline'];
    try{
      $qry5 = $con->prepare("DELETE FROM transactionlist_tbl WHERE or_id = :or_id");
      # execute query
      if($qry5->execute( [':or_id' => $or_id])){
        $qry7 = "SELECT * FROM transactionlist_tbl WHERE or_id = '$or_id'";
        $dq6 =  $con->query($qry7);
        foreach($dq6 as $qdata6){
          $resident_id = $qdata6['resident_id'];
          $trantype1 = $qdata6['tran_type'];
        }
        $sql14 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
        $d14 =  $con->query($sql14);
        foreach($d14 as $data14){
          $comnum = $data14['contact'];
          $contact = ('+63'.$data14['contact']);
          $email2f = $data14['email'];
        }

        # sending email of approval to complainant
        $mail2 = new PHPMailer(true);
        $mail2->isSMTP();
        $mail2->Host = "smtp.gmail.com";
        $mail2->SMTPAuth = true;
        $mail2->Username = 'pjjumawan18@gmail.com';
        $mail2->Password = 'lfvjckzasfrpzxzf';
        $mail2->SMTPSecure = 'ssl';
        $body2 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Transaction Request</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                  We are here to inform you that your request for <b>'.$trantype1.'</b> has been declined.</p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concecion Dos Management.</b></p>';
        
        $mail2->Port = 465;

        $mail2->setFrom('pjjumawan18@gmail.com');

        $mail2->addAddress($email2f);

        $mail2->isHTML(true);

        $mail2->Subject = 'Transaction Request';
        $mail2->Body = $body2;

        $mail2->send();
        $checker = 2;
        $showModal = 4;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }
  
  if(isset($_REQUEST['printtransaction'])){
    $_SESSION['user']['transprint'] = 1;
    $typeprint = $_REQUEST['printtransaction'];
    if($typeprint == 1){
      $_SESSION['user']['periodspan'] = $_REQUEST['periodspan'];
      header("Location: print_transactions.php");
    }
  }

  if(isset($_REQUEST['printrelease'])){
    $_SESSION['user']['transprint'] = 1;
    $typeprint = $_REQUEST['printrelease'];
    $tran_types = $_REQUEST['tran_type'];
    if($tran_types == 'Barangay Clearance' || $tran_types == 'Barangay Certificate'){
      $_SESSION['user']['resi_id'] = $_REQUEST['resi_id'];
      $_SESSION['user']['or_id'] = $typeprint;
      header("Location: print_clearance.php");
    }
    elseif($tran_types == 'Barangay I.D.'){
      $_SESSION['user']['resi_id'] = $_REQUEST['resi_id'];
      $_SESSION['user']['or_id'] = $typeprint;
      header("Location: print_id.php");
    }
    else{

    }
  }

  if(isset($_REQUEST['releaseprint'])){
    $or_id3 = $_REQUEST['releaseprint'];
    $showModal = 12;
  }

  if(isset($_REQUEST['confreleaseprint'])){
    $or_id3 = $_REQUEST['confreleaseprint'];
    $status1 = 3;
    try{
			$qry9 = $con->prepare("UPDATE transactionlist_tbl SET status=:status WHERE or_id = :or_id");
			$qry9->execute( [':status' => $status1, ':or_id' => $or_id3]);
		}
		catch(PDOException $e){
			$pdoError = $e->getMesage();
		}
  }
  
  if(isset($_REQUEST['viewtransactiondetail'])){
    $or_id4 = $_REQUEST['viewtransactiondetail'];
    $showModal = 13;
  }

  /** resetting page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: transactions.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transactions</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="css/mainstyle.css" />
    <style>
      /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      /* Firefox */
      input[type=number] {
        -moz-appearance: textfield;
      }

      #myBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
      }

      #myBtn:hover {
        background-color: #555;
      }
    </style>
</head>
<body>
  <button
    type="button"
    class="btn btn-dark btn-lg rounded-circle text-center"
    id="myBtn"
    onclick="topFunction()"
    title="Go to top"
    >
    <i class="bi bi-chevron-up"></i>
  </button>
  <!-- navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <div class="container-fluid">
      <!-- offcanvas trigger -->
      <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
          <span class="navbar-toggler-icon" data-bs-target="#sidebar"></span>
      </button>
      <!-- offcanvas trigger -->
      <a class="navbar-brand fw-bold text-uppercase" href="#"><img src="images/logo.jpg" alt="" width="45" height="45">&nbsp;brgy ###</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="bi bi-three-dots-vertical"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
          <ul class="navbar-nav ms-auto ">
            <div class=""></div>
            <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-fill"></i>
            </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                    <li><a class="dropdown-item" href="profile.php">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutconfmodal">Log-out</a></li>
                </ul>
            </li>
          </ul>
      </div>
    </div>
  </nav>
  <!-- navbar -->

  <!-- offcanvas -->
  <div class="offcanvas offcanvas-start sidebar-nav" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
    <div class="offcanvas-body p-0 bg-dark">
      <ul class="navbar-nav">
        <li>
          <div class="text-muted small fw-bold text-uppercase px-3">
            Admin
          </div>
        </li>
        <li>
          <a href="dashboard.php" class="nav-link px-3 text-white">
            <span class="me-3">
              <i class="bi bi-speedometer2"></i>
            </span>
            <span>Dashboard</span>
          </a>  
        </li>
        <li>
          <a href="officials.php" class="nav-link px-3 text-white">
            <span class="me-3">
              <i class="bi bi-archive"></i>
            </span>
            <span>Officials</span>
          </a>  
        </li>
        <li class="my-2">
          <hr class="dropdown-divider">
        </li>
        <li>
          <div class="text-muted small fw-bold text-uppercase px-3">
            Records
          </div>
        </li>
        <li>
          <a class="nav-link px-3 sidebar-link text-white" data-bs-toggle="collapse" href="#residents" role="button" aria-expanded="false" aria-controls="collapseExample">
            <span class="me-3">
              <i class="bi bi-list-ul"></i>
            </span>
            <span>Resident Lists</span>
            <span class="right-icon ms-auto">
              <i class="bi bi-chevron-down"></i>
            </span>
          </a>
          <div class="collapse" id="residents">
            <div class="card card-body  bg-danger">
              <ul class="navbar-nav ps-0">
                <li>
                  <a href="households.php" class="nav-link px-3 text-white">
                    <span class="me-3">
                      <i class="bi bi-people"></i>
                    </span>
                    <span>Households</span>
                  </a>
                </li>
                <li>
                  <a href="individuals.php" class="nav-link px-3 text-white">
                    <span class="me-3">
                      <i class="bi bi-person"></i>
                    </span>
                    <span>Individuals</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </li>
        <li>
          <a class="nav-link px-3 sidebar-link text-white" data-bs-toggle="collapse" href="#reports" role="button" aria-expanded="false" aria-controls="collapseExample">
            <span class="me-3">
              <i class="bi bi-list-ul"></i>
            </span>
            <span>Reports</span>
            <span class="right-icon ms-auto">
              <i class="bi bi-chevron-down"></i>
            </span>
          </a>
          <div class="collapse" id="reports">
            <div class="card card-body  bg-danger">
              <ul class="navbar-nav p-0">
                <li>
                  <a href="complaints.php" class="nav-link px-3 text-white">
                    <span class="me-3">
                      <i class="bi bi-people"></i>
                    </span>
                    <span>Complaints</span>
                  </a>
                </li>
                <li>
                  <a href="blotter.php" class="nav-link px-3 text-white">
                    <span class="me-3">
                      <i class="bi bi-person"></i>
                    </span>
                    <span>Blotters</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </li>
        <li>
          <a href="archive.php" class="nav-link px-3 text-white">
            <span class="me-3">
              <i class="bi bi-archive"></i>
            </span>
            <span>Archives</span>
          </a>  
        </li>
        <li class="my-2">
          <hr class="dropdown-divider">
        </li>
        <li>
          <div class="text-muted small fw-bold text-uppercase px-3">
            Actions
          </div>
        </li>
        <li>
          <a href="transactions.php" class="nav-link px-3 bg-white active text-dark">
            <span>
              <i class="bi bi-arrow-left-right"></i>
            </span>
            <span>Transactions</span>
          </a>  
        </li>
        <li class="my-2">
          <hr class="dropdown-divider">
        </li>
        <li>
          <div class="text-muted small fw-bold text-uppercase px-3">
            Community
          </div>
        </li>
        <li>
          <a href="announcements.php" class="nav-link px-3 text-white">
            <span>
              <i class="bi bi-megaphone"></i>
            </span>
            <span>Announcements</span>
          </a>  
        </li>
        <li class="my-2">
          <hr class="dropdown-divider">
        </li>
        <li>
          <div class="text-muted small fw-bold text-uppercase px-3">
            Preferences
          </div>
        </li>
        <li>
          <a href="profile.php" class="nav-link px-3 mb-5 text-white">
            <span>
              <i class="bi bi-gear"></i>
            </span>
            <span>Settings</span>
          </a>  
        </li>
    </ul>
    </div>
  </div>
  <!-- offcanvas -->

  <!-- main content -->
  <main class="mt-5 pt-4">
    <!-- content title -->
    <div class="container-fluid">
      <div class="row  me-3">
        <div class="col-md-12 fw-bold fs-3 text-danger">Transactions</div>
      </div>
      <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row m-1">
          <div class="col-md-6">
            <p class="text-start">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#rescode">New Transaction</button>
              <button type="button" class="btn btn-secondary shadow-none mx-1 position-relative" data-bs-toggle="modal" data-bs-target="#requesttransactions">
                <i class="bi bi-person-check"></i>
                Pending Transactions
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <span><?=$cnt2?></span>
                  <span class="visually-hidden">New alerts</span>
                </span>
              </button>
            </p>
          </div>
          <div class="col-md-6">
            <p class="text-end">
              <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#genreportmodal"><i class="bi bi-printer"></i>&nbsp;Generate Report</button>
            </p>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Transaction Data Table
          </div>
        </div>
        <div class="row m-1">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <table id="transaction" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>Type</th>
                      <th>Resident</th>
                      <th>Quantity</th>
                      <th>Date Requested</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql2 = "SELECT * FROM transactionlist_tbl WHERE status = '1'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resi_id = $data2['resident_id'];;
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
                        <th><?php echo $data2['tran_type'];?></th>
                        <th><?=$fullnames3;?></th>
                        <th><?php echo $data2['quantity'];?></th>
                        <th><?php echo $data2['or_date'];?></th>
                        <form action="transactions.php" method="post">
                        <input type="hidden" name="tran_type" value="<?=$data2['tran_type']?>">
                        <input type="hidden" name="resi_id" value="<?=$resi_id?>">
                        <?php
                        if($data2['tran_type'] == 'Community Tax Certificate'){
                          ?>
                          <th><button type="submit" class="btn btn-info" name="viewtransactiondetail" value="<?=$data2['or_id']?>">Details</button> <button type="submit" class="btn btn-primary" name="releaseprint" value="<?=$data2['or_id']?>">Release</button></th>
                          <?php
                        }
                        else{
                          ?>
                          <th><button type="submit" class="btn btn-success" name="printrelease" value="<?=$data2['or_id']?>">Print</button> <button type="submit" class="btn btn-primary" name="releaseprint" value="<?=$data2['or_id']?>">Release</button></th>
                          <?php
                        }
                        ?>
                        </form>
                      </tr>
                      <?php
                      }
                    ?>
                  </tbody>
                </table>
              </div>
            </div> 
          </div>
        </div>
      </div>

      <!-- entering code pop-up modal -->
      <div class="modal fade" id="rescode" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="staticBackdropLabel">Adding Individual</h5>
            </div>
            <div class="modal-body">
              <form action="transactions.php" method="post">
              <?php
                if(isset($errorMSG)){
                  echo '<div class="alert alert-danger" role="alert">'
                  .$errorMSG.'
                </div>';
                }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="resident_id" class="form-label">Resident ID</label>
                  <input type="text" class="form-control" id="resident_id" name="resident_id" placeholder="Enter here..." required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="addresid">Submit</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- entering code pop-up modal -->

      <!-- choosing type pop-up modal -->
      <div class="modal fade" id="choosetpye" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Choose Transaction</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG1)){
                  echo '<div class="alert alert-danger" role="alert">'
                  .$errorMSG1.'
                </div>';
                }
                if(isset($errorMSG2)){
                  echo '<div class="alert alert-danger" role="alert">'
                  .$errorMSG2.'
                </div>';
                }
              ?>
              <form action="transactions.php" method="post">
              <div class="row">
                <div class="col-md-8">
                  <label for="tran_id" class="form-label">Transaction Type</label>
                  <select class="form-select" aria-label="Default select example" id="tran_id" name="tran_id" required>
                    <option selected value="">Select Transaction</option>
                    <option value="1">Barangay Clearance</option>
                    <option value="2">Business Certificate</option>
                    <option value="5">Community Tax Certificate</option>
                    <option value="3">Barangay I.D.</option>
                  </select>
                </div>
                <div class="col-md-4">
                  <label for="quantity" class="form-label">Quantity</label>
                  <input type="number" class="form-control" name="quantity" id="quantity" value="1" min="1" max="100" required>
                </div>
              </div>
              <input type="hidden" name="resident_id" value="<?php echo $resident_id?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="addtrans">Proceed</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- choosing type pop-up modal end -->

      <!-- adding pop-up modal -->
      <div class="modal fade" id="addmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Adding Transaction</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG3)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG3.
                  "</div>
                </div>";
                }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="ornumber" class="form-label">OR #</label>
                  <p id="ornumber" class="text-start fs-4"><?php echo $or_id;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="typetran" class="form-label">Transaction Type</label>
                  <p id="typetran" class="text-start fs-4"><?php echo $tran_name;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="residentreq" class="form-label">Resident</label>
                  <p id="typetran" class="text-start fs-4"><?php echo $fullname;?>.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="Price" class="form-label">Price</label>
                  <p id="Price" class="text-start fs-4"><?php echo $price;?></p>
                </div>
                <div class="col-md-6">
                  <label for="Quantity" class="form-label">Quantity</label>
                  <p id="Quantity" class="text-start fs-4"><?php echo $quantity;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="Total Amount" class="form-label">Total Amount</label>
                  <p id="Total Amount" class="text-start fs-4"><?php echo $total_amount;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="ordate" class="form-label">OR Date</label>
                  <p id="ordate" class="text-start fs-4"><?php echo $or_datedis;?></p>
                </div>
              </div>
              <form action="transactions.php" method="post">
              <input type="hidden" name="quantity" value="<?php echo $quantity;?>">
              <input type="hidden" name="status" value="<?php echo $status;?>">
              <input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
              <input type="hidden" name="tran_name" value="<?php echo $tran_name;?>">
              <input type="hidden" name="resident_id" value="<?php echo $resident_id;?>">
              <input type="hidden" name="or_id" value="<?php echo $or_id;?>">
              <input type="hidden" name="or_date" value="<?php echo $or_date;?>">
              <input type="hidden" name="price" value="<?php echo $price;?>">
              <input type="hidden" name="quantity" value="<?php echo $quantity;?>">
              <input type="hidden" name="total_amount" value="<?php echo $total_amount;?>">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group green-border-focus" id="purpose">
                    <label for="purpose">Purpose</label>
                    <textarea class="form-control" id="purpose" name="purpose" rows="5" required></textarea>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmadd">Confirm</button>
              <button type="submit" class="btn btn-secondary" name="resid">Back</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- adding pop-up modal end -->
      
      <!-- aprroving pop-up modal -->
      <div class="modal fade" id="confendmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Transaction Information</h5>
            </div>
            <div class="modal-body">
              <?php
              if($checker == 1){
                echo '<p class="text-center fs-4">Transaction was accepted and now pending to be release.</p>';
              }
              elseif($checker == 2){
                echo '<p class="text-center fs-4">Transaction has been declined.</p>';
              }
              else{
                echo '<p class="text-center fs-4">Transaction successfull added.</p>';
              }
              ?>
            </div>
            <div class="modal-footer">
              <form action="transactions.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- aprroving pop-up modal end -->

      <!-- transaction details pop-up modal -->
      <div class="modal fade" id="detailmod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Adding Transaction</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG3)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG3.
                  "</div>
                </div>";
                }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="typetran" class="form-label">Transaction Type</label>
                  <p id="typetran" class="text-start fs-3"><?php echo $tran_name;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="residentreq" class="form-label">Resident</label>
                  <p id="typetran" class="text-start fs-3"><?php echo $fullname;?>.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="Price" class="form-label">Price</label>
                  <p id="Price" class="text-start fs-3"><?php echo $price;?></p>
                </div>
                <div class="col-md-3">
                  <label for="Quantity" class="form-label">Quantity</label>
                  <p id="Quantity" class="text-start fs-3"><?php echo $quantity;?></p>
                </div>
                <div class="col-md-1">
                  <label for="Total" class="form-label"></label>
                  <p id="Total" class="text-start fs-3">=</p>
                </div>
                <div class="col-md-5">
                  <label for="Total Amount" class="form-label">Total Amount</label>
                  <p id="Total Amount" class="text-start fs-3"><?php echo $total_amount;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="ornumber" class="form-label">OR #</label>
                  <p id="ornumber" class="text-start fs-3"><?php echo $or_id;?></p>
                </div>
                <div class="col-md-6">
                  <label for="ordate" class="form-label">OR Date</label>
                  <p id="ordate" class="text-start fs-3"><?php echo $or_date;?></p>
                </div>
              </div>
              <form action="transactions.php" method="post">
              <input type="hidden" name="status" value="<?php echo $status;?>">
              <input type="hidden" name="purpose" value="<?php echo $purpose;?>">
              <input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
              <input type="hidden" name="tran_name" value="<?php echo $tran_name;?>">
              <input type="hidden" name="resident_id" value="<?php echo $resident_id;?>">
              <input type="hidden" name="fullname" value="<?php echo $fullname;?>">
              <input type="hidden" name="or_id" value="<?php echo $or_id;?>">
              <input type="hidden" name="or_date" value="<?php echo $or_date;?>">
              <input type="hidden" name="price" value="<?php echo $price;?>">
              <input type="hidden" name="quantity" value="<?php echo $quantity;?>">
              <input type="hidden" name="total_amount" value="<?php echo $total_amount;?>">
              <div class="row">
                <div class="col-md-12">
                  <label for="purpose" class="form-label">Purpose</label>
                  <p id="purpose" class="text-start fs-3"><?php echo $purpose;?></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="detailapprove">Approve</button>
              <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Deny</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- transaction details pop-up modal end -->

      <!-- pending requests pop-up modal -->
      <div class="modal fade" id="requesttransactions" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="requesttransactions">Pending Transactions</h5>
            </div>
            <div class="modal-body">
                <?php
                  $sql10 = "SELECT * FROM transactionlist_tbl WHERE status = '2' ORDER BY or_date";
                  $d10 =  $con->query($sql10);
                  if($cnt2 == 0){
                    echo '<p class="text-center fs-4">No pending request.</p>';
                  }
                  else{
                    foreach($d10 as $data10){
                      $date = date_create($data10['or_date']);
                      $date = date_format($date, 'F d, Y h:i:s a');
                      $trantype = $data10['tran_type'];
                      $resident_id = $data10['resident_id'];
                    
                    /** query to get the appropriate data from the database */ 
                    $qry2 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
                    $dq2 =  $con->query($qry2);
                    foreach($dq2 as $qdata2){
                      $firstname = ($qdata2['firstname']);
                      $middlename = ($qdata2['middlename']);
                      $lastname = ($qdata2['lastname']);
                    }
                    $minicom1 = ($middlename[0]);
                    $fullnames = ucwords(strtolower($lastname . ", " . $firstname . " " . $minicom1));
                  ?>
                  <div class="row border shadow-lg p-3 ms-1 me-1 mb-2 bg-body rounded">
                    <div class="col-md-12">
                      <p class="text-start fs-4 fw-bold"><?=$fullnames;?>.</p>
                      <?php
                      if($trantype == 'Community Tax Certificate'){
                        echo '<p class="text-start fs-5">Community Tax Certificate request.</p>';
                      }
                      elseif($trantype== 'Barangay Certificate'){
                        echo '<p class="text-start fs-5">Barangay Certificate request.</p>';
                      }
                      elseif($trantype == 'Barangay Clearance'){
                        echo '<p class="text-start fs-5">Barangay Clearance request.</p>';
                      }
                      ?>
                      <form action="transactions.php" method="post">
                      <p class="text-end fs-5"><button type="submit" class="btn btn-link" name="pendingtransopen" value="<?=$data10['or_id'];?>">View</button></p>
                      </form>
                    </div>
                  </div>
                  <?php
                  }
                  }
                ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- pending requests pop-up modal end -->

      <!-- viewing transaction request pop-up modal -->
      <div class="modal fade" id="reqdetailmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Adding Transaction</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG3)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG3.
                  "</div>
                </div>";
                }
                
                $minicom1 = ($middlename[0]);
                $fullnames = ucwords(strtolower($lastname . ", " . $firstname . " " . $minicom1));
                $sql6 = "SELECT * FROM transactionlist_tbl WHERE or_id = '$or_id'";
                $d6 =  $con->query($sql6);
                foreach($d6 as $data6){
                  $price = ($data6['total_amount'] -  $data6['delivery_fee']) / $data6['quantity'];
                  $compdate4 = date_create($data6['or_date']);
                  $compdate4 = date_format($compdate4, 'F d, Y (h:i:s a)');
                  $resident_id = $data6['resident_id'];
                  
                  /** query to get the appropriate data from the database */ 
                  $qry3 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
                  $dq3 =  $con->query($qry3);
                  foreach($dq3 as $qdata3){
                    $firstname = ($qdata3['firstname']);
                    $middlename = ($qdata3['middlename']);
                    $lastname = ($qdata3['lastname']);
                  }
                  $minicom1 = ($middlename[0]);
                  $fullnames2 = ucwords(strtolower($lastname . ", " . $firstname . " " . $minicom1));
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="ornum" class="form-label">OR Number</label>
                  <p id="ornum" class="text-start fs-3"><?=$data6['or_id'];?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="typetran" class="form-label">Transaction Type</label>
                  <p id="typetran" class="text-start fs-3"><?=$data6['tran_type'];?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="residentreq" class="form-label">Resident</label>
                  <p id="typetran" class="text-start fs-3"><?=$fullnames2;?>.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="Total Amount" class="form-label">Type of Delivery</label>
                  <?php
                  if($data6['total_amount'] == 1){
                    echo '<p id="Total Amount" class="text-start fs-3">Pick-up</p>';
                  }
                  else{
                    echo '<p id="Total Amount" class="text-start fs-3">Deliver</p>';
                  }
                  ?>
                </div>
                <div class="col-md-6">
                  <label for="Total Amount" class="form-label">Total Amount</label>
                  <p id="Total Amount" class="text-start fs-3"><?=$data6['total_amount']?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="Price" class="form-label">Price</label>
                  <p id="Price" class="text-start fs-3"><?=$price;?></p>
                </div>
                <div class="col-md-4">
                  <label for="Quantity" class="form-label">Quantity</label>
                  <p id="Quantity" class="text-start fs-3"><?=$data6['quantity'];?></p>
                </div>
                <div class="col-md-4">
                  <label for="Quantity" class="form-label">Delivery Fee</label>
                  <p id="Quantity" class="text-start fs-3"><?=$data6['delivery_fee']?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="ordate" class="form-label">OR Date</label>
                  <p id="ordate" class="text-start fs-3"><?=$compdate4;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="purpose" class="form-label">Purpose</label>
                  <p id="purpose" style="word-break: break-word;" class="text-start fs-3"><?=$data6['purpose'];?></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <form action="transactions.php" method="post">
              <button type="submit" class="btn btn-success" name="verifytrans" value="<?=$data6['or_id'];?>">Approve</button>
              <button type="submit" class="btn btn-danger" name="verifydecline" value="<?=$data6['or_id'];?>">Deny</button>
              </form>
              <?php
            }
            ?>
            </div>
            
          </div>
        </div>
      </div>
      <!-- transaction details pop-up modal end -->

      <!-- transaction confirmation pop-up modal -->
      <div class="modal fade" id="approvetransmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Request</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to approve this transaction?</p>
            </div>
            <form action="transactions.php" method="post">
            <input type="hidden" name="deny1" value="<?=$deny1?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="transactionapprove" value="<?=$or_id?>">Yes</button>
              <button type="submit" class="btn btn-danger" name="pendingtransopen" value="<?=$or_id?>">No</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

      <!-- transaction confirmation pop-up modal -->
      <div class="modal fade" id="declinetransmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Request</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to decline this transaction?</p>
            </div>
            <form action="transactions.php" method="post">
            <input type="hidden" name="deny1" value="<?=$deny1?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="transactiondecline" value="<?=$or_id?>">Yes</button>
              <button type="submit" class="btn btn-danger" name="pendingtransopen" value="<?=$or_id?>">No</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

      <!-- release pop-up modal -->
      <div class="modal fade" id="releasemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Deliver Transaction</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="dateset" class="form-label">Date Delivered</label>
                  <p id="dateset" class="text-start fs-3"><?php echo $date_received;?></p>
                </div>
              </div>
              <form action="transactions.php" method="post">
              <input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
              <input type="hidden" name="status" value="<?php echo $status;?>">
              <input type="hidden" name="purpose" value="<?php echo $purpose;?>">
              <input type="hidden" name="tran_name" value="<?php echo $tran_name;?>">
              <input type="hidden" name="resident_id" value="<?php echo $resident_id;?>">
              <input type="hidden" name="fullname" value="<?php echo $fullname;?>">
              <input type="hidden" name="or_id" value="<?php echo $or_id;?>">
              <input type="hidden" name="or_date" value="<?php echo $or_date;?>">
              <input type="hidden" name="price" value="<?php echo $price;?>">
              <input type="hidden" name="quantity" value="<?php echo $quantity;?>">
              <input type="hidden" name="total_amount" value="<?php echo $total_amount;?>">
              <div class="row">
                <div class="col-md-12">
                  <label for="deliveryguy" class="form-label">Officer-in-Charge</label>
                  <input class="form-control" list="officers" id="deliveryguy" name="deliveryguy" required>
                  <datalist id="officers">
                    <option value="Delar Cruz, Juan P.">
                    <option value="Delar Cruz, Juan P.">
                    <option value="Delar Cruz, Juan P.">
                    <option value="Delar Cruz, Juan P.">
                  </datalist>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="approvingtrans">Confirm</button>
              <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- release pop-up end modal -->

      <!-- release confirmation pop-up modal -->
      <div class="modal fade" id="releaseconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Deliver Transaction</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="typetran" class="form-label">Type of Transaction</label>
                  <p id="typetran" class="text-start fs-3">Barangay Clearance</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="deliveryguy" class="form-label">Officer-in-Charge</label>
                  <p id="deliveryguy" class="text-start fs-3">Dela Cruz, Juan P.</p>
                </div>
                <div class="col-md-6">
                  <label for="daterel" class="form-label">Date Delivered</label>
                  <p id="daterel" class="text-start fs-3">02/26/2022</p>
                </div>
              </div>
              
            </div>
            <form action="transactions.php" method="post">
            <input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
            <input type="hidden" name="deliveryguy" value="<?php echo $deliveryguy;?>">
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- release confirmation pop-up end modal -->
      
      <!-- verification ogrelease confirmation pop-up modal -->
      <div class="modal fade" id="deletemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Deliver Transaction</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to release this transaction?</p>
            </div>
            <form action="transactions.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="" value="<?=$resident_id2?>">Confirm</button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#releaseconfmodal">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- verification ogrelease confirmation pop-up end modal -->

      <!-- denying pop-up modal -->
      <div class="modal fade" id="denying" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Transaction Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to deny this transaction?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Deny</button><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editmodal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- denying pop-up modal end -->

      <!-- generate report options pop-up modal -->
      <div class="modal fade" id="genreportmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Generate Report</h5>
            </div>
            <div class="modal-body">
            <form action="transactions.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="periodspan" class="form-label">Group Period</label>
                  <select class="form-select" aria-label="Default select example" name="periodspan" id="periodspan" required>
                    <option value="" hidden>Select</option>
                    <option value="1">6-month period</option>
                    <option value="2">1-year period</option>
                    <option value="3">All</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="printtransaction" value="1">Generate</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- generate report options pop-up end modal -->

      <!-- confirmation release pop-up modal -->
      <div class="modal fade" id="confreleasemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Transaction Process</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure this transaction has been printed or ready to distribute?</p>
            </div>
            <form action="transactions.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confreleaseprint" value="<?=$or_id3?>">Yes</button>
              <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">No</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- confirmation release pop-up end modal -->

      <!-- viewing transaction request pop-up modal -->
      <div class="modal fade" id="ctcviewmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Viewing CTC Details</h5>
            </div>
            <div class="modal-body">
              <?php
                $sql12 = "SELECT * FROM transactionlist_tbl WHERE or_id = '$or_id4'";
                $d12 =  $con->query($sql12);
                foreach($d12 as $data12){
                  $price = ($data12['total_amount'] -  $data12['delivery_fee']) / $data12['quantity'];
                  $compdate4 = date_create($data12['or_date']);
                  $compdate4 = date_format($compdate4, 'F d, Y (h:i:s a)');
                  $resident_id = $data12['resident_id'];
                  
                  /** query to get the appropriate data from the database */ 
                  $qry3 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
                  $dq3 =  $con->query($qry3);
                  foreach($dq3 as $qdata3){
                    $firstname = ($qdata3['firstname']);
                    $middlename = ($qdata3['middlename']);
                    $lastname = ($qdata3['lastname']);
                  }
                  $minicom1 = ($middlename[0]);
                  $fullnames2 = ucwords(strtolower($lastname . ", " . $firstname . " " . $minicom1));
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="ornum" class="form-label">OR Number</label>
                  <p id="ornum" class="text-start fs-3"><?=$data12['or_id'];?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="typetran" class="form-label">Transaction Type</label>
                  <p id="typetran" class="text-start fs-3"><?=$data12['tran_type'];?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="residentreq" class="form-label">Resident</label>
                  <p id="typetran" class="text-start fs-3"><?=$fullnames2;?>.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="Total Amount" class="form-label">Type of Delivery</label>
                  <?php
                  if($data12['total_amount'] == 1){
                    echo '<p id="Total Amount" class="text-start fs-3">Pick-up</p>';
                  }
                  else{
                    echo '<p id="Total Amount" class="text-start fs-3">Deliver</p>';
                  }
                  ?>
                </div>
                <div class="col-md-6">
                  <label for="Total Amount" class="form-label">Total Amount</label>
                  <p id="Total Amount" class="text-start fs-3"><?=$data12['total_amount']?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="Price" class="form-label">Price</label>
                  <p id="Price" class="text-start fs-3"><?=$price;?></p>
                </div>
                <div class="col-md-4">
                  <label for="Quantity" class="form-label">Quantity</label>
                  <p id="Quantity" class="text-start fs-3"><?=$data12['quantity'];?></p>
                </div>
                <div class="col-md-4">
                  <label for="Quantity" class="form-label">Delivery Fee</label>
                  <p id="Quantity" class="text-start fs-3"><?=$data12['delivery_fee']?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="ordate" class="form-label">OR Date</label>
                  <p id="ordate" class="text-start fs-3"><?=$compdate4;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="purpose" class="form-label">Purpose</label>
                  <p id="purpose" style="word-break: break-word;" class="text-start fs-3"><?=$data12['purpose'];?></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <?php
            }
            ?>
            </div>
            
          </div>
        </div>
      </div>
      <!-- transaction details pop-up modal end -->

      <!-- logout confirmation pop-up modal -->
      <div class="modal fade" id="logoutconfmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Any progress are automatically save.<br>Are you sure to logout?</p>
            </div>
            <div class="modal-footer">
              <a href="logout.php"><button type="button" class="btn btn-success">Yes</button></a>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button> 
            </div>
          </div>
        </div>
      </div>
      <!-- logout confirmation pop-up end modal -->


      
      
    </div>
  </main>
  <!-- main content -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
  <script src="js/bttop.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#choosetpye").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#detailmod").modal("show");
			});
		</script>';
	}
  elseif($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#rescode").modal("show");
			});
		</script>';
	}
  elseif($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confendmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 5) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 6) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#releasemodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 7) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#releaseconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 8) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 9) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#reqdetailmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 10) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#approvetransmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 11) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#declinetransmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 12) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confreleasemodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 13) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#ctcviewmodal").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
</body>
</html>