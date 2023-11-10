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

        if (!empty($baseServiceInfo)):
            foreach ($baseServiceInfo as $key => $item):
                if (!empty($baseServiceInfo[$key])):
                    $response = $baseServiceInfo;
                endif;
            endforeach;
        endif;

        return $response;
    }

 
}