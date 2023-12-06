<?php
class Profile extends Controller {
    private $profileModel;

    public function __construct() {
        $this->profileModel = $this->model('ProfileModel', 'user');
    }

    // Lấy danh sách dịch vụ
    public function getService() {
        $request = new Request();

        if ($request->isPost()): // Kiểm tra get

            $data = $request->getFields();

            if (!empty($data['userId'])):
                $userId = $data['userId'];

                if (!empty($userId)):
                    $result = $this->profileModel->handleGetService($userId); // Gọi xử lý ở Model
                endif;
                
                if (!empty($result)):
                    $response = $result;
                else:
                    $response = [
                        'message' => 'Đã có lỗi xảy ra'
                    ];
                endif;
                
                echo json_encode($response);
            endif;
        endif;
    }

    
    // Sửa thông tin
    public function updateInfo() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();

            if (!empty($data['userId'])):
                $userId = $data['userId'];

                $request->rules([
                    'fullname' => 'required|min:5',
                    'phone' => 'phone'
                ]);

                $request->message([
                    'fullname.required' => 'Họ tên không được để trống',
                    'fullname.min' => 'Họ tên phải lớn hơn 4 ký tự',
                    'phone.phone' => 'Số điện thoại không hợp lệ'
                ]);
    
                $validate = $request->validate();
                if ($validate):
                    $result = $this->profileModel->handleUpdate($userId);

                    if ($result):
                        $response = [
                            'message' => 'Thay đổi thành công',
                            'user_data' => Session::data('user_data')
                        ];
                    else:
                        $response = $request->errors();
                    endif;
                else:
                    $response = $request->errors();
                endif;

                echo json_encode($response);
            endif;
        endif;
    }

    // Huỷ dịch vụ đã đăng ký
    public function deleteService() {
        $request = new Request();

        if ($request->isPost()):

            $data = $request->getFields();
            $response = [];

            if (!empty($data['userId']) && !empty($data['serviceId'])):
                $userId = $data['userId'];
                $serviceId = $data['serviceId'];
    
                $result = $this->profileModel->handleDeleteService($userId, $serviceId);
    
                if ($result):
                    $response = [
                        'status' => true,
                        'message' => 'Huỷ dịch vụ thành công'
                    ];
                else:
                    $response = [
                        'status' => false,
                        'message' => 'Huỷ dịch vụ thất bại'
                    ];
                endif;
    
                echo json_encode($response);
            endif;
        endif;
    }

}