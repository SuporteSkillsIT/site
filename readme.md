[dxmoto.com](https://www.dxmoto.com) (Magento 2).

## How to deploy the static content
```posh
bin/magento cache:clean
rm -rf pub/static/*
bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
bin/magento setup:static-content:deploy \
	--area frontend \
	--theme Infortis/ultimo \
	-f en_US
```

## How do I upgrade my packages in `dxmoto.mage2.pro` 
```                 
sudo service cron stop             
bin/magento maintenance:enable      
composer remove dxmoto/core
composer remove mage2pro/core
rm -rf composer.lock
composer clear-cache
composer require mage2pro/core:*
composer require dxmoto/core:*
bin/magento setup:upgrade
bin/magento cache:enable
rm -rf var/di var/generation generated/*
bin/magento setup:di:compile
bin/magento cache:clean
rm -rf pub/static/*
bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
bin/magento setup:static-content:deploy \
	--area frontend \
	--theme Infortis/ultimo \
	-f en_US
bin/magento cache:clean
bin/magento maintenance:disable
sudo service cron start
```