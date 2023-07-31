<?php
  /** establish db connection */
  require_once 'dbcon.php';

  /** start session */
  session_start();
  $showModal = 0;

    /** account user validation */
    if(isset($_SESSION['userresident'])){
    header("Location: https://barangayconcepciondos.com/");
    }

    /** login user codes */
    if(isset($_REQUEST['login_btn'])){
        /** getting and filtering inputs */
        $username = filter_var(strtolower($_REQUEST['username']),FILTER_SANITIZE_STRING);
        $pass = strip_tags($_REQUEST['passwrd']);
        $passlen = strlen($pass);
        
        /** query if the inputs are valid */
        try{
            $query1 = $con->prepare("SELECT * FROM penresident WHERE username = :username LIMIT 1");
            $query1->execute([
            ':username' => $username
            ]);
            $row = $query1->fetch(PDO::FETCH_ASSOC);
            if($query1->rowCount() > 0){
                if($row['status'] == 'Inactive'){
                    $errorMSG1 = "Your account has been deactivated. Please proceed to our Barangay Hall to reactivate your account.";
                }
                else{
                    if(password_verify($pass, $row["pass"])){
                    $_SESSION['userresident']['username'] = $row["username"];
                    $_SESSION['userresident']['email'] = $row["email"];
                    $_SESSION['userresident']['resident_id'] = $row["resident_id"];
                    $_SESSION['userresident']['fnamedisplay'] = $row["firstname"];
                    $_SESSION['userresident']['transprint'] = 0;
                    $_SESSION['userresident']['profileaccess'] = 0;
                    header("Location: https://barangayconcepciondos.com/");
                    }
                    else{
                        $errorMSG1 = "Incorrect username or password.";
                    }
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

  /** resident code validation */
  if(isset($_REQUEST['checkcode'])){
    $resident_code = filter_var(strtolower($_REQUEST['hcode']),FILTER_SANITIZE_STRING);
    /** variable use to decide in the if statement */
    try{
      /** sql query in pdo format that check if the resident code input is already existing in the db */
      $slct_stmt1 = $con->prepare("SELECT resident_code FROM penresident WHERE resident_code = :resident_code LIMIT 1");
      $slct_stmt1->execute([
        ':resident_code' => $resident_code
      ]);
      $row1 = $slct_stmt1->fetch(PDO::FETCH_ASSOC);
      if($slct_stmt1->rowCount() > 0){
        $checker1 = 0;
        $showModal = 2;
      }
      else{
        $errorMSG = "Invalid resident code.";
        $showModal = 1;
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
                <p class="text-center"><button type="submit" name="login_btn" class="btn btn-danger">Login</button></p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <p class="text-center text-info">---------------OR---------------</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <p class="text-center"><a href="https://barangayconcepciondos.com/" type="button" name="login_btn" class="btn btn-danger">Homepage</a></p>
              </div>
            </div>
          </form>
          <form action="register.php" class="sign-up-form">
            <h2 class="title">Become a part of our community!</h2>
            <button type="submit" class="btn btn-primary">Register</button>
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
            <button class="btn transparent text-white" id="sign-up-btn">
              Sign up
            </button>
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

    <!-- verification of adding individual pop-up modal -->
    <div class="modal fade" id="confaddindividual" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title">Resident Information</h5>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-4">
                <label for="firstname" class="form-label">Firstname</label>
                <p id="firstname" class="text-start fs-3">Juan</p>
              </div>
              <div class="col-md-4">
                <label for="middlename" class="form-label">Middlename</label>
                <p id="middlename" class="text-start fs-3">Pinoy</p>
              </div>
              <div class="col-md-4">
                <label for="lastname" class="form-label">Lastname</label>
                <p id="lastname" class="text-start fs-3">Dela Cruz</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label for="age" class="form-label">Age</label>
                <p id="age" class="text-start fs-3">40</p>
              </div>
              <div class="col-md-3">
                <label for="birthdate" class="form-label">Age</label>
                <p id="birthdate" class="text-start fs-3">01/10/1970</p>
              </div>
              <div class="col-md-3">
                <label for="gender" class="form-label">Gender</label>
                <p id="age" class="text-start fs-3">Male</p>
              </div>
              <div class="col-md-3">
                <label for="citizenship" class="form-label">Citizenship</label>
                <p id="citizenship" class="text-start fs-3">Filipino</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label for="birthplace" class="form-label">Birthplace</label>
                <p id="birthplace" class="text-start fs-3">1234 Kahoy St. Brgy.21 Sampaloc, Manila</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-4">
                <label for="occupation" class="form-label">Occupation</label>
                <p id="occupation" class="text-start fs-3">Lawyer</p>
              </div>
              <div class="col-md-4">
                <label for="civil" class="form-label">Civil Status</label>
                <p id="age" class="text-start fs-3">Married</p>
              </div>
              <div class="col-md-4">
                <label for="religion" class="form-label">Religion</label>
                <p id="age" class="text-start fs-3">Roman Catholic</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <label for="nature" class="form-label">Nature of work</label>
                <p id="nature" class="text-start fs-3">Law and Public Policy</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-5">
                <label for="contact" class="form-label">Contact #</label>
                <p id="age" class="text-start fs-3">09999999999</p>
              </div>
              <div class="col-md-7">
                <label for="typeemail" class="form-label">Email</label>
                <p id="age" class="text-start fs-3">delacruzj@gmail.com</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-2">
                <label for="old" class="form-label">Old House #</label>
                <p id="old" class="text-start fs-3">001</p>
              </div>
              <div class="col-md-2">
                  <label for="middlename" class="form-label">New House #</label>
                  <p id="new" class="text-start fs-3">100</p>
              </div>
              <div class="col-md-4">
                  <label for="street" class="form-label">Street</label>
                  <p id="street" class="text-start fs-3">Langka</p>
              </div>
              <div class="col-md-4">
                  <label for="village" class="form-label">Village/Subdivision</label>
                  <p id="village" class="text-start fs-3">Village D</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <label for="fposition" class="form-label">Family Position</label>
                <p id="age" class="text-start fs-3">Brother</p>
              </div>
              <div class="col-md-4">
                <label for="housetype" class="form-label">Household Type</label>
                <p id="housetype" class="text-start fs-3">Permanent</p>
              </div>
              <div class="col-md-5">
                <label for="ptext1">Household Head</label>
                <p id="ptext1" class="text-start fs-3">Dela Cruz, Juan P.</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confmodl">Confirm</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addindividual">Back</button>
          </div>
        </div>
      </div>
    </div>
    <!-- verification of adding individual pop-up modal end -->


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
    <script src="./js/app.js"></script>
    <?php
    if($showModal == 1) {
      // CALL MODAL HERE
      echo '<script type="text/javascript">
        $(document).ready(function(){
          $("#censusmodal").modal("show");
        });
      </script>';
    }
    elseif($showModal == 2) {
      // CALL MODAL HERE
      echo '<script type="text/javascript">
        $(document).ready(function(){
          $("#addindividual").modal("show");
        });
      </script>';
    }
    
    ?>
  </body>
</html>
