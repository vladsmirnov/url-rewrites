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

require_once 'abstract.php';

/**
 * Repair Url Rewrite Shell Script
 */

class Vs_Shell_Rewrites extends Mage_Shell_Abstract
{

    /**
     * Run script
     *
     */
    public function run()
    {
        if ($this->getArg('cleanAll')) {
            $except = $this->getArg('except');
            if (!empty($except)) {
                if ($except !== true) { // check if 'except' has an value
                    $this->repairUrlRewritesTable((int)$except); //convert value to integer, and run repair method
                } else {
                    echo $this->usageHelp();
                }
            } else {
                $this->repairUrlRewritesTable();
            }
        } else {
            echo $this->usageHelp();
        }
    }

    /**
     * Remove duplicate records from 'core_url_rewrite' table
     * @param int $except
     */
    public function repairUrlRewritesTable($except = false)
    {
        try {
            //Get collection of all products
            $productCollection = Mage::getResourceModel('catalog/product_collection');
            $counter = 0;
            foreach($productCollection as $product) {

                //Get all url rewrites of each product
                $urlRewritesCollection = Mage::getResourceModel('core/url_rewrite_collection')
                    ->addFieldToFilter('product_id', array('eq' => $product->getId()))
                    ->addFieldToFilter('is_system', array('eq' => '0'))
                    ->setOrder('url_rewrite_id', 'DESC');

                // If 'except' argument exist, and valid -> apply it to $urlRewriteCollection
                if ($except !== false && $except > 0) {
                    $urlRewritesCollection->getSelect()->limit(null, $except);
                } elseif ($except !== false) {
                    throw new Exception('\'--except\' should be an integer.');
                }

                foreach ($urlRewritesCollection as $urlRewrite) {
                    try {
                        //Removing extra url rewrites
                        $urlRewrite->delete();
                        $counter++;
                    } catch(Exception $e) {
                        echo "An error was occurred: " . $e->getMessage() . PHP_EOL;
                        Mage::log($e->getMessage(), null, 'repair_url_rewrites.log');
                    }
                }
            }
            //Display result message
            $message = $counter . ' duplicating records was deleted';
            echo $message . PHP_EOL;

        } catch (Exception $e) {
            echo "An error was occurred: " . $e->getMessage() . PHP_EOL;
            Mage::log($e->getMessage(), null, 'repair_url_rewrites.log');
        }
    }

    /**
     * Retrieve Usage Help Message
     *
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f rewrites.php -- [options]

  cleanAll                Remove all duplicated records from 'core_url_rewrite' table
  --except <number>    Remove all except last <number> records 
help                   This help
  
USAGE;
    }
}

$shell = new Vs_Shell_Rewrites();
$shell->run();