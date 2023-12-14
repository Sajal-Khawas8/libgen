<?php

/**
 * This class contains methods to work with categories like adding categories, updating category data and deleting the categories
 */
class Category
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
     * Adds the data of category to the 'category' table
     * This method verifies the input data and adds the data to the table
     * 
     * @param array $data The data from the form
     * @return void
     */
    public function addCategory(array $data): void
    {
        // Validate Data
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateUniqueName($data['name'], $isDataValid, 'Name'),
            'priceErr' => $this->validation->validateNumber($data['base'], $isDataValid, 'Rent'),
            'rentErr' => $this->validation->validateNumber($data['additional'], $isDataValid, 'Rent'),
            'fineErr' => $this->validation->validateNumber($data['fine'], $isDataValid, 'Rent'),
        ];

        // If data is valid, add it to table else set and display the error messages
        if ($isDataValid) {
            $query = new DatabaseQuery();
            $query->add('category', $data);
            header("Location: admin/categories");
            exit;
        } else {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            header("Location: admin/categories/addCategory");
            exit;
        }
    }

    /**
     * Updates the data of category in the 'category' table
     * This method verifies the input data and updates the data to the table
     * 
     * @param array $data The data from the form
     * @param int $id ID of the category
     * @return bool Returns true if the data is updated successfully and false otherwise
     */
    public function updateCategory(array $data, int $id): bool
    {
        // Validate Data
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateUpdatedUniqueName($data['name'], $isDataValid, 'Name', $id),
            'priceErr' => $this->validation->validateUpdatedNumber($data['base'], $isDataValid, 'Rent'),
            'rentErr' => $this->validation->validateUpdatedNumber($data['additional'], $isDataValid, 'Rent'),
            'fineErr' => $this->validation->validateUpdatedNumber($data['fine'], $isDataValid, 'Rent'),
        ];

        // If data is valid, update it in the table else set and display error messages
        if ($isDataValid) {
            $updateStr = '';
            foreach ($data as $key => $value) {
                if (!empty($value)) {
                    $updateStr .= $key . " = '" . $value . "', ";
                }
            }
            $query = new DatabaseQuery();
            $query->update('category', $updateStr, $id, 'id');
            return true;
        }
        $_SESSION['refresh'] = true;
        setcookie('err', serialize($err), time() + 2);
        setcookie('data', serialize($data), time() + 2);
        return false;
    }

    /**
     * Delete the data of category from the 'category' table
     * 
     * @param int $id ID of the book
     * @return void
     */
    public function removeCategory(int $id): void
    {
        $query = new DatabaseQuery();

        // Check if the category has books or not
        // If it has books, display error message else delete the category
        $books = $query->selectAllSpecific('books', $id, 'category_id');
        if (count($books)) {
            setcookie('deleteId', $id, time() + 1);
            setcookie('errCategory', "This category can't be deleted at this time because this category contains " . count($books) . " books.", time() + 5);
            return;
        }
        $query->delete('category', $id, 'id');
    }
}