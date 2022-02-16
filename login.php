<?php
// PHP here
require_once("includes/head.php");
$user = new User($con);
$remember = (isset($_POST['remember']) ? $_POST['remember'] : '');

?>


<body>
    <!-- ******************** Start HTML ******************** -->
    <section>
        <div class="row bg-dark">
            <?php include_once("includes/navbar.php"); ?>
            <div class="col-md-6 offset-md-3 text-center rounded bg-light my-3 p-3">
                <div class="container">
                    <h3 class="">Login page</h3>
                    <?php
                    SessionMessage::display_message();
                    if(isset($_POST['login_submit'])) {
                        $user->loginUser($_POST['email'], $_POST['password'], $remember);
                    }
                    ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="">
                        </div>
                        <div class="mb-3">
                            <label class="visually-hidden" for="password">password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" value="">
                        </div>
                        <div class="mb-3 form-check d-flex">
                            <input type="checkbox" class="form-check-input me-2" name="remember" id="remember">
                            <label for="remember" class="form-check-label">Remember Me</label>
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-success" type="submit" name="login_submit" value="login_submit">Login</button>
                        </div>
                        <div class="mb-3">
                            <a href="forgot_password.php" class="lead text-decoration-none text-reset fst-italic">Forgotten Password</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require_once("includes/footer.php") ?>
</body>

</html>