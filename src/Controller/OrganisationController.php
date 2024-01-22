<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Organ;
use App\Entity\Session;
use App\Entity\Pelerin;
use App\Entity\Agence;
use App\Entity\SessionOrganisateur;
use App\Entity\SessionImam;
use App\Entity\Medecin;
use App\Entity\Imam;
use App\Entity\Image;
use App\Form\OrganType;
use App\Form\MedecinType;
use App\Form\ImamType;
use App\Form\ImageType;

class OrganisationController extends AbstractController
{
    public function organisateurList()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$residents = $em->getrepository(SessionOrganisateur::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$organisateurs = $em->getrepository(Organ::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
				$response = $this->render('Organisation/organisateurlist.html.twig',array('residents' => $residents,'pelerins' => $pelerins,'organisateurs' => $organisateurs, 'session' => $session->getDesignation()));
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
	
	public function designeOrganisateur(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
				$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
			$pelerin = $em->getrepository(Pelerin::class)->find($request->get('plr'));
					
						$pelerin->setType('Organisateur');
						$em->persist($pelerin);
						$em->flush();
						$res['id']= 'ok';
						
						$response = new Response();
						$response->headers->set('content-type','application/json');
						$re = json_encode($res);
						$response->setContent($re);
						return $response;
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	public function designeOrganisateurResident(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
				$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$id = $request->get('organ');
				$organisateur = $em->getrepository(SessionOrganisateur::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'organisateur' => $id, 'session' => $session));
				if(empty($organisateur))
				{
					$organisateur = $em->getrepository(Organ::class)->find($id);
					$sessionorgan = new SessionOrganisateur();
					$sessionorgan->setOrganisateur($organisateur);
					$sessionorgan->setSession($session);
					$em->persist($sessionorgan);
					$em->flush();
					$res['ok'] = 'ok';
				}
				else $res['ok'] = ' ';
				$response = new Response();
				$response->headers->set('content-type','application/json');
				$re = json_encode($res);
				$response->setContent($re);
				return $response;
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	public function annuleOrganisateur(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel'))
				{
					$id = $request->get('pel');
					
					$pelerin = $em->getrepository(Pelerin::class)->find((int)$id);
					if(!empty($pelerin))
					{
						$pelerin->setType(null);
						$em->persist($pelerin);
					}
					
					$em->flush();
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function annuleOrganisateurResident(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('organ'))
				{
					$id = $request->get('organ');
					
					$sessionorgan = $em->getrepository(SessionOrganisateur::class)->find((int)$id);
					if(!empty($sessionorgan))
					{
						$em->remove($sessionorgan);
					}  
					
					$em->flush();
					$res['ok']= 'ok';
					
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function organisateurAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$organisateur = new Organ();
				$form = $this->createForm(OrganType::class, $organisateur);
				$form->remove('image');
				$form->remove('numsaoudiantax');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$organisateur->setSession($session);
						$sessionorgan = new SessionOrganisateur();
						$sessionorgan->setSession($session);
						$sessionorgan->setOrganisateur($organisateur);
						$em->persist($organisateur);
						$em->persist($sessionorgan);
						$em->flush();
						return $this->redirectToRoute('Mecque_OrganisateurList');
					}
				}
				$response = $this->render('Organisation/organisateuradd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	 public function organisateur(Request $request,$organisateur)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			if($organisateur < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				$session = $request->getSession();
				if($session->get('new')) $session->remove('new');
				if($session->get('pelerin')) $session->remove('pelerin');
				$repository = $em->getRepository(Organ::class);
				$image = new Image();
				$form = $this->createForm(ImageType::class, $image);
				if($request->isMethod('POST'))
				{
					$pel = $em->getRepository(Organ::class)->find($organisateur);
					$form->handleRequest($request);
					if(!empty($pel->getImage()))
					{// modificationimage
						if($image->upload($pelsession->getId(),$pel))
						{
							unlink('images/'.$pel->getImage()->getUrl());
							$updateimage = $em->getRepository(Image::class)->find($pel->getImage()->getId());
							$updateimage->setUrl($image->getUrl());
							$em->persist($updateimage);
							$em->flush();
							return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pel->getId()));
						}
					}
					$pel->setImage($image);
					if($pel->getImage()->upload($pelsession->getId(),$pel))
					{
						$em->persist($pel);
						$em->flush();
						return $this->redirectToRoute('Mecque_Organisateur', array('organisateur' => $pel->getId()));
					}
				}
				$pel = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' =>$organisateur, 'session' => $pelsession->getId()));
				$response = $this->render('Organisation/organisateur.html.twig', array( 'organisateur' => $pel, 'form' => $form->createView(), 'session' => $pelsession->getDesignation()));
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
	public function organisateurEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($this->getUser() instanceof Exposant)
				{
					$repository = $em->getRepository(Organ::class);
					$organisateur = $repository->find($id);
					$form = $this->createForm(OrganType::class, $organisateur);
					
					$form->remove('image');
					$form->remove('reduction');
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							//fin getion reduction
							$em = $this->getDoctrine()->getManager();
							$em->persist($organisateur);
							$em->flush();
							return $this->redirectToRoute('Mecque_Organisateur',array('organisateur' => $organisateur->getId()));
						}
					}
					$response = $this->render('Organisation/organisateuredit.html.twig', array('form' => $form->createView(),'organisateur' => $organisateur, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function organisateurDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($this->getUser() instanceof Exposant)
				{
					$repository = $em->getRepository(Organ::class);
					$organisateur = $repository->find($request->get('plr'));
					$em->remove($organisateur);
					$em->flush();
					$res['id']= 'ok';
						
						$response = new Response();
						$response->headers->set('content-type','application/json');
						$re = json_encode($res);
						$response->setContent($re);
						return $response;
				}
				else return $this->redirect($this->generateUrl('security_login'));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function medecinList()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Organisation/medecinlist.html.twig',array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	public function designeMedecin(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
				$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
			$pelerin = $em->getrepository(Pelerin::class)->find($request->get('plr'));
					
						$pelerin->setType('Medecin');
						$em->persist($pelerin);
						$em->flush();
						$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function medecinAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$medecin = new Medecin();
				$form = $this->createForm(MedecinType::class, $medecin);
				$form->remove('image');
				$form->remove('numsaoudiantax');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$medecin->setSession($session);
						$em->persist($medecin);
						$em->flush();
						return $this->redirectToRoute('Mecque_MedecinList');
					}
				}
				$response = $this->render('Organisation/medecinadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	 public function medecin(Request $request,$medecin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			if($medecin < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				$session = $request->getSession();
				if($session->get('new')) $session->remove('new');
				if($session->get('pelerin')) $session->remove('pelerin');
				$repository = $em->getRepository(Medecin::class);
				$image = new Image();
				$form = $this->createForm(ImageType::class, $image);
				if($request->isMethod('POST'))
				{
					$pel = $em->getRepository(Medecin::class)->find($medecin);
					$form->handleRequest($request);
					if(!empty($pel->getImage()))
					{// modificationimage
						if($image->upload($pelsession->getId(),$pel))
						{
							unlink('images/'.$pel->getImage()->getUrl());
							$updateimage = $em->getRepository(Image::class)->find($pel->getImage()->getId());
							$updateimage->setUrl($image->getUrl());
							$em->persist($updateimage);
							$em->flush();
							return $this->redirectToRoute('Mecque_Medecin', array('medecin' => $pel->getId()));
						}
					}
					$pel->setImage($image);
					if($pel->getImage()->upload($pelsession->getId(),$pel))
					{
						$em->persist($pel);
						$em->flush();
						return $this->redirectToRoute('Mecque_Medecin', array('medecin' => $pel->getId()));
					}
				}
				$pel = $repository->findOneBy(array('id' =>$medecin,'agence' => $this->getUser()->getAgence()->getId(),  'session' => $pelsession->getId()));
				$response = $this->render('Organisation/medecin.html.twig', array( 'medecin' => $pel, 'form' => $form->createView(), 'session' => $pelsession->getDesignation()));
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
	
	public function medecinEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($this->getUser() instanceof Exposant)
				{
					$repository = $em->getRepository(Medecin::class);
					$medecin = $repository->find($id);
					$form = $this->createForm(MedecinType::class, $medecin);
					
					$form->remove('image');
					$form->remove('reduction');
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							//fin getion reduction
							$em = $this->getDoctrine()->getManager();
							$em->persist($medecin);
							$em->flush();
							return $this->redirectToRoute('Mecque_Medecin',array('medecin' => $medecin->getId()));
						}
					}
					$response = $this->render('Organisation/medecinedit.html.twig', array('form' => $form->createView(),'medecin' => $medecin, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function imamList()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$residents = $em->getrepository(SessionImam::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$imams = $em->getrepository(Imam::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
				$response = $this->render('Organisation/imamlist.html.twig',array('residents' => $residents,'pelerins' => $pelerins,'imams' => $imams, 'session' => $session->getDesignation()));
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
	
	public function affecterPelerins($imam)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'residimam' => null, 'imam' => null, 'type' => null));
				$imam = $em->getrepository(SessionImam::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $imam));
				$response = $this->render('Organisation/affecterpelerin.html.twig',array('pelerins' => $pelerins,'imam' => $imam, 'session' => $session->getDesignation()));
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
	
	public function affectPelerins($imam)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'residimam' => null, 'imam' => null, 'type' => null));
				$imam = $em->getrepository(Pelerin::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $imam));
				$response = $this->render('Organisation/affectpelerin.html.twig',array('pelerins' => $pelerins,'imam' => $imam, 'session' => $session->getDesignation()));
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
	
