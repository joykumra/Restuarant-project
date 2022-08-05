<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// global array of posts, to be fetched from database
$comments = [];
// global array of unique commenter email addresses to be fetched from db
$commenters = [];

require_once 'database.php';

//connect to database: PHP Data object representing Database connection
$pdo = db_connect();
// submit comment to database
handle_form_submission();

// include the template to display the page
include 'contactus.php';
?>