<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route ("/user/{lastname}", name="blog", methods={"GET"})
     *
     * @param $id
     * @return JsonResponse
     */
    public function loadUserAction($lastname)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(["lastname" => $lastname]);

        if (empty($user)) {
            return new JsonResponse(null, 404);
        }

        $resultat = [];
        $resultat["firstname"] = $user->getFirstname();
        $resultat["getLastname"] = $user->getLastname();
        $tabComments = [];
        foreach ($user->getComments() as $comment) {
            $tabComments[] = [

                "title" => $comment->getTitle(),
                "comment" => $comment->getDescription()
            ];
        }

        $resultat["comments"] = $tabComments;
        return new JsonResponse($resultat);
    }

    /**
     * @Route ("/user/{lastname}", name="delete_user", methods={"DELETE"})
     */
    public function deleteUser($lastname)
    {
        $user = $this->entityManager->getRepository(User::class)
            ->findOneBy(["lastname" => $lastname]);


        if (empty($user)) {
            return new JsonResponse("no", 404);
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return new JsonResponse("ok", 200);


    }

    public function addUserAction()
    {
        $user = $this ->entityManager->getRepository(User::class);
    }

}