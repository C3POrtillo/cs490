<?php
    function get_data( $data ) {
    global $db;
    if( $data != "" && $_POST[$data] ) {
      return mysqli_real_escape_string($db, $_POST[$data]);
    } else {
      echo convert_to_json( "403", "Invalid value for $data" );
      exit();
    }
  }

  function auth( $name, $pass ) { // Returns a JSON object based on if user info is valid
    global $db;
    global $userTable;

    $s = "SELECT * FROM `$userTable` WHERE username='$name' AND password='$pass'"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database Error Occured" ) );
    $num = mysqli_num_rows ( $t );

    if( $num >= 1 ) {
      while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
        return convert_to_json( "200", convert_user_to_json( $r["username"], $r["account_type"], $r["$professor"] )); 
      }
    } else {
      return convert_to_json( "403", "Invalid username or password." );
    }
  }

  function get_question( $q_num ) {
    global $db;
    global $questionsTable;
    $s = "SELECT * FROM `$questionsTable` WHERE q_num='$q_num'"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database Error Occured" ) );
    
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      return convert_to_json( "200", convert_question_to_json( $r["q_num"], $r["topic"], $r["difficulty"], $r["question"], $r["arg_c"], $r["arg_v"], $r["test_c"], $r["test_v"] ) );
    }
    return convert_to_json( "403", "Invalid question number." );
  }

  function set_question($topic, $difficulty, $question, $arg_c, $arg_v, $test_c, $test_v ) {
    global $db;
    global $questionsTable;
    $s = "INSERT INTO `490_Questions`(`topic`, `difficulty`, `question`, `arg_c`, `arg_v`, `test_c`, `test_v`) VALUES ('$topic', '$difficulty', '$question', '$arg_c', '$arg_v', '$test_c', '$test_v')";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database Error Occured" ) );
    return convert_to_json( "200" , "Question Successfully inserted.");
  }

  function get_test( $t_num, $professor ) {
    global $db;
    global $questionsTable;
    global $testsTable;
    $s = "SELECT * FROM `$questionsTable` WHERE t_num='$t_num' AND professor='$professor'"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database Error Occured" ) );
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      return convert_to_json( "200", convert_test_to_json( $r["professor"], $r["t_num"], $r["question_c"], $r["question_v"] ) );
    }
    return convert_to_json( "403", "Invalid test or professor." );
  }

  function set_test( $t_num, $professor, $question_c, $question_v ) {
    global $db;
    global $questionsTable;
    $s = "INSERT INTO `490_Tests`(`t_num`, `professor`, `question_c`, `question_v`) VALUES ('$t_num', '$professor', '$question_c', '$question_v')";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database Error Occured" ) );
    return convert_to_json( "200", "Successfully added question." );
  }

?>