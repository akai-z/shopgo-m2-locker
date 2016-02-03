<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */

// @codingStandardsIgnoreFile

namespace ShopGo\Locker\Block\Adminhtml\Page;

/**
 * Adminhtml header block
 */
class Header extends \Magento\Backend\Block\Page\Header
{
    /**
     * Subscribe URL
     */
    const SUBSCRIBE_URL = 'shopgo_locker/subscription/subscribe';

    /**
     * @var \ShopGo\Locker\Model\Subscription
     */
    protected $_subscription;

    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Backend\Model\Auth\Session $authSession
     * @param \Magento\Backend\Helper\Data $backendData
     * @param \ShopGo\Locker\Model\Subscription $subscription
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Backend\Helper\Data $backendData,
        \ShopGo\Locker\Model\Subscription $subscription,
        array $data = []
    ) {
        $this->_subscription = $subscription;
        parent::__construct($context, $authSession, $backendData, $data);
    }

    /**
     * Get remaining time
     *
     * @return string
     */
    public function getRemainingTime()
    {
        return 'Remaining Days: XX';
    }

    /**
     * Get subscribe URL
     *
     * @return string
     */
    public function getSubscribeUrl()
    {
        return $this->getUrl(self::SUBSCRIBE_URL);
    }
}
