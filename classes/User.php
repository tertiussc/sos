<?php

/** User class
 * 
 * The user class will manage all USER related functions
 * 
 * @param object $con A connection to the database
 * 
 * @return object a USER object
 */
class User
{
    private $con;
    private $errorArray = array();
    private $baseDevPath = 'http://localhost/basic_setup/';
    private $baseProdPath = 'https://www.meliorateafrica.com/basic_setup/';


    /** Initiate the connection variable */
    public function __construct($con)
    {
        $this->con = $con;
    }

    /** Validate Firstname
     * 
     * @param string $firstname The user's name
     * 
     * Check that the firstname is between 3 and 50 characters
     * 
     * @return void Returns errors if they are found nothing if all is good
     */
    private function validateFirstname($firstname)
    {
        if (strlen($firstname) < 2 || strlen($firstname) > 50) {
            array_push($this->errorArray, Constants::$firstnameCharacters);
        }
    }

    /** Validate Lastname
     * 
     * @param string $lastname The user's Lastname
     * 
     * Check that the lastname is between 3 and 50 characters length
     * 
     * @return void Returns errors if they are found nothing if all is good
     */
    private function validateLastname($lastname)
    {
        if (strlen($lastname) < 2 || strlen($lastname) > 50) {
            array_push($this->errorArray, Constants::$lastnameCharacters);
        }
    }

