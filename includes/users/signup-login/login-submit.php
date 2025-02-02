<?php
if (isset($_POST['login-submit'])) {
    $includeCheck = true;
    require "../../general/db_conn.php";
    require "../../general/php-functions.php";

    $user = [$_POST['email'], $_POST['pwd']];

    // checks if slots are not empty
    if (empty($user[0]) || empty($user[1])) {
        header("Location: /login?error=empty-fields&email=$user[0]");
        exit();
    }

    $sql = "SELECT id, username, password FROM users WHERE email = ? AND status = 1";
    $vars = [$user[0]];
    $varsType = "s";
    $result = executeStmt($db, $sql, $varsType, $vars);

    $row = mysqli_fetch_assoc($result);

    // checks if the user exists in the db
    if (mysqli_num_rows($result) == 0 && !password_verify($user[1], $row['password'])) {
        header("Location: /login?error=user-not-found&email=$user[0]");
    } else {
        $uid = $row['id'];
        $uname = $row['username'];
        $unameFormatted = format($uname, true);

        // creates the user session
        $_SESSION['uid'] = $uid;
        $_SESSION['uname'] = $uname;

        header("Location: /users/$unameFormatted-$uid");
    }
} else {
    header("Location: /404");
}
?>
