# Magento 1.9 CE Url Rewrite Fix

## The issue
Magento 1.9 CE has an url rewrite issue. Magento create the extra entities (duplicates) in 'core_url_rewrite' tables on each reindexing of 'Catalog URL Rewrites'. This Magento module targets this issue and prevents creating duplicates in 'core_url_rewrites' table.
However, if you have the running Magento installation, you probably have the duplicates in the database. This repository contains a dedicated Shell script that allows you to remove the duplicates from the database.

## Installation
To apply this fix just clone this repository to Magento root directory

## Removing duplicates (Shell script usage)
To removing all the extra entities from 'core_url_rewrites' table run this command:
```
php shell/rewrites.php cleanAll
```
If you don't want to remove all entities (this makes sense for SEO reasons, since the existing URLs might be cached by search engines) you should use an additional argument **--except 'number'**.
With this additional argument script will delete all records except last **'number'** rows.

For example, in this case script will delete all records, except last 5:
```
php shell/rewrites.php cleanAll --except 5

```

To get information about script commands run it with 'help' argument:
```
php shell/rewrites.php help
```

## Contribution
You can contribute by creating the issue or the pull request. 

### Running the PHPUnit Tests

To run the tests your environment should has the PHPUnit testing framework.

**How to install PHPUnit testing framework:**
```
wget https://phar.phpunit.de/phpunit.phar

chmod +x phpunit.phar

sudo mv phpunit.phar /usr/local/bin/phpunit

phpunit --version
PHPUnit 6.4.0 by Sebastian Bergmann and contributors.
```

[phpunit.de](https://phpunit.de/getting-started.html) - Getting Started with PHPUnit


**How to run:**

* Navigate to the test's directory via command:

```
cd dev/tests/integration 
```

* And run:
```
phpunit
```

**How it works:**

After running the tests, will be created 4 test fixture products. Then will be called refreshProductRewrites() 
method which also calls while running 'Catalog Url Rewrite' reindex. If Magento will create an extra rewrites the test 
will be failed. 

After tests end temp fixture products will be deleted from database.

# License
This project is released under the MIT license.

```
Copyright 2017 Vladyslav Smirnov

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated  documentation 
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, 
merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is 
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO 
THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF 
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
DEALINGS IN THE SOFTWARE.
```
