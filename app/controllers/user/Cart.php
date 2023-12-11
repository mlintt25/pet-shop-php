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

    // Xoá sản phẩm ra khỏi giỏ hàng
    public function removeProductInCart() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];
            
            if (!empty($data['userId']) && !empty($data['productId'])):
                $userId = $data['userId'];
                $productId = $data['productId'];

                $result = $this->cartModel->handleDeleteProductInCart($userId, $productId);

                if ($result):
                    $response = [
                        'status' => true,
                        'message' => 'Xoá thành công'
                    ];
                else:
                    $response = [
                        'status' => false,
                        'message' => 'Xoá thất bại'
                    ];
                endif;

                echo json_encode($response);
            endif;
        endif;
    }

    // Lấy danh sách sản phẩm trong giỏ hàng
    public function getListProductInCart() {
        $request = new Request();
        $response = [];
        
        if ($request->isPost()):
            $data = $request->getFields();

            if (!empty($data['userId'])):
                $userId = $data['userId'];
            
                $result = $this->cartModel->handleGetListProductInCart($userId);

                if (!empty($result)):
                    $response = [
                        'status' => true,
                        'data' => $result
                    ];
                else:
                    $response = [
                        'status' => false,
                        'message' => 'Đã có lỗi xảy ra'
                    ];
                endif;
    
                echo json_encode($response);
            endif;
        endif;
    }

    public function countListProductInCart() {
        $request = new Request();
        $response = [];
        
        if ($request->isPost()):
            $data = $request->getFields();

            if (!empty($data['userId'])):
                $userId = $data['userId'];
            
                $result = $this->cartModel->handleCountListProductInCart($userId);

                if (is_numeric($result)):
                    $response = $result;
                else:
                    $response = [
                        'status' => false,
                    ];
                endif;
    
                echo json_encode($response);
            endif;
        endif;
    }

    // Tiến hành mua hàng
    public function checkOut() {
        $request = new Request();

        if ($request->isPost()):
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true); // Chuyển đổi JSON thành mảng PHP
            $response = [];

            if (!empty($data['userId'])):
                $userId = $data['userId'];

                $result = $this->cartModel->handleCheckOut($userId, $data['paymentProduct']);

                if (is_numeric($result)):
                    $response = [
                        'status' => true,
                        'billId' => $result
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

    // Thanh toán
    public function payment() {
        $request = new Request();

        if ($request->isPost()):
            $json_data = file_get_contents("php://input");
            $data = json_decode($json_data, true); // Chuyển đổi JSON thành mảng PHP
            $response = [];
            
            if (!empty($data['userId'])):
                $userId = $data['userId'];
                $billId = $data['billId'];
                $paymentMethod = $data['payment_method'];

                $result = $this->cartModel->handlePayment($userId, $data['paymentProduct'], $paymentMethod, $billId);

                if ($result):
                    $response = [
                        'status' => true,
                        'message' => 'Thanh toán thành công'
                    ];
                else:
                    $response = [
                        'status' => false,
                        'message' => 'Thanh toán thất bại'
                    ];
                endif;

                echo json_encode($response);
            endif;
        endif;
    }

    // Lấy thông tin billdetail
    public function getBillDetail() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];

            if (!empty($data['userId'])):
                $userId = $data['userId'];
                $billId = $data['billId'];

                if (!empty($userId)):
                    $result = $this->cartModel->handleGetBillDetail($userId, $billId);

                    if (!empty($result)):
                        $response = [
                            'status' => true,
                            'data' => $result
                        ];
                    else:
                        $response = [
                            'status' => false,
                            'message' => 'Đã có lỗi xảy ra'
                        ];
                    endif;

                    echo json_encode($response);
                endif;
            endif;
        endif;
    }

    // Xoá bill detail khi không thanh toán
    public function deleleBillDetail() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];

            if (!empty($data['userId'])):
                $userId = $data['userId'];
                $billId = $data['billId'];

                if (!empty($userId)):
                    $result = $this->cartModel->handleDeleleBillDetail($userId, $billId);

                    if ($result):
                        $response = [
                            'status' => true,
                        ];
                    else:
                        $response = [
                            'status' => false,
                            'message' => 'Đã có lỗi xảy ra'
                        ];
                    endif;

                    echo json_encode($response);
                endif;
            endif;
        endif;
    }

    // Lấy danh sách hoá đơn đã duyệt
    public function getListBillApproved() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];

            if (!empty($data['userId'])):
                $userId = $data['userId'];

                if (!empty($userId)):
                    $result = $this->cartModel->handleGetListBillApproved($userId);

                    if (!empty($result)):
                        $response = [
                            'status' => true,
                            'data' => $result
                        ];
                    else:
                        $response = [
                            'status' => false,
                            'message' => 'Đã có lỗi xảy ra'
                        ];
                    endif;

                    echo json_encode($response);
                endif;
            endif;
        endif;
    }

}