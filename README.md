# easymanage-order-sync

Woocommerce orders to Google Spreadsheet
<p>Sync new Woocommerce orders with Google Spreadsheet. Fields from orders imported: Order id, Status, Currency,
Discount, Total, Tax, Payment, Shipping method, Shipping Total, Order Items, and Billing and Shipping fields.</p>

<strong>Recommended Woocommerce version at least 3.6</strong>
<strong>Easymanage version at least 1.0.3!</strong>

Easymanage order sync plugin is a part of <a href="https://easymanage.biz/">"Easymanage"</a> family.
Before installing it, install and configure <strong>Easymanage</strong> plugin for wordpress and <strong>Easymanage</strong> Google app add-on for Spreadsheet.
<br>
<a href="https://wordpress.org/plugins/easymanage/">Check at Wordpress</a> <br>
<a href="https://github.com/easymanagebiz/woobase">Check at github</a>

<h3>Installation</h3>

Download code from repo. FTP to your webstore root and copy it content to wp-content/plugins/easymanage-order-sync.<br>
Activate plugin in wordpress adminpanel(Plugins -> Installed Plugins). Then open spreadsheet with  <strong>Easymange app</strong> installed, top menu Menu -> Add-ons -> Easymanage -> Addons, or sidebar menu "Addons", click on link "Refresh add-ons".

<img src="https://easymanage.biz/wp-content/uploads/2019/10/menu-addons-configure-2.png" />

After that, new menu item "Orders" appear in sidebar.

<img src="https://easymanage.biz/wp-content/uploads/2019/10/addons-orders-item.png" />

<br>

<h3>How it works?</h3>
After each new order placed in Wocommerce, plugins saved order data into Easymanage trigger table. Google spreadsheet addon call Woocommerce server for an update, and if new order created its create new order at top of sheet. Each new order added to sheet during period of 1 minute from it creation, or on open Spreadsheet event. Its also possible use "Refresh orders" link at bottom, for refreshing them.</p>

<img src="https://easymanage.biz/wp-content/uploads/2019/10/addons-orders-open.png" />

<br>
Our support <a href="https://easymanage.biz/index.php/forum/" target="_blank">forum</a>
