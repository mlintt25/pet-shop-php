<?php
$routes['default_controller'] = 'authcontroller';
// Đường dẫn ảo -> đường dẫn thật
$routes['tin-tuc/.+-(\d+).html'] = 'news/category/$1';

// Định tuyến API
$routes['api/login'] = 'auth/login'; // API đăng nhập
$routes['api/register'] = 'auth/register'; // API đăng ký
$routes['api/logout'] = 'auth/logout'; // API đăng xuất


// Admin API
$routes['api/pets/detailInfo'] = 'admin/home/getPetDetailInfo'; // API thông tin chi tiết của Pets
$routes['api/services/detailInfo'] = 'admin/service/getServiceDetailInfo'; // API thông tin chi tiết của Services
$routes['api/services/getTimeWorking'] = 'admin/service/getTimeWorking'; // API thông tin chi tiết của Services
$routes['api/expert_team/detailInfo'] = 'admin/expertteam/getExpertTeamInfo'; // API lấy thông tin đội ngũ chuyên gia
$routes['api/expert_team/detailWithPosition'] = 'admin/expertteam/getExpertTeamInService'; // API lấy thông tin đội ngũ chuyên gia theo từng chức vụ
$routes['api/blogs/listBlog'] = 'admin/blog/getListBlog'; // API lấy danh sách blog theo danh mục
$routes['api/products/listProduct'] = 'admin/product/getListProduct'; // API lấy danh sách sản phẩm

// Admin API in Dashboard Admin Page
$routes['api/dashboard/listUser'] = 'admin/dashboard/user/getListUser'; // API lấy danh sách người dùng - AdminPage
$routes['api/dashboard/listCompetentPersonnel'] = 'admin/dashboard/user/getListCompetentPersonnel'; // API lấy danh sách nhân sự - AdminPage
$routes['api/dashboard/updateStatusAccount'] = 'admin/dashboard/user/updateStatusAccount'; // API update trạng thái đăng nhập - AdminPage
$routes['api/dashboard/confirmRegisterService'] = 'admin/dashboard/user/confirmRegisterService'; // API duyệt trạng thái đăng ký dịch vụ - AdminPage
$routes['api/dashboard/getPendingService'] = 'admin/dashboard/user/getPendingService'; // API lấy danh sách dịch vụ đã đăng ký đăng chờ duyệt
$routes['api/dashboard/isRegistered'] = 'admin/dashboard/user/isRegistered'; // API lấy trạng thái dịch vụ người dùng đã đăng ký
$routes['api/dashboard/getPendingBill'] = 'admin/dashboard/cart/getListBillPending'; // API lấy danh sách hoá đơn chờ duyệt - User
$routes['api/dashboard/getAllPendingBill'] = 'admin/dashboard/cart/getListAllBillPending'; // API lấy danh sách tất cả hoá đơn chờ duyệt - AdminPage
$routes['api/dashboard/changeBillStatus'] = 'admin/dashboard/cart/changeBillStatus'; // API duyệt trạng thái hoá đơn - AdminPage
$routes['api/dashboard/getUnpaidService'] = 'admin/dashboard/service/getListUnpaidService'; // API lấy danh sách dịch vụ user chưa thanh toán - AdminPage
$routes['api/dashboard/changeServicePaymentStatus'] = 'admin/dashboard/service/changeServicePaymentStatus'; // API duyệt trạng thái đã thanh toán dịch vụ - AdminPage


// User API
$routes['api/users/update'] = 'user/profile/updateInfo'; // API update thông tin người dùng - Profile
$routes['api/users/getService'] = 'user/profile/getService'; // API lấy danh sách dịch vụ đã đăng ký - Profile
$routes['api/users/registerService'] = 'user/user/registerService'; // API người dùng đăng ký dịch vụ - User
$routes['api/users/deleteService'] = 'user/profile/deleteService'; // API xoá dịch vụ đã đăng ký - Profile
$routes['api/users/updatePeriodTime'] = 'user/user/updatePeriodTime'; // API thay đổi thời gian sử dụng dịch vụ - User
$routes['api/users/addProductToCart'] = 'user/cart/addToCart'; // API thêm sản phẩm vào giỏ hàng - User
$routes['api/users/updateQuantityInCart'] = 'user/cart/updateQuantityInCart'; // API thay đổi số lượng sản phẩm trong giỏ hàng - User
$routes['api/users/deleteProductInCart'] = 'user/cart/removeProductInCart'; // API xoá sản phẩm trong giỏ hàng - User
$routes['api/users/getListProductCart'] = 'user/cart/getListProductInCart'; // API lấy danh sách sản phẩm trong giỏ hàng - User
$routes['api/users/countListProductCart'] = 'user/cart/countListProductInCart'; // API lấy số lượng sản phẩm trong giỏ hàng - User
$routes['api/users/checkOutCart'] = 'user/cart/checkout'; // API bắt đầu thanh toán giỏ hàng - User
$routes['api/users/paymentCart'] = 'user/cart/payment'; // API xác nhận thanh toán giỏ hàng - User
$routes['api/users/billDetail'] = 'user/cart/getBillDetail'; // API lấy thông tin trong billdetai - User
$routes['api/users/deleteBillDetail'] = 'user/cart/deleleBillDetail'; // API xoá billdetai khi không thanh toán - User
$routes['api/users/getApprovedBill'] = 'user/cart/getListBillApproved'; // API lấy danh sách hoá đơn đã được duyệt - User
?>