<?php
require 'dbcon.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crop image before upload and insert to database using PHP Mysqli and CropperJS </title>
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
</head>
<body>
  <div class="container"><br>
      <h3 align="center">Image Crop and Save into Database using PHP with Ajax</h3>
      <br />
      <br />
    <div class="panel panel-default">
      <div class="panel-heading">Select Profile Image</div>
        <div class="panel-body" align="center">
          <input type="file" name="insert_image" id="insert_image" accept="image/*" />
          <br />
        <div id="store_image"></div>
      </div>
    </div>
  </div>

  <div id="insertimageModal" class="modal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Crop & Insert Image</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-8 text-center">
              <div id="image_demo" style="width:350px; margin-top:30px"></div>
            </div>
            <div class="col-md-4" style="padding-top:30px;">
          <br />
          <br />
          <br/>
              <button class="btn btn-success crop_image">Crop & Insert Image</button>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <script>  
  $(document).ready(function(){

  $image_crop = $('#image_demo').croppie({
      enableExif: true,
      viewport: {
        width:200,
        height:200,
        type:'square' //circle
      },
      boundary:{
        width:300,
        height:300
      }    
    });

    $('#insert_image').on('change', function(){
      var reader = new FileReader();
      reader.onload = function (event) {
        $image_crop.croppie('bind', {
          url: event.target.result
        }).then(function(){
          console.log('jQuery bind complete');
        });
      }
      reader.readAsDataURL(this.files[0]);
      $('#insertimageModal').modal('show');
    });

    $('.crop_image').click(function(event){
      $image_crop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
      }).then(function(response){
        $.ajax({
          url:'insert.php',
          type:'POST',
          data:{"image":response},
          success:function(data){
            $('#insertimageModal').modal('hide');
            load_images();
            alert(data);
          }
        })
      });
    });

    load_images();

    function load_images()
    {
      $.ajax({
        url:"fetch_images.php",
        success:function(data)
        {
          $('#store_image').html(data);
        }
      })
    }

  });  
  </script>
</body>
</html>