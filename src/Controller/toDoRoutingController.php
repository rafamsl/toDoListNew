<?php

namespace App\Controller;

use App\Entity\ToDo;
use App\Service\toDoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class toDoRoutingController extends AbstractController
{
    public function __construct(private \App\Repository\ToDoRepository $toDoRepository){

    }
    #[Route('/api/getall', name : 'app_getall', methods: ['GET'])]
    public function getAll(LoggerInterface $logger, Request $request)
    {

        $toDos = $this->toDoRepository->findByQuery($request->query);
        $arrayCollection = array();
        foreach($toDos as $toDo) {
            $arrayCollection[] = $toDo->getString();
        }
        $logger->info('Returning API response for ToDos {toDos} ',['toDos'=>$toDos]);
        return $this->json($arrayCollection);
    }

    #[Route('/api/getone/{id<\d+>}', name : 'app_getone', methods: ['GET'])]
    public function getOne(LoggerInterface $logger, ToDo $toDo)
    {
        if (!$toDo) {
            throw $this->createNotFoundException('To Do not found');
        }
        $logger->info('Returning API response for ToDo {toDo} ',['toDo'=>$toDo]);
        return $this->json($toDo->getString());
    }
    #[Route('/api/createone', name : 'app_new', methods: ['POST'])]
    public function createOne(LoggerInterface $logger, Request $request)
    {
        $newToDo = $request->getContent();
        $this->toDoRepository->newToDo(json_decode($newToDo));
        $logger->info('Creating ToDo',);
        return new JsonResponse(['success'=>true]);
    }
    #[Route('/api/editone/{id<\d+>}', name : 'app_editone', methods: ['PUT'])]
    public function editOne(LoggerInterface $logger, ToDo $toDo, Request $request){
        if (!$toDo) {
            throw $this->createNotFoundException('To Do not found');
        }
        $newToDo = $request->getContent();
        $logger->info('Editing ToDo {toDo}',['toDo'=>$toDo->getId()]);
        $this->toDoRepository->editToDo($toDo,json_decode($newToDo));
        return new JsonResponse(['success'=>true]);
    }
    #[Route('/api/removeone/{id<\d+>}', name : 'app_removeone', methods: ['DELETE'])]
    public function removeOne(LoggerInterface $logger, ToDo $toDo)
    {
        if (!$toDo) {
            throw $this->createNotFoundException('To Do not found');
        }
        $this->toDoRepository->remove($toDo, true);
        $logger->info('Deleting {toDo} ',['toDo'=>$toDo->getId()]);
        return new JsonResponse(['success'=>true]);
    }
    // Route/Command to move a ToDo to status 2/done
    #[Route('/api/finishone/{id<\d+>}', name : 'app_finishone', methods: ['PUT'])]
    public function finishOne(LoggerInterface $logger, ToDo $toDo){
        if (!$toDo) {
            throw $this->createNotFoundException('To Do not found');
        }
        $this->toDoRepository->finishToDo($toDo);
        $logger->info('Finishing ToDo {toDo}',['toDo'=>$toDo->getId()]);
        return new JsonResponse(['success'=>true]);
    }

    // Route/Command to move all expired ToDos to status expired/3
    #[Route('/api/expiretodos', name : 'app_expire', methods: ['PUT'])]
    public function expire(LoggerInterface $logger){
        $this->toDoRepository->expireToDos();
        $logger->info('Expiring ToDos');
        return new JsonResponse(['success'=>true]);
    }

}