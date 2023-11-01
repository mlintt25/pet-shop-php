<?php
class AuthModel extends Model {

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

    public function handleLogin($username, $password) {
        $dbValue = $this->db->table('users')->select('id, email, password')
            ->where('email', '=', $username)
            ->first();
        if (!empty($dbValue['password'])):
            $passwordHash = $dbValue['password'];
        endif;
        
        if ($dbValue && password_verify($password, $passwordHash)):
            return true;
        endif;

        return false;
    }
}