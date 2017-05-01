<?php

/*
 * This file is part of Components of Nwt.cm project
 * By contributor S@int-Cyr MAPOUKA
 * (c) Saint-Cyr MAPOUKA <mapoukacyr@yahoo.fr>
 * For the full copyrght and license information, please view the LICENSE
 * file that was distributed with this source code
 */

namespace Tests\TransactionBundle\Service;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use NwtBundle\Entity\Product;

class ProductTest extends WebTestCase
{
    private $em;
    private $application;


    public function setUp()
    {
        static::$kernel = static::createKernel();
        static::$kernel->boot();
        
        $this->application = new Application(static::$kernel);
        $this->em = $this->application->getKernel()->getContainer()->get('doctrine.orm.entity_manager');
    }
    
    public function testProduct()
    {
        $product1 = new Product();
        $product1->setUnitPrice(111);

        $this->assertEquals($product1->getUnitPriceString(), '111');
        $this->assertTrue(is_numeric(111));
        $this->assertTrue(is_string(number_format(111,0,'.','')));
    }
}
