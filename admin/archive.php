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

  /** viewing resident details codes */
  if(isset($_REQUEST['resmodopen'])){
    $resi_id = $_REQUEST['resmodopen'];
    try{
      /** query to get the appropriate data from the database */ 
      $sql2 = "SELECT * FROM penresident WHERE resident_id = $resi_id";
      $d2 =  $con->query($sql2);
      foreach($d2 as $data2){
        $resident_id2 = ($data2['resident_id']);
        $resident_code2 = ($data2['resident_code']);
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
      }
      /** setting age */
      $date2 = date('Y-m-d');
      $date2_2 = new DateTime($date2);
      $date1_1 = new DateTime($birthdate2);
      $difference_1 = $date1_1->diff($date2_2);
      $age2 = ($difference_1->y);

      $showModal = 1;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** reloading page codes */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: archive.php");
  }

  /** code to approve pending records */
  if(isset($_REQUEST['restore'])){
    $resident_id = $_REQUEST['restore'];
    $status = "Registered";
    try{
      $sql5 = $con->prepare("UPDATE penresident SET status=:status WHERE resident_id=:resident_id");
      $sql5->execute([':status' => $status, ':resident_id' => $resident_id]);
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
    <title>Archives</title>
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
          <a href="archive.php" class="nav-link px-3 bg-white active text-dark">
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
      <div class="row  me-3">
        <div class="col-md-12 fw-bold fs-3 text-danger">Archive</div>
      </div>
      <div class="col-md-12">
        <div class="btn-group">
          <a href="archive.php" class="btn btn-outline-danger active" aria-current="page">Residents</a>
          <a href="archive_transaction.php" class="btn btn-outline-danger">Transactions</a>
          <a href="archive_complaint.php" class="btn btn-outline-danger">Complaints</a>
          <a href="archive_blotter.php" class="btn btn-outline-danger">Blotters</a>
          <a href="archive_announcement.php" class="btn btn-outline-danger">Announcements</a>
        </div>
      </div>
      <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Deleted Residents Data Table
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table id="transaction" class="table table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Resident ID</th>
                  <th>Name</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Street</th>
                  <th>Preferences</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT * FROM penresident WHERE status = 'Inactive' OR status = 'Denied'";
                  $d1 =  $con->query($sql1);
                  foreach($d1 as $data1){
                ?>
                <tr>
                  <th><?php echo $data1['status'];?></th>
                  <th><?php echo $data1['resident_id'];?></th>
                  <th><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?></th>
                  <?php
                    $date_0 = date('Y-m-d');
                    $date_2 = new DateTime($date_0);
                    $date_1 = new DateTime($data1['birthdate']);
                    $difference = $date_1->diff($date_2);
                  ?>
                  <th><?php echo $difference->format('%y');?></th>
                  <th><?php echo $data1['gender'];?></th>
                  <th><?php echo $data1['street'];?></th>
                  <form action="archive.php" method="post">
                  <th><button type="submit" class="btn btn-link" value="<?=$data1['resident_id']?>" name="resmodopen">Details</button></th>
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
                  <p id="resident_id" class="text-start fs-3"><?php echo $resident_id2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $fname2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3"><?php echo $mname2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3"><?php echo $lname2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="gender" class="text-start fs-3"><?php echo $gender2;?></p>
                </div>
                <div class="col-md-3">
                  <label for="age" class="form-label">Age</label>
                  <p id="age" class="text-start fs-3"><?php echo $age2;?></p>
                </div>
                <div class="col-md-6">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <p id="birthdate" class="text-start fs-3"><?php echo $birthdate2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3"><?php echo $birthplace2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="citizenship" class="form-label">Citizenship</label>
                  <p id="citizenship" class="text-start fs-3"><?php echo $citizenship2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="civil" class="text-start fs-3"><?php echo $civil2;?></p>
                </div>
                <div class="col-md-8">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="religion" class="text-start fs-3"><?php echo $religion2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="contact" class="form-label">Contact #</label>
                  <p id="contact" class="text-start fs-3">0<?php echo $contact2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="email" class="form-label">Email Address</label>
                  <p id="email" class="text-start fs-3"><?php echo $email2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3"><?php echo $occupation2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3"><?php echo $nature2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="old" class="form-label">Old House #</label>
                  <p id="old" class="text-start fs-3"><?php echo $old2;?></p>
                </div>
                <div class="col-md-6">
                    <label for="middlename" class="form-label">New House #</label>
                    <p id="new" class="text-start fs-3"><?php echo $new2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <label for="street" class="form-label">Street</label>
                    <p id="street" class="text-start fs-3"><?php echo $street2;?></p>
                </div>
                <div class="col-md-6">
                    <label for="village" class="form-label">Village/Subdivision</label>
                    <p id="village" class="text-start fs-3"><?php echo $village2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="housetype" class="form-label">Household Type</label>
                  <p id="housetype" class="text-start fs-3"><?php echo $housetype2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="frole" class="form-label">Family Role</label>
                  <p id="frole" class="text-start fs-3"><?php echo $family_role2;?></p>
                </div>
                <div class='col-md-6'>
                  <label for='fpos' class="form-label">Household Position</label>
                  <p id='fpos' class='text-start fs-3'><?php echo $family_position2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="username" class="form-label">Username</label>
                  <p id="username" class="text-start fs-3"><?php echo $username2;?></p>
                </div>
              </div>
            </div>
            <form action="archive.php" method="post">
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#approveconf">Restore</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- detail pop-up end modal -->

      <!-- approving confirmation pop-up modal -->
      <div class="modal fade" id="approveconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                  <h5 class="modal-title">Restoring Resident</h5>
              </div>
              <div class="modal-body">
                  <p class="text-center fs-4">Are you sure to restore this inactive resident?</p>
              </div>
              <form action="archive.php" method="post">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-primary" name="restore" value="<?php echo $resident_id2;?>">Confirm</button>
                  <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#detailres">Back</button>
              </div>
              </form>
          </div>
        </div>
      </div>
      <!-- approving confirmation pop-up modal end -->

      <!-- confirmatiom of approving confirmation pop-up modal -->
      <div class="modal fade" id="confapproveconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                  <h5 class="modal-title">Restoring Resident</h5>
              </div>
              <div class="modal-body">
                  <p class="text-center fs-4">Resident has been successfully restored.</p>
              </div>
              <form action="archive.php" method="post">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-success" name="confirmresident">Ok</button>
              </div>
              </form>
          </div>
        </div>
      </div>
      <!-- confirmatiom of approving confirmation pop-up modal end -->

      <!-- logout confirmation pop-up modal -->
      <div class="modal fade" id="logoutconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
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
				$("#detailres").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2){
    // CALL MODAL HERE
    echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#confapproveconf").modal("show");
    });
    </script>';
  }
  else{

  }
  ?>
</body>
</html>