<?php 


class ETransaction_Actions {
    public static function admin_post_add_product() {
        $name = $_REQUEST['name'];
        $price = $_REQUEST['price'];
        $active = isset($_REQUEST['active']);
        $free_amount = isset($_REQUEST['free_amount']);
        $category = $_REQUEST['category'];
    
        ETransactions_ProductDB::get_instance()
            ->insert($name, $price, $active);
    
        $this->redirect();            
    }

    /**
     * Convinent function to redirect on the main page
     */
    private static function redirect() {
        wp_redirect('/wp-admin/admin.php?page=etransactions_products');
        exit();
    
    }
}

