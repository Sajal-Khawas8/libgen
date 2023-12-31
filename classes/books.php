<?php

/**
 * This class contains methods to work with books like adding books, updating books data and deleting the books
 */
class Book
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
     * Adds the data of book to the 'books' table
     * This method verifies the input data and adds the data to the table
     * 
     * @param array $data The data from the form
     * @return void
     */
    public function addBook(array $data): void
    {
        // Validate data
        $isDataValid = true;
        $err = [
            'titleErr' => $this->validation->validateUniqueName($data['title'], $isDataValid, 'Title'),
            'authorErr' => $this->validation->validateTextData($data['author'], $isDataValid, 'Name'),
            'categoryErr' => $this->validation->validateSelectBox($data['category_id'], $isDataValid, 'Category'),
            'rentErr' => $this->validation->validateNumber($data['rent'], $isDataValid, 'Rent'),
            'fineErr' => $this->validation->validateNumber($data['fine'], $isDataValid, 'Fine'),
            'copiesErr' => $this->validation->validateNumber($data['copies'], $isDataValid, 'Copies'),
            'coverErr' => $this->validation->validatePictureFormat($_FILES['cover'], $isDataValid),
            'descriptionErr' => $this->validation->validateTextArea($data['description'], $isDataValid, 'Description')
        ];

        // If data is valid, add it to table else set and display the error messages
        if (!$isDataValid) {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            header("Location: admin/books/addBook");
            exit;
        }

        // Upload the book cover image
        $file = new File($_FILES['cover']);
        $data['cover'] = $file->moveFile('books');
        $data['cover'] = "http://localhost/libgen/assets/uploads/images/books/" . $data['cover'];

        $copies = $data['copies'];
        unset($data['copies']);
        $query = new DatabaseQuery();

        // Add the book data to the table
        $query->add('books', $data);
        $bookId = $query->lastEntry('books', 'book_uuid');
        $quantity = [
            'book_id' => $bookId,
            'copies' => $copies,
            'available' => $copies
        ];

        // Add the number of books to the quantity table
        $query->add('quantity', $quantity);

        // Redirect to dashboard
        header("Location: admin/books");
        exit;
    }

    /**
     * Updates the data of book in the 'books' table
     * This method verifies the input data and updates the data to the table
     * 
     * @param array $data The data from the form
     * @param string $id UUID of the book
     * @return bool Returns true if the data is updated successfully and false otherwise
     */
    public function updateBook(array $data, string $id): bool
    {
        // Validate Data
        $isDataValid = true;
        $err = [
            'titleErr' => $this->validation->validateUpdatedUniqueName($data['title'], $isDataValid, 'Title', $id),
            'authorErr' => $this->validation->validateUpdatedTextData($data['author'], $isDataValid, 'Name'),
            'categoryErr' => $this->validation->validateSelectBox($data['category_id'], $isDataValid, 'Category'),
            'rentErr' => $this->validation->validateUpdatedNumber($data['rent'], $isDataValid, 'Rent'),
            'fineErr' => $this->validation->validateUpdatedNumber($data['fine'], $isDataValid, 'Fine'),
            'copiesErr' => $this->validation->validateUpdatedNumber($data['copies'], $isDataValid, 'Copies'),
            'coverErr' => $this->validation->validateUpdatedPictureFormat($_FILES['cover'], $isDataValid),
            'descriptionErr' => $this->validation->validateUpdatedTextArea($data['description'], $isDataValid, 'Description')
        ];

        // If data is valid, update it in the table else set and display error messages
        if (!$isDataValid) {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            return false;
        }

        $updateStr = '';
        $copies = $data['copies'];
        unset($data['copies']);

        // Update the cover picture
        $file = new File($_FILES['cover']);
        if ($file->fileExist) {
            $data['cover'] = $file->moveFile('books');
            $data['cover'] = "http://localhost/libgen/assets/uploads/images/books/" . $data['cover'];

            // Search and delete the old picture
            $query = new DatabaseQuery();
            $oldImage = $query->selectColumn('cover', 'books', $id, 'book_uuid');
            $oldImage = str_replace('http://localhost/libgen/', '', $oldImage);
            unlink($oldImage);
        }

        // Update the data
        foreach ($data as $key => $value) {
            if (!empty($value)) {
                $updateStr .= $key . " = '" . $value . "', ";
            }
        }
        $updateStr = rtrim($updateStr, ", ");
        $query = new DatabaseQuery();
        $query->update('books', $updateStr, $id, 'book_uuid');
        $joins = [
            [
                'table' => 'books',
                'condition' => 'books.book_uuid = quantity.book_id'
            ]
        ];
        $quantity = $query->selectOneJoin('quantity', $joins, '*', $id, 'book_uuid');
        $copies = $copies ? $copies : $quantity['copies'];
        $updateStr = "copies = $copies, available = " . ($quantity['available'] + ($copies - $quantity['copies']));
        $query->update('quantity', $updateStr, $id, 'book_id');
        return true;
    }

    /**
     * Delete the data of book from the 'books' table
     * 
     * @param string $id UUID of the book
     * @return void
     */
    public function removeBook(string $id): void
    {
        // Retrieve the book data
        $query = new DatabaseQuery();
        $joins = [
            [
                'table' => 'books',
                'condition' => 'books.book_uuid = quantity.book_id'
            ]
        ];
        $bookData = $query->selectOneJoin('quantity', $joins, '*', $id, 'book_uuid');

        // If the total number of the books is not equal to the books available, then display the message
        if ($bookData['copies'] !== $bookData['available']) {
            setcookie('deleteId', $id, time() + 1);
            setcookie('errBook', "This book can't be deleted at this time because " . ($bookData['copies'] - $bookData['available']) . " copies of this book are given on rent", time() + 5);
            return;
        }

        // Delete the book from the books table
        $query->update('books', 'active=0', $id, 'book_uuid');

        // Delete the book from the carts
        $query->delete('cart', $id, 'book_id');
    }
}