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

use PHPUnit\Framework\TestCase;

class Vs_UrlRewriteMod_Model_UrlTest extends TestCase
{
    protected $_storeId;

    /**
     * Collection of test products
     *
     * @var array
     */
    protected $_products = array();
    protected $_rewriteModel;
    protected $_model;

    public function setUp()
    {
        parent::setUp();
        $this->_storeId = Mage::app()->getStore()->getStoreId();
        $this->_model = new Vs_UrlRewriteMod_Model_Url;
        $this->_rewriteModel = Mage::getModel('core/url_rewrite');

        Mage::register('isSecureArea', true);
        for ($i = 0; $i < 4; $i++) {
            $testProductId = Vs_UrlRewriteMod_DataProviders_ProductProvider::generateProductFixtures('TEST-' . $i);
            $this->_products[] = Mage::getModel('catalog/product')->load($testProductId);
        }
    }

    /**
     * @uses       PHPUnit_Framework_TestCase
     * @test
     */
    public function testRefreshProductRewrites()
    {
        $this->assertNotEmpty($this->_storeId);

        foreach ($this->_products as $product) {

            //Get an expected data
            $expectedProductRewrites = $this->_rewriteModel->getCollection()
                ->addFieldToFilter('product_id', array('eq' => $product->getId()));
            $expectedProductRewritesCount = $expectedProductRewrites->count();

            // Run refreshing of product rewrites. At this step Magento made extra duplicates in 'core_url_rewrite'
            $this->_model->refreshProductRewrites($this->_storeId);

            //Get an actual data
            $actualProductRewrites = $this->_rewriteModel->getCollection()
                ->addFieldToFilter('product_id', array('eq' => $product->getId()));
            $actualProductRewritesCount = $actualProductRewrites->count();

            $this->assertEquals($expectedProductRewritesCount, $actualProductRewritesCount);
        }
    }

    protected function tearDown() {
        foreach ($this->_products as $product) {
            $product->delete();
        }
        $this->_model = null;
        $this->_rewriteModel = null;
        $this->_storeId = null;
        $this->_products = null;
        Mage::unregister('isSecureArea');

        parent::tearDown();
    }
}
