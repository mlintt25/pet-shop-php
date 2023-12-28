<?php
class Service extends Controller {
    private $serviceModel;

    public function __construct() {
        $this->serviceModel = $this->model('ServiceModel', 'admin');
    }

    // Lấy danh sách dịch vụ chưa thanh toán của khách hàng
    public function getListUnpaidService() {
        $request = new Request();

        if ($request->isGet()):
            $result = $this->serviceModel->handleGetListUnpaidService();

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
}