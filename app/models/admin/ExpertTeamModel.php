<?php
class ExpertTeamModel extends Model {
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

    // Lấy thông tin cơ bản của ExpertTeam
    public function handleGetDetail() {
        // SELECT expert_team.*, staff_position.name FROM 
        // expert_team INNER JOIN staff_position ON 
        // expert_team.position_id = staff_position.position_id
        $expertTeamInfo = $this->db->table('expert_team')
            ->select('expert_team.name as fullname, expert_team.gender, expert_team.dob, 
            expert_team.phone, expert_team.avatar, expert_team.email, staff_position.name')
            ->join('staff_position', 'expert_team.position_id = staff_position.position_id')
            ->get();

        $response = [];
        $checkNull = false;

        if (!empty($expertTeamInfo)):
            foreach ($expertTeamInfo as $key => $item):
                foreach ($item as $subKey => $subItem):
                    if ($subItem === NULL || $subItem === ''):
                        $checkNull = true;
                    endif;
                endforeach;
            endforeach;
        endif;

        if (!$checkNull):
            $response = $expertTeamInfo;
        endif;

        return $response;
    }

 
}