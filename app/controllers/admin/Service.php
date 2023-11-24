<?php
class Service extends Controller {
    private $serviceModel;

    public function __construct() {
        $this->serviceModel = $this->model('ServiceModel', 'admin');
    }

    // Lấy thông tin chi tiết của dịch vụ
    public function getServiceDetailInfo() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->serviceModel->handleGetDetail(); // Gọi xử lý ở Model

            if (!empty($result)):
                $response = $result;
            else:
                $response = [
                    'message' => 'Đã có lỗi xảy ra'
                ];
            endif;

            echo json_encode($response);
        endif;
    }

    // Lấy thời gian làm việc
    public function getTimeWorking() {
        $request = new Request();

        if ($request->isPost()): // Kiểm tra post

            $data = $request->getFields();

            if (!empty($data['timeWorkingId'])):
                $timeWorkingId = $data['timeWorkingId'];
                
                $result = $this->serviceModel->handleGetTimeWorking($timeWorkingId); // Gọi xử lý ở Model

                if (!empty($result)):
                    $response = $result;
                else:
                    $response = [
                        'message' => 'Đã có lỗi xảy ra'
                    ];
                endif;
            endif;

            echo json_encode($response);
        endif;
    }


}