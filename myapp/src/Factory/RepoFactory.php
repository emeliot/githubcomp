<?php

namespace App\Factory;

use App\Entity\RepoInterface;
use App\Entity\Repo;

/**
 * Class RepoFactory
 * @package App\Factory
 */
class RepoFactory implements RepoFactoryInterface
{
    /**
     * @var PullRequestsFactoryInterface
     */
    private $pullRequestsFactory;


    /**
     * RepoFactory constructor.
     * @param PullRequestsFactoryInterface $pullRequestsFactory
     */
    public function __construct(PullRequestsFactoryInterface $pullRequestsFactory)
    {
        $this->pullRequestsFactory = $pullRequestsFactory;
    }

    /**
     * @inheritDoc
     */
    public function create(array $repoInfo, int $closedPullRequests): RepoInterface
    {
        $lastRelease = $repoInfo['updated_at']
            ? \DateTime::createFromFormat(\DateTime::RFC3339, $repoInfo['updated_at'])
            : null;
        
        return new Repo(
            $repoInfo['forks_count'] ?? 0,
            $repoInfo['stargazers_count'] ?? 0,
            $repoInfo['watchers_count'] ?? 0,
            $lastRelease != false ? $lastRelease : null,
            $this->pullRequestsFactory->create(
                $repoInfo['open_issues_count'],
                $closedPullRequests
            )
        );
    }
}