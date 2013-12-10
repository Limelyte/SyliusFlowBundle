<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\FlowBundle\Storage;
use Symfony\Component\HttpFoundation\Session;

/**
 * Session storage.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SessionStorageLegacy extends Storage
{
    /**
     * Session.
     *
     * @var Session
     */
    protected $session;

    /**
     * Constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $array = $this->getArrayStorage();

        $resolveKey = $this->resolveKey($key);
        if (isset($array[$resolveKey])) {
            return $array[$resolveKey];
        }

        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $array = $this->getArrayStorage();

        $array[$this->resolveKey($key)] = $value;

        $this->setArrayStorage($array);
    }

    /**
     * {@inheritdoc}
     */
    public function has($key)
    {
        $array = $this->getArrayStorage();

        return isset($array[$this->resolveKey($key)]);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        $array = $this->getArrayStorage();

        $resolveKey = $this->resolveKey($key);

        if (isset($array[$resolveKey])) {
            unset($array[$resolveKey]);
        }

        $this->setArrayStorage($array);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->setArrayStorage(array());
    }

	private function resolveKey($key)
	{
		return $key;
	}

    private function getArrayStorage()
    {
        $data = $this->session->get($this->getArrayStorageKey(), serialize(array()));

        return unserialize($data);
    }

    private function setArrayStorage(array $data)
    {
        $this->session->set($this->getArrayStorageKey(), serialize($data));
    }

    /**
     * @return string
     */
    private function getArrayStorageKey()
    {
        return SessionFlowsBag::NAME . '_' . $this->domain;
    }
}
