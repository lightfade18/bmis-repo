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
  </head>
  <body>
    <div class="container">
      <div class="forms-container">
        <div class="signin-signup">
          <form action="#" class="sign-in-form">
            <div class="row">
              <div class="col-md-12">
                <p>Your Password is successfully updated!</p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <a href="login.php" class="btn btn-primary">Ok</a>
              </div>
            </div>
          </form>
          <form action="#" class="sign-up-form">
            <h2 class="title">Become a part of our community!</h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#registermodal">Register</button>
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
            <!-- <button class="btn transparent text-white" id="sign-up-btn">
              Sign up
            </button> -->
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
    <!-- requirements pop-up modal -->
    <div class="modal fade" id="registermodal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Registration Requirements</h5>
          </div>
          <div class="modal-body">
            <p class="text-start fs-5 ps-3">Household Code (Code can be retrieve from a head of a household that is registered on our barangay.)</p>
            <p class="text-start fs-5 ps-3">Valid ID(e.g. School ID, Company/Office ID, Voter’s ID and etc.)</p>
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
          <div class="modal-header">
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
          <div class="modal-header">
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
            <h5 class="modal-title">Individual Information</h5>
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
                    <p class="text-center fs-5">Further notification will be sent to your used email account.</p>
                </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <!-- CTC pop-up end modal -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="js/app.js"></script>
  </body>
</html>
