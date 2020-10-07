<?php

// namespace App\Security;

// use App\Controller\UserController;
// use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as abstractController;
// use Symfony\Component\HttpFoundation\Request;
// use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Security\Core\User\UserInterface;
// use Symfony\Component\Security\Core\User\UserProviderInterface;
// use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
// use Symfony\Component\Security\Core\Exception\AuthenticationException;
// use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
// use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

// class LoginAuthenticator extends AbstractGuardAuthenticator
// {

//     private $passwordEncoder;
//     private $userController;

//     public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserController $userController)
//     {
//         $this->passwordEncoder = $passwordEncoder;
//         $this->userController = $userController;
//     }

//     public function supports(Request $request)
//     {
//         return $request->get("_route") === "user_login" && $request->isMethod("POST");
//     }

//     public function getCredentials(Request $request)
//     {
//         $data =  json_decode($request->getContent(), true);
//         return [
//             'email' => $data["email"],
//             'password' => $data["password"]
//         ];
//     }

//     public function getUser($credentials, UserProviderInterface $userProvider)
//     {
//         return $userProvider->loadUserByUsername($credentials['email']);
//     }

//     public function checkCredentials($credentials, UserInterface $user)
//     {
//         return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
//     }

//     public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
//     {
//         return new JsonResponse([
//             'error' => $exception->getMessageKey(),
//             'result' => false
//         ], 200);
//     }

//     public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
//     {
//         return new JsonResponse([
//             'result' => true,
//         ]);
//     }

//     public function start(Request $request, AuthenticationException $authException = null)
//     {
//         return new JsonResponse([
//             'error' => 'Access Denied',
//             'result' => false
//         ]);
//     }

//     public function supportsRememberMe()
//     {
//         return true;
//     }
// }
