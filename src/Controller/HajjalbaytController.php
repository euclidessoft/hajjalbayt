<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\Agence;
use App\Entity\User;
use App\Entity\Facture;
use App\Form\AgenceType;


class HajjalbaytController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder, TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mail)
    {
        $manager =$this->getDoctrine()->getManager();
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->remove('adresse');
        $form->remove('site');
        $form->remove('email1');
        $form->remove('phone1');
        $form->remove('phone2');
        $form->remove('caisse');
        $form->remove('retenu');
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                
                $manager->persist($agence);
                $manager->flush();
                
                $user = new User();
                
                 $hashpass = $encoder->encodePassword($user, 'Moda2020');
                 //$hashpass = $encoder->encodePassword($user, $user->getPassword());
                 //$password = $user->getPassword();
                 $user->setPassword($hashpass);
                 $user->setEmail($agence->getEmail());
                 $user->setPhone($agence->getPhone());
                 $user->setUsername($agence->getResponsable());
                 
                         $user->setRoles(['ROLE_PROPRIETAIRE']);
                   
                 $user->setAgence($agence);
                 // envoie mail
                 $token = $tokenGenerator->generateToken();
                 $user->setResetToken($token);
                 $manager->persist($user);
                 $manager->flush();
                 $url = $this->generateUrl('security_activation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                 
                 $message = (new \Swift_Message('Activation compte utilisateur'))
                 ->setFrom('support@hajjalbayt.com')
                 ->setTo($agence->getEmail())
                 ->setBody("Cliquez sur le lien suivant pour activer votre compte utilisasateur ".$url, 'text/html');
                 
                 $mail->send($message);
                $manager->flush();
                $this->addflash('notice','Enregistrement effectué avec succes. un mail de confirmatiom est envoyé à l\'adresse email indiqué pour l\'activation du compte');
                $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
            $form->remove('adresse');
        $form->remove('site');
        $form->remove('email1');
        $form->remove('phone1');
        $form->remove('phone2');
        $form->remove('caisse');
        $form->remove('retenu');
            return $this->render('Hajjalbayt/index.html.twig', array('form' => $form->createView()));
            }
             
        }
      
        return $this->render('Hajjalbayt/index.html.twig', array('form' => $form->createView())); 
    }
    
    /**
     * @Route("/BuyLicence", name="licence")
     */
    public function licence(Request $request)
    {
            return $this->render('licence/buy-licence.html.twig');
      
    }
    /**
     * @Route("/order", name="order")
     */
    public function order(Request $request)
    {
        
      if($this->getUser() != null)
       {
            
         return $this->render('licence/licence-pack.html.twig');
            
      }
        else return $this->redirectToRoute('connexion');
      
    }
    /**
     * @Route("/Billing", name="billing", methods="POST")
     */
    public function billing(Request $request)
    {
        if($this->getUser() != null)
       {
           if ($request->isMethod('POST')) {
            
          $nombre = $request->request->get('_nombre');
          
          $em = $this->getDoctrine()->getManager();
          $numero = $em->getRepository(Facture::class)->findAll();
//          
//          $message = (new \Swift_Message('Réinitialisation mot de passe'))
//          ->setFrom('support@hajjalbayt.com')
//          ->setTo($user->getEmail())
//          ->setBody("Cliquez sur le lien suivant pour réinitialiser votre mot de passe ".$url, 'text/html');
//          
//          $mail->send($message);
//          $this->addFlash('change', 'Un message a été envoyé à votre adresse email, veuillez consulter votre boite de réception');
          return $this->render('licence/facture.html.twig',[ 'nombre' => $nombre, 'numero' => count($numero)+1]);
               
        }
            
      }
        else return $this->redirectToRoute('connexion');
      
    }
    /**
     * @Route("/validation", name="validation", methods="POST")
     */
    public function validation(Request $request, \Swift_Mailer $mail)
    {
        if($this->getUser() != null)
       {
           if ($request->isMethod('POST')) {
            
          $nombre = $request->request->get('_validation');
          
          $em = $this->getDoctrine()->getManager();
          $numero = $em->getRepository(Facture::class)->findAll();
               $facture = new Facture();
               $facture->setAgence($this->getUser()->getAgence());
               $facture->setNombre($nombre);
               $facture->setUser($this->getUser());
               $em->persist($facture);
               $em->flush();
          
                 $message = (new \Swift_Message('Achat Licence'))
                 ->setFrom('support@hajjalbayt.com')
                 ->setTo($this->getUser()->getAgence()->getEmail())
                 ->setBody($this->renderView('licence/facturedef.html.twig', [ 'nombre' => $nombre, 'numero' => count($numero)+1 ]), 'text/html');
               $mail->send($message);
               $this->addFlash('licence','la facture est envoyée à l\'email de l\'agence. Veuillez verifier vos spam si vous ne voyez pas le message au bout de cinq(5) minutes');          
         $mail->send($message);
//               
//               $message = (new \Swift_Message('Achat Licence'))
//                 ->setFrom('Hajjalbayt@euclideservices.com')
//                 ->setTo('modadg@gmail.com')
//                 ->setBody($this->renderView('licence/facturedef.html.twig', [ 'nombre' => $nombre, 'numero' => count($numero)+1 ]), 'text/html');
//          
//          $mail->send($message);
//               
//               $message = (new \Swift_Message('Achat Licence'))
//                 ->setFrom('sarr43@gmail.com')
//                 ->setTo($this->getUser()->getAgence()->getEmail())
//                 ->setBody($this->renderView('licence/facturedef.html.twig', [ 'nombre' => $nombre, 'numero' => count($numero)+1 ]), 'text/html');
//          
//          $mail->send($message);
         
               
           
               $response = $this->redirectToRoute('Mecque_homepage');
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
            
      }
        else return $this->redirectToRoute('connexion');
            return $this->render('licence/facture.html.twig');
      
    }
    /**
     * @Route("/connexion", name="connexion")
     */
    public function connexion(Request $request)
    {
            return $this->render('licence/connexion.html.twig');
      
    }
    
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, UserPasswordEncoderInterface $encoder, TokenGeneratorInterface $tokenGenerator, \Swift_Mailer $mail)
    { $manager =$this->getDoctrine()->getManager();
        $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
        $form->remove('adresse');
        $form->remove('site');
        $form->remove('email1');
        $form->remove('phone1');
        $form->remove('phone2');
        $form->remove('caisse');
        $form->remove('retenu');
        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            if($form->isValid())
            {
                
                $manager->persist($agence);
                $manager->flush();
                
                $user = new User();
                
                 $hashpass = $encoder->encodePassword($user, 'Moda2020');
                 //$hashpass = $encoder->encodePassword($user, $user->getPassword());
                 //$password = $user->getPassword();
                 $user->setPassword($hashpass);
                 $user->setEmail($agence->getEmail());
                 $user->setPhone($agence->getPhone());
                 $user->setUsername($agence->getResponsable());
                 
                         $user->setRoles(['ROLE_PROPRIETAIRE']);
                   
                 $user->setAgence($agence);
                 // envoie mail
                 $token = $tokenGenerator->generateToken();
                 $user->setResetToken($token);
                 $manager->persist($user);
                 $manager->flush();
                 $url = $this->generateUrl('security_activation', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                 
                 $message = (new \Swift_Message('Activation compte utilisateur'))
                 ->setFrom('support@hajjalbayt.com')
                 ->setTo($agence->getEmail())
                 ->setBody("Cliquez sur le lien suivant pour activer votre compte utilisasateur ".$url, 'text/html');
                 
                 $mail->send($message);
                $manager->flush();
                $this->addflash('notice','Enregistrement effectué avec succes. un mail de confirmatiom est envoyé à l\'adresse email indiqué pour l\'activation du compte');
                $agence = new Agence();
        $form = $this->createForm(AgenceType::class, $agence);
            $form->remove('adresse');
        $form->remove('site');
        $form->remove('email1');
        $form->remove('phone1');
        $form->remove('phone2');
        $form->remove('caisse');
        $form->remove('retenu');
            return $this->render('licence/licence-pack.html.twig', array('form' => $form->createView()));
            }
             
        }
      
        return $this->render('licence/inscription.html.twig', array('form' => $form->createView())); 
    }



    
}
