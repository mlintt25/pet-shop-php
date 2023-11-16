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

    //  Lấy danh sách người dùng 
    public function handleGetListUser() {
        $queryGet = $this->db->table('users')
            ->select('id, fullname, thumbnail, email, dob, address, phone, 
                about_content, contact_facebook, contact_twitter, contact_linkedin,
                contact_pinterest, status, create_at')
            ->where('decentralization_id', '=', '2')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || $subItem === ''):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $queryGet;
        endif;

        return $response;

    }

    // Lấy danh sách người dùng (nhân sự có thẩm quyền)
    public function handleGetListCompetentPersonnel() {
        $queryGet = $this->db->table('users')
            ->select('id, fullname, thumbnail, email, dob, address, phone, 
                about_content, contact_facebook, contact_twitter, contact_linkedin,
                contact_pinterest, status, create_at')
            ->where('decentralization_id', '=', '1')
            ->orWhere('decentralization_id', '=', '3')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($queryGet)):
            foreach ($queryGet as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || $subItem === ''):
                        $checkNull = true;
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