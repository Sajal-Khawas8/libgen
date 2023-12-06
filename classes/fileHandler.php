<?php

class File
{
    private $file;
    public $fileExist;

    public function __construct($file)
    {
        $this->fileExist = !empty($file['name']);
        $this->file=$file;
    }

    public function moveFile()
    {
        $fileExtension = strtolower(pathinfo($this->file['name'])['extension']);
        $newFileName = uniqid() . "." . $fileExtension;
        if (!move_uploaded_file($this->file['tmp_name'], "./assets/uploads/images/{$newFileName}")) {
            die("Error uploading file");
        }
        return $newFileName;
    }
}