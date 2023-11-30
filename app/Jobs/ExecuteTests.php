<?php

namespace App\Jobs;

use App\Services\EventAuditLogService;
use App\Services\ServiceTestService;

class ExecuteTests extends Job
{
    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 5;

    public $queue = 'executeTests';

    /**
     * The maximum number of exceptions to allow before failing.
     *
     * @var int
     */
    public $maxExceptions = 3;

    public $payload;

    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     */
    public function handle(ServiceTestService $serviceTestService)
    {
        $serviceTestService->test(
            $this->payload['service_id'],
            $this->payload['environment_id'],
            $this->payload['scenario_id']
        );
    }
}
