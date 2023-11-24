<?php
class ExpertTeam extends Controller {
    private $expertTeamModel;

    public function __construct() {
        $this->expertTeamModel = $this->model('ExpertTeamModel', 'admin');
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

    // Lấy thông tin expert team theo chức vụ
    public function getExpertTeamInService() {
        $request = new Request();

        $data = $request->getFields();

        if ($request->isPost()): // Kiểm tra post

            if (!empty($data['teamId'])):
                $teamId = $data['teamId'];
            endif; 
            
            $result = $this->expertTeamModel->handleGetDetailInService($teamId); // Gọi xử lý ở Model

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