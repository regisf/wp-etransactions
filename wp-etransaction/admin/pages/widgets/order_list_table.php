<?php


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


if (!class_exists('Order_List_Table')) {
    class Order_List_Table extends WP_List_Table
    {
        /**
         * @var TransactionDb
         */
        private $orderDb;

        public function __construct($db)
        {
            $this->orderDb = $db;

            parent::__construct([
                'singular' => __('Order', 'etransactions'),
                'plural' => __('Orders', 'etransactions'),
            ]);

            $columns = $this->get_columns();
            $sortable = $this->get_sortable_columns();
            $this->_column_headers = array($columns, [], $sortable);

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
            return sprintf('<input type="checkbox" name="bulk-action[]" value="%d" />', $item['transaction_id']);
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

//        public function column_name($item)
//        {
//            $nonce = wp_create_nonce(NonceName);
//            $title = '<strong>' . $item['product'] . '</strong>';
//
//            $actions = [
//                'delete' => sprintf(
//                    '<a href="?page=%s&order_action=%s&order=%s&_wpnonce=%s">Delete</a>',
//                    esc_attr($_REQUEST['page']),
//                    'delete_confirm',
//                    absint($item['order_id']),
//                    $nonce
//                )
//            ];
//
//            return $title . $actions;
//        }

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

        public function process_bulk_action()
        {
            $action = $this->current_action();
            if ($action) {
                $valid = apply_filters('is_nonce_valid', $_REQUEST['_wpnonce']);
                if (!$valid) {
                    return;
                }

                if (isset($_REQUEST['bulk-action'])) {
                    $orders = $_REQUEST['bulk-action'];
                    if ('bulk-delete' === $action) {
                        $this->orderDb->deleteByIds($orders);
                    }
                }
            }

        }


        public function prepare_items()
        {
            $this->process_bulk_action();

            $perPage = $this->get_items_per_page('records_per_page', 10);
            $currentPage = $this->get_pagenum();

            $status = isset($_REQUEST['order_status']) ? $_REQUEST['order_status'] : 'all';
            $data = $this->orderDb->get_orders($perPage, $currentPage, $status);

            $totalItems = count($data);

            $this->set_pagination_args([
                'total_items' => $totalItems,
                'per_page' => $perPage
            ]);

            $this->items = $data;
        }
    }
}
