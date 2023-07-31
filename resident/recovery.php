<?php
  require_once 'dbcon.php';
  session_start();
  $showerror = 0;
  $showModal = 0;

  if(isset($_SESSION['otpsesres']['otp']) && isset($_SESSION['otpsesres']['email'])){
    $otp = filter_var(strtolower($_SESSION['otpsesres']['otp']),FILTER_SANITIZE_STRING);
    $email = filter_var(strtolower($_SESSION['otpsesres']['email']),FILTER_SANITIZE_EMAIL);    
  }
  else{
    session_destroy();
    header("Location: login.php");
  }

  if(isset($_REQUEST['verify'])){
    $password = strip_tags($_REQUEST['password']);
    $confpassword = strip_tags($_REQUEST['confpassword']);
    if($password == NULL || $confpassword == NULL){
      $showerror = 1;
    }
    else{
      if($password == $confpassword){
        if(preg_match('/[A-Z]/', $password)){
          if(preg_match('/[a-z]/', $password)){
            if(preg_match('/[0-9]/', $password)){
              if(strlen($password) < 10){
                $showerror = 3;
              }
              else{
                $epass = password_hash($password, PASSWORD_DEFAULT);
                $sql2 = $con->prepare("UPDATE penresident SET pass=:pass WHERE email = :email");
                if($sql2->execute([':pass' => $epass, ':email' => $email])){
                  header("Location: confirmation_recovery.php");
                }
              }
            }
            else{
              $showerror = 3;
            }
          }
          else{
            $showerror = 3;
          }
        }
        else{
          $showerror = 3;
        }
      }
      else{
        $showerror = 2;
      }
    }
  }

  if(isset($_REQUEST['cancotp'])){
    try{
      /** sql query in pdo format that check if the email input is already existing in the db */
      $sql1 = $con->prepare("DELETE FROM otp_tbl WHERE email = :email");
      $sql1->execute([':email' => $email]);
      session_destroy();
      header("Location: login.php");
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** reloading page */
  if(isset($_REQUEST['confirmresident'])){
    session_destroy();
    header("Location: login.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="css/style.css" />
    <title>Sign in & Sign up Form</title>
    <style>
      .fa-eye{
        position: absolute;
        top: 2%;
        left: 88%; 
        cursor: pointer;
      }
    </style>
    <?php
    if($showerror == 1){
      echo '<script type="text/javascript">
          alert("Please provide all of the rquirements.")
      </script>';
    }
    if($showerror == 2){
      echo '<script type="text/javascript">
          alert("New password and confirm password doesn\'t match.")
      </script>';
    }
    if($showerror == 3){
      echo '<script type="text/javascript">
          alert("New password must contain at least 1 uppercase and lowercase letter, at least 1 number, and must be at least 10 characters.")
      </script>';
    }
    ?>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="recovery.php" method="post" class="sign-in-form" autocomplete="off">
            <h2 class="title">Password Update</h2>
            <div class="row">
              <div class="col-md-12">
                <p class="text-center">New password must contain:</p>
                <p class="text-center">At least 1 uppercase and lowercase letter, at least 1 number, and must be at least 10 characters.</p>
              </div>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="text" placeholder="New password" name="password"/>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" placeholder="Confirm password" name="confpassword" id="id_password1"/>
              <i class="far fa-eye" id="togglePassword1"></i>
            </div>
            <div class="row">
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary" name="verify">Confirm</button>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary" name="cancotp">Cancel</button>
                
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="panels-container">
        <div class="panel left-panel">
          <div class="content">
            <h3>Concepcion Dos</h3>
            <p>
              There's no greater challenge and there's no greater honor than to be in public service. - Condoleezza Rice
            </p>
            <!-- <button class="btn transparent text-white" id="sign-up-btn">
              Sign up
            </button> -->
          </div>
          <img src="img/login.svg" class="image" alt="" />
        </div>
        <div class="panel right-panel">
          <div class="content">
            <h3>Registered already?</h3>
            <p>
              Come and enjoy our community.
            </p>
            <button class="btn transparent text-white" id="sign-in-btn">
              Sign in
            </button>
          </div>
          <img src="img/regis.svg" class="image" alt="" />
        </div>
      </div>
    </div>

    <!-- verification of changing password pop-up modal -->
    <div class="modal fade" id="verifychange" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title">Resident Information</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <p class="text-center fs-3">You succesfully change your password.</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <form action="recovery.php" method="post">
              <p class="text-end fs-3"><button type="submit" class="btn btn-primary" style="align-items: right" name="confirmresident">Ok</button></p>
            </form>
          </div>
        </div>
      </div>
    </div>
    <!-- verification of changing password pop-up modal end -->
    <script>
      const togglePassword = document.querySelector('#togglePassword1');
      const password = document.querySelector('#id_password1');

        togglePassword.addEventListener('click', function (e) {
          // toggle the type attribute
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          // toggle the eye slash icon
          this.classList.toggle('fa-eye-slash');
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php
    if($showModal == 1) {
      // CALL MODAL HERE
      echo '<script type="text/javascript">
        $(document).ready(function(){
          $("#verifychange").modal("show");
        });
      </script>';
    }
    ?>
  </body>
</html>
