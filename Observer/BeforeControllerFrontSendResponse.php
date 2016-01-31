<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Before controller front send response observer
 */
class BeforeControllerFrontSendResponse implements ObserverInterface
{
    /**
     * Lock model
     *
     * @var \ShopGo\Locker\Model\Lock
     */
    protected $_lock;

    /**
     * @param \ShopGo\Locker\Model\Lock $lock
     */
    public function __construct(\ShopGo\Locker\Model\Lock $lock)
    {
        $this->_lock = $lock;
    }

    /**
     * Redirect to forbidden page, if magento is locked
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_lock->getLockStatus()) {
            $response = $observer->getEvent()->getResponse();

            $response->setHttpResponseCode(403);
            $response->setHeader('Content-Type', 'text/plain');
            $response->setBody('Out of Service!');
        }
    }
}
