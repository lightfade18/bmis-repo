<?php
  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  date_default_timezone_set('Asia/Manila');
  $showModal = 0;

  /** code for couting registered residents */
  $sql8 = "SELECT count(resident_code) FROM penresident WHERE family_position = 'Head'";
  $res2 = $con->query($sql8);
  $cnt3 = $res2->fetchColumn();

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  /** viewing household details codes */
  if(isset($_REQUEST['hholdmodopen'])){
    $resident_code = $_REQUEST['hholdmodopen'];
    try{
      /** query to get the appropriate data from the database */ 
      $sql2 = "SELECT * FROM penresident WHERE resident_code = '$resident_code'";
      $d2 =  $con->query($sql2);
      foreach($d2 as $data2){
        $resident_id2 = ($data2['resident_id']);
        $fname2 = ($data2['firstname']);
        $mname2 = ($data2['middlename']);
        $lname2 = ($data2['lastname']);
        $gender2 = ($data2['gender']);
        $birthdate2 = ($data2['birthdate']);
        $birthplace2 = ($data2['birthplace']);
        $citizenship2 = ($data2['citizenship']);
        $civil2 = ($data2['civil']);
        $religion2 = ($data2['religion']);
        $contact2 = ($data2['contact']);
        $email2 = ($data2['email']);
        $occupation2 = ($data2['occupation']);
        $nature2 = ($data2['nature']);
        $old2 = ($data2['old']);
        $new2 = ($data2['new']);
        $street2 = ($data2['street']);
        $village2 = ($data2['village']);
        $housetype2 = ($data2['housetype']);
        $family_position2 = ($data2['family_position']);
        $username2 = ($data2['username']);
        $family_role2 = ($data2['family_role']);
        $date_reg2 = ($data2['date_reg']);
      }
      $date5 = date_create($date_reg2);
      $date5 = date_format($date5, 'F d, Y h:i:s a');
      $showModal = 1;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  if(isset($_REQUEST['printtransaction'])){
    $_SESSION['user']['transprint'] = 1;
    $typeprint = $_REQUEST['printtransaction'];
    if($typeprint == 1){
      $_SESSION['user']['streetspan'] = $_REQUEST['streetspan'];
      header("Location: print_households.php");
    }
  }

  /** viewing household member details codes */
  if(isset($_REQUEST['hholdmember'])){
    $resident_id = $_REQUEST['hholdmember'];
    try{
      /** query to get the appropriate data from the database */ 
      $sql4 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
      $d4 =  $con->query($sql4);
      foreach($d4 as $data4){
        $resident_id3 = ($data4['resident_id']);
        $fname3 = ($data4['firstname']);
        $mname3 = ($data4['middlename']);
        $lname3 = ($data4['lastname']);
        $gender3 = ($data4['gender']);
        $birthdate3 = ($data4['birthdate']);
        $birthplace3 = ($data4['birthplace']);
        $citizenship3 = ($data4['citizenship']);
        $civil3 = ($data4['civil']);
        $religion3 = ($data4['religion']);
        $contact3 = ($data4['contact']);
        $email3 = ($data4['email']);
        $occupation3 = ($data4['occupation']);
        $nature3 = ($data4['nature']);
        $family_role3 = ($data4['family_role']);
        $family_position3 = ($data4['family_position']);
        $resident_code3 = ($data4['resident_code']);
      }
      /** setting age */
      $date2 = date('Y-m-d');
      $date2_2 = new DateTime($date2);
      $date1_1 = new DateTime($birthdate3);
      $difference_1 = $date1_1->diff($date2_2);
      $age2 = ($difference_1->y);

      $showModal = 2;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Households</title>
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
          <div class="collapse show" id="residents">
            <div class="card card-body  bg-danger">
              <ul class="navbar-nav ps-0">
                <li>
                  <a href="households.php" class="nav-link px-3 bg-white active text-dark">
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
    <!-- content title -->
    <div class="container-fluid">
      <div class="row me-3">
        <div class="col-md-12 fw-bold fs-3 text-danger">Households</div>
      </div>
      <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row m-1">
          <div class="col-md-12">
            <p class="text-end"><button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#genreportmodal"><i class="bi bi-printer"></i>&nbsp;Generate Report</button></p>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 mb-3">
          </div>
          <div class="col-md-4 mb-3">
            <div class="card text-dark border-dark shadow-lg bg-body rounded pb-0 h-100">
              <div class="card-body text-center">
                <h6 class="fw-bold">Registered Household/s</h6>
                <h2><?php echo $cnt3; ?></h2>
              </div>
            </div>
          </div>
          <div class="col-md-4 mb-3">
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Household Data Table
          </div>
        </div>
        <div class="row m-1">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <table id="transaction" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>Household Head</th>
                      <th>Resident Code</th>
                      <th>Street St.</th>
                      <th>Members</th>
                      <th>Preferences</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql1 = "SELECT * FROM penresident WHERE family_position = 'Head' AND status = 'Registered'";
                      $d1 =  $con->query($sql1);
                      foreach($d1 as $data1){
                    ?>
                    <tr>
                      <th><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</th>
                      <th><?php echo $data1['resident_code'];?></th>
                      <th><?php echo $data1['street'];?></th>
                      <?php
                      $housecode = $data1['resident_code'];
                      $sql7 = "SELECT COUNT(resident_id) FROM penresident WHERE resident_code = '$housecode'";
                      $res1 = $con->query($sql7);
                      $cnt1 = $res1->fetchColumn();
                      ?>
                      <th style="padding-left: 35px;"><?php echo $cnt1;?></th>
                      <form action="households.php" method="post">
                      <th><button type="submit" class="btn btn-link" value="<?=$data1['resident_code']?>" name="hholdmodopen">Details</button></th>
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
      
      <!-- detail pop-up modal -->
      <div class="modal fade" id="detail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Household Information</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">Household Code</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $resident_code;?></p>
                </div>
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">Old House#</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $old2;?></p>
                </div>
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">New House#</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $new2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">Street</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $street2;?></p>
                </div>
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">Village</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $village2;?></p>
                </div>
                <div class="col-md-4">
                  <label for="dcreated" class="form-label">Housetype</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $housetype2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="dcreated" class="form-label">Household Date Registered</label>
                  <p id="dcreated" class="text-start fs-3"><?php echo $date5;?></p>
                </div>
              </div>
              <div class="row border border-dark shadow-lg p-3 mb-5 bg-body rounded">
                <div class="row mb-4">
                  <div class="col-md-5">
                    <i class="bi bi-table"></i>&nbsp;Household Data Table
                  </div>
                </div>
                <div class="col-md-12">
                  <table id="hmembers" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>Position</th>
                        <th>Role</th>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Class</th>
                        <th>Preferences</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql3 = "SELECT * FROM penresident WHERE resident_code = '$resident_code' AND status = 'Registered'";
                        $d3 =  $con->query($sql3);
                        foreach($d3 as $data3){
                          $date_0 = date('Y-m-d');
                          $date_2 = new DateTime($date_0);
                          $date_1 = new DateTime($data3['birthdate']);
                          $difference = $date_1->diff($date_2);
                          $age = $difference->format('%y');
                      ?>
                      <tr>
                        <th><?php echo $data3['family_position'];?></th>
                        <th><?php echo $data3['family_role'];?></th>
                        <th><?php echo $data3['lastname'];?>, <?php echo $data3['firstname'];?> <?php echo substr($data3['middlename'], 0, 1);?>.</th>
                        <th><?php echo $age;?></th>
                        <?php
                        if($age > 59){
                          echo '<th>Senior</th>';
                        }
                        elseif($age <18){
                          echo '<th>Minor</th>';
                        }
                        else{
                          echo '<th>Adult</th>';
                        }
                        ?>
                        <form action="households.php" method="post">
                        <th><button type="submit" class="btn btn-link" value="<?=$data3['resident_id']?>" name="hholdmember">Details</button></th>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- detail pop-up modal end -->

      <!-- changing head pop-up modal -->
      <div class="modal fade" id="memdetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Househead Modfication</h5>
            </div>
            <div class="modal-body">
              <form action="" method="post">
                <div class="row">
                  <div class="col-md-6">
                    <label for="head" class="form-label">Current Househead</label>
                    <p id="lastname" class="text-start fs-3">Dela Cruz, Juan P.</p>
                  </div>
                  <div class="col-md-6">
                    <label for="fposition" class="form-label">New Family Position</label>
                    <select class="form-select" aria-label="Default select example" id="fposition">
                      <option selected disabled>Family Position</option>
                      <option value="1">Wife</option>
                      <option value="2">Child</option>
                      <option value="3">Brother</option>
                      <option value="3">Sister</option>
                      <option value="3">Grandfather</option>
                      <option value="3">Grandmother</option>
                      <option value="3">Relative</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-5">
                    <label for="changehead" class="form-label">Change Househead</label>
                    <select class="form-select" aria-label="Default select example" id="changehead">
                      <option selected disabled>Choose New Househeld</option>
                      <option value="1">Dela Cruz, Juan P.</option>
                      <option value="1">Dela Cruz, Juan P.</option>
                      <option value="1">Dela Cruz, Juan P.</option>
                      <option value="1">Dela Cruz, Juan P.</option>
                    </select>
                  </div>
                  
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">Change</button><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#detail">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- changing head pop-up modal end -->

      <!-- detail pop-up modal -->
      <div class="modal fade" id="detailres" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Resident Information</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="resident_id" class="form-label">Resident ID</label>
                  <p id="resident_id" class="text-start fs-3"><?php echo $resident_id3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-13">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $fname3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3"><?php echo $mname3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3"><?php echo $lname3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="gender" class="text-start fs-3"><?php echo $gender3;?></p>
                </div>
                <div class="col-md-3">
                  <label for="age" class="form-label">Age</label>
                  <p id="age" class="text-start fs-3"><?php echo $age2;?></p>
                </div>
                <div class="col-md-6">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <p id="birthdate" class="text-start fs-3"><?php echo $birthdate3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3"><?php echo $birthplace3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="citizenship" class="form-label">Citizenship</label>
                  <p id="citizenship" class="text-start fs-3"><?php echo $citizenship3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="civil" class="text-start fs-3"><?php echo $civil3;?></p>
                </div>
                <div class="col-md-8">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="religion" class="text-start fs-3"><?php echo $religion3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="contact" class="form-label">Contact #</label>
                  <p id="contact" class="text-start fs-3">0<?php echo $contact3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="email" class="form-label">Email Address</label>
                  <p id="email" class="text-start fs-3"><?php echo $email3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3"><?php echo $occupation3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3"><?php echo $nature3;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="frole" class="form-label">Family Role</label>
                  <p id="frole" class="text-start fs-3"><?php echo $family_role3;?></p>
                </div>
                <div class='col-md-6'>
                  <label for='fpos' class="form-label">Household Position</label>
                  <p id='fpos' class='text-start fs-3'><?php echo $family_position3;?></p>
                </div>
              </div>
            </div>
            <form action="households.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary" name="hholdmodopen" value="<?=$resident_code3?>">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- detail pop-up end modal -->

      <!-- generate report options pop-up modal -->
      <div class="modal fade" id="genreportmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Generate Report</h5>
            </div>
            <div class="modal-body">
            <form action="households.php" method="post">
              <div class="row">
                <div class="col-md-12">
                <label for="streetspan" class="form-label">Street Group</label>
                <select class="form-select" aria-label="Default select example" name="streetspan" id="streetspan" required>
                  <option value="" hidden>Choose Street</option>
                  <?php
                    $sql4 = "SELECT * FROM streets";
                    $d4 =  $con->query($sql4);
                    foreach($d4 as $data4){
                      if($data4['street'] == $street){
                        echo '<option value="'.$data4['street'].'" selected>'.$data4['street'].' St.</option>';
                      }
                      else{
                        echo '<option value="'.$data4['street'].'">'.$data4['street'].' St.</option>';
                      }
                    }
                    
                  ?>
                  <option value="All">All</option>
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
  <script src="js/bttop.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#detail").modal("show");
			});
		</script>';
	}
  if($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#detailres").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
</body>
</html>