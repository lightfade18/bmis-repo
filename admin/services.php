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

  /** editing transaction details codes */
  if(isset($_REQUEST['edit1'])){
    $tran_id = $_REQUEST['edit1'];
    /** query to get the appropriate data from the database */ 
    $sql1 = "SELECT * FROM transaction_tbl WHERE tran_id = '$tran_id'";
    $d1 =  $con->query($sql1);
    foreach($d1 as $data1){
      $tran_name = ($data1['tran_name']);
      $price = ($data1['price']);
    }
    $showModal = 1;
  }

  /** updating transaction to db codes */
  if(isset($_REQUEST['update'])){
    /** getting and filtering data gathered from the html form */
    $tran_id = $_REQUEST['tran_id'];
    $price = filter_var($_REQUEST['price'],FILTER_SANITIZE_NUMBER_FLOAT);
    /** preparing a pdo query that update values to the db */
    $sql2 = $con->prepare("UPDATE transaction_tbl SET  price=:price WHERE tran_id=:tran_id");
    /** execute query */
    $sql2->execute(
      [
        ':price' => $price, 
        ':tran_id' => $tran_id
      ]
    );
    $showModal = 3;
  }

  /** confirmation of adding household member codes */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: services.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
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
          <a href="services.php" class="nav-link px-3 bg-white active mb-5 text-dark">
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
              <div class="col-md-12 border bg-danger">
                <a href="services.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-white">Services</p></a>
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
              <div class="row mb-4">
                <div class="col-md-5">
                  <i class="bi bi-table"></i>&nbsp;Services Data Table
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <table id="transaction" class="table table-striped" style="width:100%">
                    <thead>
                      <tr>
                        <th>Services</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <th>Barangay Clearance</th>
                        <form action="services.php" method="post">
                        <th><button type="submit" class="btn btn-info m-2" value="1" name="edit1">Edit</button></th>
                        </form>
                      </tr>
                      <tr>
                        <th>Business Certificate</th>
                        <form action="services.php" method="post">
                        <th><button type="submit" class="btn btn-info m-2" name="edit1" value="2">Edit</button></th>
                        </form>
                      </tr>
                      <tr>
                        <th>Community Tax Certificate</th>
                        <form action="services.php" method="post">
                        <th><button type="submit" class="btn btn-info m-2" name="edit1" value="5">Edit</button></th>
                        </form>
                      </tr>
                      <tr>
                        <th>Barangay I.D.</th>
                        <form action="services.php" method="post">
                        <th><button type="submit" class="btn btn-info m-2" name="edit1" value="3">Edit</button></th>
                        </form>
                      </tr>
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
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Updating Transaction</h5>
            </div>
            <div class="modal-body">
              <form action="services.php" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <label for="price" class="form-label">Service Fee</label>
                    <?php
                    if($price){
                      echo '<input type="number" class="form-control" name="price" id="price" value="'.$price.'" min="1" max="9999" required> ';
                    }else{
                      echo '<input type="number" class="form-control" name="price" id="price" min="1" max="9999" required>';
                                         }
                    ?>
                  </div>
                </div>
                <input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="update">Edit</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
              <h5 class="modal-title">Updating Transaction</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure about these changes?</p>
            </div>
            <div class="modal-footer">
              <form action="services.php">
              <button type="submit" class="btn btn-success" name="confupdate">Yes</button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#editmodal">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- edit confirmation pop-up end modal -->

      <!-- edit reminder pop-up modal -->
      <div class="modal fade" id="updateconfmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Updating Transaction</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Transaction has been successfully updated.</p>
            </div>
            <div class="modal-footer">
              <form action="services.php" method="post">
              <button type="submit" class="btn btn-success" name="confirmresident">Ok</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- edit reminder pop-up modal end -->
      
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
  if($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editconfmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#updateconfmodal").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
</body>
</html>