# Introduction
The provided Plugin helps store merchants to redirect customers to the Paytm Payment Gateway when they choose Paytm as their payment method. After the customer has finished the transaction they are redirected back to an appropriate  page on the merchant site depending on the status of the transaction.

# Opencart Plugin
The Plugin consists of three folders – admin, catalog and system.
The admin folder consists of files that are responsible for Admin Panel configuration. Catalog has files that take care of the live site payment procedure. System folder has files that are utilized both by admin as well as catalog files.

# Installation and Configuration
  1. Download the plugin.
  2. Extract the file for the desired version of Opencart plug-in
  3. Extract all three folders- admin, catalog & systems. You will be prompted with the warning that you are to overwrite files, click yes to copy plug-in. Your original source code will not be changed due to this activity.
  4. Paste these folders into root folder of your Opencart website.
  5. Login to Admin panel
  6. Go to the Payments tab under Extensions.
  7. Enable the Paytm option
  8. Save the below configuration
      
      * Plugin status           - Enabled
      * Order Status            - Processing
      * Title                   - Paytm PG
      * Merchant ID             - Staging/Production MID provided by Paytm
      * Merchant Key            - Staging/Production Key provided by Paytm
      * Transaction URL         
        * Staging     - https://securegw-stage.paytm.in/theia/processTransaction
        * Production  - https://securegw.paytm.in/theia/processTransaction
      * Transaction Status URL  
        * Staging     - https://securegw-stage.paytm.in/merchant-status/getTxnStatus
        * Production  - https://securegw.paytm.in/merchant-status/getTxnStatus
      * Website Name            - Provided By Paytm
      * Callback URL            - Yes
      * Industry type           - Provided by Paytm
      * Transaction Status      - Enabled

  9. Your Opencart plug-in is now installed. You can accept payment through Paytm.

# In case of any query, please contact to Paytm.