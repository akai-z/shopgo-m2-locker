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
     * App state
     *
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @param \ShopGo\Locker\Model\Lock $lock
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        \ShopGo\Locker\Model\Lock $lock,
        \Magento\Framework\App\State $appState
    ) {
        $this->_lock = $lock;
        $this->_appState = $appState;
    }

    /**
     * Redirect to forbidden page, if magento is locked
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_lock->getLockStatus() && $this->_appState->getAreaCode() == 'frontend') {
            $response = $observer->getEvent()->getResponse();

            $response->setHttpResponseCode(403);
            $response->setHeader('Content-Type', 'text/plain');
            $response->setBody('Out of Service!');
        }
    }
}
