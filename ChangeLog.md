# Changes for e-Transaction WordPress plugin

* v1.2.1:
  * Restore dropped category

* v1.2.0:
  * Add translation
  * email sending on each transction
  
* v1.0.2:
    * Fix plugin uninstallation
    * Fix database upgrade for the same version. The database was altered each 
    time the plugin was loaded.
    * Create a dist release script
    
* v1.0.1:
    * Fix cascade deletion on `_etransactions_transaction` table
    * Fix non escaped description text
    * Add free amount for product. e.g. for donation.
    * Remove hard coded string for translation key
    * Change translation functions call due to a misunderstood how it works.
    * Add product cateogry
    
* v1.0.0: 
    * Initial Release
