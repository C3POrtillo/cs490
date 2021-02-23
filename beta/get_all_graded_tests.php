<?php
  include("../env/account.php");
  include("../func/jsonfns.php");
  include("../func/validation.php");

  header('Content-type: application/json');

  $db = mysqli_connect($hostname, $username, $password, $project);
  if (mysqli_connect_errno()) {
    echo convert_to_json("503", "Unable to connect to database");
    exit();
  }
  $req = $_POST["req"];

  $json = json_decode($req);
  $content = $json->contents;
  $username = $content->username;
  $t_num = $content->t_num;

  echo get_all_graded_tests( $username, $t_num );
?>