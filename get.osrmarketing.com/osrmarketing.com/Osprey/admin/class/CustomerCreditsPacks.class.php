<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class CustomerCreditsPacks extends Action{
    public function __construct(){
        parent::__construct('customer_credits_packs'); 
    }

    public function getPurchases() {
        return $this->query("SELECT ccp.*, c.name, c.email, c.credits, cp.name AS product_name, cp.image AS product_image FROM customer_credits_packs ccp JOIN customers c ON ccp.id_customer = c.id JOIN credits_packs cp ON ccp.id_credit_pack = cp.id ORDER BY ccp.purchase_date");
    }

    public function getByIdCustomer(int $id_customer) {
        return $this->query("SELECT * FROM $this->table WHERE id_customer='$id_customer' ORDER BY `purchase_date` DESC");
    }

    public function getByIdSucceededCustomer(int $id_customer) {
        return $this->query("SELECT * FROM $this->table WHERE id_customer='$id_customer' AND  status = 'succeeded' ORDER BY `purchase_date` DESC");
    }

    public function getByIdOrder(string $id_order) {
        return $this->query("SELECT * FROM $this->table WHERE id_order='$id_order' LIMIT 1")->Fetch();
    }

    public function getByPaypalToken(string $paypal_token) {
        return $this->query("SELECT * FROM $this->table WHERE paypal_token='$paypal_token' LIMIT 1")->Fetch();
    }

    public function approvePayment(string $id_order) {
        return $this->query("UPDATE $this->table SET status = 'succeeded' WHERE id_order = '$id_order'");
    }

    public function getByPayPalId(string $paypal_token) {
        return $this->query("SELECT * FROM $this->table WHERE paypal_token='$paypal_token' LIMIT 1")->Fetch();
    }

    public function updateClaimedStatus(string $id_order) {
        return $this->query("UPDATE $this->table SET claimed = 1 WHERE id_order = '$id_order'");
    }

    public function setCheckoutStatus(string $id_order, string $status) {
        return $this->query("UPDATE $this->table SET status = '$status' WHERE id_order = '$id_order'");
    }

    public function checkoutSuccess(int $id_customer, string $id_order, int $credits) {
        $this->query("UPDATE customers SET credits = credits + $credits WHERE id = $id_customer;");
        return $this->query("UPDATE customer_credits_packs SET claimed = 1, status = 'succeeded' WHERE id_customer = $id_customer AND id_order = '$id_order';");
    }

}