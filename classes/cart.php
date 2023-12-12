<?php
class Cart
{
    public function addItem($itemId)
    {
        $query = new DatabaseQuery();
        $cartData = [
            'user_id' => $_COOKIE['user'],
            'book_id' => $itemId,
        ];
        $query->add('cart', $cartData);
    }

    public function removeItem($id)
    {
        $query = new DatabaseQuery();
        $query->delete('cart', $id);
    }
}
?>