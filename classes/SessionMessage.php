<?php
/** Session Messages
 * 
 * This session messiging class provides all the functionality to
 * 
 * Display a message stored in sessions (and remove it after it has been displayed)
 * 
 * Set Success messages in Sessions
 * 
 * Set Alert messages in Sessions
 */

class SessionMessage
{
    /** Set alert messages in sessions
     * 
     * @param string $message Set the message that you want to be contained in sessions
     * 
     */
    public static function set_alert_messages($message)
    {
        if (!empty($message)) {
            $_SESSION['message'] = "<div class='alert alert-danger alert-dismissible fade show' role='alert'>
                    $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            $message = "";
        }
    }

    /** Set success messages in sessions
     * 
     * @param string $message Set the message that you want to be contained in sessions
     * 
     */
    public static function set_success_messages($message)
    {
        if (!empty($message)) {
            $_SESSION['message'] = "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    $message
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>";
        } else {
            $message = "";
        }
    }

    /** Display message if is set in sessions
     * @return string Session message
     */
    public static function display_message()
    {
        if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            unset($_SESSION['message']);
        };
    }
}
