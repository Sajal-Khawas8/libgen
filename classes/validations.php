<?php

/**
 * This class is responsible for sanitizing and validating the input data
 * It contains all the methods for validating the input data
 * It also contains a method 'validateLoginDataAndSearchUser' which searches a user and returns the email for search utility
 */
class ValidateData
{
    /**
     * Uses the Connection trait from 'database.php' file which performs following functions:
     * 1. Establishes connection with the database (through constructor)
     * 2. Closes the connection after opertion (through destructor)
     */
    use Connection;

    /**
     * Sanitizes the input data.
     * It removes the white spaces, slashes and removes special characters
     * @param string $data The input data from the form 
     * @return void
     */
    private function cleanData(&$data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
    }

    /**
     * Checks if the input data is empty or not and sets the error message.
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data is empty
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data is empty, false otherwise
     */
    private function isEmpty($data, &$msg, $field)
    {
        if (empty($data)) {
            $msg = "*Please enter $field";
            return true;
        }
    }

    /**
     * Checks if the input data contains atleast 3 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data does not contain atleast 3 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data does not contain atleast 3 characters, false otherwise
     */
    private function isInvalidMinLengthText($data, &$msg, $field)
    {
        if (strlen($data) < 3) {
            $msg = "*{$field} must contain more than 3 characters";
            return true;
        }
    }

    /**
     * Checks if the input data contains more than 15 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data contains more than 15 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data contains more than 15 characters, false otherwise
     */
    private function isInvalidMaxLengthText($data, &$msg, $field)
    {
        if (strlen($data) > 30) {
            $msg = "*{$field} must contain less than 30 characters";
            return true;
        }
    }
    /**
     * Checks if the input data contains atleast 3 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data does not contain atleast 3 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data does not contain atleast 3 characters, false otherwise
     */
    private function isInvalidMinLengthTextarea($data, &$msg, $field)
    {
        if (strlen($data) < 10) {
            $msg = "*{$field} must contain more than 10 characters";
            return true;
        }
    }

    /**
     * Checks if the input data contains more than 15 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data contains more than 15 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data contains more than 15 characters, false otherwise
     */
    private function isInvalidMaxLengthTextarea($data, &$msg, $field)
    {
        if (strlen($data) > 500) {
            $msg = "*{$field} must contain less than 500 characters";
            return true;
        }
    }

    private function isInvalidMinRent($rent, &$msg)
    {
        if ($rent <= 0) {
            $msg = "*Rent must be greater than 0";
            return true;
        }
    }

