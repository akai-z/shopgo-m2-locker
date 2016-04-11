<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Block\Adminhtml;

class Head extends \Magento\Framework\View\Element\Template
{
    /**
     * View asset repository
     *
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepository;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Magento\Framework\View\Asset\Repository $assetRepository
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Framework\View\Asset\Repository $assetRepository,
        array $data = []
    ) {
        $this->_assetRepository = $assetRepository;
        return parent::__construct($context, $data);
    }

    /**
     * Create asset
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function createAsset($fileId, array $params = [])
    {
        return $this->_assetRepository->createAsset($fileId, $params);
    }

    /**
     * Get asset URL
     *
     * @param string $fileId
     * @param array $params
     * @return string
     */
    public function getAssetUrl($fileId, array $params = [])
    {
        $asset = $this->createAsset($fileId, $params);

        return $asset->getUrl();
    }

    /**
     * Get locker CSS HREF
     *
     * @return string
     */
    public function getLockerCssHref()
    {
        return $this->getAssetUrl('ShopGo_Locker::css/style-locker.css');
    }
}
