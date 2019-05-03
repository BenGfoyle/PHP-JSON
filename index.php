<?php
function getJSON(){
   //Retrieve JSON file and return it as an array
   // read json file
   $data = file_get_contents('readingList.json');
   // decode json to associative array
   $json_arr = json_decode($data, true);
   return $json_arr;
}
function userID(){
   //Generate unique user ID based on username
   $name = $_POST['name'];
   $hash = time(); //time() returns current time as int, use it as base for unqique ID
   for($i = 0; $i < strlen($name); $i++){
     $hash = $hash + (ord($name[$i])**($i+1));
   }
   //keep string small by changing base
   $hash = base64_encode($hash);
   return $hash;
}
$message = '';
$error = '';
// Add Data
function addThis(){
  if(empty($_POST["name"]))//check for blanks
  {
       $error = "<label class='text-danger'>Enter Name</label>";
  }
  else if(empty($_POST["url"])) //check for blanks
  {
       $error = "<label class='text-danger'>Enter URL</label>";
  }

  else if(empty($_POST["desc"])) //check for blanks
  {
       $error = "<label class='text-danger'>Enter Description</label>";
  }
  else //add the data
  {
       if(file_exists('readingList.json')) //&& checkDupes($_POST['name']) == false)
       {
            $json_arr = getJSON();
            $currentDate = date("Y/m/d");
            $newID = userID();
            $extra = array(
                  'name'    =>     $_POST['name'],
                  'url'     =>     $_POST["url"],
                  'date'    =>     $currentDate,
                  'id'      =>     $newID,
                  'desc'    =>     $_POST["desc"]
            );
            $json_arr[] = $extra;
            $final_data = json_encode($json_arr);
            if(file_put_contents('readingList.json', $final_data))
            {
                   $message = "<label class='text-success'>File Appended Success fully</p>";
              }
            }
       }
  }

