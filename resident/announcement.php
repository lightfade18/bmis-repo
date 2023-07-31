<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Announcements</title>
    <!-- <link
    href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.css"
    rel="stylesheet"
    /> -->
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
                        <a href="homepage.php" class="nav-link">
                            Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="officials.php" class="nav-link">
                            Officials
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a href="announcement.php" class="nav-link active">
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
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#brgycert">Business Certificate</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#brgycert">Community Tax Certificate</a></li>
                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#brgycert">Reproduction of Tax Records</a></li>
                            <hr class="dropdown-divider">
                            <li><a class="dropdown-item"href="#" data-bs-toggle="modal" data-bs-target="#brgycert">Complaint</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="aboutus.php" class="nav-link">
                            About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">
                            Sign-in
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
    <!-- navbar end -->

    <!-- main content -->
    <section id="anblog" class="mt-5 pt-4 bg-dark">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="anblog-title text-white">
                        <h1>Announcements</h1>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="ansingle_post">
                    <div class="col-lg-12 bg-light border border-dark rounded text-dark p-3">
                        <h3>Announcement Title</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At sapiente culpa soluta illum. Repellat saepe a non ea porro, suscipit voluptas placeat quia veniam nisi eius maiores debitis sint possimus.</p>
                    </div>
                </div> 
            </div>
            <div class="row mt-1">
                <div class="ansingle_post">
                    <div class="col-lg-12 bg-light border border-dark rounded text-dark p-3">
                        <h3>Announcement Title</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At sapiente culpa soluta illum. Repellat saepe a non ea porro, suscipit voluptas placeat quia veniam nisi eius maiores debitis sint possimus.</p>
                    </div>
                </div> 
            </div>
            <div class="row mt-1">
                <div class="ansingle_post">
                    <div class="col-lg-12 bg-light border border-dark rounded text-dark p-3">
                        <h3>Announcement Title</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At sapiente culpa soluta illum. Repellat saepe a non ea porro, suscipit voluptas placeat quia veniam nisi eius maiores debitis sint possimus.</p>
                    </div>
                </div> 
            </div>
            <div class="row mt-1">
                <div class="ansingle_post">
                    <div class="col-lg-12 bg-light border border-dark rounded text-dark p-3">
                        <h3>Announcement Title</h3>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. At sapiente culpa soluta illum. Repellat saepe a non ea porro, suscipit voluptas placeat quia veniam nisi eius maiores debitis sint possimus.</p>
                    </div>
                </div> 
            </div>
            <div class="row pt-3">
                <div class="col-lg-12">
                    <div class="anload_more mt-0">
                        <p class="text-center"><button class="anload btn btn-light rounded-pill">Load more</button></p>
                        <p class="text-center"><button class="anloadless btn btn-light rounded-pill">See less</button></p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <section id="blog" class="pt-4">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="blog-title">
                        <h1>Events</h1>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row announ">
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce1.jpg" class="img-fluid ripple hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce2.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="single_post">
                        <div class="bg-image mt-2 border border-dark p-1 rounded">
                            <img src="images/announce3.jpg" class="img-fluid hover-shadow" alt="Announcement poster" />
                            <div class="mask" style="background-color: rgba(0, 0, 0, 0.1);">
                                <div class="d-flex justify-content-start align-items-end h-100 ps-4 pb-4">
                                    <button class="btn btn-light" data-bs-toggle="modal" data-bs-target="#viewdet">View details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row pt-3">
                <div class="col-lg-12">
                    <div class="load_more mt-0">
                        <p class="text-center"><button class="load btn btn-dark rounded-pill">Load more</button></p>
                        <p class="text-center"><button class="loadless btn btn-dark rounded-pill">See less</button></p>
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
                    ><i class="bi bi-facebook"></i>
                </a>
                <!-- Twitter -->
                <a
                    class="btn btn-link btn-floating btn-lg text-white m-1 border border-white rounded"
                    href="#!"
                    role="button"
                    data-mdb-ripple-color="dark"
                    ><i class="bi bi-twitter"></i>
                </a>
                <!-- Instagram -->
                <a
                    class="btn btn-link btn-floating btn-lg text-white m-1 border border-white rounded"
                    href="#!"
                    role="button"
                    data-mdb-ripple-color="dark"
                    ><i class="bi bi-instagram"></i>
                </a>
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
    <!-- view details pop-up modal -->
    <div class="modal fade" id="viewdet" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            <div class="modal-body">
                ...
            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div>
            </div>
        </div>
    </div>
    <!-- view details pop-up modal end -->
    <!-- services pop-up modal -->
    <div class="modal fade" id="brgycert">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Service Information</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">You need to become a registered resident to avail our service.</p>
                </div>
                    <div class="modal-footer">
                        <p>Register now!<button type="button" class="btn btn-primary ms-3" data-bs-toggle="modal" data-bs-target="#registermodal">Sign-up</button></p>
                </div>
            </div>
        </div>
    </div>
    <!-- services pop-up end modal -->
    <!-- requirements pop-up modal -->
    <div class="modal fade" id="registermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title" id="staticBackdropLabel">Registration Requirements</h5>
          </div>
          <div class="modal-body">
            <p class="text-start fs-5 ps-3">Household Code (Code can be retrieve from a head of a household that is registered on our barangay.)</p>
            <p class="text-start fs-5 ps-3">Picture of your valid I.D.(e.g. School ID, Company/Office ID, Voter’s ID and etc.)</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#datapri">Continue</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- requirements pop-up modal end -->
    <!-- data privacy pop-up modal -->
    <div class="modal fade" id="datapri" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title" id="staticBackdropLabel">Data Privacy Act of 2012</h5>
          </div>
          <div class="modal-body">
            <p class="text-start fs-5 ps-3">Declaration of Policy. – It is the policy of the State to protect the fundamental human right of privacy, of communication while ensuring free flow of information to promote innovation and growth. The State recognizes the vital role of information and communications technology in nation-building and its inherent obligation to ensure that personal information in information and communications systems in the government and in the private sector are secured and protected.</p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#censusmodal">I Agree</button>
            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#registermodal">I Disagree</button>
          </div>
        </div>
      </div>
    </div>
    <!-- data privacy pop-up modal end -->
    
    <!-- census form pop-up modal -->
    <div class="modal fade" id="censusmodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title" id="staticBackdropLabel">Registration Requirements</h5>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <label for="hcode" class="form-label">Household Code</label>
              <input type="text" class="form-control" id="hcode" placeholder="Enter here...">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addindividual">Submit</button>
            <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addmodal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- census form pop-up modal end -->
    <!-- adding individual pop-up modal -->
    <div class="modal fade p-0" id="addindividual" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header p-3 mb-2 bg-secondary text-white">
            <h5 class="modal-title">Census Form</h5>
          </div>
          <div class="modal-body">
            <form action="" method="post">
              <div class="row">
                <div class="col-lg-4">
                  <label for="firstname" class="form-label">Firstname</label>
                  <input type="text" class="form-control" id="firstname" placeholder="Firstname...">
                </div>
                <div class="col-lg-4">
                  <label for="middlename" class="form-label">Middlename</label>
                  <input type="text" class="form-control" id="middlename" placeholder="Middlename...">
                </div>
                <div class="col-lg-4">
                  <label for="lastname" class="form-label">Lastname</label>
                  <input type="text" class="form-control" id="lastname" placeholder="Lastname...">
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <label for="birthdate" class="form-label">Birthdate</label>
                </div>
                <div class="col-lg-5">
                  <label for="birthplace" class="form-label">Birthplace</label>
                  <input type="text" class="form-control" id="birthplace" placeholder="Birthplace...">
                </div>
                <div class="col-lg-2">
                  <label for="gender" class="form-label">Gender</label>
                  <select class="form-select" aria-label="Default select example" id="gender">
                    <option selected disabled>Select</option>
                    <option value="1">Male</option>
                    <option value="2">Female</option>
                  </select>
                </div>
                <div class="col-lg-2">
                  <label for="citizenship" class="form-label">Citizenship</label>
                  <select class="form-select" aria-label="Default select example" id="citizenship">
                    <option selected value="1">Filipino</option>
                    <option value="2">American</option>
                    <option value="3">Chinese</option>
                    <option value="4">Indian</option>
                    <option value="5">Others</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
                  <label for="occupation" class="form-label">Occupation</label>
                  <input type="text" class="form-control" id="occupation" placeholder="Occupation...">
                </div>
                <div class="col-lg-3">
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
                <div class="col-lg-3">
                  <label for="civil" class="form-label">Civil Status</label>
                  <select class="form-select" aria-label="Default select example" id="civil">
                    <option selected disabled>Select</option>
                    <option value="1">Single</option>
                    <option value="2">Married</option>
                    <option value="2">Divorced</option>
                    <option value="2">Widowed</option>
                  </select>
                </div>
                <div class="col-lg-3">
                  <label for="religion" class="form-label">Religion</label>
                  <select class="form-select" aria-label="Default select example" id="religion">
                    <option selected disabled>Select</option>
                    <option value="1">Roman Catholic</option>
                    <option value="2">Iglesia ni Cristo</option>
                    <option value="3">Jehova's Witnesses</option>
                    <option value="4">Born Again</option>
                    <option value="5">Others</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-3">
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
                <div class="col-lg-3">
                  <label for="contact" class="form-label">Contact #</label>
                  <input type="text" id="contact" class="form-control" />
                </div>
                <div class="col-lg-6">
                  <label for="typeemail" class="form-label">Email</label>
                  <input type="email" id="typeemail" class="form-control" />
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="customFile">Identification Card (Photocopy)</label>
                    <input type="file" class="form-control" id="customFile" />
                  </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#confaddindividual">Add</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>
    <!-- adding individual pop-up modal end -->
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
    <!-- CTC confirmation pop-up modal -->
    <div class="modal fade" id="confmodl" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header p-3 mb-2 bg-secondary text-white">
                    <h5 class="modal-title">Resident Registration</h5>
                </div>
                <div class="modal-body">
                    <p class="text-center fs-5">Your registration is now pending.</p>
                    <p class="text-center fs-5">Further notification will be sent via text message through your contact number.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CTC pop-up end modal -->
    <!-- <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.js"
    ></script> -->
    <script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous">   
    </script>
    <script src="js/script.js"></script>
    <script src="js/bttop.js"></script>
</body>
</html>