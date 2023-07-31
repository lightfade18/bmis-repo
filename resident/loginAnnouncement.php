<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
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
            <a class="navbar-brand text-uppercase fs-4" href="loginHomepage.php">
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
                    <li class="nav-item active">
                        <a href="loginAnnouncement.php" class="nav-link active">
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
            <div class="row">
                <div class="col-lg-12 mt-1">
                    <p class="text-end"><a href="#top" class="text-dark border border-white p-3 rounded bg-light"><i class="bi bi-chevron-up"></i></a></p>
                </div>
            </div>
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
    <script
    type="text/javascript"
    src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.js"
    ></script>
    <!-- <script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous">   
    </script> -->
    <script src="js/script.js"></script>
    <script src="js/bttop.js"></script>
</nav>
</body>
</html>