<?php
class HomeModel extends Model {
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
        $basePetInfo = $this->db->table('pets')
            ->select('name, thumbnail, descr, other_name, origin, 
                classify, fur_style, fur_color, weight, longevity')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($basePetInfo)):
            foreach ($basePetInfo as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || empty($subItem)):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $basePetInfo;
        endif;


        return $response;
    }

 
}