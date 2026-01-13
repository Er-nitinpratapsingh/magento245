<?php
namespace Vendor1\TestCron\Cron;

use Psr\Log\LoggerInterface;

class TestCron
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('Custom test cron executed successfully.');
        return $this;
    }
}
