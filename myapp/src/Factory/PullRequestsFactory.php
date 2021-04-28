<?php

namespace App\Factory;

use App\Entity\PullRequests;
use App\Entity\PullRequestsInterface;

/**
 * Class PullRequestsFactory
 * @package App\Factory
 */
class PullRequestsFactory implements PullRequestsFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function create(int $open, int $closed): PullRequestsInterface
    {
        return new PullRequests($open, $closed);
    }
}