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

    // Duyệt trạng thái đã thanh toán dịch vụ của người dùng 
    public function changeServicePaymentStatus() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();
            $response = [];

            if (!empty($data['userId']) && !empty($data['serviceId'])):
                $userId = $data['userId'];
                $serviceId = $data['serviceId'];

                if (!empty($userId) && !empty($serviceId)):
                    $result = $this->serviceModel->handleChangeServicePaymentStatus($userId, $serviceId);

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