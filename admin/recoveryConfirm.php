<?php
  session_start();

  if(isset($_SESSION['otpsesres']['otp']) && isset($_SESSION['otpsesres']['email'])){
    $otp = filter_var(strtolower($_SESSION['otpsesres']['otp']),FILTER_SANITIZE_STRING);
    $email = filter_var(strtolower($_SESSION['otpsesres']['email']),FILTER_SANITIZE_EMAIL);    
  }
  else{
    session_destroy();
    header("Location: login.php");
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.9.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="css/signup.css" />
    <title>Sign in & Sign up Form</title>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="confirmation_recovery.php" method='post' class="sign-in-form">
            <h2 class="title text-center">Password Successfully changed.</h2>
            <div class="row">
              <div class="col-md-12">
                <p class="text-center"><button type="submit" name="confirmresident" class="btn btn-primary">OK</button></p>
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
  </body>
</html>
