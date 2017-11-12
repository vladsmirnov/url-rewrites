<?php

/**
 * Product Provider for tests
 */


class Vs_UrlRewriteMod_DataProviders_ProductProvider
{
    /**
     * Generating the Test Product
     * @param string $sku (SKU of the test product)
     * @return int ID of created test product
     */
    public static function generateProductFixtures($sku)
    {
        $product = Mage::getModel('catalog/product');
        $attributeSetId = Mage::getModel('catalog/product')->getDefaultAttributeSetId();
        $taxClassId = Mage::getModel('tax/class')->getCollection()->getFirstItem()->getId();
        $websites = Mage::app()->getWebsites();

        $websiteIds = array();
        foreach ($websites as $website) {
            $websiteIds[] = $website->getId();
        }
        $productData = array(
            'type_id' => Mage_Catalog_Model_Product_Type::TYPE_SIMPLE,
            'attribute_set_id' => $attributeSetId,
            'name' => 'Test Simple Product-' . $sku,
            'description' => 'Test Simple Product Description-' . $sku,
            'short_description' => 'Test Simple Product Short Description-' . $sku,
            'sku' => $sku,
            'weight' => 1,
            'status' => Mage_Catalog_Model_Product_Status::STATUS_ENABLED,
            'visibility' => Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH,
            'url_key' => 'test-url-key',
            'price' => 99.99,
            'tax_class_id' => $taxClassId,
            'stock_data' => array(
                'qty' => 999,
                'is_in_stock' => 1,
                'use_config_manage_stock' => 0,
                'manage_stock' => 1),
            'website_ids' => $websiteIds);

        $product->setData($productData);
        try {
            $product->save();
        } catch (Exception $e) {
            echo 'Caught exception: ',  $e->getMessage(), "\n";
            exit;
        }
        return $product->getId();
    }
}