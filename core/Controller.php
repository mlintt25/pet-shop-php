<?php
class Controller {
    public $db;

    public function model($model) {
        if (file_exists(_DIR_ROOT.'/app/models/'.$model.'.php')):
            require_once _DIR_ROOT.'/app/models/'.$model.'.php';
            if (class_exists($model)):
                $model = new $model();
                return $model;
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