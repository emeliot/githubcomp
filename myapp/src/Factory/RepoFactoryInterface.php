<?php

namespace App\Factory;

use App\Entity\RepoInterface;

/**
 * Interface RepoFactoryInterface
 * @package App\Factory
 */
interface RepoFactoryInterface
{
    /**
     * @param array $repoInfo
     * @param int $closedPullRequests
     * @return RepoInterface
     */
    public function create(array $repoInfo, int $closedPullRequests): RepoInterface;
}