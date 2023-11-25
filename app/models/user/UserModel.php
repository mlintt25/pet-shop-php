<?php
class UserModel extends Model {
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

    // Xử lý đăng ký dịch vụ
    public function handleRegisterService($data = []) {
        $dataInsert = [
            'userid' => $data['userId'],
            'serviceid' => $data['serviceId'],
            'register_day' => date('Y-m-d'),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $insertStatus = $this->db->table('user_service')
            ->insert($dataInsert);

        if ($insertStatus):
            return true;
        endif;

        return false;
    }

    
}