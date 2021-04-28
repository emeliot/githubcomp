<?php

namespace App\Entity;

/**
 * Class Repo
 * @package App\Entity
 */
class Repo implements \JsonSerializable, RepoInterface
{
    /**
     * @var int
     */
    private $forks;

    /**
     * @var int
     */
    private $stars;

    /**
     * @var int
     */
    private $watchers;

    /**
     * @var \DateTime|null
     */
    private $lastRelease;

    /**
     * @var PullRequestsInterface
     */
    private $pullRequests;

    /**
     * @param int $forks
     * @param int $stars
     * @param int $watchers
     * @param \DateTime|null $lastRelease
     * @param PullRequestsInterface $pullRequests
     */
    public function __construct(
        int $forks,
        int $stars,
        int $watchers,
        ?\DateTime $lastRelease,
        PullRequestsInterface $pullRequests
    )
    {
        $this->setForks($forks);
        $this->setStars($stars);
        $this->setWatchers($watchers);
        $this->setLastRelease($lastRelease);
        $this->setPullRequests($pullRequests);
    }

    /**
     * @inheritDoc
     */
    public function getForks(): int
    {
        return $this->forks;
    }

    /**
     * @param int $forks
     */
    private function setForks(int $forks): void
    {
        $this->forks = $forks;
    }

    /**
     * @inheritDoc
     */
    public function getStars(): int
    {
        return $this->stars;
    }

    /**
     * @param int $stars
     */
    private function setStars(int $stars): void
    {
        $this->stars = $stars;
    }

    /**
     * @inheritDoc
     */
    public function getWatchers(): int
    {
        return $this->watchers;
    }

    /**
     * @param int $watchers
     */
    private function setWatchers(int $watchers): void
    {
        $this->watchers = $watchers;
    }

    /**
     * @return \DateTime|null
     */
    public function getLastRelease(): ?\DateTime
    {
        return $this->lastRelease;
    }

    /**
     * @param \DateTime|null $lastRelease
     */
    private function setLastRelease(?\DateTime $lastRelease): void
    {
        $this->lastRelease = $lastRelease;
    }

    /**
     * @inheritDoc
     */
    public function getPullRequests(): PullRequestsInterface
    {
        return $this->pullRequests;
    }

    /**
     * @param PullRequestsInterface $pullRequests
     */
    private function setPullRequests(PullRequestsInterface $pullRequests): void
    {
        $this->pullRequests = $pullRequests;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'forks' => $this->getForks(),
            'stars' => $this->getStars(),
            'watchers' => $this->getWatchers(),
            'last_release' => $this->getLastRelease(),
            'pull_requests' => $this->getPullRequests()->jsonSerialize()
        ];
    }
}