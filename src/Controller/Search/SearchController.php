<?php

namespace App\Controller\Search;

use App\Form\SearchInputType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SearchController extends AbstractController
{

    public function __construct(private EntityManagerInterface $entityManagerInterface) {}

    #[Route('app/search', name: 'app_search')]
    public function searchData(Request $request): Response
    {
        $results = [
            'users' => [],
            'podcasts' => [],
        ];

        $searchValue = $request->get('query');

        if ($searchValue) {

            $users = $this->entityManagerInterface->createQueryBuilder()
                ->select('u')
                ->from('App\Entity\User', 'u')
                ->where('LOWER(u.username) LIKE LOWER(:term)')
                ->setParameter('term', '%' . $searchValue . '%')
                ->getQuery()
                ->getResult();

            $podcasts = $this->entityManagerInterface->createQueryBuilder()
                ->select('p')
                ->from('App\Entity\Podcast', 'p')
                ->where('LOWER(p.name) LIKE LOWER(:term)')
                ->setParameter('term', '%' . $searchValue . '%')
                ->getQuery()
                ->getResult();

            $results = [
                'users' => $users,
                'podcasts' => $podcasts
            ];
        }

        return $this->render('search/index.html.twig', [
            'searchValue' => $searchValue,
            'results' => $results
        ]);
    }
}
