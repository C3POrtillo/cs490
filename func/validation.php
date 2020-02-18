<?php
    function get_data( $data ) {
    global $db;
    if( $data != "" && $_POST[$data] ) {
      return mysqli_real_escape_string($db, $_POST[$data]);
    } else {
      echo convert_to_json("403", "Invalid username and/or password.");
      exit();
    }
  }

  function auth( $name, $pass ) { // Returns a JSON object based on if user info is valid
    global $db;
    global $userTable;

    $s = "select * from `$userTable` where username='$name' and password='$pass'"; 
    ( $t = mysqli_query($db, $s) ) or die ( mysqli_error( $db ) );
    $num = mysqli_num_rows ( $t );

    if( $num >= 1 ) {
      return convert_to_json( "200", "Login successful."); 
    } else {
      return convert_to_json( "403", "Invalid username or password.");
    }
  }
?>