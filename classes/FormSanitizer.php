<?php

/**
 * Sanitize fields by removing HTML tags, trim all leading and laging spaces, replace spaces between text with underscores, lower all letters and finally uppercase the first letter
 * 
 * @param string $inputText The input that needs to be sanitized
 * 
 * @return string $inputText The sanitized input string
 */

class FormSanitizer
{

    /** Sanitize input strings
     * 
     * @param string $input_text
     * 
     * Strip HTML tags
     * 
     * Trim whitespace and leading and lagging spaces
     * 
     * Convert everything to lowercase
     * 
     * Convert the first letter to uppercase
     * 
     * @return string Return sanitized string 
     */
    public static function sanitizeFormString($input_text)
    {
        // Strip all HTML tags
        $input_text = strip_tags($input_text);
        // Strip all leading and laging spaces
        $input_text = trim($input_text);
        // conevert to lowercase
        $input_text = strtolower($input_text);
        // Convert first letter to uppercase
        $input_text = ucfirst($input_text);
        // return the value
        return $input_text;
    }

    /** Sanitize username
     * 
     * @param string $input_text
     * 
     * Strip HTML tags
     * 
     * Trim whitespace and leading and lagging spaces
     * 
     * Replace all in between spaces with underscores 
     * 
     * @return string Returns the sanitized username 
     */
    public static function sanitizeFormUsername($input_text)
    {
        // Strip all HTML tags
        $input_text = strip_tags($input_text);
        // Strip all leading and laging spaces
        $input_text = trim($input_text);
        // replace spaces between the username with underscores 
        $input_text = str_replace(" ", "_", $input_text);

        return $input_text;
    }

    /** Sanitize password
     * 
     * @param string $input_string The input password
     * 
     * Strip all HTML tags
     * 
     * @return string Returns the sanitized password
     * 
     */
    public static function sanitizeFormPassword($input_text)
    {
        // Strip all HTML tags
        $input_text = strip_tags($input_text);

        return $input_text;
    }

    /** Sanitize form email
     * 
     * @param string $input_text The form input that will need to be sanitized 
     * 
     * Convert to lower case
     * 
     * Strip HTML tags
     * 
     * Trim whitespace and lead and laging spaces
     * 
     * Remove spaces
     * 
     * @return string The sanitized string
     */
    public static function sanitizeFormEmail($input_text)
    {
        // Change all to lowercase
        $input_text = strtolower($input_text);
        // Strip all HTML tags
        $input_text = strip_tags($input_text);
        // Strip all leading and laging spaces
        $input_text = trim($input_text);
        // replace spaces between the username with underscores 
        $input_text = str_replace(" ", "", $input_text);

        return $input_text;
    }

    /** Sanitize login email
     * This might not be needed
     * 
     * @param string $input_text The input string
     * 
     * Strip HTML tags
     * 
     * Change all characters to lowercase
     * 
     * @return string The sanitized login email
     * 
     */
    public static function sanitizeLoginEmail($input_text)
    {
        // remove special characters
        $input_text = strip_tags($input_text);
        //  convert to lowercase
        $input_text = strtolower($input_text);
        // Return the value
        return $input_text;
    }

    /** Sanitize Code
     * 
     * @param string $input_text The validation code
     * 
     * convert all HTML entities
     * 
     * @return string The sanitized Code
     */
    public static function sanitizeCode($input_text)
    {
        // Clean the code
        $input_text = htmlentities($input_text);
        // Return the value
        return $input_text;
    }
}
