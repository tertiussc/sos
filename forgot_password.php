<?php
// PHP here
require_once("includes/head.php");
$user = new User($con);

// Setup PHP mailer
use Google\Site_Kit_Dependencies\GuzzleHttp\Psr7\Message;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
// Load the PHP mailer classes
require 'includes/PHPMailer/src/Exception.php';
require 'includes/PHPMailer/src/PHPMailer.php';
require 'includes/PHPMailer/src/SMTP.php';
// Initiate/create the mailer object
$mail = new PHPMailer(true);
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
                    <h3 class="">Reset Password</h3>
                    <?php
                    SessionMessage::display_message();
                    if (isset($_POST['reset_password'])) {
                        $user->forgotPassword($_POST['email'], $mail);
                    }
                    ?>
                    <form method="post">
                        <div class="mb-3">
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="" required>
                        </div>
                        <div class="mb-3 d-grid">
                            <button class="btn btn-success" type="submit" name="reset_password" value="reset_password">Send Reset Link</button>
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