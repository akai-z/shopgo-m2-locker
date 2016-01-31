<?php
/**
 * Copyright © 2015 ShopGo. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace ShopGo\Locker\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use ShopGo\Locker\Model\Lock;

/**
 * Get lock status
 */
class GetLockStatus extends Command
{
    /**
     * Lock argument
     */
    const LOCK_ARGUMENT = 'lock';

    /**
     * @var Lock
     */
    private $_lock;

    /**
     * @param Lock $lock
     */
    public function __construct(Lock $lock)
    {
        parent::__construct();
        $this->_lock = $lock;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName('locker:get-lock-status')
            ->setDescription('Get lock status');
            /*->setDefinition([
                new InputArgument(
                    self::LOCK_ARGUMENT,
                    InputArgument::REQUIRED,
                    'Lock'
                )
            ]);*/

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->_lock->setAreaCode('adminhtml');

        $result = $this->_lock->getLockStatus();

        if ($result || $result == 0) {
            $result = $result
                ? 'Magento is locked!'
                : 'Magento is unlocked!';
        } else {
            $result = 'Failed to get lock status!';
        }

        $output->writeln('<info>' . $result . '</info>');
    }
}
