<?php
// PHP here
require_once("includes/head.php");
$user = new User($con);
var_dump($_POST);

$userInfo = $user->retrieveUserInfo($_SESSION['loggedIn']);

?>

<?php require_once("includes/head.php"); ?>

<body>
    <section>
        <div class="row bg-dark">
            <div class="container">
                <?php include_once("includes/navbar.php") ?>
                <div class="col-md-6 offset-md-3 rounded my-3 p-3 bg-light">
                    <h3>Profile Page</h3>
                    <?php
                    SessionMessage::display_message();
                    $user->logged_in();
                    if (isset($_POST['save_details'])) {
                        $user->updateProfile(
                            $_POST['firstname'],
                            $_POST['lastname'],
                            $_POST['username'],
                            $_POST['email'],
                            $_POST['password'],
                            $_POST['confirm_password']
                        );
                    }
                    ?>
                    <form method="post">
                        <div class="mb-3">
                            <?php echo $user->getError(Constants::$firstnameCharacters) ?>
                            <label class="visually-hidden" for="firstname">Name</label>
                            <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?= $userInfo['firstname'] ?>">
                        </div>
                        <div class="mb-3">
                            <?php echo $user->getError(Constants::$lastnameCharacters) ?>
                            <label class="visually-hidden" for="lastname">Surname</label>
                            <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?= $userInfo['lastname'] ?>">
                        </div>
                        <div class=" mb-3">
                            <?php echo $user->getError(Constants::$usernameCharacters) ?>
                            <label class="visually-hidden" for="username">Username</label>
                            <input  type="text" class="form-control" placeholder="Username" id="username" name="username" value="<?= $userInfo['username'] ?>">
                        </div>
                        <div class=" mb-3">
                            <?php echo $user->getError(Constants::$emailInvalid) ?>
                            <label class="visually-hidden" for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= $userInfo['email'] ?>" >
                        </div>
                        <hr>
                        <h4>Change Password</h4>
                        <?php echo $user->getError(Constants::$passwordCharacters) ?>
                        <?php echo $user->getError(Constants::$passwordsDontMatch) ?>
                        <div class=" mb-3">
                            <label class="visually-hidden" for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                        </div>
                        <div class="mb-3">
                            <label class="visually-hidden" for="confirm_password">Confirm Password</label>
                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-6 d-grid">
                                    <a href="index.php" class="btn btn-warning">Cancel</a>
                                </div>
                                <div class="col-6 d-grid">
                                    <button class="btn btn-success" type="submit" name="save_details">Save Details</button>
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