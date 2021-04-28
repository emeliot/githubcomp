<?php

namespace App\Entity;

/**
 * Interface RepoInterface
 * @package App\Entity
 */
interface RepoInterface
{
    /**
     * @return int
     */
    public function getForks(): int;

    /**
     * @return int
     */
    public function getStars(): int;

    /**
     * @return int
     */
    public function getWatchers(): int;

    /**
     * @return \DateTime|null
     */
    public function getLastRelease(): ?\DateTime;

    /**
     * @return PullRequestsInterface
     */
    public function getPullRequests(): PullRequestsInterface;
}