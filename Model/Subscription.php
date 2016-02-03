<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Model;

use \Magento\Framework\HTTP\ZendClient;

class Subscription extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Default admin user ID
     */
    const DEFAULT_ADMIN_USER_ID = 1;

    /**
     * Subscription URL
     */
    const SUBSCRIPTION_URL = 'http://billing.shopgo.me/merchant/login';

    /**
     * Subscription token request URL
     */
    const SUBSCRIPTION_TOKEN_URL = 'http://billing.shopgo.me/authenticate/request-token';

    /**
     * Store secret code xml path
     */
    const XML_PATH_SECRET_CODE = 'locker/general/store_secret_code';

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_authSession;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $_httpClientFactory;

    /**
     * @var \Magento\Framework\Encryption\EncryptorInterface
     */
    protected $_encryptor;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\User\Model\User
     */
    protected $_user;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
     * @param \Magento\Framework\Encryption\EncryptorInterface $encryptor
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\User\Model\User $user
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Framework\Encryption\EncryptorInterface $encryptor,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\User\Model\User $user
    ) {
        $this->_storeManager = $storeManager;
        $this->_httpClientFactory = $httpClientFactory;
        $this->_encryptor = $encryptor;
        $this->_scopeConfig = $scopeConfig;
        $this->_user = $user;

        parent::__construct($context, $registry);
    }

    /**
     * Get admin user email
     *
     * @return string
     */
    protected function _getAdminUserEmail()
    {
        $email = '';
        $admin = $this->_user->load(self::DEFAULT_ADMIN_USER_ID);

        if ($admin) {
            $email = $admin->getEmail();
        }

        return $email;
    }

    /**
     * Get base URL
     *
     * @return string
     */
    protected function _getBaseUrl()
    {
        return $this->_storeManager->getStore()->getBaseUrl();
    }

    /**
     * Get store secret code
     *
     * @return string
     */
    protected function _getStoreSecretCode()
    {
        $code = $this->_scopeConfig->getValue(
            self::XML_PATH_SECRET_CODE,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );

        return $code;
    }

    /**
     * Get subscription token
     *
     * @return string
     */
    public function _getSubscriptionToken()
    {
        $token = '';
        $secretCode = $this->_getStoreSecretCode();

        if (!$secretCode) {
            return $token;
        }

        /** @var \Magento\Framework\HTTP\ZendClient $httpClient */
        $httpClient = $this->_httpClientFactory->create();

        $httpClient->setUri(self::SUBSCRIPTION_TOKEN_URL);
        $httpClient->setMethod(ZendClient::POST);

        $params = [
            'email' => $this->_getAdminUserEmail(),
            'url'   => $this->_getBaseUrl(),
            'secret_code' => $secretCode
        ];

        $httpClient->setParameterPost($params);

        try {
            $response = $httpClient->request();
        } catch (\Zend_Http_Client_Exception $e) {}

        if (($response->getStatus() < 200 || $response->getStatus() > 210)) {
            return $token;
        }

        $token = json_decode($response->getBody(), true);

        if (isset($token['token']) && $token['status']) {
            $token = $token['token'];
        }

        return $token;
    }

    /**
     * Get subscription URL
     *
     * @return string
     */
    public function getSubscriptionUrl()
    {
        $token = $this->_getSubscriptionToken();

        return $token
            ? self::SUBSCRIPTION_URL . '?token=' . $token
            : $token;
    }
}
