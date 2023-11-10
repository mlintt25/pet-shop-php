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

    // Lấy thông tin cơ bản của Pets
    public function handleGetDetail() {
        $baseServiceInfo = $this->db->table('services')
            ->select('name, slug, icon, dersc, content')
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
        endif;

        if (!$checkNull):
            $response = $baseServiceInfo;
        endif;

        return $response;
    }

 
}