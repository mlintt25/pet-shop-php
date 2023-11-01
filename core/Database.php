<?php
class Database {
    private $__conn;

    use QueryBuilder;

    function __construct() {
        global $dbConfig;
        $this->__conn = Connection::getInstance($dbConfig);
    }   

    //Thêm dữ liệu
    function insertData($table, $data) {

        if (!empty($data)):
            $fieldStr = '';
            $valueStr = '';
            foreach ($data as $key=>$value):
                $fieldStr.=$key.',';
                $valueStr.="'".$value."',";
            endforeach;
            $fieldStr = rtrim($fieldStr, ',');
            $valueStr = rtrim($valueStr, ',');

            $sql = "INSERT INTO $table($fieldStr) VALUES ($valueStr)";

            $status = $this->query($sql);

            if ($status):
                return true;
            endif;
        endif;

        return false;
    }
    
    
    //Sửa dữ liệu
    function updateData($table, $data, $condition = '') {

        if (!empty($data)):
            $updateStr = '';
            foreach ($data as $key=>$value):
                $updateStr.="$key='$value',";
            endforeach;

            $updateStr = rtrim($updateStr, ',');

            if (!empty($condition)):
                $sql = "UPDATE $table SET $updateStr WHERE $condition";
            else:
                $sql = "UPDATE $table SET $updateStr";
            endif;

            $status = $this->query($sql);

            if ($status):
                return true;
            endif;
        endif;

        return false;
    }

    //Xoá dữ liệu
    function deleteData($table, $condition = '') {
        if (!empty($condition)):
            $sql = 'DELETE FROM '.$table.' WHERE '.$condition;
        else:
            $sql = 'DELETE FROM '.$table;
        endif;

        $status = $this->query($sql);

        if ($status):
            return true;
        endif;

        return false;
    }

    //Truy vấn câu lệnh SQL
    function query($sql){
        try {
            $statement = $this->__conn->prepare($sql);

            $statement->execute();

            return $statement;
        } catch (Exception $exception) {
            $mess = $exception->getMessage();
            $data['message'] = $mess;
            App::$app->loadError('database', $data);
            die();
        }

    }

    //Trả về id mới nhất sau khi đã insert
    function lastInsertId() {
        return $this->__conn->lastInsertId();
    }
}