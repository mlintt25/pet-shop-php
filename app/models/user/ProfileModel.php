<?php
class ProfileModel extends Model {
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

    public function handleUpdate($userId) {
        $checkId = $this->db->table('users')
            ->select('id')
            ->where('id', '=', $userId)
            ->first();
        
        if (!empty($checkId)):
            $dataUpdate = [
                'fullname' => $_POST['fullname'],
                'email' => $_POST['email'],
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
            
            if ($updateStatus):
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
 
}