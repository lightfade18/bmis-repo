<?php
  /** creating connection to db */
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'dbcon.php';
  require 'PHPmailer/src/Exception.php';
  require 'PHPmailer/src/PHPMailer.php';
  require 'PHPmailer/src/SMTP.php';

  /** start session */
  session_start();
  $showModal = 0;
  $id = $_SESSION['user']['id'];
  $passlen = $_SESSION['user']['passlen'];
  $pass = "";

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  date_default_timezone_set('Asia/Manila');
  $date_reg = date('Y-m-d H:i:s');

  for ($x = 0; $x < $passlen; $x++) {
    $pass .= "*";
  }

  /** geting data in the db codes */
  $sql2 = "SELECT * FROM admins_tbl WHERE id = '$id'";
  $d2 =  $con->query($sql2);
  foreach($d2 as $data2){
    $usertype1 = ($data2['usertype']);
    $username1 = ($data2['username']);
    $alias1 = ($data2['alias']);
    $mobile1 = ($data2['contact']);
    $email1 = ($data2['email']);
  }

  

  /** updating user information codes */
  if(isset($_REQUEST['updateinfo'])){
    $username = filter_var($_REQUEST['username'],FILTER_SANITIZE_STRING);
    $alias = filter_var($_REQUEST['alias'],FILTER_SANITIZE_STRING);
    $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_STRING);
    $email = filter_var($_REQUEST['email'],FILTER_SANITIZE_STRING);
    try{
      /** sql query in pdo format that check if the email input is already existing in the db */
      $select_stmt = $con->prepare("SELECT email FROM admins_tbl WHERE email = :email");
      $select_stmt->execute([':email' => $email]);
      $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

      if(isset($row['email']) == $email){
        $errorMSG = "Email is already registered. Please use a new email.";
        $showModal = 3;
      }
      else{
        # verifying entered email if it is valid using api key
        $api_key = "a12277e410a640b2a99529005100db48";

        $ch = curl_init();

        curl_setopt_array($ch, [
          CURLOPT_URL => "https://emailvalidation.abstractapi.com/v1?api_key=$api_key&email=$email",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        $data_key = json_decode($response, true);
        
        if($data_key["deliverability"] == "UNDELIVERABLE"){
          $errorMSG = "Email must valid.";
          $showModal = 3;
        }
        elseif($data_key["is_disposable_email"]["value"] == true){
          $errorMSG = "Email must not be disposable.";
          $showModal = 3;
        }
        else{
          $showModal = 1;
        } 
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** confirmation of updating user information codes */
  if(isset($_REQUEST['updateconfinfo'])){
    $username = $_REQUEST['username'];
    $alias = $_REQUEST['alias'];
    $contact = $_REQUEST['contact'];
    $email = $_REQUEST['email'];
    try{
      /** preparing a pdo query that update values to the db */
      $sql1 = $con->prepare("UPDATE admins_tbl SET username=:username, alias=:alias, email=:email, contact=:contact WHERE id = '$id'");
      /** execute query */
      $sql1->execute([':username' => $username, ':alias' => $alias, ':contact' => $contact, ':email' => $email]);
      $showModal = 2;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** login user codes */
  if(isset($_REQUEST['change_btn'])){
    $pass = strip_tags($_REQUEST['passwrd']);
    /** query if the inputs are valid */
    $query3 = $con->prepare("SELECT * FROM admins_tbl WHERE id = :id");
    $query3->execute([':id' => $id]);
    $row = $query3->fetch(PDO::FETCH_ASSOC);

    if($query3->rowCount() > 0){
      if(password_verify($pass, $row["pass"])){
        $showModal = 5;
      }
      else{
        $errorMSG5 = "Incorrect password. Please try again";
        $showModal = 4;
      }
    }
    else{
      $errorMSG5 = "Incorrect password. Please try again";
    }
  }

  if(isset($_REQUEST['verify'])){
    $newpassword = strip_tags($_REQUEST['newpassword']);
    $password = strip_tags($_REQUEST['newpasswrd']);
    if($password == $newpassword){
      if(preg_match('/[A-Z]/', $password)){
        if(preg_match('/[a-z]/', $password)){
          if(preg_match('/[0-9]/', $password)){
            if(strlen($password) < 10){
              $errorMSG6 = "Must be at least 10 characters.";
              $showModal = 5;
            }
            else{
              $epass = password_hash($password, PASSWORD_DEFAULT);
              try{
                $sql5 = $con->prepare("UPDATE admins_tbl SET pass=:pass WHERE id = :id");
                if($sql5->execute([':pass' => $epass, ':id' => $id])){
                  $showModal = 6;
                }
              }
              catch(PDOException $e){
                $pdoError = $e->getMesage();
              }
              
            }
          }
          else{
            $errorMSG6 = "Must contain at least 1 number.";
            $showModal = 5;
          }
        }
        else{
          $errorMSG6 = "Must contain at least 1 lower letter.";
          $showModal = 5;
        }
      }
      else{
        $errorMSG6 = "Must contain at least 1 upper letter.";
        $showModal = 5;
      }
    }
    else{
      $errorMSG6 = "New password and confirmation password didn't match.";
      $showModal = 5;
    }
  }

  if(isset($_REQUEST['openpassmodal'])){
    $showModal = 4;
  }

  if(isset($_REQUEST['sendotp'])){
    $otp = rand(10000, 99999);
    try{
      $sql1 = $con->prepare("INSERT INTO otp_tbl (otp, email, date) VALUES(:otp, :email, :date)");
      $sql1->execute([
          ':otp' => $otp,
          ':email' => $email1,
          ':date' => $date_reg
      ]);
      # sending otp email code
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Host = "smtp.gmail.com";
      $mail->SMTPAuth = true;
      $mail->Username = 'pjjumawan18@gmail.com';
      $mail->Password = 'lfvjckzasfrpzxzf';
      $mail->SMTPSecure = 'ssl';
      
      $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Hello Admin!</strong></p><br>
              <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">This is the otp code to use for changing your password <strong>'.$otp.'</strong>.<br>From: <b>Concecion Dos Management</b>.</p>';
      $mail->Port = 465;
      $mail->setFrom('pjjumawan18@gmail.com');
      $mail->addAddress($email1);
      $mail->isHTML(true);
      $mail->Subject = 'Email Verification';
      $mail->Body = $body;
      $mail->send();
      $counterotp = 1;
      $showModal = 7;
    }
    catch(PDOException $e){
        $pdoError = $e->getMesage();
    } 
    
  }

  if(isset($_REQUEST['cancotp'])){
    try{
      /** sql query in pdo format that check if the email input is already existing in the db */
      $sql7 = $con->prepare("DELETE FROM otp_tbl WHERE email = :email");
      $sql7->execute([':email' => $email1]);
      header("Location: profile.php");
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  if(isset($_REQUEST['otpverify'])){
    $entotp = filter_var(strtolower($_REQUEST['entotp']),FILTER_SANITIZE_STRING);
    $otp = $_REQUEST['otp'];
    $counterotp = $_REQUEST['counterotp'];
    try{
      if($counterotp < 5){
        if($entotp == $otp){
          $sql9 = $con->prepare("DELETE FROM otp_tbl WHERE otp = :otp");
          $sql9->execute([':otp' => $entotp]);
          $showModal = 5;
        }
        else{
          $counterotp += 1;
          $errorMSG8 = 'Invalid OTP. Please try again. (Remaining tries: '. 6 - $counterotp.')';
          $showModal = 7;
        }
      }
      else{
        $sql8 = $con->prepare("DELETE FROM otp_tbl WHERE email = :email");
        $sql8->execute([':email' => $email1]);
        header("Location: profile.php");
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }    
  }

  /** reloading page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: profile.php");
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
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
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
          <a href="profile.php" class="nav-link px-3 bg-white active mb-5 text-dark">
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
              <div class="col-md-12 border bg-danger">
                <a href="profile.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-white">User Profile</p></a>
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
              <div class="col-md-12 border">
                <a href="aboutus.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Barangay Details</p></a>
              </div>
            </div>
          </div>
          <div class="col-md-8 shadow-lg p-3 bg-body rounded">
            <div class="container-fluid shadow-lg p-3 bg-body rounded">
              <div class="row m-2 border-bottom border-dark">
                <div class="col-md-12">
                  <p class="text-left fs-4">Admin Information</p>
                </div>
              </div>
              <div class="row m-2">
                <div class="col-md-5">
                  <label for="username" class="form-label">Username</label>
                  <p class="text-left fs-5" id="username"><?php echo $username1;?></p>
                </div>
                <div class="col-md-3">
                  <label for="alias" class="form-label">Alias</label>
                  <p class="text-left fs-5" id="alias"><?php echo $alias1;?></p>
                </div>
                <div class="col-md-4">
                  <label for="usertype" class="form-label">UserType</label>
                  <p class="text-left fs-5" id="usertype"><?php echo $usertype1;?></p>
                </div>
              </div>
              <div class="row m-2">
                <div class="col-md-6">
                  <label for="contact" class="form-label">Mobile Number</label>
                  <p class="text-left fs-5" id="contact">+63<?php echo $mobile1;?></p>
                </div>
                <div class="col-md-6">
                  <label for="email" class="form-label">Email</label>
                  <p class="text-left fs-5" id="email"><?php echo $email1;?></p>
                </div>
              </div>
              <div class="row m-2 border-bottom border-dark">
                <div class="col-md-12">
                  <p class="text-right fs-5"><button type="button" class="btn btn-primary"  data-bs-toggle="modal" data-bs-target="#editmodal">Update Info</button></p>
                </div>
              </div>
              <div class="row m-2 border-bottom border-dark">
                <label for="password" class="form-label"><i class="bi bi-key-fill"></i> Password</label>
                <div class="col-md-8">
                  <p class="text-left fs-6" id="password">It's a good idea to use a strong password that you're not using elsewhere</p>
                </div>
                <div class="col-md-4">
                  <form action="" method="post">
                  <p class="text-left fs-5" id="password"><button type="submoit" class="btn btn-primary" name="openpassmodal">Change Password</button></p>
                  </form>
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
            <form action="profile.php" method="post">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Admin Information</h5>
              </div>
              <div class="modal-body">
                <?php
                  if(isset($errorMSG)){
                    echo '<div class="alert alert-warning" role="alert">
                    '.$errorMSG.'
                  </div>';
                  }
                ?>
                <div class="row">
                  <div class="col-md-12">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo $username1?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="alias" class="form-label">Alias</label>
                    <input type="text" class="form-control" id="alias" name="alias" value="<?php echo $alias1?>">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                  <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                  <div class="input-group mb-3">
                    <span class="input-group-text">+63</span>
                    <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" value="<?=$mobile1?>" required>
                  </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $email1?>">
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="updateinfo">Save</button>
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
              <h5 class="modal-title">Profile Information</h5>
            </div>
            <div class="modal-body">
            <p class="text-center fs-4">Are you sure about these changes?</p>
            </div>
            <div class="modal-footer">
              <form action="profile.php" method="post">
                <input type="hidden" name="username" value="<?php echo $username;?>">
                <input type="hidden" name="alias" value="<?php echo $alias;?>">
                <input type="hidden" name="contact" value="<?php echo $contact;?>">
                <input type="hidden" name="email" value="<?php echo $email;?>">
                <button type="submit" class="btn btn-success" name="updateconfinfo">Yes</button>
                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#editmodal">No</button>
              </form>
            </div>
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
            <form action="profile.php" method="post">
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="confirmresident">Ok</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit confirmation pop-up end modal -->
      
      <!-- edit password pop-up modal -->
      <div class="modal" id="editpassmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <form action="profile.php" method="post">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">User Password</h5>
              </div>
              <div class="modal-body">
                <?php
                if(isset($errorMSG5)){
                  echo '<div class="alert alert-warning" role="alert">
                          '.$errorMSG5.'
                        </div>';
                }
                ?>
                <div class="row m-2">
                  <div class="col-md-12">
                    <label for="password1" class="form-label">Current password</label>
                    <div class="input-group mb-3">
                      <input type="password" class="form-control" id="password1" name="passwrd" placeholder="**********">
                      <span class="input-group-text">
                        <i class="bi bi-eye" id="togglePassword1" style="cursor: pointer"></i>
                      </span>
                  </div>
                  </div>
                </div>
                <div class="row mt-0 ms-1">
                  <div class="col-md-12">
                    <button type="submit" class="btn btn-link" name="sendotp">Forgot password?</button>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="change_btn">Confirm</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit password pop-up modal end -->

      <!-- enter new password pop-up modal -->
      <div class="modal fade" id="newpassmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="newpassmodal">User Password</h5>
            </div>
            <div class="modal-body">
            <?php
              if(isset($errorMSG6)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG6.'
                      </div>';
              }
            ?>
            <div class="row">
                <div class="col-md-12">
                  <p class="text-start fs-5">New password must contain:<br>At least 1 uppercase and lowercase letter, at least 1 number, and must be at least 10 characters.</p>
                  <!-- <p class="text-start fs-5"></p> -->
                </div>
              </div>
            <form action="profile.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="newpassword" class="form-label">New password</label>
                  <?php
                  if(isset($newpassword)){
                    ?>
                    <input type="text" class="form-control" id="newpassword" name="newpassword" value="<?=$newpassword?>">
                    <?php
                  }
                  else{
                    ?>
                    <input type="text" class="form-control" id="newpassword" name="newpassword" >
                    <?php
                  }
                  ?>
                  
                </div>
              </div>
              <div class="row">
                <div class="clo-md-12">
                  <label for="password2" class="form-label">Confirm password</label>
                  <div class="input-group mb-3">
                    <input type="password" class="form-control" id="password2" name="newpasswrd" placeholder="**********">
                    <span class="input-group-text">
                      <i class="bi bi-eye" id="togglePassword2" style="cursor: pointer"></i>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="verify">Confirm</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- enter new password pop-up modal end -->

      <!-- changing password confirmation pop-up modal -->
      <div class="modal fade" id="changeconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Password Update</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">New password has been successfully updated..</p>
            </div>
            <form action="profile.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- changing password  confirmation pop-up end modal -->

      <!-- otp for recovering password pop-up modal -->
      <div class="modal fade" id="otpchangemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">User Information</h5>
            </div>
            <div class="modal-body">
              <?php
              if(isset($errorMSG8)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG8.'
                      </div>';
              }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <p class="text-start fs-5 text-center">An OTP was sent to your registered email.</p>
                </div>
              </div>
              <form action="profile.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="otp" class="form-label">OTP</label>
                  <input type="text" class="form-control" id="otp" name="entotp" placeholder="Provide OTP here">
                </div>
              </div>
            </div>
              <input type="hidden" name="otp" value="<?=$otp?>">
              <input type="hidden" name="counterotp" value="<?=$counterotp?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="otpverify">Confirm</button>
              <button type="submit" class="btn btn-primary" name="cancotp">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- otp for recovering password pop-up end modal -->

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
  <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>

  <?php
    if($showModal == 4){
    ?>
    <script>
      const togglePassword1 = document.querySelector("#togglePassword1");
      const password1 = document.querySelector("#password1");

      togglePassword1.addEventListener("click", function (e) {
        
        // toggle the type attribute
        const type = password1.getAttribute("type") === "password" ? "text" : "password";
        password1.setAttribute("type", type);
        // toggle the eye icon
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
      });
    </script>
    <?php
    }
    if($showModal == 5){
      ?>
      <script>
        const togglePassword2 = document.querySelector("#togglePassword2");
        const password2 = document.querySelector("#password2");
  
        togglePassword2.addEventListener("click", function (e) {
  
          // toggle the type attribute
          const type = password2.getAttribute("type") === "password" ? "text" : "password";
          password2.setAttribute("type", type);
          // toggle the eye icon
          this.classList.toggle('bi-eye');
          this.classList.toggle('bi-eye-slash');
        });
      </script>
      <?php
    }
  ?>

  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editconfmodal").modal("show");
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
				$("#editmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#editpassmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 5) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#newpassmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 6) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#changeconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 7) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#otpchangemodal").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
</body>
</html>