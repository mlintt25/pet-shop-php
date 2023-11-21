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

    
    // Update trạng thái account
    public function updateStatusAccount() {
        $request = new Request();

        if ($request->isPost()): // Kiểm tra get
            $data = $request->getFields();

            if (!empty($data['user_id'])):
                $userId = $data['user_id'];

                $result = $this->userModel->handleUpdateStatusAccount($userId); // Gọi xử lý ở Model

                if (!empty($result)):
                    $response = [
                        'message' => 'Thay đổi thành công',
                    ];
                else:
                    $response = [
                        'message' => 'Đã có lỗi xảy ra'
                    ];
                endif;
            else:
                $response = [
                    'message' => 'Đã có lỗi xảy ra'
                ];
            endif;

            echo json_encode($response);
           
        endif;
    }
}