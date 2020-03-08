<?php
  include("../env/account.php");
  include("../func/jsonfns.php");
  include("../func/validation.php");

  // header('Content-type: application/json');

  // $db = mysqli_connect($hostname, $username, $password, $project);
  // if (mysqli_connect_errno()) {
  //   echo convert_to_json("503", "Unable to connect to database");
  //   exit();
  // }

  // Takes raw data from the request
  $req = $_POST["req"];

  // $req = json_encode($req, JSON_UNESCAPED_SLASHES);
  // $req = "test";
  // Converts it into a PHP object
  // $req = json_decode($req);

  // echo "data decoded";
  // echo $data;
  echo json_encode([
    "res" => $req
  ], JSON_UNESCAPED_SLASHES);

  // echo "hello"
  // set_question( $$form->{"q_num"}, $form->{"topic"}, $form->{"difficulty"}, $form->{"question"}, $form->{"arg_c"}, $form->{"arg_v"}, $form->{"test_c"}, $form->{"test_v"} );

?>