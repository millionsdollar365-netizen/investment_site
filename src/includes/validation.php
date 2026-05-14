<?php
/**
 * PRIMEAXIS INVESTMENT PLATFORM
 * Validation Helper Functions
 */

class Validator {
    private static $errors = [];
    
    /**
     * Validate email
     */
    public static function email($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    
    /**
     * Validate required field
     */
    public static function required($value) {
        return !empty(trim($value));
    }
    
    /**
     * Validate minimum length
     */
    public static function minLength($value, $length) {
        return strlen($value) >= $length;
    }
    
    /**
     * Validate maximum length
     */
    public static function maxLength($value, $length) {
        return strlen($value) <= $length;
    }
    
    /**
     * Validate numeric
     */
    public static function numeric($value) {
        return is_numeric($value);
    }
    
    /**
     * Validate positive number
     */
    public static function positive($value) {
        return is_numeric($value) && $value > 0;
    }
    
    /**
     * Validate matches regex
     */
    public static function regex($value, $pattern) {
        return preg_match($pattern, $value) === 1;
    }
}
?>
