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

        if (!empty($basePetInfo)):
            foreach ($basePetInfo as $key => $item):
                if (!empty($basePetInfo[$key])):
                    $response = $basePetInfo;
                endif;
            endforeach;
        endif;

        return $response;
    }

 
}