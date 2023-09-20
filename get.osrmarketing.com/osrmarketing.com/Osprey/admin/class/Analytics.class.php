<?php
require_once realpath(dirname(__FILE__)) . "/Action.class.php";
class Analytics extends Action{
	public function __construct(){
		parent::__construct('analytics'); 
	}  

    public function getByTable(?string $table) {
        return $this->query("SELECT * FROM $table");
    }

    public function getMostChatPrompts(?string $limit = null, ?string $startDate = null, ?string $endDate = null) {
        $query = "SELECT p.id, p.name, p.slug, p.image, p.expert, COUNT(m.id_prompt) AS total_conversations, MAX(m.created_at) AS last_conversation_date, 
                  GROUP_CONCAT(DISTINCT m.id_customer ORDER BY m.id_customer ASC SEPARATOR ',') AS customer_ids,
                  COUNT(DISTINCT m.id_customer) AS unique_customer_count
                  FROM prompts p 
                  LEFT JOIN messages m ON p.id = m.id_prompt 
                  WHERE m.created_at IS NOT NULL ";

        if (!empty($startDate) && !empty($endDate)) {
            $query .= "AND m.created_at BETWEEN '$startDate' AND '$endDate' ";
        } elseif (!empty($startDate)) {
            $query .= "AND m.created_at >= '$startDate' ";
        } elseif (!empty($endDate)) {
            $query .= "AND m.created_at <= '$endDate' ";
        }

        $query .= "GROUP BY p.id 
                   ORDER BY total_conversations DESC ";

        if ($limit !== 'all') {
            $query .= "LIMIT " . intval($limit);
        }
        
        return $this->query($query);
    }

    public function getRegisteredCustomers(?string $limit = null, ?string $startDate = null, ?string $endDate = null) {
        $query = "
            SELECT 
                c.id, c.name, c.email, c.created_at, c.recovery_password_token, c.credits, c.status,
                IFNULL(message_counts.total_messages, 0) AS total_messages,
                IFNULL(message_counts.total_threads, 0) AS total_threads
            FROM 
                customers c
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
            ) AS message_counts ON c.id = message_counts.id_customer 
            WHERE c.created_at IS NOT NULL ";
        
        if (!empty($startDate) && !empty($endDate)) {
            $query .= "AND c.created_at BETWEEN '$startDate' AND '$endDate' ";
        } elseif (!empty($startDate)) {
            $query .= "AND c.created_at >= '$startDate' ";
        } elseif (!empty($endDate)) {
            $query .= "AND c.created_at <= '$endDate' ";
        }

        $query .= "ORDER BY c.created_at DESC ";

        if ($limit !== 'all') {
            $query .= "LIMIT " . intval($limit);
        }
        
        return $this->query($query);
    }

    public function getSales(?string $limit = null, ?string $startDate = null, ?string $endDate = null) {
        $query = "
            SELECT 
                c.id, c.name, c.email, 
                ccp.id AS sale_id, ccp.id_order, ccp.id_credit_pack, ccp.price_amount, ccp.credit_amount, ccp.price_label, ccp.price_currency_code, ccp.purchase_date, ccp.status, ccp.claimed, ccp.payment_method,
                cp.name AS pack_name
            FROM 
                customers c
            INNER JOIN customer_credits_packs ccp ON c.id = ccp.id_customer
            INNER JOIN credits_packs cp ON ccp.id_credit_pack = cp.id
            WHERE ccp.purchase_date IS NOT NULL ";
        
        if (!empty($startDate) && !empty($endDate)) {
            $query .= "AND ccp.purchase_date BETWEEN '$startDate' AND '$endDate' ";
        } elseif (!empty($startDate)) {
            $query .= "AND ccp.purchase_date >= '$startDate' ";
        } elseif (!empty($endDate)) {
            $query .= "AND ccp.purchase_date <= '$endDate' ";
        }

        $query .= "ORDER BY ccp.purchase_date DESC ";

        if ($limit !== 'all') {
            $query .= "LIMIT " . intval($limit);
        }
        
        return $this->query($query);
    }


}