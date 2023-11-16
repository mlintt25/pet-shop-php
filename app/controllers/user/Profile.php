<?php
class Profile extends Controller {
    private $profileModel;

    public function __construct() {
        $this->profileModel = $this->model('ProfileModel', 'user');
    }

    // Sửa thông tin
    public function updateInfo() {
        $request = new Request();

        if ($request->isPost()):
            $data = $request->getFields();

            if (!empty($data['user_id'])):
                $userId = $data['user_id'];
                
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

}