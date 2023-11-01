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
    public function handleGetBase() {
        $basePetInfo = $this->db->table('pets')
            ->select('name, thumbnail')
            ->get();

        $response = [];

        if (!empty($basePetInfo)):
            foreach ($basePetInfo as $item):
                if (!empty($item['name']) 
                        && !empty($item['thumbnail'])):
                    $response = $basePetInfo;
                endif;
            endforeach;
        endif;

        return $response;
    }

    // Lấy thông tin chi tiết của Pets
    public function handleGetDetail() {
        // $detailPetInfo = $this->db->table('pets')->get();

        // var_dump($detailPetInfo);

        // $response = [];

        // if (!empty($detailPetInfo)):
        //     foreach ($detailPetInfo as $key => $item):
        //         if (!empty($item[$key])):
        //             $response = $detailPetInfo;
        //         endif;
        //     endforeach;
        // endif;

        // return $response;
    }
}