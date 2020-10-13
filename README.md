# Etransactions Wordpress Plugin

Wordpress front-end for the [etransaction PHP library](https://github.com/regisf/php-etransactions).

This plugin was written initialy for the organisation that collect donation for the new [Cambo les Bains organ](https://les-amis-de-lorgue-stlaurent-de-cambo.fr/).

This Wordpress plugin allow you to install a simple payment system with the [Crédit Agricole - Mon Commerce](https://http://www.ca-moncommerce.com/) system.

The plugin allow to switch into testing mode. 

## Features

The plugin allow you to create products and display them into a single page.   

## Minimal requirements

PHP 5.5 / Tested with Wordpress 5.5.1

## Installation

Use the wordpress plugin management or the ZIP file into the [release](https://github.com/regisf/wp-etransactions/releases/tag/v1.0.0) page.

### Callback pages

The plugin needs five pages to be effective:

#### The product list

Where the products list is displayed. Just add the short code `[etransactions-products-list]` somewhere into 
the page.

#### The confirmation page

The "Crédit Agricole Mon Commerce" system needs an email address to work, that's
the purpose of this page. Just put the shortcode 
`[etransactions-confirmation-page]` somewhere into the page and it will display 
a form that collect the mail address.

#### The validation page

Because of the system technology, the plugin needs to generate an invisible
form. This is the purpose of this page. Put the `[etransactions-validation-page]`
somewhere.

Once the validation button is clicked, the Crédit Agricole system takes the 
control with the effective payment system. After the procedure, the Crédit 
Agricole system call a landing page. Those page are required know what is 
the state of the sale. 

_Note: On testing mode, the credit card must respect the credit card format and
don't have to be a real credit card_

#### Payement accepted landing page

Just put `[etransactions-accepted-page]` somewhere into the page. 

#### Payement rejected landing page

Just put `[etransactions-accepted-page]` somewhere into the page.

#### Payement canceled landing page

Just put `[etransactions-accepted-page]` somewhere into the page.

#### Other short codes

* `etransactions-product-name`: To display the product name.
* `etransactions-product-price`: To display the product price.  

### Styling

No CSS classes are defined. However the The plugin uses several class you 
could override. 

* Confirmation page:
* Validation page:
* Landing pages: 
