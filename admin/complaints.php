<?php
  /** creating connection for email sender */
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  require 'PHPMailer/src/Exception.php';
  require 'PHPMailer/src/PHPMailer.php';
  require 'PHPMailer/src/SMTP.php';

  /** creating connection to db */
  require 'dbcon.php';
  
  // creating connection for SMS sender
  require __DIR__ . '/../vendor/autoload.php';
  use Twilio\Rest\Client;

  /** start session */
  session_start();
  date_default_timezone_set('Asia/Manila');
  $date = date('Y-m-d');
  $compdate4 = date_create($date);
  $compdate4 = date_format($compdate4, 'F d, Y (h:i:s a)');
  $date_reg = date('Y-m-d H:i:s');
  $datemin = date('Y-m-d H:i:s', strtotime($date_reg . ' - 2 hours'));
  $datemax = date('Y-m-d H:i:s', strtotime($date_reg . ' + 1 year'));

  /** variable decider for the modals */
  $showModal =0;
  $errorshow = 0;
  $imageface = 0;
  $imageevi = 0;
  $reload1 = 0;
  $type3 = 0;
  $checker1 = 0;
  $notif = 0;

  /** code for couting pending residents */
  $sql3 = "SELECT COUNT(id) FROM complaint_tbl WHERE status = '1'";
  $res3 = $con->query($sql3);
  $cnt3 = $res3->fetchColumn();

  /** record session details */
  if(!isset($_SESSION['user'])){
    header("Location: login.php");
  }

  /** verifying resident id codes */
  if(isset($_REQUEST['complainantver'])){
    $resident_id = filter_var($_REQUEST['resident_id'],FILTER_SANITIZE_NUMBER_INT);
    /** variable use to decide in the if statement */
    try{
      /** sql query in pdo format that check if the resident code input is already existing in the db */
      $slct_stmt2 = $con->prepare("SELECT resident_id FROM penresident WHERE resident_id = :resident_id LIMIT 1");
      $slct_stmt2->execute([
        ':resident_id' => $resident_id
      ]);
      $row2 = $slct_stmt2->fetch(PDO::FETCH_ASSOC);
      if($slct_stmt2->rowCount() > 0){
        // $type3 = 1;
        // $type4 = 0;
        $type = 'Resident';
        $showModal = 5;
      }
      else{
        $errorMSG = "Invalid Resident ID.";
        $showModal = 7;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  /** verifying resident id codes */
  if(isset($_REQUEST['complaineever'])){
    $resident_id = $_REQUEST['resident_id'];
    // $type4 = $_REQUEST['type4'];
    $type = $_REQUEST['type'];
    if($resident_id == 'none'){
      $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
      $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
      $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
      $birthdate = $_REQUEST['birthdate'];
      $gender = $_REQUEST['gender'];
      $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
      $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
      $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
    }
    $resident_id2 = filter_var($_REQUEST['resident_id2'],FILTER_SANITIZE_NUMBER_INT);
    $showModal = 8;
  }
  
  if(isset($_REQUEST['filecomplaint'])){
    $type = $_REQUEST['type'];
    $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
    $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
    $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
    $birthdate = $_REQUEST['birthdate'];
    $gender = $_REQUEST['gender'];
    $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
    $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
    $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));

    # verifying entered email if it is valid using api key
    $api_key = "a12277e410a640b2a99529005100db48";
    $ch = curl_init();
    curl_setopt_array($ch, [
      CURLOPT_URL => "https://emailvalidation.abstractapi.com/v1?api_key=$api_key&email=$complainant_email",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true
    ]);
    $response = curl_exec($ch);

    curl_close($ch);

    $data_key = json_decode($response, true);
    
    if($data_key["deliverability"] === "UNDELIVERABLE"){
      $errorMSG1 = "Email must valid.";
      $checker1 = 1;
      $reload1 = 1;
      $showModal = 6;
    }
    elseif($data_key["is_disposable_email"]["value"] === true){
      $errorMSG1 = "Email must not be disposable.";
      $checker1 = 1;
      $reload1 = 1;
      $showModal = 6;
    }
    else{
      $type4 = 1;
      $resident_id = 'none';
      $showModal = 5;
    }
  }

  if(isset($_REQUEST['reload1'])){
    $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
    $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
    $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
    $birthdate = $_REQUEST['birthdate'];
    $gender = $_REQUEST['gender'];
    $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
    $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
    $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
    $reload1 = 1;
    $showModal = 6;
  }

  if(isset($_REQUEST['complaineetype'])){
    if($_REQUEST['complaineetype'] == 1){
      $type = $_REQUEST['type'];
      $type4 = $_REQUEST['type4'];
      $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
      $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
      $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
      $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
      $birthdate = $_REQUEST['birthdate'];
      $gender = $_REQUEST['gender'];
      $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
      $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
      $resident_id = $_REQUEST['resident_id'];
      $showModal = 9;
    }
    else{
      $type3 = $_REQUEST['type3'];
      $type = $_REQUEST['type'];
      $resident_id = $_REQUEST['resident_id'];
      $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
      $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
      $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
      $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
      $birthdate = $_REQUEST['birthdate'];
      $gender = $_REQUEST['gender'];
      $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
      $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
      $showModal = 2;
    }
  }

  /** filing outsider complaint codes */
  if(isset($_REQUEST['outcomplaintconfirm'])){
    $type = $_REQUEST['type'];
    $type3 = $_REQUEST['type3'];
    if($type3 == 1){
      $resident_id = $_REQUEST['resident_id'];
      /** query to get the appropriate data from the database */ 
      $sql6 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
      $d6 =  $con->query($sql6);
      foreach($d6 as $data6){
        $firstname = ($data6['firstname']);
        $middlename = ($data6['middlename']);
        $lastname = ($data6['lastname']);
        $birthdate = ($data6['birthdate']);
        $contact = ($data6['contact']);
        $gender = ($data6['gender']);
        $street = ($data6['street']);
        $new = ($data6['new']);
        $complainant_email = ($data6['email']);
      }
      $address = $new . ' ' . $street . ' St. Concepcion Dos, Manila';
    }
    else{
      $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
      $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
      $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
      $birthdate = $_REQUEST['birthdate'];
      $gender = $_REQUEST['gender'];
      $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
      $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
      $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
    }
    $type2 = $_REQUEST['type2'];
    $firstname2 = ucwords(trim(filter_var($_REQUEST['firstname2'],FILTER_SANITIZE_STRING)));
    $middlename2 = ucwords(trim(filter_var($_REQUEST['middlename2'],FILTER_SANITIZE_STRING)));
    $lastname2 = ucwords(trim(filter_var($_REQUEST['lastname2'],FILTER_SANITIZE_STRING)));
    $estage = $_REQUEST['estage'];
    $gender2 = $_REQUEST['gender2'];
    if(isset($_REQUEST['complainee_email'])){
      $complainee_email = strtolower(trim(filter_var($_REQUEST['complainee_email'],FILTER_SANITIZE_EMAIL)));
    }
    else{
      $complainee_email = 'none';
    }
    if(isset($_REQUEST['contact2'])){
      $contact2 = trim(filter_var($_REQUEST['contact2'],FILTER_SANITIZE_NUMBER_INT));
    }
    else{
      $contact2 = 'none';
    }
    if(isset($_REQUEST['address2'])){
      $address2 = ucwords(trim(filter_var($_REQUEST['address2'],FILTER_SANITIZE_STRING)));
    }
    else{
      $address2 = 'none';
    }
    
    $statement = ucfirst(trim(filter_var($_REQUEST['statement'],FILTER_SANITIZE_STRING)));
    $comptype = $_REQUEST['comptype'];

    /** dowhile loop that prevent the duplication of resident id in the db */
    $check1 = true;
    $cts1 = 0;
    do {
      $cts1++;
      /** creating resident id by shuffling those permitted characters*/
      $id = rand(1000000, 9999999);
      /** query to check if the produce resident id is already existing in the db */
      $select_stmt3 = $con->prepare("SELECT id FROM complaint_tbl WHERE id = :id");
      $select_stmt3->execute([':id' => $id]);
      $row3 = $select_stmt3->fetch(PDO::FETCH_ASSOC);
      if(isset($row3['id']) == $id){
        $check1 = false;
      }
      else{
        $check1 = true;
      }
    } while ($check1 == false && $cts1 != 20);

    if($check1 == true){
      $status = 1;
      $incharge = "VAWC";
      
      if(isset($_FILES["face_image"])){
        if($_FILES["face_image"]["error"] == 4){
          $errorMSG = 'Something went wrong, please try again.';
          $showModal = 4;
        }
        else{
          # preparing image variables
          # face image
          $fileName = $_FILES["face_image"]["name"];
          $fileSize = $_FILES["face_image"]["size"];
          $tmpName = $_FILES["face_image"]["tmp_name"];

          # variable that check and prepare the file extension
          $validImageExtension = ['jpg', 'jpeg', 'png'];
          $imageExtension = explode('.', $fileName);
          $imageExtension = strtolower(end($imageExtension));
          # prepare final image variable
          $newImageName = uniqid();
          $newImageName .= '.' . $imageExtension;
          # check image errors
          if ( !in_array($imageExtension, $validImageExtension) ){
            $errorMSG = 'Only accept jpg, png and jpeg files.';
            $errorshow = 1;
            $showModal = 4;
          }
          else if($fileSize > 1000000){
            $errorMSG = 'Image file size is too big.';
            $errorshow = 1;
            $showModal = 4;
          }
          else{
            $imageface = 1;
          }
        }
      }

      if(isset($_FILES["image"])){
        if($_FILES["image"]["error"] == 4){
          $errorMSG = 'Something went wrong, please try again.';
          $showModal = 4;
        }
        else{
          # evidence image
          $fileName2 = $_FILES["image"]["name"];
          $fileSize2 = $_FILES["image"]["size"];
          $tmpName2 = $_FILES["image"]["tmp_name"];
          # variable that check and prepare the file extension
          $validImageExtension2 = ['jpg', 'jpeg', 'png'];
          $imageExtension2 = explode('.', $fileName2);
          $imageExtension2 = strtolower(end($imageExtension2));
          $newImageName2 = uniqid();
          $newImageName2 .= '.' . $imageExtension2;
          
          # check image errors
          if ( !in_array($imageExtension2, $validImageExtension2) ){
            $errorMSG = 'Only accept jpg, png and jpeg files.';
            $errorshow = 1;
            $showModal = 4;
          }
          else if($fileSize2 > 1000000){
            $errorMSG = 'Image file size is too big.';
            $errorshow = 1;
            $showModal = 4;
          }
          else{
            $imageevi = 1;
          }
        }
      }

      if($errorshow == 0){
        try{
          $query1 = $con->prepare("INSERT INTO complaint_tbl (id, complainant_fname, complainant_mname, complainant_lname, complainee_fname, complainee_mname, complainee_lname, statement, incharge, complainant_address, complainant_num, status, complainee_num, complainee_address, complainant_bdate, complainant_gender, complainee_gender, complainee_age, complaint_type, date_complaint, complainant_type, complainee_type, complainant_email, complainee_email)
          VALUES (:id, :complainant_fname, :complainant_mname, :complainant_lname, :complainee_fname, :complainee_mname, :complainee_lname, :statement, :incharge, :complainant_address, :complainant_num, :status, :complainee_num, :complainee_address, :complainant_bdate, :complainant_gender, :complainee_gender, :complainee_age, :complaint_type, :date_complaint, :complainant_type, :complainee_type, :complainant_email, :complainee_email)");
          $query1->execute(
            [
            ':id' => $id,
            ':complainant_fname' => $firstname,
            ':complainant_mname' => $middlename,
            ':complainant_lname' => $lastname,
            ':complainee_fname' => $firstname2,
            ':complainee_mname' => $middlename2,
            ':complainee_lname' => $lastname2,
            ':statement' => $statement,
            ':incharge' => $incharge,
            ':complainant_address' => $address,
            ':complainant_num' => $contact,
            ':status' => $status,
            ':complainee_num' => $contact2,
            ':complainee_address' => $address2,
            ':complainant_bdate' => $birthdate, 
            ':complainant_gender' => $gender, 
            ':complainee_gender' => $gender2, 
            ':complainee_age' => $estage,
            ':complaint_type' => $comptype,
            ':date_complaint' => $date_reg,
            ':complainant_type' => $type,
            ':complainee_type' => $type2,
            ':complainant_email' => $complainant_email,
            ':complainee_email' => $complainee_email
            ]
          );
          if($imageface == 0){
            $newImageName = 'none';
          }
          if($imageevi == 0){
            $newImageName2 = 'none';
          }
          $query7 = $con->prepare("INSERT INTO evidence_tbl(id, complainee_image, evidence_image) VALUES
          (:id, :complainee_image, :evidence_image)");
          $query7->execute(
            [
            ':id' => $id,
            ':complainee_image' => $newImageName,
            ':evidence_image' => $newImageName2
            ]
          );
          if($imageface == 1){
            move_uploaded_file($tmpName, 'images/' . $newImageName);
          }
          if($imageevi == 1){
            move_uploaded_file($tmpName2, 'images/' . $newImageName2);
          }
          $showModal = 3;
        }
        catch(PDOException $e){
          $pdoError = $e->getMesage();
        }
      }

    }
    else{
      $errorMSG = 'System already reach it\'s capacity to add complaints.';
      $showModal = 4;
    }
  }

  /** filing outsider complaint codes */
  if(isset($_REQUEST['rescomplaineeconfirm'])){
    $type = $_REQUEST['type'];
    // $type4 = $_REQUEST['type4'];
    $resident_id = $_REQUEST['resident_id'];
    $resident_id2 = $_REQUEST['resident_id2'];

    if($resident_id == 'none'){
      $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
      $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
      $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
      $birthdate = $_REQUEST['birthdate'];
      $gender = $_REQUEST['gender'];
      $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
      $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
      $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
    }
    else{
      /** query to get the appropriate data from the database */ 
      $sql6 = "SELECT * FROM penresident WHERE resident_id = $resident_id";
      $d6 =  $con->query($sql6);
      foreach($d6 as $data6){
        $firstname = ($data6['firstname']);
        $middlename = ($data6['middlename']);
        $lastname = ($data6['lastname']);
        $birthdate = ($data6['birthdate']);
        $contact = ($data6['contact']);
        $gender = ($data6['gender']);
        $street = ($data6['street']);
        $new = ($data6['new']);
        $complainant_email = ($data6['email']);
      }
      $address = $new . ' ' . $street . ' St. Concepcion Dos, Manila';
    }
    $sql1 = "SELECT * FROM penresident WHERE resident_id = $resident_id2";
    $d1 =  $con->query($sql1);
    foreach($d1 as $data1){
      $firstname2 = ($data1['firstname']);
      $middlename2 = ($data1['middlename']);
      $lastname2 = ($data1['lastname']);
      $birthdate2 = ($data1['birthdate']);
      $contact2 = ($data1['contact']);
      $gender2 = ($data1['gender']);
      $street2 = ($data1['street']);
      $new2 = ($data1['new']);
      $complainee_email = ($data1['email']);
    }
    $address2 = $new2. ' ' . $street2 . ' St. Concepcion Dos, Manila';
    $type2 = 'Resident';


    /** setting age */
    $date1 = date('Y-m-d');
    $datetime2 = new DateTime($date1);
    $datetime1 = new DateTime($birthdate2);
    $difference = $datetime1->diff($datetime2);
    $estage = ($difference->y);

    
    $statement = ucfirst(trim(filter_var($_REQUEST['statement'],FILTER_SANITIZE_STRING)));
    $comptype = $_REQUEST['comptype'];

    /** dowhile loop that prevent the duplication of resident id in the db */
    $check3 = true;
    $cts3 = 0;
    do {
      $cts3++;
      /** creating resident id by shuffling those permitted characters*/
      $id = rand(1000000, 9999999);
      /** query to check if the produce resident id is already existing in the db */
      $select_stmt4 = $con->prepare("SELECT id FROM complaint_tbl WHERE id = :id");
      $select_stmt4->execute([':id' => $id]);
      $row4 = $select_stmt4->fetch(PDO::FETCH_ASSOC);
      if(isset($row4['id']) == $id){
        $check1 = false;
      }
      else{
        $check1 = true;
      }
    } while ($check3 == false && $cts3 != 20);

    if($check3 == true){
      $status = 2;
      $incharge = "VAWC";

      # checking hearing date
      $hearing2 = $_REQUEST['hearing'];
      $currdate2 = date('Y-m-d H:i:s');
      $alloweddate2 = date('Y-m-d\TH:i:s', strtotime($currdate2 . ' + 1 day'));
      if($hearing2 < $alloweddate2){
        $errorMSG4 = 'The assigned hearing must be at least 1 day prior to its approval.';
        $checker1 = 2;
        $showModal = 8;
      }
      else{
        if(isset($_FILES["face_image"])){
          if($_FILES["face_image"]["error"] == 4){
            $errorMSG2 = 'Something went wrong, please try again.';
            $showModal = 8;
          }
          else{
            # preparing image variables
            # face image
            $fileName = $_FILES["face_image"]["name"];
            $fileSize = $_FILES["face_image"]["size"];
            $tmpName = $_FILES["face_image"]["tmp_name"];
  
            # variable that check and prepare the file extension
            $validImageExtension = ['jpg', 'jpeg', 'png'];
            $imageExtension = explode('.', $fileName);
            $imageExtension = strtolower(end($imageExtension));
            # prepare final image variable
            $newImageName = uniqid();
            $newImageName .= '.' . $imageExtension;
            # check image errors
            if ( !in_array($imageExtension, $validImageExtension) ){
              $errorMSG2 = 'Only accept jpg, png and jpeg files.';
              $errorshow = 1;
              $showModal = 8;
            }
            else if($fileSize > 1000000){
              $errorMSG2 = 'Image file size is too big.';
              $errorshow = 1;
              $showModal = 8;
            }
            else{
              $imageface = 1;
            }
          }
        }
  
        if(isset($_FILES["image"])){
          if($_FILES["image"]["error"] == 4){
            $errorMSG2 = 'Something went wrong, please try again.';
            $showModal = 8;
          }
          else{
            # evidence image
            $fileName2 = $_FILES["image"]["name"];
            $fileSize2 = $_FILES["image"]["size"];
            $tmpName2 = $_FILES["image"]["tmp_name"];
            # variable that check and prepare the file extension
            $validImageExtension2 = ['jpg', 'jpeg', 'png'];
            $imageExtension2 = explode('.', $fileName2);
            $imageExtension2 = strtolower(end($imageExtension2));
            $newImageName2 = uniqid();
            $newImageName2 .= '.' . $imageExtension2;
            
            # check image errors
            if ( !in_array($imageExtension2, $validImageExtension2) ){
              $errorMSG2 = 'Only accept jpg, png and jpeg files.';
              $errorshow = 1;
              $showModal = 8;
            }
            else if($fileSize2 > 1000000){
              $errorMSG2 = 'Image file size is too big.';
              $errorshow = 1;
              $showModal = 8;
            }
            else{
              $imageevi = 1;
            }
          }
        }
  
        
        if($errorshow == 0){
          try{
            $query1 = $con->prepare("INSERT INTO complaint_tbl (id, complainant_fname, complainant_mname, complainant_lname, complainee_fname, complainee_mname, complainee_lname, statement, incharge, complainant_address, complainant_num, status, complainee_num, complainee_address, complainant_bdate, complainant_gender, complainee_gender, complainee_age, complaint_type, date_complaint, complainant_type, complainee_type, complainant_email, complainee_email)
                                                        VALUES (:id, :complainant_fname, :complainant_mname, :complainant_lname, :complainee_fname, :complainee_mname, :complainee_lname, :statement, :incharge, :complainant_address, :complainant_num, :status, :complainee_num, :complainee_address, :complainant_bdate, :complainant_gender, :complainee_gender, :complainee_age, :complaint_type, :date_complaint, :complainant_type, :complainee_type, :complainant_email, :complainee_email)");
            $query1->execute(
              [
              ':id' => $id,
              ':complainant_fname' => $firstname,
              ':complainant_mname' => $middlename,
              ':complainant_lname' => $lastname,
              ':complainee_fname' => $firstname2,
              ':complainee_mname' => $middlename2,
              ':complainee_lname' => $lastname2,
              ':statement' => $statement,
              ':incharge' => $incharge,
              ':complainant_address' => $address,
              ':complainant_num' => $contact,
              ':status' => $status,
              ':complainee_num' => $contact2,
              ':complainee_address' => $address2,
              ':complainant_bdate' => $birthdate, 
              ':complainant_gender' => $gender, 
              ':complainee_gender' => $gender2, 
              ':complainee_age' => $estage,
              ':complaint_type' => $comptype,
              ':date_complaint' => $date_reg,
              ':complainant_type' => $type,
              ':complainee_type' => $type2,
              ':complainant_email' => $complainant_email,
              ':complainee_email' => $complainee_email
              ]
            );
            if($imageface == 0){
              $newImageName = 'none';
            }
            if($imageevi == 0){
              $newImageName2 = 'none';
            }
            $query7 = $con->prepare("INSERT INTO evidence_tbl(id, complainee_image, evidence_image) VALUES
            (:id, :complainee_image, :evidence_image)");
            $query7->execute(
              [
              ':id' => $id,
              ':complainee_image' => $newImageName,
              ':evidence_image' => $newImageName2
              ]
            );
            if($imageface == 1){
              move_uploaded_file($tmpName, 'images/' . $newImageName);
            }
            if($imageevi == 1){
              move_uploaded_file($tmpName2, 'images/' . $newImageName2);
            }
            $status4 = 0;
            $query3 = $con->prepare("INSERT INTO hearing_tbl (id, hearing_date, status, type, incharge) VALUES (:id, :hearing_date, :status, :type, :incharge)");
            $query3->execute(
              [
              ':id' => $id,
              ':hearing_date' => $hearing2,
              ':status' => $status4,
              ':type' => $status4,
              ':incharge' => $incharge
              ]
            );
            $heardate2 = date_create($hearing2);
            $heardate2 = date_format($heardate2, 'F d, Y h:i:s a');
            # sending email to both parties
            # sending email of approval to complainant
            $mail3 = new PHPMailer(true);
            $mail3->isSMTP();
            $mail3->Host = "smtp.gmail.com";
            $mail3->SMTPAuth = true;
            $mail3->Username = 'pjjumawan18@gmail.com';
            $mail3->Password = 'lfvjckzasfrpzxzf';
            $mail3->SMTPSecure = 'ssl';
            $body3 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Request</strong></p><br>
                      <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                      After reviewing your submitted complaint, we are here to inform you that your complaint has been deemed acceptable.<br>
                      To resolve this issue, please report to our Bararangay Hall on <b>'.$heardate2.'</b></p><br>
                      <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concecion Dos Management.</b></p>';
            
            $mail3->Port = 465;
    
            $mail3->setFrom('pjjumawan18@gmail.com');
    
            $mail3->addAddress($complainant_email);
    
            $mail3->isHTML(true);
    
            $mail3->Subject = 'Resident Complaint';
            $mail3->Body = $body3;
    
            $mail3->send();
    
            # sending email of approval to complainee
            $mail4 = new PHPMailer(true);
            $mail4->isSMTP();
            $mail4->Host = "smtp.gmail.com";
            $mail4->SMTPAuth = true;
            $mail4->Username = 'pjjumawan18@gmail.com';
            $mail4->Password = 'lfvjckzasfrpzxzf';
            $mail4->SMTPSecure = 'ssl';
    
            $body4 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Request</strong></p><br>
                      <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                      We are here to inform you that there is a filed complaint against you.<br>
                      To resolve this issue, we invite you to our Bararangay Hall on <b>'.$heardate2.'</b><br>
                      Not attending the invitation will lead to further inconvenince against you.</p><br>
                      <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concecion Dos Management.</b></p>';
            
            $mail4->Port = 465;
    
            $mail4->setFrom('pjjumawan18@gmail.com');
    
            $mail4->addAddress($complainee_email);
    
            $mail4->isHTML(true);
    
            $mail4->Subject = 'Resident Complaint';
            $mail4->Body = $body4;
    
            $mail4->send();
  
            $showModal = 3;
          }
          catch(PDOException $e){
            $pdoError = $e->getMesage();
          }
        } 
      }
    }
    else{
      $errorMSG = 'System already reach it\'s capacity to add complaints.';
      $showModal = 8;
    }
  }

  // opening complaint codes 
  if(isset($_REQUEST['pendingcomplaint'])){
    $compid = $_REQUEST['pendingcomplaint'];
    $showModal = 10;
  }
  
  if(isset($_REQUEST['denycomplaint'])){
    $idcomp = $_REQUEST['denycomplaint'];
    $deny1 = $_REQUEST['deny1'];
    $showModal = 11;
  }
  
  if(isset($_REQUEST['deletecomp'])){
    $idcomp2 = $_REQUEST['deletecomp'];
    $deny1 = $_REQUEST['deny1'];
    try{
      $sql11 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp2'";
      $d11 =  $con->query($sql11);
      foreach($d11 as $data11){
        $complainant_num = ('+63'.$data11['complainant_num']);
        $email2f = $data11['complainant_email'];
      }
      $sql8 = $con->prepare("DELETE FROM complaint_tbl WHERE id = :id");
      if($sql8->execute([':id' => $idcomp2])){
        $sql10 = "SELECT * FROM evidence_tbl WHERE id = '$idcomp2'";
        $d10 =  $con->query($sql10);
        foreach($d10 as $data10){
          $compimg = $data10['complainee_image'];
          $eveimg = $data10['evidence_image'];
        }
        if($compimg != 'none'){
          unlink("images/".$compimg);
        }
        if($eveimg != 'none'){
          unlink("images/".$eveimg);
        }
        $sql9 = $con->prepare("DELETE FROM evidence_tbl WHERE id = :id");
        $sql9->execute([':id' => $idcomp2]);

        // sending email code
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = "smtp.gmail.com";
        $mail->SMTPAuth = true;
        $mail->Username = 'pjjumawan18@gmail.com';
        $mail->Password = 'lfvjckzasfrpzxzf';
        $mail->SMTPSecure = 'ssl';
        if($deny1 == 1){
          $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Denied</strong></p><br>
                <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">We are sorry to inform you that your complaint has been declined due to lack of information or evidence.<br>
                From: <b>Concecion Dos Management.</b></p>';
        }
        else{
          $body = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Denied</strong></p><br>
                 <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">We are sorry to inform you that your complaint has been declined because we are unable to identify the person you complained in our resident list.<br>
                 From: <b>Concecion Dos Management.</b></p>';
        }
        $mail->Port = 465;
        $mail->setFrom('pjjumawan18@gmail.com');
        $mail->addAddress($email2f);
        $mail->isHTML(true);
        $mail->Subject = 'Resident Complaint';
        $mail->Body = $body;
        $mail->send();

        // # sending message to the complainant
        // // Your Account SID and Auth Token from twilio.com/console
        // $account_sid = 'AC843a9e7b84124c5e8e7474b48b9725f6';
        // $auth_token = '3f92c70d38edbf2051eed23a0c7f089d';

        // // In production, these should be environment variables. E.g.:
        // // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]
        // // A Twilio number you own with SMS capabilities
        // $twilio_number = "+17409000891";
        // if($deny1 == 1){
        //   $msgbody = 'We are sorry to inform you that your complaint has been declined due to lack of information or evidence.';
        // }
        // else{
        //   $msgbody = 'We are soory to inform you that your complaint has been decline because we are unable to identify the person you complain in our resident list.';
        // }

        // $client = new Client($account_sid, $auth_token);
        // $message = $client->messages->create(
        //     // Where to send a text message (your cell phone?)
        //     $complainant_num,
        //     array(
        //         'from' => $twilio_number,
        //         'body' => $msgbody
        //     )
        // );
        $showModal = 12;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  if(isset($_REQUEST['identifycomp'])){
    $idcomp1 = $_REQUEST['identifycomp'];
    $showModal = 13;
  }

  if(isset($_REQUEST['approveopenmod'])){
    $idapprove = $_REQUEST['idapprove'];
    $idcomp1 = $_REQUEST['idcomp1'];
    $showModal = 14;
  }

  if(isset($_REQUEST['approvecomplaint'])){
    $idapprove = $_REQUEST['approvecomplaint'];
    $idcomp1 = $_REQUEST['idcomp1'];
    $hearing = $_REQUEST['hearing'];
    $status2 = 2;
    $currdate = date('Y-m-d H:i:s');
    $alloweddate = date('Y-m-d\TH:i:s', strtotime($currdate . ' + 1 day'));
    if($hearing < $alloweddate){
      $errorMSG3 = 'The assigned hearing must be at least 1 day prior to its approval.';
      $showModal = 14;
    }
    else{
      try{
        $sql17 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp1'";
        $d17 =  $con->query($sql17);
        foreach($d17 as $data17){
          $complainant_num = ('+63'.$data17['complainant_num']);
          $email4f = $data17['complainant_email'];
          $incharge1 = $data17['incharge'];
        }
        $sql18 = "SELECT * FROM penresident WHERE resident_id = '$idapprove'";
        $d18 =  $con->query($sql18);
        foreach($d18 as $data18){
          $comnum = $data18['contact'];
          $complainee_num = ('+63'.$data18['contact']);
          $email3f = $data18['email'];
        }
        # pdo prepared statement that update the pending resident
        $sql16 = $con->prepare("UPDATE complaint_tbl SET status=:status, complainee_num=:complainee_num, complainee_email=:complainee_email WHERE id = :id");
        # execute query
        if($sql16->execute( [':status' => $status2, ':complainee_num' => $comnum, ':complainee_email' => $email3f, ':id' => $idcomp1])){
          $status1 = 0;
          $query2 = $con->prepare("INSERT INTO hearing_tbl (id, hearing_date, status, type, incharge)
          VALUES (:id, :hearing_date, :status, :type, :incharge)");
          $query2->execute(
            [
            ':id' => $idcomp1,
            ':hearing_date' => $hearing,
            ':status' => $status1,
            ':type' => $status1,
            ':incharge' => $incharge1
            ]
          );
          $heardate = date_create($hearing);
          $heardate = date_format($heardate, 'F d, Y h:i:s a');
  
          # sending email of approval to complainant
          $mail1 = new PHPMailer(true);
          $mail1->isSMTP();
          $mail1->Host = "smtp.gmail.com";
          $mail1->SMTPAuth = true;
          $mail1->Username = 'pjjumawan18@gmail.com';
          $mail1->Password = 'lfvjckzasfrpzxzf';
          $mail1->SMTPSecure = 'ssl';
          $body1 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Request</strong></p><br>
                    <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                    After reviewing your submitted complaint, we are here to inform you that your complaint has been deemed acceptable.<br>
                    To resolve this issue, please report to our Bararangay Hall on <b>'.$heardate.'</b></p><br>
                    <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concecion Dos Management.</b></p>';
          
          $mail1->Port = 465;
  
          $mail1->setFrom('pjjumawan18@gmail.com');
  
          $mail1->addAddress($email4f);
  
          $mail1->isHTML(true);
  
          $mail1->Subject = 'Resident Complaint';
          $mail1->Body = $body1;
  
          $mail1->send();
  
          # sending email of approval to complainee
          $mail2 = new PHPMailer(true);
          $mail2->isSMTP();
          $mail2->Host = "smtp.gmail.com";
          $mail2->SMTPAuth = true;
          $mail2->Username = 'pjjumawan18@gmail.com';
          $mail2->Password = 'lfvjckzasfrpzxzf';
          $mail2->SMTPSecure = 'ssl';
  
          $body2 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Request</strong></p><br>
                    <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                    We are here to inform you that there is a filed complaint against you.<br>
                    To resolve this issue, we invite you to our Bararangay Hall on <b>'.$heardate.'</b><br>
                    Not attending the invitation will lead to further inconvenince against you.</p><br>
                    <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concecion Dos Management.</b></p>';
          
          $mail2->Port = 465;
  
          $mail2->setFrom('pjjumawan18@gmail.com');
  
          $mail2->addAddress($email3f);
  
          $mail2->isHTML(true);
  
          $mail2->Subject = 'Resident Complaint';
          $mail2->Body = $body2;
  
          $mail2->send();
          $notif = 1;
          
          $showModal = 12;
        }
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
  }

  if(isset($_REQUEST['openimage'])){
    $res_image = $_REQUEST['res_image'];
    $imgdecider = $_REQUEST['imgdecider'];
    if($imgdecider != 1){
      $comp_image = $_REQUEST['comp_image'];
      $idcomp4 = $_REQUEST['idcomp1'];
    }
    else{
      $resident_id2 = $_REQUEST['resident_id'];
      if($resident_id2 == 'none'){
        $type = $_REQUEST['type'];
        $firstname = ucwords(trim(filter_var($_REQUEST['firstname'],FILTER_SANITIZE_STRING)));
        $middlename = ucwords(trim(filter_var($_REQUEST['middlename'],FILTER_SANITIZE_STRING)));
        $lastname = ucwords(trim(filter_var($_REQUEST['lastname'],FILTER_SANITIZE_STRING)));
        $birthdate = $_REQUEST['birthdate'];
        $gender = $_REQUEST['gender'];
        $complainant_email = strtolower(trim(filter_var($_REQUEST['complainant_email'],FILTER_SANITIZE_EMAIL)));
        $contact = filter_var($_REQUEST['contact'],FILTER_SANITIZE_NUMBER_INT);
        $address = ucwords(trim(filter_var($_REQUEST['address'],FILTER_SANITIZE_STRING)));
      }
    }
    if($res_image == ''){
      $res_image = 'profile.jpg';
    }
    $showModal = 15;
  }

  if(isset($_REQUEST['opencomplaint'])){
    $idcomp3 = $_REQUEST['opencomplaint'];
    $showModal = 16;
  }

  if(isset($_REQUEST['savesettle'])){
    $idcomp3 = $_REQUEST['savesettle'];
    $result = ucfirst(trim(filter_var($_REQUEST['result'],FILTER_SANITIZE_STRING)));
    $h_id = $_REQUEST['hid'];
    $statusf = 3;
    try{
      $query8 = $con->prepare("UPDATE complaint_tbl SET status=:status, latest_hearing=:latest_hearing WHERE id = :id");
      # execute query
      if($query8->execute( [':status' => $statusf, ':latest_hearing' => $date_reg, ':id' => $idcomp3])){
        $statusf1 = 1;
        $query9 = $con->prepare("UPDATE hearing_tbl SET status=:status, result=:result WHERE h_id=:h_id");
        # execute query
        if($query9->execute([':status' => $statusf1, ':result' => $result, ':h_id' => $h_id])){
          $settlecheck = 0;
          $showModal = 18;
        }
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
    
  }

  if(isset($_REQUEST['notsettle'])){
    $idcomp3 = $_REQUEST['notsettle'];
    $h_id = $_REQUEST['hid'];
    $result = ucfirst(trim(filter_var($_REQUEST['result'],FILTER_SANITIZE_STRING)));
    $showModal = 19;
  }

  if(isset($_REQUEST['savefollowup'])){
    $idcomp3 = $_REQUEST['savefollowup'];
    $h_id = $_REQUEST['hid'];
    $result = ucfirst(trim(filter_var($_REQUEST['result'],FILTER_SANITIZE_STRING)));
    $hearing1 = $_REQUEST['hearing'];
    $currdate1 = date('Y-m-d H:i:s');
    $incharge2 = 'Barangay Chairman';
    $alloweddate1 = date('Y-m-d\TH:i:s', strtotime($currdate1 . ' + 1 day'));
    if($hearing1 < $alloweddate1){
      $errorMSG5 = 'Follow-up hearing must be at least 1 day prior today.';
      $showModal = 19;
    }
    else{
      try{
        $query11 = $con->prepare("UPDATE hearing_tbl SET result=:result WHERE h_id=:h_id");
        # execute query
        $query11->execute([':result' => $result, ':h_id' => $h_id]);
        $status3 = 0;
        $type1 = 1;
        $query10 = $con->prepare("INSERT INTO hearing_tbl (id, hearing_date, status, type, incharge)
        VALUES (:id, :hearing_date, :status, :type, :incharge)");
        $query10->execute(
          [
          ':id' => $idcomp3,
          ':hearing_date' => $hearing1,
          ':status' => $status3,
          ':type' => $type1,
          ':incharge' => $incharge2
          ]
        );
        $sql19 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp3'";
        $d19 =  $con->query($sql19);
        foreach($d19 as $data19){
          $complainant_num = ('+63'.$data19['complainant_num']);
          $email5f = $data19['complainant_email'];
          $complaiee_num = ('+63'.$data19['complainee_num']);
          $email6f = $data19['complainee_email'];
        }
        $heardate3 = date_create($hearing1);
        $heardate3 = date_format($heardate3, 'F d, Y h:i:s a');
        # sending email to both parties
        # sending email of approval to complainant
        $mail5 = new PHPMailer(true);
        $mail5->isSMTP();
        $mail5->Host = "smtp.gmail.com";
        $mail5->SMTPAuth = true;
        $mail5->Username = 'pjjumawan18@gmail.com';
        $mail5->Password = 'lfvjckzasfrpzxzf';
        $mail5->SMTPSecure = 'ssl';
        $body5 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Request</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                  We are here to inform you the date of your follow-up hearing which is on <b>'.$heardate3.'</b> and at the same venue.<br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concepcion Dos Management.</b></p>';
        
        $mail5->Port = 465;

        $mail5->setFrom('pjjumawan18@gmail.com');

        $mail5->addAddress($email5f);

        $mail5->isHTML(true);

        $mail5->Subject = 'Resident Complaint';
        $mail5->Body = $body5;

        $mail5->send();

        # sending email of approval to complainee
        $mail6 = new PHPMailer(true);
        $mail6->isSMTP();
        $mail6->Host = "smtp.gmail.com";
        $mail6->SMTPAuth = true;
        $mail6->Username = 'pjjumawan18@gmail.com';
        $mail6->Password = 'lfvjckzasfrpzxzf';
        $mail6->SMTPSecure = 'ssl';

        $body6 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Complaint Request</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                  We are here to inform you the date of your follow-up hearing which is on <b>'.$heardate3.'</b> and at the same venue.<br>
                  Not attending the invitation will lead to further inconvenince against you.</p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concepcion Dos Management.</b></p>';
        
        $mail6->Port = 465;

        $mail6->setFrom('pjjumawan18@gmail.com');

        $mail6->addAddress($email6f);

        $mail6->isHTML(true);

        $mail6->Subject = 'Resident Complaint';
        $mail6->Body = $body6;

        $mail6->send();
        $settlecheck = 1;
        $showModal = 18;
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
      
    }
    
  }
  
  if(isset($_REQUEST['saveblotter'])){
    $idcomp3 = $_REQUEST['saveblotter'];
    $h_id = $_REQUEST['hid'];
    $result = ucfirst(trim(filter_var($_REQUEST['result'],FILTER_SANITIZE_STRING)));
    $showModal = 20;
  }
 
  if(isset($_REQUEST['confirmblotter'])){
    $idcomp3 = $_REQUEST['confirmblotter'];
    $result = $_REQUEST['result'];
    $h_id = $_REQUEST['hid'];
    $status5 = 4;
    $qry2 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp3'";
    $dqry2 =  $con->query($qry2);
    foreach($dqry2 as $dataqry2){
      $complainant_num = ('+63'.$dataqry2['complainant_num']);
      $email7f = $dataqry2['complainant_email'];
      $complaiee_num = ('+63'.$dataqry2['complainee_num']);
      $email8f = $dataqry2['complainee_email'];
    }
    try{
      $qry1 = $con->prepare("UPDATE complaint_tbl SET status=:status, latest_hearing=:latest_hearing WHERE id = :id");
      # execute query
      if($qry1->execute( [':status' => $status5, ':latest_hearing' => $date_reg, ':id' => $idcomp3])){
        $qry4 = $con->prepare("UPDATE hearing_tbl SET result=:result WHERE h_id = :h_id");
        # execute query
        $qry4->execute( [':result' => $result, ':h_id' => $h_id]);

        $heardate1 = date_create($date_reg);
        $heardate1 = date_format($heardate1, 'F d, Y h:i:s a');
        # sending email to both parties
        # sending email of approval to complainant
        $mail7 = new PHPMailer(true);
        $mail7->isSMTP();
        $mail7->Host = "smtp.gmail.com";
        $mail7->SMTPAuth = true;
        $mail7->Username = 'pjjumawan18@gmail.com';
        $mail7->Password = 'lfvjckzasfrpzxzf';
        $mail7->SMTPSecure = 'ssl';
        $body7 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Blotter Record</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                  We are here to inform you that your complaint has been save as a blotter against the complainee on <b>'.$heardate1.'</b>.<br></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concepcion Dos Management.</b></p>';
        
        $mail7->Port = 465;

        $mail7->setFrom('pjjumawan18@gmail.com');

        $mail7->addAddress($email7f);

        $mail7->isHTML(true);

        $mail7->Subject = 'Resident Complaint';
        $mail7->Body = $body7;

        $mail7->send();

        # sending email of approval to complainee
        $mail8 = new PHPMailer(true);
        $mail8->isSMTP();
        $mail8->Host = "smtp.gmail.com";
        $mail8->SMTPAuth = true;
        $mail8->Username = 'pjjumawan18@gmail.com';
        $mail8->Password = 'lfvjckzasfrpzxzf';
        $mail8->SMTPSecure = 'ssl';

        $body8 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Blotter Record</strong></p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
                  We are here to inform you that a blotter record against you has been recorded in our barangay on <b>'.$heardate1.'</b>.</p><br>
                  <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concepcion Dos Management.</b></p>';
        
        $mail8->Port = 465;

        $mail8->setFrom('pjjumawan18@gmail.com');

        $mail8->addAddress($email8f);

        $mail8->isHTML(true);

        $mail8->Subject = 'Resident Complaint';
        $mail8->Body = $body8;

        $mail8->send();
        
        $showModal = 25;
      }
    }
    catch(PDOException $e){
      $pdoError = $e->getMesage();
    }
  }

  if(isset($_REQUEST['reschedhearing'])){
    $idcomp3 = $_REQUEST['reschedhearing'];
    $qry3 = "SELECT hearing_date FROM hearing_tbl WHERE hearing_date > '$date_reg'";
    $dqry3 =  $con->query($qry3);
    foreach($dqry3 as $dataqry3){
      $latesthdate = $dataqry3['hearing_date'];
    }
    $showModal = 21;
  }

  if(isset($_REQUEST['resulthearing'])){
    $idcomp3 = $_REQUEST['resulthearing'];
    $h_id = $_REQUEST['h_id'];
    $showModal = 22;
  }

  if(isset($_REQUEST['viewresult'])){
    $idcomp3 = $_REQUEST['compdid'];
    $h_id = $_REQUEST['viewresult'];
    $showModal = 23;
  }

  if(isset($_REQUEST['hearingresched'])){
    $idcomp3 = $_REQUEST['hearingresched'];
    $latesthdate = $_REQUEST['latesthdate'];
    // $heardate4 = date_create($latesthdate);
    // $heardate4 = date_format($heardate4, 'F d, Y h:i:s a');
    $hearingdate = $_REQUEST['hearingdate'];
    $alloweddate3 = date('Y-m-d\TH:i:s', strtotime($date_reg));
    if($alloweddate3 > $hearingdate){
      $errorMSG6 = 'Past date is not allowed.';
      $showModal = 21;
    }
    else{
      $sql20 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp3'";
      $d20 =  $con->query($sql20);
      foreach($d20 as $data20){
        $complainant_num = ('+63'.$data20['complainant_num']);
        $emailc1f = $data20['complainant_email'];
        $complaiee_num = ('+63'.$data20['complainee_num']);
        $emailc2f = $data20['complainee_email'];
      }
      try{
        $qry3 = $con->prepare("UPDATE hearing_tbl SET hearing_date=:hearing_date WHERE hearing_date=:leatest_hear");
        # execute query
        if($qry3->execute( [':hearing_date' => $hearingdate, ':leatest_hear' => $latesthdate])){
          
          $heardate5 = date_create($hearingdate);
          $heardate5 = date_format($heardate5, 'F d, Y h:i:s a');
          # sending email to both parties
          # sending email of approval to complainant
          $mailc1 = new PHPMailer(true);
          $mailc1->isSMTP();
          $mailc1->Host = "smtp.gmail.com";
          $mailc1->SMTPAuth = true;
          $mailc1->Username = 'pjjumawan18@gmail.com';
          $mailc1->Password = 'lfvjckzasfrpzxzf';
          $mailc1->SMTPSecure = 'ssl';
          $bodyc1 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Hearing Information</strong></p><br>
          <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
          We are here to inform you the date of your follow-up hearing has been rescheduled to <b>'.$heardate5.'</b>.<br>
          <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concepcion Dos Management.</b></p>';
          
          $mailc1->Port = 465;

          $mailc1->setFrom('pjjumawan18@gmail.com');

          $mailc1->addAddress($emailc1f);

          $mailc1->isHTML(true);

          $mailc1->Subject = 'Resident Complaint';
          $mailc1->Body = $bodyc1;

          $mailc1->send();

          # sending email of approval to complainee
          $mailc2 = new PHPMailer(true);
          $mailc2->isSMTP();
          $mailc2->Host = "smtp.gmail.com";
          $mailc2->SMTPAuth = true;
          $mailc2->Username = 'pjjumawan18@gmail.com';
          $mailc2->Password = 'lfvjckzasfrpzxzf';
          $mailc2->SMTPSecure = 'ssl';

          $bodyc2 = '<p style="font-size: 2em; margin: 0 0 0 0;"><strong>Hearing Information</strong></p><br>
          <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">Hello Citizen,<br>
          We are here to inform you the date of your follow-up hearing has been rescheduled to <b>'.$heardate5.'</b>.<br>
          <p style="font-size: 1.5em; line-height: 1.6; margin: 0 0 0 0;">From: <b>Concepcion Dos Management.</b></p>';
          
          $mailc2->Port = 465;

          $mailc2->setFrom('pjjumawan18@gmail.com');

          $mailc2->addAddress($emailc2f);

          $mailc2->isHTML(true);

          $mailc2->Subject = 'Resident Complaint';
          $mailc2->Body = $bodyc2;

          $mailc2->send();

          $showModal = 1;

        }
      }
      catch(PDOException $e){
        $pdoError = $e->getMesage();
      }
    }
  }

  if(isset($_REQUEST['printtransaction'])){
    $_SESSION['user']['transprint'] = 1;
    $typeprint = $_REQUEST['printtransaction'];
    if($typeprint == 1){
      $_SESSION['user']['inchargespan'] = $_REQUEST['inchargespan'];
      header("Location: print_complaints.php");
    }
  }

  /** reloading page */
  if(isset($_REQUEST['confirmresident'])){
    header("Location: complaints.php");
  }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaints</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://fengyuanchen.github.io/cropperjs/css/cropper.css" />
    <script src="https://fengyuanchen.github.io/cropperjs/js/cropper.js"></script>
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

      #display-image{
        height: 225px;
        background-position: center;
        background-size: cover;
      }

      #display-image2{
        height: 225px;
        background-position: center;
        background-size: cover;
      }

      #display-image3{
        height: 225px;
        background-position: center;
        background-size: cover;
      }

      #display-image4{
        height: 225px;
        background-position: center;
        background-size: cover;
      }

    </style>
    <?php
    if($checker1 == 1){
      echo '<script>
      $(document).ready(function(){
          $("#outcomplainant").on("shown.bs.modal", function(){
              $(this).find("#complainant_email").focus();
          });
      });
      </script>';
    }
    if($checker1 == 2){
      echo '<script>
      $(document).ready(function(){
          $("#rescomplaineemodal").on("shown.bs.modal", function(){
              $(this).find("#hearing2").focus();
          });
      });
      </script>';
    }
    ?>
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
      <a class="navbar-brand fw-bold text-uppercase" href="#"><img src="images/logo.jpg" alt="" width="45" height="45">&nbsp;Concepcion Dos</a>
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
          <div class="collapse show" id="reports">
            <div class="card card-body  bg-danger">
              <ul class="navbar-nav p-0">
                <li>
                  <a href="complaints.php" class="nav-link px-3 bg-white active text-dark">
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
      <div class="row me-3">
        <div class="col-md-12 fw-bold fs-3 text-danger">Complaints</div>
      </div>
      <div class="row m-1">
        <div class="col-md-12">
          <p class="text-end"><button type="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#genreportmodal"><i class="bi bi-printer"></i>&nbsp;Generate Report</button></p>
        </div>
      </div>
      <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
        <div class="row m-1">
          <div class="col-md-12">
            <p class="text-start">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#choosetype">File Complaint</button>
              <button type="button" class="btn btn-secondary shadow-none mx-1 position-relative" data-bs-toggle="modal" data-bs-target="#openrequestlist">
                <i class="bi bi-person-check"></i>
                Pending Complaints
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  <span><?=$cnt3?></span>
                  <span class="visually-hidden">New alerts</span>
                </span>
              </button>
            </p>
          </div>
        </div>
        <div class="row mb-4">
          <div class="col-md-5">
            <i class="bi bi-table"></i>&nbsp;Complaints Data Table
          </div>
        </div>
        <div class="row m-1">
          <div class="col-md-12">
            <div class="row">
              <div class="col-md-12">
                <table id="transaction" class="table table-striped" style="width:100%">
                  <thead>
                    <tr>
                      <th>Complainant</th>
                      <th>Complainee</th>
                      <th>Status</th>
                      <th>Preferences</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $sql2 = "SELECT * FROM complaint_tbl WHERE status = '2'";
                      $d2 =  $con->query($sql2);
                      foreach($d2 as $data2){
                    ?>
                    <tr>
                      <th><?php echo $data2['complainant_lname'];?>, <?php echo $data2['complainant_fname'];?> <?php echo substr($data2['complainant_mname'], 0, 1);?>.</th>
                      <th><?php echo $data2['complainee_lname'];?>, <?php echo $data2['complainee_fname'];?> <?php echo substr($data2['complainee_mname'], 0, 1);?>.</th>
                      
                      <?php
                      if($data2['status'] == 1){
                        echo '<th>Pending</th>';
                      }
                      else{
                        echo '<th>On-going</th>';
                      }
                      ?>
                      
                      <form action="complaints.php" method="post">
                      <th><button type="submit" class="btn btn-link" value="<?=$data2['id']?>" name="opencomplaint">Details</button></th>
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
        </div>
      </div>

      <!-- choosing type pop-up modal -->
      <div class="modal fade" id="choosetype" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="staticBackdropLabel">Filing Complaint</h5>
            </div>
            <div class="modal-body">
              <div class="row justify-content-center">
                <div class="row">
                  <div class="col-md-12">
                    <p class="text-center fs-3 fw-bold">Complainant</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <img src="../resident/images/adult.jpg" class="img-thumbnail rounded mx-auto d-block mb-1" alt="...">
                    <p class="text-center"><button type="button" class="btn btn-primary" data-bs-target="#rescomplainant" data-bs-toggle="modal" data-bs-dismiss="modal">With Resident ID</button></p>
                  </div>
                  <div class="col-md-6">
                    <img src="../resident/images/adult.jpg" class="img-thumbnail rounded mx-auto d-block mb-1" alt="...">
                    <p class="text-center"><button type="button" class="btn btn-primary" data-bs-target="#outcomplainant" data-bs-toggle="modal" data-bs-dismiss="modal">No Resident ID</button></p>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
      </div>
      <!-- choosing type pop-up modal -->

      <!-- add resident complaint resident pop-up modal -->
      <div class="modal fade" id="rescomplainant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="rescomplainant">Filing Complaint</h5>
            </div>
            <div class="modal-body">
              <form action="complaints.php" method="post">
                <?php
                if(isset($errorMSG)){
                  echo '<div class="alert alert-warning" role="alert">
                          '.$errorMSG.'
                        </div>';
                }
                ?>
                <div class="row">
                  <div class="col-md-12">
                    <label for="resident_id" class="form-label">Complainant's Resident ID</label>
                    <input type="text" class="form-control" id="resident_id" name="resident_id" placeholder="Enter here..." required>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="complainantver">Submit</button>
              <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#choosetype">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- add complaint pop-up modal end -->

      <!-- adding confirmation pop-up modal -->
      <div class="modal fade" id="addconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Request</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">New complaint has been filed.</p>
            </div>
            <form action="complaints.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- add outsider complaint 1/2 pop-up modal -->
      <div class="modal fade" id="outcomplainant" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="outcomplainant">Complaint Form (1/2)</h5>
            </div>
            <div class="modal-body">
              <?php
              if(isset($errorMSG1)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG1.'
                      </div>';
              }
              ?>
              <form action="complaints.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center fs-4 fw-bold">Complainant Information</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="typ" class="form-label">Classification</label>
                  <input type="text" class="form-control" id="typ" value="Outsider" disabled>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="fname" class="form-label">Firstname</label>
                  <?php
                  if($reload1 == 1){
                    echo '<input type="text" class="form-control" id="fname" name="firstname" value="'.$firstname.'" required>';
                  }
                  else{
                    echo '<input type="text" class="form-control" id="fname" name="firstname" placeholder="Enter here..." required>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="mname" class="form-label">Middlename</label>
                  <?php
                  if($reload1 == 1){
                    echo '<input type="text" class="form-control" id="mname" name="middlename" value="'.$middlename.'" required>';
                  }
                  else{
                    echo '<input type="text" class="form-control" id="mname" name="middlename" placeholder="Enter here..." required>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="lname" class="form-label">Lastname</label>
                  <?php
                  if($reload1 == 1){
                    echo '<input type="text" class="form-control" id="lname" name="lastname" value="'.$lastname.'" required>';
                  }
                  else{
                    echo '<input type="text" class="form-control" id="lname" name="lastname" placeholder="Enter here..." required>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="birthdate" class="form-label">Birthdate</label>
                  <?php
                  if($reload1 == 1){
                    echo '<input type="date" class="form-select" id="birthdate" name="birthdate" value="'.$birthdate.'" min="1890-01-01" max="'.$date.'" required>';
                  }
                  else{
                    echo '<input type="date" class="form-select" id="birthdate" name="birthdate" min="1890-01-01" max="'.$date.'" required>';
                  }
                  ?>
                </div>
                <div class="col-md-6">
                  <label for="gder" class="form-label">Gender</label>
                  <?php
                  if($reload1 == 1){
                    if($gender == 'Male'){
                      echo'<select class="form-select" aria-label="Default select example" name="gender" id="gder" required>
                            <option value="Male" selected>Male</option>
                            <option value="Female">Female</option>
                          </select>';
                    }
                    else{
                      echo'<select class="form-select" aria-label="Default select example" name="gender" id="gder" required>
                            <option value="Male">Male</option>
                            <option value="Female" selected>Female</option>
                          </select>';
                    }
                  }
                  else{
                    echo '<select class="form-select" aria-label="Default select example" name="gender" id="gder" required>
                          <option selected value="none" hidden>Select</option>
                          <option value="Male">Male</option>
                          <option value="Female">Female</option>
                        </select>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="address" class="form-label">Address</label>
                  <?php
                  if($reload1 == 1){
                    echo '<input type="text" class="form-control" id="adds" name="address" value="'.$address.'" required>';
                  }
                  else{
                    echo '<input type="text" class="form-control" id="adds" name="address" placeholder="Enter here..." required>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="contact" class="form-label">Contact Number (10-digit number)</label>
                    <div class="input-group">
                      <span class="input-group-text">+63</span>
                      <?php
                      if($reload1 == 1){
                        echo '<input type="tel" class="form-control" id="contact" name="contact" value="'.$contact.'" pattern="[9]{1}[0-9]{9}" required>';
                      }
                      else{
                        echo '<input type="tel" class="form-control" id="contact" name="contact" pattern="[9]{1}[0-9]{9}" required>';
                      }
                      ?>
                    </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="col-md-12">
                  <label for="complainant_email" class="form-label">Email</label>
                  <?php
                  if($reload1 == 1){
                    echo '<input type="email" class="form-control" id="complainant_email" name="complainant_email" value="'.$complainant_email.'" required>';
                  }
                  else{
                    echo '<input type="email" class="form-control" id="complainant_email" name="complainant_email" placeholder="Enter here..." required>';
                  }
                  ?>
                </div>
                </div>
              </div>
              <input type="hidden" name="type" value="Outsider">
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="filecomplaint">Next Form</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- add complaint pop-up modal end -->

      <!-- add resident complainee pop-up modal -->
      <div class="modal fade" id="rescomplainee" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="rescomplainant">Filing Complaint</h5>
            </div>
            <div class="modal-body">
              <form action="complaints.php" method="post">
                <?php
                if(isset($errorMSG)){
                  echo '<div class="alert alert-warning" role="alert">
                          '.$errorMSG.'
                        </div>';
                }
                ?>
                <input type="hidden" name="resident_id" value="<?=$resident_id?>">
                <input type="hidden" name="type" value="<?=$type?>">
                <!-- <input type="hidden" name="type4" value="<?=$type4?>"> -->
                <?php
                if($resident_id == 'none'){
                  echo '<input type="hidden" name="firstname" value="'.$firstname.'">
                  <input type="hidden" name="middlename" value="'.$middlename.'">
                  <input type="hidden" name="lastname" value="'.$lastname.'">
                  <input type="hidden" name="complainant_email" value="'.$complainant_email.'">
                  <input type="hidden" name="birthdate" value="'.$birthdate.'">
                  <input type="hidden" name="gender" value="'.$gender.'">
                  <input type="hidden" name="contact" value="'.$contact.'">
                  <input type="hidden" name="address" value="'.$address.'">';
                }
                ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="row border border-dark shadow-lg p-3 mb-5 bg-body rounded">
                      <div class="row mb-4">
                        <div class="col-md-5">
                          <i class="bi bi-table"></i>&nbsp;Potential Resident Data Table
                        </div>
                      </div>
                      <div class="col-md-12">
                        <table id="hmembers" class="table table-striped" style="width:100%">
                          <thead>
                            <tr>
                              <th>Profile</th>
                              <th>Name</th>
                              <th>Old House #</th>
                              <th>New House #</th>
                              <th>Street</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                              if($resident_id == 'none'){
                                $sql15 = "SELECT * FROM penresident WHERE status = 'Registered'";
                              }
                              else{
                                $sql15 = "SELECT * FROM penresident WHERE status = 'Registered' AND resident_id != '$resident_id'";
                              }
                              $d15 =  $con->query($sql15);
                              foreach($d15 as $data15){
                                $fullname = $data15['firstname'] . ' ' . $data15['middlename'] . ' ' . $data15['lastname'];
                                $res_image2 = $data15['profile_id'];
                            ?>
                            <tr>
                              <input type="hidden" name="res_image" value="<?=$res_image2?>">
                              <input type="hidden" name="imgdecider" value="1">
                              <th><button type="submit" class="btn btn-link" name="openimage">View</button></th>
                              <th><?php echo $fullname;?></th>
                              <th><?php echo $data15['old'];?></th>
                              <th><?php echo $data15['new'];?></th>
                              <th><?php echo $data15['street'];?> St.</th>
                              <input type="hidden" name="resident_id2" value="<?=$data15['resident_id']?>">
                              <th><button type="submit" class="btn btn-primary" name="complaineever">Select</button></th>
                            </tr>
                            <?php
                              }
                            ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary" name="confirmresident">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- add resident complainee pop-up modal end -->

      <!-- add resident complainee pop-up modal -->
      <div class="modal fade" id="rescomplaineemodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="rescomplaineemodal">Complaint Form</h5>
            </div>
            <div class="modal-body">
              <?php
              if(isset($errorMSG2)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG2.'
                      </div>';
              }
              ?>
              <form action="complaints.php" method="post"  enctype="multipart/form-data">
              <input type="hidden" name="resident_id" value="<?=$resident_id?>">
              <input type="hidden" name="resident_id2" value="<?=$resident_id2?>">
              <input type="hidden" name="type" value="<?=$type?>">
              <!-- <input type="hidden" name="type4" value="'.$type4.'"> -->
              <?php
              if($resident_id == 'none'){
                echo '<input type="hidden" name="firstname" value="'.$firstname.'">
                <input type="hidden" name="middlename" value="'.$middlename.'">
                <input type="hidden" name="lastname" value="'.$lastname.'">
                <input type="hidden" name="birthdate" value="'.$birthdate.'">
                <input type="hidden" name="gender" value="'.$gender.'">
                <input type="hidden" name="complainant_email" value="'.$complainant_email.'">
                <input type="hidden" name="contact" value="'.$contact.'">
                <input type="hidden" name="address" value="'.$address.'">';
              }
              ?>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-12 p-3 shadow bg-body rounded">
                    <div class="row">
                      <div class="col-md-12">
                        <p class="text-center fs-4">Complaint Details</p>
                      </div>
                    </div>
                    <div class="row">
                      <?php
                      if(isset($comptype)){
                        if($comptype == 'Adultery'){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery" selected>Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Child Abuse"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse" selected>Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Sexual Abuse"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse" selected>Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Road Accident"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident" selected>Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Crime/Harm"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm" selected>Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Neighborhood Concern"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern" selected>Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Property Concern"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern" selected>Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        elseif($comptype == "Business Concern"){
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern" selected>Business Concern</option>
                            <option value="Others">Others</option>
                          </select>
                        </div>';
                        }
                        else{
                          echo '<div class="col-md-12">
                          <label for="comptype" class="form-label">Complaint Type</label>
                          <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                            <option value="Adultery">Adultery</option>
                            <option value="Child Abuse">Child Abuse</option>
                            <option value="Sexual Abuse">Sexual Abuse</option>
                            <option value="Road Accident">Road Accident</option>
                            <option value="Crime/Harm">Crime/Harm</option>
                            <option value="Neighborhood Concern">Neighborhood Concern</option>
                            <option value="Property Concern">Property Concern</option>
                            <option value="Business Concern">Business Concern</option>
                            <option value="Others" selected>Others</option>
                          </select>
                        </div>';
                        }
                      }
                      else{
                        echo '<div class="col-md-12">
                        <label for="comptype" class="form-label">Complaint Type</label>
                        <select class="form-select" aria-label="Default select example" name="comptype" id="comptype" required>
                          <option value="" hidden>Select</option>
                          <option value="Adultery">Adultery</option>
                          <option value="Child Abuse">Child Abuse</option>
                          <option value="Sexual Abuse">Sexual Abuse</option>
                          <option value="Road Accident">Road Accident</option>
                          <option value="Crime/Harm">Crime/Harm</option>
                          <option value="Neighborhood Concern">Neighborhood Concern</option>
                          <option value="Property Concern">Property Concern</option>
                          <option value="Business Concern">Business Concern</option>
                          <option value="Others">Others</option>
                        </select>
                      </div>';
                      }
                      ?>
                    </div>
                    <div class="row">
                      <?php
                      if(isset($statement)){
                        echo '<div class="col-md-12">
                        <div class="form-group green-border-focus">
                          <label for="statement">Statement</label>
                          <textarea class="form-control" name="statement" id="statement" rows="5" required>'.$statement.'</textarea>
                        </div>
                      </div>';
                      }
                      else{
                        echo '<div class="col-md-12">
                        <div class="form-group green-border-focus">
                          <label for="statement">Statement</label>
                          <textarea class="form-control" name="statement" id="statement" rows="5" required></textarea>
                        </div>
                      </div>';
                      }
                      ?>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label for="image-input3" class="form-label">Face Image (Person-to-Complain - If available)</label>
                        <input type="file" class="form-control" id="image-input3" name="face_image" accept=".jpg, .jpeg, .png">
                      </div>
                    </div>
                    <div id="display-image3" class="container-fluid border border-dark shadow-lg p-3 mt-1 mb-2 bg-body rounded">

                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <label for="image-input4">Photo Evidence (If available)</label>
                        <input type="file" class="form-control" id="image-input4" name="image"  accept=".jpg, .jpeg, .png">
                      </div>
                    </div>
                    <div id="display-image4" class="container-fluid border border-dark shadow-lg p-3 mt-1 mb-2 bg-body rounded">

                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <?php
                        if(isset($errorMSG4)){
                          echo '<div class="alert alert-warning" role="alert">
                                  '.$errorMSG4.'
                                </div>';
                        }
                        ?>
                        <label for="hearing" class="form-label">Hearing Date</label>
                        <input type="datetime-local" class="form-select" id="hearing2" name="hearing" required>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="rescomplaineeconfirm">File</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- add resident complaint pop-up modal end -->

      <!-- pending requests pop-up modal -->
      <div class="modal fade" id="openrequestlist" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable">
              <div class="modal-content">
                  <div class="modal-header p-3 mb-2 bg-secondary text-white">
                      <h5 class="modal-title" id="openrequestlist">Pending Complaints</h5>
                  </div>
                  <div class="modal-body">
                      <?php
                      $sql4 = "SELECT * FROM complaint_tbl WHERE status = '1' ORDER BY date_complaint";
                      $d4 =  $con->query($sql4);
                      foreach($d4 as $data4){
                          $compdate = date_create($data4['date_complaint']);
                          $compdate = date_format($compdate, 'F d, Y h:i:s a');
                      ?>
                      <div class="row border shadow-lg pe-0 ps-3 pt-3 pb-3 ms-1 me-1 mb-3 bg-body rounded">
                          <div class="row">
                              <div class="col-md-12">
                                  <p class="text-start fs-5">Report Type: <b><?=$data4['complaint_type'];?></b></p>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-12">
                                  <p class="text-start fs-5">Complainant: <b><?php echo $data4['complainant_lname'];?>, <?php echo $data4['complainant_fname'];?> <?php echo substr($data4['complainant_mname'], 0, 1);?>.</b></p>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-12">
                                  <p class="text-start fs-5">Accused: <b><?php echo $data4['complainee_lname'];?>, <?php echo $data4['complainee_fname'];?> <?php echo substr($data4['complainee_mname'], 0, 1);?>.</b></p>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-12">
                                  <p class="text-start fs-5">Date Filed: <b><?=$compdate;?></b></p>
                              </div>
                          </div>
                          <div class="row">
                              <div class="col-md-8">
                              </div>
                              <div class="col-md-4">
                              <form action="complaints.php" method="post">
                                  <p class="text-end fs-5"><button type="submit" class="btn btn-link" name="pendingcomplaint" value="<?=$data4['id'];?>">View Details</button></p>
                              </form>
                              </div>
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
      
      <!-- pending request details pop-up modal -->
      <div class="modal fade" id="requestcomplaint" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="requestcomplaint">Pending Complaints</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                  $sql7 = "SELECT * FROM complaint_tbl WHERE id = '$compid'";
                  $d7 =  $con->query($sql7);
                  foreach($d7 as $data7){
                    $compdate2 = date_create($data7['date_complaint']);
                    $compdate2 = date_format($compdate2, 'F d, Y h:i:s a');
                    $date3 = date('Y-m-d');
                    $datetime5 = new DateTime($date3);
                    $datetime6 = new DateTime($data7['complainant_bdate']);
                    $difference3 = $datetime6->diff($datetime5);
                    $age4 = ($difference3->y);
                ?>
                <div class="row border shadow-lg p-3 ms-1 me-1 mb-3 bg-body rounded">
                  <div class="row">
                    <div class="col-md-6">
                      <label for="comptype" class="form-label">Report Type</label>
                      <p id="comptype" class="text-start fs-5"><b><?=$data7['complaint_type'];?></b></p>
                    </div>
                    <div class="col-md-6">
                      <label for="datef" class="form-label">Date Filed</label>
                      <p id="datef" class="text-start fs-5"><b><?=$compdate2;?></b></p>
                    </div>
                  </div>
                  <div class="row row-cols-auto">
                    <div class="col">
                      <label for="comnant" class="form-label">Complainant</label>
                      <p id="comnant" class="text-start fs-5"><b><?php echo $data7['complainant_fname'];?> <?php echo $data7['complainant_mname'];?> <?php echo $data7['complainant_lname'];?> (<?php echo $data7['complainant_type'];?>)</b></p>
                    </div>
                  </div>
                  <div class="row row-cols-auto">
                    <div class="col">
                      <label for="gender" class="form-label">Gender</label>
                      <p id="gender" class="text-start fs-5"><b><?=$data7['complainant_gender'];?></b></p>
                    </div>
                    <div class="col">
                      <label for="age" class="form-label">Age</label>
                      <p id="age" class="text-start fs-5"><b><?php echo $age4;?></b></p>
                    </div>
                    <div class="col">
                      <label for="comnannum" class="form-label">Contact #</label>
                      <p id="comnannum" class="text-start fs-5"><b>0<?php echo $data7['complainant_num'];?></b></p>
                    </div>
                    <div class="col">
                      <label for="comnanmail" class="form-label">Email</label>
                      <p id="comnanmail" class="text-start fs-5"><b><?php echo $data7['complainant_email'];?></b></p>
                    </div>
                  </div>
                  <div class="row row-cols-auto">
                    <div class="col">
                      <label for="comnee" class="form-label">Complainee</label>
                      <p id="comnee" class="text-start fs-5"><b><?php echo $data7['complainee_fname'];?> <?php echo $data7['complainee_mname'];?> <?php echo $data7['complainee_lname'];?></b></p>
                    </div>
                    <div class="col">
                    <form action="complaints.php" method="post">
                      <label for="btns" class="form-label">...</label>
                      <p id="btns"><button type="submit" class="btn btn-primary" name="identifycomp" value="<?=$data7['id'];?>">Identify Complainee</button></p>
                    </div>
                  </div>
                  <br>
                  <div class="row">
                    <div class="col-md-12">
                      <label for="statem" class="form-label">Statement</label>
                      <p id="statem" style="word-break: break-word;" class="text-start fs-5" ><b><?=$data7['statement']?>.</b></p>
                    </div>
                  </div>
                  <div class="row justify-content-md-center">
                    <?php
                    $ideve = $data7['id'];
                    $sql5 = "SELECT * FROM evidence_tbl WHERE id = $ideve";
                    $d5 =  $con->query($sql5);
                    foreach($d5 as $data5){
                    ?>
                    <?php
                      if($data5['evidence_image'] == 'none'){
                        echo '<div class="col-md-auto">
                                <label for="eveimage" class="form-label">Evidence Image</label>
                                <p id="eveimage" class="text-start fs-5"><b>No submitted evidence image.</b></p>
                              </div>';
                      }
                      else{
                        echo '<div class="col-md-auto">
                                <label for="eveimage" class="form-label">Evidence Image</label>
                                <p id="eveimage" class="text-center fs-5"><img src="images/'.$data5['evidence_image'].'" class="img-fluid border border-dark" alt="..."></p>
                              </div>';
                      }
                    ?>
                  </div>
                    <?php
                    }
                    ?>
                </div>
                <?php
                  }
                ?>
            </div>
            
            <div class="modal-footer">
              <input type="hidden" name="deny1" value="1">
              <button type="submit" class="btn btn-danger"  name="denycomplaint" value="<?=$compid?>">Deny</button>
              <!-- <button type="submit" class="btn btn-secondary" name="confirmresident">Close</button> -->
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- pending request details pop-up modal end -->

      <!-- delete confirmation pop-up modal -->
      <div class="modal fade" id="deletecompmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Request</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Are you sure to deny this complaint?</p>
            </div>
            <form action="complaints.php" method="post">
            <input type="hidden" name="deny1" value="<?=$deny1?>">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="deletecomp" value="<?=$idcomp?>">Yes</button>
              <button type="submit" class="btn btn-danger" name="pendingcomplaint" value="<?=$idcomp?>">No</button>
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
                <h5 class="modal-title">Complaint Request</h5>
              </div>
              <div class="modal-body">
                <?php
                if($notif == 1){
                  echo '<p class="text-center fs-4">Hearing date has been sent to both residents.</p>';
                }
                else{
                  echo '<p class="text-center fs-4">Complaint has been successfully denied.</p>';
                }
                ?>
              </div>
              <form action="complaints.php" method="post">
              <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="confirmresident">Ok</button>
              </div>
              </form>
          </div>
        </div>
      </div>
      <!-- confirmatiom of deleting resident pop-up modal end -->

      <!-- identifying complainee pop-up modal -->
      <div class="modal fade" id="identifymodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-fullscreen">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Potential Residents</h5>
            </div>
            <div class="modal-body">
              <?php
                $sql12 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp1'";
                $d12 =  $con->query($sql12);
                foreach($d12 as $data12){
                   $fname = $data12['complainee_fname'];
                   $mname = $data12['complainee_mname'];
                   $lname = $data12['complainee_lname'];
                   $cont2 = $data12['complainee_num'];
                   $complainee_hnum = $data12['complainee_hnum'];
                   $complainee_gender = $data12['complainee_gender'];
                   $complainee_street = $data12['complainee_street'];
                   $estage2 = $data12['complainee_age'];
                }
              ?>
              <div class="container-fluid">
                <div class="row">
                  <div class="col-md-8">
                    <div class="row">
                      <div class="col-md-6">
                        <label for="ffnn" class="form-label">Complainee</label>
                        <p id="ffnn" class="text-start fs-5"><b><?php echo $fname;?> <?php echo $mname;?> <?php echo $lname;?></b></p>
                      </div>
                      <div class="col-md-3">
                        <label for="cst" class="form-label">Gender</label>
                        <p id="cst" class="text-start fs-5"><b><?php echo $complainee_gender;?></b></p>
                      </div>
                      <div class="col-md-3">
                        <label for="cst" class="form-label">Estimated Age</label>
                        <p id="cst" class="text-start fs-5"><b><?php echo $estage2;?></b></p>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                        <?php
                        if($data12['complainee_num'] == '' || $data12['complainee_num'] == 'none'){
                          echo '<label for="con2" class="form-label">Contact #</label>
                                <p id="con2" class="text-start fs-5"><b>No submitted number</b></p>';
                        }
                        else{
                          echo '<label for="con2" class="form-label">Contact #</label>
                                <p id="con2" class="text-start fs-5"><b>0'.$cont2.'</b></p>';
                        }
                        ?>
                      </div>
                      <div class="col-md-3">
                        <label for="chn" class="form-label">House Number</label>
                        <p id="chn" class="text-start fs-5"><b><?php echo $complainee_hnum;?></b></p>
                      </div>
                      <div class="col-md-3">
                        <label for="cst" class="form-label">Street</label>
                        <p id="cst" class="text-start fs-5"><b><?php echo $complainee_street;?></b></p>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <div class="container-fluid">
                      <div class="row">
                        <div class="col-md-12">
                          <?php
                          $sql13 = "SELECT * FROM evidence_tbl WHERE id = '$idcomp1'";
                          $d13 =  $con->query($sql13);
                          foreach($d13 as $data13){
                            $imagechecker = $data13['complainee_image'];
                            $comp_image = $data13['complainee_image'];
                            if($data13['complainee_image'] == 'none'){
                              echo '<label for="compimage" class="form-label">Accused Face Image</label>
                                    <p class="text-center fs-5" id="compimage"><b>No submitted face image.</b></p>';
                            }
                            else{
                              echo '<label for="compimage" class="form-label">Accused Face Image</label>
                                    <p class="text-center fs-5" id="compimage"><img src="images/'.$data13['complainee_image'].'" class="img-fluid border border-dark" alt="..."></p>';
                            }
                          }
                          ?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row border border-dark shadow-lg p-3 mb-5 bg-body rounded">
                  <div class="row mb-4">
                    <div class="col-md-5">
                      <i class="bi bi-table"></i>&nbsp;Potential Resident Data Table
                    </div>
                  </div>
                  <div class="col-md-12">
                    <table id="resident" class="table table-striped" style="width:100%">
                      <thead>
                        <tr>
                          <th>Name</th>
                          <th>Age</th>
                          <th>Contact</th>
                          <th>House #</th>
                          <th>Street</th>
                          <?php
                          if($imagechecker != 'none'){
                            echo '<th>Image</th>';
                          }
                          ?>
                          <th>Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $sql14 = "SELECT * FROM penresident WHERE firstname = '$fname' OR middlename = '$mname' OR lastname = '$lname' OR contact = '$cont2'";
                          $d14 =  $con->query($sql14);
                          foreach($d14 as $data14){
                            $fullname = $data14['firstname'] . ' ' . $data14['middlename'] . ' ' . $data14['lastname'];
                            $date2 = date('Y-m-d');
                            $datetime3 = new DateTime($date2);
                            $datetime4 = new DateTime($data14['birthdate']);
                            $difference2 = $datetime4->diff($datetime3);
                            $age5 = ($difference2->y);
                            $res_image = $data14['profile_id'];
                        ?>
                        <tr>
                          
                          <th><?php echo $fullname;?></th>
                          <th><?php echo $age5;?></th>
                          <th>0<?php echo $data14['contact'];?></th>
                          <th><?php echo $data14['new'];?></th>
                          <th><?php echo $data14['street'];?></th>
                          <form action="complaints.php" method="post">
                          <input type="hidden" name="idcomp1" value="<?=$idcomp1?>">
                          <input type="hidden" name="idapprove" value="<?=$data14['resident_id']?>">
                          <input type="hidden" name="res_image" value="<?=$res_image?>">
                          <input type="hidden" name="comp_image" value="<?=$comp_image?>">
                          <input type="hidden" name="imgdecider" value="0">
                          <?php
                          if($imagechecker != 'none'){
                            echo '<th><button type="submit" class="btn btn-link" name="openimage">Compare</button></th>';
                          }
                          ?>
                          <th><button type="submit" class="btn btn-primary" name="approveopenmod">Select</button></th>
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
              
            </div>
            <div class="modal-footer">
              <form action="complaints.php" method="post">
              <input type="hidden" name="deny1" value="2">
              <button type="submit" class="btn btn-danger" name="denycomplaint" value="<?=$idcomp1?>">Deny</button>
              <button type="submit" class="btn btn-secondary" name="pendingcomplaint" value="<?=$idcomp1?>">Back</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- identifying complainee pop-up modal end -->

      <!-- approve confirmation pop-up modal -->
      <div class="modal fade" id="approvecompmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Request</h5>
            </div>
            <div class="modal-body">
            <form action="complaints.php" method="post">
              <?php
              if(isset($errorMSG3)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG3.'
                      </div>';
              }
              ?>
              <div class="row">
                <div class="col-md-12">
                  <label for="hearing" class="form-label">Hearing Date</label>
                  <input type="datetime-local" class="form-select" id="hearing" name="hearing" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <input type="hidden" name="idcomp1" value="<?=$idcomp1?>">
              <button type="submit" class="btn btn-primary" name="approvecomplaint" value="<?=$idapprove?>">Approve</button>
              </form>
              <form action="complaints.php" method="post">
              <button type="submit" class="btn btn-secondary" name="identifycomp" value="<?=$idcomp1?>">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- approve confirmation pop-up end modal -->

      <!-- open images modal -->
      <div class="modal fade" id="openimagesmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <?php
              if($imgdecider == 1){
                echo '<h5 class="modal-title" id="openimagesmodal">Profile I.D.</h5>';
              }
              else{
                echo '<h5 class="modal-title" id="openimagesmodal">Complaint Request</h5>';
              }
              ?>
            </div>
            <div class="modal-body">
              <div class="row"> 
                <?php
                if($imgdecider == 1){
                  echo '<div class="col-md-12">
                          <p class="text-center fs-5" id="compimage"><img src="images/'.$res_image.'" style="width:400px; height:300px;" alt="..."></p>
                        </div>';
                }
                else{
                  echo '<div class="col-md-6">
                          <label for="compimage" class="form-label">Resident Image</label>
                          <p class="text-center fs-5" id="compimage"><img src="images/'.$res_image.'" class="img-fluid border border-dark" alt="..."></p>
                        </div>
                        <div class="col-md-6">
                          <label for="compimage" class="form-label">Accused Face Image</label>
                          <p class="text-center fs-5" id="compimage"><img src="images/'.$comp_image.'" class="img-fluid border border-dark" alt="..."></p>
                        </div>';
                }
                ?>
              </div>
            </div>
            <div class="modal-footer">
              <form action="complaints.php" method="post">
              <?php
                if($imgdecider == 1){
                  if($resident_id2 == 'none'){
                    echo '<input type="hidden" name="type" value="'.$type.'">
                          <input type="hidden" name="firstname" value="'.$firstname.'">
                          <input type="hidden" name="middlename" value="'.$middlename.'">
                          <input type="hidden" name="lastname" value="'.$lastname.'">
                          <input type="hidden" name="complainant_email" value="'.$complainant_email.'">
                          <input type="hidden" name="birthdate" value="'.$birthdate.'">
                          <input type="hidden" name="gender" value="'.$gender.'">
                          <input type="hidden" name="contact" value="'.$contact.'">
                          <input type="hidden" name="address" value="'.$address.'">
                          <button type="submit" class="btn btn-primary" name="filecomplaint">Close</button>';
                  }
                  else{
                    echo '<input type="hidden" name="resident_id" value="'.$resident_id2.'">
                          <button type="submit" class="btn btn-primary" name="complainantver">Close</button>';
                  }
                  
                }
                else{
                  echo '<button type="submit" class="btn btn-primary" name="identifycomp" value="'.$idcomp4.'">Close</button>';
                }
              ?>
              
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- open images modal end -->

      <!-- viewing pop-up modal -->
      <div class="modal fade" id="compdetail" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Information</h5>
            </div>
            <div class="modal-body p-4">
              <form action="complaints.php" method="post">
              <?php
              $query4 = "SELECT * FROM complaint_tbl WHERE id = '$idcomp3'";
              $dq4 =  $con->query($query4);
              foreach($dq4 as $qdata4){
                $compdate1 = date_create($qdata4['date_complaint']);
                $compdate1 = date_format($compdate1, 'F d, Y (h:i:s a)');
              
              ?>
              <div class="row">
                <div class="col-md-6">
                  <label for="compttype" class="form-label">Complaint Type</label>
                  <p id="compttype" class="text-start fs-4"><b><?=$qdata4['complaint_type'];?></b></p>
                </div>
                <div class="col-md-6">
                  <label for="datef" class="form-label">Date Filed</label>
                  <p id="datef" class="text-start fs-4"><b><?=$compdate1;?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="complainant" class="form-label">Complainant</label>
                  <p id="complainant" class="text-start fs-4"><b><?=$qdata4['complainant_fname'];?> <?=$qdata4['complainant_mname'];?> <?=$qdata4['complainant_lname'];?></b></p>
                </div>
                <div class="col-md-6">
                  <label for="complainee" class="form-label">Complainee</label>
                  <p id="complainee" class="text-start fs-4"><b><?=$qdata4['complainee_fname'];?> <?=$qdata4['complainee_mname'];?> <?=$qdata4['complainee_lname'];?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="statement" class="form-label">Statement</label>
                  <p id="statement" class="text-justify fs-4"><b><?=$qdata4['statement'];?></b></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="evidence" class="form-label">Evidence</label>
                  <?php
                  $query5 = "SELECT * FROM evidence_tbl WHERE id = '$idcomp3'";
                  $dq5 =  $con->query($query5);
                  foreach($dq5 as $qdata5){
                    $eviimg = $qdata5['evidence_image'];
                  }
                  if($eviimg == 'none'){
                    echo '<p id="evidence" class="text-start fs-4"><b>No submitted evidence.</b></p>';
                  }
                  else{
                    echo '<p id="evidence" class="text-center fs-4"><img src="images/'.$eviimg.'" class="img-fluid border border-dark" alt="..."></p>';
                  }
                  ?>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <div class="container-fluid border border-dark mt-3 shadow-lg p-3 mb-5 bg-body rounded">
                    <div class="row mb-2">
                      <div class="col-md-8">
                        <i class="bi bi-table"></i>&nbsp;Hearing Data Table
                      </div>
                      <div class="col-md-4">
                        
                      </div>
                    </div>
                    <div class="row mt-1 mb-0">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="col-md-12">
                            <table id="example" class="table table-striped" style="width:100%">
                              <thead>
                                <tr>
                                  <th>Case Hearing Status</th>
                                  <th>Case Hearing</th>
                                  <th>In-charge</th>
                                  <th>Date of Hearing</th>
                                  <th>Result</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $query6 = "SELECT * FROM hearing_tbl WHERE id = '$idcomp3' ORDER BY hearing_date";
                                $dq6 =  $con->query($query6);
                                foreach($dq6 as $qdata6){
                                  $statush = $qdata6['status'];
                                  $typef = $qdata6['type'];
                                  $compdate3 = $qdata6['hearing_date'];
                                  $compdate5 = date_create($compdate3);
                                  $compdate5 = date_format($compdate5, 'F d, Y h:i:s a');
                                ?>
                                <tr>
                                  <?php
                                  if($compdate3 > $date_reg){
                                    echo'<th>Upcoming</th>';
                                  }
                                  else{
                                    echo'<th>Conducted</th>';
                                  }
                                  if($typef == 0){
                                    echo'<th>Mediation</th>';
                                  }
                                  elseif($typef == 1){
                                    echo'<th>Follow-up hearing</th>';
                                  }
                                  ?>
                                  <th><?=$qdata6['incharge'];?></th>
                                  <th><?=$compdate5;?></th>
                                  <?php
                                  if($qdata6['result'] == ''){
                                    if($date_reg > $qdata6['hearing_date']){
                                      echo '<input type="hidden" name="h_id" value="'.$qdata6['h_id'].'">
                                      <th><button type="submit" class="btn btn-primary" name="resulthearing" value="'.$idcomp3.'">Result</button></th>';
                                    }
                                    else{
                                      echo '<th>In-progress</th>';
                                    }
                                    
                                  }
                                  else{
                                    echo '<input type="hidden" name="compdid" value="'.$idcomp3.'">
                                    <th><button type="submit" class="btn btn-link" name="viewresult" value="'.$qdata6['h_id'].'">View</button></th>';
                                  }
                                  ?>
                                </tr>
                                <?php
                                }
                                ?>
                              </tbody>
                            </table>
                          </div>
                        </div> 
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <?php
              }
              if($compdate3 > $date_reg){
                echo '<div class="row">
                <div class="col-md-12">
                  <p class="text-end"><button type="submit" class="btn btn-primary" name="reschedhearing" value="'.$idcomp3.'">Reschedule</button>&nbsp;&nbsp;<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></p>
                </div>
              </div>';
              }
              else{
                echo '<p class="text-end"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></p>';
              }
              ?>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- viewing pop-up end modal end-->

      <!-- hearing rechedule pop-up modal -->
      <div class="modal fade" id="approvereshed" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="approvereshed">Hearing Reschedule</h5>
            </div>
            <div class="modal-body">
              <?php
              if(isset($errorMSG6)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG6.'
                      </div>';
              }
              ?>
              <form action="complaints.php" method="post">
              <input type="hidden" name="latesthdate" value="<?=$latesthdate;?>">
              <div class="row">
                <div class="col-md-12">
                  <label for="hearingds" class="form-label">Hearing Date</label>
                  <input type="datetime-local" class="form-select" id="hearingds" name="hearingdate" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="hearingresched" value="<?=$idcomp3?>">Schedule</button>
              </form>
              <form action="complaints.php" method="post">
              <button type="submit" class="btn btn-secondary" name="opencomplaint" value="<?=$idcomp3?>">Cancel</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- confirmation rescheduling pop-up modal -->
      <div class="modal fade" id="reschedmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="reschedmodal">Hearing Reschedule</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">Hearing has been rescheduled.</p>
            </div>
            <div class="modal-footer">
              <form action="complaints.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">OK</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- hearing result pop-up modal -->
      <div class="modal fade" id="hearingresultmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Hearing Result</h5>
              <form action="complaints.php" method="post">
              <button type="submit" class="btn-close btn-close-white"  name="opencomplaint" value="<?=$idcomp3;?>"></button>
              </form>
            </div>
            <div class="modal-body">
              <form action="complaints.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group green-border-focus" id="result">
                    <label for="result" class="form-label">Result</label>
                    <textarea class="form-control" id="result" name="result" rows="7" required></textarea>
                  </div>
                </div>
              </div>
              <input type="hidden" name="hid" value="<?=$h_id?>">
              <div class="row mt-2">
                <div class="col-md-4">
                  <button type="submit" class="btn btn-primary" name="saveblotter" value="<?=$idcomp3?>">Save as Blotter</button>
                </div>
                <div class="col-md-8">
                  <p class="text-end"><button type="submit" class="btn btn-success" name="savesettle" value="<?=$idcomp3?>">Settled</button>&nbsp;<button type="submit" class="btn btn-danger" name="notsettle" value="<?=$idcomp3?>">Not Settled</button></p>
                </div>
              </div>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- hearing result pop-up modal end -->

      <!-- confirmation blotter pop-up modal -->
      <div class="modal fade" id="blotterresultmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="blotterresultmodal">Blotter Report</h5>
            </div>
            <div class="modal-body">
              <p class="text-center fs-4">The complaint has been recorded as a blotter record.</p>
            </div>
            <div class="modal-footer">
              <form action="complaints.php" method="post">
              <button type="submit" class="btn btn-primary" name="confirmresident">OK</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- not settled pop-up modal -->
      <div class="modal fade" id="notset" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Follow-up Hearing</h5>
            </div>
            <div class="modal-body">
              <?php
              if(isset($errorMSG5)){
                echo '<div class="alert alert-warning" role="alert">
                        '.$errorMSG5.'
                      </div>';
              }
              ?>
              <form action="complaints.php" method="post">
              <input type="hidden" name="hid" value="<?=$h_id?>">
              <input type="hidden" name="result" value="<?=$result?>">
              <div class="row">
                <div class="col-md-6">
                  <label for="inca" class="form-label">In-charge</label>
                  <input type="text" class="form-control" id="inca" value="Barangay Captain" disabled>
                </div>
                <div class="col-md-6">
                  <label for="hearing" class="form-label">Follow-up Hearing</label>
                  <input type="datetime-local" class="form-select" id="hearing" name="hearing" required>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary" name="savefollowup" value="<?=$idcomp3;?>">Schedule</button>
              </form>
              <form action="complaints.php" method="post">
              <button type="submit" class="btn btn-secondary" name="opencomplaint" value="<?=$idcomp3;?>">Back</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- not settled pop-up modal end -->

      <!-- making blotter pop-up modal -->
      <div class="modal fade" id="blottermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Blotter Report</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-12">
                  <p class="text-center fs-4">Are you sure to save this complaint as a blotter record?</p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <form action="complaints.php" method="post">
              <input type="hidden" name="hid" value="<?=$h_id?>">
              <input type="hidden" name="result" value="<?=$result;?>">
              <button type="submit" class="btn btn-success" name="confirmblotter" value="<?=$idcomp3;?>">Yes</button>
              <button type="submit" class="btn btn-danger" name="opencomplaint" value="<?=$idcomp3;?>">No</button>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- approving complaint pop-up modal end-->

      <!-- viewing result pop-up modal -->
      <div class="modal fade" id="viewresultmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Result</h5>
            </div>
            <div class="modal-body">
            <form action="complaints.php" method="post">
              <?php
                $qry5 = "SELECT * FROM hearing_tbl WHERE h_id = '$h_id'";
                $qryd5 =  $con->query($qry5);
                foreach($qryd5 as $qrydata5){
                  $resultshow = $qrydata5['result'];
                  $htype = $qrydata5['type'];
                  $compdate6 = $qrydata5['hearing_date'];
                  $compdate7 = date_create($compdate6);
                  $compdate7 = date_format($compdate7, 'F d, Y h:i:s a');
              ?>
              <div class="row">
                <div class="col-md-6">
                  <label for="results" class="form-label">Hearing Type</label>
                  <?php
                  if($htype == 0){
                    echo'<p id="results" class="text-start fs-4">Mediation</p>';
                  }
                  elseif($htype == 1){
                    echo'<p id="results" class="text-start fs-4">Follow-up Hearing</p>';
                  }
                  ?>
                  
                </div>
                <div class="col-md-6">
                <label for="Hearing" class="form-label">Hearing Date</label>
                  <p id="Hearing" class="text-start fs-4"><?=$compdate7;?></p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12">
                  <label for="results" class="form-label">Result</label>
                  <p id="results" style="word-break: break-word;" class="text-start fs-4"><?=$resultshow;?></p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-secondary" name="opencomplaint" value="<?=$idcomp3;?>">Close</button>
              </form>
              <?php
               }
            ?>
            </div>
          </div>
        </div>
      </div>
      <!-- approving complaint pop-up modal end-->

      <!-- confirmation settlement pop-up modal -->
      <div class="modal fade" id="confirmsettle" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <?php
              if($settlecheck == 2){
                echo '<h5 class="modal-title">Blotter Report</h5>';
              }
              else{
                echo '<h5 class="modal-title">Complaint Information</h5>';
              }
              ?>
              
            </div>
            <div class="modal-body">
              <?php
              if($settlecheck == 1){
                echo '<p class="text-center fs-4">Follow-up hearing has been scheduled.</p>';
              }
              elseif($settlecheck == 2){
                echo '<p class="text-center fs-4">New blotter has been recorded.</p>';
              }
              elseif($settlecheck == 3){
                echo '<p class="text-center fs-4">Latest hearing has been rescheduled.</p>';
              }
              else{
                echo '<p class="text-center fs-4">Complaint has been settled.</p>';
              }
              ?>
            </div>
            <form action="complaints.php" method="post">
            <div class="modal-footer">
              <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
            </div>
            </form>
          </div>
        </div>
      </div>
      <!-- adding confirmation pop-up end modal -->

      <!-- generate report options pop-up modal -->
      <div class="modal fade" id="genreportmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Generate Report</h5>
            </div>
            <div class="modal-body">
            <form action="complaints.php" method="post">
              <div class="row">
                <div class="col-md-12">
                  <label for="inchargespan" class="form-label">In-charge</label>
                  <select class="form-select" aria-label="Default select example" name="inchargespan" id="inchargespan" required>
                    <option value="" hidden>Select</option>
                    <option value="1">VAWC</option>
                    <option value="2">Tanod</option>
                    <option value="3">All</option>
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

      

      <!-- -------------------------------------------------------------------------------------- -->

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

  <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
  <script src="./js/app.js"></script>
  <script src="./js/imagedisplay.js"></script>
  <?php
  if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#reschedmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#outcomplaineemodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addconfmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#outdetailsmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 5) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#rescomplainee").modal("show");
			});
		</script>';
	}
  elseif($showModal == 6) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#outcomplainant").modal("show");
			});
		</script>';
	}
  elseif($showModal == 7) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#rescomplainant").modal("show");
			});
		</script>';
	}
  elseif($showModal == 8) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#rescomplaineemodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 9) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#rescomplainee").modal("show");
			});
		</script>';
	}
  elseif($showModal == 10) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#requestcomplaint").modal("show");
			});
		</script>';
	}
  elseif($showModal == 11) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#deletecompmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 12) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#deleteconfmod").modal("show");
			});
		</script>';
	}
  elseif($showModal == 13) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#identifymodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 14) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#approvecompmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 15) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#openimagesmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 16) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#compdetail").modal("show");
			});
		</script>';
	}
  elseif($showModal == 17) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#settledmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 18) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#confirmsettle").modal("show");
			});
		</script>';
	}
  elseif($showModal == 19) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#notset").modal("show");
			});
		</script>';
	}
  elseif($showModal == 20) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#blottermodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 21) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#approvereshed").modal("show");
			});
		</script>';
	}
  elseif($showModal == 22) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#hearingresultmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 23) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#viewresultmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 24) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#reschedmodal").modal("show");
			});
		</script>';
	}
  elseif($showModal == 25) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#blotterresultmodal").modal("show");
			});
		</script>';
	}
  else{

  }
  ?>
<!-- main content -->
</body>
</html>