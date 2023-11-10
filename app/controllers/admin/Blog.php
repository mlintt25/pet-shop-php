<?php
class Blog extends Controller {
    private $blogModel;

    public function __construct() {
        $this->blogModel = $this->model('BlogModel', 'admin');
    }

    // Lấy danh sách bài viết 
    public function getListBlog() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->blogModel->handleGetListBlog(); // Gọi xử lý ở Model

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