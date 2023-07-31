<?php
  require 'dbcon.php';
  
  $showerror = 0;

  if(isset($_REQUEST['otp'])){
      session_start();
      $email = filter_var(strtolower(trim($_POST['email'],FILTER_SANITIZE_EMAIL)));
      try{
          /** sql query in pdo format that check if the email input is already existing in the db */
          $select_stmt = $con->prepare("SELECT email FROM admins_tbl WHERE email = :email");
          $select_stmt->execute([':email' => $email]);
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);

          if(isset($row['email']) == $email){
              $_SESSION['otpses']['email'] = $email;
              header("Location: send_otp.php");
          }
          else{
              $showerror = 1;
          }
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
    <link rel="stylesheet" href="css/style.css" />
    <title>Sign in & Sign up Form</title>
    <?php  
    if($showerror == 1){
        echo '<script type="text/javascript">
            alert("Email is not registered.")
        </script>';
    }
    ?>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="verify.php" method="post" class="sign-in-form">
            <h2 class="title">Verified User?</h2>
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
            <div class="row">
                <div class="col-md-12">
                    <p>Enter your registered email here.</p>
                </div>
            </div>
            <div class="input-field">
                <i class="bi bi-envelope-check-fill"></i>
                <input type="email" placeholder="Enter email here" name="email" required />
            </div>
            <div class="row">
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary" name="otp">Confirm</button>
                </div>
                <div class="col-md-6">
                    <a href="login.php" class="btn btn-primary">Cancel</a>
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
        
      </div>
    </div>
  </body>
</html>
