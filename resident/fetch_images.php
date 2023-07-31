<?php

//fetch_images.php

include('dbcon.php');

$query = "SELECT * FROM image_tbl ORDER BY id DESC";

$statement = $connect->prepare($query);

$output = '<div class="row">';

if($statement->execute())
{
 $result = $statement->fetchAll();

 foreach($result as $row)
 {
  $output .= '
  <div class="col-md-2" style="margin-bottom:16px;">
   <img src="data:image/png;base64,'.base64_encode($row['images']).'" class="img-thumbnail" />
  </div>
  ';
 }
}

$output .= '</div>';

echo $output;

?>