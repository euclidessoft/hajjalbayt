<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Pelerin;
use App\Entity\Session;
use App\Entity\Medical;
use App\Form\PelerinType;
use App\Form\MedicalType;

class MedicalController extends AbstractController
{
    public function index(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN') or $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			//return $this->redirect($this->generateUrl('Mecque_Medical_list'));
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(), 'current' => true));
			if(!empty($session))
			{
				//$request->getSession()->getFlashBag()->add('notice', 'les 2000 riyals supplementaires concernant les pelerins ayant ete a la mecque entre 2014-2018 sont entrain d\'etre implementes et ca concerne tout le systeme. Signalez la nous si vous constatez une erreur. Merci !!!!! ');

				$medicals = $em->getRepository(Medical::class)->findBy(array('session' => $session->getId()));
				$medicaux = 0;
				$chirurgi = 0;
				$psy = 0;
				$charriot = 0;
				$enceinte = 0;
				
				
				foreach($medicals as $medical)
				{
					if($medical->getHta() == true or $medical->getDiabete() == true or $medical->getAsthme() == true or $medical->getDrepano() == true or $medical->getTuberculose() == true or $medical->getArthrose() == true)
					{
						$medicaux = $medicaux+1;
					}
					if($medical->getChabdo() != null or $medical->getChpulmo() != null or $medical->getChortho()!= null or $medical->getCesarienne() != null or $medical->getAutres() != null)
					{
						$chirurgi = $chirurgi+1;
					}
					if($medical->getPsychiatrique() != null)
					{
						$psy = $psy+1;
					}
					if($medical->getCharriot())
					{
						$charriot = $charriot + 1;
					}
					if($medical->getEnceinte())
					{
						$enceinte = $enceinte + 1;
					}
					
				}
				$response = $this->render('Medecine/index.html.twig',array('medicaux' => $medicaux, 'chirurgi' => $chirurgi, 'psy' => $psy, 'charriot' => $charriot, 'enceinte' => $enceinte));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function list(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				$response = $this->render('Medecine/pelerinlist.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

	public function state(Request $request,Pelerin $pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($pelerin->getMedical() != null)
					$medical= $pelerin->getMedical();
				else $medical = new Medical();
				$form = $this->createForm(MedicalType::class, $medical);
				if($pelerin->getSexe() == "Masculin")
				{
					$form->remove('enceinte');
					$form->remove('obstep');
					$form->remove('obsteg');
					$form->remove('cesarienne');
				}
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$medical->setSession($session);
						$pelerin->setMedical($medical);
						$em->persist($medical);
						$em->persist($pelerin);
						$em->flush();
						return $this->redirectToRoute('Mecque_Medical_Pelerin', array('pelerin' => $pelerin->getId()));
					}
				}
				$response = $this->render('Medecine/state.html.twig', array('form' => $form->createView(), 'pelerin' => $pelerin));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function men(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('sexe' => 'Masculin', 'session' => $session->getId()));
				$response = $this->render('Medecine/men.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function women(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('sexe' => 'Feminin', 'session' => $session->getId()));
				$response = $this->render('Medecine/women.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
    public function women45(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$annee = (int)date('Y') -45;
				$pelerins = $em->getRepository(Pelerin::class)->Less($session->getId(),$annee);
				$response = $this->render('Medecine/women45.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function old(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$annee = (int)date('Y') -60;
				$pelerins = $em->getRepository(Pelerin::class)->old($session->getId(),$annee);
				$response = $this->render('Medecine/old.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

     public function pelerin(Request $request,$pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
			if($pelerin < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pel = $em->getrepository(Pelerin::class)->findOneBy(array('id' =>$pelerin, 'session' => $session->getId()));
				$response = $this->render('Medecine/pelerin.html.twig', array( 'pelerin' => $pel, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function antecedent(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN') or $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->Antecedent($session->getId());
				$response = $this->render('Medecine/antecedent.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function chirurgie(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN') or $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->Antecedent($session->getId());
				$response = $this->render('Medecine/chirurgie.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function psy(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN') or $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->Antecedent($session->getId());
				$response = $this->render('Medecine/psy.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function enceinte(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN') or $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->Antecedent($session->getId());
				$response = $this->render('Medecine/enceinte.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }

    public function charriot(Request $request)
    {
		
		if($this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN') or $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->Antecedent($session->getId());
				$response = $this->render('Medecine/charriot.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	


}
