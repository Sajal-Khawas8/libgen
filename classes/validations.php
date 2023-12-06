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
            $msg = "*Please enter your $field";
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
        if (strlen($data) > 15) {
            $msg = "*{$field} must contain less than 15 characters";
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
            case 'Title':
                if (!preg_match("/^[a-zA-Z ]*$/", $data)) {
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
    private function isRedundantData($data, &$msg, $field, $dataType, $userId = null)
    {
        $query = new DatabaseQuery();
        $id = $query->selectColumn('uuid', 'users', $data, $dataType);
        if ($id !== false && $id !== $userId) {
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
        if (!empty($uploadedFile['name']) && (!in_array(strtolower(pathinfo($uploadedFile['name'])['extension']), ['jpg', 'jpeg', 'png']))) {
            $isDataValid = false;
            return "*Please select jpg, jpeg or png file";
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
        $data=$query->selectOne('users', $email, 'email');
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
        $data=$query->selectOne('users', $uuid, 'uuid');
        if (!$data['active'] ||!$this->verifyPasswords($password, $data['password'])) {
            $isDataValid = false;
            return "*Invalid Password";
        }
    }
}