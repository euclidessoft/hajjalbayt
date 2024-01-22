<?php

namespace App\Controller\Security;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Form\RegistrationType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\UserType;
use App\Form\changePasswordType;
use App\Repository\UserRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Agence;

/**
 * @Route("/{_locale}")
 */
class securityController extends AbstractController
{
    /**
     * @Route("/registration", name="security_register")
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder, TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mail)
    { $manager =$this->getDoctrine()->getManager();
      if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
       {
            $user = new User();
            $form = $this->createForm(RegistrationType::class, $user);
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                 $hashpass = $encoder->encodePassword($user, 'Moda2020');
                 //$hashpass = $encoder->encodePassword($user, $user->getPassword());
                 //$password = $user->getPassword();
                 $user->setPassword($hashpass);
                 $agence = $manager->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId());
                 switch($user->getFonction()){
                     case 'employer':{
                         $user->setRoles(['ROLE_EMPLOYER']);
                         break;
                     }
                     case 'caissier':{
                         $user->setRoles(['ROLE_EMPLOYER']);
                         $user->setCaisse(true);
                         break;
                     }
                     case 'medecin':{
                         $user->setRoles(['ROLE_MEDECIN']);
                         break;
                     }
                     case 'administrateur':{
                         $user->setRoles(['ROLE_CHEF']);
                         break;
                     }
                     case 'proprietaire':{
                         $user->setRoles(['ROLE_PROPRIETAIRE']);
                         break;
                     }
                 }
                 $user->setAgence($agence);
                 // envoie mail
                 $token = $tokenGenerator->generateToken();
                 $user->setResetToken($token);
                 $manager->persist($user);
                 $manager->flush();
                 $url = $this->generateUrl('security_activation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                 
                 $message = (new \Swift_Message('Activation compte utilisateur'))
                 ->setFrom('support@hajjalbayt.com')
                 ->setTo($user->getEmail())
                 ->setBody($this->renderView('hajjalbayt/active.html.twig', [ 'url' => $url ]), 'text/html');
//                 ->setBody("Cliquez sur le lien suivant pour activer votre compte utilisasateur ".$url, 'text/html');
//                $message = (new \Swift_Message('Activation compte utilisateur'))
//                 ->setFrom('support@euclideservices.com')
//                 ->setTo($user->getEmail())
//                 ->setBody($this->renderView('licence/facture.html.twig'), 'text/html');
                 
                 $mail->send($message);
                 // fin envoie mail
                
                $this->addFlash('notice', 'Utilisateur cree, un mail a ete envoye a son adresse mail pour l\'activation du compte');
                return $this->redirectToRoute('security_profile');
            }
            
            $response = $this->render('security/security/index.html.twig', [
                'form' => $form->createView(),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
       }
        else
        {
            $this->addFlash('notice', 'Vous n\'avez pas le droit d\'accede a cette partie de l\'application');
            return $this->redirectToRoute('security_login');
        }
    }
    
    /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $auth)
    { 
        $error = $auth->getLastAuthenticationError();
        $last_user = $auth->getLastUsername();
        
        $response = $this->render('security/security/login.html.twig',[
            'error' => $error,
            'last_user' => $last_user,
        ]);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCache([
            'max_age' => 0,
            'private' => true,
        ]);
        return $response;
    }
    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout()
    {
        
    }
    /**
     * @Route("/profile", name="security_profile")
     */
    public function profile()
    {
       if($this->getUser() !== null) 
       {
       
           $response = $this->render('security/security/profile.html.twig',[
               'user' => $this->getUser(),
           ]);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCache([
            'max_age' => 0,
            'private' => true,
        ]);
        return $response;
       }
       else
       {
           $this->addFlash('notice', 'Vous n\'avez pas le droit d\'accede a cette partie de l\'application');
           return $this->redirectToRoute('security_login');
       }
    }
    
