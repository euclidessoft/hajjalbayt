<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\Hotel;
use App\Entity\Session;
use App\Entity\Agence;
use App\Entity\Pelerin;
use App\Entity\Chambre;
use App\Entity\ChambreMecque;
use App\Form\HotelType;
use App\Form\ChambreType;
use App\Form\ChambreMecqueType;

/**
 * @Route("/fr")
 */

class RoomingController extends AbstractController
{
     public function hebergement(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getrepository(Chambre::class);
				$chambres = $repository->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$hotels = $em->getrepository(Hotel::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'lieu' => 'Medine'));
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('chambre' => null,'session' => $session->getId()));
				if( $request->isXmlHttpRequest() )
				{// traitement de la requete ajax dans la fonction choice pour le choix 
					$id = $request->get('elv');// recuperation de la parametres elv envoye par ajax
					$numero = $request->get('numero');// recuperation de la parametres elv
					$chambre = $repository->find($id);
					$nbchambre = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'hotel' => $chambre->getHotel()->getId(), 'numeroreel' => $numero));//verification existant numero chambre
					if(empty($nbchambre))
					{
						$chambre->setNumeroreel($numero);
						$em->persist($chambre);
						$em->flush();
						$res['id'] = 'ok';
					}
					else $res['id'] = 'no';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
				$response = $this->render('Rooming/hebergement.html.twig',array('hotels' => $hotels,'pelerins' => $pelerins,'chambres' => $chambres, 'session' => $session->getDesignation()));
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
	
	public function hebergementMecque(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getrepository(ChambreMecque::class);
				$chambres = $repository->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$hotels = $em->getrepository(Hotel::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'lieu' => 'Mecque'));
				$pelerins = $em->getrepository(Pelerin::class)->findBy(array('chambremecque' => null,'session' => $session->getId()));
				if( $request->isXmlHttpRequest() )
				{// traitement de la requete ajax dans la fonction choice pour le choix 
					$id = $request->get('elv');// recuperation de la parametres elv envoye par ajax
					$numero = $request->get('numero');// recuperation de la parametres elv
					$chambre = $repository->find($id);
					$nbchambre = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'hotel' => $chambre->getHotel()->getId(), 'numeroreel' => $numero));//verification existant numero chambre
					if(empty($nbchambre))
					{
						$chambre->setNumeroreel($numero);
						$chambre->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($chambre);
						$em->flush();
						$res['id'] = 'ok';
					}
					else $res['id'] = 'no';
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
				$response = $this->render('Rooming/hebergementmecque.html.twig',array('hotels' => $hotels,'pelerins' => $pelerins,'chambres' => $chambres, 'session' => $session->getDesignation()));
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
	
	public function hotelMedine(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$hotel = new Hotel();
				$form = $this->createForm(HotelType::class, $hotel);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$hotel->setLieu('Medine');
						$hotel->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($hotel);
						$em->flush();
						$this->addFlash('notice', 'Hôtel créé');
						return $this->redirectToRoute('Mecque_Hebergement');
					}
				}
				$response = $this->render('Rooming/hotelmedine.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function hotelMecque(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$hotel = new Hotel();
				$form = $this->createForm(HotelType::class, $hotel);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$hotel->setLieu('Mecque');
						$hotel->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($hotel);
						$em->flush();
						$this->addFlash('notice', 'Hôtel créé');
						return $this->redirectToRoute('Mecque_HebergementMecque');
					}
				}
				$response = $this->render('Rooming/hotelmecque.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function chambreMedine(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = new Chambre();
				$form = $this->createForm(ChambreType::class, $chambre, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('numeroreel');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$chambre->setSession($session);
						$chambres = $em->getRepository(Chambre::class)->medine($this->getUser()->getAgence()->getId(), $session->getId());
						$numero = count($chambres);//recuperation du nombre de chambre
						$tamp = 1;
						while( $numero != 0 && $tamp != 0){//verification si le numero de chambre nombre+1 existe 
							$numero = $numero +1;
							$tamp = count($em->getRepository(Chambre::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'numero' =>$numero, 'session' => $session->getId())));
						}
						if($numero == 0)$chambre->setNumero(1);
						else $chambre->setNumero($numero);
						$nombre = $chambre->getNombre();//recuperation nombre de chambre a creer
						$chambre->setNombre(null);
						$chambre->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($chambre);
						$em->flush();
						if($nombre > 1)
							return $this->redirectToRoute('Mecque_CreationChambre', array('agence' => $this->getUser()->getAgence()->getId(), 'id' =>$chambre->getId(), 'nombre' => $nombre-1));
						else
						{
							$this->addFlash('notice', 'Chambre créée');
							return $this->redirectToRoute('Mecque_Hebergement');
						}	
					}
				}
				$response = $this->render('Rooming/chambremedine.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function chambreEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = $em->getRepository(Chambre::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));
				$form = $this->createForm(ChambreType::class, $chambre, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('nombre');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						if(count($chambre->getPelerins()) > $chambre->getPlace())
						{
							$this->addFlash('notice', 'Plus d\'occupants que de lits. Liberez des lits');
							return $this->render('Rooming/chambreedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
						}
						if(!empty($chambre->getNumeroreel()))
						{
							$nbchambre = $em->getRepository(Chambre::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'hotel' => $chambre->getHotel()->getId(), 'numeroreel' => $chambre->getNumeroreel()));//verification existant numero chambre
							if(!empty($nbchambre))
							{
								if($nbchambre->getId() != $id)
								{
									$this->addFlash('notice', 'Une autre chambre du même hôtel possède déjà ce numéro');
									return $this->render('Rooming/chambreedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
								}
							}
						}
						$em->persist($chambre);
						$em->flush();
						$this->addFlash('notice', 'Chambre modifiée');
						return $this->redirectToRoute('Mecque_Hebergement');
					}
				}
				$response = $this->render('Rooming/chambreedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function chambreMecqueEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = $em->getRepository(ChambreMecque::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));
				$form = $this->createForm(ChambreMecqueType::class, $chambre, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('nombre');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						if(count($chambre->getPelerins()) > $chambre->getPlace())
						{
							$this->addFlash('notice', 'Plus d\'occupants que de lits. Liberez des lits');
							return $this->render('Rooming/chambreedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
						}
						if(!empty($chambre->getNumeroreel()))
						{
							$nbchambre = $em->getRepository(ChambreMecque::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'hotel' => $chambre->getHotel()->getId(), 'numeroreel' => $chambre->getNumeroreel()));//verification existant numero chambre
							if(!empty($nbchambre))
							{
								if($nbchambre->getId() != $id)
								{
									$this->addFlash('notice', 'Une autre chambre du même hôtel possède déjà ce numéro');
									return $this->render('Rooming/chambremecqueedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
								}
							}
						}
						$em->persist($chambre);
						$em->flush();
						$this->addFlash('notice', 'Chambre modifiée');
						return $this->redirectToRoute('Mecque_HebergementMecque');
					}
				}
				$response = $this->render('Rooming/chambremecqueedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function chambreDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = $em->getRepository(Chambre::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $request->get('chr'), 'session' => $session->getId()));
						
				$em->remove($chambre);
				$em->flush();
				$this->addFlash('sup', 'Chambre supprimée');
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
	
	public function chambreMecqueDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = $em->getRepository(ChambreMecque::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $request->get('chr'), 'session' => $session->getId()));
						
				$em->remove($chambre);
				$em->flush();
				$this->addFlash('sup', 'Chambre Supprimée');
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
	
	public function creationChambre(Request $request,$id,$nombre)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = $em->getRepository(Chambre::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));
				$numero = (int)$chambre->getNumero();
				if(!empty($chambre))
				{
					$hotel = $em->getRepository(Hotel::class)->find($chambre->getHotel()->getId());
					for($i = 0; $i < $nombre; $i++)
					{
						$tamp = 1;
						while( $tamp != 0){//verification si numero de chambre +1 existe 
							$numero = $numero +1;
							$tamp = count($em->getRepository(Chambre::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'numero' =>$numero, 'session' => $session->getId())));
						}
						$creerchambre = new Chambre();
						$creerchambre->setHotel($hotel);
						$creerchambre->setNumero($numero);
						$creerchambre->setSession($session);
						$creerchambre->setPlace($chambre->getPlace());
						$creerchambre->setType($chambre->getType());
						$creerchambre->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($creerchambre);
					}
					$em->flush();
					$this->addFlash('notice', 'Chambres créées');
					return $this->redirectToRoute('Mecque_Hebergement');
				}
				else throw $this->createNotFoundException();
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function chambreMecque(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = new ChambreMecque();
				$form = $this->createForm(ChambreMecqueType::class, $chambre, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('numeroreel');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$chambre->setSession($session);
						$chambres = $em->getRepository(ChambreMecque::class)->mecque($this->getUser()->getAgence()->getId(), $session->getId());
						$numero = count($chambres);//recuperation du nombre de chambre
						$tamp = 1;
						while( $numero != 0 && $tamp != 0){//verification si numero de chambre +1 existe 
							$numero = $numero +1;
							$tamp = count($em->getRepository(ChambreMecque::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'numero' =>$numero, 'session' => $session->getId())));
						}
						if($numero == 0)$chambre->setNumero(1);
						else $chambre->setNumero($numero);
						$nombre = $chambre->getNombre();
						$chambre->setNombre(null);
						$chambre->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($chambre);
						$em->flush();
						if($nombre > 1)
							return $this->redirectToRoute('Mecque_CreationChambreMecque', array('id' =>$chambre->getId(), 'nombre' => $nombre-1));
						else
						{
							$this->addFlash('notice', 'Chambre créée');
							return $this->redirectToRoute('Mecque_HebergementMecque');
                        }
					}
				}
				$response = $this->render('Rooming/chambremecque.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function creationChambreMecque(Request $request,$id,$nombre)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$chambre = $em->getRepository(ChambreMecque::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));
				$numero = (int)$chambre->getNumero();
				if(!empty($chambre))
				{
					$hotel = $em->getRepository(Hotel::class)->find($chambre->getHotel()->getId());
					for($i = 0; $i < $nombre; $i++)
					{
						$tamp = 1;
						while( $tamp != 0){//verification si numero de chambre +1 existe 
							$numero = $numero +1;
							$tamp = count($em->getRepository(ChambreMecque::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'numero' =>$numero, 'hotel' =>$hotel->getId(), 'session' => $session->getId())));
						}
						$creerchambre = new ChambreMecque();
						$creerchambre->setHotel($hotel);
						$creerchambre->setNumero($numero);
						$creerchambre->setSession($session);
						$creerchambre->setPlace($chambre->getPlace());
						$creerchambre->setType($chambre->getType());
						$creerchambre->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($creerchambre);
					}
					$em->flush();
					$this->addFlash('notice', 'Chambres créées');
					return $this->redirectToRoute('Mecque_HebergementMecque');
				}
				else throw $this->createNotFoundException();
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function occuperChambre(Request $request,$id, $type)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Chambre::class);
				$chambre = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));//
				if(!empty($chambre) && count($chambre->getPelerins()) < $chambre->getPlace())
				{
						if(!empty($chambre->getPelerins()))
						{
							foreach($chambre->getPelerins() as $pelerin)
							{
								if($pelerin->getSexe() != $type) throw $this->createNotFoundException();
							}
						}
					if($type == 'Feminin')$pelerins = $em->getRepository(Pelerin::class)->findBy(array('chambre' => null,'sexe' => 'Feminin', 'session' => $session->getId()));
					else $pelerins = $em->getRepository(Pelerin::class)->findBy(array('chambre' => null,'sexe' => 'Masculin', 'session' => $session->getId()));
					return $this->render('Rooming/occuperchambre.html.twig', array('chambre' => $chambre, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
				}
				else throw $this->createNotFoundException();
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function occuperChambreMecque(Request $request,$id, $type)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(ChambreMecque::class);
				$chambre = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $id, 'session' => $session->getId()));//
				if(!empty($chambre) && count($chambre->getPelerins()) < $chambre->getPlace())
				{
						if(!empty($chambre->getPelerins()))
						{
							foreach($chambre->getPelerins() as $pelerin)
							{
									if($pelerin->getSexe() != $type) throw $this->createNotFoundException();
							}
						}
					if($type == 'Feminin')$pelerins = $em->getRepository(Pelerin::class)->findBy(array('chambremecque' => null,'sexe' => 'Feminin', 'session' => $session->getId()));
					else $pelerins = $em->getRepository(Pelerin::class)->findBy(array('chambremecque' => null,'sexe' => 'Masculin', 'session' => $session->getId()));
					return $this->render('Rooming/occuperchambremecque.html.twig', array('chambre' => $chambre, 'pelerins' =>$pelerins, 'session' => $session->getDesignation()));
				}
				else throw $this->createNotFoundException();
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function occupationChambre(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('chambre'))
				{
					$pel = explode(";",$request->get('pel'));
					$chambre = $em->getRepository(Chambre::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'id' =>$request->get('chambre')));
					$em->getRepository(Pelerin::class)->occupation($session->getId(), $chambre->getId(), $pel);
					$this->addFlash('notice', 'Hebergement effectué');
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
	
	public function occupationChambreMecque(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->get('pel') && $request->get('chambre'))
				{
					$pel = explode(";",$request->get('pel'));
					$chambre = $em->getrepository(ChambreMecque::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(),'id' =>$request->get('chambre')));
					$em->getrepository(Pelerin::class)->occupationMecque($session->getId(), $chambre->getId(), $pel);
					$this->addFlash('notice', 'Hebergement effectué');
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
	
    public function userLibere(Request $request)
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
						$pelerin->setChambre(null);
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
	
	public function userLibereMecque(Request $request)
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
						$pelerin->setChambremecque(null);
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
	
    public function all(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$em = $this->getDoctrine()->getManager();
				$chambres = $em->getRepository(Chambre::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Rooming/all.html.twig', array('chambres' => $chambres));
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

    /**
     * @Route("/Allexcel", name="Mecque_All_Excel")
     */
    public function allexcel(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$em = $this->getDoctrine()->getManager();
				$chambres = $em->getRepository(Chambre::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Rooming/allexcel.html.twig', array('chambres' => $chambres));
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
	
	public function allMecque(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$em = $this->getDoctrine()->getManager();
				$chambres = $em->getRepository(ChambreMecque::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Rooming/allmecque.html.twig', array('chambres' => $chambres));
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

    /**
     * @Route("/AllMecqueexcel", name="Mecque_allMecque_Excel")
     */
    public function allMecqueExcel(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$em = $this->getDoctrine()->getManager();
				$chambres = $em->getRepository(ChambreMecque::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Rooming/allmecqueexcel.html.twig', array('chambres' => $chambres));
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
	
}
