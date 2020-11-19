<?php

namespace App\Controller\Api;

use App\Repository\UserRepository;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @Route("")
 */
class LoginController extends AbstractController
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;
    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * LoginController constructor.
     * @param UserPasswordEncoderInterface $encoder
     * @param UserRepository $repository
     */
    public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $repository)
    {
        $this->encoder = $encoder;
        $this->repository = $repository;
    }

    /**
     * @Route("/login", name="api_login" , methods={"POST"})
     */
    public function login(Request $request): Response
    {

        $jsonData = json_decode($request->getContent());
        if ($jsonData === false) {
            throw new AuthenticationException('Data not valid');
        }

        $user = $this->repository->findOneBy(['email' => $jsonData->email]);
        if (is_null($user)) {
            throw new AuthenticationException('Invalid User');
        }

        if (!$this->encoder->isPasswordValid($user, $jsonData->password)) {
            throw new AuthenticationException('Invalid Password');
        }

        $token = JWT::encode(['email' => $user->getEmail()], $_SERVER['JWT_KEY'], 'HS256');

        return $this->json([
            'access_token' => $token
        ]);
    }
}
