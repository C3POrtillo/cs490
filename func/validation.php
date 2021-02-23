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
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while authenticating login." ) );
    $num = mysqli_num_rows ( $t );

    if( $num >= 1 ) {
      while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
        return convert_to_json( "200", convert_user_to_json( $r )); 
      }
    } else {
      return convert_to_json( "403", "Invalid username or password." );
    }
  }

  function get_professor( $username ) {
    global $db;
    global $userTable;
    $s = "SELECT * FROM `$userTable` WHERE username='$username'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching professor." ) );

    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      if ( $r["professor"] === NULL ) {
        return $username;
      } else {
        return $r["professor"];
      }
    }
  }

  function get_question( $q_num ) {
    global $db;
    global $questionsTable;
    $s = "SELECT * FROM `$questionsTable` WHERE q_num='$q_num'"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching question." ) );
    
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      return convert_question_to_json( $r );
    }
  }
  
  function get_all_questions() {
    global $db;
    global $questionsTable;
    $s = "SELECT * FROM `$questionsTable`"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching all questions." ) );

    $res = [];
    $count = 0;
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $count += 1;
      $res += ["$count" => convert_question_to_json( $r )];
    }
    $res += ["count" => $count];

    return convert_to_json( "200", $res );
  }


  function set_question( $name, $topic, $difficulty, $f_name, $constraint, $question, $arg_c, $arg_v, $test_c, $test_v ) {
    global $db;
    global $questionsTable;
    $s = "INSERT INTO `$questionsTable`(`name`, `topic`, `difficulty`, `f_name`, `constraint`, `question`, `arg_c`, `arg_v`, `test_c`, `test_v`) VALUES ('$name', '$topic', '$difficulty', '$f_name', '$constraint', '$question', '$arg_c', '$arg_v', '$test_c', '$test_v')";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while inserting question." ) );
    return convert_to_json( "200" , "Question Successfully inserted.");
  }

  function get_test( $t_num, $professor ) {
    global $db;
    global $testsTable;
    $s = "SELECT * FROM `$testsTable` WHERE t_num='$t_num' AND professor='$professor'"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching test." ) );

    $res = [];

    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $questions = json_decode($r["question_v"]);
      $res += ["test_data" => convert_test_to_json( $r )];
      for ($i = 1; $i <= $r["question_c"]; $i++) {
        $q_num = $questions->{"$i"}->q_num;
        $res += ["$i" => get_question( $q_num )];
      }
      return convert_to_json( "200", $res );
    }
    return convert_to_json( "403", "Invalid test or professor." );
  }

  function get_all_tests( $professor ) {
    global $db;
    global $testsTable;
    $s = "SELECT * FROM `$testsTable` WHERE professor='$professor'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching all tests." ) );

    $res = [];
    $count = 0;
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $count += 1;
      $res += ["$count" => convert_test_to_json( $r )];
    }
    $res += ["count" => $count];

    return convert_to_json( "200", $res );
  }

  function set_test( $t_num, $professor, $question_c, $question_v ) {
    global $db;
    global $testsTable;
    $s = "INSERT INTO `$testsTable`(`t_num`, `professor`, `question_c`, `question_v`) VALUES ('$t_num', '$professor', '$question_c', '$question_v')";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while inserting test." ) );
    return convert_to_json( "200", "Successfully added exam." );
  }

  function set_graded_test( $student, $professor, $t_num, $question_c, $question_v, $score, $max ) {
    global $db;
    global $gradedTable;
    $s = "INSERT INTO `$gradedTable`(`student`, `professor`, `t_num`, `question_c`, `question_v`, `score`, `max`) VALUES ('$student', '$professor', '$t_num', '$question_c', '$question_v', '$score', '$max')";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while inserting graded." ) );
    return convert_to_json( "200", "Successfully submitted exam." );
  }

  function update_graded_test( $student, $professor, $t_num, $question_c, $question_v, $score, $max ) {
    global $db;
    global $gradedTable;
    $s = "DELETE FROM `$gradedTable` WHERE student='$student' AND professor='$professor' AND t_num='$t_num'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while updating graded." ) );
    set_graded_test( $student, $professor, $t_num, $question_c, $question_v, $score, $max );
    return convert_to_json( "200", "Successfully updated exam." );
  }

  function update_graded_test_visibililty( $professor, $t_num ) {
    global $db;
    global $gradedTable;
    $s = "UPDATE `$gradedTable` SET `viewable`='1' WHERE professor='$professor' AND t_num='$t_num'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while updating visibility." ) );
    return convert_to_json( "200", "Successfully updated visibility." );
  }

  function get_all_graded_tests( $professor, $t_num ) {
    global $db;
    global $gradedTable;
    $res = [];
    $count = 0;
    $s = "SELECT * FROM `$gradedTable` WHERE professor='$professor' AND t_num='$t_num'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching all graded." ) );
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $count += 1;
      $res += ["$count" => $r["student"]];
    }
    $res += ["count" => $count];

    return convert_to_json( "200", $res );
  }

  function get_graded_test( $student, $professor, $t_num ) {
    global $db;
    global $gradedTable;
    global $testsTable;

    $s = "SELECT * FROM `$testsTable` WHERE t_num='$t_num' AND professor='$professor'"; 
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching graded." ) );

    $res = [];
    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $questions = json_decode($r["question_v"]);
      $res += ["test_data" => convert_test_to_json( $r )];
      for ($i = 1; $i <= $r["question_c"]; $i++) {
        $q_num = $questions->{"$i"}->q_num;
        $res += ["$i" => get_question( $q_num )];
      }
    }

    $s = "SELECT * FROM `$gradedTable` WHERE student='$student' AND professor='$professor' AND t_num='$t_num'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching graded." ) );

    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $res += ["student_data" => convert_graded_to_json( $r )];
      return convert_to_json( "200", $res );
    }
    return convert_to_json( "403", "Invalid student, professor, or test." );
  }

  function get_all_viewable_tests( $student, $professor ) {
    global $db;
    global $gradedTable;

    $s = "SELECT * FROM `$gradedTable` WHERE student='$student' AND professor='$professor' AND viewable='1'";
    ( $t = mysqli_query($db, $s) ) or die ( convert_to_json( "503", "Database error occured while fetching graded." ) );

    $res = [];
    $count = 0;

    while ( $r = mysqli_fetch_array ( $t, MYSQLI_ASSOC) ) {
      $count += 1;
      $res += ["$count" => convert_graded_to_json( $r )];
    }
    $res += ["count" => $count];

    return convert_to_json( "200", $res );
  }
?>