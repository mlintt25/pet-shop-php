<?php
class Auth extends Controller {
    private $authModel;

    public function __construct() {
        $this->authModel = $this->model('AuthModel');

    }

    public function login() {
        $request = new Request();

        if ($request->isPost()): // Kiểm tra post
            $data = file_get_contents("php://input");
            $data = json_decode($data, true); // None -> object | True -> array

            if (!empty($data)):
                if (!empty($data['username']) && !empty($data['password'])):
                    $username = $data['username'];
                    $password = $data['password'];
    
                    $result = $this->authModel->handleLogin($username, $password);
                    if ($result):
                        $response = [
                            'message' => 'Đăng nhập thành công'
                        ];
                    else:
                        $response = [
                            'message' => 'Đăng nhập thất bại'
                        ];
                    endif;
                    
                    echo json_encode($response);

                endif;
            endif;

        endif;
    }
}