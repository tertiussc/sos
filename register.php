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

// Testing
var_dump($_POST);

?>

<body>
    <section>
        <div class="row bg-dark">
            <?php include_once("includes/navbar.php") ?>
            <div class="container">
                <div class="col-md-6 offset-md-3 border rounded p-3 my-3 bg-light">
                    <h3 class="text-center">Register an account</h3>
                    <?php
                    SessionMessage::display_message();
                    if ($_SERVER['REQUEST_METHOD'] == "POST") {
                        $user->registerUser($mail, $_POST['firstname'], $_POST['lastname'], $_POST['username'], $_POST['email'], $_POST['confirm_email'], $_POST['password'], $_POST['confirm_password']);
                    }
                    ?>

                    <form method="post">
                        <div class="mb-3">
                            <?php echo $user->getError(Constants::$firstnameCharacters); ?>
                            <label class="visually-hidden" for="firstname">Firstname</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Firstname" value="<?= isset($_POST['firstname']) ? $_POST['firstname'] : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <?php echo $user->getError(Constants::$lastnameCharacters); ?>
                            <label class="visually-hidden" for="lastname">Lastname</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Lastname" value="<?= isset($_POST['lastname']) ? $_POST['lastname'] : ''; ?>">
                        </div>
                        <div class=" mb-3">
                            <?php echo $user->getError(Constants::$usernameCharacters); ?>
                            <?php echo $user->getError(Constants::$usernameTaken); ?>
                            <label class="visually-hidden" for="username">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?= isset($_POST['username']) ? $_POST['username'] : ''; ?>">
                        </div>
                        <div class=" mb-3">
                            <?php echo $user->getError(Constants::$emailInvalid); ?>
                            <?php echo $user->getError(Constants::$emailTaken); ?>
                            <?php echo $user->getError(Constants::$emailDontMatch); ?>
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= isset($_POST['email']) ? $_POST['email'] : ''; ?>">
                        </div>
                        <div class=" mb-3">
                            <label class="visually-hidden" for="confirm_email">Confirm Email</label>
                            <input type="email" class="form-control" id="confirm_email" name="confirm_email" placeholder="Confirm Email" value="<?= isset($_POST['confirm_email']) ? $_POST['confirm_email'] : ''; ?>">
                        </div>
                        <div class=" mb-3">
                            <?php echo $user->getError(Constants::$passwordCharacters); ?>
                            <?php echo $user->getError(Constants::$passwordsDontMatch); ?>
                            <label class="visually-hidden" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="mb-3">
                            <label class="visually-hidden" for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6 d-grid">
                                    <a href="index.php" class="btn btn-warning">Cancel</a>
                                </div>
                                <div class="col-6 d-grid">
                                    <button class="btn btn-success" type="submit" name="submit_register">Register</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
</body>

</html>

<?php require_once("includes/footer.php"); ?>