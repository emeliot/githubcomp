<?php

namespace App\Client;

/**
 * Interface GitHubClientInterface
 * @package App\Client
 */
interface GitHubClientInterface
{
    const GITHUB_ADDRESS = 'https://api.github.com';

    /**
     * @param string $repo
     * @return array
     */
    public function getRepoInfo(string $repo): array;

    /**
     * @param string $repo
     * @param string $status
     * @return int
     */
    public function getPullRequestsCount(string $repo, string $status): int;
}