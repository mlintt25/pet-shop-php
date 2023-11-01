<?php
class Session{
    /*
     * data(key, value) => set session
     * data(key) => get session
     * */
    static public function data($key = '', $value = '') {
        $sessionKey = self::isInvalid();

        if (!empty($value)):
            if (!empty($key)):
                $_SESSION[$sessionKey][$key] = $value; //set session
                return true;
            endif;
            return false;

        else:
            if (empty($key)):
                if (isset($_SESSION[$sessionKey])):
                    return $_SESSION[$sessionKey];
                endif;
            else:
                if (isset($_SESSION[$sessionKey][$key])):
                    return $_SESSION[$sessionKey][$key]; //get session
                endif;
            endif;
        endif;
    }

    /*
     * delete(key) => Xoá session với key
     * delete() => Xoá hết session
     * */
    static public function delete($key = '') {
        $sessionKey = self::isInvalid();
        if (!empty($key)):
            if (isset($_SESSION[$sessionKey][$key])):
                unset($_SESSION[$sessionKey][$key]);
                return true;
            endif;
            return false;
        else:
            unset($_SESSION[$sessionKey]);
            return true;
        endif;
        return false;
    }

    /*
     * Flash Data
     * - set flash data => giống như set session
     * - get flash data => giống như get session, xoá luôn session sau khi get
     *
     * */
    static public function flash($key='', $value='') {
        $dataFlash = self::data($key, $value);
        if (empty($value)):
            self::delete($key);
        endif;
        return $dataFlash;
    }

    static public function showErrors($message) {
        $data = ['message'=>$message];
        App::$app->loadError('exception', $data);
        die();
    }

    static function isInvalid() {

        global $config;

        if (!empty($config['session'])):
            $sessionConfig = $config['session'];
            if (!empty($sessionConfig['session_key'])):
                $sessionKey = $sessionConfig['session_key'];
                return $sessionKey;
            else:
                self::showErrors('Thiếu cấu hình session_key. Vui lòng kiểm tra file: configs/session.php');
            endif;
        else:
            self::showErrors('Thiếu cấu hình session. Vui lòng kiểm tra file: configs/session.php');
        endif;
    }
}