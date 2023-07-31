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

  /** adding street codes */
  if(isset($_REQUEST['addstreet'])){
    $street = ucwords(strtolower(filter_var($_REQUEST['street'],FILTER_SANITIZE_STRING)));
    /** sql query in pdo format that check if the street is already existing in the db */
    $select_stmt = $con->prepare("SELECT street FROM streets WHERE street = :street");
    $select_stmt->execute([':street' => $street]);
    $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

    if(isset($row['street']) == $street){
      $errorMSG = "Street is already registered. Please use another street.";
      $showModal = 6;
    }
    else{
      $showModal = 1;
    }
  }
  
  /** confirmation of adding street codes */
  if(isset($_REQUEST['addconfstreet'])){
    $street = $_REQUEST['addconfstreet'];
    try{
      /** preparing and executing a pdo query that adds values to the db */
      $sql3 = $con->prepare("INSERT INTO streets (street) VALUES (:street)");
      $sql3->execute([
        ':street' => $street
      ]);
      $showModal = 2;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** opening modal for edit confirmation codes */
  if(isset($_REQUEST['editmodalconf'])){
    $street_id = $_REQUEST['street_id'];
    $street = ucwords(strtolower(filter_var($_REQUEST['street'],FILTER_SANITIZE_STRING)));
    
    /** a simple sql query that check if the street input is already existing in the db */
    $sql4 = "SELECT street FROM streets WHERE street_id = '$street_id'";
    $d4 =  $con->query($sql4);
    foreach($d4 as $data4){
      $prev_street = ($data4['street']);
    }
    if($prev_street == $street){
      $showModal = 4;
    }
    else{
      try{
        /** sql query in pdo format that check if the email input is already existing in the db */
        $select_stmt1 = $con->prepare("SELECT street FROM streets WHERE street = :street");
        $select_stmt1->execute([':street' => $street]);
        $row1 = $select_stmt1->fetch(PDO::FETCH_ASSOC);

        if(isset($row1['street']) == $street){
          $errorMSG = "Street is already registered. Please use another street.";
          $showModal = 3;
        }
        else{
          $showModal = 4;
        }
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
      
    }
  }

  /** editing street codes */
  if(isset($_REQUEST['editstreet'])){
    $street_id = $_REQUEST['editstreet'];
    $sql2 = "SELECT * FROM streets WHERE street_id = '$street_id'";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $street = ($data2['street']);
    }
    $showModal = 3;
  }

  /** confirmation of editing street codes */
  if(isset($_REQUEST['editconf'])){
    $street_id = $_REQUEST['editconf'];
    $street = $_REQUEST['street'];
    $qry2 = "SELECT * FROM streets WHERE street_id = '$street_id'";
    $dq2 =  $con->query($qry2);
    foreach($dq2 as $qdata2){
      $streetprev = ($qdata2['street']);
    }
    try{
      $sql6 = $con->prepare("UPDATE penresident SET street=:street WHERE street=:prevstreet");
      $sql6->execute([':street' => $street, ':prevstreet' => $streetprev]);

      $sql5 = $con->prepare("UPDATE streets SET street=:street WHERE street_id=:street_id");
      $sql5->execute([':street' => $street, ':street_id' => $street_id]);

      
      $showModal = 5;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  
  if(isset($_REQUEST['deletestreet'])){
    $street_id = $_REQUEST['deletestreet'];
    $showModal = 7;
  }
  
  if(isset($_REQUEST['confdeletestreet'])){
    $street_id = $_REQUEST['confdeletestreet'];
    try{
      $qry1 = $con->prepare("DELETE FROM streets WHERE street_id=:street_id");
      $qry1->execute([':street_id' => $street_id]);
      $showModal = 8;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }
  
  /** resetting page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: street.php");
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
          <a href="street.php" class="nav-link px-3 bg-white active mb-5 text-dark">
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
              <div class="col-md-12 border bg-danger">
                <a href="street.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-white">Street</p></a>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 border">
                <a href="services.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Services</p></a>
              </div>
            </div>
            <div class="row">
            <div class="col-md-12 border">
              <a href="aboutus.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Barangay Details</p></a>
            </div>
          </div>
          </div>
          <div class="col-md-8 shadow-lg p-3 bg-body rounded">
            <div class="container-fluid shadow-lg p-3 bg-body rounded">
              <div class="row m-3">
                <div class="col-md-12">
                  <p class="text-end"><button type="button" class="btn btn-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addmodal">Add Street</button></p>
                </div>
              </div>
              <div class="row mb-4">
                <div class="col-md-5">
                  <i class="bi bi-table"></i>&nbsp;Street Data Table
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table id="transaction" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>Street Name</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        $sql1 = "SELECT * FROM streets";
                        $d1 =  $con->query($sql1);
                        foreach($d1 as $data1){
                      ?>
                      <tr>
                        <th><?php echo $data1['street'];?></th>
                        <form action="street.php" method="post">
                        <th><button type="submit" class="btn btn-info m-2" value="<?=$data1['street_id']?>" name="editstreet">Edit</button><button type="submit" class="btn btn-danger" value="<?=$data1['street_id']?>" name="deletestreet">Delete</button></th>
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
          <div>
        </div>
      </div>

      <!-- edit pop-up modal -->
      <div class="modal" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="street.php" method="post">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Street Information</h5>
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
                <label for="street" class="form-label">Street Name</label>
                <input type="text" class="form-control" id="street" name="street" value="<?php echo $street; ?>" required>
                <input type="hidden" name="street_id" value="<?php echo $street_id; ?>">
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="editmodalconf">Edit</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit pop-up modal end -->
      
      <!-- edit confirmation pop-up modal -->
      <div class="modal" id="editconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Street Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure about these changes?</p>
            </div>
            <form action="street.php" method="post">
              <input type="hidden" name="street" value="<?php echo $street; ?>">
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="editconf" value="<?php echo $street_id; ?>">Yes</button>
                <button type="submit" class="btn btn-danger" name="editstreet" value="<?php echo $street_id; ?>">No</button>
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
              <h5 class="modal-title">Street Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Street information sucessfully updated.</p>
            </div>
            <div class="modal-footer">
              <form action="street.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- aprroving pop-up modal end -->

      <!-- delete confirmation pop-up modal -->
      <div class="modal" id="deleteconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Street Information</h5>
            </div>
            <div class="modal-body">
            <p class="text-center fs-4">Are you sure to delete this street?</p>
            </div>
            <div class="modal-footer">
              <form action="street.php" method="post">
              <button type="submit" class="btn btn-success" name="confdeletestreet" value="<?=$street_id?>">Yes</button>
              <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

      <!-- delete confirmation pop-up modal -->
      <div class="modal" id="deletevermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Street Information</h5>
            </div>
            <div class="modal-body">
            <p class="text-center fs-4">Street has been succesfully deleted.</p>
            </div>
            <div class="modal-footer">
              <form action="street.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident" value="<?=$street_id?>">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

      <!-- adding pop-up modal -->
      <div class="modal" id="addmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="street.php" method="post">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Street Information</h5>
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
                <label for="street" class="form-label">Street Name</label>
                <input type="text" class="form-control" id="street" name="street" placeholder="Type here..." required>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="addstreet">Add</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding pop-up modal end -->

      <!-- adding confirmation pop-up modal -->
      <div class="modal" id="addconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Street Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to add this street?</p>
            </div>
            <form action="street.php" method="post">
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="addconfstreet" value="<?php echo $street;?>">Yes</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addmodal">No</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- aprroving pop-up modal -->
      <div class="modal fade" id="confendmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Street Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">A new street has been added.</p>
            </div>
            <div class="modal-footer">
              <form action="street.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- aprroving pop-up modal end -->

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
				$("#addconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confendmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 5) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confeditendmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 6) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 7) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#deleteconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 8) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#deletevermodal").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
</body>
</html>