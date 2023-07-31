<?php
  /** creating connection to db */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  $showModal = 0;

  /** code for couting to be deliver transactions */
  $sql5 = "SELECT COUNT(or_id) FROM transactionlist_tbl WHERE status = '1'";
  $res1 = $con->query($sql5);
  $cnt1 = $res1->fetchColumn();

  /** code for couting pending transactions */
  $sql6 = "SELECT COUNT(or_id) FROM transactionlist_tbl WHERE status = '2'";
  $res2 = $con->query($sql6);
  $cnt2 = $res2->fetchColumn();

  /** code for couting delivered transactions */
  $sql7 = "SELECT COUNT(or_id) FROM transactionlist_tbl WHERE status = '3'";
  $res3 = $con->query($sql7);
  $cnt3 = $res3->fetchColumn();

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
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
          <a href="dashboard.php" class="nav-link px-3 text-white active">
            <span class="me-3">
              <i class="bi bi-speedometer2"></i>
            </span>
            <span>Dashboard</span>
          </a>  
        </li>
        <li>
        <a class="nav-link px-3 sidebar-link text-white" data-bs-toggle="collapse" href="#officials" role="button" aria-expanded="false" aria-controls="collapseExample">
          <span class="me-3">
            <i class="bi bi-person-circle"></i>
          </span>
          <span>Officials</span>
          <span class="right-icon ms-auto">
            <i class="bi bi-chevron-down"></i>
          </span>
        </a>
        <div class="collapse" id="officials">
          <div class="card card-body  bg-danger">
            <ul class="navbar-nav ps-0">
              <li>
                <a href="officials.php" class="nav-link px-3 text-white">
                  <span class="me-3">
                    <i class="bi bi-people"></i>
                  </span>
                  <span>Honorables</span>
                </a>
              </li>
              <li>
                <a href="tanod.php" class="nav-link px-3 text-white">
                  <span class="me-3">
                    <i class="bi bi-person"></i>
                  </span>
                  <span>Brgy. Tanod</span>
                </a>
              </li>
              <li>
                <a href="caretakers.php" class="nav-link px-3 text-white">
                  <span class="me-3">
                    <i class="bi bi-person"></i>
                  </span>
                  <span>Brgy. Caretakers</span>
                </a>
              </li>
            </ul>
          </div>
        </div>
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
          <a href="archive.php" class="nav-link px-3 text-white active ">
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
          <a href="announcements.php" class="nav-link px-3 active text-white">
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
          <a href="profile.php" class="nav-link px-3 active text-white">
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
      <div class="row">
        <div class="col-md-4 mb-3">
          <div class="card text-dark border-dark shadow-lg bg-body rounded pb-0 h-100">
            <div class="card-body text-center">
              <h6 class="fw-bold">To be deliver</h6>
              <h2><?php echo $cnt1; ?></h2>
              <a href="transactions.php" class="btn btn-dark">Open Table</a>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card text-dark border-dark shadow-lg bg-body rounded pb-0 h-100">
            <div class="card-body text-center">
              <h6 class="fw-bold">Pending</h6>
              <h2><?php echo $cnt2; ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
          <div class="card text-dark border-dark shadow-lg bg-body rounded pb-0 h-100">
            <div class="card-body text-center">
              <h6 class="fw-bold">Delivered</h6>
              <h2><?php echo $cnt3; ?></h2>
              <a href="delivered_transactions.php" class="btn btn-dark">Open Table</a>
            </div>
          </div>
        </div>
      </div>

      <div class="container-fluid border border-dark shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row">
          <div class="col-md-12">
            <p class="text-end"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#choosetpye">New Transaction</button></p>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Transaction Data Table
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table id="transaction" class="table table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Document Type</th>
                  <th>Resident</th>
                  <th>Date Requested</th>
                  <th>Date Delivered</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT * FROM transactionlist_tbl WHERE status = '1'";
                  $d1 =  $con->query($sql1);
                  foreach($d1 as $data1){
                ?>
                <tr>
                  <th>To be delivered</th>
                  <th><?php echo $data1['or_id'];?></th>
                  <th><?php echo $data1['name'];?>.</th>
                  <th><?php echo $data1['or_date'];?></th>
                  <form action="individuals.php" method="post">
                  <th><button type="submit" class="btn btn-success" name="<?php echo $data1['or_id'];?>">Deliver</button>
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
      <!-- <button type="button" class="btn btn-primary">Generate Report</button> -->

      <!-- choosing type pop-up modal -->
      <div class="modal fade" id="choosetpye" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Choose Transaction</h5>
            </div>
            <div class="modal-body">
              <form action="" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <label for="trantype" class="form-label">Transaction Type</label>
                    <select class="form-select" aria-label="Default select example" id="trantype">
                      <option selected disabled>Select Transaction</option>
                      <option value="1">Barangay Clearance</option>
                      <option value="2">Business Certificate</option>
                      <option value="3">Community Tax Certificate</option>
                      <option value="4">Reproduction of Tax Records</option>
                    </select>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">Add</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- choosing type pop-up modal end -->

      <!-- adding pop-up modal -->
      <div class="modal fade" id="addmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Choose Transaction</h5>
            </div>
            <div class="modal-body">
              <form action="" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <label for="typetran" class="form-label">Transaction Type</label>
                    <p id="typetran" class="text-start fs-3">Barangay Clearance</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="residentreq" class="form-label">Resident</label>
                    <input class="form-control" list="residentreqs" id="residentreq">
                    <datalist id="residentreqs">
                      <option value="Dela Cruz, Juan P.">
                      <option value="Dela Cruz, Juan P.">
                      <option value="Dela Cruz, Juan P.">
                      <option value="Dela Cruz, Juan P.">
                    </datalist>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group green-border-focus" id="purpose">
                      <label for="purpose">Purpose</label>
                      <textarea class="form-control" id="purpose" rows="5"></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addconfmodal">Add</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- adding pop-up modal end -->

      <!-- adding confirmation pop-up modal -->
      <div class="modal fade" id="addconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Transaction Information</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <label for="typetran" class="form-label">Transaction Type</label>
                  <p id="typetran" class="text-start fs-3">Barangay Clearance</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="residentreq" class="form-label">Resident</label>
                  <p id="typetran" class="text-start fs-3">Delar Cruz, Juan P.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="ornumber" class="form-label">OR #</label>
                  <p id="ornumber" class="text-start fs-3">37482741.</p>
                </div>
                <div class="col-md-6">
                  <label for="ordate" class="form-label">OR Date</label>
                  <p id="ordate" class="text-start fs-3">03/03/2022</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="purpose" class="form-label">Purpose</label>
                  <p id="purpose" class="text-justify fs-5">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 
                    1500s, when an unknown printer took a galley of type and scrambled it to 
                    make a type specimen book. It has survived not only five centuries, but 
                    also the leap into electronic typesetting, remaining essentially unchanged. 
                    It was popularised in the 1960s with the release of Letraset sheets 
                    containing Lorem Ipsum passages, and more recently with desktop publishing 
                    software like Aldus PageMaker including versions of Lorem Ipsum.
                  </p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Save</button><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addmodal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- aprroving pop-up modal -->
      <div class="modal fade" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Transaction Information</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editconfmodal">Approve</button><button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#denying">Deny</button>
            </div>
          </div>
        </div>
      </div>
      <!-- aprroving pop-up modal end -->

      <!-- aprroving confirmation pop-up modal -->
      <div class="modal fade" id="editconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Transaction Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to approve this transaction?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editmodal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- aprroving confirmation pop-up end modal -->

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

      <!-- release pop-up modal -->
      <div class="modal fade" id="releasemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Deliver Transaction</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <label for="dateset" class="form-label">Date Delivered</label>
                  <p id="dateset" class="text-start fs-3">04/30/2022</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="deliveryguy" class="form-label">Officer-in-Charge</label>
                  <input class="form-control" list="officers" id="deliveryguy">
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
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#releaseconfmodal">Ok</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
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
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">Ok</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- release confirmation pop-up end modal -->

      <!-- delete confirmation pop-up modal -->
      <div class="modal fade" id="deletemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Delete Transaction</h5>
            </div>
            <div class="modal-body">
            <p class="text-center fs-4">Are you sure to delete this transaction?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-dismiss="modal">Yes</button><button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
            </div>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

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
</body>
</html>