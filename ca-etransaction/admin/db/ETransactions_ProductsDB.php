<?php

if (!class_exists('ETransactions_ProductDB')) {
    class ETransactions_ProductDB
    {
        const DbName = 'product';

        private static $instance = null;
        private $db_product_name;
        private $db;

        private function __construct()
        {
            global $wpdb;
            $this->db = $wpdb;
            $this->db_product_name = $this->db->prefix . ETransactions_DbPrefix . self::DbName;
        }

        /**
         * Get the class instance to use it as a singleton
         */
        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new ETransactions_ProductDB();
            }

            return self::$instance;
        }

        private function query_selector_for_status($status) 
        {
            switch ($status) {
                case 'active':
                    $query = ' WHERE `active` = true';
                break;

                case 'inactive':
                    $query = ' WHERE `active` = false';
                break;

                default:
                    $query = '';
                break;
            }

            return $query;
        }

        /**
         * Get all product from the database regarding the page number and the 
         */
        public function get_products($per_page, $page_number, $status = 'all')
        {
            $value = $this->query_selector_for_status($status);
            $query = "SELECT * FROM `{$this->db_product_name}`$value";

            if (!empty($_REQUEST['orderby'])) {
                $query .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
                $query .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
            }

            $query .= " LIMIT $per_page" . ' OFFSET ' . ($page_number - 1) * $per_page;

            return $this->db->get_results($query, 'ARRAY_A');
        }

        public function get_actives() {
            return $this->db->get_results("SELECT * FROM `{$this->db_product_name}` where active=true");
        }

        public function get_count($status)
        {
            switch ($status) {
                case 'all':
                    return $this->get_all_count();

                case 'active':
                    return $this->get_actives_count();

                case 'inactive':
                    return $this->get_inactives_count();

                default:
                    return 0;
            }
        }

        public function install()
        {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS  `{$this->db_product_name}` (
                    `product_id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `name` VARCHAR(100),
                    `category` VARCHAR(100) NULL,
                    `active` BOOLEAN,
                    `free_amount` BOOLEAN DEFAULT FALSE,
                    `price` DOUBLE
                )"
            );
        }

        public function upgrade()
        {
            if (CurrentVersion < 101) {
                $this->db->query("
                    ALTER TABLE `{$this->db_product_name}`
                        ADD `free_amount` BOOLEAN DEFAULT FALSE AFTER `active`
                    ");

                $this->db->query("
                    ALTER TABLE `{$this->db_product_name}` 
                        ADD `category` VARCHAR(100) NULL AFTER `name`
                ");
            }
        }

        public function get_actives_count()
        {
            return $this->db->get_var('SELECT COUNT(*) FROM `' . $this->db_product_name . '` WHERE `active`=true');
        }

        public function get_inactives_count()
        {
            return $this->db->get_var('SELECT COUNT(*) FROM `' . $this->db_product_name . '` WHERE `active`=false');
        }

        /**
         * Get all products count from database
         * @returns The number of products
         */
        public function get_all_count()
        {
            return $this->db->get_var('SELECT COUNT(*) FROM `' . $this->db_product_name . '`');
        }

        public function getById($id)
        {
            $results = $this->db->get_results('SELECT * FROM `' . $this->db_product_name . '` WHERE `product_id`=' . $id);

            if (count($results)) {
                return $results[0];
            }

            die('This should not append');
        }

        public function insert($name, $price, $active, $free_amount, $category)
        {
            return $this->db->insert(
                $this->db_product_name,
                [
                    'name' => $name,
                    'price' => $price,
                    'active' => $active,
                    'free_amount' => $free_amount,
                    'category' => $category
                ]
            );
        }

        public function deleteById($product_id)
        {
            return $this->db->delete(
                $this->db_product_name,
                ['product_id' => $product_id],
                ['product_id' => $product_id]
            );
        }

        public function delete_by_ids(array $products)
        {
            $query = "DELETE FROM {$this->db_product_name} WHERE `product_id` in (" . implode(',', $products) . ')';
            return $this->db->query($query);
        }

        public function pkoi()
        {
            return $this->db->last_error;
        }

        public function update($product_id, $name, $price, $active, $free_amount, $category)
        {
            return $this->db->update($this->db_product_name,
                [
                    'name' => $name,
                    'price' => $price,
                    'active' => $active,
                    'free_amount' => $free_amount,
                    'category' => $category
                ],
                ['product_id' => $product_id]);
        }

        public function get_actives_for_category($category)
        {
            $query = 'SELECT * FROM ' . $this->db_product_name . ' WHERE `active`=true';

            if ($category !== '') {
                $query .= " AND `category` = '$category'";
            }

            return $this->db->get_results($query);
        }

        public function toggle($product_id)
        {
            $query = "UPDATE `{$this->db_product_name}` SET `active`=NOT `active` WHERE `product_id` = $product_id";
            return $this->db->query($query);
        }

        public function toggle_ids($products)
        {
            $products = esc_sql($products);
            $query = "UPDATE `{$this->db_product_name}` SET `active`=NOT `active` WHERE `product_id` IN (" . implode(',', $products) . ')';
            return $this->db->query($query);
        }
    }
}
