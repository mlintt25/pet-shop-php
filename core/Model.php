<?php
abstract class Model extends Database {
    protected $db;

    function __construct(){
        $this->db = new Database();
    }

    abstract function tableFill();

    abstract function fieldFill();

    abstract function primaryKey();

    //Lấy tất cả bản ghi
    public function all() {
        $tableName = $this->tableFill();
        $fieldSelect = $this->fieldFill();
        if (empty($fieldSelect)):
            $fieldSelect = '*';
        endif;
        $sql = "SELECT $fieldSelect FROM $tableName";
        $query = $this->db->query($sql);
        if (!empty($query)):
            return $query->fetchAll(PDO::FETCH_ASSOC);
        endif;
        return false;
    }

    //Lấy 1 bản ghi
    public function find($id) {
        $tableName = $this->tableFill();
        $fieldSelect = $this->fieldFill();
        $primaryKey = $this->primaryKey();

        if (empty($fieldSelect)):
            $fieldSelect = '*';
        endif;

        $sql = "SELECT $fieldSelect FROM $tableName WHERE $primaryKey = $id";
        $query = $this->db->query($sql);
        if (!empty($query)):
            return $query->fetch(PDO::FETCH_ASSOC);
        endif;

        return false;

    }
}