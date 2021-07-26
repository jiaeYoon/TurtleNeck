<?php
    $num = 0;
    $turtle = 0;

    if ($_GET["check1"] == "on") ++$num;
    if ($_GET["check2"] == "on") ++$num;
    if ($_GET["check3"] == "on") ++$num;
    if ($_GET["check4"] == "on") ++$num;
    if ($_GET["check5"] == "on") ++$num;
    if ($_GET["check6"] == "on") ++$num;
    if ($_GET["check7"] == "on") ++$num;
    if ($_GET["check8"] == "on") ++$num;
    if ($_GET["check9"] == "on") ++$num;
    if ($_GET["check10"] == "on") ++$num;
    if ($_GET["check11"] == "on") ++$num;
    if ($num > 2) $turtle = 1;

    $conn = mysqli_connect("us-cdbr-east-03.cleardb.com", "bb0e75dfd58ff1", "73c3064a", "heroku_1189b05c9eafafd");

    // 세션값이 있는지 확인
    session_start();
    if (isset($_SESSION['userId']))
    {
      echo "로그인상태입니다.";
      $id = $_SESSION['userId'];
      $id = implode("",$id);
    }
    else
    {
    ?>
    <script>
    alert("세션이 만료되어있거나 비회원입니다.");
    location.href = "index.html";
    </script>
    <?php
    }  


    $sql = "UPDATE user_info SET isTurtle = $turtle WHERE id='{$id}'";
    $result = mysqli_query($conn, $sql);

    if ($turtle == 1)
    {
?>
<script>
  alert("🚨WARNING🚨\n당신은 거북목 증후군일 가능성이 높습니다.\n꾸준한 스트레칭이 필요합니다. 🐢TurtleNeck🐢과 함께해요🤸‍♀️🤸‍♂️!");

</script>
<?php
    }
    else {
?>
<script>
  alert("거북목 증후군일 가능성이 낮습니다.\n틈틈이 스트레칭하며 🐢TurtleNeck🐢과 함께 예방해요🤸‍♀️🤸‍♂️!");
</script>
<?php
    }
?>
<script>
    location.href = "../main/main.php";
</script>
<?php
    if($result === false){
        echo mysqli_error($conn);
  }
?>
