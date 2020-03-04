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

  $req = json_decode( $_POST[ "req" ] );
  $token = get_data( $req->{"login"} );
  $form = $req->{"form-data"};

  set_question( $$form->{"q_num"}, $form->{"topic"}, $form->{"difficulty"}, $form->{"question"}, $form->{"arg_c"}, $form->{"arg_v"}, $form->{"test_c"}, $form->{"test_v"} );

?>