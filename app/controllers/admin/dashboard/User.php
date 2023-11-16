<?php
class User extends Controller {
    private $userModel;

    public function __construct() {
        $this->userModel = $this->model('UserModel', 'admin');
    }

    // Lấy danh sách bài viết 
    public function getListUser() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->userModel->handleGetListUser(); // Gọi xử lý ở Model

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

    // Lấy danh sách người chức năng
    public function getListCompetentPersonnel() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->userModel->handleGetListCompetentPersonnel(); // Gọi xử lý ở Model

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
}