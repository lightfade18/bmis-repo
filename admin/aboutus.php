<?php
  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  $showModal = 0;

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  /** geting data in the db codes */
  $sql1 = "SELECT * FROM brgydetails_tbl";
  $d1 =  $con->query($sql1);
  foreach($d1 as $data1){
    $id = ($data1['id']);
    $brgy_name = ($data1['brgy_name']);
    $brgy_alias = ($data1['brgy_alias']);
    $aboutus = ($data1['aboutus']);
    $mission = ($data1['mission']);
    $vision = ($data1['vision']);
    $demographics = ($data1['demographics']);
    $location = ($data1['location']);
    $telephone = ($data1['telephone']);
    $smart = ($data1['smart']);
    $globe = ($data1['globe']);
    $fb = ($data1['fb']);
    $twitter = ($data1['twitter']);
    $instagram = ($data1['instagram']);
    $email = ($data1['email']);
  }

  /** editing brgy details codes */
  if(isset($_REQUEST['updatedetail'])){
    $id = $_REQUEST['updatedetail'];
    $showModal = 1;
  }

  
  if(isset($_REQUEST['getupdate'])){
    $id = $_REQUEST['id'];
    if($id == 9){
      $telephone = filter_var($_REQUEST['telephone'],FILTER_SANITIZE_STRING);
      $smart = filter_var($_REQUEST['smart'],FILTER_SANITIZE_STRING);
      $globe = filter_var($_REQUEST['globe'],FILTER_SANITIZE_STRING);
      $showModal = 3;
    }
    else{
      $content = filter_var($_REQUEST['content'],FILTER_SANITIZE_STRING);
      $showModal = 3;
    }
    
  }

  /** updating db codes */
  if(isset($_REQUEST['updateconf'])){
    $id = $_REQUEST['id'];
    if($id == 1){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET brgy_name=:brgy_name WHERE id = '1'");
        /** execute query */
        $sql2->execute([':brgy_name' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 2){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET brgy_alias=:brgy_alias WHERE id = '1'");
        /** execute query */
        $sql2->execute([':brgy_alias' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 3){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET aboutus=:aboutus WHERE id = '1'");
        /** execute query */
        $sql2->execute([':aboutus' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 4){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET mission=:mission WHERE id = '1'");
        /** execute query */
        $sql2->execute([':mission' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 5){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET vision=:vision WHERE id = '1'");
        /** execute query */
        $sql2->execute([':vision' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 6){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET demographics=:demographics WHERE id = '1'");
        /** execute query */
        $sql2->execute([':demographics' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 7){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET location=:location WHERE id = '1'");
        /** execute query */
        $sql2->execute([':location' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    elseif($id == 8){
      $content = $_REQUEST['content'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET email=:email WHERE id = '1'");
        /** execute query */
        $sql2->execute([':email' => $content]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
    else{
      $telephone = $_REQUEST['telephone'];
      $smart = $_REQUEST['smart'];
      $globe = $_REQUEST['globe'];
      try{
        /** preparing a pdo query that update values to the db */
        $sql2 = $con->prepare("UPDATE brgydetails_tbl SET telephone=:telephone, smart=:smart, globe=:globe WHERE id = '1'");
        /** execute query */
        $sql2->execute([':telephone' => $telephone, ':smart' => $smart, ':globe' => $globe]);
        $showModal = 2;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
  }

  /** reloading page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: aboutus.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/mainstyle.css" />
</head>
<body>
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
          <a href="aboutus.php" class="nav-link px-3 bg-white active mb-5 text-dark">
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
      <div class="row mb-2">
        <div class="col-md-12 fw-bold fs-3 text-danger">Settings</div>
      </div>
      <div class="container-fluid">
        <div class="row me-2">
          <div class="col-md-4 shadow-lg bg-body rounded">
            <div class="row">
              <div class="col-md-12 border">
                <a href="profile.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">User Profile</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border">
                <a href="street.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Street</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border">
                <a href="services.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Services</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border bg-danger">
                <a href="aboutus.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-white">Barangay Details</p></a>
              </div>
            </div>
          </div>
          <div class="col-md-8 shadow-lg p-3 bg-body rounded">
            <div class="container-fluid shadow-lg p-3 bg-body rounded">
              <form action="aboutus.php" method="post">
                <div class="row pt-1">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Barangay Name</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="1">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Barangay Alias</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="2">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">About Us</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="3">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Mission</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="4">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Vision</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="5">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Demographics</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="6">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Location</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="7">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="email" class="text-start fs-4">Email</p>
                  </div>
                  <div class="col-md-4">
                    <button id="email" type="submit" class="btn btn-primary" name="updatedetail" value="8">Update</button>
                  </div>
                </div>
                <hr class="mt-1 mb-1">
                <div class="row pt-3">
                  <div class="col-md-8">
                    <p id="brgy_name" class="text-start fs-4">Contacts</p>
                  </div>
                  <div class="col-md-4">
                    <button id="brgy_name" type="submit" class="btn btn-primary" name="updatedetail" value="9">Update</button>
                  </div>
                </div>
              </form>
            </div>
          <div>
        </div>
      </div>

      <!-- edit pop-up modal -->
      <div class="modal" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="aboutus.php" method="post">
              <input type="hidden" name="content" value="<?php echo $id;?>">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Barangay Details</h5>
              </div>
              <div class="modal-body">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="id" value="<?php echo $id;?>">
                    <?php
                      if($id == 1){
                        echo '<label for="brgy_name" class="form-label">Barangay Name</label>
                        <input type="text" class="form-control" id="brgy_name" name="content" value="' . $brgy_name . '" required>';
                      }
                      elseif($id == 2){
                        echo '<label for="brgy_alias" class="form-label">Barangay Alias</label>
                        <input type="text" class="form-control" id="brgy_alias" name="content" value="' . $brgy_alias . '" required>';
                      }
                      elseif($id == 3){
                        echo '<label for="aboutus" class="form-label">About Us</label>
                        <textarea class="form-control" id="aboutus" name="content" rows="5" placeholder="Enter text here..." required>' . $aboutus . '</textarea>';
                      }
                      elseif($id == 4){
                        echo '<label for="mission" class="form-label">Mission</label>
                        <textarea class="form-control" id="mission" name="content" rows="5" placeholder="Enter text here..." required>' . $mission . '</textarea>';
                      }
                      elseif($id == 5){
                        echo '<label for="vision" class="form-label">Vision</label>
                        <textarea class="form-control" id="vision" name="content" rows="5" placeholder="Enter text here..." required>' . $vision . '</textarea>';
                      }
                      elseif($id == 6){
                        echo '<label for="demographics" class="form-label">Demographics</label>
                        <textarea class="form-control" id="demographics" name="content" rows="5" placeholder="Enter text here..." required>' . $demographics . '</textarea>';
                      }
                      elseif($id == 7){
                        echo '<label for="location" class="form-label">Locaton</label>
                        <input type="text" class="form-control" id="location" name="content" value="' . $location . '" required>';
                      }
                      elseif($id == 8){
                        echo '<label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="content" value="' . $email . '" required>';
                      }
                      else{
                        echo '<label for="telephone" class="form-label">Telephone</label>
                        <input type="tel" class="form-control" id="telephone" name="telephone" value="' . $telephone . '" pattern="[0-9]{8}" required>
                        <label for="smart" class="form-label">Smart/Tnt</label>
                        <input type="tel" class="form-control" id="smart" name="smart" value="' . $smart . '" pattern="[0-9]{11}" required>
                        <label for="globe" class="form-label">Globe/TM</label>
                        <input type="tel" class="form-control" id="globe" name="globe" value="' . $globe . '" pattern="[0-9]{11}" required>';
                      }
                    ?>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"  name="getupdate">Update</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit pop-up modal end -->

      <!-- edit verification pop-up modal -->
      <div class="modal" id="editconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Barangay Details</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure about these changes?</p>
            </div>
            <form action="aboutus.php" method="post">
              <input type="hidden" name="id" value="<?php echo $id;?>">
              <input type="hidden" name="content" value="<?php echo $content;?>">
              <input type="hidden" name="telephone" value="<?php echo $telephone;?>">
              <input type="hidden" name="smart" value="<?php echo $smart;?>">
              <input type="hidden" name="globe" value="<?php echo $globe;?>">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="updateconf">Ok</button>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editmodal">Back</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit verification pop-up end modal -->

      <!-- edit confirmation pop-up modal -->
      <div class="modal" id="editconfvermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Barangay Details</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Barangay detail successfully updated.</p>
            </div>
            <form action="aboutus.php" method="post">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit confirmation pop-up end modal -->

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
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editconfvermodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 3) {
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