<?php

function getJSON(){
  // read json file
  $data = file_get_contents('readingList.json');

  // decode json to associative array
  $json_arr = json_decode($data, true);
  return $json_arr;
}

function checkDupes($name){
  //check for duplicates by checking for error
  json_decode($name);
  return (json_last_error() == JSON_ERROR_NONE);
}

function userID(){
    //generate unique user ID based on username
      $name = $_POST['name'];
      $hash = 1;
      for($i = 0; $i < strlen($name); $i++){
        $hash = $hash + (ord($name[$i])**$i);
      }

      //keep string small by changing base
      $hash = base64_encode($hash);

      return $hash;
}

$message = '';
$error = '';

// Edit Data
if(isset($_POST["add"]))
{
     if(empty($_POST["name"]))//check for blanks
     {
          $error = "<label class='text-danger'>Enter Name</label>";
     }
     else if(empty($_POST["url"])) //check for blanks
     {
          $error = "<label class='text-danger'>Enter URL</label>";
     }
     else //add the data
     {
          if(file_exists('readingList.json')) //&& checkDupes($_POST['name']) == false)
          {
               $json_arr = getJSON();
               $currentDate = time();
               $newID = userID();

               $extra = array(
                    'name'               =>     $_POST['name'],
                    'url'          =>     $_POST["url"],
                    'date'     =>     $currentDate,
                    'id'      =>     $newID
               );
               $array_data[] = $extra;
               $final_data = json_encode($array_data);
               if(file_put_contents('readingList.json', $final_data))
               {
                    $message = "<label class='text-success'>File Appended Success fully</p>";
               }
          }
          else
          {
               $message = "Name already taken, please try again";
               echo "<script type='text/javascript'>alert('$message');</script>";
          }
     }
}

//Delte Data
if(isset($_POST["delete"])){
    $json_arr = getJSON();

    // get array index to delete
    $arr_index = array();
    foreach ($json_arr as $key => $value)
    {
      if ($value['id'] == $_POST['delID'] || $value['name'] == $_POST['delName'])
      {
          $arr_index[] = $key;
      }
    }

    // delete data
    foreach ($arr_index as $i)
    {
      unset($json_arr[$i]);
    }

    // rebase array
  $json_arr = array_values($json_arr);

  // encode array to json and save to file
  file_put_contents('readingList.json', json_encode($json_arr));
}

//Edit Data
if(isset($_POST["edit"])){

  $json_arr = getJSON();

  // get array index to delete
  $arr_index = array();
  foreach ($json_arr as $key => $value)
  {
    if ($value['id'] == $_POST['delID'] || $value['name'] == $_POST['delName'])
    {
        $arr_index[] = $key;
    }
  }

  // delete data
  foreach ($arr_index as $i)
  {
    unset($json_arr[$i]);
  }

  // rebase array
$json_arr = array_values($json_arr);

// encode array to json and save to file
file_put_contents('readingList.json', json_encode($json_arr));
}

?>
<!DOCTYPE html>
<html>
     <head>
          <title>CS230 Lab 5</title>
          <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
          <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
          <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
     </head>
     <body>

       <table align="center">
           <tr>
               <th>Name</th>
               <th>URL</th>
               <th>Date</th>
               <th>ID</th>
               <th></th>
           </tr>
           <tbody>
               <?php
                $getfile = file_get_contents('readingList.json');
                $json = json_decode($getfile);
                foreach ($json as $obj): ?>
                   <tr>
                       <td><?php echo $obj->name; ?></td>
                       <td><?php echo $obj->url; ?></td>
                       <td><?php echo $obj->date; ?></td>
                       <td><?php echo $obj->id; ?></td>
                   </tr>
               <?php endforeach; ?>
           </tbody>
       </table>

<!--Append Data-->
          <br />
          <div class="container" style="width:500px;">
               <h3 align="">Add Data to JSON File</h3><br />
               <form method="post">
                    <?php
                    if(isset($error))
                    {
                         echo $error;
                    }
                    ?>
                    <br />
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" /><br />
                    <label>url</label>
                    <input type="text" name="url" class="form-control" /><br />
                    <input type="submit" name="add" value="Append" class="btn btn-info" /><br />
                    <?php
                    if(isset($message))
                    {
                         echo $message;
                    }
                    ?>
               </form>
          </div>
          <br />


<!--Delete Data-->
          <br />
          <div class="container" style="width:500px;">
               <h3 align="">Delete Data from JSON File</h3><br />
               <form method="post">
                    <?php
                    if(isset($error))
                    {
                         echo $error;
                    }
                    ?>
                    <br />
                    <label>Name</label>
                    <input type="text" name="delName" class="form-control" /><br />
                    <label>ID</label>
                    <input type="text" name="delID" class="form-control" /><br />
                    <input type="submit" name="delete" value="Delete" class="btn btn-info" /><br />
                    <?php
                    if(isset($message))
                    {
                         echo $message;
                    }
                    ?>
               </form>
          </div>
          <br />

<!--Edit Data-->

          <br />
          <div class="container" style="width:500px;">
               <h3 align="">Edit Data from JSON File</h3><br />
               <form method="post">
                    <?php
                    if(isset($error))
                    {
                         echo $error;
                    }
                    ?>
                    <br />
                    <label>Old Name</label>
                    <input type="text" name="OldName" class="form-control" /><br />
                    <label>New Name</label>
                    <input type="text" name="newName" class="form-control" /><br />
                    <label>Old URL</label>
                    <input type="text" name="oldURL" class="form-control" /><br />
                    <label>New URL</label>
                    <input type="text" name="newURL" class="form-control" /><br />
                    <input type="submit" name="edit" value="Edit" class="btn btn-info" /><br />
                    <?php
                    if(isset($message))
                    {
                         echo $message;
                    }
                    ?>
               </form>
          </div>
          <br />
     </body>
</html>
