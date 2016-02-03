<?php
/**
 *
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Controller\Adminhtml\Subscription;

use Magento\Backend\App\Action;

class Subscribe extends Action
{
    /**
     * @var \ShopGo\Locker\Model\Subscription
     */
    protected $_subscription;

    /**
     * @param Action\Context $context
     * @param \ShopGo\Locker\Model\Subscription $subscription
     */
    public function __construct(
        Action\Context $context,
        \ShopGo\Locker\Model\Subscription $subscription
    ) {
        parent::__construct($context);
        $this->_subscription = $subscription;
    }

    /**
     * Redirect to subscription URL
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $subscriptionUrl = $this->_subscription->getSubscriptionUrl();

        if (!$subscriptionUrl) {
            $subscriptionUrl = '*/*';
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        return $resultRedirect->setPath($subscriptionUrl);
    }
}
