<?php

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


if (!class_exists('ETransactions_Product_List_Table')) {
    class ETransactions_Product_List_Table extends WP_List_Table
    {
        /**
         * @var ProductDB
         */
        private $productDB;

        public function __construct($db)
        {
            $this->productDB = $db;

            parent::__construct([
                'singular' => __('Product', ETransactions_Tr),
                'plural' => __('Products', ETransactions_Tr),
            ]);

            $this->prepare_items();
        }

        public function get_columns()
        {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'name' => __('Name', 'etransaction'),
                'active' => __('Active', 'etransaction'),
                'category' => __('Category', 'etransaction'),
                'free_amount' => __('Free Amount', ETransactions_Tr),
                'price' => __('Price', 'etransaction'),
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

                case 'category':
                    if (!isset($item[$column_name])) {
                        return '--';
                    }

                case 'name':
                    return $item[$column_name];

                case 'active':
                case 'free_amount':
                    return $item[$column_name] === '1' ? __('Yes', ETransactions_Tr) : __('No', ETransactions_Tr);

                case 'price':
                    return $item[$column_name] . '&nbsp;&euro;';

                default:
                    return print_r($item, true);
            }
        }

        public function column_name($item)
        {
            $nonce = wp_create_nonce(ETransactions_NonceName);

            $title = '<strong>' . $item['name'] . '</strong>';
            $action = $item['active'] === '1' ? 'inactive' : 'active';
            $actions = [
                'edit' => sprintf(
                    '<a href="?page=%s&product_action=%s&product=%s&_wpnonce=%s">Edit</a>',
                    sanitize_text_field($_REQUEST['page']),
                    'edit',
                    absint($item['product_id']),
                    $nonce
                ),

                'inactive' => sprintf(
                    '<a href="?page=%s&product_action=toggle_active&product=%s&_wpnonce=%s">Set %s</a>',
                    sanitize_text_field($_REQUEST['page']),
                    absint($item['product_id']),
                    $nonce,
                    $action
                ),

                'delete' => sprintf(
                    '<a href="?page=%s&product_action=%s&product=%s&_wpnonce=%s">Delete</a>',
                    sanitize_text_field($_REQUEST['page']),
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
                'name' => ['name', false],
                'price' => ['price', false],
                'active' => ['active', false],
                'category' => ['category', false],
            );
        }

        public function get_bulk_actions()
        {
            return [
                'bulk-delete' => __('Delete', ETransactions_Tr),
                'bulk-toggle-active' => __('Toggle Active', ETransactions_Tr)
            ];
        }

        public function prepare_items()
        {
            $this->process_bulk_action();

            $columns = $this->get_columns();
            $hidden = [];
            $sortable = $this->get_sortable_columns();

            $status = isset($_REQUEST['product_status']) ? sanitize_text_field($_REQUEST['product_status']) : 'all';
            $perPage = $this->get_items_per_page('records_per_page', 10);
            $currentPage = $this->get_pagenum();

            $this->items = $this->productDB->get_products($perPage, $currentPage, $status);
            $totalItems = $this->productDB->get_count($status);


            $this->set_pagination_args(array(
                'total_items' => $totalItems,
                'per_page' => $perPage
            ));

            $this->_column_headers = array($columns, $hidden, $sortable);
        }

        public function process_bulk_action()
        {
            $action = $this->current_action();
            if ($action) {
                $valid = apply_filters('is_nonce_valid', $_REQUEST['_wpnonce']);
                if (!$valid) {
                    return;
                }

                if (isset($_REQUEST['bulk-action'])) {
                    $products = $_REQUEST['bulk-action'];
                    if ('bulk-delete' === $action) {
                        $this->productDB->delete_by_ids($products);
                    } else if ('bulk-toggle-active' === $action) {
                        $result = $this->productDB->toggle_ids($products);
                        if ($result === false) {
                            echo "NO";
                        }
                    }
                }
            }
        }
    }
}
