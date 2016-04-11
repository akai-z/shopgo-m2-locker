Locker
======


## Synopsis

A module that allows to lock/unlock Magento 2 website.

## Overview

Locker module adds CLI commands that allow users to lock/unlock their Magento 2 websites.
It also has the ability to listen to specific Amazon SNS module notifications to lock/unlock stores.

## Installation

Below, you can find two ways to install the locker module.

### 1. Install via Composer (Recommended)
First, make sure that Composer is installed: https://getcomposer.org/doc/00-intro.md

Make sure that Packagist repository is not disabled.

Run Composer require to install the module:

    php <your Composer install dir>/composer.phar require shopgo/locker:*

### 2. Clone the locker repository
Clone the <a href="https://github.com/shopgo-magento2/locker" target="_blank">locker</a> repository using either the HTTPS or SSH protocols.

### 2.1. Copy the code
Create a directory for the locker module and copy the cloned repository contents to it:

    mkdir -p <your Magento install dir>/app/code/ShopGo/Locker
    cp -R <locker clone dir>/* <your Magento install dir>/app/code/ShopGo/Locker

### Update the Magento database and schema
If you added the module to an existing Magento installation, run the following command:

    php <your Magento install dir>/bin/magento setup:upgrade

### Verify the module is installed and enabled
Enter the following command:

    php <your Magento install dir>/bin/magento module:status

The following confirms you installed the module correctly, and that it's enabled:

    example
        List of enabled modules:
        ...
        ShopGo_Locker
        ...

## Tests

TODO

## Contributors

Ammar (<ammar@shopgo.me>)

## License

[Open Source License](LICENSE.txt)