//Delte Data
function deleteThis(){
  $json_arr = getJSON();
  // get array index to delete
  $arr_index = array();
  foreach ($json_arr as $key => $value)
  {
    if ($value['id'] == $_POST['id'] | $value['name'] == $_POST['name'])
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

//Get Data
function getThis(){
  $json_arr = getJSON();
  // get array index to delete
  $arr_index = array();


  //iterate through list for the needed id
  for($i = 0; $i < sizeof($json_arr); $i++){
    if ($json_arr[$i]['id'] === $_POST['id'] | $json_arr[$i]['name'] === $_POST['name']){
      $userid = (string)$json_arr[$i]['id'];
      $userName = (string)$json_arr[$i]['name'];
      $userdate = (string)$json_arr[$i]['date'];
      $userurl = (string)$json_arr[$i]['url'];
      $userDesc = (string)$json_arr[$i]['desc'];
      $message = "ID: ".$userid." Name: ".$userName." Date: ".$userdate." URL: ".$userurl. " Description: ".$userDesc;
      echo "<p>('$message');</p>"; //create alert with required info
    }
  }

  // rebase array
  $json_arr = array_values($json_arr);
  // encode array to json and save to file
  file_put_contents('readingList.json', json_encode($json_arr));
}

//if post for each button
function editThis(){
  $json_arr = getJSON();
  // get aBenrray index to delete
  $arr_index = array();

  $userName = $_POST['name'];
  $userURL = $_POST["url"];
  $userDesc = $_POST["desc"];

  for($i = 0; $i < sizeof($json_arr); $i++){
    if ($json_arr[$i]['id'] == $_POST['id']){
      $arr_index[] = $i;
      $newID = userID();
      //check if inputs were empty, assume

      if(empty($userName))//check for blanks
      {
           $userName = $json_arr[$i]['name'];
      }

      if(empty($userURL))//check for blanks
      {
           $userURL = $json_arr[$i]['url'];
      }

      if(empty($userDesc))//check for blanks
      {
           $userDesc = $json_arr[$i]['desc'];
      }

      $extra = array(
           'name'    =>     $userName,
           'url'     =>     $userURL,
           'date'    =>     $json_arr[$i]['date'],
           'id'      =>     $newID,
           'desc'    =>     $userDesc
      );
      $json_arr[] = $extra;
      deleteThis();
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

//isset for each button
if(isset($_POST["edit"])){
  editThis();
}

if(isset($_POST["delete"])){
  deleteThis();
}

if(isset($_POST["add"])){
  addThis();
}

if(isset($_POST["get"])){
  getThis();
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
     <style>

      table, th, td {
      border: 1px solid black;
      }
      <style>
      * {box-sizing: border-box}
      body {font-family: "Lato", sans-serif;}
      /* Create two equal columns that floats next to each other */
      /* Create two equal columns that floats next to each other */

      body{
        background-color: #FAF3DD;
      }
      .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 75px; /* Should be removed. Only for demonstration */
      }

      /* Clear floats after the columns */
      .row:after {
        content: "";
        display: table;
        clear: both;
      }

      .content {
        width: 240px;
        padding: 5px;
        overflow: hidden;
        }

      .content img style = "background-color: white" {
          margin-right: 10px;
          float: right;
      }

      /* Style the tab */
      .tab {
        float: left;
        background-color: #8fc1a9;
        width: 30%;
        height: 1500px;
      }

      /* Style the buttons inside the tab */
      .tab button {
        display: block;
        background-color: inherit;
        color: black;
        padding: 22px 16px;
        width: 100%;
        border: none;
        outline: none;
        text-align: left;
        cursor: pointer;
        transition: 0.3s;
        font-size: 17px;
      }

      /* Change background color of buttons on hover */
      .tab button:hover {
        background-color: #9DBAD5;
      }

      /* Create an active/current "tab button" class */
      .tab button.active {
        background-color: #9DBAD5;
      }

      /* Style the tab content */
      .tabcontent {
        float: left;
        padding: 0px 12px;
        width: 70%;
        border-left: none;
        height: 1500px;
      }
     </style>
     <body>

       <div class="tab">
         <button class="tablinks" onclick="openSection(event, 'AllData')" id="defaultOpen">Display All Data</button>
         <button class="tablinks" onclick="openSection(event, 'Create')">Create</button>
         <button class="tablinks" onclick="openSection(event, 'Retrieve')">Retrieve</button>
         <button class="tablinks" onclick="openSection(event, 'Update')">Update</button>
         <button class="tablinks" onclick="openSection(event, 'Delete')">Delete</button>
         <button class="tablinks" onclick="openSection(event, 'Refs')">References</button>
       </div>

<!--Display All Data-->
       <div id="AllData" class="tabcontent">
         <table align="center">
             <tr>
                 <th>Name</th>
                 <th>URL</th>
                 <th>Date</th>
                 <th>ID</th>
                 <th>Description</th>
             </tr>
             <tbody>
                 <?php
                  $getfile = file_get_contents('readingList.json');
                  $json = json_decode($getfile);
                  foreach ($json as $obj): ?>
                     <tr>
                         <td><?php echo $obj->name; ?></td>
                         <td><a href = '<?php echo $obj->url; ?>'><?php echo $obj->url; ?></a></td>
                         <td><?php echo $obj->date; ?></td>
                         <td><?php echo $obj->id; ?></td>
                         <td><?php echo $obj->desc; ?></td>
                     </tr>
                 <?php endforeach; ?>
             </tbody>
         </table>
       </div>

<!--Get Data-->
      <div id = "Retrieve" class = "tabcontent">
          <br />
          <div class="container" style="width:500px;">
               <h3 align="">Get Data from JSON File</h3><br />
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
                    <label>ID</label>
                    <input type="text" name="id" class="form-control" /><br />
                    <input type="submit" name="get" value="Retrieve" class="btn btn-info" /><br />
                    <?php
                    if(isset($message))
                    {
                         echo $message;
                    }
                    ?>
               </form>
          </div>
          <br />
      </div>

<!--Append Data-->
      <div id="Create" class="tabcontent">
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
                    <label>URL</label>
                    <input type="text" name="url" class="form-control" /><br />
                    <label>Description</label>
                    <input type="textbox" name="desc" class="form-control" /><br />
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
      </div>

<!--Delete Data-->
      <div id="Delete" class="tabcontent">
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
                    <input type="text" name="name" class="form-control" /><br />
                    <label>ID</label>
                    <input type="text" name="id" class="form-control" /><br />
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
      </div>

<!--Edit Data-->
      <div id="Update" class="tabcontent">
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
                    <label>User ID</label>
                    <input type="text" name="id" class="form-control" /><br />
                    <label>New Name</label>
                    <input type="text" name="name" class="form-control" /><br />
                    <label>New URL</label>
                    <input type="text" name="url" class="form-control" /><br />
                    <label>New Description</label>
                    <input type="text" name="desc" class="form-control" /><br />
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
      </div>
<!--References-->
     <div id="Refs" class="tabcontent">
      <h3 align="center">Thank you to the following:</h3><br>
      <h5 align = "center"><a href = "https://www.w3schools.com/php/">W3Schools<a> php guide<a></h5>
      <h5 align = "center"><a href = "https://www.webslesson.info/search/label/php">Weblesson<a> PHP guide</h5>
      <h5 align = "center"><a href =  "https://www.amazon.com/Learning-PHP-MySQL-JavaScript-Javascript/dp/1491918667">Robin Nixon<a>Learning PHP, MySQL & JavaScript</h5>
      <h5 align = "center"><a href =  "http://www.hplovecraft.com/">HP Lovecraft<a></h5>

     </div>

    <script>
      //open tabs
      function openSection(evt, sectionName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
          tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
          tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(sectionName).style.display = "block";
        evt.currentTarget.className += " active";
      }
      // Get the element with id="defaultOpen" and click on it
      document.getElementById("defaultOpen").click();
    </script>
    </body>
</html>
