<?php
class ProductModel extends Model {
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
    public function handleGetListProduct() {
        $queryGet = $this->db->table('product')
            ->get();

        $response = [];
        $checkNull = false;
        
        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || $subItem === ''):
                        if ($subKey !== 'promotionid' 
                                && $subKey !== 'evaluate_star'):
                            $checkNull = true;
                        endif;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $queryGet;
        endif;
       

        return $response;
    }

 
}