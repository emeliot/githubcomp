<?php

namespace App\Factory;

use App\Entity\PullRequestsInterface;

/**
 * Interface PullRequestsFactoryInterface
 * @package App\Factory
 */
interface PullRequestsFactoryInterface
{
    /**
     * @param int $open
     * @param int $closed
     * @return PullRequestsInterface
     */
    public function create(int $open, int $closed): PullRequestsInterface;
}