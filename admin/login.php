<?php
  /** establish db connection */
  require_once 'dbcon.php';

  /** start session */
  session_start();

  /** account user validation */
  if(isset($_SESSION['user'])){
    header("Location: dashboard.php");
  }

  /** login user codes */
  if(isset($_REQUEST['login_btn'])){
    /** getting and filtering inputs */
    $username = filter_var(strtolower($_REQUEST['username']),FILTER_SANITIZE_STRING);
    $pass = strip_tags($_REQUEST['passwrd']);
    $passlen = strlen($pass);

    /** query if the inputs are valid */
    try{
      $query1 = $con->prepare("SELECT * FROM admins_tbl WHERE username = :username LIMIT 1");
      $query1->execute([
        ':username' => $username
      ]);
      $row = $query1->fetch(PDO::FETCH_ASSOC);

      if($query1->rowCount() > 0){
        if(password_verify($pass, $row["pass"])){
          $_SESSION['user']['username'] = $row["username"];
          $_SESSION['user']['email'] = $row["email"];
          $_SESSION['user']['passlen'] = $passlen;
          $_SESSION['user']['id'] = $row["id"];
          
          header("Location: dashboard.php");
        }
        else{
          $errorMSG1 = "Incorrect username or password.";
        }
      }
      else{
        $errorMSG1 = "Incorrect username or password.";
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  if(isset($_REQUEST['otp'])){
    $otp = filter_var($_REQUEST['otp'],FILTER_SANITIZE_STRING);
    header("Location: send_otp.php");
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
    <link rel="stylesheet" href="css/style.css" />
    <title>Sign in & Sign up Form</title>
    <style>
      /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      .fa-eye{
        position: absolute;
        top: 2%;
        left: 88%; 
        cursor: pointer;
      }
    </style>
    <script
      src="https://kit.fontawesome.com/64d58efce2.js"
      crossorigin="anonymous"
    ></script>
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="login.php" method='post' class="sign-in-form">
            <h2 class="title">Welcome Back!</h2>
            <?php
              if(isset($errorMSG1)){
                echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                <div>"
                  .$errorMSG1.
                "</div>
              </div>";
              }
            ?>
            <div class="input-field">
              <i class="fas fa-user"></i>
              <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-field">
              <i class="fas fa-lock"></i>
              <input type="password" name="passwrd" placeholder="Password" id="id_password" required>
              <i class="far fa-eye" id="togglePassword"></i>
            </div>
            <div class="row">
              <div class="col-md-12">
                <p>Forgot password?<a href="verify.php"><button type="button" class="btn btn-link">Click here</button></a></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <input type="submit" value="Login" name="login_btn" class="btn solid btn-danger" />
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
          </div>
          <img src="img/regis.svg" class="image" alt="" />
        </div>
      </div>
    </div>
    <script>
      const togglePassword = document.querySelector('#togglePassword');
      const password = document.querySelector('#id_password');

        togglePassword.addEventListener('click', function (e) {
          // toggle the type attribute
          const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
          password.setAttribute('type', type);
          // toggle the eye slash icon
          this.classList.toggle('fa-eye-slash');
      });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  </body>
</html>