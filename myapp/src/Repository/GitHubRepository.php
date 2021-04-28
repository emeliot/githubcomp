<?php

namespace App\Repository;

use App\Client\GitHubClientInterface;
use App\Entity\PullRequests;
use App\Entity\RepoInterface;
use App\Factory\RepoFactoryInterface;
use Psr\Log\LoggerInterface;

/**
 * Class GitHubRepository
 * @package App\Repository
 */
class GitHubRepository implements GitHubRepositoryInterface
{
    /**
     * @var RepoFactoryInterface
     */
    private $cmpFactory;
    
    /**
     * @var GitHubClientInterface
     */
    private $gitHubClient;
    
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * GitHubRepository constructor.
     * @param RepoFactoryInterface $cmpFactory
     * @param GitHubClientInterface $gitHubClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        RepoFactoryInterface $cmpFactory,
        GitHubClientInterface $gitHubClient,
        LoggerInterface $logger
    ) {
        $this->cmpFactory = $cmpFactory;
        $this->gitHubClient = $gitHubClient;
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function info(string $repoName): array
    {
        if (empty($repoName)) {
            $msg = "Repository name can't be empty";
            
            $this->logger->error($msg);
            
            throw new \Exception($msg);
        }

        return $this->gitHubClient->getRepoInfo($repoName);
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function compare(array $data): array
    {
        if (empty($data)) {
            $msg = 'No comparison data provided';
            
            $this->logger->error($msg);

            throw new \Exception($msg);
        }

        $result = ['comparison' => []];
        foreach ($data as $repoName) {
            $repoObj = $this->cmpFactory->create(
                $this->gitHubClient->getRepoInfo($repoName),
                $this->gitHubClient->getPullRequestsCount($repoName, PullRequests::TYPE_CLOSED),
            );

            $result['repositories'][$repoName] = $repoObj;
            $this->groupSameElementsUnderOneKey($repoObj, $repoName, $result['comparison']);
        }

        $this->sortGroupsByValues($result['comparison']);

        return $result;
    }

    /**
     * @param RepoInterface $repoObj
     * @param string $repoName
     * @param array $cmp
     */
    private function groupSameElementsUnderOneKey(RepoInterface $repoObj, string $repoName, array &$cmp): void
    {
        $cmp['forks'][$repoName] = $repoObj->getForks();
        $cmp['stars'][$repoName] = $repoObj->getStars();
        $cmp['watchers'][$repoName] = $repoObj->getWatchers();
        $cmp['last_release'][$repoName] = $repoObj->getLastRelease();
        $cmp['pull_requests'][$repoName] = $repoObj->getPullRequests()->jsonSerialize();
    }

    /**
     * @param $comparison
     */
    private function sortGroupsByValues(&$comparison): void
    {
        foreach ($comparison as $key => $val) {
            array_multisort($comparison[$key]);
        }
    }
}