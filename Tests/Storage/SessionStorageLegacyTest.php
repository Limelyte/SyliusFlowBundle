<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\FlowBundle\Tests\Storage;

use Sylius\Bundle\FlowBundle\Storage\SessionFlowsBag;
use Sylius\Bundle\FlowBundle\Storage\SessionStorage;
use Sylius\Bundle\FlowBundle\Storage\SessionStorageLegacy;
use Symfony\Component\HttpFoundation\Session;

/**
 * SessionStorage test.
 *
 * @author Leszek Prabucki <leszek.prabucki@gmail.com>
 */
class SessionStorageLegacyTest extends \PHPUnit_Framework_TestCase
{
	public function setUp()
	{

	}
	
    /**
     * @test
     * @covers Sylius\Bundle\FlowBundle\Storage\SessionStorage
     */
    public function shouldSetValueToSessionBag()
    {
        $sessionStorage = new SessionStorageLegacy($this->getSession());
        $sessionStorage->initialize('mydomain');

        $sessionStorage->set('test', 'my-value');

        $this->assertEquals('my-value', $sessionStorage->get('test'));
    }

    /**
     * @test
     * @covers Sylius\Bundle\FlowBundle\Storage\SessionStorage
     */
    public function shouldCheckIfValueIsSetInSessionBag()
    {
        $sessionStorage = new SessionStorageLegacy($this->getSession());
        $sessionStorage->initialize('mydomain');

        $sessionStorage->set('test', 'testing_value');

        $this->assertTrue($sessionStorage->has('test'));
    }

    /**
     * @test
     * @covers Sylius\Bundle\FlowBundle\Storage\SessionStorage
     */
    public function shouldRemoveFromSessionBag()
    {
        $sessionStorage = new SessionStorageLegacy($this->getSession());
        $sessionStorage->initialize('mydomain');

        $sessionStorage->set('test', 'testing_value');
		$this->assertTrue($sessionStorage->has('test'));
		
        $sessionStorage->remove('test');
		$this->assertFalse($sessionStorage->has('test'));
    }

    /**
     * @test
     * @covers Sylius\Bundle\FlowBundle\Storage\SessionStorage
     */
    public function shouldClearDomainInSessionBag()
    {
        $sessionStorage = new SessionStorageLegacy($this->getSession());

        $sessionStorage->initialize('mydomain');

        $sessionStorage->set('to-be-removed', 'value');
        $this->assertTrue($sessionStorage->has('to-be-removed'));
        $sessionStorage->clear();
        $this->assertFalse($sessionStorage->has('to-be-removed'));
    }

    /**
     * @test
     * @covers Sylius\Bundle\FlowBundle\Storage\SessionStorage
     */
    public function shouldSetValueWithPrefixAndDomain()
    {
        $session = $this->getMock(
            'Symfony\Component\HttpFoundation\Session',
            array('set', 'get'),
            array(),
            '',
            false // don't call constructor
        );

        $session->expects($this->once())
            ->method('set')
            ->with('sylius.flow.bag_mydomain', serialize(array('test' => 'value')))
        ;

        $sessionStorage = new SessionStorageLegacy($session);
        $sessionStorage->initialize('mydomain');
        $sessionStorage->set('test', 'value');
    }

    private function getSession()
    {
        $session = new Session($this->getMock('Symfony\Component\HttpFoundation\SessionStorage\SessionStorageInterface'));

        return $session;
    }
}
