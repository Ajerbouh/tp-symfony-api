<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as REST;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class UserController extends AbstractFOSRestController
{
    private $userRepository;
    private $em;

    /**
     * UserController constructor.
     * @param $userRepository
     * @param $em
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $em)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/api/users/{email}")
     * @param User $user
     * @return \FOS\RestBundle\View\View
     */
    public function getApiUser(User $user)
    {
        return $this->view($user);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Get("/api/users")
     */
    public function getApiUsers(){
        $users = $this->userRepository->findAll();

        return $this->view($users);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Post("/api/users")
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @return \FOS\RestBundle\View\View
     */
    public function postApiUser(User $user){
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Rest\View(serializerGroups={"user"})
     * @Rest\Patch("/api/users")
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function patchApiUser(Request $request){
        $user = $this->userRepository->find($this->getUser());
        if ($request->get('firstname') !== null) {
            $user->setFirstname($request->get('firstname'));
        }
        if ($request->get('lastname') !== null) {
            $user->setLastname($request->get('lastname'));
        }
        if ($request->get('email') !== null) {
            $user->setEmail($request->get('email'));
        }
        if ($request->get('apiKey') !== null) {
            $user->setApiKey($request->get('apiKey'));
        }
        if ($request->get('address') !== null) {
            $user->setAddress($request->get('address'));
        }
        if ($request->get('country') !== null) {
            $user->setCountry($request->get('country'));
        }
        if ($request->get('subscription') !== null) {
            $user->setSubscription($request->get('subscription'));
        }
        $this->em->persist($user);
        $this->em->flush();
        return $this->view($user);
    }

    /**
     * @Rest\Delete("/api/users/{id}")
     * @param User $user
     */
    public function deleteApiUser(User $user){
        $this->em->remove($user);
        $this->em->flush();
    }
}
