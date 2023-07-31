<?php
  /** creating connection to db */
  require 'dbcon.php';

  /** start session */
  session_start();
  $showModal = 0;

  date_default_timezone_set('Asia/Manila');
  $date_reg = date('Y-m-d H:i:s');

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  if(isset($_REQUEST['openblotter'])){
    $idcomp3 = $_REQUEST['openblotter'];
    $showModal = 1;
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
          <a href="archive.php" class="btn btn-outline-danger">Residents</a>
          <a href="archive_transaction.php" class="btn btn-outline-danger">Transactions</a>
          <a href="archive_complaint.php" class="btn btn-outline-danger active" aria-current="page">Complaints</a>
          <a href="archive_blotter.php" class="btn btn-outline-danger">Blotters</a>
          <a href="archive_announcement.php" class="btn btn-outline-danger">Announcements</a>
        </div>
      </div>
      <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Settled Complaints Data Table
          </div>
        </div>
        <div class="row m-1">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <table id="transaction" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>Complainant</th>
                      <th>Complainee</th>
                      <th>Date Settled</th>
                      <th>Preferences</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql2 = "SELECT * FROM complaint_tbl WHERE status = '3'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                        $compd1 = date_create($data2['date_complaint']);
                        $compd1 = date_format($compd1, 'F d, Y (h:i:s a)');
                        $compd2 = date_create($data2['latest_hearing']);
                        $compd2 = date_format($compd2, 'F d, Y (h:i:s a)');
                    ?>
                    <tr>
                      <th><?php echo $data2['complainant_lname'];?>, <?php echo $data2['complainant_fname'];?> <?php echo substr($data2['complainant_mname'], 0, 1);?>.</th>
                      <th><?php echo $data2['complainee_lname'];?>, <?php echo $data2['complainee_fname'];?> <?php echo substr($data2['complainee_mname'], 0, 1);?>.</th>
                      <th><?php echo $compd2;?></th>
                      
                      <form action="archive_complaint.php" method="post">
                      <th><button type="submit" class="btn btn-link" value="<?=$data2['id']?>" name="openblotter">Details</button></th>
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

      <!-- viewing pop-up modal -->
      <div class="modal fade" id="blotterdetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Information</h5>
            </div>
            <div class="modal-body p-4">
              <form action="complaints.php" method="post">
              <?php
              $query4 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp3'";
              $dq4 =  $con->query($query4);
              foreach($dq4 as $qdata4){
                $compdate1 = date_create($qdata4['date_complaint']);
                $compdate1 = date_format($compdate1, 'F d, Y (h:i:s a)');
                $compdate2 = date_create($qdata4['latest_hearing']);
                $compdate2 = date_format($compdate2, 'F d, Y (h:i:s a)');
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="compttype" class="form-label">Complaint Type</label>
                  <p id="compttype" class="text-start fs-4"><b><?=$qdata4['complaint_type'];?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="datef" class="form-label">Date Filed</label>
                  <p id="datef" class="text-start fs-4"><b><?=$compdate1;?></b></p>
                </div>
                <div class="col-md-6">
                  <label for="datef" class="form-label">Date settled</label>
                  <p id="datef" class="text-start fs-4"><b><?=$compdate2;?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="complainant" class="form-label">Complainant</label>
                  <p id="complainant" class="text-start fs-4"><b><?=$qdata4['complainant_fname'];?> <?=$qdata4['complainant_mname'];?> <?=$qdata4['complainant_lname'];?></b></p>
                </div>
                <div class="col-md-6">
                  <label for="complainee" class="form-label">Complainee</label>
                  <p id="complainee" class="text-start fs-4"><b><?=$qdata4['complainee_fname'];?> <?=$qdata4['complainee_mname'];?> <?=$qdata4['complainee_lname'];?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="statement" class="form-label">Statement</label>
                  <p id="statement" class="text-justify fs-4"><b><?=$qdata4['statement'];?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="evidence" class="form-label">Evidence</label>
                  <?php
                  $query5 = "SELECT * FROM evidence_tbl WHERE id = '$idcomp3'";
                  $dq5 =  $con->query($query5);
                  foreach($dq5 as $qdata5){
                    $eviimg = $qdata5['evidence_image'];
                  }
                  if($eviimg == 'none'){
                    echo '<p id="evidence" class="text-start fs-4"><b>No submitted evidence.</b></p>';
                  }
                  else{
                    echo '<p id="evidence" class="text-center fs-4"><img src="images/'.$eviimg.'" class="img-fluid border border-dark" alt="..."></p>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
                    <div class="row mb-2">
                      <div class="col-md-8">
                        <i class="bi bi-table"></i>&nbsp;Hearing Data Table
                      </div>
                      <div class="col-md-4">
                        
                      </div>
                    </div>
                    <div class="row mt-1 mb-0">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-12">
                            <table id="example" class="table table-striped" style="width:100%">
                              <thead>
                                <tr>
                                  <th>Case Hearing Status</th>
                                  <th>Case Hearing</th>
                                  <th>In-charge</th>
                                  <th>Date of Hearing</th>
                                  <th>Result</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $query6 = "SELECT * FROM hearing_tbl WHERE id = '$idcomp3' ORDER BY hearing_date";
                                $dq6 =  $con->query($query6);
                                foreach($dq6 as $qdata6){
                                  $statush = $qdata6['status'];
                                  $typef = $qdata6['type'];
                                  $compdate3 = $qdata6['hearing_date'];
                                  $compdate5 = date_create($compdate3);
                                  $compdate5 = date_format($compdate5, 'F d, Y h:i:s a');
                                ?>
                                <tr>
                                  <?php
                                  if($compdate3 > $date_reg){
                                    echo'<th>Upcoming</th>';
                                  }
                                  else{
                                    echo'<th>Conducted</th>';
                                  }
                                  if($typef == 0){
                                    echo'<th>Mediation</th>';
                                  }
                                  elseif($typef == 1){
                                    echo'<th>Follow-up hearing</th>';
                                  }
                                  ?>
                                  <th><?=$qdata6['incharge'];?></th>
                                  <th><?=$compdate5;?></th>
                                  <?php
                                  if($qdata6['result'] == ''){
                                    if($date_reg > $qdata6['hearing_date']){
                                      echo '<input type="hidden" name="h_id" value="'.$qdata6['h_id'].'">
                                      <th><button type="submit" class="btn btn-primary" name="resulthearing" value="'.$idcomp3.'">Result</button></th>';
                                    }
                                    else{
                                      echo '<th>In-progress</th>';
                                    }
                                    
                                  }
                                  else{
                                    echo '<th><button type="button" class="btn btn-link">View</button></th>';
                                  }
                                  ?>
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
                </div>
              </div>
              <?php
              }
              ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- viewing pop-up end modal end-->

      <!-- logout confirmation pop-up modal -->
      <div class="modal" id="logoutconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title"></h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Any progress are automatically save.<br>Are you sure to logout?</p>
            </div>
            <div class="modal-footer">
              <a href="login.php"><button type="button" class="btn btn-success" data-bs-dismiss="modal">Yes</button></a><button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button> 
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
				$("#blotterdetail").modal("show");
			});
		</script>';
	}
  ?>
</body>
</html>