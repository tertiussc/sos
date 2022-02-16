<?php
require_once("includes/head.php");
/** PHP code here */

$user = new User($con);
if (isset($_SESSION['loggedIn'])) {
    $userInfo = $user->retrieveUserInfo($_SESSION['loggedIn']);
    $firstname = $userInfo['firstname'];
}

?>

<body>
    <!-- ******************** Start HTML ******************** -->
    <header>
        <div class="row bg-dark">
            <?php include_once("includes/navbar.php"); ?>
            <div class="col-md-6 offset-md-3 text-center rounded bg-light my-3 p-3">
                <div class="container">
                    <h3 class="">Index page</h3>
                    <?php
                    SessionMessage::display_message();
                    $user->logged_in();
                    ?>
                    <?php if ($user->logged_in()) {
                        echo "<p class='callout-success lead'>Hello $firstname, you are logged in</p>";
                    } ?>
                </div>
            </div>
        </div>
    </header>
    <?php require_once("includes/footer.php") ?>
</body>

</html>