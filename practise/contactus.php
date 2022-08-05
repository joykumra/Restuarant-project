<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Global result of form validation
$valid = false;

require 'config.php';
function db_connect() {

  try {
    // TODO
    // try to open database connection using constants set in config.php
    // return $pdo;

    $connectionString = "mysql: host=" . DBHOST . ";dbname". DBNAME;
    $user = DBUSER;
    $pass = DBPASS;

    $pdo = new PDO($connectionString, $user, $pass);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
  }
  catch (PDOException $e)
  {
    die($e->getMessage());
  }
}
// Check each field to make sure submitted data is valid. If no boxes are checked, isset() will return false
function  validate() 
{
    global $valid;
    global $val_messages;
  global $pdo; 
    if($_SERVER['REQUEST_METHOD']== 'POST')
    { 
      if(isset($_POST["email"]) ) {
        foreach($_POST as $type => $value){
          if($type == "email"){
            $out = preg_match('#^(.+)@([^\.].*)\.([a-z]{2,})$#' , $_POST["email"]);
            if($out){
              $val_messages[$type] = "";
            }
            else{
              $val_messages[$type] = "email is not correct format";
              $valid = true;
            }
          }
        }
      }
      if(isset($_POST["location"])){
        if(!empty($_POST["location"])){
          $val_messages['location'] = "";
        }
      else{
        $val_messages['location'] = "Enter atleast one location";
        $valid = true;
        }
      }
      else{
        $val_messages['location'] = "Enter atleast one location";
        $valid = true;
      }
      if(isset($_POST["review"])){
        if($_POST["review"] >= 1){
          $val_messages[$type] = "";
        }
        else{
          $val_messages['review'] = "review should be equal or more than 1";
          $valid = true;
        }
      }
      else{
        $val_messages['review'] = "review should be equal or more than 1";
        $valid = true;
      }
      
}
}
$pdo = db_connect();
validate();

// Display error message if field not valid. Displays nothing if the field is valid.
function the_validation_message($type) {
  global $val_messages;
  if($_SERVER['REQUEST_METHOD']== 'POST')
  {
    if((isset($_POST[$type]))){
      if(!empty($val_messages[$type])){
        echo "<div class='failure-message'>" . $val_messages[$type] . "</div>";
        // echo "<div class='failure-message'>" . $val_messages[$type] . "</div>";
    }
  }
}
}
function the_results()
{
  global $pdo;
  global $valid;
  global $val_messages;
  if($valid == false){

  if($_SERVER["REQUEST_METHOD"]=="POST")
  {
      if(isset($_POST['email']) && isset($_POST['location']) && isset($_POST['review'])){
      echo " <div class='results'>
      <div class='result-text'> Your email address is: " . $_POST['email'] . "</div>";
      echo     
      "<div class='result-text'> Your favorite location are: ". $_POST['location'] ."</div>";
      echo     
      "<div class='result-text'>Your favourite date is: " . $_POST['review'] . "</div>
      </div>";
      $sql = "INSERT INTO project2130.reviews (email, location, review) VALUES (:email, :location, :review)";
      $statement = $pdo-> prepare($sql);
      $statement-> bindValue(':email', $_POST['email']);
      $statement-> bindValue(':location', $_POST['location']);
      $statement-> bindValue(':review', $_POST['review']);
      $statement-> execute();
      }
  }
}
} ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Bookings</title>

    <link rel="stylesheet" href="style.css">
  </head>
  <body>

    <h1>Booking a tour guide</h1>

    <div class="write-comment">
      <h2>Booking form</h2>

      <form action="contactus.php" method="post">

      <label for="email">  Please enter your email address:

<input type="text" name="email" id="email">

<!-- Display validation message for email input -->
<?php
      the_validation_message('email');
     ?>
</label>
<label for="location"> 
Please enter the locations you want to visit in Vancouver:
<input type="text" name="location" id="location">

<!-- Display validation message checkbox group -->
<?php
      the_validation_message('location');
     ?>
</label>


<label for="review"> 
Number of visitors:
<input type="text" name="review" id="review">

<!-- Display validation message checkbox group -->
<?php
      the_validation_message('review');
     ?>
</label>
<input type="reset" name="" value="Reset Form">

<input type="submit" value="Submit Form">

</form>
    </div>
    <?php
      the_results();
     ?>
  </body>
</html>
