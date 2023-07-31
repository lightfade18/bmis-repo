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
    $id = $_REQUEST['resmodopen'];
    try{
      /** query to get the appropriate data from the database */ 
      $sql2 = "SELECT * FROM announcements_tbl WHERE id = $id";
      $d2 =  $con->query($sql2);
      foreach($d2 as $data2){
        $type2 = ($data2['type']);
        $title2 = ($data2['title']);
        $details2 = ($data2['details']);
        $publisher2 = ($data2['publisher']);
        $date = date_create($data2['publish_date']);
        $date = date_format($date, 'm-d-Y h:i:s a');
        $date2 = date_create($data2['validation']);
        $date2 = date_format($date2, 'm-d-Y h:i:s a');
        if($data2['image'] == 'none'){
          $image = 'annposter.jpg';
        }
        elseif($data2['image'] == 'none1'){
          $image = 'eveposter.jpg';
        }
        else{
          $image = ($data2['image']);
        }
      }
      $showModal = 1;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** editing announcement codes */
  if(isset($_REQUEST['editann'])){
    $id = $_REQUEST['editann'];
    $sql2 = "SELECT * FROM announcements_tbl WHERE id=$id";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $title2 = $data2['title'];
      $details2 = $data2['details'];
      $validation2 = date_create($data2['validation']);
    }
    $validation2 = date_format($validation2, 'Y-m-d\TH:i:s');
    $showModal = 2;
  }

  /** preparing and filtering announcement codes */
  if(isset($_REQUEST["submit"])){
    $type = "Announcement";
    $title = filter_var($_REQUEST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_REQUEST['details'],FILTER_SANITIZE_STRING);
    $validation = $_REQUEST['validation'];
    $edits = $_REQUEST['edits'];
    if($edits == 1){
      $id = $_REQUEST['id'];
    }
    $date3 = date_create($validation);
    $date3 = date_format($date3, 'm-d-Y h:i:s a');
    $publisher = 'Brgy. Secretary';
    $showModal = 3;
  }

  /** adding announcement into db codes */
  if(isset($_REQUEST['addannounceconf'])){
    $type = $_REQUEST['type'];
    $title = $_REQUEST['title'];
    $details = $_REQUEST['details'];
    $validation = $_REQUEST['validation'];
    $publisher = $_REQUEST['publisher'];
    $edits = $_REQUEST['edits'];
    $status = "Posted";
    $publish_date = date('Y-m-d H:i:s');
    try{
      # preparing a pdo query that adds values to the db
      $image = "none";
      # identify if it is an insert or a update query
      if($edits == 1){
        $id = $_REQUEST['id'];
        $sql3 = $con->prepare("UPDATE announcements_tbl SET type=:type, title=:title, details=:details, validation=:validation, status=:status WHERE id=:id");
        /** execute query */
        if($sql3->execute
          ([
            ':type' => $type, 
            ':title' => $title, 
            ':details' => $details,
            ':validation' => $validation, 
            ':status' => $status,
            ':id' => $id
          ])
        ){
          $showModal = 4;
        }
        
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }

  /** editing event codes */
  if(isset($_REQUEST['editeve'])){
    $id = $_REQUEST['editeve'];
    $sql8 = "SELECT * FROM announcements_tbl WHERE id=$id";
    $d8 =  $con->query($sql8);
    foreach($d8 as $data8){
      $title8 = $data8['title'];
      $details8 = $data8['details'];
      $validation8 = date_create($data8['validation']);
      $coordinator8 = $data8['coordinator'];
      $image8 = $data8['image'];
    }
    $validation8 = date_format($validation8, 'Y-m-d\TH:i:s');
    $showModal = 10;
  }

  /** preparing, filtering and adding events codes */
  if(isset($_POST["submitevent"])){
    # getting and filtering inputs
    $type = "Event";
    $title = filter_var($_POST['title'],FILTER_SANITIZE_STRING);
    $details = filter_var($_POST['details'],FILTER_SANITIZE_STRING);
    $coordinator = filter_var($_POST['coordinator'],FILTER_SANITIZE_STRING);
    $validation = $_POST['validation'];
    $edits = $_POST['edits'];
    $publisher = 'Brgy. Secretary';
    $status = "Posted";
    $publish_date = date('Y-m-d H:i:s');
    $date3 = date_create($validation);
    $date3 = date_format($date3, 'm-d-Y h:i:s a');
    if($edits == 1){
      $id = $_POST['id'];
      # check if an image is inserted
      if($_FILES["image"]["error"] == 4){
        # trycatch pdo prepared statement
        try{
          $sql9 = $con->prepare("UPDATE announcements_tbl SET type=:type, title=:title, details=:details, coordinator=:coordinator, validation=:validation, status=:status WHERE id=:id");
          /** execute query */
          if($sql9->execute
            ([
              ':type' => $type, 
              ':title' => $title, 
              ':details' => $details,
              ':coordinator' => $coordinator,
              ':validation' => $validation, 
              ':status' => $status,
              ':id' => $id
            ])
          ){
            $showModal = 4;
          }
        }
        catch(PDOException $e){
          $pdoError = $e->getMesage();
        }
      }
      else{
        $previmage = $_POST['previmage'];
        # preparing image variables
        $fileName = $_FILES["image"]["name"];
        $fileSize = $_FILES["image"]["size"];
        $tmpName = $_FILES["image"]["tmp_name"];
        # variable that check and prepare the file extension
        $validImageExtension = ['jpg', 'jpeg', 'png'];
        $imageExtension = explode('.', $fileName);
        $imageExtension = strtolower(end($imageExtension));
        # check image errors
        if ( !in_array($imageExtension, $validImageExtension) ){
          $errorType = 0;
          $errorCount = 1;
          $showModal = 11;
        }
        else if($fileSize > 1000000){
          $errorType = 0;
          $errorCount = 2;
          $showModal = 11;
        }
        else{
          # removing previous image in the folder
          if($previmage != 'none1'){
            unlink("images/".$previmage);
          }
          # prepare final image variable
          $newImageName = uniqid();
          $newImageName .= '.' . $imageExtension;
          move_uploaded_file($tmpName, 'images/' . $newImageName);
          try{
            $sql10 = $con->prepare("UPDATE announcements_tbl SET type=:type, title=:title, details=:details, coordinator=:coordinator, validation=:validation, image=:image, status=:status WHERE id=:id");
            /** execute query */
            if($sql10->execute
              ([
                ':type' => $type, 
                ':title' => $title, 
                ':details' => $details,
                ':coordinator' => $coordinator,
                ':validation' => $validation, 
                ':image' => $newImageName, 
                ':status' => $status,
                ':id' => $id
              ])
            ){
              $showModal = 4;
            }
          }
          catch(PDOException $e){
            $pdoError = $e->getMesage();
          }
        }
      }
    }
  }

  /** reloading page codes */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: archive_announcement.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Archives</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/mainstyle.css" />
    <style>
      p{
        word-wrap: break-word;
      }
    </style>
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>
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
          <a href="transactions.php" class="nav-link px-3 active text-white">
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
          <a href="archive_complaint.php" class="btn btn-outline-danger">Complaints</a>
          <a href="archive_blotter.php" class="btn btn-outline-danger">Blotters</a>
          <a href="archive_announcement.php" class="btn btn-outline-danger active" aria-current="page">Announcements</a>
        </div>
      </div>
      <div class="container-fluid border border-danger p-1 rounded mt-3">
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Announcement Deleted Data Table
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table id="transaction" class="table table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Type</th>
                  <th>Title</th>
                  <th>Publish Date</th>
                  <th>Preferences</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT * FROM announcements_tbl WHERE status = 'Removed'";
                  $d1 =  $con->query($sql1);
                  foreach($d1 as $data1){
                    $date = date_create($data1['publish_date']);
                    $date = date_format($date, 'm-d-Y h:i:s a');
                ?>
                <tr>
                  <th><?= $data1['status'];?></th>
                  <th><?= $data1['type'];?></th>
                  <th><?= $data1['title'];?></th>
                  <th><?= $date;?>
                  <form action="archive_announcement.php" method="post">
                  <th><button type="submit" class="btn btn-link" value="<?=$data1['id']?>" name="resmodopen">Details</button></th>
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
                <div class="col-md-12 mb-3">
                  <div class="card h-100 border shadow-lg p-3 mb-5 bg-body rounded">
                    <img src="images/<?=$image;?>" class="card-img-top image-fluid" alt="...">
                    <div class="card-body">
                      <h4 class="card-title fw-bolder"><?=$title2;?></h4>
                      <p class="card-text fs-5"><?=$details2;?></p>
                      <p class="card-text fs-5">Published by: <?=$publisher2?></p>
                      <p class="card-text fs-5">Posted on: <?=$date?></p>
                      <p class="card-text fs-5">Published Until:<?=$date2?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <form action="archive_announcement.php" method="post">
            <div class="modal-footer">
              <?php
              if($type2 == 'Announcement'){
                echo '<button type="submit" class="btn btn-primary" name="editann" value="'.$id.'">Repost</button>';
              }
              else{
                echo '<button type="submit" class="btn btn-primary" name="editeve" value="'.$id.'">Repost</button>';
              }
              ?>
              
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- detail pop-up end modal -->

      <!-- edit pop-up modal -->
      <div class="modal" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
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
              <form action="archive_announcement.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Type</label>
                  <input type="text" class="form-control" id="incha" value="Announcement" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?php echo $title2;?>" required>
                </div>
              </div>
              <div class="row-3">
                <div class="col-md-12">
                  <div class="form-group green-border-focus" id="anndetails">
                    <label for="anndetails">Details</label>
                    <textarea class="form-control" id="anndetails" rows="5" name="details" required><?php echo $details2;?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="validation">Published Until</label>
                  <input type="datetime-local" class="form-select" id="validation" name="validation" value="<?php echo $validation2;?>" required>
                </div>
              </div>
            </div>
            <input type="hidden" name="edits" value="1">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submit">Publish</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit pop-up modal end -->

      <!-- adding confirmation pop-up modal -->
      <div class="modal" id="addconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Type</label>
                  <p id="title" class="text-start fs-3">Announcement</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-8">
                  <label for="title" class="form-label">Announcement Title</label>
                  <p id="title" class="text-start fs-3"><?php echo $title?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="anndetail" class="form-label">Announcement Details</label>
                  <p id="title" class="text-start fs-3"><?php echo $details?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="publisher" class="form-label">Publisher</label>
                  <p id="title" class="text-start fs-3"><?php echo $publisher?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="until" class="form-label">Valid Until</label>
                  <p id="title" class="text-start fs-3"><?php echo $date3?></p>
                </div>
              </div>
            </div>
            <form action="archive_announcement.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <input type="hidden" name="title" value="<?php echo $title?>">
            <input type="hidden" name="details" value="<?php echo $details?>">
            <input type="hidden" name="validation" value="<?php echo $validation?>">
            <input type="hidden" name="publisher" value="<?php echo $publisher?>">
            <input type="hidden" name="selection" value="3">
            <input type="hidden" name="edits" value="<?=$edits?>">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="addannounceconf">Save</button>
              <button type="submit" class="btn btn-secondary" name="editann" value="<?=$id?>">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- adding notification pop-up modal -->
      <div class="modal fade" id="addconfmodalfinal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Announcement Information</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Announcement/Event has been republished.</p>
            </div>
            <form action="archive_announcement.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding notification pop-up end modal -->

      <!-- edit event pop-up modal -->
      <div class="modal" id="editevemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Event Information</h5>
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
              <form action="archive_announcement.php" method="post" enctype="multipart/form-data">
              <div class="row">
                <div class="col-md-12">
                  <label for="type" class="form-label">Type</label>
                  <input type="text" class="form-control" id="incha" value="Event" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="title" class="form-label">Title</label>
                  <input type="text" class="form-control" id="title" name="title" value="<?= $title8;?>" required>
                </div>
              </div>
              <div class="row-3">
                <div class="col-md-12">
                  <div class="form-group green-border-focus" id="anndetails">
                    <label for="anndetails">Details</label>
                    <textarea class="form-control" id="anndetails" rows="5" name="details" required><?= $details8;?></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="coordinator" class="form-label">Event Coordinator</label>
                  <input type="text" class="form-control" id="coordinator" name="coordinator" value="<?= $coordinator8;?>" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="validation">Published Until</label>
                  <input type="datetime-local" class="form-select" id="validation" name="validation" value="<?= $validation8;?>" required>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label class="form-label" for="image">Event Poster</label>
                  <input type="file" class="form-control" name="image" id = "image" accept=".jpg, .jpeg, .png" value="">
                </div>
              </div>
            </div>
            <input type="hidden" name="edits" value="1">
            <input type="hidden" name="id" value="<?=$id?>">
            <input type="hidden" name="previmage" value="<?=$image8?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="submitevent">Publish</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit event pop-up modal end -->

      <!-- Error display pop-up modal -->
      <div class="modal fade" id="editerrormodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Event Information</h5>
            </div>
            <div class="modal-body">
              <?php
              if($errorCount == 1){
                echo'<p class="text-center fs-4">Invalid type of file. (Only accept jpg, jpeg and png files)</p>';
              }
              else{
                echo '<p class="text-center fs-4">Image file size is too large.</p>';
              }
              ?>
            </div>
            <form action="archive_announcement.php" method="post">
            <input type="hidden" name="type" value="<?php echo $type?>">
            <input type="hidden" name="title" value="<?php echo $title?>">
            <input type="hidden" name="details" value="<?php echo $details?>">
            <input type="hidden" name="validation" value="<?php echo $validation?>">
            <input type="hidden" name="publisher" value="<?php echo $publisher?>">
            <input type="hidden" name="coordinator" value="<?php echo $coordinator?>">
            <input type="hidden" name="newImageName" value="<?php echo $newImageName?>">
            <input type="hidden" name="errorType" value="<?php echo $errorType?>">
            <input type="hidden" name="selection" value="4">
            <input type="hidden" name="edits" value="<?=$edits?>">
            <input type="hidden" name="id" value="<?=$id?>">
            <div class="modal-footer">
              <?php 
              if($errorType == 0){
                echo '<button type="submit" class="btn btn-success" name="editeve" value="'.$id.'">OK</button>';
              }
              else{
                echo'<button type="submit" class="btn btn-success" name="posttype">OK</button>';
              }
              ?>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding notification pop-up end modal -->

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
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addconfmodal").modal("show");
			});
		</script>';
	}
  if($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addconfmodalfinal").modal("show");
			});
		</script>';
	}
  if($showModal == 10) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editevemodal").modal("show");
			});
		</script>';
	}
  if($showModal == 11) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editerrormodal").modal("show");
			});
		</script>';
	}
  ?>
</body>
</html>