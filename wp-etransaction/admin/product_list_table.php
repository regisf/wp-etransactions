<?php


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


if (!class_exists('Product_List_Table')) {
    class Product_List_Table extends WP_List_Table
    {
        /**
         * @var ProductsDB
         */
        private $productDB;

        public function __construct($db)
        {
            $this->productDB = $db;

            parent::__construct([
                'singular' => __('Product', 'etransactions'),
                'plural' => __('Products', 'etransactions'),
            ]);

            $this->prepare_items();
        }

        public function get_columns()
        {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'name' => 'Name',
                'active' => 'Active',
                'price' => 'Price',
            );

            return $columns;
        }

        public function column_cb($item)
        {
            return sprintf('<input type="checkbox" name="bulk-action[]" value="%s" />', $item['product_id']);
        }

        public function column_default($item, $column_name)
        {
            switch ($column_name) {
                case 'id':
                    return $this->column_cb($item);

                case 'name':
                case 'active':
                    return $item[$column_name];

                case 'price':
                    return $item[$column_name] . '&nbsp;&euro;';

                default:
                    return print_r($item, true);
            }
        }

        public function column_name($item)
        {
            $nonce = wp_create_nonce(NonceName);

            $title = '<strong>' . $item['name'] . '</strong>';
            $action = $item['active'] === '1' ? 'inactive' : 'active';
            $actions = [
                'edit' => sprintf(
                    '<a href="?page=%s&product_action=%s&product=%s&_wpnonce=%s">Edit</a>',
                    esc_attr($_REQUEST['page']),
                    'edit',
                    absint($item['product_id']),
                    $nonce
                ),

                'inactive' => sprintf(
                    '<a href="?page=%s&action=%s&product=%s&_wpnonce=%s">Set %s</a>',
                    esc_attr($_REQUEST['page']),
                    $action,
                    absint($item['product_id']),
                    $nonce,
                    $action
                ),

                'delete' => sprintf(
                    '<a href="?page=%s&product_action=%s&product=%s&_wpnonce=%s">Delete</a>',
                    esc_attr($_REQUEST['page']),
                    'delete_confirm',
                    absint($item['product_id']),
                    $nonce
                )
            ];

            return $title . $this->row_actions($actions);
        }

        public function get_sortable_columns()
        {
            return array(
                'name' => array('name', false),
                'price' => array('price', false),
                'active' => array('active', false)
            );
        }

        public function get_bulk_actions()
        {
            return [
                'bulk-delete' => __('Delete', 'etransactions'),
                'bulk-toggleactive' => __('Toggle Active', 'etransactions')
            ];
        }

        public function prepare_items()
        {
            $this->process_bulk_action();

            $columns = $this->get_columns();
            $hidden = $this->get_hidden_columns();
            $sortable = $this->get_sortable_columns();

            $status = $_REQUEST['product_status'];
            $perPage = 5;
            $currentPage = $this->get_pagenum();
            $data = $this->productDB->getProducts($perPage, $currentPage, $status);

            $totalItems = count($data);

            $this->set_pagination_args(array(
                'total_items' => $totalItems,
                'per_page' => $perPage
            ));

            $data = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
            $this->_column_headers = array($columns, $hidden, $sortable);
            $this->items = $data;
        }

        public function process_bulk_action()
        {
            $action = $this->current_action();
            if ($action) {
                $product = $_REQUEST['product'];
                switch ($action) {
                    case 'active':
                        $valid = apply_filters('is_nonce_valid', $_REQUEST['_wpnonce']);
                        if (!$valid) {
                            die('Denied');
                        }

                        $this->productDB->setActive($product);
                        break;

                    case 'inactive';
                        $valid = apply_filters('is_nonce_valid', $_REQUEST['_wpnonce']);
                        if (!$valid) {
                            die('Denied');
                        }
                        $this->productDB->setActive($product, false);
                        break;
                }
            }
        }

        public function get_active_count()
        {
            return $this->productDB->get_actives_count();
        }

        public function get_inactive_count()
        {
            return $this->productDB->get_inactives_count();
        }

        public function get_all_count()
        {
            return $this->productDB->get_all_count();
        }
    }
}
