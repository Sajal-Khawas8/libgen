<?php

/**
 * This class is responsible for sanitizing and validating the input data
 * It contains all the methods for validating the input data
 * It also contains a method 'validateLoginDataAndSearchUser' which searches a user and returns the email for search utility
 */
class ValidateData
{
    /**
     * Sanitizes the input data.
     * It removes the white spaces, slashes and removes special characters
     * 
     * @param string $data The input data from the form 
     * @return void
     */
    private function cleanData(string &$data): void
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
    }

    /**
     * Checks if the input data is empty or not and sets the error message.
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data is empty
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data is empty, false otherwise
     */
    private function isEmpty(string $data, string|null &$msg, string $field): bool
    {
        if (empty($data)) {
            $msg = "*Please enter $field";
            return true;
        }
        return false;
    }

    /**
     * Checks if the input data contains atleast 3 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data does not contain atleast 3 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data does not contain atleast 3 characters, false otherwise
     */
    private function isInvalidMinLengthText(string $data, string|null &$msg, string $field): bool
    {
        if (strlen($data) < 3) {
            $msg = "*{$field} must contain more than 3 characters";
            return true;
        }
        return false;
    }

    /**
     * Checks if the input data contains more than 15 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data contains more than 15 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data contains more than 50 characters, false otherwise
     */
    private function isInvalidMaxLengthText(string $data, string|null &$msg, string $field): bool
    {
        if (strlen($data) > 50) {
            $msg = "*{$field} must contain less than 50 characters";
            return true;
        }
        return false;
    }
    /**
     * Checks if the input data contains atleast 3 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data does not contain atleast 3 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data does not contain atleast 3 characters, false otherwise
     */
    private function isInvalidMinLengthTextarea(string $data, string|null &$msg, string $field): bool
    {
        if (strlen($data) < 10) {
            $msg = "*{$field} must contain more than 10 characters";
            return true;
        }
        return false;
    }

    /**
     * Checks if the input data contains more than 15 characters or not
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data contains more than 15 characters
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data contains more than 15 characters, false otherwise
     */
    private function isInvalidMaxLengthTextarea(string $data, string|null &$msg, string $field): bool
    {
        if (strlen($data) > 500) {
            $msg = "*{$field} must contain less than 500 characters";
            return true;
        }
        return false;
    }

    /**
     * Checks if the rent is greater than 0 or not
     * 
     * @param string $rent The rent of the book
     * @param string|null $msg The variable to contain the error message if rent is less than or equal to 0
     * @return bool Returns true if rent is less than or equal to 0, false otherwise
     */
    private function isInvalidMinRent(string $rent, string|null &$msg): bool
    {
        if ($rent <= 0) {
            $msg = "*Rent must be greater than 0";
            return true;
        }
        return false;
    }

    /**
     * Checks if the format of input data is valid or not
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data is not in valid form
     * @param string $field The type of input field which is to be checked
     * @return bool Returns true if the data is not in valid form, false otherwise
     */
    private function isInvalidFormat(string $data, string|null &$msg, string $field): bool
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
        return false;
    }

    /**
     * Checks if the input data is already present in the database or not
     * 
     * @param string $data The input data that is to be checked
     * @param string|null $msg The variable to contain the error message if data is already present in the database
     * @param string $field The type of input field which is to be checked
     * @param string $dataType The column to search in database
     * @param string $id The UUID of the user
     * @return bool Returns true if the data is already present in the database, false otherwise
     */
    private function isRedundantData(string $data, string|null &$msg, string $field, string $dataType, string $id = null): bool
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
        return false;
    }

    /**
     * Checks if the password is correct or not
     * 
     * @param string $password The password to check
     * @param string $passwordToCompare The password to compare
     * @return bool Returns true if the password matches, false otherwise
     */
    private function verifyPasswords(string $password, string $passwordToComapare): bool
    {
        return password_verify($password, $passwordToComapare);
    }

    /**
     * santizes and Checks if the input text data is valid or not
     * It checks for empty value, minimum length, maximum length and format of data
     * 
     * @param string $data The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateTextData(string &$data, bool &$isDataValid, string $field)
    {
        $this->cleanData($data);
        if ($this->isEmpty($data, $errMsg, $field) || $this->isInvalidMinLengthText($data, $errMsg, $field) || $this->isInvalidMaxLengthText($data, $errMsg, $field) || $this->isInvalidFormat($data, $errMsg, $field)) {
            $isDataValid = false;
            return $errMsg;
        }
        $data = ucwords($data);
    }

    /**
     * Santizes and Checks if the input text data is valid or not
     * It checks for minimum length, maximum length and format of data
     * 
     * @param string $data The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateUpdatedTextData(string &$data, bool &$isDataValid, string $field)
    {
        $this->cleanData($data);
        if (!empty($data) && ($this->isInvalidMinLengthText($data, $errMsg, $field) || $this->isInvalidMaxLengthText($data, $errMsg, $field) || $this->isInvalidFormat($data, $errMsg, $field))) {
            $isDataValid = false;
            return $errMsg;
        }
        $data = ucwords($data);
    }

    /**
     * santizes and Checks if the input text data is valid or not
     * It checks for empty value and minimum length
     * 
     * @param string $data The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateTextArea(string &$data, bool &$isDataValid, string $field)
    {
        $this->cleanData($data);
        if ($this->isEmpty($data, $errMsg, $field) || $this->isInvalidMinLengthTextarea($data, $errMsg, $field)) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    /**
     * santizes and Checks if the input text data is valid or not
     * It checks for minimum length
     * 
     * @param string $data The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateUpdatedTextArea(string &$data, bool &$isDataValid, string $field)
    {
        $this->cleanData($data);
        if (!empty($data) && ($this->isInvalidMinLengthTextarea($data, $errMsg, $field))) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    /**
     * Sanitizes and Checks if the email is valid or not
     * It checks for format and redundancy of email
     * It also converts the email to lowercase
     * 
     * @param string $email The input email that is to be checked
     * @param bool $isDataValid The variable to track if the email is valid or not
     * @return string|void Returns the error message if the email is not valid, or null otherwise
     */
    public function validateEmail(string &$email, bool &$isDataValid)
    {
        $this->cleanData($email);
        if ($this->isEmpty($email, $errMsg, 'Email') || $this->isInvalidFormat($email, $errMsg, 'Email Address') || $this->isRedundantData($email, $errMsg, 'Email Address', 'email')) {
            $isDataValid = false;
            return $errMsg;
        }
        $email = strtolower($email);
    }

    /**
     * Sanitizes and Checks if the email is valid or not
     * It checks for format and redundancy of email
     * It also converts the email to lowercase
     * 
     * @param string $email The input email that is to be checked
     * @param bool $isDataValid The variable to track if the email is valid or not
     * @param string $id The UUID of the user
     * @return string|void Returns the error message if the email is not valid, or null otherwise
     */
    public function validateUpdatedEmail(string &$email, bool &$isDataValid, string $id)
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
     * It also converts the password to password hash
     * 
     * @param string $password The input password that is to be checked
     * @param bool $isDataValid The variable to track if the password is in valid format or not
     * @return string|void Returns the error message if the password is not in valid format, or null otherwise
     */
    public function validatePasswordFormat(string &$password, bool &$isDataValid)
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
     * @param string $cnfrmPassword The input confirm password that is to be checked
     * @param string $password The original password
     * @param bool $isDataValid The variable to track if the confirm password is valid or not
     * @return string|void Returns the error message if the confirm pasword is not valid, or null otherwise
     */
    public function validateCnfrmPassword(string $cnfrmPassword, string $password, bool &$isDataValid)
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

    /**
     * Checks if the uploaded file is correct format or not
     * 
     * @param mixed $uploadedFile The uploaded file
     * @param bool $isDataValid The variable to track if the file is in correct format or not
     * @return string|void Returns the error message if the file is not in correct format, null otherwise
     */
    public function validatePictureFormat(mixed $uploadedFile, bool &$isDataValid)
    {
        if ($this->isEmpty($uploadedFile['name'], $msg, 'Image') || (!in_array(strtolower(pathinfo($uploadedFile['name'])['extension']), ['jpg', 'jpeg', 'png', 'webp']))) {
            $isDataValid = false;
            return "*Please select jpg, jpeg, png or webp file";
        }
    }

    /**
     * Checks if the uploaded file is correct format or not
     * 
     * @param mixed $uploadedFile The uploaded file
     * @param bool $isDataValid The variable to track if the file is in correct format or not
     * @return string|void Returns the error message if the file is not in correct format, null otherwise
     */
    public function validateUpdatedPictureFormat(mixed $uploadedFile, bool &$isDataValid)
    {
        if (!empty($uploadedFile['name']) && (!in_array(strtolower(pathinfo($uploadedFile['name'])['extension']), ['jpg', 'jpeg', 'png', 'webp']))) {
            $isDataValid = false;
            return "*Please select jpg, jpeg, png or webp file";
        }
    }

    /**
     * Santizes and Checks if the input data is valid or not
     * It checks for empty data, minimum length, maximum length, format and redundancy of data
     * 
     * @param string $name The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateUniqueName(string &$name, bool &$isDataValid, string $field)
    {
        $this->cleanData($name);
        if ($this->isEmpty($name, $errMsg, $field) || $this->isInvalidMinLengthText($name, $errMsg, $field) || $this->isInvalidMaxLengthText($name, $errMsg, $field) || $this->isInvalidFormat($name, $errMsg, $field) || $this->isRedundantData($name, $errMsg, 'Category', 'name')) {
            $isDataValid = false;
            return $errMsg;
        }
        $name = ucwords($name);
    }

    /**
     * Santizes and Checks if the input data is valid or not
     * It checks for minimum length, maximum length, format and redundancy of data
     * 
     * @param string $name The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @param mixed $id Unique identification of the data
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateUpdatedUniqueName(string &$name, bool &$isDataValid, string $field, mixed $id)
    {
        $this->cleanData($name);
        if (!empty($name) && ($this->isInvalidMinLengthText($name, $errMsg, $field) || $this->isInvalidMaxLengthText($name, $errMsg, $field) || $this->isInvalidFormat($name, $errMsg, $field) || $this->isRedundantData($name, $errMsg, 'Category', 'name', $id))) {
            $isDataValid = false;
            return $errMsg;
        }
        $name = ucwords($name);
    }

    /**
     * Santizes and Checks if the input data is valid or not
     * It checks for empty data, format and minimum value of data
     * 
     * @param string $rent The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateNumber(string &$rent, bool &$isDataValid, string $field)
    {
        $this->cleanData($rent);
        if ($this->isEmpty($rent, $errMsg, $field) || $this->isInvalidFormat($rent, $errMsg, $field) || $this->isInvalidMinRent($rent, $errMsg)) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    /**
     * Santizes and Checks if the input data is valid or not
     * It checks for format and minimum value of data
     * 
     * @param string $rent The input data that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateUpdatedNumber(string &$rent, bool &$isDataValid, string $field)
    {
        $this->cleanData($rent);
        if (!empty($rent) && ($this->isInvalidFormat($rent, $errMsg, $field) || $this->isInvalidMinRent($rent, $errMsg))) {
            $isDataValid = false;
            return $errMsg;
        }
    }

    /**
     * Santizes and Checks if the book title is valid or not
     * It checks for empty data, minimum length, maximum length, format and redundancy of data
     * 
     * @param string $title The book title that is to be checked
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if the data is not valid, or null otherwise
     */
    public function validateBookTitle(string &$title, bool &$isDataValid, string $field)
    {
        $this->cleanData($title);
        if ($this->isEmpty($title, $errMsg, $field) || $this->isInvalidMinLengthText($title, $errMsg, $field) || $this->isInvalidMaxLengthText($title, $errMsg, $field) || $this->isInvalidFormat($title, $errMsg, $field) || $this->isRedundantData($title, $errMsg, $field, 'title')) {
            $isDataValid = false;
            return $errMsg;
        }
        $title = ucwords($title);
    }

    /**
     * Checks if any option is selected from select box or not
     * 
     * @param string $data The data from the select box
     * @param bool $isDataValid The variable to track if the data is valid or not
     * @param string $field The type of input field which is to be checked
     * @return string|void Returns the error message if tno option is selected, or null otherwise
     */
    public function validateSelectBox(string &$data, bool &$isDataValid, string $field)
    {
        $config = require "./core/config.php";
        $data = openssl_decrypt($data, $config['openssl']['algo'], $config['openssl']['pass'], 0, $config['openssl']['iv']);
        if (!$data) {
            $isDataValid = false;
            return "*Please select $field";
        }
    }

    /**
     * Checks if the email is valid and is of admin or not
     * 
     * @param string $email The input email
     * @param bool $isDataValid The variable to track if the email is valid or not
     * @return string|void Returns the error message if the email is not valid, or null otherwise
     */
    public function validateAdminLoginEmail(string &$email, bool &$isDataValid)
    {
        $this->cleanData($email);
        if ($this->isEmpty($email, $errMsg, 'Email') || $this->isInvalidFormat($email, $errMsg, 'Email')) {
            $isDataValid = false;
            return $errMsg;
        }

        $query = new DatabaseQuery();
        $data = $query->selectOne('users', $email, 'email');
        if (!$data['active'] || $data['role'] === '1') {
            $isDataValid = false;
            return "*Invalid Email Address";
        }
    }

    /**
     * Checks if the email is valid or not
     * 
     * @param string $email The input email
     * @param bool $isDataValid The variable to track if the email is valid or not
     * @return string|void Returns the error message if the email is not valid, or null otherwise
     */
    public function validateLoginEmail(string &$email, bool &$isDataValid)
    {
        $this->cleanData($email);
        if ($this->isEmpty($email, $errMsg, 'Email') || $this->isInvalidFormat($email, $errMsg, 'Email')) {
            $isDataValid = false;
            return $errMsg;
        }

        $query = new DatabaseQuery();
        $data=$query->selectOne('users', $email, 'email');
        if (!$data['active'] || $data['role'] !== '1') {
            $isDataValid = false;
            return "*No account with this email";
        }
    }

    /**
     * Checks if the login password is correct for the login email or not
     * 
     * @param string $password The login password
     * @param string $email The login email
     * @param bool $isDataValid The variable to track if the password is valid or not
     * @return string|void Returns the error message if the password is not valid, or null otherwise
     */
    public function validatePassword(string $password, string $email, bool &$isDataValid)
    {
        $this->cleanData($password);
        if ($this->isEmpty($password, $errMsg, 'Password')) {
            $isDataValid = false;
            return $errMsg;
        }

        $query = new DatabaseQuery();
        $data = $query->selectOne('users', $email, 'email');
        if (!$data['active'] || !$this->verifyPasswords($password, $data['password'])) {
            $isDataValid = false;
            return "*Invalid Password";
        }
    }

    /**
     * Checks if the card number is valid or not
     * 
     * @param string $cardNumber The input card number
     * @param bool $isDataValid The variable to track if the card number is valid or not
     * @return string|void Returns the error message if the card number is not valid, or null otherwise
     */
    public function validateCardNumber(string &$cardNumber, bool &$isDataValid)
    {
        $this->cleanData($cardNumber);
        if (!preg_match("/^(\d{4}-\d{4}-\d{4}-\d{4})$/", $cardNumber)) {
            $isDataValid = false;
            return "*Please enter a valid card number";
        }
    }

    /**
     * Checks if the CVV number is valid or not
     * 
     * @param string $cvv The input CVV number
     * @param bool $isDataValid The variable to track if the CVV number is valid or not
     * @return string|void Returns the error message if the CVV number is not valid, or null otherwise
     */
    public function validateCVV(string &$cvv, bool &$isDataValid)
    {
        $this->cleanData($cvv);
        if (!preg_match("/^\d{3}$/", $cvv)) {
            $isDataValid = false;
            return "*Please enter a valid CVV";
        }
    }

    /**
     * Checks if the card expiry date is valid or not
     * 
     * @param string $date The input card expiry date
     * @param bool $isDataValid The variable to track if the card expiry date is valid or not
     * @return string|void Returns the error message if the card expiry date is not valid, or null otherwise
     */
    public function validateExpiryDate(string &$date, bool &$isDataValid)
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

    /**
     * Checks if the return date is valid or not
     * 
     * @param string $cardNumber The input return date
     * @param bool $isDataValid The variable to track if the return date is valid or not
     * @return string|void Returns the error message if the return date is not valid, or null otherwise
     */
    public function validateReturnDate(string &$date, bool &$isDataValid)
    {
        $this->cleanData($date);
        if (!preg_match("/^(\d{4}-\d{2}-\d{2})$/", $date) || $date <= date("Y-m-d") || $date > date('Y-m-d', strtotime('+6 months', strtotime(date("Y-m-d"))))) {
            $isDataValid = false;
            return "*Please choose a valid return date";
        }
    }
}