[dxmoto.com](https://www.dxmoto.com) (Magento 2).

## How to deploy the static content
### On localhost
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
### On the production server
```
sudo service cron stop           
php7.2 bin/magento maintenance:enable      
rm -rf var/di var/generation generated/*
php7.2 bin/magento setup:upgrade
php7.2 bin/magento cache:enable
php7.2 bin/magento setup:di:compile
php7.2 bin/magento cache:clean
rm -rf pub/static/*
php7.2 bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
php7.2 bin/magento setup:static-content:deploy \
	--area frontend \
	--theme Infortis/ultimo \
	-f en_US
php7.2 bin/magento cache:clean
php7.2 bin/magento maintenance:disable
sudo service cron start
rm -rf var/log/*
```

## How to restart services on the production server
```
service cron restart
service elasticsearch restart
service mysql restart
service nginx restart
service php7.2-fpm restart
service postfix restart
``` 

## Magento reindexing
```
php7.2 bin/magento indexer:reindex
```

## How do I upgrade my packages in `dxmoto.com` on the production server
```                 
sudo service cron stop           
php7.2 bin/magento maintenance:enable      
php7.2 /usr/local/bin/composer remove dxmoto/core
rm -rf composer.lock
php7.2 /usr/local/bin/composer clear-cache
php7.2 /usr/local/bin/composer require dxmoto/core:*
rm -rf var/di var/generation generated/*
php7.2 bin/magento setup:upgrade
php7.2 bin/magento cache:enable
php7.2 bin/magento setup:di:compile
php7.2 bin/magento cache:clean
rm -rf pub/static/*
php7.2 bin/magento setup:static-content:deploy \
	--area adminhtml \
	--theme Magento/backend \
	-f en_US
php7.2 bin/magento setup:static-content:deploy \
	--area frontend \
	--theme Infortis/ultimo \
	-f en_US
php7.2 bin/magento cache:clean
php7.2 bin/magento maintenance:disable
sudo service cron start
rm -rf var/log/*
```