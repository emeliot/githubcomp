<?php

namespace App\Entity;

/**
 * Class PullRequests
 * @package App\Entity
 */
class PullRequests implements \JsonSerializable, PullRequestsInterface
{
    const TYPE_CLOSED = 'closed';

    /**
     * @var int
     */
    private $open;

    /**
     * @var int
     */
    private $closed;

    /**
     * PullRequests constructor.
     * @param int $open
     * @param int $closed
     */
    public function __construct(int $open, int $closed)
    {
        $this->setOpen($open);
        $this->setClosed($closed);
    }

    /**
     * @param int $open
     */
    private function setOpen(int $open): void
    {
        $this->open = $open;
    }

    /**
     * @inheritDoc
     */
    public function getOpen(): int
    {
        return $this->open;
    }

    /**
     * @param int $closed
     */
    private function setClosed(int $closed): void
    {
        $this->closed = $closed;
    }

    /**
     * @inheritDoc
     */
    public function getClosed(): int
    {
        return $this->closed;
    }

    /**
     * @inheritDoc
     */
    public function jsonSerialize()
    {
        return [
            'open' => $this->getOpen(),
            'closed' => $this->getClosed()
        ];
    }
}