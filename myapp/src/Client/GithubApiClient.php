<?php

namespace App\Client;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpClient\HttpClient;

/**
 * Class GithubApiClient
 * @package App\Client
 */
class GithubApiClient implements GitHubClientInterface
{
    /**
     * @var HttpClient
     */
    private $httpClient;
    
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * GithubApiClient constructor.
     * @param HttpClient $httpClient
     * @param LoggerInterface $logger
     */
    public function __construct(
        HttpClient $httpClient,
        LoggerInterface $logger
    ) {
        $this->httpClient = $httpClient::create();
        $this->logger = $logger;
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function getRepoInfo(string $repo): array
    {
        try {
            $res = $this->httpClient->request(
                'GET',
                sprintf('%s/%s/%s', self::GITHUB_ADDRESS, 'repos', $repo)
            );

            return $res->toArray();

        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception(
                'Something went wrong. Please contact with administrator.',
                $e->getCode(),
                $e->getPrevious()
            );
        }
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function getPullRequestsCount(string $repo, string $status): int
    {
        try {
            $res = $this->httpClient->request(
                'GET',
                sprintf('%s/search/issues?q=%s:%s+type:issue+state:%s', self::GITHUB_ADDRESS, 'repo', $repo, $status)
            );

            return $res->toArray()['total_count'] ?? 0;

        } catch (\Throwable $e) {
            $this->logger->error($e->getMessage());
            throw new \Exception(
                'Something went wrong. Please contact with administrator.',
                $e->getCode(),
                $e->getPrevious()
            );
        }
    }
}