    /**
     * @Route("/edit_profile", name="security_profile_edit")
     */
    public function edit(Request $request)
    {
        if($this->getUser() !== null)
        {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($this->getUser()->getId());
        $form = $this->createForm(UserType::class, $user);
        $form->remove('username');
        
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid())
        {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Profil modifié avec succès');
            return $this->redirectToRoute('security_profile', ['id' => $user->getId()]);
            
        } 
        $response = $this->render('security/security/edit.html.twig',[
            'form' => $form->createView(),
        ]);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCache([
            'max_age' => 0,
            'private' => true,
        ]);
        return $response;
    }
    else
    {
        $this->addFlash('notice', 'Vous n\'avez pas le droit d\'accede a cette partie de l\'application');
        return $this->redirectToRoute('security_login');
    }
    }
    
    
    
    /**
     * @Route("/ChangePassword", name="security_change_password")
     */
    public function change(Request $request, UserPasswordEncoderInterface $encoder)
    {
        if($this->getUser() !== null)
        {
        $userinit =  new User();
        $form = $this->createForm(changePasswordType::class, $userinit);
        
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
           
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->find($this->getUser()->getId());
            if($encoder->isPasswordValid($user, $userinit->getTest()))
            {
                $newpassword = $encoder->encodePassword($user, $userinit->getPassword());
                $user->setPassword($newpassword);
                $em->persist($user);
                $em->flush();
                $this->addFlash('change', 'Votre mot de passe  a ete modifié, reconnectez vous!');
                return $this->redirectToRoute('security_login');
            }
           else
           {
               $form->addError(new FormError('Ancien mot de passe incorrecte'));
           }
            
        }
        $response =$this->render('security/security/changepassword.html.twig',[
            'form' => $form->createView(),
        ]);
        $response->setSharedMaxAge(0);
        $response->headers->addCacheControlDirective('no-cache', true);
        $response->headers->addCacheControlDirective('no-store', true);
        $response->headers->addCacheControlDirective('must-revalidate', true);
        $response->setCache([
            'max_age' => 0,
            'private' => true,
        ]);
        return $response;
        
    }
    else
    {
        $this->addFlash('notice', 'Vous n\'avez pas le droit d\'accede a cette partie de l\'application');
        return $this->redirectToRoute('security_login');
    }
    }
    /**
     * @Route("/forgottenPassword", name="security_forgotten_password")
     */
    public function forgotten(Request $request, TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mail)
    {
        if ($request->isMethod('POST')) {
            
          $email = $request->request->get('_mail');
          
          $em = $this->getDoctrine()->getManager();
          $user = $em->getRepository(User::class)->findOneByEmail($email);
          if($user === null)
          {
              $this->addFlash('notice', 'Adresse email inconnue');
              return $this->redirectToRoute('security_forgotten_password');
          }
          $token = $tokenGenerator->generateToken();
          $user->setResetToken($token);
          $em->persist($user);
          $em->flush();
          $url = $this->generateUrl('security_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
          
          $message = (new \Swift_Message('Réinitialisation mot de passe'))
          ->setFrom('support@hajjalbayt.com')
          ->setTo($user->getEmail())
          ->setBody($this->renderView('hajjalbayt/forget.html.twig', [ 'url' => $url ]), 'text/html');
//          ->setBody("Cliquez sur le lien suivant pour réinitialiser votre mot de passe ".$url, 'text/html');
          
          $mail->send($message);
          $this->addFlash('change', 'Un message a été envoyé à votre adresse email, veuillez consulter votre boite de réception');
        }
        
       
         $response = $this->render('security/security/forget.html.twig');
         
         $response->setSharedMaxAge(0);
         $response->headers->addCacheControlDirective('no-cache', true);
         return $response;
    }
    
    /**
     * @Route("/Users", name="security_users")
     */
    public function users(UserRepository $userRepository)
    {
         if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
            {
               
                $response = $this->render('security/security/users.html.twig', [
                    'users' => $userRepository->findby(['agence' => $this->getUser()->getAgence()->getId()]),
                ]);
                $response->setSharedMaxAge(0);
                $response->headers->addCacheControlDirective('no-cache', true);
                $response->headers->addCacheControlDirective('no-store', true);
                $response->headers->addCacheControlDirective('must-revalidate', true);
                $response->setCache([
                    'max_age' => 0,
                    'private' => true,
                ]);
                return $response;
            }
            else
            {
                $this->addFlash('notice', 'Vous n\'avez pas le droit d\'accede a cette partie de l\'application');
                return $this->redirectToRoute('security_login');
            }
        
    }
    
    /**
     * @Route("/User/{name}", name="security_user")
     */
    public function user(UserRepository $userRepository,$name)
    {
     if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
        {
            
            $response = $this->render('security/security/user.html.twig', [
                'user' => $userRepository->findOneBy(['username' => $name , 'agence' => $this->getUser()->getAgence()->getId()]),
            ]);
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
        else
        {
            $this->addFlash('notice', 'Vous n\'avez pas le droit d\'accede a cette partie de l\'application');
            return $this->redirectToRoute('security_login');
        }
      
    }
    
    /**
     * @Route("/ResetPassword/{token}", name="security_reset_password")
     */
    public function reset(Request $request, string $token, UserPasswordEncoderInterface $encoder)
    {
        $userinit =  new User();
        $form = $this->createForm(changePasswordType::class, $userinit);
        $form->remove('test');
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {         
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneByResetToken($token);
            if($user === null)
            {
                $this->addFlash('notice', 'Chaine de réinitialisation invalide');
                return $this->redirectToRoute('security_login');
            }
            $user->setResetToken(null);
            $newpassword = $encoder->encodePassword($user,$userinit->getPassword());
            $user->setPassword($newpassword);
            $em->persist($user);
            $em->flush();
            $this->addFlash('change', 'Reinitialisation reussie');
            $response = $this->redirectToRoute('security_login');
            $response->setSharedMaxAge(0);
            $response->headers->addCacheControlDirective('no-cache', true);
            $response->headers->addCacheControlDirective('no-store', true);
            $response->headers->addCacheControlDirective('must-revalidate', true);
            $response->setCache([
                'max_age' => 0,
                'private' => true,
            ]);
            return $response;
        }
        
        
        return $this->render('security/security/reset.html.twig', ['form' => $form->createView()]
            );
    }
    
    /**
     * @Route("/Activation/{token}", name="security_activation")
     */
    public function active(Request $request, string $token)
    {
        
            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository(User::class)->findOneByResetToken($token);
            if($user === null)
            {
                $this->addFlash('notice', 'Chaine d\'activation invalide');
                return $this->redirectToRoute('security_login');
            }
            $user->setEnabled(true);
            $em->persist($user);
            $em->flush();
            $this->addFlash('notice', 'Compte active, veuillez  definir votre mot de passe pour la premiere connexion');
            return $this->redirectToRoute('security_reset_password', ['token' => $token]);
        
        
        return $this->render('security/security/reset.html.twig', ['form' => $form->createView()]
            );
    }
    
    /**
     * @Route("/UserDisable", name="security_user_disable")
     */
    public function UserdisableAction(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
        {
            $em = $this->getDoctrine()->getManager();
            //$users = $em->getrepository(User::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
            $user =  $em->getrepository(User::class)->find($request->get('usr'));
            if(!$this->get('security.authorization_checker')->isGranted('ROLE_CHEF' ) || $user->getFonction() != 'proprietaire')
            {
                $user->setEnabled(false);
                $em->persist($user);
            }
            $em->flush();
            $res['ok']= 'ok';
            $response = new Response();
            $response->headers->set('content-type','application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }
        else return $this->redirect($this->generateUrl('security_login'));
    }
    
    /**
     * @Route("/UserEnable", name="security_user_enable")
     */
    public function UserenableAction(Request $request)
    {
        
        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
        {
            $em = $this->getDoctrine()->getManager();
            //$users = $em->getrepository(User::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
            $user =  $em->getrepository(User::class)->find($request->get('usr'));
            if(!$this->get('security.authorization_checker')->isGranted('ROLE_CHEF' ) || $user->getFonction() != 'proprietaire')
            {
                $user->setEnabled(true);
                $em->persist($user);
            }
            $em->flush();
            $res['ok']= 'ok';
            $response = new Response();
            $response->headers->set('content-type','application/json');
            $re = json_encode($res);
            $response->setContent($re);
            return $response;
        }
        else return $this->redirect($this->generateUrl('security_login'));
    }
}
