<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Controller\ResultFactory;

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
     * Result layout
     *
     * @var \Magento\Framework\View\Result\Layout
     */
    protected $_resultFactory;

    /**
     * @param \ShopGo\Locker\Model\Lock $lock
     * @param \Magento\Framework\App\State $appState
     * @param \Magento\Framework\View\Result\Layout $resultFactory
     */
    public function __construct(
        \ShopGo\Locker\Model\Lock $lock,
        \Magento\Framework\App\State $appState,
        ResultFactory $resultFactory
    ) {
        $this->_lock = $lock;
        $this->_appState = $appState;
        $this->_resultFactory = $resultFactory;
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
            /** @var \Magento\Framework\View\Result\Layout $resultLayout */
            $resultLayout = $this->_resultFactory->create(ResultFactory::TYPE_LAYOUT);
            $html = $resultLayout->getLayout()->createBlock('ShopGo\Locker\Block\Adminhtml\Head')
                ->setTemplate('ShopGo_Locker::out_of_service.phtml')
                ->toHtml();

            $response = $observer->getEvent()->getResponse();

            $response->setHttpResponseCode(403);
            $response->setHeader('Content-Type', 'text/html; charset=utf-8');
            $response->setBody($html);
        }
    }
}
