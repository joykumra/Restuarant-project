<?php
require 'config.php';

 // Global result of form validation
$valid = true;

// Should return a PDO
function db_connect() {

  try {
    // TODO
    // try to open database connection using constants set in config.php
    // return $pdo;

    
    $connectionString = "mysql:host=". DBHOST . ";dbname=" . DBNAME;
    $user = DBUSER;
    $pass = DBPASS;
    $pdo = new PDO($connectionString,$user,$pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $pdo;
  }
  catch (PDOException $e)
  {
    die($e->getMessage());
  }
}


function validate(){
  global $valid;
  global $val_messages;
  global $pdo;

  if($_SERVER['REQUEST_METHOD']== 'POST'){
    if(isset($_POST["email"]) ) {
      foreach($_POST as $type => $value){
        if($type == "email"){
          $out = preg_match('#^(.+)@([^\.].*)\.([a-z]{2,})$#' , $_POST["email"]);
          if($out){
            $val_messages[$type] = "";
            $valid = true;
          }
          else{
            $val_messages[$type] = "email is not correct format";
            $valid = false;
          }
        }
      }
    }
    else{
      $val_messages[$type] = "email is not correct format";
      $valid = false;
    }
    
    
    if(isset($_POST["location"]) ){
      $www = !empty($_POST["location"]);
      if($www){
        $val_messages['location'] = "";
        $valid = true;
      }
      else{
        $val_messages['location'] = "Please choose the location you visited";
        $valid = false;
        }
    }

    if(isset($_POST["review"]) ){
      $abc = !empty($_POST["review"]);
      if($abc){
        $val_messages['review'] = "";
        $valid = true;
      }
      else{
        $val_messages['review'] = "Give us a review";
        $valid = false;
        }
    }      
  }


}
$pdo = db_connect();
validate();

// function validate_messages($type){
//  global $val_messages;
  
// }                              

// Handle form submission
function handle_form_submission() {
  global $pdo;
  global $valid;
  global $val_messages;
  if($valid == true){
    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        // TODO
        if(isset($_POST['email']) && isset($_POST['location']) && isset($_POST['review'])){
    
        $sql = 'INSERT INTO reviews (location,email,review) VALUES(:location, :email, :review)';

        $statement = $pdo->prepare($sql);
        $statement -> bindValue(':email', $_POST['email']);
        $statement -> bindValue(':location', $_POST['location']);
        $statement -> bindValue(':review', $_POST['review']);
        $statement -> execute();
        

        }
    }
    else{
      echo "you must enter all fields";
    }
    
  }
}



