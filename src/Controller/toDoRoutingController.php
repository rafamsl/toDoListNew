<?php

namespace App\Controller;

use App\Service\toDoRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class toDoController extends AbstractController
{
    public function __construct(private toDoRepository $toDoRepository){

    }
    #[Route('/api/getall', name : 'app_getall', methods: ['GET'])]
    public function getAll()
    {
        $toDos = $this->toDoRepository->findAll();
        return $this->json($toDos);
    }

    #[Route('/api/getone/{id<\d+>}', name : 'app_getone', methods: ['GET'])]
    public function getOne(int $id, LoggerInterface $logger)
    {
        $toDo = $this->toDoRepository->findOne();
        $logger->info('Returning API response for ToDo {toDo} ',['toDo'=>$toDo]);
        return $this->json($toDo);
    }

}