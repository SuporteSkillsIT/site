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
	-f en_GB
```