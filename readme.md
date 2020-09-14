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

## How to restart services in `dxmoto.mage2.pro`
```
sudo service php7.1-fpm restart
``` 

## How do I upgrade my packages in `dxmoto.mage2.pro` 
```                 
sudo service cron stop             
bin/magento maintenance:enable      
composer remove dxmoto/core
rm -rf composer.lock
composer clear-cache
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
rm -rf var/log/*
```

## How do I upgrade my packages in `www1.dxmoto.com` 
```                          
php7.1 bin/magento maintenance:enable      
php7.1 /usr/local/bin/composer remove dxmoto/core
rm -rf composer.lock
php7.1 /usr/local/bin/composer clear-cache
php7.1 /usr/local/bin/composer require dxmoto/core:*
php7.1 bin/magento setup:upgrade
php7.1 bin/magento cache:enable
rm -rf var/di var/generation generated/*
php7.1 bin/magento setup:di:compile
php7.1 bin/magento cache:clean
rm -rf pub/static/*
php7.1 bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
php7.1 bin/magento setup:static-content:deploy \
	--area frontend \
	--theme Infortis/ultimo \
	-f en_US
php7.1 bin/magento cache:clean
php7.1 bin/magento maintenance:disable
rm -rf var/log/*
```