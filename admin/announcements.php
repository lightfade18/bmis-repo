<?php
  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  date_default_timezone_set('Asia/Manila');
  $currdate = date('Y-m-d\TH:i:s');

  /** code for couting to be deliver transactions */
  $sql11 = "SELECT COUNT(id) FROM announcements_tbl WHERE status = 'Posted'";
  $res11 = $con->query($sql11);
  $cnt1 = $res11->fetchColumn();

  # sql that automatically removed a post if their validation date is due 
  $sql4 = "SELECT validation, id, image, type FROM announcements_tbl WHERE status='Posted'";
  $d4 =  $con->query($sql4);
  $statusf = 'Removed';
  foreach($d4 as $data4){
    $datecheck = $data4['validation'];
    $datecheck = date('Y-m-d\TH:i:s', strtotime($datecheck . ' + 0 day'));
    $id4 = $data4['id'];
    if($datecheck < $currdate) {
      try{
        if($data4['type'] == 'Event'){
            unlink("images/".$data4['image']);
        }
        $sql6 = $con->prepare("DELETE FROM announcements_tbl WHERE id=:id");
        $sql6->execute([':id' => $id4]);
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
  }

  /** variable for the modals */
  $showModal = 0;

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  /** choosingtype codes */
  if(isset($_REQUEST['posttype'])){
    $selection = $_REQUEST['selection'];
    if($selection == 1){
      $showModal = 1;
    }
    elseif($selection == 3){
      $id = $_REQUEST['id'];
      $title = $_REQUEST['title'];
      $details = $_REQUEST['details'];
      $edits = $_REQUEST['edits'];
      $validation = date_create($_REQUEST['validation']);
      $validation = date_format($validation, 'Y-m-d\TH:i:s');
      $showModal = 1;
    }
    elseif($selection == 2){
      $showModal = 8;
    }
    elseif($selection == 4){
      // query to delete the newest data input from the database
      // $newImageName = $_REQUEST['newImageName'];
      // $errorType = $_REQUEST['errorType'];
      $title = $_REQUEST['title'];
      $details = $_REQUEST['details'];
      $coordinator = $_REQUEST['coordinator'];
      $validation = date_create($_REQUEST['validation']);
      $validation = date_format($validation, 'Y-m-d\TH:i:s');
      $showModal = 8;
    }
  }

  /** preparing and filtering announcement codes */
  if(isset($_REQUEST["submit"])){
    $type = "Announcement";
    $title = filter_var($_REQUEST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_REQUEST['details'],FILTER_SANITIZE_STRING);
    $validation = $_REQUEST['validation'];
    $currdate1 = date('Y-m-d H:i:s');
    $alloweddate = date('Y-m-d\TH:i:s', strtotime($currdate1));
    if($validation < $alloweddate){
      $errorMSG = 'Past date is not allowed';
      $selection = 3;
      $showModal = 1;
    }
    else{
      $edits = $_REQUEST['edits'];
      if($edits == 1){
        $id = $_REQUEST['id'];
      }
      $date3 = date_create($validation);
      $date3 = date_format($date3, 'm-d-Y h:i:s a');
      $publisher = 'Brgy. Secretary';
      $showModal = 2;
    }
    
  }

  /** adding announcement into db codes */
  if(isset($_REQUEST['addannounceconf'])){
    $type = $_REQUEST['type'];
    $title = $_REQUEST['title'];
    $details = $_REQUEST['details'];
    $validation = $_REQUEST['validation'];
    $publisher = $_REQUEST['publisher'];
    $edits = $_REQUEST['edits'];
    $status = "Posted";
    $publish_date = date('Y-m-d H:i:s');
    try{
      # preparing a pdo query that adds values to the db
      $image = "none";
      # identify if it is an insert or a update query
      if($edits == 1){
        $id = $_REQUEST['id'];
        $sql3 = $con->prepare("UPDATE announcements_tbl SET type=:type, title=:title, details=:details, validation=:validation, status=:status WHERE id=:id");
        /** execute query */
        $sql3->execute(
          [
            ':type' => $type, 
            ':title' => $title, 
            ':details' => $details,
            ':validation' => $validation, 
            ':status' => $status,
            ':id' => $id
          ]
        );
        $showModal = 4;
      }
      else{
        $query = $con->prepare("INSERT INTO announcements_tbl (type, title, details, publish_date, validation, image, publisher, status)
        VALUES (:type, :title, :details, :publish_date, :validation, :image, :publisher, :status)");
        if($query->execute(
          [
          ':type' => $type,
          ':title' => $title,
          ':details' => $details,
          ':publish_date' => $publish_date,
          ':validation' => $validation,
          ':image' => $image,
          ':publisher' => $publisher,
          ':status' => $status
          ]
        )
        ){
        $showModal = 4;
        }
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }

  /** editing announcement codes */
  if(isset($_REQUEST['editann'])){
    $id = $_REQUEST['editann'];
    $sql2 = "SELECT * FROM announcements_tbl WHERE id=$id";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $title2 = $data2['title'];
      $details2 = $data2['details'];
      $validation2 = date_create($data2['validation']);
    }
    $validation2 = date_format($validation2, 'Y-m-d\TH:i:s');
    $showModal = 5;
  }

  /** preparing, filtering and adding events codes */
  if(isset($_POST["submitevent"])){
    # getting and filtering inputs
    $type = "Event";
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'],FILTER_SANITIZE_STRING);
    $coordinator = filter_var($_POST['coordinator'],FILTER_SANITIZE_STRING);
    $validation = $_POST['validation'];
    $edits = $_POST['edits'];
    if($edits == '1'){
      $edits = $_POST['edits'];
    }
    $publisher = 'Brgy. Secretary';
    $status = "Posted";
    $publish_date = date('Y-m-d H:i:s');
    $date3 = date_create($validation);
    $date3 = date_format($date3, 'm-d-Y h:i:s a');
    $showModal = 9;
  }

  /** adding event into db codes */
  if(isset($_POST['addeveconf'])){
    $type = "Event";
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'],FILTER_SANITIZE_STRING);
    $coordinator = filter_var($_POST['coordinator'],FILTER_SANITIZE_STRING);
    $validation = $_POST['validation'];
    $edits = $_POST['edits'];
    $status = "Posted";
    $publisher = 'Brgy. Secretary';
    $publish_date = date('Y-m-d H:i:s');
    $date3 = date_create($validation);
    $date3 = date_format($date3, 'm-d-Y h:i:s a');
    if($edits == '0'){
      if($_FILES["image"]["error"] == 4){
        $newImageName = 'none1';
        # trycatch pdo prepared statement
        try{
          $query1 = $con->prepare("INSERT INTO announcements_tbl (type, title, details, coordinator, publish_date, validation, image, publisher, status)
          VALUES (:type, :title, :details, :coordinator, :publish_date, :validation, :image, :publisher, :status)");
          if($query1->execute(
            [
            ':type' => $type,
            ':title' => $title,
            ':details' => $details,
            ':coordinator' => $coordinator,
            ':publish_date' => $publish_date,
            ':validation' => $validation,
            ':image' => $newImageName,
            ':publisher' => $publisher,
            ':status' => $status
            ]
          )
          ){
            $showModal = 4;
          }
        }
        catch(PDOException $e){
          $pdoError = $e->getMesage();
        }
      }
      else{
        # preparing image variables
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
        # variable that check and prepare the file extension
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        # prepare final image variable
        $newImageName = uniqid();
        $newImageName .= '.' . $imageExtension;
        # check image errors
        if ( !in_array($imageExtension, $validImageExtension) ){
          $errorMSG9 = 'Invalid image extension';
          $showModal = 9;
        }
        else if($fileSize > 1000000){
          $errorMSG9 = 'Invalid image size';
          $showModal = 9;
        }
        else{
          move_uploaded_file($tmpName, 'images/' . $newImageName);
          # trycatch pdo prepared statement
          try{
            $query1 = $con->prepare("INSERT INTO announcements_tbl (type, title, details, coordinator, publish_date, validation, image, publisher, status)
            VALUES (:type, :title, :details, :coordinator, :publish_date, :validation, :image, :publisher, :status)");
            if($query1->execute(
              [
              ':type' => $type,
              ':title' => $title,
              ':details' => $details,
              ':coordinator' => $coordinator,
              ':publish_date' => $publish_date,
              ':validation' => $validation,
              ':image' => $newImageName,
              ':publisher' => $publisher,
              ':status' => $status
              ]
            )
            ){
              $showModal = 4;
            }
          }
          catch(PDOException $e){
            $pdoError = $e->getMesage();
          }
        }
      }
    }
    else{
      $id = $_POST['id'];
      if($_FILES["image"]["error"] == 4){
        $newImageName = 'none1';
        # trycatch pdo prepared statement
        try{
          $sql9 = $con->prepare("UPDATE announcements_tbl SET type=:type, title=:title, details=:details, coordinator=:coordinator, validation=:validation, status=:status WHERE id=:id");
          /** execute query */
          if($sql9->execute
            ([
              ':type' => $type, 
              ':title' => $title, 
              ':details' => $details,
              ':coordinator' => $coordinator,
              ':validation' => $validation, 
              ':status' => $status,
              ':id' => $id
            ])
          ){
            $showModal = 4;
          }
        }
        catch(PDOException $e){
          $pdoError = $e->getMesage();
        }
      }
      else{
        # preparing image variables
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
        # variable that check and prepare the file extension
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        # prepare final image variable
        $newImageName = uniqid();
        $newImageName .= '.' . $imageExtension;
        # check image errors
        if ( !in_array($imageExtension, $validImageExtension) ){
          $errorMSG9 = 'Invalid image extension';
          $showModal = 9;
        }
        else if($fileSize > 1000000){
          $errorMSG9 = 'Invalid image size';
          $showModal = 9;
        }
        else{
          move_uploaded_file($tmpName, 'images/' . $newImageName);
          # trycatch pdo prepared statement
          try{
            $query1 = $con->prepare("INSERT INTO announcements_tbl (type, title, details, coordinator, publish_date, validation, image, publisher, status)
            VALUES (:type, :title, :details, :coordinator, :publish_date, :validation, :image, :publisher, :status)");
            if($query1->execute(
              [
              ':type' => $type,
              ':title' => $title,
              ':details' => $details,
              ':coordinator' => $coordinator,
              ':publish_date' => $publish_date,
              ':validation' => $validation,
              ':image' => $newImageName,
              ':publisher' => $publisher,
              ':status' => $status
              ]
            )
            ){
              $showModal = 4;
            }
          }
          catch(PDOException $e){
            $pdoError = $e->getMesage();
          }
        }
      }
    }
    
  }

  /** editing event codes */
  if(isset($_REQUEST['editeve'])){
    $id = $_REQUEST['editeve'];
    $sql8 = "SELECT * FROM announcements_tbl WHERE id = '$id'";
    $d8 =  $con->query($sql8);
    foreach($d8 as $data8){
      $title8 = $data8['title'];
      $details8 = $data8['details'];
      $validation8 = date_create($data8['validation']);
      $coordinator8 = $data8['coordinator'];
    }
    $validation8 = date_format($validation8, 'Y-m-d\TH:i:s');
    $showModal = 10;
  }

  if(isset($_REQUEST['confediteve'])){
    $id = $_REQUEST['id'];
    $type = "Event";
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'],FILTER_SANITIZE_STRING);
    $coordinator = filter_var($_POST['coordinator'],FILTER_SANITIZE_STRING);
    $validation = $_POST['validation'];
    $showModal = 11;
  }

  
  if(isset($_REQUEST['confirmedit'])){
    $id = $_REQUEST['id'];
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'],FILTER_SANITIZE_STRING);
    $coordinator = filter_var($_POST['coordinator'],FILTER_SANITIZE_STRING);
    $validation = $_POST['validation'];
    # trycatch pdo prepared statement
    try{
      $sql10 = $con->prepare("UPDATE announcements_tbl SET title=:title, details=:details, coordinator=:coordinator, validation=:validation WHERE id=:id");
      /** execute query */
      if($sql10->execute
        ([
          ':title' => $title, 
          ':details' => $details,
          ':coordinator' => $coordinator,
          ':validation' => $validation,
          ':id' => $id
        ])
      ){
        $showModal = 4;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** opening deletemodal codes */
  if(isset($_REQUEST['deleteann'])){
    $postimage = $_REQUEST['postimage'];
    $id = $_REQUEST['deleteann'];
    $showModal = 6;
  }

  /** deleting resident codes */
  if(isset($_REQUEST['deleteconf'])){
    $id = $_REQUEST['deleteconf'];
    $postimage = $_REQUEST['postimage'];
    try{
      if($postimage != 'none'){
          unlink("images/".$postimage);
      }
      $sql5 = $con->prepare("DELETE FROM announcements_tbl WHERE id=:id");
      $sql5->execute([':id' => $id]);
      $showModal = 7;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** reloading page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: announcements.php");
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements/Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/mainstyle.css" />
    <style>
      p{
        word-wrap: break-word;
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
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>
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
            <ul class="navbar-nav ms-auto">
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
            <div class="card card-body bg-danger">
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
            <div class="card card-body bg-danger">
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
          <a href="transactions.php" class="nav-link px-3 text-white">
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
          <a href="announcements.php" class="nav-link px-3 bg-white active text-dark">
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
        <div class="col-md-12 fw-bold fs-3 text-danger">Announcements</div>
      </div>
      <div class="row">
        <div class="col-sm-12">
          <p class="text-end"><button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#choosetype">Publish Post</button></p>
        </div>
      </div>
      <hr class="bg-dark border-5 border-top border-dark">
      <div class="row border shadow-lg p-3 mb-5 m-1 bg-body rounded">
        <?php
        $sql1 = "SELECT * FROM announcements_tbl WHERE status='Posted' ORDER BY validation";
        $d1 =  $con->query($sql1);
        if($cnt1 == 0){
          echo '<p class="text-center fs-4">No posted announcement/s.</p>';
        }
        else{
          foreach($d1 as $data1){
            $date = date_create($data1['publish_date']);
            $date = date_format($date, 'm-d-Y h:i:s a');
            $date2 = date_create($data1['validation']);
            $date2 = date_format($date2, 'm-d-Y h:i:s a');
            ?>
            <div class="col-md-6 mb-3">
              <div class="card h-100 border shadow-lg p-3 mb-5 bg-body rounded">
                <?php
                if($data1['image'] == 'none'){
                  echo '<img src="images/annposter.jpg" class="card-img-top image-fluid" alt="...">';
                }
                elseif($data1['image'] == 'none1'){
                  echo '<img src="images/eveposter.jpg" class="card-img-top image-fluid" alt="...">';
                }
                else{
                  echo '<img src="images/'.$data1['image'].'" class="card-img-top image-fluid" alt="...">';
                }
                ?>
                <div class="card-img-overlay text-end">
                  <div class="dropdown">
                    <button type="button" class="btn btn-dark btn-circle" id="optionannounce" data-bs-toggle="dropdown" aria-expanded="false">
                      <i class="bi bi-three-dots"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border shadow-lg p-1 bg-body rounded" aria-labelledby="optionannounce">
                    <form action="announcements.php" method="post">
                      <?php 
                      if($data1['type'] == 'Announcement'){
                        echo '<li><button type="submit" class="btn btn-link" name="editann" value="' . $data1['id'] . '">Edit</button></li>';
                      }
                      else{
                        echo '<li><button type="submit" class="btn btn-link" name="editeve" value="' . $data1['id'] . '">Edit</button></li>';
                      }
                      ?>
                      <input type="hidden" name="postimage" value="<?=$data1['image']?>">
                      <li><button type="submit" class="btn btn-link" name="deleteann" value="<?=$data1['id']?>">Delete</button></li>
                    </form>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <h4 class="card-title fw-bolder"><?=$data1['title']?></h4>
                  <p class="card-text fs-5"><?=$data1['details']?></p>
                  <p class="card-text fs-5">Type: <?=$data1['type']?></p>
                  <p class="card-text fs-5">Published By: <?=$data1['publisher']?></p>
                  <p class="card-text fs-5">Posted On: <?=$date?></p>
                  <p class="card-text fs-5">Published Until: <?=$date2?></p>
                </div>
              </div>
            </div>
            <?php
          }
        }
        ?>
      </div>

      <!-- choosing type pop-up modal -->
      <div class="modal fade" id="choosetype" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Choose Type</h5>
            </div>
            <form action="announcements.php" method="post">
            <div class="modal-body">
              <?php
                if(isset($errorMSG)){
                  echo "<div class='alert alert-warning d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG.
                  "</div>
                </div>";
                }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Post Type</label>
                  <select class="form-select" aria-label="Default select example" id="type" name="selection" required>
                    <option value="" hidden>Select Type</option>
                    <option value="1">Announcement</option>
                    <option value="2">Event</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="posttype">Select</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- choosing type pop-up modal -->
      
      <!-- adding annonncement pop-up modal -->
      <div class="modal" id="addannmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG.
                  "</div>
                </div>";
                }
              ?>
              <form action="announcements.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Type</label>
                  <input type="text" class="form-control" id="type" value="Announcement" disabled>
                </div>
              </div>
              <?php
              if($selection == 1){
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="title" class="form-label">Title</label>
                          <input type="text" class="form-control" id="title" name="title" placeholder="Enter here..." required>
                        </div>
                      </div>
                      <input type="hidden" name="edits" value="0">
                      <div class="row-3">
                        <div class="col-md-12">
                          <div class="form-group green-border-focus" id="anndetails">
                            <label for="anndetails">Details</label>
                            <textarea class="form-control" id="anndetails" rows="5" name="details" required></textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label class="form-label" for="validation">Published Until</label>
                          <input type="datetime-local" class="form-select" id="validation" name="validation" required>
                        </div>
                      </div>';
              }
              elseif($selection == 3){
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="title" class="form-label">Title</label>
                          <input type="text" class="form-control" id="title" name="title" value="'.$title.'" required>
                        </div>
                      </div>
                      <input type="hidden" name="edits" value="'.$edits.'">
                      <input type="hidden" name="id" value="'.$id.'">
                      <div class="row-3">
                        <div class="col-md-12">
                          <div class="form-group green-border-focus" id="anndetails">
                            <label for="anndetails">Details</label>
                            <textarea class="form-control" id="anndetails" rows="5" name="details" required>'.$details.'</textarea>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-12">
                          <label class="form-label" for="validation">Published Until</label>
                          <input type="datetime-local" class="form-select" id="validation" name="validation" value="'.$validation.'" required>
                        </div>
                      </div>';
              }
              ?>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit">Publish</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding annonncement pop-up modal end -->

      <!-- adding confirmation pop-up modal -->
      <div class="modal" id="addconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Type</label>
                  <p id="title" class="text-start fs-3">Announcement</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Type</label>
                  <p id="title" class="text-start fs-3">Announcement</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Announcement Title</label>
                  <p id="title" class="text-start fs-3"><?php echo $title?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="anndetail" class="form-label">Announcement Details</label>
                  <p id="title" class="text-start fs-3"><?php echo $details?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="publisher" class="form-label">Publisher</label>
                  <p id="title" class="text-start fs-3"><?php echo $publisher?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="until" class="form-label">Valid Until</label>
                  <p id="title" class="text-start fs-3"><?php echo $date3?></p>
                </div>
              </div>
            </div>
            <form action="announcements.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <input type="hidden" name="title" value="<?php echo $title?>">
            <input type="hidden" name="details" value="<?php echo $details?>">
            <input type="hidden" name="validation" value="<?php echo $validation?>">
            <input type="hidden" name="publisher" value="<?php echo $publisher?>">
            <input type="hidden" name="selection" value="3">
            <input type="hidden" name="edits" value="<?=$edits?>">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="addannounceconf">Save</button>
              <button type="submit" class="btn btn-secondary" name="posttype">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- adding notification pop-up modal -->
      <div class="modal fade" id="addconfmodalfinal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Announcement/Events has been published/updated.</p>
            </div>
            <form action="announcements.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding notification pop-up end modal -->

      <!-- edit pop-up modal -->
      <div class="modal" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG.
                  "</div>
                </div>";
                }
              ?>
              <form action="announcements.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Type</label>
                  <input type="text" class="form-control" id="incha" value="Announcement" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo $title2;?>" required>
                </div>
              </div>
              <div class="row-3">
                <div class="col-md-12">
                  <div class="form-group green-border-focus" id="anndetails">
                    <label for="anndetails">Details</label>
                    <textarea class="form-control" id="anndetails" rows="5" name="details" required><?php echo $details2;?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="validation">Published Until</label>
                  <?php
                  if($validation2){
                    echo '<input type="datetime-local" class="form-select" id="validation" name="validation" value="'.$validation2.'" required>';
                  }else{
                    echo '<input type="datetime-local" class="form-select" id="validation" name="validation"  required>';
                  }
                  ?>
                </div>
              </div>
            </div>
            <input type="hidden" name="edits" value="1">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit">Publish</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit pop-up modal end -->

      <!-- delete confirmation pop-up modal -->
      <div class="modal fade" id="deletemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to remove this post?</p>
            </div>
            <form action="announcements.php" method="post">
            <input type="hidden" name="postimage" value="<?=$postimage?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="deleteconf" value="<?=$id?>">Yes</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

      <!-- delete notification pop-up modal -->
      <div class="modal fade" id="deleteconfmodalfinal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Announcement successfully removed.</p>
            </div>
            <form action="announcements.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- delete notification pop-up end modal -->

      <!-- adding events pop-up modal -->
      <div class="modal" id="addevemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Event Information</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG.
                  "</div>
                </div>";
                }
              ?>
              <form action="announcements.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Type</label>
                  <input type="text" class="form-control" id="type" value="Event" disabled>
                </div>
              </div>
              <?php
              if($selection == 4){
                echo'<div class="row">
                      <div class="col-md-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="'.$title.'" required>
                      </div>
                    </div>
                    <input type="hidden" name="edits" value="0">
                    <div class="row-3">
                      <div class="col-md-12">
                        <div class="form-group green-border-focus" id="anndetails">
                          <label for="anndetails">Details</label>
                          <textarea class="form-control" id="anndetails" rows="5" name="details" required>'.$details.'</textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label class="form-label" for="coor">Event Coordinator</label>
                        <input type="text" class="form-control" id="coor" name="coordinator" value="'.$coordinator.'" required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label class="form-label" for="validation">Published Until</label>
                        <input type="datetime-local" class="form-select" id="validation" name="validation" value="'.$validation.'" required>
                      </div>
                    </div>';
              }
              else{
                echo'<div class="row">
                      <div class="col-md-12">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" placeholder="Enter title here..." required>
                      </div>
                    </div>
                    <input type="hidden" name="edits" value="0">
                    <div class="row-3">
                      <div class="col-md-12">
                        <div class="form-group green-border-focus" id="anndetails">
                          <label for="anndetails">Details</label>
                          <textarea class="form-control" id="anndetails" rows="5" name="details" placeholder="Enter details here..." required></textarea>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label class="form-label" for="coor">Event Coordinator</label>
                        <input type="text" class="form-control" id="coor" name="coordinator" placeholder="Enter fullname here..." required>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label class="form-label" for="validation">Published Until</label>
                        <input type="datetime-local" class="form-select" id="validation" name="validation" required>
                      </div>
                    </div>';
              }
              ?>
              <!-- <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="image">Event Poster</label>
                  <input type="file" class="form-control" name="image" id = "image" accept=".jpg, .jpeg, .png" value="">
                </div>
              </div> -->
              <input type="hidden" name="edits" value="0">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submitevent">Confirm</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding events pop-up modal end -->

      <!-- adding confirmation pop-up modal -->
      <div class="modal" id="addeveconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Event Information</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG9)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG9.
                  "</div>
                </div>";
                }
              ?>
              <form action="announcements.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="image">Event Poster</label>
                  <input type="file" class="form-control" name="image" id = "image" accept=".jpg, .jpeg, .png" value="">
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Type</label>
                  <p id="title" class="text-start fs-3">Event</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Announcement Title</label>
                  <p id="title" class="text-start fs-3"><?php echo $title?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="anndetail" class="form-label">Announcement Details</label>
                  <p id="title" class="text-start fs-3"><?php echo $details?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="coordinator" class="form-label">Event Coordinator</label>
                  <p id="coordinator" class="text-start fs-3"><?php echo $coordinator?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="publisher" class="form-label">Publisher</label>
                  <p id="title" class="text-start fs-3"><?php echo $publisher?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="until" class="form-label">Valid Until</label>
                  <p id="title" class="text-start fs-3"><?php echo $date3?></p>
                </div>
              </div>
              
            </div>
            <input type="hidden" name="title" value="<?= $title?>">
            <input type="hidden" name="details" value="<?= $details?>">
            <input type="hidden" name="validation" value="<?= $validation?>">
            <input type="hidden" name="publisher" value="<?= $publisher?>">
            <input type="hidden" name="coordinator" value="<?=$coordinator?>">
            <input type="hidden" name="edits" value="<?=$edits?>">
            <input type="hidden" name="selection" value="4">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="addeveconf">Publish</button>
              <button type="submit" class="btn btn-secondary" name="posttype">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- edit event pop-up modal -->
      <div class="modal" id="editevemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Event Information</h5>
            </div>
            <div class="modal-body">
              <?php
                if(isset($errorMSG)){
                  echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                  <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                  <div>"
                    .$errorMSG.
                  "</div>
                </div>";
                }
              ?>
              <form action="announcements.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Type</label>
                  <input type="text" class="form-control" id="incha" value="Event" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?= $title8;?>" required>
                </div>
              </div>
              <div class="row-3">
                <div class="col-md-12">
                  <div class="form-group green-border-focus" id="anndetails">
                    <label for="anndetails">Details</label>
                    <textarea class="form-control" id="anndetails" rows="5" name="details" required><?= $details8;?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="coordinator" class="form-label">Event Coordinator</label>
                  <input type="text" class="form-control" id="coordinator" name="coordinator" value="<?= $coordinator8;?>" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="validation">Published Until</label>
                  <?php
                  if($validation8){
                    echo '<input type="datetime-local" class="form-select" id="validation" name="validation" value="'.$validation8.'" required>';
                  }else{
                    echo '<input type="datetime-local" class="form-select" id="validation" name="validation"  required>';
                  }
                  ?>
                </div>
              </div>
            </div>
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="confediteve">Save</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit event pop-up modal end -->

      <!-- Error display pop-up modal -->
      <div class="modal fade" id="editconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Event Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to save this changes?</p>
            </div>
            <form action="announcements.php" method="post">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <input type="hidden" name="title" value="<?php echo $title?>">
            <input type="hidden" name="details" value="<?php echo $details?>">
            <input type="hidden" name="validation" value="<?php echo $validation?>">
            <input type="hidden" name="publisher" value="<?php echo $publisher?>">
            <input type="hidden" name="coordinator" value="<?php echo $coordinator?>">
            <input type="hidden" name="newImageName" value="<?php echo $newImageName?>">
            <input type="hidden" name="errorType" value="<?php echo $errorType?>">
            <input type="hidden" name="selection" value="4">
            <input type="hidden" name="edits" value="<?=$edits?>">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="confirmedit" value="">OK</button>
              <button type="submit" class="btn btn-secondary" name="editeve" value="<?=$id?>">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding notification pop-up end modal -->

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
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="js/bttop.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addannmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addconfmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#choosetype").modal("show");
			});
		</script>';
	}
  if($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addconfmodalfinal").modal("show");
			});
		</script>';
	}
  if($showModal == 5) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 6) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#deletemodal").modal("show");
			});
		</script>';
	}
  if($showModal == 7) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#deleteconfmodalfinal").modal("show");
			});
		</script>';
	}
  if($showModal == 8) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addevemodal").modal("show");
			});
		</script>';
	}
  if($showModal == 9) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addeveconfmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 10) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editevemodal").modal("show");
			});
		</script>';
	}
  if($showModal == 11) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editconfmodal").modal("show");
			});
		</script>';
	}
  
  ?>
</body>
</html>