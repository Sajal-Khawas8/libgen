<?php
class Book
{
    private $validation = '';

    public function __construct($valiadtionObj = '')
    {
        $this->validation = $valiadtionObj;
    }
    public function addBook($data)
    {
        $isDataValid = true;
        $err = [
            'titleErr' => $this->validation->validateUniqueName($data['title'], $isDataValid, 'Title'),
            'authorErr' => $this->validation->validateTextData($data['author'], $isDataValid, 'Name'),
            'categoryErr' => $this->validation->validateSelectBox($data['category_id'], $isDataValid, 'Category'),
            'rentErr' => $this->validation->validateNumber($data['rent'], $isDataValid, 'Rent'),
            'copiesErr' => $this->validation->validateNumber($data['copies'], $isDataValid, 'Copies'),
            'coverErr' => $this->validation->validatePictureFormat($_FILES['cover'], $isDataValid),
            'descriptionErr' => $this->validation->validateTextArea($data['description'], $isDataValid, 'Description')
        ];

        if ($isDataValid) {
            $file = new File($_FILES['cover']);
            $data['cover'] = $file->moveFile('books');
            $copies = $data['copies'];
            unset($data['copies']);
            $query = new DatabaseQuery();
            $query->add('books', $data);
            $bookId = $query->lastEntry('books', 'id');
            $quantity = [
                'book_id' => $bookId,
                'copies' => $copies,
                'available' => $copies
            ];
            $query->add('quantity', $quantity);
            header("Location: admin");
            exit;
        } else {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            header("Location: admin/books/addBook");
            exit;
        }
    }

    public function updateBook($data, $id)
    {
        $isDataValid = true;
        $err = [
            'titleErr' => $this->validation->validateUpdatedUniqueName($data['title'], $isDataValid, 'Title', $id),
            'authorErr' => $this->validation->validateUpdatedTextData($data['author'], $isDataValid, 'Name'),
            'categoryErr' => $this->validation->validateSelectBox($data['category_id'], $isDataValid, 'Category'),
            'rentErr' => $this->validation->validateUpdatedNumber($data['rent'], $isDataValid, 'Rent'),
            'copiesErr' => $this->validation->validateUpdatedNumber($data['copies'], $isDataValid, 'Copies'),
            'coverErr' => $this->validation->validateUpdatedPictureFormat($_FILES['cover'], $isDataValid),
            'descriptionErr' => $this->validation->validateUpdatedTextArea($data['description'], $isDataValid, 'Description')
        ];

        if ($isDataValid) {
            $updateStr = '';
            $copies = $data['copies'];
            unset($data['copies']);
            $file = new File($_FILES['cover']);
            if ($file->fileExist) {
                $data['cover'] = $file->moveFile('books');
                $query = new DatabaseQuery();
                $oldImage = $query->selectColumn('cover', 'books', $id, 'book_uuid');
                unlink("assets/uploads/images/books/$oldImage");
            }
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $updateStr .= $key . " = '" . $value . "', ";
                }
            }
            $query = new DatabaseQuery();
            $query->update('books', $updateStr, $id, 'book_uuid');
            $joins = [
                [
                    'table' => 'books',
                    'condition' => 'books.id = quantity.book_id'
                ]
            ];
            $quantity = $query->selectOneJoin('quantity', $joins, '*', $id, 'book_uuid');
            $updateStr = "copies = $copies, available = " . ($quantity['available'] + ($copies - $quantity['copies'])) . ", ";
            $query->update('quantity', $updateStr, $quantity['book_id'], 'book_id');
            return true;
        } else {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            return false;
        }
    }

    public function removeBook($id)
    {
        $query = new DatabaseQuery();
        $joins = [
            [
                'table' => 'books',
                'condition' => 'books.id = quantity.book_id'
            ]
        ];
        $bookData = $query->selectOneJoin('quantity', $joins, '*', $id, 'book_uuid');
        if ($bookData['copies'] !== $bookData['available']) {
            setcookie('deleteId', $id, time() + 5);
            setcookie('errBook', "This book can't be deleted at this time because " . ($bookData['copies'] - $bookData['available']) . " copies of this book are given on rent", time() + 5);
            return;
        }
        $query->delete('books', $id, 'book_uuid');
        $query->delete('quantity', $bookData['book_id'], 'book_id');
        unlink("assets/uploads/images/books/{$bookData['cover']}");
    }
}