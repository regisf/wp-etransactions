<?php


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


if (!class_exists('Order_List_Table')) {
    class Order_List_Table extends WP_List_Table
    {
        /**
         * @var OrderDB
         */
        private $orderDb;

        public function __construct($db)
        {
            $this->orderDb = $db;

            parent::__construct([
                'singular' => __('Order', 'etransactions'),
                'plural' => __('Orders', 'etransactions'),
            ]);

            $this->prepare_items();
        }

        public function get_columns()
        {
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'product' => 'Product',
                'order_ref' => 'References',
                'amount' => 'Price',
                'email' => 'Email',
                'state' => 'State',
                'creation_date' => 'Creation date'
            );

            return $columns;
        }

        public function column_cb($item)
        {
            return sprintf('<input type="checkbox" name="bulk-action[]" value="%s" />', $item['product']);
        }

        public function column_default($item, $column_name)
        {
            switch ($column_name) {
                case 'transaction_id':
                    return $this->column_cb($item);

                case 'product':
                case 'order_ref':
                case 'creation_date':
                case 'email':
                case 'state':
                case 'active':
                    return $item[$column_name];

                case 'amount':
                    return $item[$column_name] . '&nbsp;&euro;';

                default:
                    return print_r($item, true);
            }
        }

        public function column_name($item)
        {
//            $nonce = wp_create_nonce(NonceName);
//
//            print_r($item);
//            $title = '<strong>Hello</strong>'; // . $item['name'] . '</strong>';
//            $actions = [
//                'edit' => sprintf(
//                    '<a href="?page=%s&product_action=%s&product=%s&_wpnonce=%s">Edit</a>',
//                    esc_attr($_REQUEST['page']),
//                    'edit',
//                    absint($item['product_id']),
//                    $nonce
//                ),
//
//                'delete' => sprintf(
//                    '<a href="?page=%s&product_action=%s&product=%s&_wpnonce=%s">Delete</a>',
//                    esc_attr($_REQUEST['page']),
//                    'delete_confirm',
//                    absint($item['product_id']),
//                    $nonce
//                )
//            ];
//
//            return $title; // . $this->row_actions($actions);
        }

        public function get_sortable_columns()
        {
            return array(
                'product' => array('product', false),
                'amount' => array('amount', false),
                'state' => array('state', false),
                'creation_date' => array('creation_date', false)
            );
        }

        public function get_bulk_actions()
        {
            return [
                'bulk-delete' => __('Delete', 'etransactions'),
            ];
        }

        public function prepare_items()
        {
            $this->process_bulk_action();

            $columns = $this->get_columns();
            $hidden = []; //$this->get_hidden_columns();
            $sortable = $this->get_sortable_columns();

            $perPage = 5;
            $currentPage = $this->get_pagenum();
            $data = $this->orderDb->get_orders($perPage, $currentPage);

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
            }
        }
    }
}
