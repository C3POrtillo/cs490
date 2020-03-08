<?php
  function convert_to_json( $status, $message ) {
    return json_encode( [ 
      "status" => "$status", 
      "message" => $message
    ] );
  }

  function convert_user_to_json( $username, $account_type, $professor ) {
    return [
      "username" => "$username",
      "account_type" => "$account_type",
      "professor" => "$professor",
    ];
  }

  function convert_question_to_json( $q_num, $topic, $difficulty, $question, $arg_c, $arg_v, $test_c, $test_v ) {
    return [ 
      "q_num" => "$q_num", 
      "topic" => "$topic", 
      "difficulty" => "$difficulty", 
      "question" => "$question", 
      "arg_c" => "$arg_c", 
      "arg_v" => "$arg_v",
      "test_c" => "$test_c", 
      "test_v" => "test_v" 
    ];
  }

  function convert_test_to_json( $professor, $t_num, $question_c, $question_v ) {
    return [
      "professor" => "$professor", 
      "t_num" => "$t_num",
      "question_c" => "$question_c", 
      "question_v" => "$question_v"
    ];
  }

  function convert_graded_to_json( $student, $professor, $t_num, $question_c, $question_v ) {
    return [
      "student" => "$student", 
      "professor" => "$professor", 
      "t_num" => "$t_num",
      "question_c" => "$question_c", 
      "question_v" => "$question_v"
    ];
  }
?>