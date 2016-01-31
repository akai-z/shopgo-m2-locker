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
 * Change lock status
 */
class ChangeLockStatus extends Command
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
        $this->setName('locker:change-lock-status')
            ->setDescription('Change lock status')
            ->setDefinition([
                new InputArgument(
                    self::LOCK_ARGUMENT,
                    InputArgument::REQUIRED,
                    'Lock'
                )
            ]);

        parent::configure();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $lock = $input->getArgument(self::LOCK_ARGUMENT);

        if (!is_null($lock)) {
            $this->_lock->setAreaCode('adminhtml');

            $result = $this->_lock->setLockStatus($lock);

            if ($result) {
                $result = $lock
                    ? 'Magento is now locked!'
                    : 'Magento is now unlocked!';
            } else {
                $result = 'Failed to change lock status!';
            }
        } else {
            throw new \InvalidArgumentException('Argument ' . self::LOCK_ARGUMENT . ' is missing.');
        }

        $output->writeln('<info>' . $result . '</info>');
    }
}
