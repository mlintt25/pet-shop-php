<?php
class Home extends Controller {
    private $homeModel;

    public function __construct() {
        $this->homeModel = $this->model('HomeModel');
    }

    // Lấy thông tin cơ bản của Pets
    public function getPetBaseInfo() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->homeModel->handleGetBase(); // Gọi xử lý ở Model

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

    // Lấy thông tin chi tiết của Pets
    public function getPetDetailInfo() {
        // $request = new Request();

        // if ($request->isGet()): // Kiểm tra get
            
        //     $result = $this->homeModel->handleGetDetail(); // Gọi xử lý ở Model

        //     if (!empty($result)):
        //         $response = $result;
        //     else:
        //         $response = [
        //             'message' => 'Đã có lỗi xảy ra'
        //         ];
        //     endif;

        //     echo json_encode($response);
        // endif;
    }


}