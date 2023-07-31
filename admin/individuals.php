<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  /** creating connection to db */
  require_once 'dbcon.php';
  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  /** start session */
  session_start();
  date_default_timezone_set('Asia/Manila');
  $datenow = date('Y-m-d');
  $date = date('Y-m-d');
  $minordate = date('Y-m-d', strtotime($date . ' - 12 years'));

  /** variable for the modals */
  $showModal = 0;
  $checker1 = 0;
  $checkmodal2 = 0;
  $checkmodal3 = 0;
  $checkmodal6 = 0;

  /** code for couting registered residents */
  $sql7 = "SELECT COUNT(resident_id) FROM penresident WHERE status = 'Registered'";
  $res1 = $con->query($sql7);
  $cnt1 = $res1->fetchColumn();

  /** code for couting pending residents */
  $sql8 = "SELECT COUNT(resident_id) FROM penresident WHERE status = 'Pending'";
  $res2 = $con->query($sql8);
  $cnt2 = $res2->fetchColumn();

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  /** adding resident head codes */
  if(isset($_REQUEST['headadd'])){
    /** getting and filtering data gathered from the html form */
    $family_position = "Head";
    $chck1 = "0";
    $firstname = ucwords(strtolower(filter_var(trim($_REQUEST['firstname'],FILTER_SANITIZE_STRING))));
    $middlename = ucwords(strtolower(filter_var(trim($_REQUEST['middlename'],FILTER_SANITIZE_STRING))));
    $lastname = ucwords(strtolower(filter_var(trim($_REQUEST['lastname'],FILTER_SANITIZE_STRING))));
    $birthplace = filter_var($_REQUEST['birthplace'],FILTER_SANITIZE_STRING);
    $gender = $_REQUEST['gender'];
    $citizenship = $_REQUEST['citizenship'];
    $civil = $_REQUEST['civil'];
    $religion = $_REQUEST['religion'];
    $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
    $email = strtolower(trim(filter_var($_REQUEST['email'],FILTER_SANITIZE_EMAIL)));
    $old = filter_var($_REQUEST['old'],FILTER_SANITIZE_NUMBER_INT);
    $new = filter_var($_REQUEST['new'],FILTER_SANITIZE_NUMBER_INT);
    $street = $_REQUEST['street'];
    $village = filter_var(trim($_REQUEST['village'],FILTER_SANITIZE_STRING));
    $housetype = $_REQUEST['housetype'];
    $birthdate = $_REQUEST['birthdate'];
    $family_role = $_REQUEST['family_role'];
    $occupation = filter_var(trim($_REQUEST['occupation'],FILTER_SANITIZE_STRING));
    $nature = $_REQUEST['nature'];
    /** checking if all fields are not empty (by setting the $errorMSG variable)  */
    if(empty($firstname) || empty($middlename) || empty($lastname)  || empty($birthplace) || empty($contact) || empty($email) || empty($old) || empty($new) || empty($village) || empty($birthdate) || empty($occupation) || $gender == "none" || $civil == "none"  || $religion == "none" || $street == "none" || $housetype == "none" || $family_role == "none" || $birthdate == "none" || $nature == "none" || $family_role == "none"){
      $errorMSG = "Please input all requirements.";
	    $showModal = 1;
    }
    else{
      /** preparing variables to use in creating username */
      $lname = strtolower(str_replace(' ', '', $lastname));
      $fname = strtoupper($firstname[0]);
      $bdate = substr($birthdate, -2);
    }
    /** verification of the errorMsg variable */
    if(empty($errorMSG)){
      /** try/catch method for the first query */
      try{

        /** setting the timezone of the system */
        $date_reg = date('Y-m-d H:i:s');
            
        /** characters to be used in the resident id */
        $permitted_resid = '012345678901234567890123456789012345678901234567890123456789';

        /** dowhile loop that prevent the duplication of resident id in the db */
        $check1 = true;
        $cts1 = 0;
        do {
          $cts1++;
          /** creating resident id by shuffling those permitted characters*/
          $resident_id = (substr(str_shuffle($permitted_resid ), 0, 5));
          /** query to check if the produce resident id is already existing in the db */
          $select_stmt3 = $con->prepare("SELECT resident_id FROM penresident WHERE resident_id = :resident_id");
          $select_stmt3->execute([':resident_id' => $resident_id]);
          $row3 = $select_stmt3->fetch(PDO::FETCH_ASSOC);
          if(isset($row3['resident_id']) == $resident_id){
            $check1 = false;
          }
          else{
            $check1 = true;
          }
        } while ($check1 == false && $cts1 != 20);

        /** an if statement that check if the capacity of residents that the system can handle */
        if($check1){
          /** characters to be used in the resident code */
          $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
          /** dowhile loop that prevent the duplication of resident code in the db */
          $check = true;
          $cts = 0;
          do {
            $cts++;
            /** creating resident code by shuffling those permitted characters*/
            $resident_code = (substr(str_shuffle($permitted_chars), 0, 10));
            /** query to check if the produce resident id is already existing in the db */
            $select_stmt2 = $con->prepare("SELECT resident_code FROM penresident WHERE resident_code = :resident_code");
            $select_stmt2->execute([':resident_code' => $resident_code]);
            $row2 = $select_stmt2->fetch(PDO::FETCH_ASSOC);
            if(isset($row2['resident_code']) == $resident_code){
              $check = false;
            }
            else{
              $check = true;
            }
          } while ($check == false && $cts != 1000000);
          /** an if statement that check if all of the possible combinations are already used in the db */
          if($check){
            /** creation of variables to be used on the password */
            $birthd = str_replace('-', '', $birthdate);
            $bdate1 = substr($birthd, -4);
            $fname1 = strtolower($firstname[0]);
            $lname1 = strtolower($lastname[0]);
            $bdate2 = substr($birthd,0,4);
            $mname1 = strtolower($middlename[0]);
            /** producing unique password by combining different variables */
            $prepass = $bdate2 . $mname1 . $resident_id;
            $pass = str_shuffle($prepass);
            /** encripting password */
            $epass = password_hash($pass, PASSWORD_DEFAULT);
            /** producing unique username by combining different variables */
            $username = strtolower($lname .  $resident_id);

            /** setting age */
            $date1 = date('Y-m-d');
            $datetime2 = new DateTime($date1);
            $datetime1 = new DateTime($birthdate);
            $difference = $datetime1->diff($datetime2);
            $age = ($difference->y);

            /** sql query in pdo format that check if the email input is already existing in the db */
            $select_stmt = $con->prepare("SELECT email FROM penresident WHERE email = :email");
            $select_stmt->execute([':email' => $email]);
            $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
            if(isset($row['email']) == $email){
              $errorMSG = "Email is already registered.";
              $checker1 = 1;
              $showModal = 1;
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
                $checkmodal6 = 1;
                $checker1 = 1;
                $showModal = 1;
              }
              elseif($data_key["is_disposable_email"]["value"] == true){
                $errorMSG = "Email must not be disposable.";
                $checkmodal6 = 1;
                $checker1 = 1;
                $showModal = 1;
              }
              else{
                $showModal = 2;
              }
            }
          }
          else{
            $errorMSG = "All possible resident codes has been already used.";
            $checker1 = 1;
            $showModal = 1;
          }
        }
        else{
          $errorMSG = "The system already reach it's maximum resident capacity.";
          $checker1 = 1;
          $showModal = 1;
        }

        
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
  }

  /** reloading census form by retaining previous inputs codes */
  if(isset($_REQUEST['reloadaddhead'])){
    $firstname = $_REQUEST['firstname'];
    $middlename = $_REQUEST['middlename'];
    $lastname = $_REQUEST['lastname'];
    $gender = $_REQUEST['gender'];
    $birthdate = $_REQUEST['birthdate'];
    $birthplace = $_REQUEST['birthplace'];
    $citizenship = $_REQUEST['citizenship'];
    $civil = $_REQUEST['civil'];
    $religion = $_REQUEST['religion'];
    $contact = $_REQUEST['contact'];
    $email = $_REQUEST['email'];
    $occupation = $_REQUEST['occupation'];
    $nature = $_REQUEST['nature'];
    $old = $_REQUEST['old'];
    $new = $_REQUEST['new'];
    $street = $_REQUEST['street'];
    $village = $_REQUEST['village'];
    $housetype = $_REQUEST['housetype'];
    $family_role = $_REQUEST['family_role'];
    $chck1 = $_REQUEST['chck1'];
    $resident_code = $_REQUEST['resident_code'];
    $checker1 = 1;
    $reloader = 1;
    if($chck1 == 1){
      $showModal = 1;
    }
    else{
      $showModal = 8;
    }
    
  }

  /** confirmation of adding head codes */
  if(isset($_REQUEST['confmodadding'])){
    $resident_id = $_REQUEST['resident_id'];
    $pass = $_REQUEST['pass'];
    $resident_code = $_REQUEST['resident_code'];
    $family_position = $_REQUEST['family_position'];
    $firstname = $_REQUEST['firstname'];
    $middlename = $_REQUEST['middlename'];
    $lastname = $_REQUEST['lastname'];
    $gender = $_REQUEST['gender'];
    $birthdate = $_REQUEST['birthdate'];
    $birthplace = $_REQUEST['birthplace'];
    $citizenship = $_REQUEST['citizenship'];
    $civil = $_REQUEST['civil'];
    $religion = $_REQUEST['religion'];
    $contact = $_REQUEST['contact'];
    $email = $_REQUEST['email'];
    $occupation = $_REQUEST['occupation'];
    $nature = $_REQUEST['nature'];
    $old = $_REQUEST['old'];
    $new = $_REQUEST['new'];
    $street = $_REQUEST['street'];
    $village = $_REQUEST['village'];
    $housetype = $_REQUEST['housetype'];
    $family_role = $_REQUEST['family_role'];
    $username = $_REQUEST['username'];
    $epass = $_REQUEST['epass'];
    $date_reg = $_REQUEST['date_reg'];
    $status = "Registered";
    try{
      /** preparing a pdo query that adds values to the db */
      $query = $con->prepare("INSERT INTO penresident (resident_id, resident_code, family_position, housetype, old, new, street, village, firstname, middlename, lastname, birthdate, gender, citizenship, birthplace, occupation, civil, religion, nature, contact, email, username, pass, date_reg, status, family_role)
      VALUES (:resident_id, :resident_code, :family_position, :housetype, :old, :new, :street, :village, :firstname, :middlename, :lastname, :birthdate, :gender, :citizenship, :birthplace, :occupation, :civil, :religion, :nature, :contact, :email, :username, :pass, :date_reg, :status, :family_role)");
      /** executing the query */
      if($query->execute(
        [
        ':resident_id' => $resident_id,
        ':resident_code' => $resident_code,
        ':family_position' => $family_position,
        ':housetype' => $housetype,
        ':old' => $old,
        ':new' => $new,
        ':street' => $street,
        ':village' => $village,
        ':firstname' => $firstname,
        ':middlename' => $middlename,
        ':lastname' => $lastname,
        ':birthdate' => $birthdate,
        ':gender' => $gender,
        ':citizenship' => $citizenship,
        ':birthplace' => $birthplace,
        ':occupation' => $occupation,
        ':civil' => $civil,
        ':religion' => $religion,
        ':nature' => $nature,
        ':contact' => $contact,
        ':email' => $email,
        ':username' => $username,
        ':pass' => $epass,
        ':date_reg' => $date_reg,
        ':status' => $status,
        ':family_role' => $family_role
      ]
      )
      ){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'lfvjckzasfrpzxzf';
        $mail->SMTPSecure = 'ssl';
        $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Registration Approved</strong></p><br>
                 <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Your Username: <strong>'.$username.'</strong><br>
                 Your Password: <strong>'.$pass.'</strong></p><br>
                 <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">We are happy for you becoming a registered resident.<br>
                 From: <b>Concecion Dos Management.</b></p>';
        $mail->Port = 465;

        $mail->setFrom('pjjumawan18@gmail.com');

        $mail->addAddress($email);

        $mail->isHTML(true);

        

        $mail->Subject = 'Resident Registration';
        $mail->Body = $body;

        $mail->send();
        $showModal = 11;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** viewing resident details codes */
  if(isset($_REQUEST['resmodopen'])){
    $resi_id = $_REQUEST['resmodopen'];
    try{
      /** query to get the appropriate data from the database */ 
      $sql2 = "SELECT * FROM penresident WHERE resident_id = $resi_id";
      $d2 =  $con->query($sql2);
      foreach($d2 as $data2){
        $resident_id2 = ($data2['resident_id']);
        $resident_code2 = ($data2['resident_code']);
        $fname2 = ($data2['firstname']);
        $mname2 = ($data2['middlename']);
        $lname2 = ($data2['lastname']);
        $gender2 = ($data2['gender']);
        $birthdate2 = ($data2['birthdate']);
        $birthplace2 = ($data2['birthplace']);
        $citizenship2 = ($data2['citizenship']);
        $civil2 = ($data2['civil']);
        $religion2 = ($data2['religion']);
        $contact2 = ($data2['contact']);
        $email2 = ($data2['email']);
        $occupation2 = ($data2['occupation']);
        $nature2 = ($data2['nature']);
        $old2 = ($data2['old']);
        $new2 = ($data2['new']);
        $street2 = ($data2['street']);
        $village2 = ($data2['village']);
        $housetype2 = ($data2['housetype']);
        $family_position2 = ($data2['family_position']);
        $username2 = ($data2['username']);
        $family_role2 = ($data2['family_role']);
        $verification_id = ($data2['verification_id']);
      }
      /** setting age */
      $date2 = date('Y-m-d');
      $date2_2 = new DateTime($date2);
      $date1_1 = new DateTime($birthdate2);
      $difference_1 = $date1_1->diff($date2_2);
      $age2 = ($difference_1->y);

      $showModal = 3;
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** deleting resident codes */
  if(isset($_REQUEST['deleteconf'])){
    $resi_id = $_REQUEST['deleteconf'];
    $stat = "Inactive";

    $sql5 = $con->prepare("UPDATE penresident SET status=:status WHERE resident_id=:resident_id");
    $sql5->execute([':status' => $stat, ':resident_id' => $resi_id]);
    $showModal = 4;
  }

  /** updating resident details codes */
  if(isset($_REQUEST['updateres'])){
    $resident_id3 = $_REQUEST['updateres'];
    /** query to get the appropriate data from the database */ 
    $sql2 = "SELECT * FROM penresident WHERE resident_id = $resident_id3";
    $d2 =  $con->query($sql2);
    foreach($d2 as $data2){
      $resident_id2 = ($data2['resident_id']);
      $fname2 = ($data2['firstname']);
      $mname2 = ($data2['middlename']);
      $lname2 = ($data2['lastname']);
      $gender2 = ($data2['gender']);
      $birthdate2 = ($data2['birthdate']);
      $birthplace2 = ($data2['birthplace']);
      $citizenship2 = ($data2['citizenship']);
      $civil2 = ($data2['civil']);
      $religion2 = ($data2['religion']);
      $contact2 = ($data2['contact']);
      $email2 = ($data2['email']);
      $occupation2 = ($data2['occupation']);
      $nature2 = ($data2['nature']);
      $old2 = ($data2['old']);
      $new2 = ($data2['new']);
      $street2 = ($data2['street']);
      $village2 = ($data2['village']);
      $housetype2 = ($data2['housetype']);
      $family_position2 = ($data2['family_position']);
      $family_role2 = ($data2['family_role']);
    }

    /** getting and filtering data gathered from the html form */
    $firstname = strtoupper(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING));
    $middlename = strtoupper(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING));
    $lastname = strtoupper(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING));
    $birthplace = filter_var($_REQUEST['birthplace'],FILTER_SANITIZE_STRING);
    $gender = $_REQUEST['gender'];
    $citizenship = $_REQUEST['citizenship'];
    $civil = $_REQUEST['civil'];
    $religion = $_REQUEST['religion'];
    $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var(strtolower($_REQUEST['email']),FILTER_SANITIZE_EMAIL);
    $birthdate = $_REQUEST['birthdate'];
    $occupation = filter_var($_REQUEST['occupation'],FILTER_SANITIZE_STRING);
    $nature = $_REQUEST['nature'];
    
    /** checking if all fields are not empty (by setting the $errorMSG variable)  */
    if(empty($firstname) || empty($middlename) || empty($lastname)  || empty($birthplace) || empty($contact) || empty($email) || empty($birthdate) || empty($occupation) || $gender == "none" || $civil == "none"  || $religion == "none" || $birthdate == "none" || $nature == "none"){
      $errorMSG = "Please input all requirements.";
	    $showModal = 5;
    }
    /** verification of the errorMsg variable */
    if(empty($errorMSG)){
      /** sql query in pdo format that check if the email input is already existing in the db */
      $sql3 = "SELECT * FROM penresident WHERE resident_id = $resident_id3";
      $d3 =  $con->query($sql3);
      foreach($d3 as $data3){
        $prev_email = ($data3['email']);
      }
      if($prev_email == $email){
        /** preparing a pdo query that update values to the db */
        $sql11 = $con->prepare("UPDATE penresident SET firstname=:firstname, middlename=:middlename, lastname=:lastname, birthdate=:birthdate, gender=:gender,citizenship=:citizenship, birthplace=:birthplace, occupation=:occupation, civil=:civil, religion=:religion, nature=:nature, contact=:contact, email=:email WHERE resident_id=:resident_id");
        /** execute query */
        $sql11->execute(
          [
            ':firstname' => $firstname, 
            ':middlename' => $middlename, 
            ':lastname' => $lastname,
            ':birthdate' => $birthdate, 
            ':gender' => $gender, 
            ':citizenship' => $citizenship, 
            ':birthplace' => $birthplace, 
            ':occupation' => $occupation, 
            ':civil' => $civil, 
            ':religion' => $religion, 
            ':nature' => $nature, 
            ':contact' => $contact, 
            ':email' => $email, 
            ':resident_id' => $resident_id3
          ]
        );
        $showModal = 6;
      }
      else{
        try{
          /** sql query in pdo format that check if the email input is already existing in the db */
          $select_stmt = $con->prepare("SELECT email FROM penresident WHERE email = :email");
          $select_stmt->execute([':email' => $email]);
          $row = $select_stmt->fetch(PDO::FETCH_ASSOC);
  
          if(isset($row['email']) == $email){
            $errorMSG2 = "Email is already registered by other residents. Please use your previous or new email.";
            $showModal = 5;
          }
          else{
            /** preparing a pdo query that update values to the db */
            $sql12 = $con->prepare("UPDATE penresident SET firstname=:firstname, middlename=:middlename, lastname=:lastname, birthdate=:birthdate, gender=:gender,citizenship=:citizenship, birthplace=:birthplace, occupation=:occupation, civil=:civil, religion=:religion, nature=:nature, contact=:contact, email=:email WHERE resident_id=:resident_id");
            /** execute query */
            $sql12->execute(
              [
                ':firstname' => $firstname, 
                ':middlename' => $middlename, 
                ':lastname' => $lastname,
                ':birthdate' => $birthdate, 
                ':gender' => $gender, 
                ':citizenship' => $citizenship, 
                ':birthplace' => $birthplace, 
                ':occupation' => $occupation, 
                ':civil' => $civil, 
                ':religion' => $religion, 
                ':nature' => $nature, 
                ':contact' => $contact, 
                ':email' => $email, 
                ':resident_id' => $resident_id3
              ]
            );
            $showModal = 6;
          }
        }
        catch(PDOException $e){
          $pdoError = $e->getMesage();
        }
      }    
    }
  }

  /** approving prending request codes */
  if(isset($_REQUEST['pendingresopen'])){
    if(isset($_REQUEST['pendingresopen'])){
      $resident_id = $_REQUEST['pendingresopen'];
    }
    if(isset($_REQUEST['resident_id'])){
      $resident_id = $_REQUEST['resident_id'];
    }
    $sql10 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
    $d10 =  $con->query($sql10);
    foreach($d10 as $data10){
      $resident_id = ($data10['resident_id']);
      $resident_code = ($data10['resident_code']);
      $family_position = ($data10['family_position']);
      $housetype = ($data10['housetype']);
      $old = ($data10['old']);
      $new = ($data10['new']);
      $street = ($data10['street']);
      $village = ($data10['village']);
      $firstname = ($data10['firstname']);
      $middlename = ($data10['middlename']);
      $lastname = ($data10['lastname']);
      $birthdate = ($data10['birthdate']);
      $gender = ($data10['gender']);
      $citizenship = ($data10['citizenship']);
      $birthplace = ($data10['birthplace']);
      $occupation = ($data10['occupation']);
      $civil = ($data10['civil']);
      $religion = ($data10['religion']);
      $nature = ($data10['nature']);
      $contact = ($data10['contact']);
      $email = ($data10['email']);
      $family_role = ($data10['family_role']);
      $verification_id = ($data10['verification_id']);
    }
    $sql13 = "SELECT * FROM penresident WHERE resident_code = '$resident_code' AND family_position = 'Head'";
    $d13 =  $con->query($sql13);
    foreach($d13 as $data13){
      $fname3 = $data13['firstname'];
      $lname3 = $data13['lastname'];
      $mnane3 = $data13['middlename'];
    }
    $mnane3 = substr($mnane3, 0, 1);
    $fhead = ($lname3 . ', ' . $fname3 . ' ' . $mnane3);
    /** setting age */
    $date3 = date('Y-m-d');
    $datetime3 = new DateTime($date3);
    $datetime4 = new DateTime($birthdate);
    $difference2 = $datetime4->diff($datetime3);
    $age3 = ($difference2->y);
    $showModal = 12;
  }

  /** approving prending request codes */
  if(isset($_REQUEST['approvepending'])){
    $resident_id = $_REQUEST['resident_id'];
    $birthdate = $_REQUEST['birthdate'];
    $firstname = $_REQUEST['firstname'];
    $lastname = $_REQUEST['lastname'];
    $middlename = $_REQUEST['middlename'];
    $email = $_REQUEST['email'];
    $verification_id = $_REQUEST['verification_id'];
    /** preparing variables to use in creating username */
    $lname = strtolower(str_replace(' ', '', $lastname));
    $fname = strtoupper($firstname[0]);
    $bdate = substr($birthdate, -2);
    /** creation of variables to be used on the password */
    $birthd = str_replace('-', '', $birthdate);
    $bdate1 = substr($birthd, -4);
    $fname1 = strtolower($firstname[0]);
    $lname1 = strtolower($lastname[0]);
    $bdate2 = substr($birthd,0,4);
    $mname1 = strtolower($middlename[0]);
    /** producing unique password by combining different variables */
    $prepass = $bdate2 . $mname1 . $resident_id;
    $pass = str_shuffle($prepass);
    /** encripting password */
    $epass = password_hash($pass, PASSWORD_DEFAULT);
    $status = "Registered";
    /** producing unique username by combining different variables */
    $username = strtolower($lname .  $resident_id);
    $checker2 = 1;
    $showModal = 13;
    
  }
  
  /** confirmation of denying prending request codes */
  if(isset($_REQUEST['confapprovepending'])){
    $resident_id = $_REQUEST['resident_id'];
    $verification_id = $_REQUEST['verification_id'];
    $username = $_REQUEST['username'];
    $password = $_REQUEST['password'];
    $prevpassword = $_REQUEST['prevpassword'];
    $email = $_REQUEST['email'];
    $status = 'Registered';
    $date_reg = date('Y-m-d H:i:s');
    try{
      # pdo prepared statement that update the pending resident
      $sql15 = $con->prepare("UPDATE penresident SET username=:username, pass=:pass, status=:status, date_reg=:date_reg, verification_id = NULL WHERE resident_id=:resident_id");
      # execute query
      if($sql15->execute(
        [
        ':resident_id' => $resident_id,
        ':username' => $username,
        ':pass' => $password,
        ':date_reg' => $date_reg,
        ':status' => $status
      ]
      )
      ){
        unlink("../resident/images/".$verification_id);
        # sending email of approval
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'lfvjckzasfrpzxzf';
        $mail->SMTPSecure = 'ssl';
        $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Registration Approved</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Your Username: <strong>'.$username.'</strong><br>
                  Your Password: <strong>'.$prevpassword.'</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">We are happy for you becoming a registered resident.<br>
                  From: <b>Concecion Dos Management.</b></p>';
        $mail->Port = 465;

        $mail->setFrom('pjjumawan18@gmail.com');

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = 'Resident Registration';
        $mail->Body = $body;

        $mail->send();
        $notif = 0;
        
        $showModal = 14;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }
  
  /** denying prending request codes */
  if(isset($_REQUEST['denypending'])){
    $resident_id = $_REQUEST['resident_id'];
    $verification_id = $_REQUEST['verification_id'];
    $email = $_REQUEST['email'];
    $checker2 = 0;
    $showModal = 13;
  }

  /** confirmation of denying prending request codes */
  if(isset($_REQUEST['confdenypending'])){
    $resident_id = $_REQUEST['resident_id'];
    $verification_id = $_REQUEST['verification_id'];
    $email = $_REQUEST['email'];
    $showModal = 15;
  }

  # reason denied codes
  if(isset($_REQUEST['denyreason'])){
    $resident_id = $_REQUEST['resident_id'];
    $verification_id = $_REQUEST['verification_id'];
    $reason = filter_var($_REQUEST['reason'],FILTER_SANITIZE_STRING);
    $email = $_REQUEST['email'];
    try{
      $sql14 = $con->prepare("DELETE FROM penresident WHERE resident_id = :resident_id");
      if($sql14->execute([':resident_id' => $resident_id])){
        unlink("../resident/images/".$verification_id);
        # sending email of approval
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'lfvjckzasfrpzxzf';
        $mail->SMTPSecure = 'ssl';
        $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Registration Denied</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Reason:<br>
                  '.$reason.'<br>
                  From: <b>Concecion Dos Management.</b></p>';
        $mail->Port = 465;

        $mail->setFrom('pjjumawan18@gmail.com');

        $mail->addAddress($email);

        $mail->isHTML(true);

        $mail->Subject = 'Resident Registration';
        $mail->Body = $body;

        $mail->send();
        $notif = 1;
        $showModal = 14;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** reloading page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: individuals.php");
  }

  if(isset($_REQUEST['openmod'])){
    $openmod = $_REQUEST['openmod'];
    if($openmod == 1){
      $showModal = 16;
    }
    else{
      $showModal = 1;
    }
  }

  #opening choosetype codes
  if(isset($_REQUEST['chooseopen'])){
    $type = $_REQUEST['chooseopen'];
    if($type == 1){
      $showModal = 17;
    }
    if($type == 2){
      $showModal = 18;
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
        $checkmodal1 = 0;
        $_SESSION['user']['checker4'] = 0;
        $showModal = 8;
      }
      else{
        $type = $_REQUEST['type'];
        $errorMSG = "Invalid resident code.";
        $showModal = 17;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** adding resident member codes */
  if(isset($_REQUEST['indiadd'])){
    /** getting and filtering data gathered from the html form */
    $family_position = "Member";
    $firstname = ucwords(strtolower(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
    $middlename = ucwords(strtolower(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
    $lastname = ucwords(strtolower(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
    $birthplace = filter_var($_REQUEST['birthplace'],FILTER_SANITIZE_STRING);
    $gender = $_REQUEST['gender'];
    $citizenship = $_REQUEST['citizenship'];
    $checkmodal3 = $_REQUEST['checkmodal3'];
    $civil = $_REQUEST['civil'];
    $checkmodal4 = $_SESSION['user']['checker4'];
    $religion = $_REQUEST['religion'];
    $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
    $email = filter_var(strtolower($_REQUEST['email']),FILTER_SANITIZE_EMAIL);
    $birthdate = $_REQUEST['birthdate'];
    $family_role = $_REQUEST['family_role'];
    $occupation = filter_var($_REQUEST['occupation'],FILTER_SANITIZE_STRING);
    $nature = $_REQUEST['nature'];
    $birthdateguar = $_REQUEST['birthdateguar'];
    $resident_code = $_REQUEST['resident_code'];
    /** checking if all fields are not empty (by setting the $errorMSG variable)  */
    if(empty($firstname) || empty($middlename) || empty($lastname)  || empty($birthplace) || empty($contact) || empty($email) || empty($birthdate) || empty($occupation) || $gender == "none" || $civil == "none"  || $religion == "none" || $family_role == "none" || $birthdate == "none" || $citizenship == "none"){
      $errorMSG = "Please input all requirements.";
    }
    else{
      if(empty($errorMSG)){
        /** preparing variables to use in creating username */
        $lname = strtolower(str_replace(' ', '', $lastname));
        $fname = strtoupper($firstname[0]);
        $bdate = substr($birthdate, -2);

        /** setting the timezone of the system */
        date_default_timezone_set('Asia/Manila');
        $date_reg = date('Y-m-d H:i:s');
        
        /** characters to be used in the resident id */
        $permitted_resid = '012345678901234567890123456789012345678901234567890123456789';

        /** dowhile loop that prevent the duplication of resident id in the db */
        $check1 = true;
        $cts1 = 0;
        do {
          $cts1++;
          /** creating resident id by shuffling those permitted characters*/
          $resident_id = (substr(str_shuffle($permitted_resid ), 0, 5));
          /** query to check if the produce resident id is already existing in the db */
          $slct_stmt3 = $con->prepare("SELECT resident_id FROM penresident WHERE resident_id = :resident_id");
          $slct_stmt3->execute([':resident_id' => $resident_id]);
          $row5 = $slct_stmt3->fetch(PDO::FETCH_ASSOC);
          if(isset($row5['resident_id']) == $resident_id){
            $check1 = false;
          }
          else{
            $check1 = true;
          }
        } while ($check1 == false && $cts1 != 20);
        if($check1){
          /** try/catch method for the first query */
          try{
            if($checkmodal4 == 1){
              /** query to get the appropriate data from the database */ 
              $sql6 = "SELECT * FROM penresident WHERE resident_code = '$resident_code'";
              $d6 =  $con->query($sql6);
              foreach($d6 as $data6){
                $old7 = ($data6['old']);
                $new7 = ($data6['new']);
                $street7 = ($data6['street']);
                $village7 = ($data6['village']);
                $housetype7 = ($data6['housetype']);
              }
              $old = $old7;
              $new = $new7;
              $street = $street7;
              $village = $village7;
              $housetype = $housetype7;

              /** setting age */
              $date1 = date('Y-m-d');
              $datetime2 = new DateTime($date1);
              $datetime1 = new DateTime($birthdate);
              $difference = $datetime1->diff($datetime2);
              $age = ($difference->y);

              $showModal = 9;
            }
            else{
              /** sql query in pdo format that check if the email input is already existing in the db */
              $slct_stmt2 = $con->prepare("SELECT email FROM penresident WHERE email = :email");
              $slct_stmt2->execute([':email' => $email]);
              $row4 = $slct_stmt2->fetch(PDO::FETCH_ASSOC);

              if(isset($row4['email']) == $email){
                $errorMSG = 'Email already registered.';
                $checkmodal1 = 1;
                $checkmodal2 = 1;
                $showModal = 8;
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
                
                if($data_key["deliverability"] === "UNDELIVERABLE"){
                  $errorMSG = 'Must be a valid email.';
                  $checkmodal1 = 1;
                  $checkmodal2 = 1;
                  $showModal = 8;
                }
                elseif($data_key["is_disposable_email"]["value"] === true){
                  $errorMSG = 'Must be a valid email.';
                  $checkmodal1 = 1;
                  $checkmodal2 = 1;
                  $showModal = 8;
                }
                else{
                  /** query to get the appropriate data from the database */ 
                  $sql17 = "SELECT * FROM penresident WHERE resident_code = '$resident_code'";
                  $d17 =  $con->query($sql17);
                  foreach($d17 as $data17){
                    $old7 = ($data17['old']);
                    $new7 = ($data17['new']);
                    $street7 = ($data17['street']);
                    $village7 = ($data17['village']);
                    $housetype7 = ($data17['housetype']);
                  }
                  $old = $old7;
                  $new = $new7;
                  $street = $street7;
                  $village = $village7;
                  $housetype = $housetype7;
                  /** creation of variables to be used on the password */
                  $birthd = str_replace('-', '', $birthdate);
                  $bdate1 = substr($birthd, -4);
                  $fname1 = strtolower($firstname[0]);
                  $lname1 = strtolower($lastname[0]);
                  $bdate2 = substr($birthd,0,4);
                  $mname1 = strtolower($middlename[0]);
                  /** producing unique password by combining different variables */
                  $prepass = $bdate2 . $mname1 . $resident_id;
                  $pass = str_shuffle($prepass);
                  /** encripting password */
                  $epass = password_hash($pass, PASSWORD_DEFAULT);
                  $status = "Registered";
                  /** producing unique username by combining different variables */
                  $username = strtolower($lname .  $resident_id);

                  /** setting age */
                  $date1 = date('Y-m-d');
                  $datetime2 = new DateTime($date1);
                  $datetime1 = new DateTime($birthdate);
                  $difference = $datetime1->diff($datetime2);
                  $age = ($difference->y);
                  $showModal = 9;
                }
              }
            }
          }
          catch(PDOException $e){
            $pdoError = $e->getMesage();
          }
        }
        else{
          $errorMSG4 = "The amount of resident is already maxed.";
          $showModal = 8;
        }
      }
    }
  }

  /** confirmation of adding household member codes */
  if(isset($_REQUEST['confmemadding'])){
    $resident_id = $_REQUEST['resident_id'];
    $resident_code = $_REQUEST['resident_code'];
    $family_position = $_REQUEST['family_position'];
    $firstname = $_REQUEST['firstname'];
    $middlename = $_REQUEST['middlename'];
    $lastname = $_REQUEST['lastname'];
    $gender = $_REQUEST['gender'];
    $birthdate = $_REQUEST['birthdate'];
    $birthplace = $_REQUEST['birthplace'];
    $citizenship = $_REQUEST['citizenship'];
    $civil = $_REQUEST['civil'];
    $religion = $_REQUEST['religion'];
    $contact = $_REQUEST['contact'];
    $email = $_REQUEST['email'];
    $occupation = $_REQUEST['occupation'];
    $nature = $_REQUEST['nature'];
    $old = $_REQUEST['old'];
    $new = $_REQUEST['new'];
    $street = $_REQUEST['street'];
    $village = $_REQUEST['village'];
    $housetype = $_REQUEST['housetype'];
    $family_role = $_REQUEST['family_role'];
    $newImageName = $_REQUEST['newImageName'];
    $username = $_REQUEST['username'];
    $pass = $_REQUEST['pass'];
    $epass = $_REQUEST['epass'];
    $checkmodal4 = $_REQUEST['checkmodal4'];
    $status = "Registered";

    /** setting the time of the session */
    $date_reg = date('Y-m-d H:i:s');

    try{
      /** preparing a pdo query that adds values to the db */
      $query1 = $con->prepare("INSERT INTO penresident (resident_id, resident_code, family_position, housetype, old, new, street, village, firstname, middlename, lastname, birthdate, gender, citizenship, birthplace, occupation, civil, religion, nature, contact, email, status, family_role, date_reg, username, pass)
      VALUES (:resident_id, :resident_code, :family_position, :housetype, :old, :new, :street, :village, :firstname, :middlename, :lastname, :birthdate, :gender, :citizenship, :birthplace, :occupation, :civil, :religion, :nature, :contact, :email, :status, :family_role, :date_reg, :username, :pass)");
      /** executing the query */
      if($query1->execute(
        [
        ':resident_id' => $resident_id,
        ':resident_code' => $resident_code,
        ':family_position' => $family_position,
        ':housetype' => $housetype,
        ':old' => $old,
        ':new' => $new,
        ':street' => $street,
        ':village' => $village,
        ':firstname' => $firstname,
        ':middlename' => $middlename,
        ':lastname' => $lastname,
        ':birthdate' => $birthdate,
        ':gender' => $gender,
        ':citizenship' => $citizenship,
        ':birthplace' => $birthplace,
        ':occupation' => $occupation,
        ':civil' => $civil,
        ':religion' => $religion,
        ':nature' => $nature,
        ':contact' => $contact,
        ':email' => $email,
        ':status' => $status,
        ':date_reg' => $date_reg,
        ':username' => $username,
        ':pass' => $epass,
        ':family_role' => $family_role
      ]
      )
      ){
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'lfvjckzasfrpzxzf';
        $mail->SMTPSecure = 'ssl';
        $body = '<h1>Your request to become a registered citizen has been approved.</h1><br>
                 <h2>Your Username: '.$username.'</h2><br>
                 <h2>Your Password: '.$pass.'</h2><br>
                 <h2>We are happy for you becoming a registered resident.</h2><br>
                 <h2>From: Concecion Dos Management.</h2>';
        $mail->Port = 465;

        $mail->setFrom('pjjumawan18@gmail.com');

        $mail->addAddress($email);

        $mail->isHTML(true);

        

        $mail->Subject = 'Resident Registration';
        $mail->Body = $body;

        $mail->send();
        $showModal = 11;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** resident code validation */
  if(isset($_REQUEST['resiid'])){
    $resident_id = filter_var(strtolower($_REQUEST['resid']),FILTER_SANITIZE_STRING);
    /** variable use to decide in the if statement */
    try{
      /** sql query in pdo format that check if the resident code input is already existing in the db */
      $slct_stmt4 = $con->prepare("SELECT resident_id FROM penresident WHERE resident_id = :resident_id LIMIT 1");
      $slct_stmt4->execute([
        ':resident_id' => $resident_id
      ]);
      $row4 = $slct_stmt4->fetch(PDO::FETCH_ASSOC);
      if($slct_stmt4->rowCount() > 0){
        $checkmodal1 = 0;
        $checkmodal3 = 1;
        /** query to get the appropriate data from the database */ 
        $sql1 = "SELECT email, contact, resident_code, birthdate FROM penresident WHERE resident_id = '$resident_id'";
        $d1 =  $con->query($sql1);
        foreach($d1 as $data1){
          $email1 = ($data1['email']);
          $contact1 = ($data1['contact']);
          $resident_code = ($data1['resident_code']);
          $birthdateguar = ($data1['birthdate']);
        }
        $_SESSION['user']['checker4'] = 1;
        $showModal = 8;
      }
      else{
        $errorMSG = "Invalid resident id.";
        $showModal = 18;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }

  if(isset($_REQUEST['printtransaction'])){
    $_SESSION['user']['transprint'] = 1;
    $typeprint = $_REQUEST['printtransaction'];
    if($typeprint == 1){
      $_SESSION['user']['agespan'] = $_REQUEST['agespan'];
      header("Location: print_individuals.php");
    }
  }

  /** reloading census form by retaining previous inputs codes */
  if(isset($_REQUEST['reloadaddhead2'])){
    $firstname = $_REQUEST['firstname'];
    $middlename = $_REQUEST['middlename'];
    $lastname = $_REQUEST['lastname'];
    $gender = $_REQUEST['gender'];
    $birthdate = $_REQUEST['birthdate'];
    $birthplace = $_REQUEST['birthplace'];
    $citizenship = $_REQUEST['citizenship'];
    $civil = $_REQUEST['civil'];
    $religion = $_REQUEST['religion'];
    $contact = $_REQUEST['contact'];
    $email = $_REQUEST['email'];
    $checkmodal3 = $_REQUEST['checkmodal3'];
    $occupation = $_REQUEST['occupation'];
    $nature = $_REQUEST['nature'];
    $old = $_REQUEST['old'];
    $new = $_REQUEST['new'];
    $street = $_REQUEST['street'];
    $village = $_REQUEST['village'];
    $housetype = $_REQUEST['housetype'];
    $family_role = $_REQUEST['family_role'];
    $resident_code = $_REQUEST['resident_code'];
    $_SESSION['user']['checker4'] = 1;
    $checkmodal4 = $_SESSION['user']['checker4'];
    $birthdateguar = $_REQUEST['birthdateguar'];
    $checkmodal1 = 1;
    $showModal = 8;
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Individuals</title>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="css/mainstyle.css" />
    <style>
      /* Chrome, Safari, Edge, Opera */
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }

      /* Firefox */
      input[type=number] {
        -moz-appearance: textfield;
      }

      #myBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
      }

      #myBtn:hover {
        background-color: #555;
      }
    </style>
    <?php
    if($checkmodal2 == 1){
      echo '<script>
      $(document).ready(function(){
          $("#addindividual").on("shown.bs.modal", function(){
              $(this).find("#emailrel").focus();
          });
      });
      </script>';
    }
    if($checkmodal6 == 1){
      echo '<script>
      $(document).ready(function(){
          $("#addhead").on("shown.bs.modal", function(){
              $(this).find("#emailrel").focus();
          });
      });
      </script>';
    }
    ?>
</head>
<body>
  <button
    type="button"
    class="btn btn-dark btn-lg rounded-circle text-center"
    id="myBtn"
    onclick="topFunction()"
    title="Go to top"
    >
    <i class="bi bi-chevron-up"></i>
  </button>
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
          <a href="dashboard.php" class="nav-link px-3 text-white active">
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
          <div class="collapse show" id="residents">
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
                  <a href="individuals.php" class="nav-link px-3 bg-white active text-dark">
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
        <div class="col-md-12 fw-bold fs-3 text-danger">Residents</div>
      </div>
      <div class="row">
        <div class="col-md-4 mb-3">
        </div>
        <div class="col-md-4 mb-3">
          <div class="card text-dark border-dark shadow-lg bg-body rounded pb-0 h-100">
            <div class="card-body text-center">
              <h6 class="fw-bold">Total Resident/s</h6>
              <h2><?php echo $cnt1; ?></h2>
            </div>
          </div>
        </div>
        <div class="col-md-4 mb-3">
        </div>
      </div>
      <div class="container-fluid border border-dark shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row m-1">
          <div class="col-md-6">
            <p class="text-start">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addmodal">New Resident</button>
              <button type="button" class="btn btn-secondary shadow-none mx-1 position-relative" data-bs-toggle="modal" data-bs-target="#requestresident">
                <i class="bi bi-person-check"></i>
                Pending Requests
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <span><?=$cnt2?></span>
                  <span class="visually-hidden">New alerts</span>
                </span>
              </button>
            </p>
          </div>
          <div class="col-md-6">
            <p class="text-end">
              <button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#genreportmodal"><i class="bi bi-printer"></i>&nbsp;Generate Report</button>
            </p>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Pending Resident Data Table
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table id="transaction" class="table table-striped" style="width:100%">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Age</th>
                  <th>Gender</th>
                  <th>Street</th>
                  <th>Class</th>
                  <th>Preferences</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  $sql1 = "SELECT * FROM penresident WHERE status = 'Registered'";
                  $d1 =  $con->query($sql1);
                  foreach($d1 as $data1){
                ?>
                <tr>
                  <th><?php echo $data1['lastname'];?>, <?php echo $data1['firstname'];?> <?php echo substr($data1['middlename'], 0, 1);?>.</th>
                  <?php
                    $date3 = date('Y-m-d');
                    $datetime5 = new DateTime($date3);
                    $datetime6 = new DateTime($data1['birthdate']);
                    $difference3 = $datetime6->diff($datetime5);
                    $age4 = ($difference3->y);
                  ?>
                  <th><?php echo $age4?></th>
                  <th><?php echo $data1['gender'];?></th>
                  <th><?php echo $data1['street'];?></th>
                  <?php
                  if($age4 < 18){
                    echo '<th>Minor</th>';
                  }
                  elseif($age4 > 59){
                    echo '<th>Senior</th>';
                  }
                  else{
                    echo '<th>Adult</th>';
                  }
                  ?>
                  <form action="individuals.php" method="post">
                  <th><button type="submit" class="btn btn-link" value="<?=$data1['resident_id']?>" name="resmodopen">Details</button></th>
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

      <!-- choosing type pop-up modal -->
      <div class="modal fade" id="addmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="addmodal">Adding Resident</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <img src="images/sample.jpg" class="img-thumbnail rounded mx-auto d-block" alt="...">
                  <p class="text-center"><button type="submit" class="btn btn-primary" name="openmod" value="1">Individual</button></p>
                </div>
                <div class="col-md-6">
                  <img src="images/sample.jpg" class="img-thumbnail rounded mx-auto d-block" alt="...">
                  <p class="text-center"><button type="submit" class="btn btn-primary" name="openmod" value="2">Househead</button></p>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- choosing type pop-up modal -->

      <!-- adding head pop-up modal -->
      <div class="modal fade" id="addhead" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Census Form</h5>
            </div>
            <div class="modal-body">
              <form action="individuals.php" method="post">
                <?php
                  if(isset($errorMSG)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG2)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG2.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG3)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG3.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG4)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG4.
                    "</div>
                  </div>";
                  }

                  if($checker1 == 1){
                    echo '<div class="row">
                            <div class="col-md-12">
                              <label for="firstname" class="form-label">Firstname</label>
                              <input type="text" class="form-control" name="firstname" id="firstname" value="'.$firstname.'" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="middlename" class="form-label">Middlename</label>
                              <input type="text" class="form-control" name="middlename" id="middlename" value="'.$middlename.'" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="lastname" class="form-label">Lastname</label>
                              <input type="text" class="form-control" name="lastname" id="lastname" value="'.$lastname.'" required>
                            </div>
                          </div>
                          <div class="row">';
                    if($gender == 'Male'){
                      echo '<div class="col-md-6">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                                <option >Select</option>
                                <option value="Male" selected>Male</option>
                                <option value="Female">Female</option>
                              </select>
                            </div>';
                    }
                    else{
                      echo '<div class="col-md-6">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                                <option >Select</option>
                                <option value="Male">Male</option>
                                <option value="Female" selected>Female</option>
                              </select>
                            </div>';
                    }
                    echo '<div class="col-md-6">
                            <label for="birthdate" class="form-label">Birthdate</label>
                            <input type="date" class="form-select" id="birthdate" name="birthdate" min="1800-01-01" max="3000-12-31" value="'.$birthdate.'" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label for="birthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" name="birthplace" id="birthplace" value="'.$birthplace.'" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label for="citizenship" class="form-label">Citizenship</label>
                            <input class="form-control" list="lists" id="citizenship" name="citizenship" value="'.$citizenship.'" required>
                            <datalist id="lists">
                              <option selected value="Filipino">Filipino</option>
                              <option value="Afghanistan">Afghanistan</option>
                              <option value="Åland Islands">Åland Islands</option>
                              <option value="Albania">Albania</option>
                              <option value="Algeria">Algeria</option>
                              <option value="American Samoa">American Samoa</option>
                              <option value="Andorra">Andorra</option>
                              <option value="Angola">Angola</option>
                              <option value="Anguilla">Anguilla</option>
                              <option value="Antarctica">Antarctica</option>
                              <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                              <option value="Argentina">Argentina</option>
                              <option value="Armenia">Armenia</option>
                              <option value="Aruba">Aruba</option>
                              <option value="Australia">Australia</option>
                              <option value="Austria">Austria</option>
                              <option value="Azerbaijan">Azerbaijan</option>
                              <option value="Bahamas">Bahamas</option>
                              <option value="Bahrain">Bahrain</option>
                              <option value="Bangladesh">Bangladesh</option>
                              <option value="Barbados">Barbados</option>
                              <option value="Belarus">Belarus</option>
                              <option value="Belgium">Belgium</option>
                              <option value="Belize">Belize</option>
                              <option value="Benin">Benin</option>
                              <option value="Bermuda">Bermuda</option>
                              <option value="Bhutan">Bhutan</option>
                              <option value="Bolivia">Bolivia</option>
                              <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                              <option value="Botswana">Botswana</option>
                              <option value="Bouvet Island">Bouvet Island</option>
                              <option value="Brazil">Brazil</option>
                              <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                              <option value="Brunei Darussalam">Brunei Darussalam</option>
                              <option value="Bulgaria">Bulgaria</option>
                              <option value="Burkina Faso">Burkina Faso</option>
                              <option value="Burundi">Burundi</option>
                              <option value="Cambodia">Cambodia</option>
                              <option value="Cameroon">Cameroon</option>
                              <option value="Canada">Canada</option>
                              <option value="Cape Verde">Cape Verde</option>
                              <option value="Cayman Islands">Cayman Islands</option>
                              <option value="Central African Republic">Central African Republic</option>
                              <option value="Chad">Chad</option>
                              <option value="Chile">Chile</option>
                              <option value="China">China</option>
                              <option value="Christmas Island">Christmas Island</option>
                              <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                              <option value="Colombia">Colombia</option>
                              <option value="Comoros">Comoros</option>
                              <option value="Congo">Congo</option>
                              <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                              <option value="Cook Islands">Cook Islands</option>
                              <option value="Costa Rica">Costa Rica</option>
                              <option value="Cote D\'ivoire">Cote D\'ivoire</option>
                              <option value="Croatia">Croatia</option>
                              <option value="Cuba">Cuba</option>
                              <option value="Cyprus">Cyprus</option>
                              <option value="Czech Republic">Czech Republic</option>
                              <option value="Denmark">Denmark</option>
                              <option value="Djibouti">Djibouti</option>
                              <option value="Dominica">Dominica</option>
                              <option value="Dominican Republic">Dominican Republic</option>
                              <option value="Ecuador">Ecuador</option>
                              <option value="Egypt">Egypt</option>
                              <option value="El Salvador">El Salvador</option>
                              <option value="Equatorial Guinea">Equatorial Guinea</option>
                              <option value="Eritrea">Eritrea</option>
                              <option value="Estonia">Estonia</option>
                              <option value="Ethiopia">Ethiopia</option>
                              <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                              <option value="Faroe Islands">Faroe Islands</option>
                              <option value="Fiji">Fiji</option>
                              <option value="Finland">Finland</option>
                              <option value="France">France</option>
                              <option value="French Guiana">French Guiana</option>
                              <option value="French Polynesia">French Polynesia</option>
                              <option value="French Southern Territories">French Southern Territories</option>
                              <option value="Gabon">Gabon</option>
                              <option value="Gambia">Gambia</option>
                              <option value="Georgia">Georgia</option>
                              <option value="Germany">Germany</option>
                              <option value="Ghana">Ghana</option>
                              <option value="Gibraltar">Gibraltar</option>
                              <option value="Greece">Greece</option>
                              <option value="Greenland">Greenland</option>
                              <option value="Grenada">Grenada</option>
                              <option value="Guadeloupe">Guadeloupe</option>
                              <option value="Guam">Guam</option>
                              <option value="Guatemala">Guatemala</option>
                              <option value="Guernsey">Guernsey</option>
                              <option value="Guinea">Guinea</option>
                              <option value="Guinea-bissau">Guinea-bissau</option>
                              <option value="Guyana">Guyana</option>
                              <option value="Haiti">Haiti</option>
                              <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                              <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                              <option value="Honduras">Honduras</option>
                              <option value="Hong Kong">Hong Kong</option>
                              <option value="Hungary">Hungary</option>
                              <option value="Iceland">Iceland</option>
                              <option value="India">India</option>
                              <option value="Indonesia">Indonesia</option>
                              <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                              <option value="Iraq">Iraq</option>
                              <option value="Ireland">Ireland</option>
                              <option value="Isle of Man">Isle of Man</option>
                              <option value="Israel">Israel</option>
                              <option value="Italy">Italy</option>
                              <option value="Jamaica">Jamaica</option>
                              <option value="Japan">Japan</option>
                              <option value="Jersey">Jersey</option>
                              <option value="Jordan">Jordan</option>
                              <option value="Kazakhstan">Kazakhstan</option>
                              <option value="Kenya">Kenya</option>
                              <option value="Kiribati">Kiribati</option>
                              <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
                              <option value="Korea, Republic of">Korea, Republic of</option>
                              <option value="Kuwait">Kuwait</option>
                              <option value="Kyrgyzstan">Kyrgyzstan</option>
                              <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>
                              <option value="Latvia">Latvia</option>
                              <option value="Lebanon">Lebanon</option>
                              <option value="Lesotho">Lesotho</option>
                              <option value="Liberia">Liberia</option>
                              <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                              <option value="Liechtenstein">Liechtenstein</option>
                              <option value="Lithuania">Lithuania</option>
                              <option value="Luxembourg">Luxembourg</option>
                              <option value="Macao">Macao</option>
                              <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                              <option value="Madagascar">Madagascar</option>
                              <option value="Malawi">Malawi</option>
                              <option value="Malaysia">Malaysia</option>
                              <option value="Maldives">Maldives</option>
                              <option value="Mali">Mali</option>
                              <option value="Malta">Malta</option>
                              <option value="Marshall Islands">Marshall Islands</option>
                              <option value="Martinique">Martinique</option>
                              <option value="Mauritania">Mauritania</option>
                              <option value="Mauritius">Mauritius</option>
                              <option value="Mayotte">Mayotte</option>
                              <option value="Mexico">Mexico</option>
                              <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                              <option value="Moldova, Republic of">Moldova, Republic of</option>
                              <option value="Monaco">Monaco</option>
                              <option value="Mongolia">Mongolia</option>
                              <option value="Montenegro">Montenegro</option>
                              <option value="Montserrat">Montserrat</option>
                              <option value="Morocco">Morocco</option>
                              <option value="Mozambique">Mozambique</option>
                              <option value="Myanmar">Myanmar</option>
                              <option value="Namibia">Namibia</option>
                              <option value="Nauru">Nauru</option>
                              <option value="Nepal">Nepal</option>
                              <option value="Netherlands">Netherlands</option>
                              <option value="Netherlands Antilles">Netherlands Antilles</option>
                              <option value="New Caledonia">New Caledonia</option>
                              <option value="New Zealand">New Zealand</option>
                              <option value="Nicaragua">Nicaragua</option>
                              <option value="Niger">Niger</option>
                              <option value="Nigeria">Nigeria</option>
                              <option value="Niue">Niue</option>
                              <option value="Norfolk Island">Norfolk Island</option>
                              <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                              <option value="Norway">Norway</option>
                              <option value="Oman">Oman</option>
                              <option value="Pakistan">Pakistan</option>
                              <option value="Palau">Palau</option>
                              <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                              <option value="Panama">Panama</option>
                              <option value="Papua New Guinea">Papua New Guinea</option>
                              <option value="Paraguay">Paraguay</option>
                              <option value="Peru">Peru</option>
                              <option value="Pitcairn">Pitcairn</option>
                              <option value="Poland" selected>Poland</option>
                              <option value="Portugal">Portugal</option>
                              <option value="Puerto Rico">Puerto Rico</option>
                              <option value="Qatar">Qatar</option>
                              <option value="Reunion">Reunion</option>
                              <option value="Romania">Romania</option>
                              <option value="Russian Federation">Russian Federation</option>
                              <option value="Rwanda">Rwanda</option>
                              <option value="Saint Helena">Saint Helena</option>
                              <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                              <option value="Saint Lucia">Saint Lucia</option>
                              <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                              <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                              <option value="Samoa">Samoa</option>
                              <option value="San Marino">San Marino</option>
                              <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                              <option value="Saudi Arabia">Saudi Arabia</option>
                              <option value="Senegal">Senegal</option>
                              <option value="Serbia">Serbia</option>
                              <option value="Seychelles">Seychelles</option>
                              <option value="Sierra Leone">Sierra Leone</option>
                              <option value="Singapore">Singapore</option>
                              <option value="Slovakia">Slovakia</option>
                              <option value="Slovenia">Slovenia</option>
                              <option value="Solomon Islands">Solomon Islands</option>
                              <option value="Somalia">Somalia</option>
                              <option value="South Africa">South Africa</option>
                              <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                              <option value="Spain">Spain</option>
                              <option value="Sri Lanka">Sri Lanka</option>
                              <option value="Sudan">Sudan</option>
                              <option value="Suriname">Suriname</option>
                              <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                              <option value="Swaziland">Swaziland</option>
                              <option value="Sweden">Sweden</option>
                              <option value="Switzerland">Switzerland</option>
                              <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                              <option value="Taiwan">Taiwan</option>
                              <option value="Tajikistan">Tajikistan</option>
                              <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                              <option value="Thailand">Thailand</option>
                              <option value="Timor-leste">Timor-leste</option>
                              <option value="Togo">Togo</option>
                              <option value="Tokelau">Tokelau</option>
                              <option value="Tonga">Tonga</option>
                              <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                              <option value="Tunisia">Tunisia</option>
                              <option value="Turkey">Turkey</option>
                              <option value="Turkmenistan">Turkmenistan</option>
                              <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                              <option value="Tuvalu">Tuvalu</option>
                              <option value="Uganda">Uganda</option>
                              <option value="Ukraine">Ukraine</option>
                              <option value="United Arab Emirates">United Arab Emirates</option>
                              <option value="United Kingdom">United Kingdom</option>
                              <option value="United States">United States</option>
                              <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                              <option value="Uruguay">Uruguay</option>
                              <option value="Uzbekistan">Uzbekistan</option>
                              <option value="Vanuatu">Vanuatu</option>
                              <option value="Venezuela">Venezuela</option>
                              <option value="Viet Nam">Viet Nam</option>
                              <option value="Virgin Islands, British">Virgin Islands, British</option>
                              <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                              <option value="Wallis and Futuna">Wallis and Futuna</option>
                              <option value="Western Sahara">Western Sahara</option>
                              <option value="Yemen">Yemen</option>
                              <option value="Zambia">Zambia</option>
                              <option value="Zimbabwe">Zimbabwe</option>
                            </datalist>
                          </div>
                        </div>';
                    if($civil == 'Single'){
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single" selected>Single</option>
                                  <option value="Married">Married</option>
                                  <option value="Divorced">Divorced</option>
                                  <option value="Widowed">Widowed</option>
                                </select>
                              </div>';
                    }
                    elseif($civil == 'Married'){
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single">Single</option>
                                  <option value="Married" selected>Married</option>
                                  <option value="Divorced">Divorced</option>
                                  <option value="Widowed">Widowed</option>
                                </select>
                              </div>';
                    }
                    elseif($civil == 'Divorced'){
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single">Single</option>
                                  <option value="Married">Married</option>
                                  <option value="Divorced" selected>Divorced</option>
                                  <option value="Widowed">Widowed</option>
                                </select>
                              </div>';
                    }
                    else{
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single">Single</option>
                                  <option value="Married">Married</option>
                                  <option value="Divorced">Divorced</option>
                                  <option value="Widowed" selected>Widowed</option>
                                </select>
                              </div>';
                    }
                    echo '<div class="col-md-8">
                            <label for="religion" class="form-label">Religion</label>
                            <input class="form-control" list="lists3" id="religion" name="religion" value="'.$religion.'" required>
                            <datalist id="lists3">
                              <option value="" hidden>Select</option>
                              <option value="Roman Catholic">Roman Catholic</option>
                              <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                              <option value="Jehova\'s Witnesses">Jehova\'s Witnesses</option>
                              <option value="Born Again">Born Again</option>
                            </datalist>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                          <div class="input-group mb-3">
                            <span class="input-group-text">+63</span>
                            <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" value="'.$contact.'" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label for="emailrel" class="form-label">Email Address</label>
                            <input type="email" class="form-control" name="email" id="emailrel" value="'.$email.'" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label for="occupation" class="form-label">Occupation</label>
                            <input type="text" class="form-control" name="occupation" id="occupation" value="'.$occupation.'" required>
                          </div>
                        </div>';
                    if($nature == 'Architecture and Engineering'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering" selected>Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($nature == 'Arts, Culture and Entertainment'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment" selected>Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($nature == 'Business, Management and Administration'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration" selected>Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($nature == 'Communications'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications" selected>Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($nature == 'Community and Social Services'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services" selected>Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($nature == 'Education'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education" selected>Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Science and Technology'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology" selected>Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Installation, Repair and Maintenance'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance" selected>Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Farming, Fishing and Forestry'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry" selected>Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Government'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government" selected>Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Health and Medicine'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine" selected>Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Law and Public Policy'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy" selected>Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    elseif($nature == 'Sales'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales" selected>Sales</option>
                                  <option value="None">None</option>
                                </select>
                              </div>
                            </div>';
                    } 
                    else{
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="nature" class="form-label">Nature of work</label>
                                <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                  <option value="Architecture and Engineering">Architecture and Engineering</option>
                                  <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                  <option value="Business, Management and Administration">Business, Management and Administration</option>
                                  <option value="Communications">Communications</option>
                                  <option value="Community and Social Services">Community and Social Services</option>
                                  <option value="Education">Education</option>
                                  <option value="Science and Technology">Science and Technology</option>
                                  <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                  <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                  <option value="Government">Government</option>
                                  <option value="Health and Medicine">Health and Medicine</option>
                                  <option value="Law and Public Policy">Law and Public Policy</option>
                                  <option value="Sales">Sales</option>
                                  <option value="None" selected>None</option>
                                </select>
                              </div>
                            </div>';
                    }
                    echo '<div class="row">
                            <div class="col-md-6">
                              <label for="old" class="form-label">Old House #</label>
                              <input type="number" class="form-control" name="old" id="old"  min="1" max="9999" value="'.$old.'" required>
                            </div>
                            <div class="col-md-6">
                              <label for="middlename" class="form-label">New House #</label>
                              <input type="number" class="form-control" name="new" id="new"  min="1" max="9999" value="'.$new.'" required>
                            </div>
                          </div>';
                  }
                  else{
                    echo '<div class="row">
                            <div class="col-md-12">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <label for="middlename" class="form-label">Middlename</label>
                                <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Middlename..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname..." required>
                            </div>
                          </div>
                        
                          <div class="row">
                            <div class="col-md-6">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                                <option value="" hidden>Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                              </select>
                            </div>
                            <div class="col-md-6">
                              <label for="birthdate" class="form-label">Birthdate</label>
                              <input type="date" class="form-select" id="birthdate" name="birthdate" min="1880-01-01" max="'.$datenow.'" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="birthplace" class="form-label">Birthplace</label>
                              <input type="text" class="form-control" name="birthplace" id="birthplace" placeholder="Birthplace..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="citizenship" class="form-label">Citizenship</label>
                              <input class="form-control" list="lists" id="citizenship" name="citizenship" required>
                              <datalist id="lists">
                                <option selected value="Filipino">Filipino</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Åland Islands">Åland Islands</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Anguilla">Anguilla</option>
                                <option value="Antarctica">Antarctica</option>
                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Bouvet Island">Bouvet Island</option>
                                <option value="Brazil">Brazil</option>
                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Cayman Islands">Cayman Islands</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Christmas Island">Christmas Island</option>
                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                <option value="Cook Islands">Cook Islands</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Cote D\'ivoire">Cote D\'ivoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                <option value="Faroe Islands">Faroe Islands</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="French Guiana">French Guiana</option>
                                <option value="French Polynesia">French Polynesia</option>
                                <option value="French Southern Territories">French Southern Territories</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Gibraltar">Gibraltar</option>
                                <option value="Greece">Greece</option>
                                <option value="Greenland">Greenland</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guadeloupe">Guadeloupe</option>
                                <option value="Guam">Guam</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guernsey">Guernsey</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-bissau">Guinea-bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hong Kong">Hong Kong</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Isle of Man">Isle of Man</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jersey">Jersey</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
                                <option value="Korea, Republic of">Korea, Republic of</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macao">Macao</option>
                                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Martinique">Martinique</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mayotte">Mayotte</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Montserrat">Montserrat</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                <option value="New Caledonia">New Caledonia</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Niue">Niue</option>
                                <option value="Norfolk Island">Norfolk Island</option>
                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Pitcairn">Pitcairn</option>
                                <option value="Poland" selected>Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Puerto Rico">Puerto Rico</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Reunion">Reunion</option>
                                <option value="Romania">Romania</option>
                                <option value="Russian Federation">Russian Federation</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="Saint Helena">Saint Helena</option>
                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option value="Saint Lucia">Saint Lucia</option>
                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Timor-leste">Timor-leste</option>
                                <option value="Togo">Togo</option>
                                <option value="Tokelau">Tokelau</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Viet Nam">Viet Nam</option>
                                <option value="Virgin Islands, British">Virgin Islands, British</option>
                                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                <option value="Western Sahara">Western Sahara</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                              </datalist>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <label for="civil" class="form-label">Civil Status</label>
                              <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                <option value="" hidden>Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                              </select>
                          </div>
                          <div class="col-md-8">
                              <label for="religion" class="form-label">Religion</label>
                              <input class="form-control" list="lists3" id="religion" name="religion" required>
                              <datalist id="lists3">
                                <option value="" hidden>Select</option>
                                <option value="Roman Catholic">Roman Catholic</option>
                                <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                <option value="Jehova\'s Witnesses">Jehova\'s Witnesses</option>
                                <option value="Born Again">Born Again</option>
                              </datalist>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                              <div class="input-group mb-3">
                                <span class="input-group-text">+63</span>
                                <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" required>
                              </div>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="email" class="form-label">Email Address</label>
                              <input type="email" name="email" id="email" class="form-control" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="occupation" class="form-label">Occupation</label>
                              <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Occupation..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="nature" class="form-label">Nature of work</label>
                              <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                <option value="" hidden>Select</option>
                                <option value="Architecture and Engineering">Architecture and Engineering</option>
                                <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                <option value="Business, Management and Administration">Business, Management and Administration</option>
                                <option value="Communications">Communications</option>
                                <option value="Community and Social Services">Community and Social Services</option>
                                <option value="Education">Education</option>
                                <option value="Science and Technology">Science and Technology</option>
                                <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                <option value="Government">Government</option>
                                <option value="Health and Medicine">Health and Medicine</option>
                                <option value="Law and Public Policy">Law and Public Policy</option>
                                <option value="Sales">Sales</option>
                                <option value="None">None</option>
                              </select>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <label for="old" class="form-label">Old House #</label>
                              <input type="number" class="form-control" name="old" id="old"  min="1" max="9999" required>
                            </div>
                            <div class="col-md-6">
                              <label for="middlename" class="form-label">New House #</label>
                              <input type="number" class="form-control" name="new" id="new"  min="1" max="9999" required>
                            </div>
                          </div>';
                  }
                ?>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="street" class="form-label">Street</label>
                              <select class="form-select" aria-label="Default select example" name="street" id="street" required>
                                <option value="" hidden>Choose Street</option>
                                <?php
                                  $sql4 = "SELECT * FROM streets";
                                  $d4 =  $con->query($sql4);
                                  foreach($d4 as $data4){
                                    if($data4['street'] == $street){
                                      echo '<option value="'.$data4['street'].'" selected>'.$data4['street'].' St.</option>';
                                    }
                                    else{
                                      echo '<option value="'.$data4['street'].'">'.$data4['street'].' St.</option>';
                                    }
                                  }
                                ?>
                              </select>
                            </div>
                          </div>
                <?php
                  if($checker1 == 1){
                    echo '<div class="row">
                            <div class="col-md-12">
                              <label for="village" class="form-label">Village/Subdivision</label>
                              <input type="text" class="form-control" name="village" id="village" placeholder="Village..." value="'.$village.'" required>
                            </div>
                          </div>';
                    if($housetype == 'Permanent'){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="housetype" class="form-label">Household Type</label>
                                <select class="form-select" aria-label="Default select example" name="housetype" id="housetype" required>
                                  <option value="Permanent" selected>Permanent</option>
                                  <option value="Renting">Renting</option>
                                </select>
                              </div>
                            </div>';
                    }
                    else{
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="housetype" class="form-label">Household Type</label>
                                <select class="form-select" aria-label="Default select example" name="housetype" id="housetype" required>
                                  <option value="Permanent">Permanent</option>
                                  <option value="Renting" selected>Renting</option>
                                </select>
                              </div>
                            </div>';
                    }
                    if($family_role == "Father"){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father" selected>Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($family_role == "Mother"){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father">Father</option>
                                  <option value="Mother selected">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($family_role == "Son"){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son" selected>Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($family_role == "Daugther"){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther" selected>Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($family_role == "Grandfather"){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather" selected>Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    elseif($family_role == "Grandmother"){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother" selected>Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    else{
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative" selected>Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                  }
                  else{
                    echo '<div class="row">
                            <div class="col-md-12">
                              <label for="village" class="form-label">Village/Subdivision</label>
                              <input type="text" class="form-control" name="village" id="village" placeholder="Village..." placeholder="Entere email here..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="housetype" class="form-label">Household Type</label>
                              <select class="form-select" aria-label="Default select example" name="housetype" id="housetype" required>
                                <option value="" hidden>Select</option>
                                <option value="Permanent">Permanent</option>
                                <option value="Renting">Renting</option>
                              </select>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="family_role" class="form-label">Family Role</label>
                              <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                <option value="" hidden>Select</option>
                                <option value="Father">Father</option>
                                <option value="Mother">Mother</option>
                                <option value="Son">Son</option>
                                <option value="Daugther">Daugther</option>
                                <option value="Grandfather">Grandfather</option>
                                <option value="Grandmother">Grandmother</option>
                                <option value="Relative">Relative</option>
                              </select>
                            </div>
                          </div>';
                  }
                ?>
                
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="headadd">Add</button>
              <button type="button" class="btn btn-danger" data-bs-target="#addmodal" data-bs-toggle="modal" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding head pop-up modal end -->

      <!-- confirmation of adding head pop-up modal -->
      <div class="modal fade" id="confaddhead" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Househead Information</h5>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="resident_id" class="form-label">Resident ID</label>
                  <p id="resident_id" class="text-start fs-3"><?php echo $resident_id;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $firstname;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3"><?php echo $middlename;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3"><?php echo $lastname;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="gender" class="text-start fs-3"><?php echo $gender;?></p>
                </div>
                <div class="col-md-3">
                  <label for="age" class="form-label">Age</label>
                  <p id="age" class="text-start fs-3"><?php echo $age;?></p>
                </div>
                <div class="col-md-6">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <p id="birthdate" class="text-start fs-3"><?php echo $birthdate;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3"><?php echo $birthplace;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="citizenship" class="form-label">Citizenship</label>
                    <p id="citizenship" class="text-start fs-3"><?php echo $citizenship;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="civil" class="text-start fs-3"><?php echo $civil;?></p>
                </div>
                <div class="col-md-8">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="religion" class="text-start fs-3"><?php echo $religion;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="contact" class="form-label">Contact #</label>
                  <p id="contact" class="text-start fs-3">+63<?php echo $contact;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="typeemail" class="form-label">Email Address</label>
                  <p id="email" class="text-start fs-3"><?php echo $email;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3"><?php echo $occupation;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3"><?php echo $nature;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="old" class="form-label">Old House #</label>
                  <p id="old" class="text-start fs-3"><?php echo $old;?></p>
                </div>
                <div class="col-md-6">
                  <label for="new" class="form-label">New House #</label>
                  <p id="new" class="text-start fs-3"><?php echo $new;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="street" class="form-label">Street</label>
                  <p id="street" class="text-start fs-3"><?php echo $street;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    <label for="village" class="form-label">Village/Subdivision</label>
                    <p id="village" class="text-start fs-3"><?php echo $village;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="housetype" class="form-label">Household Type</label>
                  <p id="housetype" class="text-start fs-3"><?php echo $housetype;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="family_position" class="form-label">Family Position</label>
                  <p id="family_position" class="text-start fs-3"><?php echo $family_position;?></p>
                </div>
                <div class="col-md-6">
                  <label for="family_role" class="form-label">Family Role</label>
                  <p id="family_role" class="text-start fs-3"><?php echo $family_role;?></p>
                </div>
              </div>
              <input type="hidden" name="resident_id" value="<?= $resident_id?>">
              <input type="hidden" name="resident_code" value="<?=$resident_code?>">
              <input type="hidden" name="family_position" value="<?= $family_position?>">
              <input type="hidden" name="firstname" value="<?= $firstname?>">
              <input type="hidden" name="middlename" value="<?= $middlename?>">
              <input type="hidden" name="lastname" value="<?= $lastname?>">
              <input type="hidden" name="gender" value="<?= $gender?>">
              <input type="hidden" name="birthdate" value="<?= $birthdate?>">
              <input type="hidden" name="birthplace" value="<?= $birthplace?>">
              <input type="hidden" name="citizenship" value="<?= $citizenship?>">
              <input type="hidden" name="status" value="<?= $status?>">
              <input type="hidden" name="religion" value="<?= $religion?>">
              <input type="hidden" name="contact" value="<?= $contact?>">
              <input type="hidden" name="email" value="<?= $email?>">
              <input type="hidden" name="occupation" value="<?= $occupation?>">
              <input type="hidden" name="nature" value="<?= $nature?>">
              <input type="hidden" name="old" value="<?= $old?>">
              <input type="hidden" name="new" value="<?= $new?>">
              <input type="hidden" name="street" value="<?= $street?>">
              <input type="hidden" name="village" value="<?= $village?>">
              <input type="hidden" name="civil" value="<?= $civil?>">
              <input type="hidden" name="housetype" value="<?= $housetype?>">
              <input type="hidden" name="family_role" value="<?= $family_role?>">
              <input type="hidden" name="username" value="<?= $username?>">
              <input type="hidden" name="pass" value="<?= $pass?>">
              <input type="hidden" name="epass" value="<?= $epass?>">
              <input type="hidden" name="date_reg" value="<?= $date_reg?>">
              <input type="hidden" name="chck1" value="1">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="confmodadding">Confirm</button>
              <button type="submit" class="btn btn-secondary" name="reloadaddhead">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- confirmation of adding head pop-up modal end -->
      
      <!-- adding confirmation pop-up modal -->
      <div class="modal fade" id="addconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Adding Resident</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">New resident has been added.</p>
              <p class="text-center fs-4">Username and password has been sent to the registered email.</p>
            </div>
            <form action="individuals.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- detail pop-up modal -->
      <div class="modal fade" id="detailres" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Resident Information</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <?php
              if($age2 < 18){
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="firstname" class="form-label">Resident Classification</label>
                          <p id="firstname" class="text-start fs-3">Minor</p>
                        </div>
                      </div>';
              }
              elseif($age2 > 59){
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="firstname" class="form-label">Resident Classification</label>
                          <p id="firstname" class="text-start fs-3">Senior</p>
                        </div>
                      </div>';
              }
              else{
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="firstname" class="form-label">Resident Classification</label>
                          <p id="firstname" class="text-start fs-3">Adult</p>
                        </div>
                      </div>';
              }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="resident_id" class="form-label">Resident ID</label>
                  <p id="resident_id" class="text-start fs-3"><?php echo $resident_id2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $fname2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3"><?php echo $mname2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3"><?php echo $lname2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="gender" class="text-start fs-3"><?php echo $gender2;?></p>
                </div>
                <div class="col-md-3">
                  <label for="age" class="form-label">Age</label>
                  <p id="age" class="text-start fs-3"><?php echo $age2;?></p>
                </div>
                <div class="col-md-6">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <p id="birthdate" class="text-start fs-3"><?php echo $birthdate2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3"><?php echo $birthplace2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="citizenship" class="form-label">Citizenship</label>
                  <p id="citizenship" class="text-start fs-3"><?php echo $citizenship2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="civil" class="text-start fs-3"><?php echo $civil2;?></p>
                </div>
                <div class="col-md-8">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="religion" class="text-start fs-3"><?php echo $religion2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="contact" class="form-label">Contact #</label>
                  <p id="contact" class="text-start fs-3">0<?php echo $contact2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="email" class="form-label">Email Address</label>
                  <p id="email" class="text-start fs-3"><?php echo $email2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3"><?php echo $occupation2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3"><?php echo $nature2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="old" class="form-label">Old House #</label>
                  <p id="old" class="text-start fs-3"><?php echo $old2;?></p>
                </div>
                <div class="col-md-6">
                    <label for="middlename" class="form-label">New House #</label>
                    <p id="new" class="text-start fs-3"><?php echo $new2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <label for="street" class="form-label">Street</label>
                    <p id="street" class="text-start fs-3"><?php echo $street2;?></p>
                </div>
                <div class="col-md-6">
                    <label for="village" class="form-label">Village/Subdivision</label>
                    <p id="village" class="text-start fs-3"><?php echo $village2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="housetype" class="form-label">Household Type</label>
                  <p id="housetype" class="text-start fs-3"><?php echo $housetype2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="frole" class="form-label">Family Role</label>
                  <p id="frole" class="text-start fs-3"><?php echo $family_role2;?></p>
                </div>
                <div class='col-md-6'>
                  <label for='fpos' class="form-label">Household Position</label>
                  <p id='fpos' class='text-start fs-3'><?php echo $family_position2;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="username" class="form-label">Username</label>
                  <p id="username" class="text-start fs-3"><?php echo $username2;?></p>
                </div>
              </div>
            </div>
            <form action="individuals.php" method="post">
            <div class="modal-footer">
              <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#editmodal" data-bs-toggle="modal" data-bs-dismiss="modal">Edit</button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deletemodal" data-bs-toggle="modal" data-bs-dismiss="modal">Delete</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- detail pop-up end modal -->
      
      <!-- edit pop-up modal -->
      <div class="modal fade" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Resident Information</h5>
            </div>
            <div class="modal-body">
              <form action="individuals.php" method="post">
                <?php
                  if(isset($errorMSG)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG2)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG2.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG3)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG3.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG4)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG4.
                    "</div>
                  </div>";
                  }
                ?>
                <div class="row">
                  <div class="col-md-12">
                    <label for="firstname" class="form-label">Firstname</label>
                    <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $fname2;?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="middlename" class="form-label">Middlename</label>
                    <input type="text" class="form-control" name="middlename" id="middlename" value="<?php echo $mname2;?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="lastname" class="form-label">Lastname</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $lname2;?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                      <?php
                        if($gender2 == 'Male'){
                          echo '<option selected value="Male">Male</option>
                          <option value="Female">Female</option>';
                        }
                        else{
                          echo '<option value="Male">Male</option>
                          <option selected value="Female">Female</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="birthdate" class="form-label">Birthdate</label>
                    <?php
                    if($birthdate2){
                      echo '<input type="date" class="form-select" id="birthdate" name="birthdate" value="'.$birthdate2.'" min="1800-01-01" max="3000-12-31" required>';
                    }
                    else{
                      echo '<input type="date" class="form-select" id="birthdate" name="birthdate" min="1800-01-01" max="3000-12-31" required>';
                    }
                    ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="birthplace" class="form-label">Birthplace</label>
                    <input type="text" class="form-control" name="birthplace" id="birthplace" value="<?php echo $birthplace2;?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="citizenship" class="form-label">Citizenship</label>
                    <input class="form-control" list="lists" id="citizenship" name="citizenship"  value="<?php echo $citizenship2;?>" required>
                    <datalist id="lists">
                      <option selected value="Filipino">Filipino</option>
                      <option value="Afghanistan">Afghanistan</option>
                      <option value="Åland Islands">Åland Islands</option>
                      <option value="Albania">Albania</option>
                      <option value="Algeria">Algeria</option>
                      <option value="American Samoa">American Samoa</option>
                      <option value="Andorra">Andorra</option>
                      <option value="Angola">Angola</option>
                      <option value="Anguilla">Anguilla</option>
                      <option value="Antarctica">Antarctica</option>
                      <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                      <option value="Argentina">Argentina</option>
                      <option value="Armenia">Armenia</option>
                      <option value="Aruba">Aruba</option>
                      <option value="Australia">Australia</option>
                      <option value="Austria">Austria</option>
                      <option value="Azerbaijan">Azerbaijan</option>
                      <option value="Bahamas">Bahamas</option>
                      <option value="Bahrain">Bahrain</option>
                      <option value="Bangladesh">Bangladesh</option>
                      <option value="Barbados">Barbados</option>
                      <option value="Belarus">Belarus</option>
                      <option value="Belgium">Belgium</option>
                      <option value="Belize">Belize</option>
                      <option value="Benin">Benin</option>
                      <option value="Bermuda">Bermuda</option>
                      <option value="Bhutan">Bhutan</option>
                      <option value="Bolivia">Bolivia</option>
                      <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                      <option value="Botswana">Botswana</option>
                      <option value="Bouvet Island">Bouvet Island</option>
                      <option value="Brazil">Brazil</option>
                      <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                      <option value="Brunei Darussalam">Brunei Darussalam</option>
                      <option value="Bulgaria">Bulgaria</option>
                      <option value="Burkina Faso">Burkina Faso</option>
                      <option value="Burundi">Burundi</option>
                      <option value="Cambodia">Cambodia</option>
                      <option value="Cameroon">Cameroon</option>
                      <option value="Canada">Canada</option>
                      <option value="Cape Verde">Cape Verde</option>
                      <option value="Cayman Islands">Cayman Islands</option>
                      <option value="Central African Republic">Central African Republic</option>
                      <option value="Chad">Chad</option>
                      <option value="Chile">Chile</option>
                      <option value="China">China</option>
                      <option value="Christmas Island">Christmas Island</option>
                      <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                      <option value="Colombia">Colombia</option>
                      <option value="Comoros">Comoros</option>
                      <option value="Congo">Congo</option>
                      <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                      <option value="Cook Islands">Cook Islands</option>
                      <option value="Costa Rica">Costa Rica</option>
                      <option value="Cote D'ivoire">Cote D'ivoire</option>
                      <option value="Croatia">Croatia</option>
                      <option value="Cuba">Cuba</option>
                      <option value="Cyprus">Cyprus</option>
                      <option value="Czech Republic">Czech Republic</option>
                      <option value="Denmark">Denmark</option>
                      <option value="Djibouti">Djibouti</option>
                      <option value="Dominica">Dominica</option>
                      <option value="Dominican Republic">Dominican Republic</option>
                      <option value="Ecuador">Ecuador</option>
                      <option value="Egypt">Egypt</option>
                      <option value="El Salvador">El Salvador</option>
                      <option value="Equatorial Guinea">Equatorial Guinea</option>
                      <option value="Eritrea">Eritrea</option>
                      <option value="Estonia">Estonia</option>
                      <option value="Ethiopia">Ethiopia</option>
                      <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                      <option value="Faroe Islands">Faroe Islands</option>
                      <option value="Fiji">Fiji</option>
                      <option value="Finland">Finland</option>
                      <option value="France">France</option>
                      <option value="French Guiana">French Guiana</option>
                      <option value="French Polynesia">French Polynesia</option>
                      <option value="French Southern Territories">French Southern Territories</option>
                      <option value="Gabon">Gabon</option>
                      <option value="Gambia">Gambia</option>
                      <option value="Georgia">Georgia</option>
                      <option value="Germany">Germany</option>
                      <option value="Ghana">Ghana</option>
                      <option value="Gibraltar">Gibraltar</option>
                      <option value="Greece">Greece</option>
                      <option value="Greenland">Greenland</option>
                      <option value="Grenada">Grenada</option>
                      <option value="Guadeloupe">Guadeloupe</option>
                      <option value="Guam">Guam</option>
                      <option value="Guatemala">Guatemala</option>
                      <option value="Guernsey">Guernsey</option>
                      <option value="Guinea">Guinea</option>
                      <option value="Guinea-bissau">Guinea-bissau</option>
                      <option value="Guyana">Guyana</option>
                      <option value="Haiti">Haiti</option>
                      <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                      <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                      <option value="Honduras">Honduras</option>
                      <option value="Hong Kong">Hong Kong</option>
                      <option value="Hungary">Hungary</option>
                      <option value="Iceland">Iceland</option>
                      <option value="India">India</option>
                      <option value="Indonesia">Indonesia</option>
                      <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                      <option value="Iraq">Iraq</option>
                      <option value="Ireland">Ireland</option>
                      <option value="Isle of Man">Isle of Man</option>
                      <option value="Israel">Israel</option>
                      <option value="Italy">Italy</option>
                      <option value="Jamaica">Jamaica</option>
                      <option value="Japan">Japan</option>
                      <option value="Jersey">Jersey</option>
                      <option value="Jordan">Jordan</option>
                      <option value="Kazakhstan">Kazakhstan</option>
                      <option value="Kenya">Kenya</option>
                      <option value="Kiribati">Kiribati</option>
                      <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                      <option value="Korea, Republic of">Korea, Republic of</option>
                      <option value="Kuwait">Kuwait</option>
                      <option value="Kyrgyzstan">Kyrgyzstan</option>
                      <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                      <option value="Latvia">Latvia</option>
                      <option value="Lebanon">Lebanon</option>
                      <option value="Lesotho">Lesotho</option>
                      <option value="Liberia">Liberia</option>
                      <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                      <option value="Liechtenstein">Liechtenstein</option>
                      <option value="Lithuania">Lithuania</option>
                      <option value="Luxembourg">Luxembourg</option>
                      <option value="Macao">Macao</option>
                      <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                      <option value="Madagascar">Madagascar</option>
                      <option value="Malawi">Malawi</option>
                      <option value="Malaysia">Malaysia</option>
                      <option value="Maldives">Maldives</option>
                      <option value="Mali">Mali</option>
                      <option value="Malta">Malta</option>
                      <option value="Marshall Islands">Marshall Islands</option>
                      <option value="Martinique">Martinique</option>
                      <option value="Mauritania">Mauritania</option>
                      <option value="Mauritius">Mauritius</option>
                      <option value="Mayotte">Mayotte</option>
                      <option value="Mexico">Mexico</option>
                      <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                      <option value="Moldova, Republic of">Moldova, Republic of</option>
                      <option value="Monaco">Monaco</option>
                      <option value="Mongolia">Mongolia</option>
                      <option value="Montenegro">Montenegro</option>
                      <option value="Montserrat">Montserrat</option>
                      <option value="Morocco">Morocco</option>
                      <option value="Mozambique">Mozambique</option>
                      <option value="Myanmar">Myanmar</option>
                      <option value="Namibia">Namibia</option>
                      <option value="Nauru">Nauru</option>
                      <option value="Nepal">Nepal</option>
                      <option value="Netherlands">Netherlands</option>
                      <option value="Netherlands Antilles">Netherlands Antilles</option>
                      <option value="New Caledonia">New Caledonia</option>
                      <option value="New Zealand">New Zealand</option>
                      <option value="Nicaragua">Nicaragua</option>
                      <option value="Niger">Niger</option>
                      <option value="Nigeria">Nigeria</option>
                      <option value="Niue">Niue</option>
                      <option value="Norfolk Island">Norfolk Island</option>
                      <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                      <option value="Norway">Norway</option>
                      <option value="Oman">Oman</option>
                      <option value="Pakistan">Pakistan</option>
                      <option value="Palau">Palau</option>
                      <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                      <option value="Panama">Panama</option>
                      <option value="Papua New Guinea">Papua New Guinea</option>
                      <option value="Paraguay">Paraguay</option>
                      <option value="Peru">Peru</option>
                      <option value="Pitcairn">Pitcairn</option>
                      <option value="Poland" selected>Poland</option>
                      <option value="Portugal">Portugal</option>
                      <option value="Puerto Rico">Puerto Rico</option>
                      <option value="Qatar">Qatar</option>
                      <option value="Reunion">Reunion</option>
                      <option value="Romania">Romania</option>
                      <option value="Russian Federation">Russian Federation</option>
                      <option value="Rwanda">Rwanda</option>
                      <option value="Saint Helena">Saint Helena</option>
                      <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                      <option value="Saint Lucia">Saint Lucia</option>
                      <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                      <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                      <option value="Samoa">Samoa</option>
                      <option value="San Marino">San Marino</option>
                      <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                      <option value="Saudi Arabia">Saudi Arabia</option>
                      <option value="Senegal">Senegal</option>
                      <option value="Serbia">Serbia</option>
                      <option value="Seychelles">Seychelles</option>
                      <option value="Sierra Leone">Sierra Leone</option>
                      <option value="Singapore">Singapore</option>
                      <option value="Slovakia">Slovakia</option>
                      <option value="Slovenia">Slovenia</option>
                      <option value="Solomon Islands">Solomon Islands</option>
                      <option value="Somalia">Somalia</option>
                      <option value="South Africa">South Africa</option>
                      <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                      <option value="Spain">Spain</option>
                      <option value="Sri Lanka">Sri Lanka</option>
                      <option value="Sudan">Sudan</option>
                      <option value="Suriname">Suriname</option>
                      <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                      <option value="Swaziland">Swaziland</option>
                      <option value="Sweden">Sweden</option>
                      <option value="Switzerland">Switzerland</option>
                      <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                      <option value="Taiwan">Taiwan</option>
                      <option value="Tajikistan">Tajikistan</option>
                      <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                      <option value="Thailand">Thailand</option>
                      <option value="Timor-leste">Timor-leste</option>
                      <option value="Togo">Togo</option>
                      <option value="Tokelau">Tokelau</option>
                      <option value="Tonga">Tonga</option>
                      <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                      <option value="Tunisia">Tunisia</option>
                      <option value="Turkey">Turkey</option>
                      <option value="Turkmenistan">Turkmenistan</option>
                      <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                      <option value="Tuvalu">Tuvalu</option>
                      <option value="Uganda">Uganda</option>
                      <option value="Ukraine">Ukraine</option>
                      <option value="United Arab Emirates">United Arab Emirates</option>
                      <option value="United Kingdom">United Kingdom</option>
                      <option value="United States">United States</option>
                      <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                      <option value="Uruguay">Uruguay</option>
                      <option value="Uzbekistan">Uzbekistan</option>
                      <option value="Vanuatu">Vanuatu</option>
                      <option value="Venezuela">Venezuela</option>
                      <option value="Viet Nam">Viet Nam</option>
                      <option value="Virgin Islands, British">Virgin Islands, British</option>
                      <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                      <option value="Wallis and Futuna">Wallis and Futuna</option>
                      <option value="Western Sahara">Western Sahara</option>
                      <option value="Yemen">Yemen</option>
                      <option value="Zambia">Zambia</option>
                      <option value="Zimbabwe">Zimbabwe</option>
                    </datalist>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-4">
                    <label for="civil" class="form-label">Civil Status</label>
                    <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                      <?php
                        if($civil2 == 'Single'){
                          echo '<option selected value="Single">Single</option>
                          <option value="Married">Married</option>
                          <option value="Divorced">Divorced</option>
                          <option value="Widowed">Widowed</option>';
                        }
                        elseif($civil2 == 'Married'){
                          echo '<option value="Single">Single</option>
                          <option selected value="Married">Married</option>
                          <option value="Divorced">Divorced</option>
                          <option value="Widowed">Widowed</option>';
                        }
                        elseif($civil2 == 'Divorced'){
                          echo '<option value="Single">Single</option>
                          <option value="Married">Married</option>
                          <option selected value="Divorced">Divorced</option>
                          <option value="Widowed">Widowed</option>';
                        }
                        else{
                          echo '<option value="Single">Single</option>
                          <option value="Married">Married</option>
                          <option value="Divorced">Divorced</option>
                          <option selected value="Widowed">Widowed</option>';
                        }
                      ?>
                    </select>
                  </div>
                  <div class="col-md-8">
                    <label for="religion" class="form-label">Religion</label>
                    <input class="form-control" list="lists2" id="religion" name="religion" value="<?php echo $religion2;?>" required>
                    <datalist id="lists2">
                      <option value="Roman Catholic">Roman Catholic</option>
                      <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                      <option value="Jehova's Witnesses">Jehova's Witnesses</option>
                      <option value="Born Again">Born Again</option>
                    </datalist>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                    <div class="input-group mb-3">
                      <span class="input-group-text">+63</span>
                      <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" value="<?php echo $contact2;?>" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" value="<?php echo $email2;?>" class="form-control" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="occupation" class="form-label">Occupation</label>
                    <input type="text" class="form-control" name="occupation" id="occupation" value="<?php echo $occupation2;?>" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="nature" class="form-label">Nature of work</label>
                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" value="<?php echo $nature;?>" required>
                    <?php 
                        if($nature2 == 'None'){
                          echo '<option selected value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Architecture and Engineering'){
                          echo '<option value="None">None</option>
                          <option selected value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Arts, Culture and Entertainment'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option selected value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Business, Management and Administration'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option selected value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Communications'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option selected value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Community and Social Services'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option selected value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Education'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option selected value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Science and Technology'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option selected value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Installation, Repair and Maintenance'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option selected value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Farming, Fishing and Forestry'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option selected value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Government'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option selected value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Health and Medicine'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option selected value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        elseif($nature2 == 'Law and Public Policy'){
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option selected value="Law and Public Policy">Law and Public Policy</option>
                          <option value="Sales">Sales</option>';
                        }
                        else{
                          echo '<option value="None">None</option>
                          <option value="Architecture and Engineering">Architecture and Engineering</option>
                          <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                          <option value="Business, Management and Administration">Business, Management and Administration</option>
                          <option value="Communications">Communications</option>
                          <option value="Community and Social Services">Community and Social Services</option>
                          <option value="Education">Education</option>
                          <option value="Science and Technology">Science and Technology</option>
                          <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                          <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                          <option value="Government">Government</option>
                          <option value="Health and Medicine">Health and Medicine</option>
                          <option value="Law and Public Policy">Law and Public Policy</option>
                          <option selected value="Sales">Sales</option>';
                        }
                      ?>
                    </select>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="updateres" value="<?=$resident_id2?>">Update</button>
              <button type="submit" class="btn btn-secondary" bs-target-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- edit pop-up modal end -->

      <!-- updating confirmation pop-up modal -->
      <div class="modal fade" id="updateconfmodal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Update Resident</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Resident has been successfully updated.</p>
            </div>
            <form action="individuals.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success"  name="resmodopen" value="<?=$resident_id3;?>">Ok</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- updating confirmation pop-up end modal -->

      <!-- delete confirmation pop-up modal -->
      <div class="modal fade" id="deletemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Delete Resident</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to delete this resident?</p>
            </div>
            <form action="individuals.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="deleteconf" value="<?=$resident_id2?>">Yes</button>
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#detailres" data-bs-toggle="modal" data-bs-dismiss="modal">No</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- delete confirmation pop-up end modal -->

      <!-- confirmatiom of deleting resident pop-up modal -->
      <div class="modal fade" id="deleteconfmod" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                  <h5 class="modal-title">Restoring Resident</h5>
              </div>
              <div class="modal-body">
                  <p class="text-center fs-4">Resident has been successfully deleted.</p>
              </div>
              <form action="individuals.php" method="post">
              <div class="modal-footer">
                  <button type="submit" class="btn btn-success" name="confirmresident">Ok</button>
              </div>
              </form>
          </div>
        </div>
      </div>
      <!-- confirmatiom of deleting resident pop-up modal end -->

      <!-- pending requests pop-up modal -->
      <div class="modal fade" id="requestresident" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="requestresident">Pending Residents</h5>
            </div>
            <div class="modal-body">
                <?php
                  $sql9 = "SELECT * FROM penresident WHERE status = 'Pending'";
                  $d9 =  $con->query($sql9);
                  foreach($d9 as $data9){
                    $date = date_create($data9['last_session']);
                    $date = date_format($date, 'F d, Y h:i:s a');
                ?>
                <div class="row border shadow-lg p-3 ms-1 me-1 mb-3 bg-body rounded">
                  <div class="col-md-12">
                    <p class="text-start fs-4 fw-bold"><?=$data9['lastname'];?>, <?=$data9['firstname'];?> <?php echo substr($data9['middlename'], 0, 1);?>.</p>
                    <p class="text-start fs-5">Requesting to register as a resident.</p>
                    <p class="text-start fs-5"><?=$date;?></p>
                    <form action="individuals.php" method="post">
                    <p class="text-end fs-5"><button type="submit" class="btn btn-link" name="pendingresopen" value="<?=$data9['resident_id'];?>">View</button></p>
                    </form>
                  </div>
                </div>
                <?php
                  }
                ?>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
          </div>
        </div>
      </div>
      <!-- pending requests pop-up modal end -->

      <!-- veiwing pending resident request pop-up modal -->
      <div class="modal fade" id="viewingpending" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Pending Resident Information</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <?php
              if($age3 < 18){
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="firstname" class="form-label">Resident Classification</label>
                          <p id="firstname" class="text-start fs-3">Minor</p>
                        </div>
                      </div>';
              }
              elseif($age3 > 59){
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="firstname" class="form-label">Resident Classification</label>
                          <p id="firstname" class="text-start fs-3">Senior</p>
                        </div>
                      </div>';
              }
              else{
                echo '<div class="row">
                        <div class="col-md-12">
                          <label for="firstname" class="form-label">Resident Classification</label>
                          <p id="firstname" class="text-start fs-3">Adult</p>
                        </div>
                      </div>';
              }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $firstname;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3"><?php echo $middlename;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3"><?php echo $lastname;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="gender" class="text-start fs-3"><?php echo $gender;?></p>
                </div>
                <div class="col-md-3">
                  <label for="Age" class="form-label">Age</label>
                  <p id="Age" class="text-start fs-3"><?php echo $age3?></p>
                </div>
                <div class="col-md-5">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <p id="birthdate" class="text-start fs-3"><?php echo $birthdate;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3"><?php echo $birthplace;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="citizenship" class="form-label">Citizenship</label>
                    <p id="citizenship" class="text-start fs-3"><?php echo $citizenship;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="civil" class="text-start fs-3"><?php echo $civil;?></p>
                </div>
                <div class="col-md-8">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="religion" class="text-start fs-3"><?php echo $religion;?></p>
                </div>
              </div>
              <?php
              if($age3 < 17){
                echo '<div class="row">
                <div class="col-md-12">
                  <label for="resident_id" class="form-label">Guardian</label>
                  <p id="resident_id" class="text-start fs-3">'.$fhead.'</p>
                </div>
              </div>';
              }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <?php
                  if($age3 < 17){
                    echo '<label for="contact" class="form-label">Guradian\'s Contact #</label>';
                  }
                  else{
                    echo '<label for="contact" class="form-label">Contact #</label>';
                  }
                  ?>
                  <p id="contact" class="text-start fs-3">0<?php echo $contact;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <?php
                  if($age3 < 17){
                    echo '<label for="typeemail" class="form-label">Guardian\'s Email Address</label>';
                  }
                  else{
                    echo '<label for="typeemail" class="form-label">Email Address</label>';
                  }
                  ?>
                  <p id="email" class="text-start fs-3"><?php echo $email;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3"><?php echo $occupation;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3"><?php echo $nature;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="old" class="form-label">Old House #</label>
                  <p id="old" class="text-start fs-3"><?php echo $old;?></p>
                </div>
                <div class="col-md-6">
                  <label for="new" class="form-label">New House #</label>
                  <p id="new" class="text-start fs-3"><?php echo $new;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="street" class="form-label">Street</label>
                  <p id="street" class="text-start fs-3"><?php echo $street;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    <label for="village" class="form-label">Village/Subdivision</label>
                    <p id="village" class="text-start fs-3"><?php echo $village;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="housetype" class="form-label">Household Type</label>
                  <p id="housetype" class="text-start fs-3"><?php echo $housetype;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="family_position" class="form-label">Family Position</label>
                  <p id="family_position" class="text-start fs-3">Member</p>
                </div>
                <div class="col-md-6">
                  <label for="family_role" class="form-label">Family Role</label>
                  <p id="family_role" class="text-start fs-3"><?php echo $family_role;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="resident_id" class="form-label">Household Code</label>
                  <p id="resident_id" class="text-start fs-3"><?php echo $resident_code;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <?php
                  if($age3 < 13){
                    echo '<label for="family_position" class="form-label">Birth Certificate Photo</label>';
                  }
                  else{
                    echo '<label for="family_position" class="form-label">Verification ID</label>';
                  }
                  ?>
                  <p id="family_position" class="text-center fs-3"><img src="../resident/images/<?=$verification_id?>" class="img-fluid" alt="..."></p>
                </div>
              </div>
              <input type="hidden" name="verification_id" value="<?=$verification_id?>">
              <input type="hidden" name="resident_id" value="<?=$resident_id?>">
              <input type="hidden" name="birthdate" value="<?=$birthdate?>">
              <input type="hidden" name="email" value="<?=$email?>">
              <input type="hidden" name="lastname" value="<?=$lastname?>">
              <input type="hidden" name="firstname" value="<?=$firstname?>">
              <input type="hidden" name="middlename" value="<?=$middlename?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="approvepending">Approve</button>
              <button type="submit" class="btn btn-danger" name="denypending">Deny</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- veiwing pending resident request pop-up modal end -->

      <!-- cofirmation of veiwing pending resident request pop-up modal -->
      <div class="modal fade" id="confviewingpending" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Pending Request</h5>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <input type="hidden" name="resident_id" value="<?=$resident_id?>">
              <input type="hidden" name="verification_id" value="<?=$verification_id?>">
              <input type="hidden" name="email" value="<?=$email?>">
              <input type="hidden" name="prevpassword" value="<?=$pass?>">
              <?php
              if($checker2 == 0){
                echo '<p class="text-center fs-4">Are you sure to deny this request?</p>
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" name="confdenypending">Yes</button>
                  <button type="submit" class="btn btn-danger" name="pendingresopen"">No</button>';
              }
              else{
                echo '<p class="text-center fs-4">Are you sure to approve this request?</p>
                <input type="hidden" name="username" value="'.$username.'">
                <input type="hidden" name="password" value="'.$epass.'">
                </div>
                <div class="modal-footer">
                  <button type="submit" class="btn btn-success" name="confapprovepending">Yes</button>
                  <button type="submit" class="btn btn-danger" name="pendingresopen"">No</button>';
              }
              ?>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- cofirmation of veiwing pending resident request pop-up modal end -->

      <!-- confirmatiom of deleting resident pop-up modal -->
      <div class="modal fade" id="denyreasonmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header p-3 mb-2 bg-secondary text-white">
                  <h5 class="modal-title">Denying Request</h5>
              </div>
              <div class="modal-body">
              <form action="individuals.php" method="post">
                <input type="hidden" name="resident_id" value="<?=$resident_id?>">
                <input type="hidden" name="verification_id" value="<?=$verification_id?>">
                <input type="hidden" name="email" value="<?=$email?>">
                <div class="row-3">
                  <div class="col-md-12">
                    <div class="form-group green-border-focus" id="anndetails">
                      <label for="anndetails">Reason</label>
                      <textarea class="form-control" id="anndetails" rows="5" name="reason" required></textarea>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="submit" class="btn btn-success" name="denyreason">Send</button>
              </div>
              </form>
          </div>
        </div>
      </div>
      <!-- confirmatiom of deleting resident pop-up modal end -->

      <!-- notification of pending resident pop-up modal -->
      <div class="modal fade" id="noticonfviewingpending" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Pending Resident</h5>
            </div>
            <div class="modal-body">
              <?php
              if($notif == 0){
                echo '<p class="text-center fs-4">Pending request has been approved.</p>';
              }
              else{
                echo '<p class="text-center fs-4">Pending request has been denied.</p>';
              }
              ?>
              
            </div>
            <form action="individuals.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- notification of pending resident pop-up modal end -->

      <!-- choosing type pop-up modal -->
      <div class="modal fade" id="choosetype" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="staticBackdropLabel">Adding Resident</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-target="#addmodal" data-bs-toggle="modal" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <div class="row justify-content-center">
                <div class="col-md-6">
                  <img src="../resident/images/adult.jpg" class="img-thumbnail rounded mx-auto d-block" alt="...">
                  <p class="text-center"><button type="submit" class="btn btn-primary" name="chooseopen" value="1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Personal Account&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</button></p>
                </div>
                <div class="col-md-6">
                  <img src="../resident/images/minor.jpg" class="img-thumbnail rounded mx-auto d-block" alt="...">
                  <p class="text-center"><button type="submit" class="btn btn-primary" name="chooseopen" value="2">Family Member(Minor)</button></p>
                </div>
              </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- choosing type pop-up modal -->

      <!-- entering code pop-up modal -->
      <div class="modal fade" id="censusmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <?php
              if($type == 1){
                echo'<h5 class="modal-title" id="staticBackdropLabel">Adding Personal Account</h5>';
              }
              else{
                echo'<h5 class="modal-title" id="staticBackdropLabel">Adding Minor Account Account</h5>';
              }
              ?> 
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <?php
                  if(isset($errorMSG)){
                    echo '<div class="alert alert-warning" role="alert">
                            '.$errorMSG.'
                          </div>';
                  }
                  ?>
                </div>
              </div>
              <form action="individuals.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="hcode" class="form-label">Household Code (Provided by the Household Leader)</label>
                  <input type="text" class="form-control" id="hcode" name="hcode" placeholder="Enter valid household code..." required>
                </div>
              </div>
              <input type="hidden" name="type" value="<?=$type?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="checkcode">Submit</button>
              <a href="individuals.php"><button type="button" class="btn btn-secondary" name="confirmresident">Cancel</button></a>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- entering code pop-up modal -->

      <!-- adding individual pop-up modal 2 -->
      <div class="modal fade" id="addindividual" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Census Form</h5>
            </div>
            <div class="modal-body">
              <form action="individuals.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="resident_code" value="<?=$resident_code?>">
                <input type="hidden" name="birthdateguar" value="<?=$birthdateguar?>">
                <input type="hidden" name="checkmodal3" value="<?=$checkmodal3?>">
                <?php
                  if(isset($errorMSG)){
                    echo '<div class="alert alert-warning" role="alert">
                            '.$errorMSG.'
                          </div>';
                  }
                  if(isset($errorMSG2)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG2.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG3)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG3.
                    "</div>
                  </div>";
                  }
                  if(isset($errorMSG4)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                      .$errorMSG4.
                    "</div>
                  </div>";
                  }

                  if($checkmodal1 == 1){
                    echo '<input type="hidden" name="resident_code" value="'.$resident_code.'">
                          <div class="row">
                            <div class="col-md-12">
                              <label for="firstname" class="form-label">Firstname</label>
                              <input type="text" class="form-control text-start" name="firstname" id="firstname" value="'.$firstname.'" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="middlename" class="form-label">Middlename</label>
                              <input type="text" class="form-control" name="middlename" id="middlename" value="'.$middlename.'" required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="lastname" class="form-label">Lastname</label>
                              <input type="text" class="form-control" name="lastname" id="lastname" value="'.$lastname.'" required>
                            </div>
                          </div>
                          <div class="row">';
                    if($gender == 'Male'){
                      echo '<div class="col-md-6">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                                <option >Select</option>
                                <option value="Male" selected>Male</option>
                                <option value="Female">Female</option>
                              </select>
                            </div>';
                    }
                    else{
                      echo '<div class="col-md-6">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                                <option >Select</option>
                                <option value="Male">Male</option>
                                <option value="Female" selected>Female</option>
                              </select>
                            </div>';
                    }
                    if($checkmodal4 == 1){
                      if($checkmodal3 == 1){
                        echo '<div class="col-md-6">
                                  <label for="birthdate" class="form-label">Birthdate</label>
                                  <input type="date" class="form-select" id="birthdate" name="birthdate" min="'.$minordate.'" max="'.$date.'" value="'.$birthdate.'" required>
                                </div>
                              </div>';
                      }
                      else{
                        echo '<div class="col-md-6">
                                  <label for="birthdate" class="form-label">Birthdate</label>
                                  <input type="date" class="form-select" id="birthdate" name="birthdate" min="1880-01-01" max="'.$date.'" value="'.$birthdate.'" required>
                                </div>
                              </div>';
                      }
                    }
                    else{
                      echo '<div class="col-md-6">
                              <label for="birthdate" class="form-label">Birthdate</label>
                              <input type="date" class="form-select" id="birthdate" name="birthdate" min="1890-01-01" max="'.$date.'" value="'.$birthdate.'" required>
                            </div>
                          </div>';
                    }   
                    echo '<div class="row">
                          <div class="col-md-12">
                            <label for="birthplace" class="form-label">Birthplace</label>
                            <input type="text" class="form-control" name="birthplace" id="birthplace" value="'.$birthplace.'" required>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-md-12">
                            <label for="citizenship" class="form-label">Citizenship</label>
                            <input class="form-control" list="lists" id="citizenship" name="citizenship" value="'.$citizenship.'" required>
                            <datalist id="lists">
                              <option selected value="Filipino">Filipino</option>
                              <option value="Afghanistan">Afghanistan</option>
                              <option value="Åland Islands">Åland Islands</option>
                              <option value="Albania">Albania</option>
                              <option value="Algeria">Algeria</option>
                              <option value="American Samoa">American Samoa</option>
                              <option value="Andorra">Andorra</option>
                              <option value="Angola">Angola</option>
                              <option value="Anguilla">Anguilla</option>
                              <option value="Antarctica">Antarctica</option>
                              <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                              <option value="Argentina">Argentina</option>
                              <option value="Armenia">Armenia</option>
                              <option value="Aruba">Aruba</option>
                              <option value="Australia">Australia</option>
                              <option value="Austria">Austria</option>
                              <option value="Azerbaijan">Azerbaijan</option>
                              <option value="Bahamas">Bahamas</option>
                              <option value="Bahrain">Bahrain</option>
                              <option value="Bangladesh">Bangladesh</option>
                              <option value="Barbados">Barbados</option>
                              <option value="Belarus">Belarus</option>
                              <option value="Belgium">Belgium</option>
                              <option value="Belize">Belize</option>
                              <option value="Benin">Benin</option>
                              <option value="Bermuda">Bermuda</option>
                              <option value="Bhutan">Bhutan</option>
                              <option value="Bolivia">Bolivia</option>
                              <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                              <option value="Botswana">Botswana</option>
                              <option value="Bouvet Island">Bouvet Island</option>
                              <option value="Brazil">Brazil</option>
                              <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                              <option value="Brunei Darussalam">Brunei Darussalam</option>
                              <option value="Bulgaria">Bulgaria</option>
                              <option value="Burkina Faso">Burkina Faso</option>
                              <option value="Burundi">Burundi</option>
                              <option value="Cambodia">Cambodia</option>
                              <option value="Cameroon">Cameroon</option>
                              <option value="Canada">Canada</option>
                              <option value="Cape Verde">Cape Verde</option>
                              <option value="Cayman Islands">Cayman Islands</option>
                              <option value="Central African Republic">Central African Republic</option>
                              <option value="Chad">Chad</option>
                              <option value="Chile">Chile</option>
                              <option value="China">China</option>
                              <option value="Christmas Island">Christmas Island</option>
                              <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                              <option value="Colombia">Colombia</option>
                              <option value="Comoros">Comoros</option>
                              <option value="Congo">Congo</option>
                              <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                              <option value="Cook Islands">Cook Islands</option>
                              <option value="Costa Rica">Costa Rica</option>
                              <option value="Cote D\'ivoire">Cote D\'ivoire</option>
                              <option value="Croatia">Croatia</option>
                              <option value="Cuba">Cuba</option>
                              <option value="Cyprus">Cyprus</option>
                              <option value="Czech Republic">Czech Republic</option>
                              <option value="Denmark">Denmark</option>
                              <option value="Djibouti">Djibouti</option>
                              <option value="Dominica">Dominica</option>
                              <option value="Dominican Republic">Dominican Republic</option>
                              <option value="Ecuador">Ecuador</option>
                              <option value="Egypt">Egypt</option>
                              <option value="El Salvador">El Salvador</option>
                              <option value="Equatorial Guinea">Equatorial Guinea</option>
                              <option value="Eritrea">Eritrea</option>
                              <option value="Estonia">Estonia</option>
                              <option value="Ethiopia">Ethiopia</option>
                              <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                              <option value="Faroe Islands">Faroe Islands</option>
                              <option value="Fiji">Fiji</option>
                              <option value="Finland">Finland</option>
                              <option value="France">France</option>
                              <option value="French Guiana">French Guiana</option>
                              <option value="French Polynesia">French Polynesia</option>
                              <option value="French Southern Territories">French Southern Territories</option>
                              <option value="Gabon">Gabon</option>
                              <option value="Gambia">Gambia</option>
                              <option value="Georgia">Georgia</option>
                              <option value="Germany">Germany</option>
                              <option value="Ghana">Ghana</option>
                              <option value="Gibraltar">Gibraltar</option>
                              <option value="Greece">Greece</option>
                              <option value="Greenland">Greenland</option>
                              <option value="Grenada">Grenada</option>
                              <option value="Guadeloupe">Guadeloupe</option>
                              <option value="Guam">Guam</option>
                              <option value="Guatemala">Guatemala</option>
                              <option value="Guernsey">Guernsey</option>
                              <option value="Guinea">Guinea</option>
                              <option value="Guinea-bissau">Guinea-bissau</option>
                              <option value="Guyana">Guyana</option>
                              <option value="Haiti">Haiti</option>
                              <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                              <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                              <option value="Honduras">Honduras</option>
                              <option value="Hong Kong">Hong Kong</option>
                              <option value="Hungary">Hungary</option>
                              <option value="Iceland">Iceland</option>
                              <option value="India">India</option>
                              <option value="Indonesia">Indonesia</option>
                              <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                              <option value="Iraq">Iraq</option>
                              <option value="Ireland">Ireland</option>
                              <option value="Isle of Man">Isle of Man</option>
                              <option value="Israel">Israel</option>
                              <option value="Italy">Italy</option>
                              <option value="Jamaica">Jamaica</option>
                              <option value="Japan">Japan</option>
                              <option value="Jersey">Jersey</option>
                              <option value="Jordan">Jordan</option>
                              <option value="Kazakhstan">Kazakhstan</option>
                              <option value="Kenya">Kenya</option>
                              <option value="Kiribati">Kiribati</option>
                              <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
                              <option value="Korea, Republic of">Korea, Republic of</option>
                              <option value="Kuwait">Kuwait</option>
                              <option value="Kyrgyzstan">Kyrgyzstan</option>
                              <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>
                              <option value="Latvia">Latvia</option>
                              <option value="Lebanon">Lebanon</option>
                              <option value="Lesotho">Lesotho</option>
                              <option value="Liberia">Liberia</option>
                              <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                              <option value="Liechtenstein">Liechtenstein</option>
                              <option value="Lithuania">Lithuania</option>
                              <option value="Luxembourg">Luxembourg</option>
                              <option value="Macao">Macao</option>
                              <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                              <option value="Madagascar">Madagascar</option>
                              <option value="Malawi">Malawi</option>
                              <option value="Malaysia">Malaysia</option>
                              <option value="Maldives">Maldives</option>
                              <option value="Mali">Mali</option>
                              <option value="Malta">Malta</option>
                              <option value="Marshall Islands">Marshall Islands</option>
                              <option value="Martinique">Martinique</option>
                              <option value="Mauritania">Mauritania</option>
                              <option value="Mauritius">Mauritius</option>
                              <option value="Mayotte">Mayotte</option>
                              <option value="Mexico">Mexico</option>
                              <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                              <option value="Moldova, Republic of">Moldova, Republic of</option>
                              <option value="Monaco">Monaco</option>
                              <option value="Mongolia">Mongolia</option>
                              <option value="Montenegro">Montenegro</option>
                              <option value="Montserrat">Montserrat</option>
                              <option value="Morocco">Morocco</option>
                              <option value="Mozambique">Mozambique</option>
                              <option value="Myanmar">Myanmar</option>
                              <option value="Namibia">Namibia</option>
                              <option value="Nauru">Nauru</option>
                              <option value="Nepal">Nepal</option>
                              <option value="Netherlands">Netherlands</option>
                              <option value="Netherlands Antilles">Netherlands Antilles</option>
                              <option value="New Caledonia">New Caledonia</option>
                              <option value="New Zealand">New Zealand</option>
                              <option value="Nicaragua">Nicaragua</option>
                              <option value="Niger">Niger</option>
                              <option value="Nigeria">Nigeria</option>
                              <option value="Niue">Niue</option>
                              <option value="Norfolk Island">Norfolk Island</option>
                              <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                              <option value="Norway">Norway</option>
                              <option value="Oman">Oman</option>
                              <option value="Pakistan">Pakistan</option>
                              <option value="Palau">Palau</option>
                              <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                              <option value="Panama">Panama</option>
                              <option value="Papua New Guinea">Papua New Guinea</option>
                              <option value="Paraguay">Paraguay</option>
                              <option value="Peru">Peru</option>
                              <option value="Pitcairn">Pitcairn</option>
                              <option value="Poland" selected>Poland</option>
                              <option value="Portugal">Portugal</option>
                              <option value="Puerto Rico">Puerto Rico</option>
                              <option value="Qatar">Qatar</option>
                              <option value="Reunion">Reunion</option>
                              <option value="Romania">Romania</option>
                              <option value="Russian Federation">Russian Federation</option>
                              <option value="Rwanda">Rwanda</option>
                              <option value="Saint Helena">Saint Helena</option>
                              <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                              <option value="Saint Lucia">Saint Lucia</option>
                              <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                              <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                              <option value="Samoa">Samoa</option>
                              <option value="San Marino">San Marino</option>
                              <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                              <option value="Saudi Arabia">Saudi Arabia</option>
                              <option value="Senegal">Senegal</option>
                              <option value="Serbia">Serbia</option>
                              <option value="Seychelles">Seychelles</option>
                              <option value="Sierra Leone">Sierra Leone</option>
                              <option value="Singapore">Singapore</option>
                              <option value="Slovakia">Slovakia</option>
                              <option value="Slovenia">Slovenia</option>
                              <option value="Solomon Islands">Solomon Islands</option>
                              <option value="Somalia">Somalia</option>
                              <option value="South Africa">South Africa</option>
                              <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                              <option value="Spain">Spain</option>
                              <option value="Sri Lanka">Sri Lanka</option>
                              <option value="Sudan">Sudan</option>
                              <option value="Suriname">Suriname</option>
                              <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                              <option value="Swaziland">Swaziland</option>
                              <option value="Sweden">Sweden</option>
                              <option value="Switzerland">Switzerland</option>
                              <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                              <option value="Taiwan">Taiwan</option>
                              <option value="Tajikistan">Tajikistan</option>
                              <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                              <option value="Thailand">Thailand</option>
                              <option value="Timor-leste">Timor-leste</option>
                              <option value="Togo">Togo</option>
                              <option value="Tokelau">Tokelau</option>
                              <option value="Tonga">Tonga</option>
                              <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                              <option value="Tunisia">Tunisia</option>
                              <option value="Turkey">Turkey</option>
                              <option value="Turkmenistan">Turkmenistan</option>
                              <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                              <option value="Tuvalu">Tuvalu</option>
                              <option value="Uganda">Uganda</option>
                              <option value="Ukraine">Ukraine</option>
                              <option value="United Arab Emirates">United Arab Emirates</option>
                              <option value="United Kingdom">United Kingdom</option>
                              <option value="United States">United States</option>
                              <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                              <option value="Uruguay">Uruguay</option>
                              <option value="Uzbekistan">Uzbekistan</option>
                              <option value="Vanuatu">Vanuatu</option>
                              <option value="Venezuela">Venezuela</option>
                              <option value="Viet Nam">Viet Nam</option>
                              <option value="Virgin Islands, British">Virgin Islands, British</option>
                              <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                              <option value="Wallis and Futuna">Wallis and Futuna</option>
                              <option value="Western Sahara">Western Sahara</option>
                              <option value="Yemen">Yemen</option>
                              <option value="Zambia">Zambia</option>
                              <option value="Zimbabwe">Zimbabwe</option>
                            </datalist>
                          </div>
                        </div>';
                    if($civil == 'Single'){
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single" selected>Single</option>
                                  <option value="Married">Married</option>
                                  <option value="Divorced">Divorced</option>
                                  <option value="Widowed">Widowed</option>
                                </select>
                              </div>';
                    }
                    elseif($civil == 'Married'){
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single">Single</option>
                                  <option value="Married" selected>Married</option>
                                  <option value="Divorced">Divorced</option>
                                  <option value="Widowed">Widowed</option>
                                </select>
                              </div>';
                    }
                    elseif($civil == 'Divorced'){
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single">Single</option>
                                  <option value="Married">Married</option>
                                  <option value="Divorced" selected>Divorced</option>
                                  <option value="Widowed">Widowed</option>
                                </select>
                              </div>';
                    }
                    else{
                      echo '<div class="row">
                              <div class="col-md-4">
                                <label for="civil" class="form-label">Civil Status</label>
                                <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                  <option value="Single">Single</option>
                                  <option value="Married">Married</option>
                                  <option value="Divorced">Divorced</option>
                                  <option value="Widowed" selected>Widowed</option>
                                </select>
                              </div>';
                    }
                    echo '<div class="col-md-8">
                            <label for="religion" class="form-label">Religion</label>
                            <input class="form-control" list="lists3" id="religion" name="religion" value="'.$religion.'" required>
                            <datalist id="lists3">
                              <option value="" hidden>Select</option>
                              <option value="Roman Catholic">Roman Catholic</option>
                              <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                              <option value="Jehova\'s Witnesses">Jehova\'s Witnesses</option>
                              <option value="Born Again">Born Again</option>
                            </datalist>
                          </div>
                        </div>';
                    if($checkmodal4 == 1){
                      if($checkmodal3 == 1){
                        echo '<input type="hidden" id="contact" name="contact"value="'.$contact.'">
                            <input type="hidden" name="email" id="emailrel" value="'.$email.'">
                            <input type="hidden"name="occupation" id="occupation" value="None">
                            <input type="hidden"name="nature" id="nature" value="None">';
                      }
                      else{
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                                  <div class="input-group">
                                    <span class="input-group-text">+63</span>
                                    <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" value="'.$contact.'" required>
                                  </div>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <label for="emailrel" class="form-label">Email Address</label>
                                  <input type="email" class="form-control" name="email" id="emailrel" value="'.$email.'" required>
                                </div>
                              </div>
                              <div class="row">
                                <div class="col-md-12">
                                  <label for="occupation" class="form-label">Occupation</label>
                                  <input type="text" class="form-control" name="occupation" id="occupation" value="'.$occupation.'" required>
                                </div>
                              </div>';
                        if($nature == 'Architecture and Engineering'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering" selected>Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                        elseif($nature == 'Arts, Culture and Entertainment'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment" selected>Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                        elseif($nature == 'Business, Management and Administration'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration" selected>Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                        elseif($nature == 'Communications'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications" selected>Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                        elseif($nature == 'Community and Social Services'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services" selected>Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                        elseif($nature == 'Education'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education" selected>Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Science and Technology'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology" selected>Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Installation, Repair and Maintenance'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance" selected>Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Farming, Fishing and Forestry'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry" selected>Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Government'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government" selected>Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Health and Medicine'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine" selected>Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Law and Public Policy'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy" selected>Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        elseif($nature == 'Sales'){
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales" selected>Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        } 
                        else{
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None" selected>None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                      }
                    }
                    else{
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                                <div class="input-group">
                                  <span class="input-group-text">+63</span>
                                  <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" value="'.$contact.'" required>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <label for="emailrel" class="form-label">Email Address</label>
                                <input type="email" class="form-control" name="email" id="emailrel" value="'.$email.'" required>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-md-12">
                                <label for="occupation" class="form-label">Occupation</label>
                                <input type="text" class="form-control" name="occupation" id="occupation" value="'.$occupation.'" required>
                              </div>
                            </div>';
                      if($nature == 'Architecture and Engineering'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering" selected>Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($nature == 'Arts, Culture and Entertainment'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment" selected>Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($nature == 'Business, Management and Administration'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration" selected>Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($nature == 'Communications'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications" selected>Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($nature == 'Community and Social Services'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services" selected>Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($nature == 'Education'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education" selected>Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Science and Technology'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology" selected>Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Installation, Repair and Maintenance'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance" selected>Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Farming, Fishing and Forestry'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry" selected>Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Government'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government" selected>Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Health and Medicine'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine" selected>Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Law and Public Policy'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy" selected>Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      elseif($nature == 'Sales'){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales" selected>Sales</option>
                                    <option value="None">None</option>
                                  </select>
                                </div>
                              </div>';
                      } 
                      else{
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="nature" class="form-label">Nature of work</label>
                                  <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                    <option value="Architecture and Engineering">Architecture and Engineering</option>
                                    <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                    <option value="Business, Management and Administration">Business, Management and Administration</option>
                                    <option value="Communications">Communications</option>
                                    <option value="Community and Social Services">Community and Social Services</option>
                                    <option value="Education">Education</option>
                                    <option value="Science and Technology">Science and Technology</option>
                                    <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                    <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                    <option value="Government">Government</option>
                                    <option value="Health and Medicine">Health and Medicine</option>
                                    <option value="Law and Public Policy">Law and Public Policy</option>
                                    <option value="Sales">Sales</option>
                                    <option value="None" selected>None</option>
                                  </select>
                                </div>
                              </div>';
                      }
                    }
                  }
                  else{
                    echo '<div class="row">
                            <div class="col-md-12">
                                <label for="firstname" class="form-label">Firstname</label>
                                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="Firstname..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <label for="middlename" class="form-label">Middlename</label>
                                <input type="text" class="form-control" name="middlename" id="middlename" placeholder="Middlename..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                                <label for="lastname" class="form-label">Lastname</label>
                                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="Lastname..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-6">
                              <label for="gender" class="form-label">Gender</label>
                              <select class="form-select" aria-label="Default select example" name="gender" id="gender" required>
                                <option value="" hidden>Select</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                              </select>
                            </div>';
                      if($checkmodal3 == 1){
                        echo '<div class="col-md-6">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-select" id="birthdate" name="birthdate" min="'.$minordate.'" max="'.$date.'" required>
                              </div>';
                      }
                      else{
                        echo '<div class="col-md-6">
                                <label for="birthdate" class="form-label">Birthdate</label>
                                <input type="date" class="form-select" id="birthdate" name="birthdate" min="1890-01-01" max="'.$date.'" required>
                              </div>';
                      }      
                    echo '</div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="birthplace" class="form-label">Birthplace</label>
                              <input type="text" class="form-control" name="birthplace" id="birthplace" placeholder="Birthplace..." required>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-12">
                              <label for="citizenship" class="form-label">Citizenship</label>
                              <input class="form-control" list="lists" id="citizenship" name="citizenship" required>
                              <datalist id="lists">
                                <option selected value="Filipino">Filipino</option>
                                <option value="Afghanistan">Afghanistan</option>
                                <option value="Åland Islands">Åland Islands</option>
                                <option value="Albania">Albania</option>
                                <option value="Algeria">Algeria</option>
                                <option value="American Samoa">American Samoa</option>
                                <option value="Andorra">Andorra</option>
                                <option value="Angola">Angola</option>
                                <option value="Anguilla">Anguilla</option>
                                <option value="Antarctica">Antarctica</option>
                                <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                                <option value="Argentina">Argentina</option>
                                <option value="Armenia">Armenia</option>
                                <option value="Aruba">Aruba</option>
                                <option value="Australia">Australia</option>
                                <option value="Austria">Austria</option>
                                <option value="Azerbaijan">Azerbaijan</option>
                                <option value="Bahamas">Bahamas</option>
                                <option value="Bahrain">Bahrain</option>
                                <option value="Bangladesh">Bangladesh</option>
                                <option value="Barbados">Barbados</option>
                                <option value="Belarus">Belarus</option>
                                <option value="Belgium">Belgium</option>
                                <option value="Belize">Belize</option>
                                <option value="Benin">Benin</option>
                                <option value="Bermuda">Bermuda</option>
                                <option value="Bhutan">Bhutan</option>
                                <option value="Bolivia">Bolivia</option>
                                <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                                <option value="Botswana">Botswana</option>
                                <option value="Bouvet Island">Bouvet Island</option>
                                <option value="Brazil">Brazil</option>
                                <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                                <option value="Brunei Darussalam">Brunei Darussalam</option>
                                <option value="Bulgaria">Bulgaria</option>
                                <option value="Burkina Faso">Burkina Faso</option>
                                <option value="Burundi">Burundi</option>
                                <option value="Cambodia">Cambodia</option>
                                <option value="Cameroon">Cameroon</option>
                                <option value="Canada">Canada</option>
                                <option value="Cape Verde">Cape Verde</option>
                                <option value="Cayman Islands">Cayman Islands</option>
                                <option value="Central African Republic">Central African Republic</option>
                                <option value="Chad">Chad</option>
                                <option value="Chile">Chile</option>
                                <option value="China">China</option>
                                <option value="Christmas Island">Christmas Island</option>
                                <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                                <option value="Colombia">Colombia</option>
                                <option value="Comoros">Comoros</option>
                                <option value="Congo">Congo</option>
                                <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                                <option value="Cook Islands">Cook Islands</option>
                                <option value="Costa Rica">Costa Rica</option>
                                <option value="Cote D\'ivoire">Cote D\'ivoire</option>
                                <option value="Croatia">Croatia</option>
                                <option value="Cuba">Cuba</option>
                                <option value="Cyprus">Cyprus</option>
                                <option value="Czech Republic">Czech Republic</option>
                                <option value="Denmark">Denmark</option>
                                <option value="Djibouti">Djibouti</option>
                                <option value="Dominica">Dominica</option>
                                <option value="Dominican Republic">Dominican Republic</option>
                                <option value="Ecuador">Ecuador</option>
                                <option value="Egypt">Egypt</option>
                                <option value="El Salvador">El Salvador</option>
                                <option value="Equatorial Guinea">Equatorial Guinea</option>
                                <option value="Eritrea">Eritrea</option>
                                <option value="Estonia">Estonia</option>
                                <option value="Ethiopia">Ethiopia</option>
                                <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                                <option value="Faroe Islands">Faroe Islands</option>
                                <option value="Fiji">Fiji</option>
                                <option value="Finland">Finland</option>
                                <option value="France">France</option>
                                <option value="French Guiana">French Guiana</option>
                                <option value="French Polynesia">French Polynesia</option>
                                <option value="French Southern Territories">French Southern Territories</option>
                                <option value="Gabon">Gabon</option>
                                <option value="Gambia">Gambia</option>
                                <option value="Georgia">Georgia</option>
                                <option value="Germany">Germany</option>
                                <option value="Ghana">Ghana</option>
                                <option value="Gibraltar">Gibraltar</option>
                                <option value="Greece">Greece</option>
                                <option value="Greenland">Greenland</option>
                                <option value="Grenada">Grenada</option>
                                <option value="Guadeloupe">Guadeloupe</option>
                                <option value="Guam">Guam</option>
                                <option value="Guatemala">Guatemala</option>
                                <option value="Guernsey">Guernsey</option>
                                <option value="Guinea">Guinea</option>
                                <option value="Guinea-bissau">Guinea-bissau</option>
                                <option value="Guyana">Guyana</option>
                                <option value="Haiti">Haiti</option>
                                <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                                <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                                <option value="Honduras">Honduras</option>
                                <option value="Hong Kong">Hong Kong</option>
                                <option value="Hungary">Hungary</option>
                                <option value="Iceland">Iceland</option>
                                <option value="India">India</option>
                                <option value="Indonesia">Indonesia</option>
                                <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                                <option value="Iraq">Iraq</option>
                                <option value="Ireland">Ireland</option>
                                <option value="Isle of Man">Isle of Man</option>
                                <option value="Israel">Israel</option>
                                <option value="Italy">Italy</option>
                                <option value="Jamaica">Jamaica</option>
                                <option value="Japan">Japan</option>
                                <option value="Jersey">Jersey</option>
                                <option value="Jordan">Jordan</option>
                                <option value="Kazakhstan">Kazakhstan</option>
                                <option value="Kenya">Kenya</option>
                                <option value="Kiribati">Kiribati</option>
                                <option value="Korea, Democratic People\'s Republic of">Korea, Democratic People\'s Republic of</option>
                                <option value="Korea, Republic of">Korea, Republic of</option>
                                <option value="Kuwait">Kuwait</option>
                                <option value="Kyrgyzstan">Kyrgyzstan</option>
                                <option value="Lao People\'s Democratic Republic">Lao People\'s Democratic Republic</option>
                                <option value="Latvia">Latvia</option>
                                <option value="Lebanon">Lebanon</option>
                                <option value="Lesotho">Lesotho</option>
                                <option value="Liberia">Liberia</option>
                                <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                                <option value="Liechtenstein">Liechtenstein</option>
                                <option value="Lithuania">Lithuania</option>
                                <option value="Luxembourg">Luxembourg</option>
                                <option value="Macao">Macao</option>
                                <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                                <option value="Madagascar">Madagascar</option>
                                <option value="Malawi">Malawi</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="Maldives">Maldives</option>
                                <option value="Mali">Mali</option>
                                <option value="Malta">Malta</option>
                                <option value="Marshall Islands">Marshall Islands</option>
                                <option value="Martinique">Martinique</option>
                                <option value="Mauritania">Mauritania</option>
                                <option value="Mauritius">Mauritius</option>
                                <option value="Mayotte">Mayotte</option>
                                <option value="Mexico">Mexico</option>
                                <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                                <option value="Moldova, Republic of">Moldova, Republic of</option>
                                <option value="Monaco">Monaco</option>
                                <option value="Mongolia">Mongolia</option>
                                <option value="Montenegro">Montenegro</option>
                                <option value="Montserrat">Montserrat</option>
                                <option value="Morocco">Morocco</option>
                                <option value="Mozambique">Mozambique</option>
                                <option value="Myanmar">Myanmar</option>
                                <option value="Namibia">Namibia</option>
                                <option value="Nauru">Nauru</option>
                                <option value="Nepal">Nepal</option>
                                <option value="Netherlands">Netherlands</option>
                                <option value="Netherlands Antilles">Netherlands Antilles</option>
                                <option value="New Caledonia">New Caledonia</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Nicaragua">Nicaragua</option>
                                <option value="Niger">Niger</option>
                                <option value="Nigeria">Nigeria</option>
                                <option value="Niue">Niue</option>
                                <option value="Norfolk Island">Norfolk Island</option>
                                <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                                <option value="Norway">Norway</option>
                                <option value="Oman">Oman</option>
                                <option value="Pakistan">Pakistan</option>
                                <option value="Palau">Palau</option>
                                <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                                <option value="Panama">Panama</option>
                                <option value="Papua New Guinea">Papua New Guinea</option>
                                <option value="Paraguay">Paraguay</option>
                                <option value="Peru">Peru</option>
                                <option value="Pitcairn">Pitcairn</option>
                                <option value="Poland" selected>Poland</option>
                                <option value="Portugal">Portugal</option>
                                <option value="Puerto Rico">Puerto Rico</option>
                                <option value="Qatar">Qatar</option>
                                <option value="Reunion">Reunion</option>
                                <option value="Romania">Romania</option>
                                <option value="Russian Federation">Russian Federation</option>
                                <option value="Rwanda">Rwanda</option>
                                <option value="Saint Helena">Saint Helena</option>
                                <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                                <option value="Saint Lucia">Saint Lucia</option>
                                <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                                <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                                <option value="Samoa">Samoa</option>
                                <option value="San Marino">San Marino</option>
                                <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                                <option value="Saudi Arabia">Saudi Arabia</option>
                                <option value="Senegal">Senegal</option>
                                <option value="Serbia">Serbia</option>
                                <option value="Seychelles">Seychelles</option>
                                <option value="Sierra Leone">Sierra Leone</option>
                                <option value="Singapore">Singapore</option>
                                <option value="Slovakia">Slovakia</option>
                                <option value="Slovenia">Slovenia</option>
                                <option value="Solomon Islands">Solomon Islands</option>
                                <option value="Somalia">Somalia</option>
                                <option value="South Africa">South Africa</option>
                                <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                                <option value="Spain">Spain</option>
                                <option value="Sri Lanka">Sri Lanka</option>
                                <option value="Sudan">Sudan</option>
                                <option value="Suriname">Suriname</option>
                                <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                                <option value="Swaziland">Swaziland</option>
                                <option value="Sweden">Sweden</option>
                                <option value="Switzerland">Switzerland</option>
                                <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                                <option value="Taiwan">Taiwan</option>
                                <option value="Tajikistan">Tajikistan</option>
                                <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Timor-leste">Timor-leste</option>
                                <option value="Togo">Togo</option>
                                <option value="Tokelau">Tokelau</option>
                                <option value="Tonga">Tonga</option>
                                <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                                <option value="Tunisia">Tunisia</option>
                                <option value="Turkey">Turkey</option>
                                <option value="Turkmenistan">Turkmenistan</option>
                                <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                                <option value="Tuvalu">Tuvalu</option>
                                <option value="Uganda">Uganda</option>
                                <option value="Ukraine">Ukraine</option>
                                <option value="United Arab Emirates">United Arab Emirates</option>
                                <option value="United Kingdom">United Kingdom</option>
                                <option value="United States">United States</option>
                                <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                                <option value="Uruguay">Uruguay</option>
                                <option value="Uzbekistan">Uzbekistan</option>
                                <option value="Vanuatu">Vanuatu</option>
                                <option value="Venezuela">Venezuela</option>
                                <option value="Viet Nam">Viet Nam</option>
                                <option value="Virgin Islands, British">Virgin Islands, British</option>
                                <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                                <option value="Wallis and Futuna">Wallis and Futuna</option>
                                <option value="Western Sahara">Western Sahara</option>
                                <option value="Yemen">Yemen</option>
                                <option value="Zambia">Zambia</option>
                                <option value="Zimbabwe">Zimbabwe</option>
                              </datalist>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-md-4">
                              <label for="civil" class="form-label">Civil Status</label>
                              <select class="form-select" aria-label="Default select example" name="civil" id="civil" required>
                                <option value="" hidden>Select</option>
                                <option value="Single">Single</option>
                                <option value="Married">Married</option>
                                <option value="Divorced">Divorced</option>
                                <option value="Widowed">Widowed</option>
                              </select>
                          </div>
                          <div class="col-md-8">
                              <label for="religion" class="form-label">Religion</label>
                              <input class="form-control" list="lists3" id="religion" name="religion" required>
                              <datalist id="lists3">
                                <option value="" hidden>Select</option>
                                <option value="Roman Catholic">Roman Catholic</option>
                                <option value="Iglesia ni Cristo">Iglesia ni Cristo</option>
                                <option value="Jehova\'s Witnesses">Jehova\'s Witnesses</option>
                                <option value="Born Again">Born Again</option>
                              </datalist>
                            </div>
                          </div>';
                        if($checkmodal3 == 1){
                          echo '<input type="hidden" name="email" id="email" value="'.$email1.'">
                                <input type="hidden" id="contact" name="contact" value="'.$contact1.'">
                                <input type="hidden"name="occupation" id="occupation" value="None">
                                <input type="hidden"name="nature" id="occupation" value="None">';
                        }
                        else{
                          echo '<div class="row">
                                  <div class="col-md-12">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" name="email" id="email" class="form-control" required>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <label for="contact" class="form-label">Contact #(e.g +63 9*********)</label>
                                      <div class="input-group">
                                        <span class="input-group-text">+63</span>
                                        <input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" required>
                                      </div>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <label for="occupation" class="form-label">Occupation</label>
                                    <input type="text" class="form-control" name="occupation" id="occupation" placeholder="Occupation..." required>
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col-md-12">
                                    <label for="nature" class="form-label">Nature of work</label>
                                    <select class="form-select" aria-label="Default select example" name="nature" id="nature" required>
                                      <option value="" hidden>Select</option>
                                      <option value="Architecture and Engineering">Architecture and Engineering</option>
                                      <option value="Arts, Culture and Entertainment">Arts, Culture and Entertainment</option>
                                      <option value="Business, Management and Administration">Business, Management and Administration</option>
                                      <option value="Communications">Communications</option>
                                      <option value="Community and Social Services">Community and Social Services</option>
                                      <option value="Education">Education</option>
                                      <option value="Science and Technology">Science and Technology</option>
                                      <option value="Installation, Repair and Maintenance">Installation, Repair and Maintenance</option>
                                      <option value="Farming, Fishing and Forestry">Farming, Fishing and Forestry</option>
                                      <option value="Government">Government</option>
                                      <option value="Health and Medicine">Health and Medicine</option>
                                      <option value="Law and Public Policy">Law and Public Policy</option>
                                      <option value="Sales">Sales</option>
                                      <option value="None">None</option>
                                    </select>
                                  </div>
                                </div>';
                        }
                  }
                  if($checkmodal1 == 1){
                    if($checkmodal4 == 1){
                      if($family_role == "Son"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Son" selected>Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($family_role == "Daugther"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Son">Son</option>
                                    <option value="Daugther" selected>Daugther</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      else{
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Son">Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Relative" selected>Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                    }
                    else{
                      if($family_role == "Father"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father" selected>Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($family_role == "Mother"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother" selected">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($family_role == "Son"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son" selected>Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($family_role == "Daugther"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daugther" selected>Daugther</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($family_role == "Grandfather"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Grandfather" selected>Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      elseif($family_role == "Grandmother"){
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother" selected>Grandmother</option>
                                    <option value="Relative">Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                      else{
                        echo '<div class="row">
                                <div class="col-md-12">
                                  <label for="family_role" class="form-label">Family Role</label>
                                  <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Son">Son</option>
                                    <option value="Daugther">Daugther</option>
                                    <option value="Grandfather">Grandfather</option>
                                    <option value="Grandmother">Grandmother</option>
                                    <option value="Relative" selected>Relative</option>
                                  </select>
                                </div>
                              </div>';
                      }
                    }
                    
                  }
                  else{
                    if($checkmodal3 == 1){
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="" hidden>Select</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    else{
                      echo '<div class="row">
                              <div class="col-md-12">
                                <label for="family_role" class="form-label">Family Role</label>
                                <select class="form-select" aria-label="Default select example" name="family_role" id="family_role" required>
                                  <option value="" hidden>Select</option>
                                  <option value="Father">Father</option>
                                  <option value="Mother">Mother</option>
                                  <option value="Son">Son</option>
                                  <option value="Daugther">Daugther</option>
                                  <option value="Grandfather">Grandfather</option>
                                  <option value="Grandmother">Grandmother</option>
                                  <option value="Relative">Relative</option>
                                </select>
                              </div>
                            </div>';
                    }
                    
                  }
                ?>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="indiadd">Add</button>
              <a href="individuals.php"><button type="button" class="btn btn-secondary" name="confirmresident">Cancel</button></a>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding individual pop-up modal end -->

      <!-- confirmation of adding individual pop-up modal -->
      <div class="modal fade" id="confaddindividual" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Househead Information</h5>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="firstname" class="form-label">Resident ID</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $resident_id;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="firstname" class="form-label">Firstname</label>
                  <p id="firstname" class="text-start fs-3"><?php echo $firstname;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="middlename" class="form-label">Middlename</label>
                  <p id="middlename" class="text-start fs-3"><?php echo $middlename;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lastname" class="form-label">Lastname</label>
                  <p id="lastname" class="text-start fs-3"><?php echo $lastname;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="gender" class="form-label">Gender</label>
                  <p id="gender" class="text-start fs-3"><?php echo $gender;?></p>
                </div>
                <div class="col-md-3">
                  <label for="age" class="form-label">Age</label>
                  <p id="age" class="text-start fs-3"><?php echo $age;?></p>
                </div>
                <div class="col-md-6">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <p id="birthdate" class="text-start fs-3"><?php echo $birthdate;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <p id="birthplace" class="text-start fs-3"><?php echo $birthplace;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="citizenship" class="form-label">Citizenship</label>
                    <p id="citizenship" class="text-start fs-3"><?php echo $citizenship;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="civil" class="form-label">Civil Status</label>
                  <p id="civil" class="text-start fs-3"><?php echo $civil;?></p>
                </div>
                <div class="col-md-8">
                  <label for="religion" class="form-label">Religion</label>
                  <p id="religion" class="text-start fs-3"><?php echo $religion;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="contact" class="form-label">Contact #</label>
                  <p id="contact" class="text-start fs-3"><?php echo $contact;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="typeemail" class="form-label">Email Address</label>
                  <p id="email" class="text-start fs-3"><?php echo $email;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="occupation" class="form-label">Occupation</label>
                  <p id="occupation" class="text-start fs-3"><?php echo $occupation;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="nature" class="form-label">Nature of work</label>
                  <p id="nature" class="text-start fs-3"><?php echo $nature;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="old" class="form-label">Old House #</label>
                  <p id="old" class="text-start fs-3"><?php echo $old;?></p>
                </div>
                <div class="col-md-6">
                  <label for="new" class="form-label">New House #</label>
                  <p id="new" class="text-start fs-3"><?php echo $new;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="street" class="form-label">Street</label>
                  <p id="street" class="text-start fs-3"><?php echo $street;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                    <label for="village" class="form-label">Village/Subdivision</label>
                    <p id="village" class="text-start fs-3"><?php echo $village;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="housetype" class="form-label">Household Type</label>
                  <p id="housetype" class="text-start fs-3"><?php echo $housetype;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="family_position" class="form-label">Family Position</label>
                  <p id="family_position" class="text-start fs-3"><?php echo $family_position;?></p>
                </div>
                <div class="col-md-6">
                  <label for="family_role" class="form-label">Family Role</label>
                  <p id="family_role" class="text-start fs-3"><?php echo $family_role;?></p>
                </div>
              </div>
              <input type="hidden" name="resident_id" value="<?=$resident_id?>">
              <input type="hidden" name="resident_code" value="<?=$resident_code?>">
              <input type="hidden" name="family_position" value="<?= $family_position?>">
              <input type="hidden" name="firstname" value="<?= $firstname?>">
              <input type="hidden" name="middlename" value="<?= $middlename?>">
              <input type="hidden" name="lastname" value="<?= $lastname?>">
              <input type="hidden" name="gender" value="<?= $gender?>">
              <input type="hidden" name="birthdate" value="<?= $birthdate?>">
              <input type="hidden" name="birthplace" value="<?= $birthplace?>">
              <input type="hidden" name="citizenship" value="<?= $citizenship?>">
              <input type="hidden" name="religion" value="<?= $religion?>">
              <input type="hidden" name="contact" value="<?= $contact?>">
              <input type="hidden" name="email" value="<?= $email?>">
              <input type="hidden" name="occupation" value="<?= $occupation?>">
              <input type="hidden" name="nature" value="<?= $nature?>">
              <input type="hidden" name="old" value="<?= $old?>">
              <input type="hidden" name="new" value="<?= $new?>">
              <input type="hidden" name="street" value="<?= $street?>">
              <input type="hidden" name="village" value="<?= $village?>">
              <input type="hidden" name="civil" value="<?= $civil?>">
              <input type="hidden" name="housetype" value="<?= $housetype?>">
              <input type="hidden" name="family_role" value="<?= $family_role?>">
              <input type="hidden" name="newImageName" value="<?= $newImageName?>">
              <input type="hidden" name="checkmodal4" value="<?= $checkmodal4?>">
              <input type="hidden" name="birthdateguar" value="<?= $birthdateguar?>">
              <input type="hidden" name="username" value="<?= $username?>">
              <input type="hidden" name="pass" value="<?= $pass?>">
              <input type="hidden" name="epass" value="<?= $epass?>">
              <input type="hidden" name="checkmodal3" value="<?= $checkmodal3?>">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="confmemadding">Confirm</button>
              <button type="submit" class="btn btn-secondary" name="reloadaddhead2">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- confirmation of adding individual pop-up modal end -->

      <!-- entering parent resident id pop-up modal -->
      <div class="modal fade" id="censusmodalminor" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="staticBackdropLabel">Adding Minor Account</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <?php
                    if(isset($errorMSG)){
                      echo '<div class="alert alert-warning" role="alert">
                              '.$errorMSG.'
                            </div>';
                    }
                  ?>
                </div>
              </div>
              <form action="individuals.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="resid" class="form-label">Guardian Resident ID</label>
                  <input type="text" class="form-control" id="resid" name="resid" placeholder="Enter valid resident id..." required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="resiid">Submit</button>
              <a href="individuals.php"><button type="button" class="btn btn-secondary" name="confirmresident">Cancel</button></a>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- entering parent resident id pop-up modal end -->

      <!-- generate report options pop-up modal -->
      <div class="modal fade" id="genreportmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Generate Report</h5>
            </div>
            <div class="modal-body">
            <form action="individuals.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="agespan" class="form-label">Group Age</label>
                  <select class="form-select" aria-label="Default select example" name="agespan" id="agespan" required>
                    <option value="" hidden>Select</option>
                    <option value="1">0-17 years (minor)</option>
                    <option value="2">18-59 year (adult)</option>
                    <option value="3">60+ years (senior)</option>
                    <option value="4">All</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="printtransaction" value="1">Generate</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- generate report options pop-up end modal -->

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

  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
  <script src="js/bttop.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addhead").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#confaddhead").modal("show");
    });
  </script>';
  }
  elseif($showModal == 3){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#detailres").modal("show");
    });
  </script>';
  }
  elseif($showModal == 4){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#deleteconfmod").modal("show");
    });
  </script>';
  }
  elseif($showModal == 5){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#editmodal").modal("show");
    });
  </script>';
  }
  elseif($showModal == 6){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#updateconfmodal").modal("show");
    });
  </script>';
  }
  elseif($showModal == 7){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#addindi").modal("show");
    });
  </script>';
  }
  elseif($showModal == 8){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#addindividual").modal("show");
    });
  </script>';
  }
  elseif($showModal == 9){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#confaddindividual").modal("show");
    });
  </script>';
  }
  elseif($showModal == 11){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#addconfmodal").modal("show");
    });
  </script>';
  }
  elseif($showModal == 12){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#viewingpending").modal("show");
    });
  </script>';
  }
  elseif($showModal == 13){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#confviewingpending").modal("show");
    });
  </script>';
  }
  elseif($showModal == 14){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#noticonfviewingpending").modal("show");
    });
  </script>';
  }
  elseif($showModal == 15){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#denyreasonmodal").modal("show");
    });
  </script>';
  }
  elseif($showModal == 16){
    // CALL MODAL HERE
		echo '<script type="text/javascript">
    $(document).ready(function(){
      $("#choosetype").modal("show");
    });
  </script>';
  }
  if($showModal == 17) {
    // CALL MODAL HERE
    echo '<script type="text/javascript">
      $(document).ready(function(){
        $("#censusmodal").modal("show");
      });
    </script>';
  }
  elseif($showModal == 18) {
    // CALL MODAL HERE
    echo '<script type="text/javascript">
      $(document).ready(function(){
        $("#censusmodalminor").modal("show");
      });
    </script>';
  }
  else{
    
  }
  ?>
</body>
</html>