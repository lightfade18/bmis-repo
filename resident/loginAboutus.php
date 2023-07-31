<?php
    require 'dbcon.php';
    session_start();
    $resident_id = $_SESSION['userresident']['resident_id'];
    $showModal = 0;
    $errorshow = 0;
    $imageface = 0;
    $imageevi = 0;

    date_default_timezone_set('Asia/Manila');
    $date = date('Y-m-d');
    $date_reg = date('Y-m-d H:i:s');

    /** record session details */
    if(!isset($_SESSION['userresident'])){
        header("Location: login.php");
    }

    /** filing complaint codes */
    if(isset($_REQUEST['complaintconfirm'])){
        $type = 'Resident';
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
        $type2 = 'Resident';
        $firstname2 = ucwords(trim(filter_var($_REQUEST['firstname2'],FILTER_SANITIZE_STRING)));
        $middlename2 = ucwords(trim(filter_var($_REQUEST['middlename2'],FILTER_SANITIZE_STRING)));
        $lastname2 = ucwords(trim(filter_var($_REQUEST['lastname2'],FILTER_SANITIZE_STRING)));
        $street2 = ucwords(trim(filter_var($_REQUEST['street2'],FILTER_SANITIZE_STRING)));
        $hnum = trim(filter_var($_REQUEST['hnum'],FILTER_SANITIZE_NUMBER_INT));
        $estage = $_REQUEST['estage'];
        $gender2 = $_REQUEST['gender2'];
        // if(isset($_REQUEST['complainee_email'])){
        //     $complainee_email = strtolower(trim(filter_var($_REQUEST['complainee_email'],FILTER_SANITIZE_EMAIL)));
        // }
        // else{
        //     $complainee_email = 'none';
        // }
        if(isset($_REQUEST['contact2'])){
            $contact2 = trim(filter_var($_REQUEST['contact2'],FILTER_SANITIZE_NUMBER_INT));
        }
        else{
            $contact2 = 'none';
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
                    $showModal = 1;
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
                        $showModal = 1;
                    }
                    else if($fileSize > 1000000){
                        $errorMSG = 'Image file size is too big.';
                        $errorshow = 1;
                        $showModal = 1;
                    }
                    else{
                        $imageface = 1;
                    }
                }
            }

            if(isset($_FILES["image"])){
                if($_FILES["image"]["error"] == 4){
                    $errorMSG = 'Something went wrong, please try again.';
                    $showModal = 1;
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
                        $showModal = 1;
                    }
                    else if($fileSize2 > 1000000){
                        $errorMSG = 'Image file size is too big.';
                        $errorshow = 1;
                        $showModal = 1;
                    }
                    else{
                        $imageevi = 1;
                    }
                }
            }

            if($errorshow == 0){
                try{
                    $query1 = $con->prepare("INSERT INTO complaint_tbl (id, complainant_fname, complainant_mname, complainant_lname, complainee_fname, complainee_mname, complainee_lname, statement, incharge, complainant_address, complainant_num, status, complainee_num, complainant_bdate, complainant_gender, complainee_gender, complainee_age, complaint_type, date_complaint, complainant_type, complainee_type, complainant_email, complainee_street, complainee_hnum)
                    VALUES (:id, :complainant_fname, :complainant_mname, :complainant_lname, :complainee_fname, :complainee_mname, :complainee_lname, :statement, :incharge, :complainant_address, :complainant_num, :status, :complainee_num, :complainant_bdate, :complainant_gender, :complainee_gender, :complainee_age, :complaint_type, :date_complaint, :complainant_type, :complainee_type, :complainant_email, :complainee_street, :complainee_hnum)");
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
                        ':complainant_bdate' => $birthdate, 
                        ':complainant_gender' => $gender, 
                        ':complainee_gender' => $gender2, 
                        ':complainee_age' => $estage,
                        ':complaint_type' => $comptype,
                        ':complainee_type' => $type2,
                        ':date_complaint' => $date_reg,
                        ':complainant_type' => $type,
                        ':complainant_email' => $complainant_email,
                        ':complainee_street' => $street2,
                        ':complainee_hnum' => $hnum
                        
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
                        move_uploaded_file($tmpName, '../admin/images/' . $newImageName);
                    }
                    if($imageevi == 1){
                        move_uploaded_file($tmpName2, '../admin/images/' . $newImageName2);
                    }
                    $showModal = 2;
                }
                catch(PDOException $e){
                    $pdoError = $e->getMesage();
                }
            }
        }
        else{
            $errorMSG = 'System already reach it\'s capacity to add complaints.';
            $showModal = 1;
        }
    }

    /** adding new transaction codes */
    if(isset($_REQUEST['addtrans'])){
        $tran_id = $_REQUEST['tran_id'];
        $quantity = $_REQUEST['quantity'];

        /** query to get the appropriate data from the database */ 
        $sql2 = "SELECT * FROM penresident WHERE resident_id = '$resident_id'";
        $d2 =  $con->query($sql2);
        foreach($d2 as $data2){
            $firstname = ($data2['firstname']);
            $middlename = ($data2['middlename']);
            $lastname = ($data2['lastname']);
        }

        /** query to get the appropriate data from the database */ 
        $sql4 = "SELECT * FROM transaction_tbl WHERE tran_id = '$tran_id'";
        $d4 =  $con->query($sql4);
        foreach($d4 as $data4){
            $price = ($data4['price']);
        }

        /** calculate the total amount of the transaction */
        $total_amount = $price * $quantity;

        /** formatting the names to display in the page */
        $minicom1 = ($middlename[0]);
        $fullname = ucwords(strtolower($lastname . ", " . $firstname . " " . $minicom1));
        /** if statement that set what type of transaction to display */
        if($tran_id == 1){
            $tran_name = "Barangay Clearance";
        }
        elseif($tran_id == 2){
            $tran_name = "Barangay Certificate";
        }
        elseif($tran_id == 3){
            $tran_name = "Reproduction of Tax Records";
        }
        else{
            $tran_name = "Community Tax Certificate";
        }
        /** dowhile loop that prevent the duplication of or_id in the db */
        $check = true;
        $cts = 0;
        do{
            $cts++;
            /** creating resident id by randoming numbers*/
            $or_id = rand(100000, 999999);
            $slct_stmt2 = $con->prepare("SELECT or_id FROM transactionlist_tbl WHERE or_id = :or_id");
            $slct_stmt2->execute([':or_id' => $or_id]);
            $row2 = $slct_stmt2->fetch(PDO::FETCH_ASSOC);
            if(isset($row2['or_id']) == $or_id){
                $check = false;
            }
            else{
                $check = true;
            }
        }while($check == false && $cts != 1000000);

        /** an if statement that check if the capacity of residents that the system can handle */
        if($check){
            /** setting the timezone of the system */
            date_default_timezone_set('Asia/Manila');
            $or_date = date('Y-m-d H:i:s');
            $or_datedis = date_create($or_date);
            $or_datedis = date_format($or_datedis, 'F d, Y (h:i:s a)');
            $status = "1";
            $showModal = 4;
        }
        else{
            $showModal = 3;
        }
        
    }

    /** reloading page */
    if(isset($_REQUEST['confirmresident'])){
        header("Location: loginHomepage.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <link rel="stylesheet" href="css/mainstyle.css" />
    <style>
        body {
            word-break: break-word;
        }
        /* @media (min-width : 992px) {
            .offcanvas {
                visibility      : visible;
                position        : relative;
                background      : none;
                border          : none;
                justify-content : end;
                color           : red;
            }
        } */
        
        @media (max-width : 992px) {
            .offcanvas {
                width : 300px !important;
            }
            .offcanvas-start-lg {
                top          : 0;
                left         : 0;
                border-right : 1px solid rgba(0, 0, 0, .2);
                transform    : translateX(-100%);
            }
        }
        #myBtn {
            display: none;
            position: fixed;
            bottom: 20px;
            right: 10px;
            z-index: 99;
        }

        p{
            text-align: justify;
        }

        .dropdown:hover>.dropdown-menu {
            display: block;
        }

        .dropdown>.dropdown-toggle:active {
        /*Without this, clicking will make it sticky*/
            pointer-events: none;
        }

    </style>
</head>
<body>
    <button
        type="button"
        class="btn btn-danger btn-lg rounded-circle text-center"
        id="myBtn"
        onclick="topFunction()"
        title="Go to top"
        >
        <i class="bi bi-chevron-up"></i>
    </button>

    <nav class="navbar navbar-expand-md navbar-dark bg-danger fixed-top ps-2 pe-2">
        <a class="navbar-brand text-uppercase fs-4" href="loginHomepage.php">
            <img src="images/logo.jpg" alt="" width="40" height="34" class="d-inline-block align-text-top">
                Concepcion Dos 
        </a>
        <button 
            type="button" 
            data-bs-toggle="offcanvas" 
            data-bs-target="#offcanvasExample" 
            class="navbar-toggler" 
            aria-controls="offcanvasExample"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbuttons">
            <ul class="navbar-nav text-center">
                <li class="nav-item active">
                    <a href="loginHomepage.php" class="nav-link">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#announcetarget" class="nav-link">
                        Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#eventtarget" class="nav-link">
                        Events
                    </a>
                </li>
                <li class="nav-item">
                    <a href="loginOfficials.php" class="nav-link active">
                        Officials
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Services
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        
                        <li><a class="dropdown-item"href="#" data-bs-toggle="modal" data-bs-target="#choosetpye">Certificates</a></li>
                        <hr class="dropdown-divider">
                        <li><a class="dropdown-item"href="#" data-bs-toggle="modal" data-bs-target="#addregmodal">Complaint</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="loginAboutus.php" class="nav-link">
                        About Us
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav position-absolute end-0 pe-2">
                <li class="nav-item dropdown">
                    <div class="dropdown">
                        <button class="btn btn-light dropdown-toggle rounded-pill" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Username
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutconfmodal">Log-out</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <div class="offcanvas offcanvas-start bg-danger ps-2 pe-2" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header text-white pb-0">
            <h5 class="offcanvas-title" id="offcanvasExampleLabel"><img src="images/logo.jpg" alt="" width="40" height="34" class="d-inline-block align-text-top"> CONCEPCION DOS</h5>
        </div>
        <div class="offcanvas-body pt-3">
            <ul class="navbar-nav text-start pb-3">
                <li class="nav-item active">
                    <a href="loginHomepage.php" class="nav-link active text-white">
                        Home
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#announcetarget" class="nav-link text-white" data-bs-dismiss="offcanvas">
                        Announcements
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#eventtarget" class="nav-link text-white" data-bs-dismiss="offcanvas">
                        Events
                    </a>
                </li>
                <li class="nav-item">
                    <a href="loginOfficials.php" class="nav-link text-white">
                        Officials
                    </a>
                </li>
                <li class="nav-item">
                    <a href="loginAboutus.php" class="nav-link text-white active">
                        About Us
                    </a>
                </li>
                <li>
                    <a class="nav-link sidebar-link text-white" data-bs-toggle="collapse" href="#reports" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <span>Services</span>
                        <span class="right-icon ms-auto">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="collapse" id="reports">
                        <div class="card card-body bg-white">
                            <ul class="navbar-nav p-0">
                                <li><a class="dropdown-item"href="#" data-bs-toggle="modal" data-bs-target="#choosetpye" data-bs-dismiss="offcanvas">Certificates</a></li>
                                <hr class="dropdown-divider">
                                <li><a class="dropdown-item"href="#" data-bs-toggle="modal" data-bs-target="#addregmodal" data-bs-dismiss="offcanvas">Complaint</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li>
                    <a class="nav-link sidebar-link text-white" data-bs-toggle="collapse" href="#userdropdown" role="button" aria-expanded="false" aria-controls="collapseExample">
                        <span>Username</span>
                        <span class="right-icon ms-auto">
                            <i class="bi bi-chevron-down"></i>
                        </span>
                    </a>
                    <div class="collapse" id="userdropdown">
                        <div class="card card-body bg-white">
                            <ul class="navbar-nav p-0">
                                <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutconfmodal" data-bs-dismiss="offcanvas">Log-out</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- main body -->
    <main class="mt-4 pt-4 ms-4 me-4 mb-4">
        <section class="mt-4">
            <div class="container d-flex justify-content-center">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-info" href="#loc" role="button">Location</a>&nbsp;&nbsp;&nbsp;<a class="btn btn-info" href="#serv" role="button">Services</a>
                    </div>
                </div>
            </div>
        </section>
        <section class="mt-3 shadow-lg p-4 bg-body rounded">
            <div class="row d-flex justify-content-center">
                <div class="col-md-7">
                    <h1 class="text-center">Abouts Us</h1>
                    <p class="fs-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia cumque saepe ullam autem enim unde deleniti quod dignissimos consequuntur fugiat sint, perspiciatis quaerat velit laboriosam porro culpa nostrum non alias?</p>
                </div>
            </div>
        </section>
        <section class="mt-3 shadow-lg p-0 bg-body rounded">
            <div class="row">
                <div class="col-md-7 p-5">
                    <h1 class="text-center">Mission</h1>
                    <p class="fs-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia cumque saepe ullam autem enim unde deleniti quod dignissimos consequuntur fugiat sint, perspiciatis quaerat velit laboriosam porro culpa nostrum non alias?</p>
                </div>
                <div class="col-md-5 d-flex justify-content-end">
                    <img src="images/mission.jpg" class="img-fluid mvbg" alt="...">
                </div>
            </div>
        </section>
        <section class="mt-3 shadow-lg p-0 bg-body rounded">
            <div class="row">
                <div class="col-md-5">
                    <img src="images/vision.jpg" class="img-fluid mvbg" alt="...">
                </div>
                <div class="col-md-7 p-5">
                    <h1 class="text-center">Vision</h1>
                    <p class="fs-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia cumque saepe ullam autem enim unde deleniti quod dignissimos consequuntur fugiat sint, perspiciatis quaerat velit laboriosam porro culpa nostrum non alias?</p>
                </div>
            </div>
        </section>
        <section class="mt-3  shadow-lg p-4 bg-body rounded">
            <div class="row d-flex justify-content-center">
                <div class="col-md-7">
                    <h1 class="text-center">Demographics</h1>
                    <p class="fs-5">Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia cumque saepe ullam autem enim unde deleniti quod dignissimos consequuntur fugiat sint, perspiciatis quaerat velit laboriosam porro culpa nostrum non alias?</p>
                </div>
            </div>
        </section>
        <section class="mt-3 shadow-lg bg-body rounded">
            <div class="bgs shadow-lg p-1 bg-body rounded">
                <div class="row d-flex justify-content-center">
                    <div class="col-md-7">
                        <p class="text-center fs-1"><h1 class="text-center">Contact Us</h1></p>
                    </div>
                </div>
                <div class="row d-flex justify-content-center">
                    <div class="col-md-6 text-center">
                        <div class="bg-light m-2 rounded p-1 shadow-lg bg-body rounded">
                            <img src="images/phone.png"  width="60" height="60" class="mt-3 rounded-circle" alt="...">
                            <p class="text-center"><h3 class="text-center">Contact Details</h3></p>
                            <p class="fs-5 text-center">Telephone: (02) 942 0559</p> 
                            <p class="fs-5 text-center">Smart/TnT: 09999999999</p> 
                            <p class="fs-5 text-center">Globe/TM: 09999999999</p> 
                        </div>              
                    </div>
                    <div class="col-md-6 text-center">
                        <div class="bg-light m-2 p-1 shadow-lg bg-body rounded">
                            <p class="text-center"><h3 class="text-center">Socials</h3></p>
                            <!-- Facebook -->
                            <a
                                class="btn btn-link btn-lg text-dark m-1 border border-dark rounded"
                                href="https://www.facebook.com/pages/Concepcion-Dos-Barangay-Hall/2023504107936325"
                                role="button"
                                data-mdb-ripple-color="dark"
                                target="_blank"
                                ><i class="bi bi-facebook"></i>
                            </a>

                            <a
                                class="btn btn-link btn-lg text-dark m-1 border border-dark rounded disabled"
                                href="#"
                                role="button"
                                data-mdb-ripple-color="dark"
                                target="_blank"
                                ><i class="bi bi-twitter"></i>
                            </a>
                            <a
                                class="btn btn-link btn-lg text-dark m-1 border border-dark rounded disabled"
                                href="#"
                                role="button"
                                data-mdb-ripple-color="dark"
                                target="_blank"
                                ><i class="bi bi-instagram"></i>
                            </a>
                            <p class="fs-5 text-center"><a class="btn btn-link text-dark" href="mailto: concepciondosmarikina@gmail.com" role="button"><i class="bi bi-envelope me-2"></i>concepciondosmarikina@gmail.com</a></p>
                        </div>              
                    </div>
                </div>
            </div>
        </section>

        <section id="serv" class="mt-5 mb-5 border border-dark rounded bg-dark p-3">
            <div class="row mb-3 d-flex justify-content-center text-white">
                <div class="col-md-7">
                    <h1 class="text-center">Services</h1>
                </div>
            </div>
            <div class="row mt-5 d-flex justify-content-center text-white mb-3">
                <div class="col-md-6">
                <?php
                    
                        echo '<div id="carouselExampleFade" class="carousel carousel-dark slide carousel-fade p-1" data-bs-ride="carousel">
                        <div class="carousel-inner carousels">';
                        $qry1 = "SELECT * FROM transaction_tbl WHERE tran_id != '7'";
                        $dq1 =  $con->query($qry1);
                        $slctr = 1;
                        foreach($dq1 as $qdata1){
                            if($slctr == 1){
                                echo '<div class="carousel-item active">';
                            }
                            else{
                                echo '<div class="carousel-item">';
                            }
                        ?>
                                    <h2><?=$qdata1['tran_name']?></h2>
                                    <h4>Cost: Php <?=$qdata1['price']?></h4>
                                    <p class="text-start fs-5 mb-0">Requirements:</p>
                                    <p class="text-start fs-5"><?=$qdata1['requirements']?></p>
                                    <p class="text-center fs-5"><button type="button" class="btn btn-light" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#choosetpye">Avail now!</button></p>
                            </div>
                        <?php
                            $slctr += 1;
                        }
                        echo '</div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>';
                    ?>
                </div>
            </div>
        </section>

        <!-- add complaint resident pop-up modal -->
        <div class="modal fade" id="addregmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable modal-xl">
                <div class="modal-content">
                    <div class="modal-header p-3 mb-2 bg-secondary text-white">
                        <h5 class="modal-title" id="outcomplainant">Complaint Form</h5>
                    </div>
                    <div class="modal-body">
                    <?php
                    if(isset($errorMSG)){
                    echo '<div class="alert alert-warning" role="alert">
                            '.$errorMSG.'
                            </div>';
                    }
                    ?>
                    <form action="loginHomepage.php" method="post"  enctype="multipart/form-data">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-6 p-3 shadow bg-body rounded">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-center fs-4">Complainee Information</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="fname" class="form-label">Firstname</label>
                                            <input type="text" class="form-control" id="fname" name="firstname2" placeholder="Enter here..." required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="mname" class="form-label">Middlename</label>
                                            <input type="text" class="form-control" id="mname" name="middlename2" placeholder="Enter here..." required>
                                        </div>
                                    </div><div class="row">
                                        <div class="col-md-12">
                                            <label for="lname" class="form-label">Lastname</label>
                                            <input type="text" class="form-control" id="lname" name="lastname2" placeholder="Enter here..." required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="gder" class="form-label">Gender</label>
                                            <select class="form-select" aria-label="Default select example" name="gender2" id="gder" required>
                                            <option selected value="none" hidden>Select</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="birthdate" class="form-label">Estimated Age</label>
                                            <input type="number" class="form-control" id="estage" name="estage" min="1" max="120" required>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label for="hnum" class="form-label">House Number</label>
                                            <input type="number" class="form-control" id="hnum" name="hnum" min="1" max="9999" required>
                                        </div>
                                        <div class="col-md-7">
                                            <label for="street2" class="form-label">Street</label>
                                            <select class="form-select" aria-label="Default select example" name="street2" id="street2" required>
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
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="contact2" class="form-label">Contact Number (If possible)</label>
                                            <div class="input-group">
                                                <span class="input-group-text">+63</span>
                                                <input type="tel" class="form-control" id="contact2" name="contact2" pattern="[9]{1}[0-9]{9}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="image-input" class="form-label">Face Image (If available)</label>
                                            <input type="file" class="form-control" id="image-input" name="face_image" accept=".jpg, .jpeg, .png">
                                        </div>
                                    </div>
                                    <div id="display-image" class="container-fluid border border-dark shadow-lg p-3 mt-1 mb-2 bg-body rounded">

                                    </div>
                                </div>
                                <div class="col-md-6 p-3 shadow bg-body rounded">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p class="text-center fs-4">Complaint Details</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                    <div class="col-md-12">
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
                                    </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group green-border-focus" id="statement">
                                            <label for="statement">Statement</label>
                                            <textarea class="form-control" name="statement" id="statement" rows="5" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group green-border-focus" id="facial_feat">
                                            <label for="facial_feat">Facial Features (Person-to-Complain)</label>
                                            <textarea class="form-control" name="facial_feat" id="facial_feat" rows="5" required></textarea>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label for="image-input2">Photo Evidence (If available)</label>
                                            <input type="file" class="form-control" id="image-input2" name="image"  accept=".jpg, .jpeg, .png">
                                        </div>
                                    </div>
                                    <div id="display-image2" class="container-fluid border border-dark shadow-lg p-3 mt-1 mb-2 bg-body rounded">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" name="complaintconfirm">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- add complaint pop-up modal end -->

        <!-- adding confirmation pop-up modal -->
        <div class="modal fade" id="compconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-3 mb-2 bg-secondary text-white">
                        <h5 class="modal-title" id="compconfmodal">Request Complaint</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center fs-4">Your complaint request has been successfully submitted.</p>
                    </div>
                    <form action="loginHomepage.php" method="post">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" name="confirmresident">OK</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- adding confirmation pop-up end modal -->

        <!-- choosing type pop-up modal -->
        <div class="modal fade" id="choosetpye" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Choose Transaction</h5>
                </div>
                <div class="modal-body">
                <?php
                    if(isset($errorMSG1)){
                    echo '<div class="alert alert-danger" role="alert">'
                    .$errorMSG1.'
                    </div>';
                    }
                    if(isset($errorMSG2)){
                    echo '<div class="alert alert-danger" role="alert">'
                    .$errorMSG2.'
                    </div>';
                    }
                ?>
                <form action="loginHomepage.php" method="post">
                <div class="row">
                    <div class="col-md-8">
                    <label for="tran_id" class="form-label">Transaction Type</label>
                    <select class="form-select" aria-label="Default select example" id="tran_id" name="tran_id" required>
                        <option selected value="">Select Transaction</option>
                        <option value="1">Barangay Clearance</option>
                        <option value="2">Business Certificate</option>
                        <option value="5">Community Tax Certificate</option>
                        <option value="3">Reproduction of Tax Records</option>
                    </select>
                    </div>
                    <div class="col-md-4">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" name="quantity" id="quantity" value="1" min="1" max="100" required>
                    </div>
                </div>
                <input type="hidden" name="resident_id" value="<?php echo $resident_id?>">
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-primary" name="addtrans">Proceed</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
                </form>
            </div>
            </div>
        </div>
        <!-- choosing type pop-up modal end -->

        <!-- adding pop-up modal -->
        <div class="modal fade" id="addmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                <h5 class="modal-title">Adding Transaction</h5>
                </div>
                <div class="modal-body">
                <?php
                    if(isset($errorMSG3)){
                    echo "<div class='alert alert-danger d-flex align-items-center' role='alert'>
                    <svg class='bi flex-shrink-0 me-2' width='24' height='24' role='img' aria-label='Danger:'><use xlink:href='#exclamation-triangle-fill'/></svg>
                    <div>"
                        .$errorMSG3.
                    "</div>
                    </div>";
                    }
                ?>
                <div class="row">
                    <div class="col-md-12">
                    <label for="ornumber" class="form-label">OR #</label>
                    <p id="ornumber" class="text-start fs-4"><?php echo $or_id;?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <label for="typetran" class="form-label">Transaction Type</label>
                    <p id="typetran" class="text-start fs-4"><?php echo $tran_name;?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <label for="residentreq" class="form-label">Resident</label>
                    <p id="typetran" class="text-start fs-4"><?php echo $fullname;?>.</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                    <label for="Price" class="form-label">Price</label>
                    <p id="Price" class="text-start fs-4"><?php echo $price;?></p>
                    </div>
                    <div class="col-md-6">
                    <label for="Quantity" class="form-label">Quantity</label>
                    <p id="Quantity" class="text-start fs-4"><?php echo $quantity;?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <label for="Total Amount" class="form-label">Total Amount</label>
                    <p id="Total Amount" class="text-start fs-4"><?php echo $total_amount;?></p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    <label for="ordate" class="form-label">OR Date</label>
                    <p id="ordate" class="text-start fs-4"><?php echo $or_datedis;?></p>
                    </div>
                </div>
                <form action="loginHomepage.php" method="post">
                <input type="hidden" name="status" value="<?php echo $status;?>">
                <input type="hidden" name="tran_id" value="<?php echo $tran_id;?>">
                <input type="hidden" name="tran_name" value="<?php echo $tran_name;?>">
                <input type="hidden" name="fullname" value="<?php echo $fullname;?>">
                <input type="hidden" name="or_id" value="<?php echo $or_id;?>">
                <input type="hidden" name="or_date" value="<?php echo $or_date;?>">
                <input type="hidden" name="price" value="<?php echo $price;?>">
                <input type="hidden" name="quantity" value="<?php echo $quantity;?>">
                <input type="hidden" name="total_amount" value="<?php echo $total_amount;?>">
                <div class="row">
                    <div class="col-md-12">
                    <div class="form-group green-border-focus" id="purpose">
                        <label for="purpose">Purpose</label>
                        <textarea class="form-control" id="purpose" name="purpose" rows="5" required></textarea>
                    </div>
                    </div>
                </div>
                </div>
                <div class="modal-footer">
                <button type="submit" class="btn btn-success" name="confirmadd">Confirm</button>
                </form>
                <form action="loginHomepage.php" method="post">
                <button type="submit" class="btn btn-secondary" name="resid">Back</button>
                </form>
                </div>
            </div>
            </div>
        </div>
        <!-- adding pop-up modal end -->

        <!-- logout confirmation pop-up modal -->
        <div class="modal fade" id="logoutconfmodal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header p-3 mb-2 bg-secondary text-white">
                        <h5 class="modal-title">User Session</h5>
                    </div>
                    <div class="modal-body">
                        <p class="text-center fs-4">Any progress are automatically save.<br>Are you sure to logout?</p>
                    </div>
                        <div class="modal-footer">
                        <a href="login.php"><button type="button" class="btn btn-success" data-bs-dismiss="modal">Yes</button></a><button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>  
                    </div>
                </div>
            </div>
        </div>
        <!-- logout confirmation pop-up end modal -->
    </main>
    <!-- main body end -->
    
    <!-- footer -->
    <footer class="text-center text-white bg-danger">

        <!-- Copyright -->
        <div class="text-center text-white p-2 bg-dark">
            Copyright © 2022: Brgy.Concepcion Dos, Philippines
        </div>
        <!-- Copyright -->
    </footer>
    <!-- footer end -->


    <script>
    // Get the button
    let mybutton = document.getElementById("myBtn");

    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }
    </script>

    <?php
    if($showModal == 1) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addregmodal").modal("show");
			});
		</script>';
    }
    elseif($showModal == 2) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#compconfmodal").modal("show");
			});
		</script>';
    }
    elseif($showModal == 3) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#choosetpye").modal("show");
			});
		</script>';
    }
    elseif($showModal == 4) {
		// CALL MODAL HERE
		echo '<script type="text/javascript">
			$(document).ready(function(){
				$("#addmodal").modal("show");
			});
		</script>';
    }
    else{

    }
    ?>
</body>
</html>