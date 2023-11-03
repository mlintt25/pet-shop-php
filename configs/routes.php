<?php
$routes['default_controller'] = 'authcontroller';
// Đường dẫn ảo -> đường dẫn thật
$routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';

// Định tuyến API
$routes['api/login'] = 'auth/login'; // API login
$routes['api/pets/detailInfo'] = 'home/getPetDetailInfo'; // API thông tin chi tiết của Pets
$routes['api/services/detailInfo'] = 'service/getServiceDetailInfo'; // API thông tin chi tiết của Services
$routes['api/expert-team/detailInfo'] = 'expertteam/getExpertTeamInfo';
?>