<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Model;

class Lock extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Lock xml path
     */
    const XML_PATH_GENERAL_Lock = 'locker/general/lock';

    /**
     * Config factory model
     *
     * @var \Magento\Config\Model\Config\Factory
     */
    protected $_configFactory;

    /**
     * App state
     *
     * @var \Magento\Framework\App\State
     */
    protected $_appState;

    /**
     * @param \Magento\Config\Model\Config\Factory $configFactory
     * @param \Magento\Framework\App\State $appState
     */
    public function __construct(
        \Magento\Config\Model\Config\Factory $configFactory,
        \Magento\Framework\App\State $appState
    ) {
        $this->_configFactory = $configFactory;
        $this->_appState = $appState;
    }

    /**
     * Get config model
     *
     * @param array $configData
     * @return \Magento\Config\Model\Config
     */
    protected function _getConfigModel($configData = [])
    {
        /** @var \Magento\Config\Model\Config $configModel  */
        $configModel = $this->_configFactory->create(['data' => $configData]);
        return $configModel;
    }

    /**
     * Get config data value
     *
     * @param string $path
     * @return string
     */
    protected function _getConfigData($path)
    {
        return $this->_getConfigModel()->getConfigDataValue($path);
    }

    /**
     * Set config data
     *
     * @param array $configData
     */
    protected function _setConfigData($configData = [])
    {
        $this->_getConfigModel($configData)->save();
    }

    /**
     * Get lock status
     *
     * @return string
     */
    public function getLockStatus()
    {
        return $this->_getConfigModel()->getConfigDataValue(self::XML_PATH_GENERAL_Lock);
    }

    /**
     * Set lock status
     *
     * @param string $content
     * @return string
     */
    public function setLockStatus($content)
    {
        $result = __('Could not change lock status');

        try {
            $group = [
                'general' => [
                    'fields' => [
                        'lock' => [
                            'value' => $content
                        ]
                    ]
                ]
            ];

            $configData = [
                'section' => 'locker',
                'website' => null,
                'store'   => null,
                'groups'  => $group
            ];

            $this->_setConfigData($configData);

            $result = ($content && $content != 0)
                ? __('Magento is now locked!')
                : __('Magento is now unlocked!');
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $messages = explode("\n", $e->getMessage());

            foreach ($messages as $message) {
                $result .= "\n" . $message;
            }
        } catch (\Exception $e) {
            $result .= "\n" . $e->getMessage();
        }

        return $result;
    }

    /**
     * Set area code
     *
     * @param string $code
     */
    public function setAreaCode($code)
    {
        $this->_appState->setAreaCode($code);
    }
}
