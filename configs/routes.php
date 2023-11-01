<?php
$routes['default_controller'] = 'authcontroller';
// Đường dẫn ảo -> đường dẫn thật
$routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';

// Định tuyến API
$routes['api/login'] = 'auth/login';
$routes['api/pets/baseInfo'] = 'home/getPetBaseInfo';
$routes['api/pets/detailInfo'] = 'home/getPetDetailInfo';

?>