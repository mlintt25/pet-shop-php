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

    // Lấy thông tin cơ bản của dịch vụ
    public function handleGetDetail() {
        $baseServiceInfo = $this->db->table('services')
            ->select('id, name, slug, icon, dersc, content')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($baseServiceInfo)):
            foreach ($baseServiceInfo as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || empty($subItem)):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        else:
            $response = [
                'message' => 'Chưa có người dùng đăng ký dịch vụ'
            ];
            $checkNull = true;
        endif;

        if (!$checkNull):
            $response = $baseServiceInfo;
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
 
}