<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/mainstyle.css" />
</head>
<body>
    <button
        type="button"
        class="btn btn-dark btn-floating btn-lg rounded-circle text-center"
        id="btn-back-to-top"
        >
        <i class="bi bi-chevron-up"></i>
    </button>
    <!-- navbar -->
    <div class="container">
        <nav class="navbar navbar-expand-md navbar-dark bg-danger fixed-top">
            <a class="navbar-brand text-uppercase fs-4" href="homepage.php">
                <img src="images/logo.jpg" alt="" width="40" height="34" class="d-inline-block align-text-top">
                    Concepcion Dos
            </a>
            <button 
                type="button" 
                data-bs-toggle="collapse" 
                data-bs-target="#navbuttons" 
                class="navbar-toggler" 
                aria-controls="navbarNav"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbuttons">
                <ul class="navbar-nav text-center">
                    <li class="nav-item">
                        <a href="loginHomepage.php" class="nav-link">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="loginOfficials.php" class="nav-link">
                            Officials
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="loginAnnouncement.php" class="nav-link">
                            Announcements
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Services
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li class="text-muted p-2">Services</li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#brgycert">Brgy Clearance</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#busicert">Business Certificate</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#ctccert">Community Tax Certificate</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#repcert">Reproduction of Tax Records</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item"href="#" data-bs-toggle="modal" data-bs-target="#choosetype">Complaint</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="loginAboutus.php" class="nav-link">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-fill"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDarkDropdownMenuLink">
                            <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutconfmodal">Log-out</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- navbar end -->

    <!-- main content -->
    <section>
        <div class="container mt-5 pt-4 mb-3">
            <div class="row">
                <div class="col-md-3">
                    <div class="row-2">
                        <div class="col-md-12 border bg-danger">
                            <a href="profile.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-white">Profile</p></a>
                        </div>
                    </div>
                    <div class="row-2">
                        <div class="col-md-12 border">
                            <a href="profileRecord.php" class="text-decoration-none"><p id="deliveryguy" class="text-center fs-4 text-danger">Records</p></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-md-12 border border-danger rounded p-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>Profile Information</h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label for="name" class="form-label">Name</label>
                                    <h5 id="name">Dela Cruz, Juan Pinoy</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="age" class="form-label">Age</label>
                                    <h5 id="age">40</h5>
                                </div>
                                <div class="col-md-4">
                                    <label for="bdate" class="form-label">Birthdate</label>
                                    <h5 id="bdate">01/10/1970</h5>
                                </div>
                                <div class="col-md-4">
                                    <label for="gender" class="form-label">Gender</label>
                                    <h5 id="gender">Male</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="contact" class="form-label">Contact #</label>
                                    <h5 id="contact">09999999999</h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <h5 id="email">delacruzj@gmail.com</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="uname" class="form-label">Username</label>
                                    <h5 id="uname">delacruzJ70</h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="uname" class="form-label">Password</label>
                                    <h5 id="uname">1970110010dj</h5>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="fpos" class="form-label">Family Position</label>
                                    <h5 id="fpos">Head</h5>
                                </div>
                                <div class="col-md-6">
                                    <label for="hcode" class="form-label">Household Code (available only to househead)</label>
                                    <h5 id="hcode">201510012345</h5>
                                </div>
                            </div>
                            <div class="row mb-2">
                                <div class="col-md-12">
                                    <label for="idpic" class="form-label">Registered I.D.</label>
                                    <p id="idpic"><img src="images/id.jpg" class="img-fluid" alt="..."></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="infoup" class="form-label">Update Information</label>
                                    <p id="infoup"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editmodal">Update</button></p>
                                </div>
                                <div class="col-md-6">
                                    <label for="chpass" class="form-label">Change Password</label>
                                    <p id="chpass"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editpass">Change</button></p>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- main content end -->

    <!-- footer -->
    <footer class="text-center text-white bg-danger">
        <!-- Grid container -->
        <div class="container pt-4">
            <!-- Section: Social media -->
            <section class="mb-4">
            <!-- Facebook -->
            <a
                class="btn btn-link btn-floating btn-lg text-white m-1 border border-white rounded"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-facebook"></i
            ></a>
            <!-- Twitter -->
            <a
                class="btn btn-link btn-floating btn-lg text-white m-1 border border-white rounded"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-twitter"></i
            ></a>
            <!-- Instagram -->
            <a
                class="btn btn-link btn-floating btn-lg text-white m-1 border border-white rounded"
                href="#!"
                role="button"
                data-mdb-ripple-color="dark"
                ><i class="bi bi-instagram"></i
            ></a>
            </section>
            <!-- Section: Social media -->
        </div>
        <!-- Grid container -->

        <!-- Copyright -->
        <div class="text-center text-white p-2 bg-dark">
            Copyright © 2022: Brgy.Concepcion Dos, Philippines
        </div>
        <!-- Copyright -->
    </footer>
    <!-- footer end -->
    <!-- brgy clearance pop-up modal -->
    <div class="modal fade" id="brgycert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Barangay Clearance</h5>
                </div>
                <div class="modal-body">
                    ...
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#brgycertver">Avail</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- brgy clearance pop-up end modal -->
    <!-- brgy clearance verification pop-up modal -->
    <div class="modal fade" id="brgycertver" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Barangay Clearance</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-4">Are you sure to avail this service?</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#brgycertconf">Confirm</button><button type="button" class="btn btn-Secondary" data-bs-toggle="modal" data-bs-target="#brgycert">Back</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- brgy clearance verification pop-up end modal -->
    <!-- brgy clearance confirmation pop-up modal -->
    <div class="modal fade" id="brgycertconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Barangay Clearance</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Your avail is now pending.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- brgy clearance pop-up end modal -->

    <!-- business cert pop-up modal -->
    <div class="modal fade" id="busicert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Business Certificate</h5>
                </div>
                <div class="modal-body">
                    ...
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#busicertver">Avail</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- business cert pop-up end modal -->
    <!-- business cert verification pop-up modal -->
    <div class="modal fade" id="busicertver" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Business Certificate</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-4">Are you sure to avail this service?</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#busicertconf">Confirm</button><button type="button" class="btn btn-Secondary" data-bs-toggle="modal" data-bs-target="#busicert">Back</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- business cert verification pop-up end modal -->
    <!-- business cert confirmation pop-up modal -->
    <div class="modal fade" id="busicertconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Business Certificate</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Your avail is now pending.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- business cert pop-up end modal -->

    <!-- CTC pop-up modal -->
    <div class="modal fade" id="ctccert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Community Tax Certificate</h5>
                </div>
                <div class="modal-body">
                    ...
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#ctccertver">Avail</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- CTC pop-up end modal -->
    <!-- CTC verification pop-up modal -->
    <div class="modal fade" id="ctccertver" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Community Tax Certificate</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-4">Are you sure to avail this service?</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#ctccertconf">Confirm</button><button type="button" class="btn btn-Secondary" data-bs-toggle="modal" data-bs-target="#ctccert">Back</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- CTC verification pop-up end modal -->
    <!-- CTC confirmation pop-up modal -->
    <div class="modal fade" id="ctccertconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Community Tax Certificate</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Your avail is now pending.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CTC pop-up end modal -->

    <!-- rep pop-up modal -->
    <div class="modal fade" id="repcert" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Reproduction of Tax Records</h5>
                </div>
                <div class="modal-body">
                    ...
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#repcertver">Avail</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- rep pop-up end modal -->
    <!-- rep verification pop-up modal -->
    <div class="modal fade" id="repcertver" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Reproduction of Tax Records</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-4">Are you sure to avail this service?</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#repcertconf">Confirm</button><button type="button" class="btn btn-Secondary" data-bs-toggle="modal" data-bs-target="#repcert">Back</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- rep verification pop-up end modal -->
    <!-- rep confirmation pop-up modal -->
    <div class="modal fade" id="repcertconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Reproduction of Tax Records</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Your avail is now pending.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- rep pop-up end modal -->

    <!-- choosing type pop-up modal -->
    <div class="modal fade" id="choosetype" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="staticBackdropLabel">Filing Complaint</h5>
              <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                    <p class="text-center"><img src="images/adult.jpg" class="img-thumbnail" alt="..."></p>
                    <p class="text-center"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addregmodal">Resident</button></p>
                </div>
                <div class="col-md-6">
                    <p class="text-center"><img src="images/adult.jpg" class="img-thumbnail" alt="..."></p>
                    <p class="text-center"><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addnotmodal">Not Resident</button></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- choosing type pop-up modal -->
    <!-- add complaint resident pop-up modal -->
    <div class="modal fade" id="addregmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="staticBackdropLabel">Filing Complaint</h5>
            </div>
            <div class="modal-body">
              <form action="" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <label for="complainee" class="form-label">Person to complain</label>
                    <input class="form-control" list="complainees" id="complainee">
                    <datalist id="complainees">
                      <option value="Delar Cruz, Juan P.">
                      <option value="Delar Cruz, Juan P.">
                      <option value="Delar Cruz, Juan P.">
                      <option value="Delar Cruz, Juan P.">
                    </datalist>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group green-border-focus" id="statement">
                      <label for="statement">Statement</label>
                      <textarea class="form-control" id="statement" rows="5"></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#compprev">File</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
    </div>
    <!-- add complaint pop-up modal end -->
    <!-- add complaint not resident pop-up modal -->
    <div class="modal fade" id="addnotmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title" id="staticBackdropLabel">Filing Complaint</h5>
            </div>
            <div class="modal-body">
              <form action="" method="post">
                <div class="row">
                  <div class="col-md-12">
                    <label for="complainee" class="form-label">Person to complain</label>
                    <input type="text" class="form-control" id="complainee" placeholder="Enter here...">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-3">
                    <label for="age" class="form-label">Age</label>
                    <input type="number" class="form-control" id="age" placeholder="##" min="1" max="150">
                  </div>
                  <div class="col-md-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" aria-label="Default select example" id="gender">
                      <option selected disabled>Select</option>
                      <option value="1">Male</option>
                      <option value="2">Female</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="contact" class="form-label">Contact #(if possible)</label>
                    <input type="text" class="form-control" id="contact" placeholder="Enter here...">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <label for="address" class="form-label">Address</label>
                    <input type="address" class="form-control" id="address" placeholder="Enter here...">
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group green-border-focus" id="statement">
                      <label for="statement">Statement</label>
                      <textarea class="form-control" id="statement" rows="5"></textarea>
                    </div>
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#compprev">File</button><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
          </div>
        </div>
    </div>
    <!-- add complaint pop-up modal end -->
    <!-- add complaint preview pop-up modal -->
    <div class="modal fade" id="compprev" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
          <div class="modal-content">
            <div class="modal-header p-3 mb-2 bg-secondary text-white">
              <h5 class="modal-title">Complaint Preview</h5>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <label for="complainant" class="form-label">Complainant</label>
                  <p id="complainant" class="text-start fs-3">Dela Cruz, Juan P.</p>
                </div>
                <div class="col-md-6">
                  <label for="complainee" class="form-label">Complainee</label>
                  <p id="complainee" class="text-start fs-3">Dela Cruz, Juan P.</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <label for="datefiled" class="form-label">Date Filed</label>
                  <p id="datefiled" class="text-start fs-3">02/22/2022</p>
                </div>
                <div class="col-md-6">
                  <label for="complainee" class="form-label">Complainee Status</label>
                  <p id="complainee" class="text-start fs-3">Resident/Not Resident</p>
                </div>
              </div>
              <div class="row-3">
                <div class="col-md-12">
                  <label for="statement" class="form-label">Statement</label>
                  <p id="statement" class="text-justify fs-5">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. 
                    Lorem Ipsum has been the industry's standard dummy text ever since the 
                    1500s, when an unknown printer took a galley of type and scrambled it to 
                    make a type specimen book. It has survived not only five centuries, but 
                    also the leap into electronic typesetting, remaining essentially unchanged. 
                    It was popularised in the 1960s with the release of Letraset sheets 
                    containing Lorem Ipsum passages, and more recently with desktop publishing 
                    software like Aldus PageMaker including versions of Lorem Ipsum.
                  </p>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#compconf">Save</button><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addmodal">Cancel</button>
            </div>
          </div>
        </div>
    </div>
      <!-- add complaint preview pop-up modal end -->
      <!-- add complaint confirmation pop-up modal -->
    <div class="modal fade" id="compconf">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Complaint Information</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Your complain is waiting to be verified.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- rep pop-up end modal -->
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
                    <a href="login.php"><button type="button" class="btn btn-success" data-bs-dismiss="modal">Yes</button></a><button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- logout confirmation pop-up end modal -->
    <!-- edit pop-up modal -->
    <div class="modal fade" id="editmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title">Resident Information</h5>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row">
                <div class="col-md-4">
                  <label for="chcode" class="form-label">Current Household Code</label>
                  <p id="chcode" class="text-start fs-3">21343242</p>
                </div>
                <div class="col-md-4">
                  <label for="hcode" class="form-label">Household Code</label>
                  <input type="text" class="form-control" id="hcode" placeholder="Enter here...">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="firstname" class="form-label">Firstname</label>
                  <input type="text" class="form-control" id="firstname" placeholder="Firstname...">
                </div>
                <div class="col-md-4">
                  <label for="middlename" class="form-label">Middlename</label>
                  <input type="text" class="form-control" id="middlename" placeholder="Middlename...">
                </div>
                <div class="col-md-4">
                  <label for="lastname" class="form-label">Lastname</label>
                  <input type="text" class="form-control" id="lastname" placeholder="Lastname...">
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <label for="birthdate" class="form-label">Birthdate</label>
                </div>
                <div class="col-md-4">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <input type="text" class="form-control" id="birthplace" placeholder="Birthplace...">
                </div>
                <div class="col-md-4">
                  <label for="gender" class="form-label">Gender</label>
                  <select class="form-select" aria-label="Default select example" id="gender">
                    <option selected disabled>Select</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="occupation" class="form-label">Occupation</label>
                  <input type="text" class="form-control" id="occupation" placeholder="Occupation...">
                </div>
                <div class="col-md-3">
                  <label for="nature" class="form-label">Nature of work</label>
                  <select class="form-select" aria-label="Default select example" id="nature">
                    <option selected value="1">None</option>
                    <option value="1">Architecture and Engineering</option>
                    <option value="2">Arts, Culture and Entertainment</option>
                    <option value="2">Business, Management and Administration</option>
                    <option value="2">Communications</option>
                    <option value="2">Community and Social Services</option>
                    <option value="2">Education</option>
                    <option value="2">Science and Technology</option>
                    <option value="2">Installation, Repair and Maintenance</option>
                    <option value="2">Farming, Fishing and Forestry</option>
                    <option value="2">Government</option>
                    <option value="2">Health and Medicine</option>
                    <option value="2">Law and Public Policy</option>
                    <option value="2">Sales</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="civil" class="form-label">Civil Status</label>
                  <select class="form-select" aria-label="Default select example" id="civil">
                    <option selected disabled>Select</option>
                    <option value="1">Single</option>
                    <option value="2">Married</option>
                    <option value="2">Divorced</option>
                    <option value="2">Widowed</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="religion" class="form-label">Religion</label>
                  <input type="text" class="form-control" id="religion" placeholder="Religion...">
                </div>
              </div>
              <div class="row">
                <div class="col-md-3">
                  <label for="fposition" class="form-label">Family Position</label>
                  <select class="form-select" aria-label="Default select example" id="fposition">
                    <option selected disabled>Family Position</option>
                    <option value="1">Wife</option>
                    <option value="2">Child</option>
                    <option value="3">Brother</option>
                    <option value="3">Sister</option>
                    <option value="3">Grandfather</option>
                    <option value="3">Grandmother</option>
                    <option value="3">Relative</option>
                  </select>
                </div>
                <div class="col-md-3">
                  <label for="contact" class="form-label">Contact #</label>
                  <input type="text" id="contact" class="form-control" />
                </div>
                <div class="col-md-6">
                  <label for="typeemail" class="form-label">Email</label>
                  <input type="email" id="typeemail" class="form-control" />
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editconfmodal">Confirm</button><button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#detailres">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- edit pop-up modal end -->
    <!-- edit confirmation pop-up modal -->
    <div class="modal fade" id="editconfmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title">Updated Resident Information</h5>
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
              <div class="col-md-4">
                <label for="age" class="form-label">Age</label>
                <p id="age" class="text-start fs-3">40</p>
              </div>
              <div class="col-md-4">
                <label for="birthdate" class="form-label">Age</label>
                <p id="birthdate" class="text-start fs-3">01/10/1970</p>
              </div>
              <div class="col-md-4">
                <label for="gender" class="form-label">Gender</label>
                <p id="age" class="text-start fs-3">Male</p>
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
            <div class="row">
              <div class="col-md-6">
                <label for="username" class="form-label">Username</label>
                <p id="username" class="text-start fs-3">delacruzJ70</p>
              </div>
              <div class="col-md-6">
                <label for="password" class="form-label">Password</label>
                <p id="password" class="text-start fs-3">1970110010dj</p>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editconfir">Save</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#editmodal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- edit confirmation pop-up end modal -->
    <!-- edit password pop-up modal -->
    <div class="modal fade" id="editpass" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">User Password</h5>
                </div>
                <div class="modal-body">
                  <div class="row">
                    <p class="text-center fs-5">Password must contain an Uppercase and Lowercase letters, with numbers.</p>
                    <div class="col-md-6">
                      <label for="npass" class="form-label">New Password</label>
                      <input type="npass" class="form-control" id="firstname" placeholder="Firstname...">
                    </div>
                    <div class="col-md-6">
                      <label for="cpass" class="form-label">Confirm Password</label>
                      <input type="cpass" class="form-control" id="firstname" placeholder="Firstname...">
                    </div>
                  </div>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editpassconf">Save</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <!-- rep pop-up end modal -->
    <!-- edit password confirmation pop-up modal -->
    <div class="modal fade" id="editpassconf" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">User Password</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">You successfully updated your password.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- rep pop-up end modal -->
    <!-- edit password pop-up modal -->
    <div class="modal fade" id="editconfir" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Updated Resident Information</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Changes to your information is now pending.</p>
                    <p class="text-center fs-5">A message notification will be sent through your contact number regarding of the approval.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- rep pop-up end modal -->
    <script src="js/bttop.js"></script>
</nav>
</body>
</html>