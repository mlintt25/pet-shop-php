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
                $oldEmail =  Session::data('user_data')['email'];
                
                if ($oldEmail === $data['email']):
                    $request->rules([
                        'fullname' => 'required|min:5',
                        'email' => 'required|email|min:11',
                        'phone' => 'phone'
                    ]);
                else:
                    $request->rules([
                        'fullname' => 'required|min:5',
                        'email' => 'required|email|min:11|unique:users:email',
                        'phone' => 'phone'
                    ]);
                endif;

                $request->message([
                    'fullname.required' => 'Họ tên không được để trống',
                    'fullname.min' => 'Họ tên phải lớn hơn 4 ký tự',
                    'email.required' => 'Email không được để trống',
                    'email.email' => 'Định dạng email không hợp lệ',
                    'email.min' => 'Email phải lớn hơn 11 ký tự',
                    'email.unique' => 'Email đã tồn tại',
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