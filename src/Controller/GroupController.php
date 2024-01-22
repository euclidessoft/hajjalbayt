<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Pelerin;
use App\Entity\Agence;
use App\Entity\Credit;
use App\Entity\VersementAgence;
use App\Entity\Groupement;
use App\Entity\Session;
use App\Form\AgenceType;
use App\Form\SessionType;
use App\Form\GroupementType;
use App\Form\VersementAgenceType;

class GroupController extends AbstractController
{
    public function index(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$group = new Groupement();
				$form = $this->createForm(GroupementType::class, $group, ['id' => $this->getUser()->getAgence()->getId()]);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$group->setSession($session);
						$group->setChef($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($group);
						$em->flush();
						return $this->redirectToRoute('Mecque_Group');
					}
				}
				$agency = $em->getrepository(Agence::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
				$group = $em->getrepository(Groupement::class)->findBy(array('chef' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
			    $response = $this->render('Group/index.html.twig',array('session' => $session->getDesignation(),'group' => $group, 'agencies' => $agency, 'form' => $form->createView(), 'quota' => $session->getCotat()));
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
	
	public function agencyAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				
					$agency = new Agence();
					$form = $this->createForm(AgenceType::class, $agency);
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
							$em = $this->getDoctrine()->getManager();
							$agency->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
							$agency->setCaisse(false);
							$em->persist($agency);
							$em->flush();
							return $this->redirectToRoute('Mecque_Group');
						}
					}
					$response = $this->render('Group/agencyadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
    
	public function agencyEdit(Request $request, $agency)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
					$repository = $em->getRepository(Agence::class);
					$agence = $repository->find($agency);
					$form = $this->createForm(AgenceType::class, $agence);
                    $form->remove('site');
                    $form->remove('email1');
                    $form->remove('phone1');
                    $form->remove('phone2');
                    $form->remove('caisse');
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							//fin getion reduction
							$em->persist($agence);
							$em->flush();
							return $this->redirectToRoute('Mecque_Group');
						}
					}
					$response = $this->render('Group/agencyedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
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
	
    public function sessionGroupCreate(Request $request)
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true, 'configurer' => false));
			if(!empty($session))
			{
				$group = new Groupement();
				$form = $this->createForm(GroupementType::class, $group, [ 'id' => $this->getUser()->getAgence()->getId()]);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$group->setSession($session);
						$group->setChef($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($group);
						$em->flush();
						return $this->redirectToRoute('Mecque_SessionGroupCreate');
					}
				}
				$agency = $em->getrepository(Agence::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId() ));
				$group = $em->getrepository(Groupement::class)->findBy(array('chef' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Group/sessiongroupcreate.html.twig',array('session' => $session->getDesignation(),'group' => $group, 'agencies' => $agency, 'form' => $form->createView(), 'quota' => $session->getCotat()));
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
			else return $this->redirectToRoute('Mecque_homepage');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function sessionAgencyAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$agency = new Agence();
				$form = $this->createForm(AgenceType::class, $agency);
                $form->remove('site');
                $form->remove('email1');
                $form->remove('phone1');
                $form->remove('phone2');
                $form->remove('caisse');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$agency->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($agency);
						$em->flush();
						return $this->redirectToRoute('Mecque_SessionGroupCreate');
					}
				}
				$response = $this->render('Group/sessionagencyadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function suivant(Request $request)
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			//if($group != 'no' || $group != 'yes') throw $this->createNotFoundHttpException();// verification parametres reponse si yes or no
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($session->getConfigurer() == false)
				{
					
					$session->setConfigurer(true);
					$session->setGroupement(true);
					$session->setChef(true);
					$em->persist($session);
					$em->flush();
					return $this->redirectToRoute('Mecque_PackAdd');
						
				}
				else return $this->redirectToRoute('Mecque_PackAdd');
			}
			else return $this->redirectToRoute('Mecque_PreClose');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
    public function quota(Request $request)
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			//if($group != 'no' || $group != 'yes') throw $this->createNotFoundHttpException();// verification parametres reponse si yes or no
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($session->getConfigurer() == true && $session->getCotat() == 0)
				{
					$form = $this->createForm(SessionType::class, $session);
					$form->remove('designation');
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							$em->persist($session);
							$em->flush();
							return $this->redirectToRoute('Mecque_Group');
						}
					}
					$response = $this->render('Group/quota.html.twig', array('form' => $form->createView()));
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
				else return $this->redirectToRoute('Mecque_SessionConfig');
			}
			else return $this->redirectToRoute('Mecque_PreClose');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
    public function agency(Request $request,$agency)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') )
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
			
				$repository = $em->getRepository(Agence::class);
				$agence = $repository->find($agency);
				if($session->getChef() == true)
				{
					$versements = $em->getRepository(VersementAgence::class)->findBy(array('session' => $session->getId(), 'agence' => $agence->getId()));
					
					$chef = 'chef';
					$groupement = $em->getRepository(Groupement::class)->findOneBy(array('agence' => $agence->getId(), 'session' => $session->getId()));
					$pelerins = $em->getRepository(Pelerin::class)->findBy(array('agence' => $agence->getId(), 'session' => $session->getId()));
					$montant =0;
					foreach($pelerins as $pel)
					{
						$montant = $montant + $pel->getPack()->getExploitation();	
					}
					if(!empty($groupement))// agence dans le groupement
						return $this->render('Group/agency.html.twig', array('agence' => $agence, 'session' => $session->getDesignation(), 'quota' => $groupement->getQuota(), 'pelerins' => count($pelerins), 'montant' => $montant, 'chef' => $chef, 'versements' => $versements));
					else 
					{// agencepas dans le groupement
						$chef = null;
						$response = $this->render('Group/agency.html.twig', array('agence' => $agence, 'session' => $session->getDesignation(), 'chef' => $chef));
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
				else
				{
					$chef = null;
					$response = $this->render('Group/agency.html.twig', array('agence' => $agence, 'session' => $session->getDesignation(), 'chef' => $chef));
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function Versement(Request $request,$agence)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				
				if($agence < 0)
				{
					throw $this->createNotFoundHttpException();
				} 
				$versement = new VersementAgence();
				$form = $this->createForm(VersementAgenceType::class, $versement);
				$agency = $em->getRepository(Agence::class)->Find($agence);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$versement->setAgence($agency);
						$versement->setSession($pelsession);
						$versement->setEncaisser($this->getUser());
						$credit = new Credit();
						$credit->setAgence($agency);
						$credit->setVersementagence($versement);
						$credit->setSession($pelsession);
						$em->persist($credit);
						$em->persist($versement);
						$em->flush();
						return $this->redirectToRoute('Mecque_Group_Agency',array('agency' => $agency->getid()));
					
					}
				}
				$response = $this->render('Group/versement.html.twig', array('agence' => $agency, 'form' => $form->createView(), 'session' => $pelsession->getDesignation()));
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
	
    public function agencePelerin($agence)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId(), 'agence' => $agence));
				$agency = $em->getRepository(Agence::class)->find($agence);
				$response = $this->render('Group/agencepelerin.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'agence' => $agency->getNom()));
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

    public function info(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				
				$agence = $em->getrepository(Agence::class)->find($this->getUser()->getAgence()->getId());

				$response = $this->render('Group/infoagence.html.twig', array('agence' => $agence, 'session' => $session->getDesignation()));
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

    public function infoagenceEdit(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
					$repository = $em->getRepository(Agence::class);
					$agence = $repository->find($this->getUser()->getAgence()->getId());
					$form = $this->createForm(AgenceType::class, $agence);
					$form->remove('nom');
                    if($this->getUser()->getAgence()->getLicence() == false)
                        $form->remove('caisse'); 
                
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							//fin getion reduction
							$em->persist($agence);
							$em->flush();
							return $this->redirectToRoute('Mecque_AgenceInfo');
						}
					}
					$response = $this->render('Group/infoagenceedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
