<?php
class ProfileModel extends Model
{
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

    public function handleUpdate($userId)
    {
        $checkId = $this->db->table('users')
            ->select('id')
            ->where('id', '=', $userId)
            ->first();

        if (!empty($checkId)) :
            $dataUpdate = [
                'fullname' => $_POST['fullname'],
                'dob' => $_POST['dob'],
                'address' => $_POST['address'],
                'phone' => $_POST['phone'],
                'about_content' => $_POST['about_content'],
                'contact_facebook' => $_POST['contact_facebook'],
                'contact_twitter' => $_POST['contact_twitter'],
                'contact_linkedin' => $_POST['contact_linkedin'],
                'contact_pinterest' => $_POST['contact_pinterest'],
                'update_at' => date('Y-m-d H:i:s')
            ];

            $updateStatus = $this->db->table('users')
                ->where('id', '=', $userId)
                ->update($dataUpdate);

            if ($updateStatus) :
                // Xoá session cũ
                Session::delete('user_data');

                $userData = $this->db->table('users')
                    ->select('id, fullname, thumbnail, email, 
                            dob, address, phone, password, about_content, 
                            contact_facebook, contact_twitter, contact_linkedin,
                            contact_pinterest, status, decentralization_id, 
                            last_activity, create_at')
                    ->where('id', '=', $userId)
                    ->first();
                // Update lại session
                Session::data('user_data', $userData);

                return true;
            endif;
        endif;

        return false;
    }

    // Xử lý lấy danh sách dịch vụ đã đăng ký
    public function handleGetService($userId)
    {
        $queryGet = $this->db->table('user_service')
            ->select('services.*, user_service.*, timeworking.*')
            ->join('users', 'users.id = user_service.userid')
            ->join('services', 'services.id = user_service.serviceid')
            ->join('timeworking', 'user_service.periodTime = timeworking.id')
            ->where('users.id', '=', $userId)
            ->where('(user_service.status', '=', '1')
            ->orWhere('user_service.status', '=', '0)')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)) :
            foreach ($queryGet as $key => $item) :
                foreach ($item as $subKey => $subItem) :
                    if ($subItem === NULL || $subItem === '') :
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull) :
            for ($i = 0; $i < count($queryGet); $i++):
                if ($queryGet[$i]['periodTime'] == 1):
                    $time_str = '11:00:00';
                    $queryGet[$i]['time'] = strtotime($time_str) - strtotime('TODAY');
                else:
                    $time_str = '17:00:00';
                    $queryGet[$i]['time'] = strtotime($time_str) - strtotime('TODAY');
                endif;
            endfor;
           
            $response = $queryGet;
        endif;

        return $response;
    }

    // Xử lý xoá dịch vụ đã đăng ký
    public function handleDeleteService($userId, $serviceId)
    {
        $checkId = $this->db->table('user_service')
            ->select('userid')
            ->where('userid', '=', $userId)
            ->where('serviceid', '=', $serviceId)
            ->first();

        if (!empty($checkId)) :
            $deleteStatus = $this->db->table('user_service')
                ->where('userid', '=', $userId)
                ->where('serviceid', '=', $serviceId)
                ->delete();

            if ($deleteStatus) :
                return true;
            endif;
        endif;

        return false;
    }
}