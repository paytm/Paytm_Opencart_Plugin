# Introduction
The provided Plugin helps store merchants to redirect customers to the Paytm Payment Gateway when they choose Paytm as their payment method. After the customer has finished the transaction they are redirected back to an appropriate  page on the merchant site depending on the status of the transaction.

# Opencart Plugin
The Plugin consists of three folders – admin, catalog and system.
The admin folder consists of files that are responsible for Admin Panel configuration. Catalog has files that take care of the live site payment procedure. System folder has files that are utilized both by admin as well as catalog files.

# Installation and Configuration
 1. Copy the three (admin,catalog and system) folders to the root folder of your Opencart website. 
 2. You will be prompted with the warning that you are about to overwrite files. Click yes to copy the Plugin files without disturbing the original source files or the directory structure.
 3. Log in to your admin panel and go to Extensions->Payments on the dashboard. Paytm PG is listed as one of the payment methods. Install it by clicking install and then press Edit to configure the Payment Gateway. Enter your Merchant ID,Merchant Key,Website Name and Industry type. Make sure these details are the same as have been configures on the Paytm PG site. Select your Order Status(this is the status assigned to an order that has successfully completed payment using Paytm PG) Select 'Enabled' as the Plugin status. Choose whether you would like your transaction status to be checked when the callback url is called.This option provides additional security.Click save to configure the Plugin.
 4. Paytm PG should now get reflected as a payment method on the Checkout Page.

# Source Code details
## ADMIN folder
It has the following files
 1. admin->controller->extension->payment->paytm.php (Controller for the admin panel Paytm page)
 2. admin->language->en-gb->extension->payment->paytm.php (String constants used on the page)
 3. admin->view->template->extension->payment->paytm.tpl (Template file for the admin page)


## CATALOG folder
It has the following files
 1. catalog->controller->extension->payment->paytm.php (Controller for the payment gateway on checkout page)
 2. catalog->language->en-gb->extension->payment->paytm.php (String Constants for Catalog pages)
 3. catalog->model->extension->payment->paytm.php (Model for the PG procedure.Any future direct interactions with database are to be added here)
 4. catalog->view->theme->default->template->extension->payment->(paytm.tpl,paytm_failure.tpl,paytm_success.tpl)

## SYSTEM folder
It has the following files
 1. catalog->system->encdec_paytm.php
 2. catalog->system->paytm_constants.php

# Paytm PG URL Details
	staging	
		Transaction URL             => https://securegw-stage.paytm.in/theia/processTransaction
		Transaction Status Url      => https://securegw-stage.paytm.in/merchant-status/getTxnStatus

	Production
		Transaction URL             => https://securegw.paytm.in/theia/processTransaction
		Transaction Status Url      => https://securegw.paytm.in/merchant-status/getTxnStatus
