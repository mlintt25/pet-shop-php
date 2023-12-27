<?php
class Cart extends Controller {
    private $cartModel;

    public function __construct() {
        $this->cartModel = $this->model('CartModel', 'admin');
    }

    // Lấy danh sách hoá đơn chưa duyệt
    public function getListBillPending() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];

            if (!empty($data['userId'])):
                $userId = $data['userId'];

                if (!empty($userId)):
                    $result = $this->cartModel->handleGetListBillPending($userId);

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

     // Lấy danh sách tất cả hoá đơn chưa duyệt
     public function getListAllBillPending() {
        $request = new Request();

        if ($request->isGet()):
            $response = [];

            $result = $this->cartModel->handleGetListAllBillPending();

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
    }

    // Duyệt các hoá đơn
    public function changeBillStatus() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];

            if (!empty($data['billId'])):
                $billId = $data['billId'];

                if (!empty($billId)):
                    $result = $this->cartModel->handleChangeBillStatus($billId);

                    if ($result):
                        $response = [
                            'status' => true,
                            'message' => 'Duyệt thành công',
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