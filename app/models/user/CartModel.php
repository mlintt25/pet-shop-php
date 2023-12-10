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
    public function handleGetListProductInCart($userId) {
        $queryGet = $this->db->table('cart')
            ->select('cart.quantity, cart.price, product.product_name')
            ->join('product', 'cart.productid = product.productid')
            ->join('users', 'cart.userid = users.id')
            ->where('cart.userid', '=', $userId)
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

    public function handleCountListProductInCart($userId) {
        return count($this->handleGetListProductInCart($userId));
    }

    // Xử lý tiến hành mua hàng
    public function handleCheckOut($userId, $data) {
        $queryCheck = $this->db->table('cart')
            ->select('id')
            ->where('userid', '=', $userId)
            ->first();

        if (!empty($queryCheck)):
            $dataInsert = [
                'userid' => $userId,
                'total_price' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];

            $insertBillData = $this->db->table('bill')
                ->insert($dataInsert);

            if ($insertBillData):
                $billId = $this->db->lastId();
                Session::data('bill_id', $billId);

                if (!empty($data)):
                    unset($data[0]);
                    $data = array_values($data);

                    foreach ($data as $item):
                        $dataInsertBillDetail = [
                            'productid' => $item['id'],
                            'price' => $item['intoMoney'],
                            'quantity' => $item['quantity'],
                            'billid' => $billId,
                            'created_at' => date('Y-m-d H:i:s')
                        ];

                        $insertStatus = $this->db->table('billdetail')->insert($dataInsertBillDetail);
                    endforeach;

                    if ($insertStatus):
                        return true;
                    endif;
                endif;
            endif;
        endif;

        return false;
    }

    // Xử lý thanh toán 
    public function handlePayment($userId, $data, $paymentMethod) {
        $billId = Session::data('bill_id');

        $queryGetBillDetail = $this->db->table('billdetail')
            ->select('billdetail.billid, billdetail.quantity, billdetail.price')
            ->join('bill', 'billdetail.billid = bill.billid')
            ->where('bill.userid', '=', $userId)
            ->where('billdetail.billid', '=', $billId)
            ->get();
                        
        if (!empty($queryGetBillDetail)):
            foreach ($queryGetBillDetail as $item):
                $queryGetBill = $this->db->table('bill')
                    ->select('total_price')
                    ->where('billid', '=', $billId)
                    ->first();
                
                if (!empty($queryGetBill)):
                    $dataUpdateBill = [
                        'total_price' => $queryGetBill['total_price'] + $item['quantity'] * $item['price'],
                        'payment_method' => $paymentMethod,
                    ];

                    $updateStatus = $this->db->table('bill')
                        ->where('billid', '=', $billId)
                        ->update($dataUpdateBill);

                    $this->db->resetQuery();
                endif;
            endforeach;

            if ($updateStatus):
                $deleteAfterPayment = $this->handleDeleteAfterPayment($userId, $data);
                if ($deleteAfterPayment):
                    return true;
                endif;
            endif;
        endif;

        return false;
    }
 
    // Xoá sau khi thanh toán billdetail - cart
    public function handleDeleteAfterPayment($userId, $data) {
        foreach ($data as $item):
            $deleteCart = $this->db->table('cart')
                ->where('productid', '=', $item['id'])
                ->where('userid', '=', $userId)
                ->delete();

            $this->db->resetQuery();
        endforeach;

        if ($deleteCart):
            Session::delete('bill_id');
            return true;
        endif;

        return false;
    }

    public function handleGetBillDetail($userId) {
        $billId = Session::data('bill_id');

        $queryGet = $this->db->table('billdetail')
            ->join('bill', 'bill.billid = billdetail.billid')    
            ->where('bill.userid', '=', $userId)
            ->where('billdetail.billid', '=', $billId)
            ->get();

        $response = [];

        if (!empty($queryGet)):
            $response = $queryGet;
        endif;

        return $response;
    }

    // Xử lý xoá bill và billdetail khi unpayment
    public function handleDeleleBillDetail($userId) {
        $billId = Session::data('bill_id');

        $queryGet = $this->db->table('billdetail')
            ->join('bill', 'bill.billid = billdetail.billid')    
            ->where('bill.userid', '=', $userId)
            ->where('billdetail.billid', '=', $billId)
            ->get();

        if (!empty($queryGet)):
            $deleteBillDetail = $this->db->table('billdetail')  
                ->where('billid', '=', $billId)
                ->delete();
            
            $this->db->resetQuery();

            if ($deleteBillDetail):
                $deleteBill = $this->db->table('bill')  
                    ->where('billid', '=', $billId)
                    ->delete();
                
                if ($deleteBill):
                    Session::delete('bill_id');
                    return true;
                endif;
            endif;
        endif;

        return false;
    }
}