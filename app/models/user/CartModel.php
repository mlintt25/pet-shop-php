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
                ->where('productid', '=', $productId)
                ->update($dataUpdate);

            if ($updateQuantity):
                $productQuantity = $this->db->table('cart')
                    ->select('quantity')
                    ->where('userid', '=', $userId)
                    ->where('productid', '=', $productId)
                    ->first();

                $dataUpdate = [
                    'price' => $productQuantity['quantity'] * $productPrice['price']
                ];

                $updateStatus = $this->db->table('cart')
                    ->where('userid', '=', $userId)
                    ->where('productid', '=', $productId)
                    ->update($dataUpdate);

                if ($updateStatus):
                    return true;
                endif;
            endif;
        endif;

        return false;
    }

    // Xử lý thay đổi số lượng sản phẩm trong giỏ hàng
    public function handleUpdateQuantityInCart($userId, $productId) {
        $productPrice = $this->db->table('product')
            ->select('price')
            ->where('productid', '=', $productId)
            ->first();

        $dataUpdate = [ 
            'quantity' => $_POST['quantity'],
            'price' => $_POST['quantity'] * $productPrice['price']
        ];

        $updateStatus = $this->db->table('cart')
            ->where('userid', '=', $userId)
            ->where('productid', '=', $productId)
            ->update($dataUpdate);

        if ($updateStatus):
            return true;
        endif;

        return false;
    }   

    // Xử lý xoá sản phẩm khỏi giỏ hàng
    public function handleDeleteProductInCart($userId, $productId) {
        $queryCheck = $this->db->table('cart')
            ->select('id')
            ->where('userid', '=', $userId)
            ->where('productid', '=', $productId)
            ->first();

        if (!empty($queryCheck)):
            $deleteStatus = $this->db->table('cart')
                ->where('userid', '=', $userId)
                ->where('productid', '=', $productId)
                ->delete();

            if ($deleteStatus):
                return true;
            endif;
        endif;

        return false;
    }

    // Xử lý lấy danh sách sản phẩm trong giỏ hàng
    public function handleGetListProductInCart() {
        $queryGet = $this->db->table('cart')
            ->select('cart.quantity, cart.price, product.product_name')
            ->join('product', 'cart.productid = product.productid')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || $subItem === ''):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $queryGet;
        endif;

        return $response;
    }
 
}