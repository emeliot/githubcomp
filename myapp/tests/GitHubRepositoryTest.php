<?php

use App\Client\GitHubClientInterface;
use App\Entity\PullRequests;
use App\Entity\RepoInterface;
use App\Factory\RepoFactoryInterface;
use App\Repository\GitHubRepository;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class GitHubRepositoryTest extends TestCase
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function setUp(): void
    {
        $this->faker = Faker\Factory::create();
    }

    public function testCompare()
    {
        $forks = $this->faker->numberBetween(1, 10000);
        $stars = $this->faker->numberBetween(1, 10000);
        $watchers = $this->faker->numberBetween(1, 10000);
        $datetimeStr = $this->faker->date(\DateTime::RFC3339);
        $lastRelease = \DateTime::createFromFormat(\DateTime::RFC3339, $datetimeStr);
        $pullRequestOpen = $this->faker->numberBetween(1, 10000);
        $pullRequestClosed = $this->faker->numberBetween(1, 10000);
        $pullRequest = new PullRequests($pullRequestOpen, $pullRequestClosed);

        $repoInfo = [
            'updated_at' => $datetimeStr,
            'stargazers_count' => $stars,
            'watchers_count' => $watchers,
            'forks_count' => $forks,
            'open_issues_count' => $pullRequestOpen,
        ];

        $repo = Mockery::mock(RepoInterface::class);
        $repo->shouldReceive('getForks')->andReturn($forks);
        $repo->shouldReceive('getStars')->andReturn($stars);
        $repo->shouldReceive('getWatchers')->andReturn($watchers);
        $repo->shouldReceive('getLastRelease')->andReturn($lastRelease);
        $repo->shouldReceive('getPullRequests')->andReturn($pullRequest);

        $repoFactory = Mockery::mock(RepoFactoryInterface::class);
        $repoFactory->shouldReceive('create')->andReturn($repo);
        $gitHubClient = Mockery::mock(GitHubClientInterface::class);
        $gitHubClient->shouldReceive('getRepoInfo')->andReturn($repoInfo);
        $gitHubClient->shouldReceive('getPullRequestsCount')->andReturn($pullRequestClosed);
        $logger = Mockery::mock(LoggerInterface::class);
        $gitHubRepository = new GitHubRepository($repoFactory, $gitHubClient, $logger);

        $data = [
            0 => "guzzle/guzzle",
            1 => "symfony/symfony",
            2 => "docker/compose",
        ];

        $actual = $gitHubRepository->compare($data);

        $this->assertEquals($this->getExpectedResult($repo), $actual);
    }

    private function getExpectedResult(RepoInterface $repo): array
    {
        return [
            "repositories" => [
                "guzzle/guzzle" => $repo,
                "symfony/symfony" => $repo,
                "docker/compose" => $repo
            ],
            "comparison" => [
                "forks" => [
                    "guzzle/guzzle" => $repo->getForks(),
                    "docker/compose" => $repo->getForks(),
                    "symfony/symfony" => $repo->getForks(),
                ],
                "stars" => [
                    "guzzle/guzzle" => $repo->getStars(),
                    "docker/compose" => $repo->getStars(),
                    "symfony/symfony" => $repo->getStars(),
                ],
                "watchers" => [
                    "guzzle/guzzle" => $repo->getWatchers(),
                    "docker/compose" => $repo->getWatchers(),
                    "symfony/symfony" => $repo->getWatchers(),
                ],
                "last_release" => [
                    "docker/compose" => $repo->getLastRelease(),
                    "guzzle/guzzle" => $repo->getLastRelease(),
                    "symfony/symfony" => $repo->getLastRelease(),
                ],
                "pull_requests" => [
                    "docker/compose" => $repo->getPullRequests()->jsonSerialize(),
                    "guzzle/guzzle" => $repo->getPullRequests()->jsonSerialize(),
                    "symfony/symfony" => $repo->getPullRequests()->jsonSerialize(),
                ]
            ]
        ];
    }

    public function testEmptyDataException()
    {
        $msg = 'No comparison data provided';
        
        $this->expectExceptionMessage($msg);
        
        $logger = Mockery::mock(LoggerInterface::class);
        $logger->shouldReceive('error')->andReturn($msg);

        $gitHubRepository = new GitHubRepository(Mockery::mock(
            RepoFactoryInterface::class),
            Mockery::mock(GitHubClientInterface::class),
            $logger
        );
        $gitHubRepository->compare([]);
    }
}