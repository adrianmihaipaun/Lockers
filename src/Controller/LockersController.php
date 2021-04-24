<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Crons\Lockers\Sameday\Sameday as SamedayLockers;
use App\Entity\Lockers;
use App\Crons\Lockers\LockerCredentials;
use Exception;
use Symfony\Component\Config\FileLocator;

use App\Entity\LockersBoxesTypes;
use App\Repository\LockersBoxesTypesRepository;

class LockersController extends AbstractController
{
    /**
     * @Route("/lockers", name="lockers")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $lockersBoxesTypesRepository = $entityManager->getRepository(LockersBoxesTypes::class);

        $types = $lockersBoxesTypesRepository->findAll();
        foreach($types as $type) {
            dd($type);
            foreach ($type as $attr) {
                dd($attr);
            }
            
        }
        dd('x');
        $locker = new SamedayLockers(
            new LockerCredentials(
                "milleniumTEST",
                "PpfGWl5wrw==",
                "https://sameday-api.demo.zitec.com"
            ), 
            $this->entityManager
        );

        try {
            $locker->storeLockers();
        } catch (\Exception $e) {
            throw new Exception($e->getMessage());
        }
        
        dd($locker->getResponse());


        $entityManager = $this->getDoctrine()->getManager();

        $locker = new Lockers();

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($locker);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return $this->render('lockers/index.html.twig', [
            'controller_name' => 'LockersController',
        ]);
    }
}
