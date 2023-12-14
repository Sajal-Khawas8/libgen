<?php

/**
 * This class handles the operations related to the cart like adding books in the cart or deleting books from the cart
 */
class Cart
{
    /**
     * This method is responsible for adding books in the cart table
     * 
     * @param string $itemId The UUID of the book
     * @return void
     */
    public function addItem($itemId): void
    {
        $query = new DatabaseQuery();
        $cartData = [
            'user_id' => $_COOKIE['user'],
            'book_id' => $itemId,
        ];
        $query->add('cart', $cartData);
    }

    /**
     * This method is responsible for deleting books from the cart table
     * 
     * @param string $id The id from the cart table
     * @return void
     */
    public function removeItem($id): void
    {
        $query = new DatabaseQuery();
        $query->delete('cart', $id);
    }
}
?>