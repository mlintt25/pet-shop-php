<?php
class Payment extends Controller {
    private $paymentModel;

    public function __construct() {
        $this->paymentModel = $this->model('PaymentModel', 'user');
    }

    public function receiveVNPayCallback() {
        // file_put_contents('vnpay_callback.log', print_r($_GET, true), FILE_APPEND);
        // Nếu có $_GET và data từ vnpay thì gọi model và truyền giá trị $_GET cho model xử lý
        if (isset($_GET) && !empty($_GET)) {
            $vnpayData = $_GET;
            $this->paymentModel->handleVNPayCallback($vnpayData);
        } else {
            // Nếu không có dữ liệu từ vnpay thì trả về thông báo lỗi
            echo json_encode(['status' => false, 'message' => 'Không có dữ liệu từ VNPay']);
        }
    }    
}