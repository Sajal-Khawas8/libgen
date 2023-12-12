<?php
class Category {
    private $validation = '';

    public function __construct($valiadtionObj = '') {
        $this->validation = $valiadtionObj;
    }
    public function addCategory($data) {
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateUniqueName($data['name'], $isDataValid, 'Name'),
            'priceErr' => $this->validation->validateNumber($data['base'], $isDataValid, 'Rent'),
            'rentErr' => $this->validation->validateNumber($data['additional'], $isDataValid, 'Rent'),
            'fineErr' => $this->validation->validateNumber($data['fine'], $isDataValid, 'Rent'),
        ];

        if($isDataValid) {
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

    public function updateCategory($data, $id) {
        $isDataValid = true;
        $err = [
            'nameErr' => $this->validation->validateUpdatedUniqueName($data['name'], $isDataValid, 'Name', $id),
            'priceErr' => $this->validation->validateUpdatedNumber($data['base'], $isDataValid, 'Rent'),
            'rentErr' => $this->validation->validateUpdatedNumber($data['additional'], $isDataValid, 'Rent'),
            'fineErr' => $this->validation->validateUpdatedNumber($data['fine'], $isDataValid, 'Rent'),
        ];

        if($isDataValid) {
            $updateStr = '';
            foreach($data as $key => $value) {
                if(!empty($value)) {
                    $updateStr .= $key." = '".$value."', ";
                }
            }
            $query = new DatabaseQuery();
            $query->update('category', $updateStr, $id, 'id');
            return true;
        } else {
            $_SESSION['refresh'] = true;
            setcookie('err', serialize($err), time() + 2);
            setcookie('data', serialize($data), time() + 2);
            return false;
        }
    }

    public function removeCategory($id) {
        $query = new DatabaseQuery();
        $query->delete('category', $id, 'id');
    }
}