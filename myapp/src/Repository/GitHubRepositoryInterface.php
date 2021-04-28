<?php

namespace App\Repository;

/**
 * Interface GitHubRepositoryInterface
 */
interface GitHubRepositoryInterface
{
    /**
     * @param string $repoName
     * @return array
     */
    public function info(string $repoName): array;

    /**
     * @param array $data
     * @return array
     */
    public function compare(array $data): array;
}