<?php
require "./classes/fileHandler.php";
class User
{
    private $validation = '';

    public function __construct($valiadtionObj = '')
    {
        $this->validation = $valiadtionObj;
    }
    public function addUser($data)
    {
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateTextData($data['name'], $isDataValid, 'Name'),
            'emailErr' => $this->validation->validateEmail($data['email'], $isDataValid),
            'pictureErr' => $this->validation->validateUpdatedPictureFormat($_FILES['profilePicture'], $isDataValid),
            'addressErr' => $this->validation->validateTextArea($data['address'], $isDataValid, 'Address'),
            'passwordErr' => $this->validation->validatePasswordFormat($data['password'], $isDataValid),
            'cnfrmPasswordErr' => $this->validation->validateCnfrmPassword($data['confirmPassword'], $data['password'], $isDataValid),
        ];

        if ($isDataValid) {
            unset($data['confirmPassword']);
            $file = new File($_FILES['profilePicture']);
            if ($file->fileExist) {
                $data['image'] = $file->moveFile('users');
            }
            $query = new DatabaseQuery();
            $query->add('users', $data);
            return $query->lastEntry('users');
        } else {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            $location = isset($data['role']) ? 'admin/addMember' : '/signUp';
            header("Location: $location");
            exit;
        }
    }

    public function updateUser($data, $uuid)
    {
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateUpdatedTextData($data['name'], $isDataValid, 'Name'),
            'emailErr' => $this->validation->validateUpdatedEmail($data['email'], $isDataValid, $uuid),
            'pictureErr' => $this->validation->validateUpdatedPictureFormat($_FILES['profilePicture'], $isDataValid),
            'addressErr' => $this->validation->validateTextArea($data['address'], $isDataValid, 'Address'),
            'cnfrmPasswordErr' => $this->validation->validatePassword($data['oldPassword'], $uuid, $isDataValid),
            'passwordErr' => $this->validation->validatePasswordFormat($data['password'], $isDataValid),
        ];

        if ($isDataValid) {
            unset($data['oldPassword']);
            $file = new File($_FILES['profilePicture']);
            if ($file->fileExist) {
                $data['image'] = $file->moveFile('users');
                $query = new DatabaseQuery();
                $oldImage=$query->selectColumn('image', 'users', $uuid, 'uuid');
                unlink("assets/uploads/images/users/$oldImage");
            }
            $updateStr = '';
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $updateStr .= $key . " = '" . $value . "', ";
                }
            }
            $query = new DatabaseQuery();
            $query->update('users', $updateStr, $uuid, 'uuid');
            return true;
        } else {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            return false;
        }
    }

    public function removeUser($id)
    {
        $query = new DatabaseQuery();
        $query->update('users', 'active=false, ', $id, 'uuid');
    }
}