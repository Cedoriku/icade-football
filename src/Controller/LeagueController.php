<?php

namespace App\Controller;

use App\Service\ApiHandler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeagueController extends AbstractController
{
    /**
     * @Route("/")
     * @Template()
     * @param ApiHandler $apiHandler
     * @return array []
     */
    public function index(ApiHandler $apiHandler): array
    {
        return [
            'fixtures' => $apiHandler->getMatchesForLeagueOne()
        ];
    }

    /**
     * @Route("/match/{id}", name="match_detail")
     * @Template()
     * @param int $id
     * @param ApiHandler $apiHandler
     * @return array []
     */
    public function match(int $id, ApiHandler $apiHandler): array
    {
        return $apiHandler->getMatchDetail($id);
    }
}