    /** Validate username
     * 
     * @param string $username The user's username
     * 
     * Check that the length is between 2 and 50 characters 
     * 
     * Check that the username has not already been taken
     * 
     * @return void Returns errors if they are found nothing if all is good
     */
    private function validateUsername($username)
    {
        if (strlen($username) < 2 || strlen($username) > 50) {
            array_push($this->errorArray, Constants::$usernameCharacters);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE username=:username");
        $query->bindValue(":username", $username);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$usernameTaken);
        }
    }

    /** Validate Emails
     * 
     * @param string $email The user's email
     * 
     * @param string $confirm_email The user's confirmation email
     * 
     * Check that the email format conform to email standards 
     * 
     * Check to see if emails match
     * 
     * Check that the email has not already been taken
     * 
     * @return void Returns errors if they are found nothing if all is good
     * 
     */
    private function validateEmails($email, $confirm_email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constants::$emailInvalid);
            return;
        }

        if ($email != $confirm_email) {
            array_push($this->errorArray, Constants::$emailDontMatch);
            return;
        }

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindValue(":email", $email);
        $query->execute();

        if ($query->rowCount() != 0) {
            array_push($this->errorArray, Constants::$emailTaken);
        }
    }

    /** Validate password
     * 
     * @param string $password The user's entered password
     * 
     * @param string $confirm_password The user's confirmation password
     * 
     * Check that the password and confirmation passwords match
     * 
     * Check that the password length is at least 8 characters long
     * 
     * @return void Returns errors if they are found nothing if all is good
     */
    private function validatePasswords($password, $confirm_password)
    {
        if ($password != $confirm_password) {
            array_push($this->errorArray, Constants::$passwordsDontMatch);
            return;
        }

        if (strlen($password) < 8) {
            array_push($this->errorArray, Constants::$passwordCharacters);
        }
    }

    /** Get error
     * Check to see if there are any errors in the errorArray
     * 
     * @param string Constant::$param the constant variable associated with the error e.g. Constant::nameCharacters
     * 
     * @return String Paragraph with callout-danger class 
     */
    public function getError($error)
    {
        if (in_array($error, $this->errorArray)) {
            return "<p class='callout-danger'>" . $error . "</p>";
        }
    }

    /** Register a user
     * 
     * @param string $firstname The user's firstname
     * 
     * @param string $lastname The user's lastname
     * 
     * @param string $username The user's username
     * 
     * @param string $email The user's email
     * 
     * @param string $confirm_email The user's confirm_email
     * 
     * @param string $password The user's password
     * 
     * @param string $confirm_password The user's confirm_password
     * 
     * Clean the input data
     * 
     * Validate the input data
     * 
     * Check for errors
     * 
     * Hash the password
     * 
     * Insert the details into the database
     * 
     * Set a message status in Sessions
     * 
     * @return bool True if the user was created False if not
     * 
     */
    public function registerUser($mail, $firstname, $lastname, $username, $email, $confirm_email, $password, $confirm_password)
    {
        // Clean the data
        $firstname = FormSanitizer::sanitizeFormString($firstname);
        $lastname = FormSanitizer::sanitizeFormString($lastname);
        $username = FormSanitizer::sanitizeFormUsername($username);
        $email = FormSanitizer::sanitizeFormEmail($email);
        $confirm_email = FormSanitizer::sanitizeFormEmail($confirm_email);
        $password = FormSanitizer::sanitizeFormPassword($password);
        $confirm_password = FormSanitizer::sanitizeFormPassword($confirm_password);

        // Validate inputs
        $this->validateFirstname($firstname);
        $this->validateLastname($lastname);
        $this->validateUsername($username);
        $this->validateEmails($email, $confirm_email);
        $this->validatePasswords($password, $confirm_password);

        // If no errors is found then insert details into the database
        if (empty($this->errorArray)) {

            // Hash the password;
            $password = password_hash($password, PASSWORD_DEFAULT);

            // create a code
            $code = md5($username . microtime());
            // Prepare statement/query
            $query = $this->con->prepare("INSERT INTO users (firstname, lastname, username, email, password, code)
                                VALUES (:firstname, :lastname, :username, :email, :password, :code)");

            // Bind values
            $query->bindValue(":firstname", $firstname);
            $query->bindValue(":lastname", $lastname);
            $query->bindValue(":username", $username);
            $query->bindValue(":email", $email);
            $query->bindValue(":password", $password);
            $query->bindValue(":code", $code);

            // Execute the statement
            $query->execute();

            // when success
            if ($query->rowCount() == 1) {
                // Email the User
                $email = "$email";
                $subject = "Activate your password";
                $url = $this->baseProdPath . "activate.php?email=$email&code=$code";
                $message = "To activate your account <a href='$url'>Click here</a>";

                // Email the customer and put the email and code in the link via $_GET method
                $this->sendActivationEmail($mail, $email, $subject, $message);

                // Set the result in sessions
                SessionMessage::set_success_messages("An email has been sent to $email to activate your account.");

                // Redirect
                header("Location: activate.php");
            }
            return true;
        } else {
            // Set the result in sessions
            SessionMessage::set_alert_messages("User registration failed!");
            return false;
        }
    }

    /** Send activation email
     * 
     * @param object $mail PHPMailer object
     * 
     * @param string $email The user's email address used for registration
     * 
     * @param string $subject The email's subject
     * 
     * @param string $message The email's body
     * 
     * Set from email variable
     * 
     * @return void Send an email to the user to activate their account
     */
    private function sendActivationEmail($mail, $email, $subject, $message)
    {
        // from email
        $fromEmail = 'activate@meliorateAfrica.com';

        // Try and send the email
        try {
            //Recipients
            $mail->setFrom($fromEmail, 'Meliorate Africa'); // Where the email will be sent from
            $mail->addAddress($email);     //Add a recipient -> Where to send the email to
            //$mail->addCC('tertiussc@meliorateafrica.com', 'Tertius'); //Optional

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);

            if ($mail->send()) {
                SessionMessage::set_success_messages("An email has been sent to activate your password");
            };
        } catch (Exception $e) {
            SessionMessage::set_alert_messages("Message was not sent:");
            header("Location: login.php");
        }
    }

    /** Activate user's account
     * 
     * @param string $email The user's registered email
     * 
     * @param string $code The user's activation code
     * 
     * Sanitize the input data
     * 
     * Search for the user's account
     * 
     * Update the user's account
     * 
     * Set a success message
     * 
     * Redirect the user to login
     * 
     * @return void Activate the user's account
     */
    public function activateAccount($email, $code)
    {
        // Sanitize the inputs
        $email = FormSanitizer::sanitizeFormEmail($email);
        $code = FormSanitizer::sanitizeCode($code);

        // Get the user profile
        // Prepare the query
        $query = $this->con->prepare("SELECT * FROM users 
                                    WHERE email=:email
                                    AND code=:code");
        // Bind the data
        $query->bindValue(":email", $email);
        $query->bindValue(":code", $code);

        // Execute the query
        $query->execute();

        // Check to row count if user is found update database to activate the user
        if ($query->rowCount() == 1) {
            // Activate the user in the database
            $query2 = $this->con->prepare("UPDATE users SET active=1, code=0 WHERE email=:email");
            // Bind value
            $query2->bindValue(":email", $email);
            // Execute the query
            $query2->execute();
            // Set Activation message
            SessionMessage::set_success_messages("Your account has been activated, congratulations!");
            // Redirect the user
            header("Location: login.php");
        } else {
            SessionMessage::set_alert_messages("Incorrect activation information received/entered!");
        }
    }

    /** Login the user
     * 
     * @param string $email The user's email
     * 
     * @param string $password The user's password
     * 
     * @param string $remember If the user checked the remember me button then string "On" is submitted otherwise default value to 0
     * 
     * Sanitize the form inputs
     * 
     * fetch the user account in the database
     * 
     * verify the user's password and check if the user account is active
     */
    public function loginUser($email, $password, $remember = 0)
    {
        // Sanitize the form data
        $email = FormSanitizer::sanitizeLoginEmail($email);
        $password = FormSanitizer::sanitizeFormPassword($password);
        $remember = FormSanitizer::sanitizeFormString($remember);

        $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindValue(":email", $email);
        $query->execute();

        if ($query->rowCount() == 1) {
            // fetch the associative array
            $row = $query->fetch(PDO::FETCH_ASSOC);
            // verify the password and check that the account is active
            if ($row['active'] == 0) {
                SessionMessage::set_alert_messages("Please activate you account first");
            } else if (password_verify($password, $row['password']) && $row['active'] == 1) {
                // Set session to logged in
                $_SESSION['loggedIn'] = $email;
                // Check if the use wants to be remembered
                // if remember was checked set a cookie to expire in 10years (315360000 seconds)
                if ($remember == "On") {
                    setcookie('save_login', $email, time() + 315360000);
                }
                // Redirect to homs page
                header("Location: index.php");
            } else {
                SessionMessage::set_alert_messages("Incorrect password!");
                header("Location: login.php");
            }
            // Check the password

        } else {
            SessionMessage::set_alert_messages("User not found OR does not exist");
        }
    }

    /** Check to see if the user is logged in
     * 
     * @return boolean True if the user is logged in false if not
     */
    public function logged_in()
    {
        if (isset($_SESSION['loggedIn']) || isset($_COOKIE['save_login'])) {
            return true;
        } else {
            header("Location: login.php");
            SessionMessage::set_alert_messages("You are not logged in, please log in.");
            return false;
        }
    }

    /** Forgot password
     * 
     * @param string $email The user's email
     * 
     * Sanitize the email
     * 
     * Set a reset token cookie valid for 1 hour 
     * 
     * Insert a reset token into the database for later verification
     * 
     * Send an email to the customer's email address that they can use to reset their account
     * 
     * @return void Sends an email when successfull
     * 
     */
    public function forgotPassword($email, $mail)
    {
        // Sanitize the email
        $email = FormSanitizer::sanitizeLoginEmail($email);
        // get the user
        $query = $this->con->prepare("SELECT * from users WHERE email=:email");
        $query->bindValue(":email", $email);
        $query->execute();

        // If the email is found then fetch the data
        if ($query->rowCount() == 1) {
            // fetch the data from the database
            $row = $query->fetch(PDO::FETCH_ASSOC);
            echo "User: <strong>" . $row['username'] . "</strong> found in the database <br>";
            // create a temp code
            $reset_token = md5($email . microtime());
            echo $reset_token . "<br>";
            // Set the temp code in cookies for 1hour
            setcookie("reset_token", $reset_token, time() + 3600);
            // Set the temp code in the database
            $query = $this->con->prepare("UPDATE users SET reset_token = :reset_token");
            $query->bindValue(":reset_token", $reset_token);
            $query->execute();
            // When the user is found send reset email
            echo "The rows found: " . $query->rowCount();
            // Send the reset email
            $email = "$email";
            $subject = "Reset your password";
            $url = $this->baseProdPath . "reset_password.php?email=$email&code=$reset_token'> Reset Your Password";
            $message = "Here is you reset code: $reset_token <br>
                        Please click here to reset your password <a href='$url'";

            // Email the user
            $this->sendResetEmail($mail, $email, $subject, $message);
            // Redirect temp for testing
            header("Location: reset_password.php");
        } else {
            // ***Message not displaying at the correct time
            SessionMessage::set_alert_messages("Email not registered.");
        }
    }

    /** Reset the password
     * 
     * @param string $email The user's email
     * 
     * @param string $code The reset validation code
     * 
     * @param string $password The user's password
     * 
     * @param string $confirm_password The user's confirmation password
     * 
     * Clean the received data
     * 
     * Check that the passwords match
     * 
     * Hash the new password
     * 
     * Insert the new password into the database
     * 
     * @return void If all goes well then the user's password will be updated and the user will be redirected to the login screen to login
     */
    public function resetPassword($email, $code, $password, $confirm_password)
    {

        // Check that the reset token has not expired AND OR is set
        if (isset($_COOKIE['reset_token'])) {
            SessionMessage::set_success_messages("Token is present");

            $email = FormSanitizer::sanitizeFormEmail($email);
            $code = FormSanitizer::sanitizeFormString($code);
            $password = FormSanitizer::sanitizeFormPassword($password);
            $confirm_password = FormSanitizer::sanitizeFormPassword($confirm_password);

            // Check that the passwords match
            if ($password === $confirm_password) {

                // Select the user from the database with the email and reset_token matching
                $query = $this->con->prepare("SELECT * FROM users WHERE email=:email AND reset_token=:reset_token");
                $query->bindValue("email", $email);
                $query->bindValue("reset_token", $code);
                $query->execute();

                // If the user is found update the password in the database
                if ($query->rowCount() == 1) {
                    // Hash the new password
                    $password = password_hash($password, PASSWORD_DEFAULT);

                    // Update the user rescord
                    $query2 = $this->con->prepare("UPDATE users SET reset_code=0, password=:password WHERE email=:email");
                    $query2->bindValue("email", $email);
                    $query2->bindValue("password", $password);
                    $query2->execute();

                    // If all goes well redirect and set success message
                    if ($query2->rowCount() == 1) {
                        SessionMessage::set_success_messages("Your password has been updated successfully. Please log in");
                        header("Location: login.php");
                    } else {
                        // error
                        SessionMessage::set_alert_messages("Unable to update your password.");
                    }
                } else {
                    // Set message -> If user is not found check email and code
                    SessionMessage::set_alert_messages("User not found, please check your email or reset token.");
                }

                // When found then Hash the password and update the db
                password_hash($password, PASSWORD_DEFAULT);
                // Insert the password into the database
                $query = $this->con->prepare("UPDATE users 
                                            SET password=:password,
                                            reset_token=0
                                            WHERE email=:email
                                            AND reset_token=:reset_token");
                $query->bindValue(":password", $password);
                $query->bindValue(":reset_token", $code);
                $query->bindValue(":email", $email);

                $query->execute();

                if ($query->rowCount() == 1) {
                    // Set success message
                    SessionMessage::set_success_messages("Your password has been updated");
                    header(("Location: login.php"));
                } else {
                    SessionMessage::set_alert_messages("Unable to update password");
                }
            } else {
                echo "<p class='callout-danger'>Passwords does not match<p>";
            }
        } else {
            SessionMessage::set_alert_messages("Reset token not found");
        }
    }

    /** Send Password Reset Email
     * 
     * This function will have to be tested in a online server environment
     * 
     * @param object $mail The PHPMailer object
     * 
     * @param string $email The user's email
     * 
     * @param string $subject The subject of the email
     * 
     * @param string $message The email body
     * 
     * Set from email
     * 
     * @return void Send a reset password email
     */
    private function sendResetEmail($mail, $email, $subject, $message)
    {

        // from email
        $fromEmail = 'reset@meliorateAfrica.com';

        // Try and send the email
        try {
            //Recipients
            $mail->setFrom($fromEmail, 'Meliorate Africa'); // Where the email will be sent from
            $mail->addAddress($email);     //Add a recipient -> Where to send the email to
            //$mail->addCC('tertiussc@meliorateafrica.com', 'Tertius'); //Optional

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;
            $mail->AltBody = strip_tags($message);

            if ($mail->send()) {
                SessionMessage::set_success_messages("An email has been sent to reset your password");
            };
        } catch (Exception $e) {
            SessionMessage::set_alert_messages("Message was not sent:");
            header("Location: login.php");
        }
    }

    /** Retrieve the user's information
     * 
     * @param string $email The logged in user's email address
     * 
     * Clean the email
     * 
     * Retrieve the user account information
     * 
     * @return object $userInfo The user's account information object
     */
    public function retrieveUserInfo($email)
    {
        // Clean the data
        FormSanitizer::sanitizeFormEmail($email);

        // Select the user in the database
        $query = $this->con->prepare("SELECT * FROM users WHERE email=:email");
        $query->bindValue("email", $email);
        $query->execute();

        // When the user is found retrieve and populate the User Info array
        if ($query->rowCount() == 1) {
            $userInfo = $query->fetch(PDO::FETCH_ASSOC);
            return $userInfo;
        }
    }

    /** Update profile details
     * 
     * @param string $firstname The user's firstname
     * 
     * @param string $lastname The user's lastname
     * 
     * @param string $username The user's username
     * 
     * @param string $email The user's email
     * 
     * @param string $password The user's password
     * 
     * @param string $confirm_password The user's confirm_password
     * 
     * Clean the data
     * 
     * Validate the data
     * 
     * Hash the new password
     * 
     * Update the user's profile information
     * 
     * @return void Update the user's profile information or show errors should they occur
     */
    public function updateProfile($firstname, $lastname, $username, $email, $password, $confirm_password)
    {
        // Clean the data
        $firstname = FormSanitizer::sanitizeFormString($firstname);
        $lastname = FormSanitizer::sanitizeFormString($lastname);
        $username = FormSanitizer::sanitizeFormString($username);
        $email = FormSanitizer::sanitizeFormEmail($email);
        $password = FormSanitizer::sanitizeFormPassword($password);
        $confirm_password = FormSanitizer::sanitizeFormPassword($confirm_password);

        // Validate the fields
        $this->validateFirstname($firstname);
        $this->validateLastname($lastname);
        $this->validateLastname($username);
        $this->validateLastname($email);
        $this->validatePasswords($password, $confirm_password);

        // Check that passwords match
        if (empty($this->errorArray)) {
            echo "No errors found";
            // hash the password
            $password = password_hash($password, PASSWORD_DEFAULT);

            // update the information on the database
            $query = $this->con->prepare("UPDATE users 
                                    SET firstname=:firstname, lastname=:lastname, password=:password
                                    WHERE email=:email AND username=:username");

            $query->bindValue(":firstname", $firstname);
            $query->bindValue(":lastname", $lastname);
            $query->bindValue(":password", $password);
            $query->bindValue(":email", $email);
            $query->bindValue(":username", $username);

            $query->execute();

            if ($query->rowCount() == 1) {
                SessionMessage::set_success_messages("Your details have been updated");
                header("Location: profile.php");
            } else {
                SessionMessage::set_alert_messages("Your details was NOT updated");
                header("Location: profile.php");
            }
        } else {
            echo "<p class='callout-danger'>Some error/s have occurred!</p>";
        }
    }
}
