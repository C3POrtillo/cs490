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

  $username = get_data( "u" );
  $password = get_data( "p" );
  
  // $req = json_decode( $_POST[ "req" ] );
  // $username = get_data( $req->{"u"} );
  // $password = get_data( $req->{"p"} );
  echo auth( $username, $password );
?>