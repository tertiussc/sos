<?php
require_once("includes/head.php");
// Create the user object
$user = new User($con);
var_dump($_POST);
?>
<?php require_once("includes/head.php"); ?>

<body>
    <!-- ******************** Start HTML ******************** -->
    <section>
        <div class="row bg-dark">
            <?php include_once("includes/navbar.php"); ?>
            <div class="col-md-6 offset-md-3 my-3 p-3 rounded bg-light text-center">
                <div class="container">
                    <h3 class="">Activate Your Account</h3>
                    <?php
                    SessionMessage::display_message();
                    // Call functions after messaging
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        // Activate the account
                        $user->activateAccount($_GET['email'], $_GET['code']);
                    }
                    ?>
                    <form method="post">
                        <div class="mb-3">
                            <?php echo $user->getError(Constants::$emailInvalid); ?>
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= (isset($_GET['email']) ? $_GET['email'] : ''); ?>">
                        </div>
                        <div class="mb-3">
                            <?php echo $user->getError(Constants::$invalidCode); ?>
                            <label class="visually-hidden" for="code">code</label>
                            <input type="text" class="form-control" id="code" name="code" placeholder="Code" value="<?= (isset($_GET['code']) ? $_GET['code'] : ''); ?>">
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-success" type="submit" name="reset_password" value="reset_password">Activate account</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <?php require_once("includes/footer.php") ?>
</body>

</html>