	public function annulerPelerins($imam)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'residimam' => $imam));
				$imam = $em->getrepository(SessionImam::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $imam));
				$response = $this->render('Organisation/annulerpelerin.html.twig',array('pelerins' => $pelerins,'imam' => $imam, 'session' => $session->getDesignation()));
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
	
	public function affectationPelerins(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('imam'))
				{
					$pel = explode(";",$request->get('pel'));
					$imam = $em->getrepository(SessionImam::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $request->get('imam')));
					$em->getrepository(Pelerin::class)->affectation($session->getId(), $imam->getId(), $pel);
					$this->addFlash('notice', 'Affectation reussie');
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function affectationPelerinsDeux(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('imam'))
				{
					$pel = explode(";",$request->get('pel'));
					$imam = $em->getrepository(Pelerin::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $request->get('imam')));
					$em->getrepository(Pelerin::class)->affectationDeux($session->getId(), $imam->getId(), $pel);
					$this->addFlash('notice', 'Affectation reussie');
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function annulationPelerins(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('imam'))
				{
					$pel = explode(";",$request->get('pel'));
					$imam = $em->getrepository(SessionImam::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $request->get('imam')));
					$em->getrepository(Pelerin::class)->annulation($session->getId(), $pel);
					$this->addFlash('notice', 'Annulation reussie');
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function designeImam(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
				$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
			$pelerin = $em->getrepository(Pelerin::class)->find($request->get('imam'));
					
						$pelerin->setType('Imam');
						$em->persist($pelerin);
						$em->flush();
						$res['id']= 'ok';
						
						$response = new Response();
						$response->headers->set('content-type','application/json');
						$re = json_encode($res);
						$response->setContent($re);
						return $response;
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function imamAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$imam = new Imam();
				$form = $this->createForm(ImamType::class, $imam);
				$form->remove('image');
				$form->remove('numsaoudiantax');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$sessionimam = new SessionImam();
						$imam->setSession($session);
						$imam->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$sessionimam->setSession($session);
						$sessionimam->setImam($imam);
						$sessionimam->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($imam);
						$em->persist($sessionimam);
						$em->flush();
						return $this->redirectToRoute('Mecque_ImamList');
					}
				}
			    $response = $this->render('Organisation/imamadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function imam(Request $request,$imam)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			if($imam < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				$session = $request->getSession();
				if($session->get('new')) $session->remove('new');
				if($session->get('pelerin')) $session->remove('pelerin');
				$repository = $em->getRepository(Imam::class);
				$image = new Image();
				$form = $this->createForm(ImageType::class, $image);
				if($request->isMethod('POST'))
				{
					$pel = $em->getRepository(Imam::class)->find($imam);
					$form->handleRequest($request);
					if(!empty($pel->getImage()))
					{// modificationimage
						if($image->upload($pelsession->getId(),$pel))
						{
							unlink('images/'.$pel->getImage()->getUrl());
							$updateimage = $em->getRepository(Image::class)->find($pel->getImage()->getId());
							$updateimage->setUrl($image->getUrl());
							$em->persist($updateimage);
							$em->flush();
							return $this->redirectToRoute('Mecque_Imam', array('imam' => $pel->getId()));
						}
					}
					$pel->setImage($image);
					if($pel->getImage()->upload($pelsession->getId(),$pel))
					{
						$em->persist($pel);
						$em->flush();
						return $this->redirectToRoute('Mecque_Imam', array('imam' => $pel->getId()));
					}
				}
				$pel = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' =>$imam, 'session' => $pelsession->getId()));
				return $this->render('Organisation/imam.html.twig', array( 'imam' => $pel, 'form' => $form->createView(), 'session' => $pelsession->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function imamEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($this->getUser() instanceof Exposant)
				{
					$repository = $em->getRepository(Imam::class);
					$imam = $repository->find($id);
					$form = $this->createForm(ImamType::class, $imam);
					
					$form->remove('image');
					$form->remove('reduction');
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							//fin getion reduction
							$em = $this->getDoctrine()->getManager();
							$em->persist($imam);
							$em->flush();
							return $this->redirectToRoute('Mecque_Imam',array('imam' => $imam->getId()));
						}
					}
					$response = $this->render('Organisation/imamedit.html.twig', array('form' => $form->createView(),'imam' => $imam, 'session' => $session->getDesignation()));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function imamDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				
					$repository = $em->getRepository(Imam::class);
					$imam = $repository->find($request->get('plr'));
					
							$em->remove($imam);
							$em->flush();
							$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	public function designeImamResident(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
				$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$id = $request->get('imam');
				$imam = $em->getrepository(SessionImam::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'imam' => $id, 'session' => $session));
				if(empty($imam))
				{
					$imam = $em->getrepository(Imam::class)->find($request->get('imam'));
					$sessionimam = new SessionImam();
					$sessionimam->setImam($imam);
					$sessionimam->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
					$sessionimam->setSession($session);
					$em->persist($sessionimam);
					$em->flush();
					$res['ok'] = 'ok';
				}
				else $res['ok'] = ' ';
				$response = new Response();
				$response->headers->set('content-type','application/json');
				$re = json_encode($res);
				$response->setContent($re);
				return $response;
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function annuleImamResident(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('imam'))
				{
					$id = $request->get('imam');
					
					$sessionimam = $em->getrepository(SessionImam::class)->find((int)$request->get('imam'));
					if(!empty($sessionimam))
					{
						$em->remove($sessionimam);
					}  
					
					$em->flush();
					$res['ok']= 'ok';
					
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function annulPelerins($imam)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'imam' => $imam));
				$imam = $em->getrepository(Pelerin::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $imam));
				return $this->render('Organisation/annulpelerin.html.twig',array('pelerins' => $pelerins,'imam' => $imam, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function annulationPelerinsDeux(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('imam'))
				{
					$pel = explode(";",$request->get('pel'));
					$imam = $em->getrepository(Pelerin::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'id' => $request->get('imam')));
					$em->getrepository(Pelerin::class)->annulationDeux($session->getId(), $pel);
					$this->addFlash('notice', 'Annulation reussie');
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	public function annulerAffectation(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('imam'))
				{
					$pelerin = $em->getrepository(Pelerin::class)->find((int)$request->get('imam'));
					if(!empty($pelerin))
					{
						$pelerin->setImam(null);
						$em->persist($pelerin);
					}
					
					$em->flush();
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function annulAffectation(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('imam'))
				{
					$pelerin = $em->getrepository(Pelerin::class)->find((int)$request->get('imam'));
					if(!empty($pelerin))
					{
						$pelerin->setResidimam(null);
						$em->persist($pelerin);
					}
					
					$em->flush();
					$res['ok']= 'ok';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
}
