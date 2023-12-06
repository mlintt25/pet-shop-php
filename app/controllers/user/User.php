<?php
class User extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel', 'user');
    }

    // Đăng ký dịch vụ
    public function registerService() {
        $request = new Request();

        $data = $request->getFields();

        if (!empty($data['userId']) 
            && !empty($data['serviceId']) && !empty($data['periodTime'])):
            

            $dataRequest = [
                'userId' => $data['userId'], 
                'serviceId' => $data['serviceId'], 
                'periodTime' => $data['periodTime'],
            ];

            $result = $this->userModel->handleRegisterService($dataRequest);

            if ($result):
                $response = [
                    'message' => 'Đăng ký dịch vụ thành công'
                ];
            else:
                $response = [
                    'message' => 'Đăng ký dịch vụ thất bại'
                ];
            endif;

            echo json_encode($response);
        endif;
    }

    // Thay đổi thời gian sử dụng dịch vụ
    public function updatePeriodTime() {
        $request = new Request();

        $data = $request->getFields();

        if (!empty($data)):

            $result = $this->userModel->handleUpdatePeriodTime($data);

            if ($result):
                $response = [
                    'message' => 'Thay đổi thành công'
                ];
            else:
                $response = [
                    'message' => 'Thay đổi thất bại'
                ];
            endif;

            echo json_encode($response);
        endif;
    }
   

}