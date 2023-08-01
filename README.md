# Paytm Payment Gateway Extention for opencart
Contributors: integrationdevpaytm
Tags: Paytm, Paytm Payments, PayWithPaytm, Paytm Payment Extention, Paytm Payment Gateway
Requires Opencart
Requires at least: 2.x
Tested up to: 4.x
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

# Description

Welcome to the official Paytm Payment Gateway extention for Opencart. Paytm Payment Gateway is ideal for Opencart.it allows them to give their customers a seamless, super-fast checkout experience backed by cutting-edge payments technology that powers India’s largest payments platform. Accept payments from over 100+ payment sources including credit cards, debit cards, netbanking from 50+ banks (including HDFC & SBI), UPI, wallets and Buy-now-pay-later options. Here are a few reasons why Opencart merchants should choose Paytm Payment Gateway.  

# Compatibilities and Dependencies 

* Opencart v2.x or higher
* PHP v7.4.0 or higher
* Php-curl

# Features 

* Largest scale:  Preferred by 330M+ consumers in India.
* India’s most reliable PG: Trusted by India’s biggest online brands such as Uber, Flipkart, Zomato, Airtel, IRCTC, LIC and many more.  
* Industry best prices guaranteed: 2x more affordable than other payment gateways with 0% transaction fees on UPI & Rupay payments.
* Boost  conversions: Affordability options like EMI and Paytm Postpaid to boost conversions.
* Superior technology: Industry best success rates & 99.99% Up-time, Capable of supporting 3x more transactions per second than other payment gateways.
* Superfast next day settlements, even on holidays and weekends.
* Powerful dashboard: Get payment analytics at your fingerprints. Get insights by payment source and customer cohorts.
* Instant refunds: Initiate refunds seamlessly with just a click right from your Paytm for business dashboard. 

# Getting Started 

New to PaytmPG? Use this [link](https://dashboard.paytm.com) to create your Paytm for Business account and get access to exciting offers.

Before enabling the Paytm Payment Gateway on Opencart, make sure you have a registered business account with Paytm. Please visit - 
[Paytm Dashboard](https://dashboard.paytm.com) to sign-up

# Step-1: Generate your API keys with Paytm

To generate the API Key,
* Log into your [Dashboard](https://dashboard.paytm.com/).
* Select the API Keys under Developers on the left menu-bar.
* Select the mode for which you want to generate the API Key from the menu.
* Click Generate now to generate a key for the test mode and in case of live mode, first activate the account by submitting documents and then generate the key by clicking the Generate now button.
* You will get the merchant ID and merchant key in response to the above. Please make a note of these to be used further.

Note: You have to generate separate API Keys for the test and live modes. No money is deducted from your account in test mode.
MID and merchant keys generation may take few minutes. In case you do not see these details, please logout and login after 5 minutes. Proceed now to generate these keys.

# Step-2: Extention Installation 

To install an extension in OpenCart, follow these steps:

1. **Download the Extension**: Visit the OpenCart marketplace or the developer's website and download the extension you want to install. The extension should be in a ZIP file format.

2. **Access OpenCart Admin Panel**: Log in to your OpenCart store's admin panel using your credentials.

3. **Navigate to Extension Installer**: In the admin dashboard, go to the "Extensions" menu on the left-hand side and click on "Extension Installer." This will take you to the extension installation page.

4. **Upload the Extension**: On the Extension Installer page, click the "Upload" button. A new window will appear, allowing you to browse your computer for the downloaded ZIP file of the extension.

5. **Select and Upload the ZIP File**: Find the extension's ZIP file on your computer, select it, and click the "Open" or "Upload" button in the file browser to begin the upload process.

6. **Install the Extension**: Once the upload is complete, you will see a success message confirming the extension has been uploaded successfully. Click on the "Continue" button to proceed.

7. **Navigate to Extension Extensions**: Now, go back to the admin dashboard and click on "Extensions" in the left-hand menu, then select "Extensions" from the dropdown.

8. **Choose the Extension Type**: In the Extensions page, you will see a list of different extension types (e.g., Modules, Payments, Shipping, etc.). Choose the appropriate category based on the type of extension you just installed.

9. **Find and Install the Extension**: Look for the extension you uploaded in the list and click the green "Install" button next to it. This will install the extension on your OpenCart store.

10. **Configure the Extension (If Required)**: After installing the extension, you may need to configure its settings. Click on the "Edit" button or navigate to the "Settings" section for the specific extension to set it up according to your preferences.

11. **Verify Installation**: Once configured, test the extension on your store to make sure it is functioning correctly.

12. **Clear Cache (If Necessary)**: If your extension adds new features or modifies existing ones, it's a good idea to clear the cache to ensure the changes take effect immediately. You can do this by going to the "Extensions" menu and clicking on "Modification," then click the blue refresh button at the top right corner.

That's it! The extension should now be successfully installed and ready to use in your OpenCart store. Make sure to follow any additional instructions provided by the extension's developer if necessary.

# Step-3: Paytm Payment Configuration 

* Log into your Opencart admin and activate the Paytm Payment Gateway extention in Opencart  side bar extention menu.
* Click on Paytm Payment Gateway to edit the settings. If you do not see Paytm Payment Gateway in the list at the top of the screen make sure you have install the extention from the extention installer.
* Fill in the following credentials.
	* Enable - Enable Status
	* Merchant Identifier - Staging/Production MID provided by Paytm
	* Secret Key - Staging/Production Key provided by Paytm
	* Website Name - Provided by Paytm
	* Environment - Select environment type

Your Paytm payment gateway is enabled. Now you can accept payment through Paytm.
In case of any issues with integration, please [get in touch](https://business.paytm.com/contact-us#developer).

#  Developer Docs: 
https://business.paytm.com/docs/opencart/




