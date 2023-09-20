<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Customers extends Action{

	public function __construct(){
		parent::__construct('customers'); 
	}

    public function getCustomer(int $id) {
        return $this->query("
        SELECT 
          c.id, 
          c.name, 
          c.status, 
          c.created_at, 
          c.credits, 
          c.email, 
          c.confirm_email_token,
          IFNULL(m.total_credits_spend, 0) AS total_credits_spend,
          IFNULL(cp.total_purchases, 0) AS total_purchases
        FROM 
          customers AS c
        LEFT JOIN (
          SELECT 
            id_customer, 
            SUM(total_characters) AS total_credits_spend
          FROM 
            messages
          GROUP BY 
            id_customer
        ) AS m ON c.id = m.id_customer
        LEFT JOIN (
          SELECT 
            id_customer,
            COUNT(*) AS total_purchases
          FROM 
            customer_credits_packs
          GROUP BY 
            id_customer
        ) AS cp ON c.id = cp.id_customer
        WHERE 
          c.id = '$id'
        LIMIT 1;
        ")->fetch();
    }

    public function getList() {
      return $this->query("
      SELECT customers.*,
      IFNULL(message_counts.total_messages, 0) AS total_messages,
      IFNULL(message_counts.total_threads, 0) AS total_threads,
      IFNULL(credit_counts.total_purchases, 0) AS total_purchases
        FROM 
          customers 
        LEFT JOIN (
          SELECT 
            id_customer, 
            COUNT(*) AS total_messages,
            COUNT(DISTINCT id_thread) AS total_threads
          FROM 
            messages 
          WHERE 
            role != 'system' OR role IS NULL 
          GROUP BY 
            id_customer
        ) AS message_counts ON customers.id = message_counts.id_customer 
        LEFT JOIN (
          SELECT 
            id_customer, 
            COUNT(*) AS total_purchases
          FROM 
            customer_credits_packs 
          GROUP BY 
            id_customer
        ) AS credit_counts ON customers.id = credit_counts.id_customer 
        ORDER BY 
          customers.created_at DESC;
        ");
    }

    public function getCustomerMessagesInfo(int $id_customer) {
      return $this->query("
      SELECT customers.*,
      IFNULL(message_counts.total_messages, 0) AS total_messages,
      IFNULL(message_counts.total_threads, 0) AS total_threads,
      IFNULL(credit_counts.total_purchases, 0) AS total_purchases
        FROM 
          customers 
        LEFT JOIN (
          SELECT 
            id_customer, 
            COUNT(*) AS total_messages,
            COUNT(DISTINCT id_thread) AS total_threads
          FROM 
            messages 
          WHERE 
            role != 'system' OR role IS NULL 
          GROUP BY 
            id_customer
        ) AS message_counts ON customers.id = message_counts.id_customer 
        LEFT JOIN (
          SELECT 
            id_customer, 
            COUNT(*) AS total_purchases
          FROM 
            customer_credits_packs 
          GROUP BY 
            id_customer
        ) AS credit_counts ON customers.id = credit_counts.id_customer 
        WHERE customers.id = $id_customer
        ORDER BY 
          customers.created_at DESC;
        ")->Fetch();
    }

    public function checkCustomerPackages($id_credit_pack, int $id_customer) {
        return $this->query("SELECT * FROM `customer_credits_packs` WHERE `id_customer` = $id_customer AND status='succeeded' AND id_credit_pack='$id_credit_pack'")->Fetch();
    }
    public function getByEmail(?string $email) {
        return $this->query("SELECT * FROM $this->table WHERE email='$email' LIMIT 1")->Fetch();
    }  

    public function checkUserData(?string $email, ?string $password) {
        return $this->query("SELECT * FROM $this->table WHERE email='$email' and password='$password' LIMIT 1")->Fetch();
    }  

    public function subtractCredits(?int $id, ?int $amount) {
        return $this->query("UPDATE $this->table SET credits = credits -$amount WHERE id=$id");
    } 

    public function setCredits(?int $id, ?int $amount) {
        return $this->query("UPDATE $this->table SET credits = $amount WHERE id=$id");
    }

    public function confirmEmailCustomer($id) {
        return $this->query("UPDATE customers SET confirm_email_token = NULL WHERE id=$id");
    }       

    public function updatePasswordToken(?int $id, ?string $recovery_password_token) {
        return $this->query("UPDATE $this->table SET recovery_password_token = '$recovery_password_token' WHERE id=$id");
    }   
}