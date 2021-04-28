<?php

use App\Factory\PullRequestsFactory;
use App\Factory\RepoFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class GitHubFactoryTest
 */
class GitHubFactoryTest extends TestCase
{
    /**
     * @var \Faker\Generator
     */
    private $faker;

    public function setUp(): void
    {
        $this->faker = Faker\Factory::create();
    }
    
    public function testCreateRepoObject()
    {
        $forks = $this->faker->numberBetween(1, 10000);
        $stars = $this->faker->numberBetween(1, 10000);
        $watchers = $this->faker->numberBetween(1, 10000);
        $datetimeStr = $this->faker->date(\DateTime::RFC3339);
        $lastRelease = \DateTime::createFromFormat(\DateTime::RFC3339, $datetimeStr);
        $pullRequestOpen = $this->faker->numberBetween(1, 10000);
        $pullRequestClosed = $this->faker->numberBetween(1, 10000);

        $repoInfo = [
            'updated_at' => $datetimeStr,
            'stargazers_count' => $stars,
            'watchers_count' => $watchers,
            'forks_count' => $forks,
            'open_issues_count' => $pullRequestOpen,
        ];

        $pullRequestFactory = new PullRequestsFactory();
        $repoFactory = new RepoFactory($pullRequestFactory);
        $actual = $repoFactory->create($repoInfo, $pullRequestClosed);

        $this->assertEquals(
            [
                'forks' => $forks,
                'stars' => $stars,
                'watchers' => $watchers,
                'last_release' => $lastRelease,
                'pull_requests' => [
                    'open' => $pullRequestOpen,
                    'closed' => $pullRequestClosed
                ]
            ],
            $actual->jsonSerialize()
        );
    }
}