<?php
class PaymentModel extends Model {
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

    public function handleVNPayCallback($vnpayData) {
        // file_put_contents('vnpay_callback.log', print_r($vnpayData, true), FILE_APPEND);\
        global $config;

        $vnp_HashSecret = $config['vn_pay']['vnp_HashSecret'];
        $logPath = 'payment_callback2.log'; // đường dẫn tới thư mục log
    
        // Bước 1: Lấy toàn bộ dữ liệu GET từ VNPay
        $inputData = [];
        foreach ($vnpayData as $key => $value) {
            if (substr($key, 0, 4) == "vnp_") {
                $inputData[$key] = $value;
            }
        }
    
        file_put_contents($logPath, "[" . date('Y-m-d H:i:s') . "] Callback received: " . json_encode($inputData) . "\n", FILE_APPEND);
    
        // Lưu lại chữ ký để so sánh
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash']);
    
        // Bước 2: Sắp xếp dữ liệu để xác minh chữ ký
        ksort($inputData);
        $hashDataArr = [];
        foreach ($inputData as $key => $value) {
            $hashDataArr[] = urlencode($key) . "=" . urlencode($value);
        }
        $hashData = implode('&', $hashDataArr);
    
        // Bước 3: Tạo chữ ký từ server để so sánh với VNPay gửi về
        $secureHashCheck = hash_hmac('sha512', $hashData, $vnp_HashSecret);
    
        // Bước 4: So sánh và xử lý kết quả
        if (hash_equals($secureHashCheck, $vnp_SecureHash)) {
            $vnp_TxnRef = $vnpayData['vnp_TxnRef'] ?? null; // Chính là billId
            $vnp_ResponseCode = $vnpayData['vnp_ResponseCode'] ?? '99';
            
            $splitBillId = explode("-", $vnp_TxnRef);
            $billId = $splitBillId[0];
            
            $bill = $this->db->table('bill')->where('billid', '=', $billId)->first();
    
            if (!$bill) {
                file_put_contents($logPath, "[" . date('Y-m-d H:i:s') . "] Không tìm thấy đơn hàng với billId: $vnp_TxnRef\n", FILE_APPEND);
                return;
            }

            $user = $this->db->table('users')
                ->where('id', '=', $bill['userid'])
                ->first();

            if (!$user) {
                file_put_contents($logPath, "[" . date('Y-m-d H:i:s') . "] Không tìm thấy người dùng với userId: {$bill['userid']}\n", FILE_APPEND);
                return;
            }

    
            if ($vnp_ResponseCode === '00') {
                $this->db->table('bill')
                    ->where('billid', '=', $billId)
                    ->update(['payment_status' => 'paid']);
    
                file_put_contents($logPath, "[" . date('Y-m-d H:i:s') . "] Thanh toán thành công cho billId: $vnp_TxnRef\n", FILE_APPEND);
    
    
                $billDetails = $this->db->table('billdetail')
                    ->select('billdetail.quantity, billdetail.price, product.product_name')
                    ->join('product', 'product.productid = billdetail.productid')
                    ->where('billid', '=', $billId)
                    ->get();
    
                $postData = [
                    'email' => $user['email'],
                    'billId' => $billId,
                    'totalPrice' => $bill['total_price'],
                    'paymentMethod' => $bill['payment_method'],
                    'products' => $billDetails,
                ];
    
                $n8nUrl = 'http://localhost:5678/webhook-test/payment';
                $this->handleSendPostRequestToN8n($n8nUrl, $postData, $logPath);
    
            } else {
                $this->db->table('bill')
                    ->where('billid', '=', $billId)
                    ->update(['payment_status' => 'failed']);
    
                file_put_contents($logPath, "[" . date('Y-m-d H:i:s') . "] Thanh toán thất bại cho billId: $billId, Mã lỗi: $vnp_ResponseCode\n", FILE_APPEND);
            }
        } else {
            file_put_contents($logPath, "[" . date('Y-m-d H:i:s') . "] Dữ liệu callback sai chữ ký! (Có thể bị giả mạo)\n", FILE_APPEND);
        }
    }

    private function handleSendPostRequestToN8n($url, $data)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Set headers nếu cần (nếu n8n yêu cầu)
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        // Kiểm tra phản hồi từ n8n nếu cần
        if ($response === false) {
            // Log lỗi nếu cần thiết
            file_put_contents('n8n_error.log', "Error sending data to n8n: " . curl_error($ch) . "\n", FILE_APPEND);
        }
    }
}