<?php
class AuthModel extends Model {

    public function tableFill()
    {
        return '';
    }

    public function fieldFill()
    {
        return '';
    }

    public function primaryKey()
    {
        return '';
    }

    // Xử lý login
    public function handleLogin($username, $password) {
        $dbValue = $this->db->table('users')->select('id, email, password, status')
            ->where('email', '=', $username)
            ->first();

        $response = [];
        
        if (!empty($dbValue)):
            $passwordHash = $dbValue['password'];
            $userId = $dbValue['id'];
            $statusAccount = $dbValue['status'];

            if (password_verify($password, $passwordHash)):
                $loginToken = sha1(uniqid().time());
                $dataToken = [
                    'user_id' => $userId,
                    'token' => $loginToken,
                    'create_at' => date('Y-m-d H:i:s')
                ];

                if ($statusAccount === 1):
                    $insertTokenStatus = $this->db->table('login_token')->insert($dataToken);
                    if ($insertTokenStatus):
                        // Lưu login token vào session
                        Session::data('login_token', $loginToken);
                        // Lưu thông tin người đăng nhập
                        $userData = $this->db->table('users')
                            ->select('id, fullname, thumbnail, email, 
                                dob, address, phone, password, about_content, 
                                contact_facebook, contact_twitter, contact_linkedin,
                                contact_pinterest, status, decentralization_id, 
                                last_activity, create_at')
                            ->where('id', '=', $userId)
                            ->first();
                        Session::data('user_data', $userData);

                        return true;
                    endif;
                endif;

                if ($statusAccount === 0):
                    $response = [
                        'message' => 'Vui lòng kích hoạt tài khoản tại Gmail bạn dùng để đăng ký tài khoản'
                    ];
                endif;

                if ($statusAccount === 2):
                    $response = [
                        'message' => 'Tài khoản của bạn đã tạm thời bị khoá. Vui lòng liên hệ quản trị viên để xử lý'
                    ];
                endif;

            endif;
        endif;

        if (!empty($response)):
            return $response;
        endif;


        return false;
    }

    // Xử lý register
    public function handleRegister() {
        $activeToken = sha1(uniqid().time());
        $dataInsert = [
            'fullname' => $_POST['fullname'],
            'email' => $_POST['email'],
            'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
            'active_token' => $activeToken,
            'decentralization_id' => 2,
            'create_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = $this->db->table('users')->insert($dataInsert);
        if ($insertStatus):
            // Tạo link active
            $linkActive = _WEB_ROOT.'/auth/active?token='.$activeToken;
            // Thiết lập mail
            $subject = ucwords($_POST['fullname']).' ơi. Bạn vui lòng kích hoạt tài khoản';
            $content = 'Chào bạn: '.ucwords($_POST['fullname']).'<br>';
            $content .= 'Vui lòng click vào link dưới đây để kích hoạt tài khoản của bạn: <br>';
            $content .= $linkActive.'<br>';
            $content .= 'Trân trọng!';

            $sendStatus = Mailer::sendMail($_POST['email'], $subject, $content);

            if ($sendStatus):
                return true;
            endif;
        endif;

        return false;
    }

    // Xử lý kích hoạt tài khoản
    public function handleActiveAccount($token) {
        if (!empty($token)):
            // Truy vấn sql để so sánh
            $tokenQuery = $this->db->table('users')
                ->select('id, fullname, email')
                ->where('active_token', '=', $token)
                ->first();

            if (!empty($tokenQuery)):
                $userId = $tokenQuery['id'];
                $dataUpdate = [
                    'status' => 1,
                    'active_token' => null
                ];
                $updateStatus = $this->db->table('users')->update($dataUpdate, "id = $userId");
                if ($updateStatus):
                    return true;
                endif;    
            endif;
        endif;

        return false;
    }

    // Xử lý đăng xuất
    public function handleLogout($userId) {
        $queryDelete = $this->db->table('login_token')
            ->where('user_id', '=', $userId)
            ->delete();
        
        if ($queryDelete):
            Session::delete('login_token');
            Session::delete('user_data');

            return true;
        endif;

        return false;
    }
}