<?php
  function convert_to_json( $status, $message ) {
    return json_encode( [ 
      "status" => "$status", 
      "message" => $message
    ] );
  }

  function convert_user_to_json( $r ) {
    return [
      "username" => $r["username"],
      "account_type" => $r["account_type"],
      "professor" => $r["professor"]
    ];
  }

  function convert_question_to_json( $r ) {
    return [ 
      "q_num" => $r["q_num"],
      "name" => $r["name"], 
      "topic" => $r["topic"], 
      "difficulty" => $r["difficulty"], 
      "f_name" => $r["f_name"],
      "constraint" => $r["constraint"],
      "question" => $r["question"], 
      "arg_c" => $r["arg_c"], 
      "arg_v" => $r["arg_v"],
      "test_c" => $r["test_c"], 
      "test_v" => $r["test_v"] 
    ];
  }

  function convert_value_to_json ( $val ) {
    $val = json_decode($val);
  }

  function convert_test_to_json( $r ) {
    return [
      "professor" => $r["professor"], 
      "t_num" => $r["t_num"],
      "question_c" => $r["question_c"], 
      "question_v" => $r["question_v"]
    ];
  }

  function convert_graded_to_json( $r , $q_c ) {    
    return [
      "student" => $r["student"], 
      "professor" => $r["professor"], 
      "t_num" => $r["t_num"],
      "question_c" => $r["question_c"], 
      "question_v" => $r["question_v"],
      "score" => $r["score"],
      "max" => $r["max"],
      "viewable" => $r["viewable"]
    ];
  }

  function stringifyGradedQuestions( $q_v, $q_c ) {
    $q_str = "{";
    for ($i = 1; $i <= $q_c; $i++) {
      $test_c = $q_v->{"$i"}->test_c;
  
      $item_score =  $q_v->{"$i"}->item_points;
      $item_max =  $q_v->{"$i"}->item_max_points;
      $item_test_cases = $q_v->{"$i"}->item_test_cases;
  
      $q_str .= "\"$i\":{\"q_num\":\"";
      $q_str .= $q_v->{"$i"}->q_num;
      $q_str .= "\",\"answer\":\"";
      $q_str .= urlencode($q_v->{"$i"}->answer);
      $q_str .= "\",\"test_c\":\"";
      $q_str .= $test_c;
  
      $q_str .= "\",\"comment\":\"";
      $q_str .= urlencode($q_v->{"$i"}->comment);
  
      $q_str .= "\",\"item_points\":{";
      $q_str .= "\"f_name\":" . $item_score->f_name;
      $q_str .= ",\"colon\":" . $item_score->colon;
      $q_str .= ",\"args\":" . $item_score->args;
      $q_str .= ",\"constraint\":" . $item_score->constraint;
      for ($j = 1; $j <= $test_c; $j++) {
        $q_str .= ",\"$j\":" . $item_score->{"$j"};
      }
  
      $q_str .= "},\"item_max_points\":{";
      $q_str .= "\"f_name\":" . $item_max->f_name;
      $q_str .= ",\"colon\":" . $item_max->colon;
      $q_str .= ",\"args\":" . $item_max->args;
      $q_str .= ",\"constraint\":" . $item_max->constraint;
      for ($j = 1; $j <= $test_c; $j++) {
        $q_str .= ",\"$j\":" . $item_max->{"$j"};
      }
  
      $q_str .= "},\"item_test_cases\":{";
      $q_str .= "\"f_name\":\"" . urlencode($item_test_cases->f_name);
      $q_str .= "\",\"colon\":\"" . urlencode($item_test_cases->colon);
      $q_str .= "\",\"args\":\"" . urlencode($item_test_cases->args);
      $q_str .= "\",\"constraint\":\"" . urlencode($item_test_cases->constraint);
      for ($j = 1; $j <= $test_c; $j++) {
        $q_str .= "\",\"$j\":\"" . urlencode($item_test_cases->{"$j"});
      }
  
      $q_str .= "\"},\"score\":\"";
      $q_str .= $q_v->{"$i"}->score;      
      $q_str .= "\",\"max\":\"";
      $q_str .= $q_v->{"$i"}->max;
      $q_str .= "\"}";
      if ($i < $q_c) {
        $q_str .= ",";
      }
    }
    $q_str .= "}";

    return $q_str;
  }
?>