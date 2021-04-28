<?php

namespace App\Controller;

use App\Repository\GitHubRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    /**
     * @param GitHubRepositoryInterface $gitHubRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function info(GitHubRepositoryInterface $gitHubRepository, Request $request)
    {
        $owner = $request->get('ownerName') ?? '';
        $package = $request->get('packageName') ?? '';
        
        try {
            return $this->json($gitHubRepository->info(sprintf('%s/%s', $owner, $package)));
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param GitHubRepositoryInterface $gitHubRepository
     * @param Request $request
     * @return JsonResponse
     */
    public function compare(GitHubRepositoryInterface $gitHubRepository, Request $request)
    {
        try {
            return $this->json($gitHubRepository->compare($request->get('compare') ?? []));
        } catch (\Exception $e) {
            return $this->json($e->getMessage(), $e->getCode());
        }
    }
}