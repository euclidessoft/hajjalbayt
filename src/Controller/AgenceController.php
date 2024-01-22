<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Agence;
use App\Entity\User;
use App\Entity\Commentaire;
use App\Form\AgenceType;
use App\Form\CommentaireType;
use App\Form\Type\RegistrationFormType;
/**
 * @Route("/{_locale}")
 */
class AgenceController extends AbstractController
{
   public function add(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CONCEPTEUR'))
		{
			$agence = new Agence();
			$form = $this->createForm(AgenceType::class, $agence);
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if($form->isValid())
				{
					$em = $this->getDoctrine()->getManager();
					$em->persist($agence);
					$em->flush();
					return $this->redirectToRoute('Mecque_CourtierList');
				}
			}
			return $this->render('Agence/add.html.twig', array('form' => $form->createView()));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
    public function agence(Request $request)
    {
		return $this->redirect($this->generateUrl('security_login'));
    }
	/**
	 * @Route("/AgenceList", name="agence_list")
	 */
	 public function list()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CONCEPTEUR'))
		{
			
			$em = $this->getDoctrine()->getManager();
			$agence = $em->getRepository(Agence::class)->findby(['agence' => null]);
			return $this->render('Agence/list.html.twig', array('agences' => $agence));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	public function user(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CONCEPTEUR'))
		{
			
				$user = new User();
				$form = $this->createForm(RegistrationFormType::class, $user);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$userManager = $this->container->get('fos_user.user_manager');
						$user = new User();
						$user->setRoles(array('ROLE_CHEF'));
						$userManager->updateUser($user);
						return $this->redirectToRoute('Agence_List');
					}
				}

				
				return $this->render('Agence/user.html.twig',array('form' => $form->createView()));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function Userenable($id)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository('MecqueBundle:Session')->findOneBy(array('current' => true));
			if(!empty($session))
			{
				

				$userManager = $this->container->get('fos_user.user_manager');
				$users = $userManager->findUsers();
				$user = $userManager->findUserBy(array('id' => $id));
				$user->setEnabled(true);
				$userManager->updateUser($user);
				return $this->render('FashionUserBundle:Profile:list.html.twig',array('users' => $users, 'session' => $session->getDesignation() ));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

     public function activation()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CONCEPTEUR'))
		{
			
				$em = $this->getDoctrine()->getManager();
				$users = $em->getrepository('FashionUserBundle:User')->findBy(array('agence' => 1, 'enabled' => false));
				return $this->render('Agence/activationlist.html.twig',array('users' => $users ));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
    
    /**
* @Route("/Feedback", name="Mecque_Feedback")
*/
    public function feedback(Request $request)
    {
        if($this->getUser() != null)
        {
            $comment = new Commentaire();
			$form = $this->createForm(CommentaireType::class, $comment);
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if($form->isValid())
				{
					$em = $this->getDoctrine()->getManager();
                    $comment->setUser($this->getUser());
					$em->persist($comment);
					$em->flush();
                    $this->addFlash('notice', 'Enregistré avec succés, nous l\'étudierons avec soins et vous reviendrons, si  necessaire, le plus tôt possible');
					return $this->redirectToRoute('Mecque_Feedback');
				}
			}
            $response = $this->render('Default/feedback.html.twig',[ 'form' => $form->createView()]);
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
        else return $this->redirect($this->generateUrl('security_login'));
    }
    
}
