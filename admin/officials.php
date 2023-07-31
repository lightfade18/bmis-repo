<?php
  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();

  /** variable for the modals */
  $showModal = 0;

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }
  
  /** updating official initiation codes */
  if(isset($_REQUEST['updatelist'])){
    $official_id = $_REQUEST['updatelist'];
    $showModal = 1;
  }

  /** updating official codes */
  if(isset($_REQUEST['updatelist1'])){
    /** getting and filtering data gathered from the html form */
    $capt = filter_var($_REQUEST['capt'],FILTER_SANITIZE_NUMBER_INT);
    $official_id = $_REQUEST['official_id'];

    /** sql query in pdo format that check if the resident id input is already existing in the db */
    $sql1 = "SELECT * FROM officials_tbl WHERE official_id = $official_id";
    $d1 =  $con->query($sql1);
    foreach($d1 as $data1){
      $prev_id = ($data1['resident_id']);
    }
    if($prev_id == $capt){
      $showModal = 2;
    }
    else{
      try{
        /** sql query in pdo format that check if the resident id input is already existing in the db */
        $select_stmt1 = $con->prepare("SELECT resident_id FROM officials_tbl WHERE resident_id = :resident_id");
        $select_stmt1->execute([':resident_id' => $capt]);
        $row1 = $select_stmt1->fetch(PDO::FETCH_ASSOC);

        if(isset($row1['resident_id']) == $capt){
          $errorMSG = "The resident id you entered is already assigned to an another position.";
          $showModal = 1;
        }
        else{
          /** sql query in pdo format that check if the resident id input is already existing in the db */
          $select_stmt4 = $con->prepare("SELECT resident_id FROM penresident WHERE resident_id = :resident_id");
          $select_stmt4->execute([':resident_id' => $capt]);
          $row4 = $select_stmt4->fetch(PDO::FETCH_ASSOC);
          if(isset($row4['resident_id']) == $capt){
            
            $showModal = 2;
          }
          else{
            $errorMSG = "The resident id you entered doesn't exist.";
            $showModal = 1;
          }
        }
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    } 
  }

  /** confirmation of official update codes */
  if(isset($_REQUEST['editconf'])){
    $capt = $_REQUEST['editconf'];
    $official_id = $_REQUEST['official_id'];
    try{
      /** a pdo query that update input to the db */
      $select_stmt2 = $con->prepare("UPDATE officials_tbl SET resident_id=:resident_id WHERE official_id = $official_id");
      $select_stmt2->execute([':resident_id' => $capt]);
      $showModal = 3;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }
  
  /** removing official record codes */
  if(isset($_REQUEST['remove'])){
    try{
      $select_stmt3 = $con->prepare("UPDATE officials_tbl SET resident_id=:resident_id");
      $select_stmt3->execute([':resident_id' => null]);
      $showModal = 4;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** resetting page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: officials.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Officials</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/mainstyle.css" />
    <style>
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
      <a class="navbar-brand fw-bold text-uppercase" href="#"><img src="images/logo.jpg" alt="" width="45" height="45">brgy ###</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="bi bi-three-dots-vertical"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
          <ul class="navbar-nav ms-auto ">
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
          <a href="officials.php" class="nav-link px-3 bg-white active text-dark">
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
    <div class="container-fluid">
      <!-- content title -->
      <div class="row mb-2">
        <div class="col-md-12 fw-bold fs-3 text-danger">Officials</div>
      </div>
      <div class="container">
        <div class="row me-2">
          <div class="col-md-4 shadow-lg bg-body rounded">
            <div class="row">
              <div class="col-md-12 border bg-danger">
                <a href="officials.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-white">Brgy Officials</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border">
                <a href="sk_officials.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">SK Officials</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border">
                <a href="tanod.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Brgy. Tanod</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border">
                <a href="employees.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Brgy. Employees</p></a>
              </div>
            </div>
          </div>
          <div class="col-md-8 shadow-lg p-3 bg-body rounded">
            <form action="officials.php" method="post">
              <div class="container-fluid shadow-lg p-3 bg-body rounded">
                <div class="row m-2 border-bottom border-dark justify-content-between">
                  <div class="col-md-6">
                    <p class="text-left fs-4">Elected Brgy Officials</p>
                  </div>
                  <div class="col-md-4">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#removemodal">Remove All</button>
                  </div>
                </div>

                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="capt" class="form-label">Barangay Captain</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '1'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type='submit' id='capt' class='btn btn-primary' name='updatelist' value='1'>Update Official</button>
                  </div>
                  <div class="col-md-8">
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">1st Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '2'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);

                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="2">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">2nd Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '3'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="3">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                    
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">3rd Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '4'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="4">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">4th Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '5'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="5">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">5th Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '6'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="6">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">6th Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '7'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="7">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="kag1" class="form-label">7th Barangay Kagawad</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '8'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="8">Update Offcial</button>
                  </div>
                  <div class="col-md-8">
                    
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="sec" class="form-label">Barangay Secretary</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '9'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="9">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
                <div class="row p-1 m-2 border-bottom border-dark">
                  <label for="tre" class="form-label">Barangay Treasurer</label>
                  <div class="col-md-4">
                    <?php
                      /** geting data in the db codes */
                      $sql2 = "SELECT * FROM officials_tbl WHERE official_id = '10'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $resident_id = ($data2['resident_id']);
                      }
                      if($resident_id == null){
                        echo "<p class='text-left fs-5' id='kag1'>No assigned official.</p>";
                      }
                      else{
                        /** geting data in the db codes */
                        $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $firstname = ($data3['firstname']);
                          $middlename = ($data3['middlename']);
                          $lastname = ($data3['lastname']);
                          $profile_id = ($data3['profile_id']);
                        }
                        echo "<p class='text-left fs-5' id='kag1'>" . $lastname . ", " . $firstname . " " . substr($middlename, 0, 1) . ".</p>";
                      }
                    ?>
                    <button type="submit" id="capt" class="btn btn-primary" name="updatelist" value="10">Update Official</button>
                  </div>
                  <div class="col-md-8">
                    
                    <?php
                    if($resident_id == null){
                      
                    }
                    else{
                      if($profile_id == ''){
                        echo '<p class="fs-5 text-center">No profile image.</p>';
                      }
                      else{
                        ?>
                        <img src="../resident/images/<?=$profile_id?>" class="image-fluid rounded mx-auto d-block mb-3" alt="Resident profile image">
                        <?php
                      }
                    }
                    ?>
                  </div>
                </div>
              </div>
            </form>
          <div>
        </div>
      </div>

      <!-- view pop-up modal -->
      <div class="modal" id="captmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Personal Information</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3">Juan</p>
                </div>
                <div class="col-md-4">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3">Pinoy</p>
                </div>
                <div class="col-md-4">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3">Dela Cruz</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="age" class="form-label">Age</label>
                  <p id="age" class="text-start fs-3">40</p>
                </div>
                <div class="col-md-3">
                  <label for="birthdate" class="form-label">Age</label>
                  <p id="birthdate" class="text-start fs-3">01/10/1970</p>
                </div>
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="age" class="text-start fs-3">Male</p>
                </div>
                <div class="col-md-3">
                  <label for="citizenship" class="form-label">Citizenship</label>
                  <p id="citizenship" class="text-start fs-3">Filipino</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3">1234 Kahoy St. Brgy.21 Sampaloc, Manila</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3">Lawyer</p>
                </div>
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="age" class="text-start fs-3">Married</p>
                </div>
                <div class="col-md-4">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="age" class="text-start fs-3">Roman Catholic</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3">Law and Public Policy</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-5">
                  <label for="contact" class="form-label">Contact #</label>
                  <p id="age" class="text-start fs-3">09999999999</p>
                </div>
                <div class="col-md-7">
                  <label for="typeemail" class="form-label">Email</label>
                  <p id="age" class="text-start fs-3">delacruzj@gmail.com</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-2">
                  <label for="old" class="form-label">Old House #</label>
                  <p id="old" class="text-start fs-3">001</p>
                </div>
                <div class="col-md-2">
                    <label for="middlename" class="form-label">New House #</label>
                    <p id="new" class="text-start fs-3">100</p>
                </div>
                <div class="col-md-4">
                    <label for="street" class="form-label">Street</label>
                    <p id="street" class="text-start fs-3">Langka</p>
                </div>
                <div class="col-md-4">
                    <label for="village" class="form-label">Village/Subdivision</label>
                    <p id="village" class="text-start fs-3">Village D</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="fposition" class="form-label">Family Position</label>
                  <p id="age" class="text-start fs-3">Brother</p>
                </div>
                <div class="col-md-4">
                  <label for="housetype" class="form-label">Household Type</label>
                  <p id="housetype" class="text-start fs-3">Permanent</p>
                </div>
                <div class="col-md-5">
                  <label for="ptext1">Household Head</label>
                  <p id="ptext1" class="text-start fs-3">Dela Cruz, Juan P.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="username" class="form-label">Username</label>
                  <p id="username" class="text-start fs-3">delacruzJ70</p>
                </div>
                <div class="col-md-4">
                  <label for="password" class="form-label">Password</label>
                  <p id="password" class="text-start fs-3">1970110010dj</p>
                </div>
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">Date Registered</label>
                  <p id="dcreated" class="text-start fs-3">01/12/2002</p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#editmodal">Change</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- view pop-up modal -->
      
      <!-- edit pop-up modal -->
      <div class="modal" id="editmodal1" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Update Information</h5>
              </div>
              <div class="modal-body">
                <form action="officials.php" method="post">
                  <input type="hidden" name="official_id" value="<?php echo $official_id;?>">
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
                  <div class="row">
                    <div class="col-md-12">
                      <label for="officer" class="form-label">Resident ID</label>
                      <input class="form-control" list="officers" id="officer" name="capt" required>
                      <datalist id="officers">
                        <?php
                          $sql1 = "SELECT * FROM penresident";
                          $d1 =  $con->query($sql1);
                          foreach($d1 as $data1){
                          ?>
                        <option value="<?=$data1['resident_id'];?>"><?=$data1['lastname'];?>, <?=$data1['firstname'];?> <?=$data1['middlename'];?></option>
                          <?php
                          }
                        ?>
                      </datalist>
                    </div>
                  </div>
                  <!-- <div class="row">
                    <div class="col-md-12">
                      <label class="form-label" for="customFile">Profile Image</label>
                      <input type="file" class="form-control" id="customFile" />
                    </div>
                  </div> -->
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="updatelist1">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit pop-up modal -->

      <!-- edit confirmation pop-up modal -->
      <div class="modal" id="confmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="officials.php" method="post">
              <input type="hidden" name="official_id" value="<?php echo $official_id;?>">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Update Information</h5>
              </div>
              <div class="modal-body">
                <?php
                  if($official_id == 1){
                    echo"<p class='text-center fs-4'>Are you to assign or change the Barangay Captain?</p>";
                  }
                  elseif($official_id == 9){
                    echo"<p class='text-center fs-4'>Are you to assign or change the Barangay Secretary?</p>";
                  }
                  elseif($official_id == 10){
                    echo"<p class='text-center fs-4'>Are you to assign or change the Barangay Treasurer?</p>";
                  }
                  else{
                    echo"<p class='text-center fs-4'>Are you to assign or change this Barangay Kagawad?</p>";
                  }
                ?>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="editconf" value="<?php echo $capt; ?>">Yes</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#editmodal1">No</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit confirmation pop-up end modal -->

      <!-- aprroving pop-up modal -->
      <div class="modal fade" id="confeditendmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Official Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Official information sucessfully updated.</p>
            </div>
            <div class="modal-footer">
              <form action="officials.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- aprroving pop-up modal end -->

      <!-- removing all officials pop-up modal -->
      <div class="modal" id="removemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Update Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you to remove all current assigned officials?</p>
            </div>
            <form action="officials.php" method="post">
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="remove">Yes</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- removing all officials pop-up end modal -->

      <!-- confirmation of removing all officials pop-up modal -->
      <div class="modal" id="removeconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Official Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Officials information sucessfully remove.</p>
            </div>
            <div class="modal-footer">
              <form action="officials.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- confirmation of removing all officials pop-up end modal -->

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
				$("#editmodal1").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confeditendmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#removeconfmodal").modal("show");
			});
		</script>';
	}
  ?>
</body>
</html>