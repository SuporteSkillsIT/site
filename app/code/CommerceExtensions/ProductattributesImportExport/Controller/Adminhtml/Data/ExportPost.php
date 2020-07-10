<?php
/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

class ExportPost extends \CommerceExtensions\ProductattributesImportExport\Controller\Adminhtml\Data
{
    /**
     * Export action from import/export product attributes
     *
     * @return ResponseInterface
     */
	
    public function execute()
    {
        $data = new \Magento\Framework\DataObject();
		$params = $this->getRequest()->getParams();
		$_resource = $this->_objectManager->create('Magento\Framework\App\ResourceConnection');
		$connection = $_resource->getConnection();
		$recordlimit = "999999999999";
		$storeID = "0";
		
		if($params['export_delimiter'] != "") {
			$delimiter = $params['export_delimiter'];
		} else {
			$delimiter = ",";
		}
		if($params['export_enclose'] != "") {
			$enclose = $params['export_enclose'];
		} else {
			$enclose = "\"";
		}
		
		//Entity Type ID
		if($params['entitytypeid'] !="") { $EntityTypeId = $params['entitytypeid']; }
		
		//get/set delimiters for use on export
		 if($params['attribute_options_delimiter'] !="") {
			$attribute_options_delimiter = $params['attribute_options_delimiter'];
		 } else {
			$attribute_options_delimiter = "|";
		 }
		 if($params['attribute_options_value_delimiter'] !="") {
			$attribute_options_values_delimiter = $params['attribute_options_value_delimiter'];
		 } else {
			$attribute_options_values_delimiter = ",";
		 }
        /** start csv content and set template */
        $headers = new \Magento\Framework\DataObject(
            [
                'EntityTypeId' => __('EntityTypeId'),
                'attribute_set' => __('attribute_set'),
                'attribute_name' => __('attribute_name'),
                'attribute_group_name' => __('attribute_group_name'),
                'is_global' => __('is_global'),
                'is_user_defined' => __('is_user_defined'),
                'is_filterable' => __('is_filterable'),
                'is_visible' => __('is_visible'),
                'is_required' => __('is_required'),
                'is_visible_on_front' => __('is_visible_on_front'),
                'is_searchable' => __('is_searchable'),
                'is_unique' => __('is_unique'),
                'frontend_class' => __('frontend_class'),
                'is_visible_in_advanced_search' => __('is_visible_in_advanced_search'),
                'is_comparable' => __('is_comparable'),
                'is_filterable_in_search' => __('is_filterable_in_search'),
                'is_used_for_price_rules' => __('is_used_for_price_rules'),
                'is_used_for_promo_rules' => __('is_used_for_promo_rules'),
                'sort_order' => __('sort_order'),
                'position' => __('position'),
                'is_html_allowed_on_front' => __('is_html_allowed_on_front'),
                'used_in_product_listing' => __('used_in_product_listing'),
                'used_for_sort_by' => __('used_for_sort_by'),
                'frontend_input' => __('frontend_input'),
                'backend_type' => __('backend_type'),
                'frontend_label' => __('frontend_label'),
                'default_value' => __('default_value'),
                'apply_to' => __('apply_to'),
                'is_wysiwyg_enabled' => __('is_wysiwyg_enabled'),
                'is_required_in_admin_store' => __('is_required_in_admin_store'),
                'is_used_in_grid' => __('is_used_in_grid'),
                'is_visible_in_grid' => __('is_visible_in_grid'),
                'is_filterable_in_grid' => __('is_filterable_in_grid'),
                'search_weight' => __('search_weight'),
                'additional_data' => __('additional_data'),
                'attribute_options' => __('attribute_options'),
                'attribute_options_swatch' => __('attribute_options_swatch')
            ]
        );

$template=''.$enclose.'{{EntityTypeId}}'.$enclose.''.$delimiter.''.$enclose.'{{attribute_set}}'.$enclose.''.$delimiter.''.$enclose.'{{attribute_name}}'.$enclose.''.$delimiter.''.$enclose.'{{attribute_group_name}}'.$enclose.''.$delimiter.''.$enclose.'{{is_global}}'.$enclose.''.$delimiter.''.$enclose.'{{is_user_defined}}'.$enclose.''.$delimiter.''.$enclose.'{{is_filterable}}'.$enclose.''.$delimiter.''.$enclose.'{{is_visible}}'.$enclose.''.$delimiter.''.$enclose.'{{is_required}}'.$enclose.''.$delimiter.''.$enclose.'{{is_visible_on_front}}'.$enclose.''.$delimiter.''.$enclose.'{{is_searchable}}'.$enclose.''.$delimiter.''.$enclose.'{{is_unique}}'.$enclose.''.$delimiter.''.$enclose.'{{frontend_class}}'.$enclose.''.$delimiter.''.$enclose.'{{is_visible_in_advanced_search}}'.$enclose.''.$delimiter.''.$enclose.'{{is_comparable}}'.$enclose.''.$delimiter.''.$enclose.'{{is_filterable_in_search}}'.$enclose.''.$delimiter.''.$enclose.'{{is_used_for_price_rules}}'.$enclose.''.$delimiter.''.$enclose.'{{is_used_for_promo_rules}}'.$enclose.''.$delimiter.''.$enclose.'{{sort_order}}'.$enclose.''.$delimiter.''.$enclose.'{{position}}'.$enclose.''.$delimiter.''.$enclose.'{{is_html_allowed_on_front}}'.$enclose.''.$delimiter.''.$enclose.'{{used_in_product_listing}}'.$enclose.''.$delimiter.''.$enclose.'{{used_for_sort_by}}'.$enclose.''.$delimiter.''.$enclose.'{{frontend_input}}'.$enclose.''.$delimiter.''.$enclose.'{{backend_type}}'.$enclose.''.$delimiter.''.$enclose.'{{frontend_label}}'.$enclose.''.$delimiter.''.$enclose.'{{default_value}}'.$enclose.''.$delimiter.''.$enclose.'{{apply_to}}'.$enclose.''.$delimiter.''.$enclose.'{{is_wysiwyg_enabled}}'.$enclose.''.$delimiter.''.$enclose.'{{is_required_in_admin_store}}'.$enclose.''.$delimiter.''.$enclose.'{{is_used_in_grid}}'.$enclose.''.$delimiter.''.$enclose.'{{is_visible_in_grid}}'.$enclose.''.$delimiter.''.$enclose.'{{is_filterable_in_grid}}'.$enclose.''.$delimiter.''.$enclose.'{{search_weight}}'.$enclose.''.$delimiter.''.$enclose.'{{additional_data}}'.$enclose.''.$delimiter.''.$enclose.'{{attribute_options}}'.$enclose.''.$delimiter.''.$enclose.'{{attribute_options_swatch}}'.$enclose.'';
		
        $content = $headers->toString($template);
        $storeTemplate = [];
        $content .= "\n";
		
		
		$_eav_attribute = $_resource->getTableName('eav_attribute');
		$_eav_entity_attribute = $_resource->getTableName('eav_entity_attribute');
		$_eav_attribute_set = $_resource->getTableName('eav_attribute_set');
		$_eav_attribute_group = $_resource->getTableName('eav_attribute_group');
		$_catalog_eav_attribute = $_resource->getTableName('catalog_eav_attribute');
		$_eav_attribute_label = $_resource->getTableName('eav_attribute_label');
		$_eav_attribute_option = $_resource->getTableName('eav_attribute_option');
		$_eav_attribute_option_value = $_resource->getTableName('eav_attribute_option_value');
		$_eav_attribute_option_swatch = $_resource->getTableName('eav_attribute_option_swatch');
		
		if($params['attribute_set_filter_list'] !="") {
			$attribute_filter_list = $params['attribute_set_filter_list'];
			$finalsqlstringforattributecode = "";
			$attribute_code_data = explode(',', $attribute_filter_list);
			$counteritems=0;
			foreach($attribute_code_data as $single_attribute_code) {
				if($counteritems==0) {
					$finalsqlstringforattributecode .=  "eav_attribute_set.attribute_set_name = '" . $single_attribute_code . "'";
				} else {
					$finalsqlstringforattributecode .=  " OR eav_attribute_set.attribute_set_name = '" . $single_attribute_code . "'";
				}
				$counteritems++;
			}
		 $select_qry = "SELECT ".$_eav_attribute.".*, ".$_eav_attribute_set.".*, ".$_eav_attribute_group.".*, ".$_catalog_eav_attribute.".* FROM ".$_eav_attribute." INNER JOIN ".$_eav_entity_attribute." ON ".$_eav_entity_attribute.".entity_type_id = ".$_eav_attribute.".entity_type_id AND ".$_eav_entity_attribute.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_catalog_eav_attribute." ON ".$_catalog_eav_attribute.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_eav_attribute_set." ON ".$_eav_attribute_set.".attribute_set_id = ".$_eav_entity_attribute.".attribute_set_id INNER JOIN ".$_eav_attribute_group." ON ".$_eav_attribute_group.".attribute_group_id = ".$_eav_entity_attribute.".attribute_group_id WHERE ".$_eav_attribute.".entity_type_id = '".$EntityTypeId."' AND ".$finalsqlstringforattributecode." LIMIT ".$recordlimit."";
		 
		 } else {
		 
		 $select_qry = "SELECT ".$_eav_attribute.".*, ".$_eav_attribute_set.".*, ".$_eav_attribute_group." .*, ".$_catalog_eav_attribute.".* FROM ".$_eav_attribute." INNER JOIN ".$_eav_entity_attribute." ON ".$_eav_entity_attribute.".entity_type_id = ".$_eav_attribute.".entity_type_id AND ".$_eav_entity_attribute.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_catalog_eav_attribute." ON ".$_catalog_eav_attribute.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_eav_attribute_set." ON ".$_eav_attribute_set.".attribute_set_id = ".$_eav_entity_attribute.".attribute_set_id INNER JOIN ".$_eav_attribute_group." ON ".$_eav_attribute_group.".attribute_group_id = ".$_eav_entity_attribute.".attribute_group_id WHERE ".$_eav_attribute.".entity_type_id = '".$EntityTypeId."' LIMIT ".$recordlimit."";
		 
		 }
		 
		$attributesCollection = $connection->fetchAll($select_qry);
		foreach ($attributesCollection as $row)
		{
				 #print_r($row);
				 $storeTemplate["EntityTypeId"] = $row['entity_type_id'];
				 $storeTemplate["attribute_set"] = $row['attribute_set_name'];
				 $storeTemplate["attribute_name"] = $row['attribute_code'];
				 $storeTemplate["attribute_group_name"] = $row['attribute_group_name'];
				 $storeTemplate["is_global"] = $row['is_global'];
				 $storeTemplate["is_user_defined"] = $row['is_user_defined'];
				 $storeTemplate["is_filterable"] = $row['is_filterable'];
				 $storeTemplate["is_visible"] = $row['is_visible'];
				 $storeTemplate["is_required"] = $row['is_required'];
				 $storeTemplate["is_visible_on_front"] = $row['is_visible_on_front'];
				 $storeTemplate["is_searchable"] = $row['is_searchable'];
				 $storeTemplate["is_unique"] = $row['is_unique'];
				 //latest additional fields (values = 0: NO / 1: YES)
				 $storeTemplate["frontend_class"] = $row['frontend_class'];
				 $storeTemplate["is_visible_in_advanced_search"] = $row['is_visible_in_advanced_search'];
				 $storeTemplate["is_comparable"] = $row['is_comparable'];
				 $storeTemplate["is_filterable_in_search"] = $row['is_filterable_in_search'];
				 $storeTemplate["is_used_for_price_rules"] = $row['is_used_for_price_rules'];
				 $storeTemplate["is_used_for_promo_rules"] = $row['is_used_for_promo_rules'];
				 $storeTemplate["sort_order"] = $row['sort_order'];
				 $storeTemplate["position"] = $row['position'];
				 $storeTemplate["is_html_allowed_on_front"] = $row['is_html_allowed_on_front'];
				 $storeTemplate["used_in_product_listing"] = $row['used_in_product_listing'];
				 $storeTemplate["used_for_sort_by"] = $row['used_for_sort_by'];
				 //frontend_input and backend_type #[useable types:]# decimal,int,select,text
				 $storeTemplate["frontend_input"] = $row['frontend_input'];
				 $storeTemplate["backend_type"] = $row['backend_type'];
				 
				 if($row['frontend_label']!="") {	
						  $finalproductlabelattributes="";
						  $finalproductlabelattributes .=  "0:".$row['frontend_label'] . "|";
							$select_attribute_labels_qry = "SELECT store_id, value FROM ".$_eav_attribute_label." WHERE attribute_id = '".$row['attribute_id']."'";
							$attributelabelrows = $connection->fetchAll($select_attribute_labels_qry);
							foreach($attributelabelrows as $attributelabeldata) { 
								 $finalproductlabelattributes .= $attributelabeldata["store_id"] . ":" . $attributelabeldata["value"] . "|";
							}		
						$storeTemplate["frontend_label"] = substr_replace($finalproductlabelattributes,"",-1);	
				 } else {
						$storeTemplate["frontend_label"] = $row['frontend_label'];
				 }
				 $storeTemplate["default_value"] = $row['default_value'];
				 //apply_to #[OPTIONAL usable types:]# simple,grouped,configurable,virtual,downloadable,bundle
				 $storeTemplate["apply_to"] = $row['apply_to'];
				 $storeTemplate["is_wysiwyg_enabled"] = $row['is_wysiwyg_enabled'];
				 $storeTemplate["is_required_in_admin_store"] = $row['is_required_in_admin_store'];
				 $storeTemplate["is_used_in_grid"] = $row['is_used_in_grid'];
				 $storeTemplate["is_visible_in_grid"] = $row['is_visible_in_grid'];
				 $storeTemplate["is_filterable_in_grid"] = $row['is_filterable_in_grid'];
				 $storeTemplate["search_weight"] = $row['search_weight'];
				 $storeTemplate["additional_data"] = $row['additional_data'];
				
				 //this will get all options for a attribute (dropdown/multi select/etc)
				 $finalproductattributes="";
				 if($storeID != "0") {
				 $select_attribute_options_qry = "SELECT ".$_eav_attribute.".*, ".$_eav_attribute_option.".sort_order, ".$_eav_attribute_option_value.".* FROM ".$_eav_attribute." INNER JOIN ".$_eav_attribute_option." ON ".$_eav_attribute_option.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_eav_attribute_option_value." ON ".$_eav_attribute_option_value.".option_id = ".$_eav_attribute_option.".option_id WHERE ".$_eav_attribute.".attribute_id = '".$row['attribute_id']."' AND eav_attribute_option_value.store_id = '".$storeID."'";
				 } else {
				 $select_attribute_options_qry = "SELECT ".$_eav_attribute.".*, ".$_eav_attribute_option.".sort_order, ".$_eav_attribute_option_value.".* FROM ".$_eav_attribute." INNER JOIN ".$_eav_attribute_option." ON ".$_eav_attribute_option.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_eav_attribute_option_value." ON ".$_eav_attribute_option_value.".option_id = ".$_eav_attribute_option.".option_id WHERE ".$_eav_attribute.".attribute_id = '".$row['attribute_id']."'";
				 }
					
				$attributeoptionrows = $connection->fetchAll($select_attribute_options_qry);
				foreach($attributeoptionrows as $attributeoptiondata)
				 {
				  if(!isset($temp) || $temp == $attributeoptiondata["option_id"]) {		
						if($params['export_w_sort_order'] != "true") {
							$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . $attribute_options_values_delimiter;
						} else {
							$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . ":" . $attributeoptiondata["sort_order"] . $attribute_options_values_delimiter;
						}	
					}	else { 
						$finalproductattributes = substr_replace($finalproductattributes,"",-1);
						$finalproductattributes .= $attribute_options_delimiter;
						if($params['export_w_sort_order'] != "true") {
							$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . $attribute_options_values_delimiter;	
						} else {
							$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . ":" . $attributeoptiondata["sort_order"] . $attribute_options_values_delimiter;	
						}
					}
					$temp = $attributeoptiondata["option_id"];
				 }
				 $finalproductattributes = substr_replace($finalproductattributes,"",-1);
				 if($finalproductattributes !="") { $finalproductattributes .= $attribute_options_delimiter; }
				 $finalproductattributes = ltrim($finalproductattributes, $attribute_options_delimiter);
				 $finalproductattributes = rtrim($finalproductattributes, $attribute_options_delimiter);
						
				 $storeTemplate["attribute_options"] = $finalproductattributes;	
				 
				 
				 //this will get all swatch options for a attribute (dropdown/multi select/etc)
				 $finalproductattributes="";
				 if($storeID != "0") {
				 $select_attribute_options_qry = "SELECT ".$_eav_attribute.".*, ".$_eav_attribute_option_swatch.".* FROM ".$_eav_attribute." INNER JOIN ".$_eav_attribute_option." ON ".$_eav_attribute_option.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_eav_attribute_option_swatch." ON ".$_eav_attribute_option_swatch.".option_id = ".$_eav_attribute_option.".option_id WHERE ".$_eav_attribute.".attribute_id = '".$row['attribute_id']."' AND ".$_eav_attribute_option_swatch.".store_id = '".$storeID."'";
				 } else {
				 $select_attribute_options_qry = "SELECT ".$_eav_attribute.".*, ".$_eav_attribute_option_swatch.".* FROM ".$_eav_attribute." INNER JOIN ".$_eav_attribute_option." ON ".$_eav_attribute_option.".attribute_id = ".$_eav_attribute.".attribute_id INNER JOIN ".$_eav_attribute_option_swatch." ON ".$_eav_attribute_option_swatch.".option_id = ".$_eav_attribute_option.".option_id WHERE ".$_eav_attribute.".attribute_id = '".$row['attribute_id']."'";
				 }
					
				$attributeoptionrows = $connection->fetchAll($select_attribute_options_qry);
				foreach($attributeoptionrows as $attributeoptiondata)
				 {
				    if(!isset($temp) || $temp == $attributeoptiondata["option_id"]) {		
						$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . ":" . $attributeoptiondata["type"] . $attribute_options_values_delimiter;
					} else { 
						$finalproductattributes = substr_replace($finalproductattributes,"",-1);
						$finalproductattributes .= $attribute_options_delimiter;
						$finalproductattributes .= $attributeoptiondata["store_id"] . ":" . $attributeoptiondata["value"] . ":" . $attributeoptiondata["type"] . $attribute_options_values_delimiter;	
					}
					$temp = $attributeoptiondata["option_id"];
				 }
				 $finalproductattributes = substr_replace($finalproductattributes,"",-1);
				 if($finalproductattributes !="") { $finalproductattributes .= $attribute_options_delimiter; }
				 $finalproductattributes = ltrim($finalproductattributes, $attribute_options_delimiter);
				 $finalproductattributes = rtrim($finalproductattributes, $attribute_options_delimiter);
						
				 $storeTemplate["attribute_options_swatch"] = $finalproductattributes;	
				 
				 $data->addData($storeTemplate);
				 $content .= $data->toString($template) . "\n";	
		}
		
		#exit;
        //$content .= $template . "\n";
        
        return $this->fileFactory->create('export_product_attributes.csv', $content, DirectoryList::VAR_DIR);
    }

    /**
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed(
            'CommerceExtensions_ProductattributesImportExport::import_export'
        );

    }
}
