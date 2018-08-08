# Introduction
The provided Plugin helps store merchants to redirect customers to the Paytm Payment Gateway when they choose Paytm as their payment method. After the customer has finished the transaction they are redirected back to an appropriate  page on the merchant site depending on the status of the transaction.

# Opencart Plugin
The Plugin consists of three folders – admin, catalog and system.
The admin folder consists of files that are responsible for Admin Panel configuration. Catalog has files that take care of the live site payment procedure. System folder has files that are utilized both by admin as well as catalog files.

# Installation and Configuration

  1. Download the plugin.
  2. Extract the file for the desired version of Opencart plug-in
  3. Extract all three folders- admin, catalog &amp; systems. You will be prompted with the warning that you are to overwrite files, click Yes to copy plug-in. Your original source code will not be changed due to this activity.
  4. Paste these folders into root folder of your Opencart website.
  5. Login to Admin panel
  6. Go to the Payments tab under Extensions.
  7. Enable the Paytm option
  8. Save the below configuration
      
      * Merchant ID             - Staging/Production MID provided by Paytm
      * Merchant Key            - Staging/Production key provided by Paytm
      * Website Name            - provided by Paytm
      * Industry type           - provided by Paytm
      * Transaction URL         
        * Staging     - https://securegw-stage.paytm.in/theia/processTransaction
        * Production  - https://securegw.paytm.in/theia/processTransaction
      * Transaction Status URL  
        * Staging     - https://securegw-stage.paytm.in/merchant-status/getTxnStatus
        * Production  - https://securegw.paytm.in/merchant-status/getTxnStatus
      * Custom Callback Url     - Disable
      * Callback Url            - customized callback url(this is visible when Custom Callback Url is Enable)
      * Order status            - Processing
      * Paytm payment           - Enabled

  9. Your Opencart plug-in is now installed. You can accept payment through Paytm.

# In case of any query, please contact to Paytm.