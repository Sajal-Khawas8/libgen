<?php
require "./classes/fileHandler.php";

/**
 * This class contains methods to work with users like adding users, updating user data and deleting the users
 */
class User
{
    private $validation = '';

    /**
     * Initializes the validation class to validate the input data. Defaults to an empty string
     * 
     * @param ValidateData|string $validationObj
     * @return void
     */
    public function __construct(ValidateData|string $valiadtionObj = '')
    {
        $this->validation = $valiadtionObj;
    }

    /**
     * Adds the data of user to the 'users' table
     * This method verifies the input data and adds the data to the table
     * 
     * @param array $data The data from the form
     * @return mixed Returns the uuid of the newly created user or void if the user is not created
     */
    public function addUser(array $data): mixed
    {
        // Validate Data
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateTextData($data['name'], $isDataValid, 'Name'),
            'emailErr' => $this->validation->validateEmail($data['email'], $isDataValid),
            'pictureErr' => $this->validation->validateUpdatedPictureFormat($_FILES['profilePicture'], $isDataValid),
            'addressErr' => $this->validation->validateTextArea($data['address'], $isDataValid, 'Address'),
            'passwordErr' => $this->validation->validatePasswordFormat($data['password'], $isDataValid),
            'cnfrmPasswordErr' => $this->validation->validateCnfrmPassword($data['confirmPassword'], $data['password'], $isDataValid),
        ];

        // If data is valid, add it to table else set and display the error messages
        if ($isDataValid) {
            unset($data['confirmPassword']);

            // Upload profile picture
            $file = new File($_FILES['profilePicture']);
            if ($file->fileExist) {
                $data['image'] = $file->moveFile('users');
                $data['image']="http://localhost/libgen/assets/uploads/images/users/" . $data['image'];
            }

            // Add data to table
            $query = new DatabaseQuery();
            $query->add('users', $data);
            return $query->lastEntry('users');
        }
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($data), time() + 2);
        $location = isset($data['role']) ? 'admin/addMember' : '/signUp';
        header("Location: $location");
        exit;
    }

    /**
     * Updates the data of user in the 'users' table
     * This method verifies the input data and updates the data to the table
     * 
     * @param array $data The data from the form
     * @param string $uuid UUID of the user
     * @return bool Returns true if the data is updated successfully and false otherwise
     */
    public function updateUser(array $data, string $uuid): bool
    {
        // Fetch email of the user
        $query = new DatabaseQuery();
        $email = $query->selectColumn('email', 'users', $uuid, 'uuid');

        // Validate Data
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateUpdatedTextData($data['name'], $isDataValid, 'Name'),
            'emailErr' => $this->validation->validateUpdatedEmail($data['email'], $isDataValid, $uuid),
            'pictureErr' => $this->validation->validateUpdatedPictureFormat($_FILES['profilePicture'], $isDataValid),
            'addressErr' => $this->validation->validateTextArea($data['address'], $isDataValid, 'Address'),
            'cnfrmPasswordErr' => $this->validation->validatePassword($data['oldPassword'], $email, $isDataValid),
            'passwordErr' => $this->validation->validatePasswordFormat($data['password'], $isDataValid),
        ];

        // If data is valid, update it in the table else set and display error messages
        if ($isDataValid) {
            unset($data['oldPassword']);

            // Delete the old image and upload the new image
            $file = new File($_FILES['profilePicture']);
            if ($file->fileExist) {
                $data['image'] = $file->moveFile('users');
                $data['image'] = "http://localhost/libgen/assets/uploads/images/users/" . $data['image'];
                $query = new DatabaseQuery();
                $oldImage = $query->selectColumn('image', 'users', $uuid, 'uuid');
                $oldImage= str_replace('http://localhost/libgen/', '', $oldImage);
                unlink($oldImage);
            }

            // update data
            $updateStr = '';
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $updateStr .= $key . " = '" . $value . "', ";
                }
            }
            $query = new DatabaseQuery();
            $query->update('users', $updateStr, $uuid, 'uuid');
            return true;
        }
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($data), time() + 2);
        return false;
    }

    /**
     * Delete (Block) user
     * 
     * @param string $id UUID of the user
     * @return void
     */
    public function removeUser(string $id): void
    {
        $query = new DatabaseQuery();
        $rentedBooks = count($query->selectAllSpecific('orders', $id, 'user_id'));
        if ($_SESSION['user'][1]==='1' && $rentedBooks) {
            setcookie('error', true, time() + 2);
            setcookie('message', "This account can't be deleted as you have taken $rentedBooks books on rent. Please login to your account.", time() + 2);
            return;
        }
        $query->update('users', 'active=false, ', $id, 'uuid');
    }
}