<?php
  require_once 'dbcon.php';
  session_start();
  $showerror = 0;

  if(isset($_SESSION['otpsesres']['otp']) && isset($_SESSION['otpsesres']['email'])){
    $otp = filter_var(strtolower($_SESSION['otpsesres']['otp']),FILTER_SANITIZE_STRING);
    $email = filter_var(strtolower($_SESSION['otpsesres']['email']),FILTER_SANITIZE_EMAIL);    
  }
  else{
    session_destroy();
    header("Location: login.php");
  }

  if(isset($_REQUEST['verify'])){
    $entotp = filter_var(strtolower($_REQUEST['entotp']),FILTER_SANITIZE_STRING);
    if($entotp == NULL){
      $showerror = 1;
    }
    else{
      if($entotp == $otp){
        try{
          $sql2 = $con->prepare("DELETE FROM otp_tbl WHERE otp = :otp");
          $sql2->execute([':otp' => $entotp]);
          header("Location: recovery.php");
        }
        catch(PDOException $e){
          $pdoError = $e->getMesage();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css"> -->
    <link rel="stylesheet" href="css/style.css" />
    <title>Sign in & Sign up Form</title>
    <?php  
    if($showerror == 1){
        echo '<script type="text/javascript">
            alert("Please provide the OTP.")
        </script>';
    }
    if($showerror == 2){
      echo '<script type="text/javascript">
          alert("Invalid OTP. Please try again")
      </script>';
  }
    ?>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="verification.php" method="post" class="sign-in-form" autocomplete="off">
            <h2 class="title">Verified User?</h2>
            <div class="row">
              <div class="col-md-12">
                <p>An OTP was sent to your registered email.</p>
              </div>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="text" placeholder="Enter otp here" name="entotp" />
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
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>
