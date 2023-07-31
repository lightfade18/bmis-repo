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
    $id = $_REQUEST['openblotter'];
    $showModal = 1;
  }

  if(isset($_REQUEST['printtransaction'])){
    $_SESSION['user']['transprint'] = 1;
    $typeprint = $_REQUEST['printtransaction'];
    if($typeprint == 1){
      $_SESSION['user']['typespan'] = $_REQUEST['typespan'];
      header("Location: print_blotter.php");
    }
  }

  if(isset($_REQUEST['viewresult'])){
    $idcomp3 = $_REQUEST['compdid'];
    $h_id = $_REQUEST['viewresult'];
    $showModal = 2;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blotter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
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
          <div class="collapse show" id="reports">
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
                  <a href="blotter.php" class="nav-link px-3 bg-white active text-dark">
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
        <div class="col-md-12 fw-bold fs-3 text-danger">Blotter</div>
      </div>
      <div class="row m-1">
        <div class="col-md-12">
          <p class="text-end"><button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#genreportmodal"><i class="bi bi-printer"></i>&nbsp;Generate Report</button></p>
        </div>
      </div>
      <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Blotter Data Table
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
                      <th>Blotter Type</th>
                      <th>Date Recorded</th>
                      <th>Preferences</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql2 = "SELECT * FROM complaint_tbl WHERE status = '5'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                    ?>
                    <tr>
                      <th><?php echo $data2['complainant_lname'];?>, <?php echo $data2['complainant_fname'];?> <?php echo substr($data2['complainant_mname'], 0, 1);?>.</th>
                      <th><?php echo $data2['complainee_lname'];?>, <?php echo $data2['complainee_fname'];?> <?php echo substr($data2['complainee_mname'], 0, 1);?>.</th>
                      <th><?php echo $data2['complaint_type'];?></th>
                      <th><?php echo $data2['latest_hearing'];?></th>
                      
                      <form action="blotter.php" method="post">
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

      <!-- viewing result pop-up modal-->
      <div class="modal fade" id="viewresultmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="viewresultmodal">Blotter Report</h5>
            </div>
            <div class="modal-body">
              <?php
                $qry5 = "SELECT * FROM hearing_tbl WHERE h_id = '$h_id'";
                $qryd5 =  $con->query($qry5);
                foreach($qryd5 as $qrydata5){
                  $resultshow = $qrydata5['result'];
                  $htype = $qrydata5['type'];
                  $compdate6 = $qrydata5['hearing_date'];
                  $compdate7 = date_create($compdate6);
                  $compdate7 = date_format($compdate7, 'F d, Y h:i:s a');
                }
              ?>
              <div class="row">
                <div class="col-md-6">
                  <label for="results" class="form-label">Hearing Type</label>
                  <?php
                  if($htype == 0){
                    echo'<p id="results" class="text-start fs-4">Mediation</p>';
                  }
                  elseif($htype == 1){
                    echo'<p id="results" class="text-start fs-4">Follow-up Hearing</p>';
                  }
                  ?>
                </div>
                <div class="col-md-6">
                <label for="Hearing" class="form-label">Hearing Date</label>
                  <p id="Hearing" class="text-start fs-4"><?=$compdate7;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="results" class="form-label">Result</label>
                  <p id="results" style="word-break: break-word;" class="text-start fs-4"><?=$resultshow;?></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <form action="blotter.php" method="post">
              <button type="submit" class="btn btn-secondary" name="openblotter" value="<?=$idcomp3;?>">Close</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- viewing pop-up modal -->
      <div class="modal fade" id="blotterdetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Blotter Information</h5>
            </div>
            <div class="modal-body">
              <?php
              $query4 = "SELECT * FROM complaint_tbl WHERE id = '$id'";
              $dq4 =  $con->query($query4);
              foreach($dq4 as $qdata4){
                $compdate1 = date_create($qdata4['latest_hearing']);
                $compdate1 = date_format($compdate1, 'F d, Y (h:i:s a)');
              
              ?>
              <div class="row">
                <div class="col-md-6">
                  <label for="compttype" class="form-label">Blotter Type</label>
                  <p id="compttype" class="text-start fs-4"><b><?=$qdata4['complaint_type'];?></b></p>
                </div>
                <div class="col-md-6">
                  <label for="datef" class="form-label">Blotter Recorded</label>
                  <p id="datef" class="text-start fs-4"><b><?=$compdate1;?></b></p>
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
                  $query5 = "SELECT * FROM evidence_tbl WHERE id = '$id'";
                  $dq5 =  $con->query($query5);
                  foreach($dq5 as $qdata5){
                    $eviimg = $qdata5['evidence_image'];
                  }
                  if($eviimg == 'none'){
                    echo '<p id="evidence" class="text-start fs-4"><b>No submitted evidence.</b></p>';
                  }
                  else{
                    echo '<p id="evidence" class="text-center"><img src="images/'.$eviimg.'" class="img-fluid border border-dark" alt="..."></p>';
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
                                $query6 = "SELECT * FROM hearing_tbl WHERE id = '$id' ORDER BY hearing_date";
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
                                  <form action="blotter.php" method="post">
                                  <input type="hidden" name="compdid" value="<?=$id;?>">
                                  <th><button type="submit" class="btn btn-link" name="viewresult" value="<?=$qdata6['h_id'];?>">View</button></th>
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
      <!-- viewing pop-up modal end -->

      <!-- generate report options pop-up modal -->
      <div class="modal fade" id="genreportmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Generate Report</h5>
            </div>
            <div class="modal-body">
            <form action="blotter.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="typespan" class="form-label">Blotter Type</label>
                  <select class="form-select" aria-label="Default select example" name="typespan" id="typespan" required>
                    <option value="" hidden>Select</option>
                    <option value="Adultery">Adultery</option>
                    <option value="Child Abuse">Child Abuse</option>
                    <option value="Sexual Abuse">Sexual Abuse</option>
                    <option value="Road Accident">Road Accident</option>
                    <option value="Crime/Harm">Crime/Harm</option>
                    <option value="Neighborhood Concern">Neighborhood Concern</option>
                    <option value="Property Concern">Property Concern</option>
                    <option value="Business Concern">Business Concern</option>
                    <option value="Others">Others</option>
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
      <div class="modal fade" id="logoutconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Blotter Information</h5>
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
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="./js/app2.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#blotterdetail").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#viewresultmodal").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
<!-- main content -->
</body>
</html>