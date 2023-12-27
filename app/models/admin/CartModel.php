<?php
class CartModel extends Model {
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

    // Xử lý lấy danh sách hoá đơn chưa duyệt
    public function handleGetListBillPending($userId) {
        $queryGet = $this->db->table('bill')
            ->select('bill.billid, bill.payment_method, bill.total_price, bill.created_at,
                billdetail.productid, billdetail.quantity, billdetail.price, product.product_name')
            ->join('billdetail', 'billdetail.billid = bill.billid')
            ->join('product', 'billdetail.productid = product.productid')
            ->where('bill.userid', '=', $userId)
            ->where('bill.status', '=', 0)
            ->get();

        $response = [];
        $resultArray = [];

        if (!empty($queryGet)):
            foreach ($queryGet as $item) {
                $billId = $item["billid"];
            
                if (!isset($resultArray[$billId])) {
                    // Nếu billid chưa có trong mảng kết quả, tạo một mục mới
                    $resultArray[$billId] = [
                        "payment_method" => $item["payment_method"],
                        "total_price" => $item["total_price"],
                        "created_at" => $item["created_at"],
                        "products" => []
                    ];
                }
            
                // Thêm thông tin sản phẩm vào danh sách sản phẩm của billid
                $resultArray[$billId]["products"][] = [
                    "productid" => $item["productid"],
                    "quantity" => $item["quantity"],
                    "price" => $item["price"],
                    "product_name" => $item["product_name"]
                ];
            }

            $response = $resultArray;
        endif;

        return $response;
    }

    // Xử lý lấy danh sách tất cả hoá đơn chưa duyệt
    public function handleGetListAllBillPending() {
        $queryGet = $this->db->table('bill')
            ->select('bill.billid, bill.payment_method, bill.total_price, bill.created_at,
                billdetail.productid, billdetail.quantity, billdetail.price, product.product_name')
            ->join('billdetail', 'billdetail.billid = bill.billid')
            ->join('product', 'billdetail.productid = product.productid')
            ->where('bill.status', '=', 0)
            ->get();

        $response = [];
        $resultArray = [];

        if (!empty($queryGet)):
            foreach ($queryGet as $item) {
                $billId = $item["billid"];
            
                if (!isset($resultArray[$billId])) {
                    // Nếu billid chưa có trong mảng kết quả, tạo một mục mới
                    $resultArray[$billId] = [
                        "payment_method" => $item["payment_method"],
                        "total_price" => $item["total_price"],
                        "created_at" => $item["created_at"],
                        "products" => []
                    ];
                }
            
                // Thêm thông tin sản phẩm vào danh sách sản phẩm của billid
                $resultArray[$billId]["products"][] = [
                    "productid" => $item["productid"],
                    "quantity" => $item["quantity"],
                    "price" => $item["price"],
                    "product_name" => $item["product_name"]
                ];
            }

            $response = $resultArray;
        endif;

        return $response;
    }

    public function handleChangeBillStatus($billId) {
        $queryCheck = $this->db->table('bill')
            ->select('status')
            ->where('billid', '=', $billId)
            ->first();

        if (!empty($queryCheck)):
            $dataUpdate = [
                'status' => 1,
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $updateStatus = $this->db->table('bill')
                ->where('billid', '=', $billId)
                ->update($dataUpdate);

            if ($updateStatus):
                return true;
            endif;
        endif;

        return false;
    }
}