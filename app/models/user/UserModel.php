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
    
    // Xử lý thay đổi thời gian sử dụng dịch vụ
    public function handleUpdatePeriodTime($data = []) {
        if (!empty($data['userId']) && !empty($data['serviceId'])):
            $userId = $data['userId'];
            $serviceId = $data['serviceId'];

            $checkId = $this->db->table('user_service')
                ->select('status')
                ->where('userid', '=', $userId)
                ->where('serviceid', '=', $serviceId)
                ->first();

            if (!empty($checkId)):
                $dataUpdate = [
                    'periodTime' => $data['periodTime'],
                    'updated_at' => date('Y-m-d H:i:s')
                ];
        
                $updateStatus = $this->db->table('user_service')
                    ->where('userid', '=', $userId)
                    ->where('serviceid', '=', $serviceId)
                    ->update($dataUpdate);
        
                if ($updateStatus):
                    return true;
                endif;
            endif;
        endif;
        
        return false;
    }

    
}