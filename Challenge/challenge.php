<?php
$conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");
phpinfo();
echo "something";
$sql  = "
    INSERT INTO challenge(
        date, 
        doExercise
    )
    VALUES (
        NOW(),
        0
    )";
$result = mysqli_query($conn, $sql);

$sql = "SELECT * FROM heroku_1189b05c9eafafd.challenge where c_id=1";
$result = mysql_query($sql, $db);
$array = mysql_fetch_array($result);

if($result === false){
    echo mysqli_error($conn);
}
?>

