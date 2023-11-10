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

    // Lấy thông tin cơ bản của Pets
    public function handleGetDetail() {
        // SELECT expert_team.*, staff_position.name FROM 
        // expert_team INNER JOIN staff_position ON 
        // expert_team.position_id = staff_position.position_id
        $basePetInfo = $this->db->table('expert_team')
            ->select('expert_team.name as fullname, expert_team.gender, expert_team.dob, 
            expert_team.phone, expert_team.avatar, expert_team.email, staff_position.name')
            ->join('staff_position', 'expert_team.position_id = staff_position.position_id')
            ->get();

        $response = [];

        if (!empty($basePetInfo)):
            foreach ($basePetInfo as $key => $item):
                if (!empty($basePetInfo[$key])):
                    $response = $basePetInfo;
                endif;
            endforeach;
        endif;

        return $response;
    }

 
}