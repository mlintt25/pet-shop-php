<?php
class Product extends Controller {
    private $productModel;

    public function __construct() {
        $this->productModel = $this->model('ProductModel', 'admin');
    }

    // Lấy danh sách bài viết 
    public function getListProduct() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->productModel->handleGetListProduct(); // Gọi xử lý ở Model

            if (!empty($result)):
                $response = $result;
            else:
                $response = [
                    'message' => 'Đã có lỗi xảy ra'
                ];
            endif;

            echo json_encode($response);
        endif;
    }
}