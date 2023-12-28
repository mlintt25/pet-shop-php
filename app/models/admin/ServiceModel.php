<?php
class ServiceModel extends Model {
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

    // Lấy thông tin chi tiết của dịch vụ
    public function handleGetDetail() {
        $queryGet = $this->db->table('services')
            ->select('services.*, staff_position.name as staff_position_name')
            ->join('staff_position', 'staff_position.position_id = services.teamid')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || empty($subItem)):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        else:
            $response = [
                'message' => 'Chưa có dịch vụ'
            ];
            $checkNull = true;
        endif;

        if (!$checkNull):
            $response = $queryGet;
        endif;

        return $response;
    }

     // Xử lý lấy thời gian làm việc
     public function handleGetTimeWorking($timeWorkingId) {
        $queryGet = $this->db->table('timeworking')
            ->where('id', '=', $timeWorkingId)
            ->first();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                if ($item === NULL || $item === ''):
                    $checkNull = true;
                endif;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $queryGet;
        endif;

        return $response;
    }

    // Xử lý lấy danh sách dịch vụ chưa thanh toán của user
    public function handleGetListUnpaidService() {
        $queryGet = $this->db->table('user_service')
            ->select('user_service.serviceid, user_service.userid, user_service.status, 
                user_service.register_day, user_service.payment_status, user_service.created_at,
                timeworking.timeworking')
            ->join('timeworking', 'timeworking.id = user_service.periodTime')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                if ($item === NULL || $item === ''):
                    $checkNull = true;
                endif;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $queryGet;
        endif;

        return $response;
    }

    // Xử lý duyệt trạng thái đã thanh toán dịch vụ của người dùng 
    public function handleChangeServicePaymentStatus($userId, $serviceId) {
        $queryCheck = $this->db->table('user_service')
            ->select('payment_status')
            ->where('userid', '=', $userId)
            ->where('serviceid', '=', $serviceId)
            ->first();

        if (!empty($queryCheck)):
            $dataUpdate = [
                'payment_status' => 1,
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

        return false;
    }
 
}