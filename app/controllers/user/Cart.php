<?php
class Cart extends Controller {
    private $cartModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel', 'user');
    }

    // Thêm sản phẩm vào giỏ hàng
    public function addToCart() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];
            
            if (!empty($data['userId']) && !empty($data['productId'])):
                $userId = $data['userId'];
                $productId = $data['productId'];

                $result = $this->cartModel->handleAddToCart($userId, $productId);

                if ($result):
                    $response = [
                        'status' => true,
                        'message' => 'Đã thêm vào giỏ hàng'
                    ];
                else:
                    $response = [
                        'status' => false,
                        'message' => 'Thêm vào giỏ hàng thất bại'
                    ];
                endif;

                echo json_encode($response);
            endif;
        endif;
    }

    // Update số lượng trong giỏ hàng
    public function updateQuantityInCart() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];
            
            if (!empty($data['userId']) && !empty($data['productId'])):
                $userId = $data['userId'];
                $productId = $data['productId'];

                $result = $this->cartModel->handleUpdateQuantityInCart($userId, $productId);

                if ($result):
                    $response = [
                        'status' => true,
                    ];
                else:
                    $response = [
                        'status' => false,
                    ];
                endif;

                echo json_encode($response);
            endif;
        endif;
    }
}