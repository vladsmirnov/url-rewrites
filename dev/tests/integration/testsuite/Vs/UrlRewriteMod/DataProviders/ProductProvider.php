<?php
/**
 * Copyright 2017 Vladyslav Smirnov
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation the rights to use, copy,
 * modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or
 * substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING
 * BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR
 * ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

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