    /**
     * Checks if the format of input data is valid or not
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data is not in valid form
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data is not in valid form, false otherwise
     */
    private function isInvalidFormat($data, &$msg, $field)
    {
        $field = strtok($field, " ");
        switch ($field) {
            case 'Name':
                if (!preg_match("/^[a-zA-Z .,]*$/", $data)) {
                    $msg = "*$field must contain only letters and white spaces";
                    return true;
                }
                break;
            case 'Email':
                if (!filter_var($data, FILTER_VALIDATE_EMAIL)) {
                    $msg = "*Invalid Email Address";
                    return true;
                }
                break;
            case 'Password':
                if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*()-_+]).{8,16}$/", $data)) {
                    $msg = "*Your password must contain: <ul class='list-disc list-inside p-2.5'> <li>Uppercase letters (A-Z)</li><li>Lowercase letters (a-z)</li><li>Numbers (0-9)</li><li>Special Characters (!@#$%^&*()-_+)</li><li>Minimum 8 and Maximum 16 characters</li></ul>";
                    return true;
                }
                break;
            case 'Rent':
            case 'Copies':
                if (!preg_match("/^[0-9]*$/", $data)) {
                    $msg = "*Invalid $field";
                    return true;
                }
                break;
            case 'Title':
                if (!preg_match("/^(?=.*[a-zA-Z]{3,})([0-9a-zA-Z ()&,-]*)$/", $data)) {
                    $msg = "*$field must contain only letters, numbers, hyphen and paranthesis";
                    return true;
                }
                break;
        }

    }

    /**
     * Checks if the input data is already present in the database or not
     * 
     * @param string $data The input data that is to be checked
     * @param string $msg The variable to contain the error message if data is already present in the database
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data is already present in the database, false otherwise
     */
    private function isRedundantData($data, &$msg, $field, $dataType, $id = null)
    {
        switch ($field) {
            case 'Email Address':
                $column = 'uuid';
                $table = 'users';
                break;
            case 'Category':
                $column = 'id';
                $table = 'category';
                break;
            case 'Title':
                $column = 'uuid';
                $table = 'books';
                break;
        }
        $query = new DatabaseQuery();
        $uuid = $query->selectColumn($column, $table, $data, $dataType);
        if ($uuid !== false && $uuid !== $id) {
            $msg = "*This $field has already been taken";
            return true;
        }
    }

    private function verifyPasswords($password, $passwordToComapare)
    {
        return password_verify($password, $passwordToComapare);
    }

    /**
     * santizes and Checks if the input text data is valid or not
     * It checks for empty value, minimum length, maximum length and format of data
     * 
     * @param string $data The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @return string|null Returns the error message if the data is not valid, or null otherwise
     */
    public function validateTextData(&$data, &$isDataValid, $field)
    {
        $this->cleanData($data);
        if ($this->isEmpty($data, $errMsg, $field) || $this->isInvalidMinLengthText($data, $errMsg, $field) || $this->isInvalidMaxLengthText($data, $errMsg, $field) || $this->isInvalidFormat($data, $errMsg, $field)) {
            $isDataValid = false;
            return $errMsg;
        }
        $data = ucwords($data);
    }

    public function validateUpdatedTextData(&$data, &$isDataValid, $field)
    {
        $this->cleanData($data);
        if (!empty($data) && ($this->isInvalidMinLengthText($data, $errMsg, $field) || $this->isInvalidMaxLengthText($data, $errMsg, $field) || $this->isInvalidFormat($data, $errMsg, $field))) {
            $isDataValid = false;
            return $errMsg;
        }
        $data = ucwords($data);
    }

    public function validateTextArea(&$data, &$isDataValid, $field)
    {
        $this->cleanData($data);
        if ($this->isEmpty($data, $errMsg, $field) || $this->isInvalidMinLengthTextarea($data, $errMsg, $field) || $this->isInvalidMaxLengthTextarea($data, $errMsg, $field)) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    public function validateUpdatedTextArea(&$data, &$isDataValid, $field)
    {
        $this->cleanData($data);
        if (!empty($data) && ($this->isInvalidMinLengthTextarea($data, $errMsg, $field) || $this->isInvalidMaxLengthTextarea($data, $errMsg, $field))) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    /**
     * Sanitizes and Checks if the email is valid or not
     * It checks for format and redundancy of email
     * It also converts the email to lowercase
     * 
     * @param string $data The input email that is to be checked
     * @param bool $isDataValid The variable to track if the email is valid or not
     * @return string|null Returns the error message if the email is not valid, or null otherwise
     */
    public function validateEmail(&$email, &$isDataValid)
    {
        $this->cleanData($email);
        if ($this->isEmpty($email, $errMsg, 'Email') || $this->isInvalidFormat($email, $errMsg, 'Email Address') || $this->isRedundantData($email, $errMsg, 'Email Address', 'email')) {
            $isDataValid = false;
            return $errMsg;
        }
        $email = strtolower($email);
    }

    public function validateUpdatedEmail(&$email, &$isDataValid, $id)
    {
        $this->cleanData($email);
        if (!empty($email) && ($this->isInvalidFormat($email, $errMsg, 'Email Address') || $this->isRedundantData($email, $errMsg, 'Email Address', 'email', $id))) {
            $isDataValid = false;
            return $errMsg;
        }
        $email = strtolower($email);
    }

    /**
     * Checks if the password is in valid format or not
     * It checks for empty value and format of password
     * 
     * @param string $data The input password that is to be checked
     * @param bool $isDataValid The variable to track if the password is in valid format or not
     * @return string|null Returns the error message if the password is not in valid format, or null otherwise
     */
    public function validatePasswordFormat(&$password, &$isDataValid)
    {
        $this->cleanData($password);
        if ($this->isEmpty($password, $errMsg, 'Password') || $this->isInvalidFormat($password, $errMsg, 'Password')) {
            $isDataValid = false;
            return $errMsg;
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Checks if the confirm password is valid or not
     * It checks if the field is empty and if the confirm password matches with the actual password
     * 
     * @param string $data The input confirm password that is to be checked
     * @param bool $isDataValid The variable to track if the confirm password is valid or not
     * @return string|null Returns the error message if the confirm pasword is not valid, or null otherwise
     */
    public function validateCnfrmPassword($cnfrmPassword, $password, &$isDataValid)
    {
        $this->cleanData($cnfrmPassword);
        if (empty($cnfrmPassword)) {
            $isDataValid = false;
            return "*Please Confirm your Password";
        } elseif (!$this->verifyPasswords($cnfrmPassword, $password)) {
            $isDataValid = false;
            return "*Passwords do not match";
        }
    }

    public function validatePictureFormat($uploadedFile, &$isDataValid)
    {
        if ($this->isEmpty($uploadedFile['name'], $msg, 'Image') || (!in_array(strtolower(pathinfo($uploadedFile['name'])['extension']), ['jpg', 'jpeg', 'png', 'webp']))) {
            $isDataValid = false;
            return "*Please select jpg, jpeg, png or webp file";
        }
    }

    public function validateUpdatedPictureFormat($uploadedFile, &$isDataValid)
    {
        if (!empty($uploadedFile['name']) && (!in_array(strtolower(pathinfo($uploadedFile['name'])['extension']), ['jpg', 'jpeg', 'png', 'webp']))) {
            $isDataValid = false;
            return "*Please select jpg, jpeg, png or webp file";
        }
    }

    public function validateUniqueName(&$name, &$isDataValid, $field)
    {
        $this->cleanData($name);
        if ($this->isEmpty($name, $errMsg, $field) || $this->isInvalidMinLengthText($name, $errMsg, $field) || $this->isInvalidMaxLengthText($name, $errMsg, $field) || $this->isInvalidFormat($name, $errMsg, $field) || $this->isRedundantData($name, $errMsg, 'Category', 'name')) {
            $isDataValid = false;
            return $errMsg;
        }
        $name = ucwords($name);
    }

    public function validateUpdatedUniqueName(&$name, &$isDataValid, $field, $id)
    {
        $this->cleanData($name);
        if (!empty($name) && ($this->isInvalidMinLengthText($name, $errMsg, $field) || $this->isInvalidMaxLengthText($name, $errMsg, $field) || $this->isInvalidFormat($name, $errMsg, $field) || $this->isRedundantData($name, $errMsg, 'Category', 'name', $id))) {
            $isDataValid = false;
            return $errMsg;
        }
        $name = ucwords($name);
    }

    public function validateNumber(&$rent, &$isDataValid, $field)
    {
        $this->cleanData($rent);
        if ($this->isEmpty($rent, $errMsg, $field) || $this->isInvalidFormat($rent, $errMsg, $field) || $this->isInvalidMinRent($rent, $errMsg)) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    public function validateUpdatedNumber(&$rent, &$isDataValid, $field)
    {
        $this->cleanData($rent);
        if (!empty($rent) && ($this->isInvalidFormat($rent, $errMsg, $field) || $this->isInvalidMinRent($rent, $errMsg))) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    public function validateBookTitle(&$title, &$isDataValid, $field)
    {
        $this->cleanData($title);
        if ($this->isEmpty($title, $errMsg, $field) || $this->isInvalidMinLengthText($title, $errMsg, $field) || $this->isInvalidMaxLengthText($title, $errMsg, $field) || $this->isInvalidFormat($title, $errMsg, $field) || $this->isRedundantData($title, $errMsg, $field, 'title')) {
            $isDataValid = false;
            return $errMsg;
        }
        $title = ucwords($title);
    }

    public function validateSelectBox(&$data, &$isDataValid, $field)
    {
        $config = require "./core/config.php";
        $data = openssl_decrypt($data, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        if (!$data) {
            $isDataValid = false;
            return "*Please select $field";
        }
    }
    public function validateAdminLoginEmail($email, &$isDataValid)
    {
        $this->cleanData($email);
        if ($this->isEmpty($email, $errMsg, 'Email') || $this->isInvalidFormat($email, $errMsg, 'Email')) {
            $isDataValid = false;
            return $errMsg;
        }

        $query = new DatabaseQuery();
        $data = $query->selectOne('users', $email, 'email');
        if (!$data['active'] || !$data['role']) {
            $isDataValid = false;
            return "*Invalid Email Address";
        }
    }

    public function validateLoginEmail($email, &$isDataValid)
    {
        $this->cleanData($email);
        if ($this->isEmpty($email, $errMsg, 'Email') || $this->isInvalidFormat($email, $errMsg, 'Email')) {
            $isDataValid = false;
            return $errMsg;
        }

        $query = new DatabaseQuery();
        if (!$query->selectColumn('active', 'users', $email, 'email')) {
            $isDataValid = false;
            return "*No account with this email";
        }
    }

    public function validatePassword($password, $uuid, &$isDataValid)
    {
        $this->cleanData($password);
        if ($this->isEmpty($password, $errMsg, 'Password')) {
            $isDataValid = false;
            return $errMsg;
        }

        $query = new DatabaseQuery();
        $data = $query->selectOne('users', $uuid, 'uuid');
        if (!$data['active'] || !$this->verifyPasswords($password, $data['password'])) {
            $isDataValid = false;
            return "*Invalid Password";
        }
    }

    public function validateCardNumber(&$cardNumber, &$isDataValid)
    {
        $this->cleanData($cardNumber);
        if (!preg_match("/^(\d{4}-\d{4}-\d{4}-\d{4})$/", $cardNumber)) {
            $isDataValid = false;
            return "*Please enter a valid card number";
        }
    }

    public function validateCVV(&$cvv, &$isDataValid)
    {
        $this->cleanData($cvv);
        if (!preg_match("/^\d{3}$/", $cvv)) {
            $isDataValid = false;
            return "*Please enter a valid CVV";
        }
    }

    public function validateExpiryDate(&$date, &$isDataValid)
    {
        $this->cleanData($date);
        if (!preg_match("/^(\d{2}\/\d{2})$/", $date)) {
            $isDataValid = false;
            return "*Please enter a valid card expiry date";
        }
        list($month, $year) = explode('/', $date);
        if ($month > 12 || $year > date('y') + 7) {
            $isDataValid = false;
            return "*Please enter a valid card expiry date";
        } elseif ($year < date('y') || ($year === date('y') && $month < date('m'))) {
            $isDataValid = false;
            return "*This card is expired";
        }
    }

    public function validateReturnDate(&$date, &$isDataValid)
    {
        $this->cleanData($date);
        if (!preg_match("/^(\d{4}-\d{2}-\d{2})$/", $date) || $date <= date("Y-m-d") || $date > date('Y-m-d', strtotime('+6 months', strtotime(date("Y-m-d"))))) {
            $isDataValid = false;
            return "*Please choose a valid return date";
        }
    }
}