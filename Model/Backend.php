<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Model;

use Magento\Store\Model\ScopeInterface;

class Backend extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Admin user names xml path
     */
    const XML_PATH_GENERAL_ADMIN_USERS = 'locker/general/admin_users';

    /**
     * Admin user limited access role xml path
     */
    const XML_PATH_GENERAL_ADMIN_USER_LIMITED_ROLE = 'locker/general/admin_user_limited_role';

    /**
     * Admin user full access role xml path
     */
    const XML_PATH_GENERAL_ADMIN_USER_FULL_ROLE = 'locker/general/admin_user_full_role';

    /**
     * Admin user limited access role
     */
    const LIMITED_ROLE = 'limited';

    /**
     * Admin user full access role
     */
    const FULL_ROLE = 'full';

    /**
     * User factory model
     *
     * @var \Magento\User\Model\UserFactory
     */
    protected $_userFactory;

    /**
     * Scope config interface
     *
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @param \Magento\User\Model\UserFactory $userFactory
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\User\Model\UserFactory $userFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->_userFactory = $userFactory;
        $this->_scopeConfig = $scopeConfig;
    }

    /**
     * Get role ID based on access level
     *
     * $param string $access
     * @return string
     */
    public function _getRoleId($access)
    {
        $id = 0;

        switch ($access) {
            case self::LIMITED_ROLE:
                $id = $this->_scopeConfig->getValue(
                    self::XML_PATH_GENERAL_ADMIN_USER_LIMITED_ROLE,
                    ScopeInterface::SCOPE_STORE
                );
                break;
            case self::FULL_ROLE:
                $id = $this->_scopeConfig->getValue(
                    self::XML_PATH_GENERAL_ADMIN_USER_FULL_ROLE,
                    ScopeInterface::SCOPE_STORE
                );
                break;
        }

        return $id;
    }

    /**
     * Get admin user collection filters
     *
     * @return array
     */
    protected function _getUsersFilter()
    {
        $usersFilter = [];

        $users = $this->_scopeConfig->getValue(
            self::XML_PATH_GENERAL_ADMIN_USERS,
            ScopeInterface::SCOPE_STORE
        );
        $users = array_map('trim', explode(',', $users));

        foreach ($users as $user) {
            $usersFilter[] = ['eq' => $user];
        }

        return $usersFilter;
    }

    /**
     * Change admin users role based on access level
     *
     * $param string $access
     * @return void
     */
    public function changeRole($access)
    {
        $roleId = $this->_getRoleId($access);
        if (!$roleId) {
            return;
        }

        $usersFilter = $this->_getUsersFilter();
        if (empty($usersFilter)) {
            return;
        }

        $userCollection = $this->_userFactory->create()->getCollection()
                          ->addFieldToFilter('username', $usersFilter)
                          ->getItems();

        foreach ($userCollection as $user) {
            $user->deleteFromRole($user);
            $user->setRoleId($roleId);
            $user->save();
        }
    }
}
