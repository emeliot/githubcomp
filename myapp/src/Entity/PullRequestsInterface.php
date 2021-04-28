<?php

namespace App\Entity;

/**
 * Interface PullRequestsInterface
 * @package App\Entity
 */
interface PullRequestsInterface
{
    /**
     * @return int
     */
    public function getOpen(): int;

    /**
     * @return int
     */
    public function getClosed(): int;
}