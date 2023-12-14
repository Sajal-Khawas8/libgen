<?php

/**
 * This class contains method to work with files
 */
class File
{
    private $file;
    public $fileExist;

    /**
     * This constructor checks if the user has uploaded a file or not
     * 
     * @param mixed $file The file uploaded by user
     */
    public function __construct($file)
    {
        $this->fileExist = !empty($file['name']);
        $this->file = $file;
    }

    /**
     * This method renames the file and moves it to the specified directory inside the upload directory
     * 
     * @param string $location The name of the directory where the file needs to be moved
     * @return string Returns the new filename
     */
    public function moveFile(string $location): string
    {
        $fileExtension = strtolower(pathinfo($this->file['name'])['extension']);
        $newFileName = uniqid() . "." . $fileExtension;
        if (!move_uploaded_file($this->file['tmp_name'], "./assets/uploads/images/$location/$newFileName")) {
            die("Error uploading file");
        }
        return $newFileName;
    }
}