<?php

if (!class_exists('ProductsDb')) {
    class ProductsDb
    {
        private static $instance = null;
        private $db_product_name;
        private $db;

        private function __construct()
        {
            global $wpdb;
            $this->db = $wpdb;
            $this->db_product_name = $this->db->prefix . DbPrefix . 'product';
            $this->db_order_name = $this->db->prefix . DbPrefix . 'order';
        }

        public static function get_instance()
        {
            if (self::$instance === null) {
                self::$instance = new ProductsDb();
            }

            return self::$instance;
        }

        public function getProducts($per_page = 5, $page_number = 1, $status = 'all')
        {
            $query = "SELECT * FROM `{$this->db_product_name}`";

            if ($status === 'active') {
                $query .= ' WHERE `active`=true';
            } else if ($status === 'inactive') {
                $query .= ' WHERE `active`=false';
            }

            if (!empty($_REQUEST['orderby'])) {
                $query .= ' ORDER BY ' . esc_sql($_REQUEST['orderby']);
                $query .= !empty($_REQUEST['order']) ? ' ' . esc_sql($_REQUEST['order']) : ' ASC';
            }

            $query .= " LIMIT $per_page" . ' OFFSET ' . ($page_number - 1) * $per_page;

            return $this->db->get_results($query, 'ARRAY_A');
        }

        public function install()
        {
            $this->db->query("
                CREATE TABLE IF NOT EXISTS  `{$this->db_product_name}` (
                    `product_id` INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
                    `name` VARCHAR(100),
                    `active` BOOLEAN,
                    `price` DOUBLE 
                )"
            );
        }

        public function setActive($product_id, $active = true)
        {
            return $this->db->update($this->db_product_name,
                ['active' => $active],
                ['product_id' => $product_id]);
        }

        public function get_actives_count()
        {
            return $this->db->get_var('SELECT COUNT(*) FROM `' . $this->db_product_name . '` WHERE `active`=true');
        }

        public function get_inactives_count()
        {
            return $this->db->get_var('SELECT COUNT(*) FROM `' . $this->db_product_name . '` WHERE `active`=false');
        }

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

        public function insert($name, $price, $active)
        {
            return $this->db->insert($this->db_product_name, ['name' => $name, 'price' => $price, 'active' => $active]);
        }

        public function deleteById($product_id)
        {
            return $this->db->delete($this->db_product_name, ['product_id' => $product_id], ['product_id' => $product_id]);
        }

        public function pkoi()
        {
            return $this->db->last_error;
        }

        public function update($product_id, $name, $price, $active)
        {
            return $this->db->update($this->db_product_name,
                ['name' => $name, 'price' => $price, 'active' => $active],
                ['product_id' => $product_id]);
        }

        public function get_actives()
        {
            return $this->db->get_results('SELECT * FROM ' . $this->db_product_name . ' WHERE `active`=true');
        }
    }
}
