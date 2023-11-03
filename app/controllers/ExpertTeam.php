<?php
class ExpertTeam extends Controller {
    private $expertTeamModel;

    public function __construct() {
        $this->expertTeamModel = $this->model('ExpertTeamModel');

    }

    public function getExpertTeamInfo() {
        $request = new Request();

        if ($request->isGet()): // Kiểm tra get
            
            $result = $this->expertTeamModel->handleGetDetail(); // Gọi xử lý ở Model

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