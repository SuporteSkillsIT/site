<?php

/**
 * Copyright © 2015 CommerceExtensions. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace CommerceExtensions\ProductattributesImportExport\Model\Data;

use Magento\Framework\App\ResourceConnection;

/**
 *  CSV Import Handler
 */
 
class CsvImportHandler
{ 
	/**
     * Resource instance
     *
     * @var Resource
     */
    protected $_resource;
	
    protected $_productFactory;
	
    protected $_attributeModel;
	
    protected $_attributeResourceModel;
	
    protected $_attributeSet;
	
    protected $_attSetFactory;
	
    protected $_swatchCollectionFactory;
	
    protected $_attributeOptionCollection;
	
    protected $_attributeGroupCollection;

    /**
     * CSV Processor
     *
     * @var \Magento\Framework\File\Csv
     */
    protected $csvProcessor;
	 
	protected $isSwatchExists;
	

    /**
     * @param \Magento\Store\Model\Resource\Store\Collection $storeCollection
     * @param \Magento\Catalog\Model\ProductFactory $productFactory
     * @param \Magento\Customer\Model\CustomerFactory $customerFactory
     * @param \Magento\Framework\File\Csv $csvProcessor
     */

    public function __construct(
        ResourceConnection $resource,
		\Magento\Catalog\Model\ResourceModel\Product $productModel,
        \Magento\Catalog\Model\ProductFactory $productFactory,
		\Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeResourceModel,
		\Magento\Eav\Model\Entity\Attribute $attributeModel,
		\Magento\Eav\Model\Entity\Attribute\Set $attributeSet,
		\Magento\Eav\Model\Entity\Attribute\SetFactory $attSetFactory,
        \Magento\Swatches\Model\ResourceModel\Swatch\CollectionFactory $collectionFactory,
		\Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection $attributeOptionCollection,
		\Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory $attributeGroupCollection,
        \Magento\Framework\File\Csv $csvProcessor
    ) {
        // prevent admin store from loading
        $this->_resource = $resource;
        $this->_productModel = $productModel;
        $this->_productFactory = $productFactory;
        $this->_attributeModel = $attributeModel;
        $this->_attributeResourceModel = $attributeResourceModel;
        $this->_attributeSet = $attributeSet;
        $this->_attSetFactory = $attSetFactory;
        $this->_swatchCollectionFactory = $collectionFactory;
        $this->_attributeOptionCollection = $attributeOptionCollection;
        $this->_attributeGroupCollection = $attributeGroupCollection;
        $this->csvProcessor = $csvProcessor;
    }

    /**
     * Retrieve a list of fields required for CSV file (order is important!)
     *
     * @return array
     */
    public function getRequiredCsvFields()
    {
        // indexes are specified for clarity, they are used during import
        return [
            0 => __('EntityTypeId'),
            1 => __('attribute_set'),
            2 => __('attribute_name'),
            3 => __('attribute_group_name'),
            4 => __('is_global'),
            5 => __('is_user_defined'),
            6 => __('is_filterable'),
            7 => __('is_visible'),
            8 => __('is_required'),
            9 => __('is_visible_on_front'),
            10 => __('is_searchable'),
            11 => __('is_unique'),
            12 => __('frontend_class'),
            13 => __('is_visible_in_advanced_search'),
            14 => __('is_comparable'),
            15 => __('is_filterable_in_search'),
            16 => __('is_used_for_price_rules'),
            17 => __('is_used_for_promo_rules'),
            18 => __('sort_order'),
            19 => __('position'),
            20 => __('frontend_input'),
            21 => __('backend_type'),
            22 => __('frontend_label'),
            23 => __('default_value'),
            24 => __('apply_to'),
            25 => __('is_wysiwyg_enabled'),
            26 => __('is_required_in_admin_store'),
            27 => __('is_used_in_grid'),
            28 => __('is_visible_in_grid'),
            29 => __('is_filterable_in_grid'),
            30 => __('search_weight'),
            31 => __('additional_data'),
            32 => __('attribute_options'),
            33 => __('attribute_options_swatch')
        ];
    }

