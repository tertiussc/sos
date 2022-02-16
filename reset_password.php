<?php
// PHP here
require_once("includes/head.php");
$user = new User($con);
var_dump($_GET);

?>
<?php require_once("includes/head.php"); ?>

<body>
    <!-- ******************** Start HTML ******************** -->
    <section>
        <div class="row bg-dark">
            <?php include_once("includes/navbar.php"); ?>
            <div class="col-md-6 offset-md-3 my-3 p-3 rounded bg-light text-center">
                <div class="container">
                    <h3 class="">Reset Password</h3>
                    <?php
                    SessionMessage::display_message();
                    if (isset($_POST['code_submit'])) {
                        $user->resetPassword($_POST['email'], $_POST['code'], $_POST['password'], $_POST['confirm_password']);
                    }
                    ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_GET['email']) ? $_GET['email'] : ''; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="visually-hidden" for="code">Code</label>
                            <input type="code" class="form-control" id="code" name="code" placeholder="Reset Code" value="<?= isset($_GET['code']) ? $_GET['code'] : ''?>" required>
                        </div>
                        <hr>
                        <h3>Create a new password</h3>
                        <div class="mb-3">
                            <label class="visually-hidden" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label class="visually-hidden" for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-success" type="submit" name="code_submit" value="code_submit">Change password</button>
                        </div>
                        <input type="hidden" class="hide" name="token" id="token" value="">
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require_once("includes/footer.php") ?>
</body>

</html>