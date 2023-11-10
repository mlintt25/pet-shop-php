<?php
class Controller {
    public $db;

    public function model($model, $role = '') {
        if (!empty($role)):
            if ($role == 'admin' || $role == 'user'):
                if (file_exists(_DIR_ROOT.'/app/models/'.$role.'/'.$model.'.php')):
                    require_once _DIR_ROOT.'/app/models/'.$role.'/'.$model.'.php';
                    if (class_exists($model)):
                        $model = new $model();
                        return $model;
                    endif;
                endif;
            endif;
        else:
            if (file_exists(_DIR_ROOT.'/app/models/'.$model.'.php')):
                require_once _DIR_ROOT.'/app/models/'.$model.'.php';
                if (class_exists($model)):
                    $model = new $model();
                    return $model;
                endif;
            endif;
        endif;
        
        return false;
    }

    public function render($view, $data = []) {
        // Chuyển phần tử mảng thành biến
        extract($data); 
        /*
            'name' -> $name
            'title' -> $title 
        */
        if (file_exists(_DIR_ROOT.'/app/views/'.$view.'.php')):
            require_once _DIR_ROOT.'/app/views/'.$view.'.php';
        endif;
    }
}