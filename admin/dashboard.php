<?php
  /** creating connection to db */
  require 'dbcon.php';

  /** start session */
  session_start();
  $showModal = 0;

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }
  
  /** code for couting registered residents */
  $sql8 = "SELECT count(resident_code) FROM penresident WHERE family_position = 'Head'";
  $res8 = $con->query($sql8);
  $cnt3 = $res8->fetchColumn();
  
  /** code for couting registered residents */
  $sql31 = "SELECT count(resident_id) FROM penresident WHERE status = 'Pending'";
  $res31 = $con->query($sql31);
  $cnt2 = $res31->fetchColumn();
  
  /** code for couting registered residents */
  $sql32 = "SELECT count(or_id) FROM transactionlist_tbl WHERE status = '2'";
  $res32 = $con->query($sql32);
  $cnt1 = $res32->fetchColumn();
  
  /** code for couting registered residents */
  $sql2 = "SELECT count(id) FROM complaint_tbl WHERE status = '1'";
  $res2 = $con->query($sql2);
  $cnt4 = $res2->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
          <a href="dashboard.php" class="nav-link px-3 bg-white active text-dark">
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
        <li class="mb-4">
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
      <div class="row">
        <div class="col-md-12 fw-bold fs-3 text-danger">Dashboard</div>
      </div>
      <div class="row">
        <div class="col-md-3 mb-3">
          <div class="card text-white bg-danger pb-0 h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <i class="bi bi-people m-1"></i>
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h3 class="fw-bold"><?=$cnt3?></h3>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h6>Total Household/s</h6>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-success ms-auto p-0 m-0">
              <a href="households.php" class="nav-link text-white">View details <i class="bi bi-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card text-white bg-danger pb-0 h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <i class="bi bi-exclamation-octagon"></i>
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h3 class="fw-bold"><?=$cnt2?></h3>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h6>Pending Resident/s</h6>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-success ms-auto p-0 m-0">
              <a href="blotter.php" class="nav-link text-white">View details <i class="bi bi-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card text-white bg-danger pb-0 h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <i class="bi bi-arrow-left-right"></i>
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h3 class="fw-bold"><?=$cnt1?></h3>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h6>Pending Transaction/s</h6>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-success ms-auto p-0 m-0">
              <a href="transactions.php" class="nav-link text-white">View details <i class="bi bi-chevron-right"></i></a>
            </div>
          </div>
        </div>
        <div class="col-md-3 mb-3">
          <div class="card text-white bg-danger pb-0 h-100">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <i class="bi bi-exclamation-circle"></i>
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h3 class="fw-bold"><?=$cnt4?></h3>
                </div>
              </div>
              <div class="row">
                <div class="col-sm-3">
                </div>
                <div class="col-sm-9 justify-content-end">
                  <h6>Pending Complaint/s</h6>
                </div>
              </div>
            </div>
            <div class="card-footer bg-transparent border-success ms-auto p-0 m-0">
              <a href="complaints.php" class="nav-link text-white">View details <i class="bi bi-chevron-right"></i></a>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          
        </div>
      </div>
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
  
</body>
</html>