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

        $form = $this->createForm(SearchInputType::class);
        $form->handleRequest($request);

        $results = [
            'users' => [],
            'podcasts' => [],
        ];

        if ($form->isSubmitted() && $form->isValid()) {
            $searchValue = $form->get('query')->getData();

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

                $categories = $this->entityManagerInterface->createQueryBuilder()
                    ->select('c')
                    ->from('App\Entity\Category', 'c')
                    ->where('LOWER(c.name) LIKE LOWER(:term)')
                    ->setParameter('term', '%' . $searchValue . '%')
                    ->getQuery()
                    ->getResult();

                $results = [
                    'users' => $users,
                    'podcasts' => $podcasts
                ];
            }
        }
        return $this->render('search/index.html.twig', [
            'form' => $form,
            'results' => $results
        ]);
    }
}
