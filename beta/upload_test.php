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

  set_test( $form->{"professor"}, $form->{"t_num"}, $form->{"question_c"}, $form->{"question_v"} );

?>