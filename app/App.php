<?php
class App {
    private $__controller, $__action, $__params, $__routes, $__db;
    public static $app;

    function __construct() {
        global $routes, $config;

        self::$app = $this;

        $this->__routes = new Route();

        if (!empty($routes['default_controller'])):
            $this->__controller = $routes['default_controller'];
        endif;
        
        $this->__action = 'index';
        $this->__params = [];

        if (class_exists('DB')):
            $dbObject = new DB();
            $this->__db = $dbObject->db;
        endif;

        $this->handleUrl();

    }

    // Lấy url
    private function getUrl(){
        if (!empty($_SERVER['PATH_INFO'])):
            $url = $_SERVER['PATH_INFO'];
        else:
            $url = '/';
        endif;

        return $url;
    }

    // Xử lý, phân tách url
    private function handleUrl() {
        $url = $this->getUrl();
        $url = $this->__routes->handleRoute($url);

        $urlArr = array_filter(explode('/', $url));
        $urlArr = array_values($urlArr);
        /* 
            Kiểm tra controller nằm trong nhiều folder
            home/admin/.../Dashboard
        */
        $urlCheck = '';
        if (!empty($urlArr)):
            foreach ($urlArr as $key => $item):
                $urlCheck .= $item.'/';
                $fileCheck = rtrim($urlCheck, '/');
                $fileArr = explode('/', $fileCheck);
                $fileArr[count($fileArr) - 1] = ucfirst($fileArr[count($fileArr) - 1]);
                $fileCheck = implode('/', $fileArr);
    
                if (!empty($urlArr[$key - 1])):
                    unset($urlArr[$key - 1]);
                endif;
    
                if (file_exists('app/controllers/'.($fileCheck).'.php')):
                   $urlCheck = $fileCheck;
                   break;
                endif;            
    
            endforeach;
        endif;
        
        $urlArr = array_values($urlArr);

        /* 
            home/detail/a/b/c.....
            controller: home arr[0]
            action: detail arr[1]
            params: a, b, c, .... 
        */
        // Xử lý controller
        if (!empty($urlArr[0])):
            $this->__controller = ucfirst($urlArr[0]);
        else:
            $this->__controller = ucfirst($this->__controller);
        endif;

        // Xử lý urlCheck rỗng
        if (empty($urlCheck)):
            $urlCheck = $this->__controller;
        endif;

        if (file_exists('app/controllers/'.($urlCheck).'.php')):
            require_once 'app/controllers/'.($urlCheck).'.php';
            
            // Kiểm tra class tồn tại
            if (class_exists($this->__controller)):
                $this->__controller = new $this->__controller();
                unset($urlArr[0]);

                if (!empty($this->__db)):
                    $this->__controller->db = $this->__db;
                endif;
            else:
                $this->loadError();
            endif;
        else:
            $this->loadError();
        endif;


        // Xử lý action
        if (!empty($urlArr[1])):
            $this->__action = ucfirst($urlArr[1]);
            unset($urlArr[1]);
        endif;

        //Xử lý params
        $this->__params = array_values($urlArr);

        //Kiểm tra method tồn tại
        if (method_exists($this->__controller, $this->__action)):
            // Phương thức gọi hàm và truyền mảng params cho hàm đó
            call_user_func_array([$this->__controller, $this->__action], $this->__params);
        else:
            $this->loadError();
        endif;

    }

    public function getCurrentController(){
        return $this->__controller;
    }

    public function loadError($name = '404', $data = []) {
        extract($data);
        require_once 'app/errors/'.$name.'.php';
    }


}