    /**
     * Import Data from CSV file
     *
     * @param array $file file info retrieved from $_FILES array
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function importFromCsvFile($file, $params)
    {
        if (!isset($file['tmp_name'])) {
            throw new \Magento\Framework\Exception\LocalizedException(__('Invalid file upload attempt.'));
        }
		
		if($params['import_delimiter'] != "") { $this->csvProcessor->setDelimiter($params['import_delimiter']); }
		if($params['import_enclose'] != "") { $this->csvProcessor->setEnclosure($params['import_enclose']); }
		
        $RawData = $this->csvProcessor->getData($file['tmp_name']);
        // first row of file represents headers
        $fileFields = $RawData[0];
        $ratesData = $this->_filterData($fileFields, $RawData);
		foreach ($ratesData as $dataRow) {
            $this->_importProductattributes($dataRow, $params);
        }
    }


    /**
     * Filter data (i.e. unset all invalid fields and check consistency)
     *
     * @param array $RawDataHeader
     * @param array $RawData
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _filterData(array $RawDataHeader, array $RawData)
    {
		$rowCount=0;
		$RawDataRows = array();
		
        foreach ($RawData as $rowIndex => $dataRow) {
			// skip headers
            if ($rowIndex == 0) {
                continue;
            }
            // skip empty rows
            if (count($dataRow) <= 1) {
                unset($RawData[$rowIndex]);
                continue;
            }
			/* we take rows from [0] = > value to [website] = base */
            if ($rowIndex > 0) {
				foreach ($dataRow as $rowIndex => $dataRowNew) {
					$RawDataRows[$rowCount][$RawDataHeader[$rowIndex]] = $dataRowNew;
				}
			}
			$rowCount++;
        }
        return $RawDataRows;
    }


	public function loadSwatchIfExists($optionId, $storeId)
    {
        $collection = $this->_swatchCollectionFactory->create();
        $collection->addFieldToFilter('option_id', $optionId);
        $collection->addFieldToFilter('store_id', $storeId);
        $collection->setPageSize(1);
        
        $loadedSwatch = $collection->getFirstItem();
        if ($loadedSwatch->getId()) {
            $this->isSwatchExists = true;
        }
        return $loadedSwatch;
    }
	public function attributeValueExists($arg_attribute, $arg_value, $arg_store_id)
    {
      	$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$attribute_code = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->getIdByCode('catalog_product', $arg_attribute);
		$attribute = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->load($attribute_code);
		// THIS WILL DELETE A EXISTING ATTRIBUTE OPTION AND REIMPORT A NEW. THIS COULD BE ISSUE FOR PRODUCTS WITH EXISTING ATTRIBUTE OPTIONS ASSIGNED THEM AS DELETING AND REIMPORTING NEW CHANGES ID.
		/*
		if($arg_store_id ==0) {
				$valuesCollection = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')->setAttributeFilter($attribute->getId())->setStoreFilter($arg_store_id, false);
				
				foreach($valuesCollection as $option)
				{
					if ($option['value'] != "") { $option->delete(); }
				}
				
			return false;
		}
		*/
		//this one is true if the above is uncommented otherwise false
		#$valuesCollection = $this->_attributeOptionCollection->setAttributeFilter($attribute->getId())->setStoreFilter($arg_store_id, false);
		$valuesCollection = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')->setAttributeFilter($attribute->getId())->setStoreFilter($arg_store_id, false);
		
		foreach($valuesCollection as $value_option)
        {
            if (htmlspecialchars($value_option['value']) == htmlspecialchars($arg_value))
            {	
			   #$idforreturn = $value_option->getId();
               #return $idforreturn;
               return $value_option['value'];
               #return true;
            } 
		}
		
        return false;
    }
	protected function attributeValueExistsById($arg_attribute, $arg_value, $arg_store_id)
    {
      
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$attribute_code = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->getIdByCode('catalog_product', $arg_attribute);
		$attribute = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->load($attribute_code);
		// THIS WILL DELETE A EXISTING ATTRIBUTE OPTION AND REIMPORT A NEW. THIS COULD BE ISSUE FOR PRODUCTS WITH EXISTING ATTRIBUTE OPTIONS ASSIGNED THEM AS DELETING AND REIMPORTING NEW CHANGES ID.
		/*
		if($arg_store_id ==0) {
				$valuesCollection = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')->setAttributeFilter($attribute->getId())->setStoreFilter($arg_store_id, false);
				
				foreach($valuesCollection as $option)
				{
					if ($option['value'] != "") { $option->delete(); }
				}
				
			return false;
		}
		*/
		//this one is true if the above is uncommented otherwise false
		#$valuesCollection = $this->_attributeOptionCollection->setAttributeFilter($attribute->getId())->setStoreFilter($arg_store_id, false);
		$valuesCollection = $objectManager->create('Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\Collection')->setAttributeFilter($attribute->getId())->setStoreFilter($arg_store_id, false);
		#echo "<br/>STORE ID: " . $arg_store_id. "<br/><br/>";
		foreach($valuesCollection as $value_option)
        {
            if (htmlspecialchars($value_option['value']) == htmlspecialchars($arg_value))
            {	
				#echo "SITE VAL: " . $value_option['value'] . "<br/>";
			    #echo "CSV VAL: " . $arg_value . "<br/>";
			   #$idforreturn = $value_option->getId();
               #return $idforreturn;
               return $value_option->getId();
               #return true;
            } 
		}
		#echo "<br/><br/>";
		
		
        return false;
    }
    /**
     * Import Product Attributes
     *
     * @param array $Data
     * @param array $storesCache cache of stores related to tax rate titles
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _importProductattributes(array $Data, array $params)
    {
		 
		 #print_r($Data);
		
		 $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		 if (isset($Data["delete"]) && $Data["delete"]=="1") {
			 /*
			 $attributeSet = $objectManager->create('Magento\Eav\Model\Entity\Attribute\Set')->load('empty_attribute_set', 'attribute_set_name');
			 if ($attributeSet->getId()) {
				$attributeSet->delete();
			 }
			 */
			 $productAttribute = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product');
			 $attribute = $productAttribute->getAttribute($Data["attribute_name"]);
	         if ($attribute) {
	        	 $attribute->delete();
			 }
		 	 return true;
		 }
		 $_eav_attribute_set = $this->_resource->getTableName('eav_attribute_set');
		 $_eav_attribute_group = $this->_resource->getTableName('eav_attribute_group');
		 
		 $connection = $this->_resource->getConnection();
		 //\Magento\Catalog\Model\Category::ENTITY)->getTypeId()
		 //$entityTypeId = $objectManager->create('Magento\Eav\Model\Entity\Type')->loadByCode('catalog_product')->getId();
		 if(isset($Data['EntityTypeId'])) { $EntityTypeId = $Data['EntityTypeId']; }
		 
		 #$product = $this->_productModel;
		 $product = $objectManager->create('Magento\Catalog\Model\ResourceModel\Product');
		 
		 /** @var \Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\Collection $setCollection */
		 #$entityType = $this->eavTypeFactory->create()->loadByCode('catalog_product');
		 $setCollection = $this->_attSetFactory->create()->getCollection();
		 $setCollection->addFieldToFilter('attribute_set_name', $Data['attribute_set']);
		 $setCollection->addFieldToFilter('entity_type_id', $EntityTypeId);
         $attribute_set_exists = $setCollection->getFirstItem();
		 
		 #$attribute_set_exists = $this->_attributeSet->load($Data['attribute_set'], 'attribute_set_name');
		 #print_r($setCollection->getData());
		 #$attributeSetId = $product->getEntityType()->getDefaultAttributeSetId();
		 
		 #$select_qry_set_id = $connection->query("SELECT attribute_set_id FROM ".$_eav_attribute_set." WHERE `attribute_set_name`=\"".addslashes($Data['attribute_set'])."\" and entity_type_id ='".$EntityTypeId."'");
		 #$rowSetId = $select_qry_set_id->fetch();
		 #$attributeSetId = $rowSetId['attribute_set_id'];
			
		 //THIS MAKES SURE WE ONLY TRY TO INSERT AN UNQUIE ATTRIBUTE SET ONCE
		 if($attribute_set_exists->getAttributeSetName()=="") {
		 	 //echo "MAKE new set";
			 #$modelSet = $this->_attributeSet;
			 $modelSet = $objectManager->create('Magento\Eav\Model\Entity\Attribute\Set');
			 $modelSet->setEntityTypeId($EntityTypeId);
			 $modelSet->setAttributeSetName($Data['attribute_set']);
			 $modelSet->save();

			 #if ($attributeSetId == "") {
		     $select_qry_set_id = $connection->query("SELECT attribute_set_id FROM ".$_eav_attribute_set." WHERE `attribute_set_name`='Default' and entity_type_id ='".$EntityTypeId."'");
		     $rowSetId = $select_qry_set_id->fetch();
		     $initFromSkeletonId = $rowSetId['attribute_set_id'];
			 #}
			 $modelSet->initFromSkeleton($initFromSkeletonId)->save(); //FIX this is the attribute set ID we want to use aka Default.. may not be same in all cases
			 $attributeSetId = $modelSet->getAttributeSetId();
		 } else {
		 
		 	$attributeSetId = $attribute_set_exists->getAttributeSetId();
		 }
		
		# $attribute_code1 = $this->_attributeModel->getIdByCode('catalog_product', $Data['attribute_name']);
		 #$attribute_exists = $this->_attributeModel->load($attribute_code1);
		 $attribute_code1 = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->getIdByCode('catalog_product', $Data['attribute_name']);
		 $attribute_exists = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->load($attribute_code1);
		 
		
		 #THIS DOES A CHECK TO SEE IF WE ARE SPECIFYING WHERE WE ARE PUTTING THE ATTRIBUTES INSIDE THE ATTRIBUTE SET. The else"by default" drops them into the general folder but including the column "attribute_group_name" in your CSV you can specify.
		 
		
		 if($Data['attribute_group_name'] != "") {
		 	#$final_group_name = str_replace(',','',strtolower($Data['attribute_group_name']));
		 	#$final_group_name = str_replace('/','-',$final_group_name);
			#$groupCode = str_replace(' ', '-', $final_group_name);
			$groupCode = $Data['attribute_group_name'];
			$groupCollection = $this->_attributeGroupCollection->create()
                    ->setAttributeSetFilter($attributeSetId)
                    ->addFieldToFilter('attribute_group_name', $groupCode)
                    ->setPageSize(1)
                    ->load();
			
            $group = $groupCollection->getFirstItem();
			$newGroupId = $group->getId();	
			#echo "MATCH ID " . $group->getId();
			
			 #$select_qry =$connection->query("SELECT * FROM ".$_eav_attribute_group." WHERE `attribute_set_id` = '".$attributeSetId."'");
			 #$query1 = "SELECT * FROM ".$_eav_attribute_group." WHERE `attribute_set_id` = '".$attributeSetId."'";
			 #$newrow = $select_qry->fetch();
			 #$searchtermsCollection = $connection->fetchAll($query1);
			 #$newGroupId = $newrow['attribute_group_id'];
			 #foreach ($searchtermsCollection as $row) {
			 	#print_r($row);
			 #}
			 #echo "DONE";
			 if (!$group->getId()) {
				#$group->setAttributeGroupCode($groupCode);
				if(isset($Data['attribute_group_sort_order'])) { $group->setSortOrder($Data['attribute_group_sort_order']); } else { $group->setSortOrder(1); };
				$group->setAttributeGroupName($Data['attribute_group_name']);
				$group->setAttributeSetId($attributeSetId);
				$group->setTabGroupCode("basic");
				$group->save();
				$newGroupId = $group->getId();
			 }
		 } else {
			 $select_qry =$connection->query("SELECT attribute_group_id FROM ".$_eav_attribute_group." WHERE `attribute_set_id` = '".$attributeSetId."'");
			 $newrow = $select_qry->fetch();
			 $newGroupId = $newrow['attribute_group_id'];
		 }
		
		
		  #$model = $this->_attributeResourceModel;
		  $model = $objectManager->create('Magento\Catalog\Model\ResourceModel\Eav\Attribute');
		  if($attribute_exists->getId()=="") {
		 
			  if($Data['frontend_input'] == "select" && $Data['backend_type'] == "int") {
				$backend_model = NULL;
			  	$source_model = 'Magento\Eav\Model\Entity\Attribute\Source\Table';
			  } else if($Data['frontend_input'] == "select" && $Data['backend_type'] == "varchar") {
				$backend_model = NULL;
			  	$source_model = 'Magento\Eav\Model\Entity\Attribute\Source\Boolean';
			  } else if($Data['frontend_input'] == "multiselect" && $Data['backend_type'] == "varchar") {
				$backend_model = 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend';
			  	$source_model = NULL;
			  } else if($Data['frontend_input'] == "boolean" && $Data['backend_type'] == "int") {
			  	$backend_model = NULL;
				$source_model = 'Magento\Eav\Model\Entity\Attribute\Source\Boolean';
			  } else {
			  	$backend_model = NULL;
			  	$source_model = NULL;
			  }
		  	  //echo "MAKE NEW ATTRIBUTE";
			  $modelData = [
				'attribute_code' => $Data['attribute_name'],
				'is_global' => $Data['is_global'],
				'is_user_defined' => $Data['is_user_defined'],
				'is_filterable' => $Data['is_filterable'],
				'is_visible' => $Data['is_visible'],
				'is_required' => $Data['is_required'],
				'is_visible_on_front' => $Data['is_visible_on_front'],
				'is_searchable' => $Data['is_searchable'],
				'is_unique' => $Data['is_unique'],
				'frontend_class' => $Data['frontend_class'],
				'is_visible_in_advanced_search' => $Data['is_visible_in_advanced_search'],
				'is_comparable' => $Data['is_comparable'],
				'is_filterable_in_search' => $Data['is_filterable_in_search'],
				'is_used_for_price_rules' => $Data['is_used_for_price_rules'],
				'is_used_for_promo_rules' => $Data['is_used_for_promo_rules'],
				'sort_order' => $Data['sort_order'],
				'position' => $Data['position'],
				'frontend_input' => $Data['frontend_input'],
				'backend_model' => $backend_model,
				'source_model' => $source_model,
				'backend_type' => $Data['backend_type'],
				'default_value' => $Data['default_value'],
				'apply_to' => $Data['apply_to'],
				'is_html_allowed_on_front' => $Data['is_html_allowed_on_front'],
				'used_in_product_listing' => $Data['used_in_product_listing'],
				'used_for_sort_by' => $Data['used_for_sort_by'],
				'is_wysiwyg_enabled' => $Data['is_wysiwyg_enabled'],
				'is_required_in_admin_store' => $Data['is_required_in_admin_store'],
				'is_used_in_grid' => $Data['is_used_in_grid'],
				'is_visible_in_grid' => $Data['is_visible_in_grid'],
				'is_filterable_in_grid' => $Data['is_filterable_in_grid'],
				'search_weight' => $Data['search_weight'],
				'additional_data' => $Data['additional_data'],
				'attribute_options' => $Data['attribute_options'],
			 ];
			
			 //apply_to #[OPTIONAL usable types:]# simple,grouped,configurable,virtual,downloadable,bundle
			 
			 #if ($importData['frontend_input'] == "multiselect") {
			 	#$data['backend_model'] = 'eav/entity_attribute_backend_array';
			 #}
			 if($Data['frontend_label'] != "") {
				 #$data['frontend_label'] = $Data[22];
				 $frontEndLabel=array();
				 $pipedelimiteddata = explode('|',$Data['frontend_label']);
				 foreach ($pipedelimiteddata as $datalabel) {
					 $pos = strpos($datalabel, ":");
					   if ($pos !== false) {
							$pipedelimiteddata1 = explode(':',$datalabel);
							//0 is the admin value, 1 is the "default store view" (frontend) value
							#$frontEndLabel=array('0'=>'Media Format', '1'=>'Media Format');
							$frontEndLabel[$pipedelimiteddata1[0]] = $pipedelimiteddata1[1];
						} else {
							$frontEndLabel[0] = $datalabel;
						}
				 }
				 $model->setFrontendLabel($frontEndLabel);	
				
			 } 
		  	
			 if($Data['attribute_options'] != "") {
				#$optionData=array();
				$optionDataValues=array();
				$attributevaluecounter=0;
				$dataOptionValues['option'] = array('value' => array());
				if ($params['attribute_options_delimiter'] == "true") {
					$attribute_options_delimiter = $params['attribute_options_delimiter'];
				} else {
					$attribute_options_delimiter = "|";
				}
				if($params['attribute_options_value_delimiter'] != "") {
					$attribute_options_values_delimiter = $params['attribute_options_value_delimiter'];
				} else {
					$attribute_options_values_delimiter = ",";
				}
				$pipedelimiteddata = explode($attribute_options_delimiter,$Data['attribute_options']);
				 foreach ($pipedelimiteddata as $attribute_options_data) {
				 
					  $pipedelimiteddatabycomma = explode($attribute_options_values_delimiter,$attribute_options_data);
					  foreach ($pipedelimiteddatabycomma as $options_data) {
							// change $options_data to $pre_options_data
							/*
							$checkactualattributeoptiondata = explode(':',$pre_options_data);
							if(!isset($checkactualattributeoptiondata[1])) {
								$options_data = "0:".$pre_options_data;
							} else {
								$options_data = $pre_options_data;
							}
							*/
							//this could be a trick for when you are only loading all default values.. no need to set 0:value,0:next could do value,next basically
							$actualattributeoptiondata = explode(':',$options_data);
							#$this->addAttributeValue($importData["attribute_name"], $data);
							//0 is the admin value, 1 is the "default store view" (frontend) value
							#$dataOptionValues['option']['value']['option_'.$attributevaluecounter.''][$actualattributeoptiondata[0]] = $actualattributeoptiondata[1];
							#$dataOptionValues['option']['value'] = array("option_".$attributevaluecounter."" => array($actualattributeoptiondata[0] => $actualattributeoptiondata[1]));
						    #$dataOptionValues['option']['value']['option_'.$attributevaluecounter.''] = array(0 => $actualattributeoptiondata[1], 2 => "test2");
							if(isset($actualattributeoptiondata[1])) {
								$optionDataValues[$actualattributeoptiondata[0]] = $actualattributeoptiondata[1];
								#$optionDataValues[0] = $actualattributeoptiondata[1];
								#echo "LABEL: " . $actualattributeoptiondata[1];
								$dataOptionValues['option']['value']['option_'.$attributevaluecounter.''] = $optionDataValues;
								if($params['import_w_sort_order'] == "true") {
									if(isset($actualattributeoptiondata[2])) {
										$dataOptionValues['option']['order']['option_'.$attributevaluecounter.''] = $actualattributeoptiondata[2];
									}
								}
							}
					 }
					 #$optionData['order']['option_'.$attributevaluecounter.''] = $attributevaluecounter+1;
					 $attributevaluecounter+=1;
				 }
				 $model->addData($dataOptionValues);
			 }
		  	  
			 //FINALLY SAVE THE ROW TO DATABASE
			 $model->addData($modelData);
			 #$model->setIsUserDefined($importData["is_user_defined"]);
			 $model->setAttributeGroupId($newGroupId);
			
			 $model->setAttributeSetId($attributeSetId);
			 #$model->setEntityTypeId(Mage::getModel('eav/entity')->setType('catalog_product')->getTypeId());
			 $model->setEntityTypeId($EntityTypeId); 
			
			 $model->save();
			 //had to add this cause attribute options missing on first import
			$attribute_code = $model->getId();
		   # $attribute_model = $this->_attributeModel;
		    $attribute_model  = $objectManager->create('Magento\Eav\Model\Entity\Attribute');
			
			
			if($Data['attribute_options'] != "") {
				 $attributeupdatevaluecounter=0;
				 $pipedelimiteddata = explode($attribute_options_delimiter,$Data['attribute_options']);
				 foreach ($pipedelimiteddata as $options_data_update) {
					$thisotherdatacommadelimited = explode($attribute_options_values_delimiter,$options_data_update);
					foreach ($thisotherdatacommadelimited as $options_data1) {
						$actualattributeoptiondata_update = explode(':',$options_data1);
						
						$attributeUpdate         = $attribute_model->load($attribute_code);
						if(isset($actualattributeoptiondata_update[1])) {
						
						if(!$this->attributeValueExists($Data["attribute_name"], $actualattributeoptiondata_update[1], $actualattributeoptiondata_update[0]))
						{
				
							if($actualattributeoptiondata_update[1] !="") {
								$optionUpdateDataValues['value']['option_'.$attributeupdatevaluecounter.''][$actualattributeoptiondata_update[0]] = $actualattributeoptiondata_update[1];	
								if($params['import_w_sort_order'] == "true") {
									if(isset($actualattributeoptiondata_update[2])) {
										$optionUpdateDataValues['order']['option_'.$attributeupdatevaluecounter.''] = $actualattributeoptiondata_update[2];
									}
								}
							}
						}
						}
					}
					$attributeupdatevaluecounter+=1;
				}
				 
				if(!empty($optionUpdateDataValues)) {
					$attributeUpdate->setData('option',$optionUpdateDataValues);
					$attributeUpdate->setAttributeSetId($attributeSetId);
					$attributeUpdate->setEntityTypeId($EntityTypeId);
					$attributeUpdate->save();
				}
			}
			
			//for swatches
			if(isset($Data['attribute_options_swatch'])) {
				if($Data['attribute_options'] != "" && $Data['attribute_options_swatch'] != "") {
					 $attributeupdatevaluecounter=0;
					 $pipedelimiteddata = explode($attribute_options_delimiter,$Data['attribute_options']);
					 $pipedelimiteddataSwatch = explode($attribute_options_delimiter,$Data['attribute_options_swatch']);
					 foreach ($pipedelimiteddata as $options_data_update) {
					 //0:Rudy Project:0|
						$thisotherdatacommadelimited = explode($attribute_options_values_delimiter,$options_data_update);
						foreach ($thisotherdatacommadelimited as $options_data1) {
							$actualattributeoptiondata_update = explode(':',$options_data1);
							
							$attributeUpdate         = $attribute_model->load($attribute_code);
							if(isset($actualattributeoptiondata_update[1])) {
								if($optionId = $this->attributeValueExistsById($Data["attribute_name"], $actualattributeoptiondata_update[1], $actualattributeoptiondata_update[0]))
								{
									#echo "OPTION ID: " . $optionId;
									$swatchDataArrayByStore = explode($attribute_options_values_delimiter,$pipedelimiteddataSwatch[$attributeupdatevaluecounter]);
									if($swatchDataArrayByStore[0]!="") {
										$swatchDataArray = explode(':',$swatchDataArrayByStore[0]);
										
										$swatch = $this->loadSwatchIfExists($optionId, 0);
										$swatch->setData('option_id', $optionId);
										$swatch->setData('store_id', $swatchDataArray[0]);
										$swatch->setData('type', $swatchDataArray[2]);
										$swatch->setData('value', $swatchDataArray[1]);
										$swatch->save();
									}
								}
							}
						}
						$attributeupdatevaluecounter+=1;
					}
				}
			}
			} #ends check to see if attribute already exists
			else { 
			 // CODE THAT UPDATES
			 //echo "UPDATE ATTRIBUTE";
			 #'default_value' => $Data[23],
			 $updateData = [
				'is_global' => $Data['is_global'],
				'is_user_defined' => $Data['is_user_defined'],
				'is_visible' => $Data['is_visible'],
				'is_required' => $Data['is_required'],
				'is_visible_on_front' => $Data['is_visible_on_front'],
				'is_searchable' => $Data['is_searchable'],
				'is_unique' => $Data['is_unique'],
				'is_visible_in_advanced_search' => $Data['is_visible_in_advanced_search'],
				'is_comparable' => $Data['is_comparable'],
				'is_filterable_in_search' => $Data['is_filterable_in_search'],
				'is_used_for_price_rules' => $Data['is_used_for_price_rules'],
				'is_used_for_promo_rules' => $Data['is_used_for_promo_rules'],
				'sort_order' => $Data['sort_order'],
				'position' => $Data['position'],
				'apply_to' => $Data['apply_to'],
				'is_html_allowed_on_front' => $Data['is_html_allowed_on_front'],
				'used_in_product_listing' => $Data['used_in_product_listing'],
				'used_for_sort_by' => $Data['used_for_sort_by'],
				'is_wysiwyg_enabled' => $Data['is_wysiwyg_enabled'],
				'is_required_in_admin_store' => $Data['is_required_in_admin_store'],
				'is_used_in_grid' => $Data['is_used_in_grid'],
				'is_visible_in_grid' => $Data['is_visible_in_grid'],
				'is_filterable_in_grid' => $Data['is_filterable_in_grid'],
				'search_weight' => $Data['search_weight'],
				'additional_data' => $Data['additional_data'],
			 ];
			 
			 
			 $attribute_exists->addData($updateData);
			 if($Data['frontend_input'] == "price" || $Data['frontend_input'] == "multiselect" || $Data['frontend_input'] == "select") {
				 $attribute_exists->setIsFilterable($Data['is_filterable']);
				 $attribute_exists->setIsFilterableInSearch($Data['is_filterable_in_search']);
			 }
			 $attribute_exists->setAttributeGroupId($newGroupId);
			 $attribute_exists->setAttributeSetId($attributeSetId);
			 $attribute_exists->setEntityTypeId($EntityTypeId); 
			 $attribute_exists->save(); 
			 
			 	
			 $result = array();
			 $optionUpdateDataValues=array();
				
			   if($Data['frontend_label'] != "") {
			
				 #$data['frontend_label'] = $Data[22];
				 $frontEndLabel=array();
				 $pipedelimiteddata = explode('|',$Data['frontend_label']);
				 foreach ($pipedelimiteddata as $datalabel) {
				    $pos = strpos($datalabel, ":");
				    if ($pos !== false) {
						$pipedelimiteddata1 = explode(':',$datalabel);
						//0 is the admin value, 1 is the "default store view" (frontend) value
						#$frontEndLabel=array('0'=>'Media Format', '1'=>'Media Format');
						$frontEndLabel[$pipedelimiteddata1[0]] = $pipedelimiteddata1[1];
					} else {
						$frontEndLabel[0] = $datalabel;
					}
				 }
		        #$attribute_model 		= $this->_attributeModel;
		        $attribute_model 		= $objectManager->create('Magento\Eav\Model\Entity\Attribute');
				$attribute_code         = $attribute_model->getIdByCode('catalog_product', $Data['attribute_name']);
				$attributelabelmodel    = $attribute_model->load($attribute_code);
				$attributelabelmodel->setFrontendLabel($frontEndLabel);
				$attributelabelmodel->save();	
				
			  } 
			  
			  if($params['attribute_options_delimiter'] != "") {
				$attribute_options_delimiter = $params['attribute_options_delimiter'];
			  } else {
				$attribute_options_delimiter = "|";
			  }
			  if($params['attribute_options_value_delimiter'] != "") {
				$attribute_options_values_delimiter = $params['attribute_options_value_delimiter'];
			  } else {
				$attribute_options_values_delimiter = ",";
			  }
			  #exit;
			  //see http://www.discuzfeed.com/magento/hzlrlp-magento2-programmatically-add-product-attribute-options.html
			  
			  if($Data['attribute_options'] != "") {
				 $attributeupdatevaluecounter=0;
				 $pipedelimiteddata = explode($attribute_options_delimiter,$Data['attribute_options']);
				 if(isset($Data['attribute_options_swatch'])) { 
				 	if($Data['attribute_options_swatch'] != "") {
				 		$pipedelimiteddataSwatch = explode($attribute_options_delimiter,$Data['attribute_options_swatch']);
					} 
				 }
				 foreach ($pipedelimiteddata as $options_data_update) {
					$thisotherdatacommadelimited = explode($attribute_options_values_delimiter,$options_data_update);
					foreach ($thisotherdatacommadelimited as $options_data1) {
						$actualattributeoptiondata_update = explode(':',$options_data1);
						
						#$attribute_model        = Mage::getModel('eav/entity_attribute');
						#$attribute_code1 = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->getIdByCode('catalog_product', $Data['attribute_name']);
					    #$attribute_exists = $objectManager->create('Magento\Eav\Model\Entity\Attribute')->load($attribute_code1);
						#$attribute_code         = $attribute_model->getIdByCode('catalog_product', $importData["attribute_name"]);
						
						$attributeUpdate         = $attribute_model->load($attribute_code1);
						if(isset($actualattributeoptiondata_update[1])) {
						#if(!$this->attributeValueExists($Data["attribute_name"], $actualattributeoptiondata_update[1], $actualattributeoptiondata_update[0]))
						#{
				
						#}
						if($optionId = $this->attributeValueExistsById($Data["attribute_name"], $actualattributeoptiondata_update[1], $actualattributeoptiondata_update[0]))
						{
							#echo "MATCHED OPTION ID: " . $optionId . "<br/>";
							if(isset($pipedelimiteddataSwatch)) {
								if($pipedelimiteddataSwatch != "" && is_array($pipedelimiteddataSwatch)) {
									$swatchDataArrayByStore = explode($attribute_options_values_delimiter,$pipedelimiteddataSwatch[$attributeupdatevaluecounter]);
									if($swatchDataArrayByStore[0]!="") {
										$swatchDataArray = explode(':',$swatchDataArrayByStore[0]);
										
										$swatch = $this->loadSwatchIfExists($optionId, 0);
										$swatch->setData('option_id', $optionId);
										$swatch->setData('store_id', $swatchDataArray[0]);
										$swatch->setData('type', $swatchDataArray[2]);
										$swatch->setData('value', $swatchDataArray[1]);
										$swatch->save();
									}
								}
							}
						} else {
							if($actualattributeoptiondata_update[1] !="") {
								$optionUpdateDataValues['value']['option_'.$attributeupdatevaluecounter.''][$actualattributeoptiondata_update[0]] = $actualattributeoptiondata_update[1];	
								if($params['import_w_sort_order'] == "true") {
									if(isset($actualattributeoptiondata_update[2])) {
										$optionUpdateDataValues['order']['option_'.$attributeupdatevaluecounter.''] = $actualattributeoptiondata_update[2];
									}
								}
							}
						}
						
						}
					}
					$attributeupdatevaluecounter+=1;
				}
				 
				if(!empty($optionUpdateDataValues)) {
					$attributeUpdate->setData('option',$optionUpdateDataValues);
					$attributeUpdate->save();
				}
			} 
			
		}
    }
}