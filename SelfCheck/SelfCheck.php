<?php
    $num = 0;
    $turtle = 0;

    if ($_GET["check1"] == "on") ++$num;
    if ($_GET["check2"] == "on") ++$num;
    if ($_GET["check3"] == "on") ++$num;
    if ($_GET["check4"] == "on") ++$num;
    if ($_GET["check5"] == "on") ++$num;
    if ($num > 2) $turtle = 1;

    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

    session_start();
    $id = $_SESSION;
    $id = implode("",$id);

    $sql = "UPDATE  user_info SET isTurtle = $turtle WHERE id='{$id}'";
    $result = mysqli_query($conn, $sql);

    if($result === false){
        echo mysqli_error($conn);
  }
?>
