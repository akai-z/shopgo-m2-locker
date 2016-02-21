<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Sns notification observer
 */
class SnsNotification implements ObserverInterface
{
    /**
     * Locker code
     */
    const CODE = 'ShopGo_Locker';

    /**
     * Set lock status action
     */
    const ACTION_SET_LOCK_STATUS = 'set_lock_status';

    /**
     * Notifier model
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
     * Handle SNS notifications
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $notification = $observer->getEvent()->getData('notification');

        if ($notification['module'] != self::CODE) {
            return false;
        }

        switch ($notification['action']) {
            case self::ACTION_SET_LOCK_STATUS:
                $this->_lock->setLockStatus(
                    $notification['arguments']['status']
                );
                break;
        }
    }
}
