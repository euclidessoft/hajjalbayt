<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Compagnie;
use App\Entity\Agence;
use App\Entity\Session;
use App\Entity\Pelerin;
use App\Entity\Bus;
use App\Entity\Vol;
use App\Form\CompagnieType;
use App\Form\VolType;
use App\Form\BusType;


/**
 * @Route("/{_locale}")
 */
class TransportController extends AbstractController
{
    public function terrestre()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$busses = $em->getrepository(Bus::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('bus' => null,'session' => $session->getId()));
				$response = $this->render('Transport/terrestre.html.twig',array('pelerins' => $pelerins, 'busses' => $busses, 'session' => $session->getDesignation()));
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
    public function companies(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$companies = $em->getrepository(Compagnie::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
				$response = $this->render('Transport/companies.html.twig',array('compagnies' => $companies, 'session' => $session->getDesignation()));
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
	public function aerien(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$vols = $em->getrepository(Vol::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'aller' => true, 'session' => $session->getId()));
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('vol' => null,'session' => $session->getId()));
				$response = $this->render('Transport/aerien.html.twig',array('pelerins' => $pelerins,'vols' => $vols, 'session' => $session->getDesignation()));
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
	public function aerienRetour(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$retour = $em->getrepository(Vol::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'retour' => true, 'session' => $session->getId()));
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('retour' => null,'session' => $session->getId()));
				$response = $this->render('Transport/aerienretour.html.twig',array('pelerins' => $pelerins,'retour' => $retour, 'session' => $session->getDesignation()));
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
	public function compagnieList()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$compagnies = $em->getrepository(Compagnie::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Transport/compagnielist.html.twig',array('agence' => $this->getUser()->getAgence()->getId(), 'compagnies' => $compagnies, 'session' => $session->getDesignation()));
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
	
	public function compagnieAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$compagnie = new Compagnie();
				$form = $this->createForm(CompagnieType::class, $compagnie);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$compagnie->setSession($session);
						$compagnie->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($compagnie);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportCompanies');
					}
				}
				$response = $this->render('Transport/compagnieadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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

    public function companyEdit(Request $request, $id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$compagnie = $em->getrepository(Compagnie::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id));
				$form = $this->createForm(CompagnieType::class, $compagnie);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em->persist($compagnie);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportCompanies');
					}
				}
				$response = $this->render('Transport/companyedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function volAller(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$vol = new Vol();
				$form = $this->createForm(VolType::class, $vol, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('lieudepart');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$vol->setSession($session);
						$vol->setAller(true);
						$vol->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($vol);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportAerien');
					}
				}
				$response =$this->render('Transport/volaller.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function volRetour(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$vol = new Vol();
				$form = $this->createForm(VolType::class, $vol, [ 'id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('destination');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$vol->setSession($session);
						$vol->setRetour(true);
						$vol->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($vol);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportAerienRetour');
					}
				}
				$response = $this->render('Transport/volretour.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function volAllerEdit(Request $request, $id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$vol = $em->getrepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));
				$form = $this->createForm(VolType::class, $vol, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('lieudepart');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em->persist($vol);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportAerien');
					}
				}
				$response = $this->render('Transport/voledit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function volRetourEdit(Request $request, $id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$vol = $em->getrepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));
				$form = $this->createForm(VolType::class, $vol, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('destination');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em->persist($vol);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportAerienRetour');
					}
				}
				$response = $this->render('Transport/volretouredit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function volDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$vol = $em->getrepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $request->get('vol'), 'session' => $session->getId()));
				$em->remove($vol);
				$em->flush();
				$typevol = $vol->getAller();
				$typevol = 1 ? $res['id'] = 1 : $res['id'] = 0;
						
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
	
	public function volEmbarquer(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Vol::class);
				$vol = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'aller' => true,'id' => $id, 'session' => $session->getId()));//
				if(!empty($vol))$pelerins = $em->getRepository(Pelerin::class)->findBy(array('vol' => null, 'session' => $session->getId()));
				else throw $this->createNotFoundException();
				return $this->render('Transport/volembarquer.html.twig', array('vol' => $vol, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function volDebarquer(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Vol::class);
				$vol = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'aller' => true,'id' => $id, 'session' => $session->getId()));//
				if(!empty($vol))$pelerins = $em->getRepository(Pelerin::class)->findBy(array('vol' => $id, 'session' => $session->getId()));
				else throw $this->createNotFoundException();
				return $this->render('Transport/voldebarquer.html.twig', array('vol' => $vol, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function retourEmbarquer(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Vol::class);
				$vol = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'retour' => true,'id' => $id, 'session' => $session->getId()));//
				if(!empty($vol))$pelerins = $em->getRepository(Pelerin::class)->findBy(array('retour' => null, 'session' => $session->getId()));
				else throw $this->createNotFoundException();
				return $this->render('Transport/retourembarquer.html.twig', array('vol' => $vol, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function retourDebarquer(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Vol::class);
				$vol = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'retour' => true,'id' => $id, 'session' => $session->getId()));//
				if(!empty($vol))$pelerins = $em->getRepository(Pelerin::class)->findBy(array('retour' => $id, 'session' => $session->getId()));
				else throw $this->createNotFoundException();
				return $this->render('Transport/retourdebarquer.html.twig', array('vol' => $vol, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function embarquer(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('vol'))
				{
					$pel = explode(";",$request->get('pel'));
					$vol = $em->getrepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'aller' => true,'session' => $session->getId(),'id' =>$request->get('vol')));
					$em->getrepository(Pelerin::class)->volEmbarquer($session->getId(), $vol->getId(), $pel);
					$this->addFlash('notice', 'Embarquement effectué');
					$res['id']= 'ok';
					
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
	
	public function debarquer(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('vol'))
				{
					$pel = explode(";",$request->get('pel'));
					$vol = $em->getrepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'aller' => true,'session' => $session->getId(),'id' =>$request->get('vol')));
					$em->getrepository(Pelerin::class)->volDebarquer($session->getId(), $pel);
					$this->addFlash('notice', 'Débarquement effectué');
					$res['id']= 'ok';
					
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
	
	public function embarquerRetour(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('vol'))
				{
					$pel = explode(";",$request->get('pel'));
					$vol = $em->getRepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'retour' => true,'session' => $session->getId(),'id' =>$request->get('vol')));
					$em->getRepository(Pelerin::class)->retourEmbarquer($session->getId(), $vol->getId(), $pel);
					$this->addFlash('notice', 'Embarquement effectué');
					$res['id']= 'ok';
					
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
	
	public function debarquerRetour(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('vol'))
				{
					$pel = explode(";",$request->get('pel'));
					$vol = $em->getrepository(Vol::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'retour' => true,'session' => $session->getId(),'id' =>$request->get('vol')));
					$em->getrepository(Pelerin::class)->retourDebarquer($session->getId(), $pel);
					$this->addFlash('notice', 'Débarquement effectué');
					$res['id']= 'ok';
					
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
	
	public function busAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$bus = new Bus();
				$form = $this->createForm(BusType::class, $bus, ['id' => $this->getUser()->getAgence()->getId()]);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$bus->setSession($session);
						$bus->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($bus);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportTerrestre');
					}
				}
				$response = $this->render('Transport/busadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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

    /**
     * @Route("/BusEdit/{id}", name="Mecque_bus_edit")
     */
    public function busEdit(Request $request, $id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$bus =  $em->getrepository(Bus::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id));
				$form = $this->createForm(BusType::class, $bus, ['id' => $this->getUser()->getAgence()->getId()]);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$bus->setSession($session);
						$bus->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($bus);
						$em->flush();
						return $this->redirectToRoute('Mecque_TransportTerrestre');
					}
				}
				$response = $this->render('Transport/busedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function busEmbarquement(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Bus::class);
				$bus = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));//
				if(!empty($bus))
				{
					if($bus->getGenre() != 'Mixte')$pelerins = $em->getRepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'bus' => null,'sexe' => $bus->getGenre(), 'session' => $session->getId()));
					else $pelerins = $em->getRepository(Pelerin::class)->findBy(array('bus' => null, 'session' => $session->getId()));
				}
				$response = $this->render('Transport/busembarquer.html.twig', array('bus' => $bus, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
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
	
	public function embarquerBus(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('bus'))
				{
					$pel = explode(";",$request->get('pel'));
					$bus = $em->getrepository(Bus::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'id' =>$request->get('bus')));
					$em->getrepository(Pelerin::class)->busEmbarquer($session->getId(), $bus->getId(), $pel);
					$this->addFlash('notice', 'Embarquement effectué');
					$res['id']= 'ok';
					
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
	
	public function changeBus(Request $request,$pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Pelerin::class);
				$pelerin = $repository->findOneBy(array('id' => $pelerin, 'session' => $session->getId()));//
				$repository = $em->getRepository(Bus::class);
				$busses = $repository->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));//
				
				return $this->render('Transport/changebus.html.twig', array('busses' => $busses, 'pelerin' =>$pelerin, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function ajaxChangeBus(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('bus'))
				{
					$bus = $em->getrepository(Bus::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $request->get('bus'), 'session' => $session->getId()));
					$pelerin = $em->getrepository(Pelerin::class)->findOneBy(array('id' => $request->get('pel'), 'session' => $session->getId()));
					if(!empty($pelerin))
					{
						$pelerin->setBus($bus);
						$em->persist($pelerin);
					}
					
					$em->flush();
					$res['ok']= $this->generateUrl('Mecque_Pelerin',array('pelerin' => $pelerin->getId()));
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
	
	public function busDescendre(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Bus::class);
				$bus = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));//
				if(!empty($bus))
				{
					$pelerins = $em->getRepository(Pelerin::class)->findBy(array('bus' => $bus->getId(), 'session' => $session->getId()));
				}
				$response = $this->render('Transport/busdescendre.html.twig', array('bus' => $bus, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
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
	
	public function descendreBus(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('bus'))
				{
					$pel = explode(";",$request->get('pel'));
					$bus = $em->getrepository(Bus::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'id' =>$request->get('bus')));
					$em->getrepository(Pelerin::class)->busDescendre($session->getId(), $pel);
					$this->addFlash('notice', 'Débarquement effectué');
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
	
	public function userDescendre(Request $request)
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
						$pelerin->setBus(null);
						$em->persist($pelerin);
					}
					
					$em->flush();
                    $this->addFlash('notice', 'Débarquement effectué');
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
	
	public function volUserDescendre(Request $request)
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
						$pelerin->setVol(null);
						$em->persist($pelerin);
					}
					
					$em->flush();
                    $this->addFlash('notice', 'Débarquement effectué');
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
	
	public function volRetourUserDescendre(Request $request)
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
						$pelerin->setRetour(null);
						$em->persist($pelerin);
					}
					
					$em->flush();
                    $this->addFlash('notice', 'Débarquement effectué');
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

	public function defineBus(Request $request,$pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Pelerin::class);
				$pelerin = $repository->findOneBy(array('id' => $pelerin, 'session' => $session->getId()));//
				$repository = $em->getRepository(Bus::class);
				$busses = $repository->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));//
				
				return $this->render('Transport/definebus.html.twig', array('busses' => $busses, 'pelerin' =>$pelerin, 'session' => $session->getDesignation()));
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}

	 /**
     * @Route("/BusDelete", name="Mecque_bus_delete")
     */
    public function delete(Request $request)
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
        {
            $em = $this->getDoctrine()->getManager();
            //$users = $em->getrepository(User::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
            $bus =  $em->getrepository(Bus::class)->find($request->get('bus'));
            $em->remove($bus);
            $em->flush();
            $this->addFlash('notice', 'Bus supprimé');
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
