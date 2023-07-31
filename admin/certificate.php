<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
  <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
  <style>
    .box{
      width: 150px;
      height: 150px;
      border: 2px solid black;
    }
    @media print{
        #print {
            display:none;
        }
    }
    @media print {
        #PrintButton {
            display: none;
        }
    }

    @page {
        size: auto;   /* auto is the initial value */
        margin: 0;  /* this affects the margin in the printer settings */
    }
  </style>
</head>
<body>
<div class="container m-5">
  <div class="row">
    <div class="col-md-3">
      <p class="text-center">Column</p> 
    </div>
    <div class="col-md-6">
      <p class="text-center mb-0 pb-0 pt-0 fs-5">Republic of the Philippines</p>
      <p class="text-center fw-bold mb-0 mt-0 pb-0 pt-0 fs-4">Barangay Concepcion Dos</p>
      <p class="text-center mb-0 mt-0 pb-0 pt-0 fs-5">CITY OF MARIKINA</p>
      <p class="text-center mt-0 pb-0 pt-0 fs-5">OFFICE OF THE PUNONG BARANGAY</p>
    </div>
    <div class="col-md-3">
      <p class="text-center">Column</p> 
    </div>
  </div>
  <div class="row mt-2">
    <div class="col-md-12">
      <p class="text-center fw-bold mb-0 mt-0 fs-3">BARANGAY CLEARANCE/CERTIFICATION</p>
    </div>
  </div>
  <div class="row mt-5">
    <div class="col-md-5">
      <p class="text-start mb-0 mt-0 fs-5">TO WHOM IT MAY CONCERN:</p>
    </div>
    <div class="col-md-7">
      <p class="text-end fw-bold mb-0 mt-0 fs-5 fw-bold">No. 2022-01259</p>
    </div>
  </div>
  <div class="row">
    <div class="col-md-12">
      <p class="mb-0 mt-0 fs-5" style="text-align: justify; text-justify: inter-word;">This is to certify that the person whose name, right thumbmark and picture appear hereon has requested for a record and Baranagay Clearance from this Office and the result/s are listed below.</p>
    </div>
  </div>
  <div class="row m-3">
    <div class="col-md-8">
      <p class="text-start mb-0 mt-0 fs-5" ><b>NAME:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUAN</p>
      <p class="text-start mb-0 mt-0 fs-5" ><b>ADDRESS:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUAN</p>
      <p class="text-start mb-0 mt-0 fs-5" ><b>DOB:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUAN</p>
      <p class="text-start mb-0 mt-0 fs-5" ><b>POB:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUAN</p>
      <p class="text-start mb-0 mt-0 fs-5" ><b>PURPOSE:</b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;JUAN</p>
      <p class="mb-0 mt-3 fs-5" style="text-align: justify; text-justify: inter-word;">This is to further certify that he/she is a bonafide resident of this Barangay since ______ and that he/she has no derogatory record on file with this office.</p>
      <p class="mb-0 mt-0 fs-5" style="text-align: justify; text-justify: inter-word;">Issued upon the request of the above named person this ___ day of ________, 2022 at Barangay Concepcion Dos, Marikina City and is valid for six (6) months hereof.</p>
    </div>
    <div class="col-md-4">
      <br><br><br>
      <p class="text-center ms-5 mb-0 mt-0 fs-5" >PHOTO AREA</p>
    </div>
  </div>
  <div class="row mt-3">
    <div class="col-md-3">
      <p class="text-center mb-0 mt-3 fs-5"><div class="box"></div></p>
      <p class="text-start mb-0 mt-3 fs-5">Right Thumbmark</p>
    </div>
    <div class="col-md-5">
      <p class="text-start mb-0 mt-0 fs-5">OR #:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________</p>
      <p class="text-start mb-0 mt-0 fs-5">OR Date:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________</p>
      <p class="text-start mb-0 mt-0 fs-5">CTC #:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________</p>
      <p class="text-start mb-0 mt-0 fs-5">Date Issued:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________</p>
      <p class="text-start mb-0 mt-0 fs-5">Place Issued:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________</p>
      <p class="text-start mb-0 mt-0 fs-5">TIN:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;_______________________</p>
    </div>
    <div class="col-md-4 mt-0 mb-0">
      <br><br><br><br><br>
      <p class="text-center mb-0 mt-0 fs-5">PUNONG BARANGAY NAME</p>
      <p class="text-center mb-0 mt-0 fs-5">________________________________</p>
      <p class="text-center mb-0 mt-0 fs-5">Punong Barangay</p>
    </div>
  </div>
  <br><br><br>
  <button onclick="window.print();"class="btn btn-success">Release</button>
</div>
</body>
</html>