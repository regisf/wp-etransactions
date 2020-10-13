<?php
if (!class_exists('TransactionDB')) {
    class TransactionDB
    {
        const Initiated = 'initiated';
        const Accepted = 'accepted';
        const Canceled = 'canceled';
        const Rejected = 'rejected';
        const DbName = 'transaction';

        private static $instance = null;

        private function __construct()
        {
            global $wpdb;
            $this->db = $wpdb;

            $this->db_order_name = $this->db->prefix . DbPrefix . self::DbName;
            $this->db_product_name = $this->db->prefix . DbPrefix . ProductDB::DbName;
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new TransactionDB();
            }

            return self::$instance;
        }

        public function install()
        {
            $enum_list = implode("','", [self::Initiated, self::Accepted, self::Canceled, self::Rejected]);
            $initiated = self::Initiated;

            $this->db->query("
                CREATE TABLE IF NOT EXISTS `{$this->db_order_name}` (
                    `transaction_id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `product_id` INTEGER NOT NULL,
                    `order_ref` VARCHAR(100) NOT NULL,
                    `amount` FLOAT,
                    `email` VARCHAR(100) NOT NULL,
                    `state` ENUM('$enum_list') DEFAULT '$initiated',
                    `creation_date` TIMESTAMP DEFAULT NOW(),
                    
                    FOREIGN KEY (`product_id`) 
                        REFERENCES `{$this->db_product_name}`(`product_id`) 
                        ON DELETE CASCADE
                )"
            );

            $this->db->query("
                CREATE INDEX `idx_{$this->db_order_name}_order_ref` 
                    ON `{$this->db_order_name}` (`order_ref`)");
        }

        public function get_orders($per_page, $page_number, $status)
        {
            $query = "
                SELECT
                    `o`.`transaction_id` as `transaction_id`,
                    `o`.`order_ref` as `order_ref`, 
                    `o`.`amount` as `amount`, 
                    `o`.`email` as `email`, 
                    `o`.`state` as `state`,
                    `o`.`creation_date` as `creation_date`, 
                    `p`.`name` as `product` 
                FROM 
                     `{$this->db_order_name}` AS `o` 
                 INNER JOIN 
                     `{$this->db_product_name}` as `p` 
                 ON 
                     `o`.`product_id` = `p`.`product_id`
            ";

            if ($status !== 'all') {
                $query .= "WHERE o.state = '$status'";
            }

            if (!empty($_REQUEST['orderby'])) {
                $query .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
                $query .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
            }

            $query .= " LIMIT $per_page" . ' OFFSET ' . ($page_number - 1) * $per_page;

            return $this->db->get_results($query, 'ARRAY_A');
        }


        public function insert_order($product_id, $holder, $ref, $amount)
        {
            $result = $this->db->insert(
                $this->db_order_name,
                [
                    'product_id' => $product_id,
                    'email' => $holder,
                    'order_ref' => $ref,
                    'amount' => $amount
                ]);

            if ($result !== false) {
                return $this->db->get_row("
                    SELECT * FROM `{$this->db_order_name}` 
                        WHERE `transaction_id` = {$this->db->insert_id}"
                );
            }

            return false;
        }

        private function set_transaction_state($ref, $state)
        {
            return $this->db->update($this->db_order_name,
                ['state' => $state],
                ['order_ref' => $ref]);
        }

        /**
         * @param string $ref
         * @return bool|int
         */
        public function set_transaction_rejected($ref)
        {
            return $this->set_transaction_state($ref, self::Rejected);
        }

        public function set_transaction_canceled($ref)
        {
            return $this->set_transaction_state($ref, self::Canceled);
        }

        public function set_transaction_succeed($ref)
        {
            return $this->set_transaction_state($ref, self::Accepted);
        }

        public function get_all_count()
        {
            return $this->db->get_var("SELECT COUNT(*) FROM {$this->db_order_name}");
        }

        private function get_count($state)
        {
            return $this->db->get_var("SELECT COUNT(*) FROM {$this->db_order_name} WHERE state='$state'");
        }

        public function get_initiated_count()
        {
            return $this->get_count(self::Initiated);
        }

        public function get_success_count()
        {
            return $this->get_count(self::Accepted);
        }

        public function get_reject_count()
        {
            return $this->get_count(self::Canceled);
        }

        public function get_cancel_count()
        {
            return $this->get_count(self::Rejected);
        }

        /**
         * Delete all rows where the id are into the array
         *
         * @param array $orders The primary keys
         * @return bool|int False on error, the number of rows deleted otherwise
         */
        public function deleteByIds(array $orders)
        {
            $query = "DELETE FROM {$this->db_order_name} WHERE `transaction_id` in (" . implode(',', $orders) . ')';
            return $this->db->query($query);
        }
    }
}
