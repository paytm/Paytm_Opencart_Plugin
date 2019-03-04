# Paytm Payment plugin for Opencart version 2.0.x, 2.1.x & 2.2.x

## Table of Contents
- [Overview][0]<br/>
- [Prerequisites][1] <br />
- [Installation][2]<br />
- [Configuration][3]<br />
    - [Configuration Parameters][3.1]

## Overview
This plugin adds the Paytm payment option on checkout and enables you to accept payment through Paytm (Wallet/Credit Card/Debit Card/Net Banking/UPI)

## Prerequisites

* PHP >= 5.3
* PHP extensions are required - [cURL][2_link]


## Installation

### Using Installer
1. Download [**paytm_v2.0.ocmod.zip**][1_link]
1. Go to the Opencart administration page [http://www.example.com/admin].
1. Go to **Extensions** > **Extension Installer**.
1. Click on **Upload** button, Select the downloaded file (STEP 1) then Click on **Continue** button and wait for process complete
1. Go to **Extensions** > **Payments**
1. In payment option list, find  **Paytm Payments** and click on **Install** button.

### Using FTP
1. Copy all folders (admin, catalog & system) from upload diectory, and paste them into your Opencart **root** directory on server 
1. Go to the Opencart administration page [http://www.example.com/admin].
1. Go to **Extensions** > **Payments**.
1. In payment option list, find  **Paytm Payments** and click on **Install** button.
1. After finishing installtion, proceed to configuration.

**See Installation**: https://www.youtube.com/watch?v=ccXjwA5pKfA

## Configuration

1. Go to the Opencart administration page [http://www.example.com/admin].
1. Go to **Extensions** > **Payments**.
1. In payment option list, find  **Paytm Payments** and click on **Edit** button.

### Configuration Parameters

The below table describes the configurable parameters and helps you to set their values.


| Parameter | Type | Description |
|:---------:|:------:|:-----------:|
|Merchant ID|ALPHANUMERIC|Enter your Merchant ID provided by Paytm|
|Merchant Key|ALPHANUMERIC|Enter your Merchant Key provided by Paytm|
|Website Name|ALPHANUMERIC|Enter your Website Name provded by Paytm|
|Industry Type|ALPHANUMERIC|Eg. Retail, Entertainment etc.|
|Transaction URL|URL|[See here][t_link] |
|Transaction Status URL|URL|[See here][ts_link]|
|Custom Callback URL|Enabled/Disabled|Enable this only if you want to modify default callback URL|
|Callback URL|URL|On completion of transaction, Paytm will rediret to this URL with response parameters.|
|Total|Amount|Minimal amount require to make this payment method active.|
|Geo Zone|Zone List|Geo Zone for payment method to be active.|
|Status|Enabled/Disabled|Keep this **Enabled** to active.|
|Sort Order|Positive Number|Set ordering in the payment methods list.|
|Order Success Status|Order Status List|Order status that will set for Successful Payment|
|Order Failed Status|Order Status List|Order status that will set for Failed Payment|

### Transaction URL
* Staging     - https://securegw-stage.paytm.in/theia/processTransaction
* Production  - https://securegw.paytm.in/theia/processTransaction
### Transaction Status URL
* Staging     - https://securegw-stage.paytm.in/merchant-status/getTxnStatus
* Production  - https://securegw.paytm.in/merchant-status/getTxnStatus

## In case of any query, please contact to Paytm.


<!--LINKS-->

<!--topic urls:-->
[0]: #overview
[1]: #prerequisites
[2]: #installation
[3]: #configuration
[3.1]: #configuration-parameters
[t_link]: #transaction-url
[ts_link]: #transaction-status-url


<!--external links:-->
[1_link]: paytm_v2.x.ocmod.zip
[2_link]: http://php.net/manual/en/book.curl.php

<!--images:-->