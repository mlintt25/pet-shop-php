<?php
class CartModel extends Model {
    public function tableFill()
    {
        return '';
    }

    public function fieldFill()
    {
        return '';
    }

    public function primaryKey()
    {
        return '';
    }

    // Xử lý thêm sản phẩm vào giỏ hàng
    public function handleAddToCart($userId, $productId) {
        $checkEmpty = $this->db->table('cart')
            ->select('id, quantity')
            ->where('userid', '=', $userId)
            ->where('productid', '=', $productId)
            ->first();

        $productPrice = $this->db->table('product')
            ->select('price')
            ->where('productid', '=', $productId)
            ->first();

        if (empty($checkEmpty)):
            $dataInsert = [
                'productid' => $productId,
                'userid' => $userId,
                'quantity' => $_POST['quantity'],
                'price' => $_POST['quantity'] * $productPrice['price'],
            ];

            $insertStatus = $this->db->table('cart')
                ->insert($dataInsert);
            
            if ($insertStatus):
                return true;
            endif;
        else:
            $dataUpdate = [
                'quantity' => $checkEmpty['quantity'] + $_POST['quantity'],
            ];

            $updateQuantity = $this->db->table('cart')
                ->where('userid', '=', $userId)
                ->update($dataUpdate);

            if ($updateQuantity):
                $productQuantity = $this->db->table('cart')
                    ->select('quantity')
                    ->where('userid', '=', $userId)
                    ->first();

                $dataUpdate = [
                    'price' => $productQuantity['quantity'] * $productPrice['price']
                ];

                $updateStatus = $this->db->table('cart')
                    ->where('userid', '=', $userId)
                    ->update($dataUpdate);

                if ($updateStatus):
                    return true;
                endif;
            endif;
        endif;

        return false;
    }
 
}