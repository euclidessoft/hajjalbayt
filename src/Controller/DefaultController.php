<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\PelerinCourtier;
use App\Entity\Courtier;
use App\Entity\Pelerin;
use App\Entity\Image;
use App\Entity\OldPelerin;
use App\Entity\Reduction;
use App\Entity\Depense;
use App\Entity\Session;
use App\Entity\Debit;
use App\Entity\Credit;
use App\Entity\Gain;
use App\Entity\Pack;
use App\Entity\Versement;
use App\Form\PelerinType;
use App\Form\CreditType;
use App\Form\CourtierType;
use App\Form\ImageType;
use App\Form\ReductionType;
use App\Form\DepenseType;
use App\Form\SessionType;
use App\Form\GainType;
use App\Form\PackType;
use App\Form\VersementType;
use App\Entity\PelerinWating;
use App\Form\PelerinWatingType;
use App\Entity\Agence;
use App\Entity\Groupement;
use App\Entity\Vol;


/**
 * @Route("/{_locale}")
 */
class DefaultController extends AbstractController
{
    public function index(Request $request)
    {
    	if($this->get('security.authorization_checker')->isGranted('ROLE_CONCEPTEUR'))
		{
			return $this->redirect($this->generateUrl('agence_list'));
		}
    	else if( $this->get('security.authorization_checker')->isGranted('ROLE_MEDECIN'))
		{
		    if(!$this->getUser()->getEnabled())
		    {
		        $this->addFlash('change', 'Vous avez pas le droit de vous connecter avec ce compte. Veuillez contacter votre administrateur');
		       
		        $response = $this->redirectToRoute('security_logout');
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
			return $this->redirect($this->generateUrl('Mecque_Medical_Home'));
		}
		else if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
		    if(!$this->getUser()->getEnabled())
		    {
		        $this->addFlash('change', 'Vous avez pas le droit de vous connecter avec ce compte. Veuillez contacter votre administrateur');
		        return $this->redirect($this->generateUrl('security_logout'));
		    }
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				//$request->getSession()->getFlashBag()->add('notice', 'les 2000 riyals supplementaires concernant les pelerins ayant ete a la mecque entre 2014-2018 sont entrain d\'etre implementes et ca concerne tout le systeme. Signalez la nous si vous constatez une erreur. Merci !!!!! ');

				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$pelerinother = $em->getRepository(Pelerin::class)->other($this->getUser()->getAgence()->getId(), $session->getId());
				$wates = $em->getRepository(PelerinWating::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$remark = $em->getRepository(Pelerin::class)->remark($session->getId());
				$enregle = 0;
				$pasenregle = 0;
				$accompte = 0;
				$dossier = 0;
				$nombre = 0;
				
				foreach($pelerinother as $pelerin)
				{
					if(!$pelerin->Dossier())//verification dossier incomplet
					{
						$dossier = $dossier+1;
					}
					
					$montant = $pelerin->getSituation();
					
					if($pelerin->getCompte() == $montant || $pelerin->getExonorer() == true)
					{
						$enregle = $enregle+1;
						continue;
					}
					if($pelerin->getCompte() > 0 && $pelerin->getCompte() < $montant && $pelerin->getExonorer() == false)
					{
						$accompte = $accompte+1;
						continue;
					}
					if($pelerin->getCompte() == 0 && $pelerin->getExonorer() == false)
					{
						$pasenregle = $pasenregle+1;
					}
				}
				foreach($pelerins as $pelerin)
				{
					if(!$pelerin->Dossier())//verification dossier incomplet
					{
						$dossier = $dossier+1;
					}
					
					$montant = $pelerin->getPack()->getMontant();
					if($pelerin->getReduction())
					{
						if($montant == $pelerin->getReduction()->getMontant())// cas ou la reduction egale au monatnt du pack (exonorer)
						{
							$enregle = $enregle+1;
							continue;							
						}
						$montant = $montant - $pelerin->getReduction()->getMontant();
					}
					if($pelerin->getCompte() == $montant || $pelerin->getExonorer() == true)
					{
						$enregle = $enregle+1;
						continue;
					}
					if($pelerin->getCompte() > 0 && $pelerin->getCompte() < $montant && $pelerin->getExonorer() == false)
					{
						$accompte = $accompte+1;
						continue;
					}
					if($pelerin->getCompte() == 0 && $pelerin->getExonorer() == false)
					{
						$pasenregle = $pasenregle+1;
					}
					
				}
				$response = $this->render('Default/index.html.twig',array('nombre' => count($pelerins), 'enregle' => $enregle, 'pasenregle' => $pasenregle, 'dossier' => $dossier, 'attente' => count($wates), 'session' => $session->getDesignation(), 'remark' =>count($remark), 'soldeagence' => count($pelerinother), 'accompte' =>$accompte ));
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

    public function surplus(Request $request)
    {
		
		$session = $request->getSession();
		if($request->get('surplus') == 'oui')
			$session->set('surplus', 'oui');
		else
			$session->remove('surplus');
		$res['ok']= '';	
		$response = new Response();
		$response->headers->set('content-type','application/json');
		$re = json_encode($res);
		$response->setContent($re);
		return $response;
    }
	
    public function pelerinAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$sessionpel = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($sessionpel))
			{
				$courtiers = $this->getDoctrine()->getManager()->getRepository(Courtier::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
				if($sessionpel->getChef() == true)$groupement = $this->getDoctrine()->getManager()->getRepository(Groupement::class)->findBy(array('chef' => $this->getUser()->getAgence()->getId(), 'session' => $sessionpel->getId()));
				else $groupement = null;
				$session = $request->getSession();
				$pelerin = new Pelerin();
				$form = $this->createForm(PelerinType::class, $pelerin, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('numdossier');
				if(!$this->get('security.authorization_checker')->isGranted('ROLE_PROPRIETAIRE')) $form->remove('reduction');
				$form->remove('image');
				$form->remove('numsaoudiantax');
				if( $request->isXmlHttpRequest() )
				{// traitement de la requete ajax amener par un courtier
				    if($request->get('elv'))
				    {
				        $id = (int)$request->get('elv');// recuperation de la parametres elv envoye par ajax
				        if($id == -1)
				        {
				            $session->remove('courtier');
				            $res['id']= 'ok';
				        }
				        else if($id != 0)
				        {// condition verifiant le choix d'un eleve
				            $em = $this->getDoctrine()->getManager();
				            $repository = $em->getRepository(Courtier::class);
				            $courtier = $repository->find($id);//
				            if(!empty($courtier))
				            {
				                $res['id'] = $courtier->getId();
				                $res['prenom'] = $courtier->getPrenom();
				                $res['nom'] = $courtier->getNom();
				                $res['phone'] = $courtier->getPhone();
				                $session->set('courtier', $courtier->getId());// memorisaation de l'eleve choisi dans la variable de session eleve
				            }
				            else
				            {
				                
				                $res['id'] = '';
				            }
				        }
				        else
				        {// annulation du choix
				            if($session->get('courtier')) $session->remove('courtier');
				            $res['id']= 'ok';
				        }
				    }
				    else if($request->get('agence'))
				    {
				        $id = (int)$request->get('agence');// recuperation de la parametres agence envoye par ajax
				        if($id == -1)
				        {
				            $session->remove('agence');
				            $res['id']= 'ok';
				        }
				        else if($id != 0)
				        {// condition verifiant le choix d'un eleve
				            $em = $this->getDoctrine()->getManager();
				            $repository = $em->getRepository(Agence::class);
				            $agence = $repository->find($id);//
				            if(!empty($agence))
				            {
				                $res['id'] = $agence->getId();
				                $res['nom'] = $agence->getNom();
				                $res['adresse'] = $agence->getAdresse();
				                $session->set('agence', $agence->getId());// memorisaation de l'eleve choisi dans la variable de session eleve
				            }
				            else
				            {
				                
				                $res['id'] = '';
				            }
				        }
				        else
				        {// annulation du choix
				            $session->remove('agence');
				            $res['id']= 'ok';
				        }
				    }
				    else
				    {
				        if($request->get('exonore') == '1')
				        {
				            $session->set('exonorer', 'exonorer');
				            $res['id'] = 'ok';
				        }
				        else
				        {
				            $session->remove('exonorer');
				            $res['id'] = 'ok';
				        }
				    }
				    
				    $response = new Response();
				    $response->headers->set('content-type','application/json');
				    $re = json_encode($res);
				    $response->setContent($re);
				    return $response;
				}
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						if($session->get('exonorer'))
						{
							$pelerin->setExonorer(true);
							$session->remove('exonorer');
						}
						if($session->get('agence'))
						{// traitement pelerin autres agences du groupement
							$agencegroup = $em->getrepository(Groupement::class)->findOneby(array('agence' =>$session->get('agence'), 'session' => $sessionpel->getId()));
							$agence = $em->getrepository(Agence::class)->find($session->get('agence'));
							$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId(), 'agence' => $session->get('agence')));

							if(count($pelerins) < $agencegroup->getQuota())
							{
								$session->remove('agence');
								$pelerin->setReduction(null);
								$pelerin->setAgence($agence);
								$pelerin->setNumdossier(count($em->getrepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId())))+1);
								$pelerin->setSession($sessionpel);
								if($session->get('surplus'))
								{
									$pelerin->setSurplus(true);
									$session->remove('surplus');
								}
								$em->persist($pelerin);
								$em->flush();
								return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
							}
							else
							{
								$session->remove('agence');
								$this->get('session')->getFlashBag()->add('notice', 'Quota atteint par l\'agence');
							}
						}
						else
						{
							$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId(), 'agence' => $this->getUser()->getAgence()->getId()));
							if(count($pelerins) < $sessionpel->getCotat())
							{//enregistrement pelerin si non groupement ou groupement etant pas chef de groupe
								$pelerin->setSession($sessionpel);
								
								//gestion de la reduction
								if($this->get('security.authorization_checker')->isGranted('ROLE_PROPRIETAIRE'))
								{
									if(!empty($pelerin->getReduction()->getMontant()))
									{
										$pelerin->getReduction()->setSession($sessionpel);
									}
									else
									{
										$pelerin->setReduction(null);
									}
								}
								//fin getion reduction
								if($session->get('courtier'))
								{// si le pelerin est ammene par un courtier 
									$courtierpelerin = new PelerinCourtier();
									$courtier = $em->getrepository(Courtier::class)->find($session->get('courtier'));
									$courtierpelerin->setPelerin($pelerin);
									$courtierpelerin->setCourtier($courtier);
									$courtierpelerin->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
									$courtierpelerin->setSession($sessionpel);
									$em->persist($courtierpelerin);
									$session->remove('courtier');
								}
								if($session->get('surplus'))
								{
									$pelerin->setSurplus(true);
									$session->remove('surplus');
								}
								$pelerin->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
								$pels = $this->getDoctrine()->getManager()->getRepository(Pelerin::class)->findBy(array( 'session' => $sessionpel->getId()));
								$numero = count($pels);//recuperation du nombre de chambre
								$tamp = 1;
								while( $numero != 0 && $tamp != 0){//verification si le numero de chambre nombre+1 existe 
									$numero = $numero +1;
									$tamp = count($em->getRepository(Pelerin::class)->findBy(array( 'numdossier' =>$numero, 'session' => $session->getId())));
								}
								if($numero == 0)$numero = 1;
								$pelerin->setNumdossier($numero);
								$em->persist($pelerin);
								$em->flush();
								return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
							}
							else
							$this->get('session')->getFlashBag()->add('notice', 'Quota atteint');
						}
					}
				}
				
				$response = $this->render('Default/pelerinadd.html.twig', array('form' => $form->createView(), 'courtiers' => $courtiers, 'session' => $sessionpel->getDesignation(),'agences' => $groupement));
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
	
	public function pelerinEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Pelerin::class);
				$pelerin = $repository->find($id);
				$form = $this->createForm(PelerinType::class, $pelerin, [ 'id' => $this->getUser()->getAgence()->getId()]);
				if(!$this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
				{
					$form->remove('prenom');
					$form->remove('nom');
					$form->remove('sexe');
					$form->remove('pack');
				}
				$form->remove('numdossier');
				$form->remove('pack');
				$form->remove('image');
				$form->remove('reduction');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						//fin getion reduction
						$sess = $request->getSession();
						if($sess->get('surplus'))
						{
							$pelerin->setSurplus(true);
							$sess->remove('surplus');
						}
						else $pelerin->setSurplus(false);
						$em->persist($pelerin);
						$em->flush();
						return $this->redirectToRoute('Mecque_Pelerin',array('pelerin' => $pelerin->getId()));
					}
				}
				$response = $this->render('Default/pelerinedit.html.twig', array('form' => $form->createView(),'pelerin' => $pelerin, 'session' => $session->getDesignation(), 'mecque' => $pelerin->getSurplus()));
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
	
	public function pelerinList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				$response = $this->render('Default/pelerinlist.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'tous' => 'ok'));
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
    * @Route("/Pelerin_List/Bataaxal", name="pelerin_list_Bataaxal")
    */
    public function pelerinListBataaxal(Request $request)
    {
    	$agence = $_POST['id'];
    	//$_POST['id'] = "8";
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $_POST['id'], 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				$result['error'] = false;
		        $result['message'] = [];
		        if (!empty($pelerins)) {

		            foreach ($pelerins as $pelerin) {// historique de paiement de léleve
		                $res['id'] = $pelerin->getId();
		                $res['nom'] = $pelerin->getPrenom(). " " .$pelerin->getNom();
		                $res['phone'] = $pelerin->getPhone();
		                $result['message'][] = $res;
		            }
		        }
		       
			}
			else{
				$result['error'] = true;
		        $result['message'] = 'aucune session';
			}
			 $response = new Response();
	        $response->headers->set('content-type', 'application/json');
	        $re = json_encode($result);
	        $response->setContent($re);
	        return $response;
		
    }

    public function oldPeople(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$annee = (int)date('Y') -60;
				$pelerins = $em->getRepository(Pelerin::class)->Old($session->getId(),$annee);
				$response = $this->render('Default/oldpeople.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'old' => 'ok'));
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

    public function oldPeopletemp(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$annee = (int)date('Y') -60;
				$pelerins = $em->getRepository(Pelerin::class)->Old($session->getId(),$annee);
				$response = $this->render('Default/oldpeopletemp.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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

	public function chefpelerinList(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId(), 'agence' => $session->getAgence()->getId()));
				$response = $this->render('Default/chefpelerinlist.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	
    public function pelerinDossier()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				$dossier = array();
				foreach($pelerins as $pelerin)
				{
					
					if(!$pelerin->Dossier())
					{
						$dossier []= $pelerin;
					}
				}
				$response = $this->render('Default/pelerindossier.html.twig',array('pelerins' => $dossier, 'session' => $session->getDesignation() ));
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
	
	public function pelerinWating()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(PelerinWating::class)->findBy(array('agence' => $this->getUser()->getAgence(), 'session' => $session->getId()));
				$response = $this->render('Default/pelerinwating.html.twig',array('pelerins' => $pelerins, 'session' => $session->getDesignation() ));
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
	
	public function pelerinOk(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				$pelerins= array();
				foreach($pels as $pel)
				{
						$montant = $pel->getSituation();
						if($pel->getCompte() == $montant || $pel->getExonorer() == true)
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/pelerinok.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	
	public function pelerinNotOk(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			$pelerins = array();
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				foreach($pels as $pel)
				{
					$montant = $pel->getSituation();
					if($pel->getCompte() < $montant && $pel->getCompte() > 0 && $pel->getExonorer() == false)
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/pelerinnotok.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	
    public function pelerinConfirmer(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			$pelerins = array();
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				foreach($pels as $pel)
				{
					$montant = $pel->getPack()->getMontant();
					if($pel->getReduction())
					{
						if($montant == $pel->getReduction()->getMontant())
						{
							continue;							
						}
						$montant = $montant - $pel->getReduction()->getMontant();
					}
					if($pel->getCompte() == 0 && $pel->getExonorer() == false)
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/pelerinconfirmer.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	
	public function pelerinCourtierList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(PelerinCourtier::class)->findBy(array('session' => $session->getId()));
				$response = $this->render('Default/pelerincourtierlist.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'courtier' => 'ok'));
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
	
    public function pelerinOldList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(OldPelerin::class)->findBy(array('session' => $session->getId()));
				$response = $this->render('Default/pelerinoldlist.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'abandons' => 'ok'));
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
	
	public function pelerinListMale(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId(), 'sexe' => 'Masculin'));
				$response = $this->render('Default/pelerinlistmale.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'male' => 'ok'));
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
	
	public function pelerinListFemale(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId(), 'sexe' => 'Feminin'));
				$response = $this->render('Default/pelerinlistfemale.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'female' => 'ok'));
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
	
	public function pelerinListFemaleless45(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$annee = (int)date('Y') -45;
				$pelerins = $em->getRepository(Pelerin::class)->Less($session->getId(),$annee);
				$hommes = $em->getRepository(Pelerin::class)->findBy(array('sexe' => 'Masculin', 'session' => $session->getId()));
				$response = $this->render('Default/pelerinlistfemaleless45.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'hommes' => $hommes, 'femaleless' => 'ok'));
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
	
	public function pelerinDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerin = $em->getRepository(Pelerin::class)->find($request->get('plr'));
				$old = new OldPelerin();
				$old->setPrenom($pelerin->getPrenom());
				$old->setNom($pelerin->getNom());
				$old->setAdresse($pelerin->getAdresse());
				$old->setPhone($pelerin->getPhone());
				$old->setSexe($pelerin->getSexe());
				$old->setfamillyphone($pelerin->getFamillyphone());
				$old->setBloodgroup($pelerin->getBloodgroup());
				$old->setPack($em->getRepository(Pack::class)->find($pelerin->getPack()->getId()));
				$old->setAgence($em->getRepository(Agence::class)->find($pelerin->getAgence()->getId()));
				$old->setSession($session);
				//if($pelerin->getImage() != null)$old->setImage($em->getRepository(Image::class)->find($pelerin->getImage()->getId()));
				$old->setDiabete($pelerin->getDiabete());
				$old->setHypo($pelerin->gethypo());
				$old->setHyper($pelerin->gethyper());
				$old->setHandicap($pelerin->getHandicap());
				$old->setNonvoyant($pelerin->getNonvoyant());
				$old->setRemark($pelerin->getRemark());
				$old->setNumdossier($pelerin->getNumdossier());
				$old->setPhoto($pelerin->getPhoto());
				$old->setVisite($pelerin->getVisite());
				if(!empty($pelerin->getVersements()))
				{
					$somme = 0;
					foreach($pelerin->getVersements() as $versement)
					{
						$credit = $em->getRepository(Credit::class)->findOneBy(array('versement' =>$versement->getId()));
						$versement = $em->getRepository(Versement::class)->find($versement->getId());
						$somme = $somme + $versement->getMontant();
						$em->remove($credit);
						$em->remove($versement);
					}
					
					$old->setSomme($somme);
				}
				else
				{
					$old->setSomme(0);
				}
				$em->persist($old);
				$em->remove($pelerin);
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
	
	public function Versement(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				$session = $request->getSession();
				if($id < 0)
				{
					throw $this->createNotFoundHttpException();
				} 
				$pelerin =$this->getDoctrine()->getManager()->getRepository(Pelerin::class)->Find($id);
				$versement = new Versement();
				$form = $this->createForm(VersementType::class, $versement);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$test = 0;
						$pelerin = $em->getRepository(Pelerin::class)->Find($id);
						//if($pelerin->getAgence() != null) return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
						if($pelerin->getExonorer() == true) 
						{
							return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
						}
						$montant = $pelerin->getSituation();
						if(!empty($pelerin->getVersements()))
						{
							foreach($pelerin->getVersements() as $verser)
							{
								$test = $test + $verser->getMontant();
							}
						}
						if($test <= $montant && $test+ $versement->getMontant() <= $montant)
						{
						
							if($versement->getType() == 'Espece')
							{
								$versement->setBanque(null);
								$versement->setNumero(null);
							}
							$versement->setPelerin($pelerin);
							$versement->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
							$versement->setSession($pelsession);
							$versement->setFacturer($this->getUser());
							$em->persist($versement);
							$em->flush();
							if( $this->getUser()->getAgence()->getCaisse() == true)
							{
								$session->getFlashBag()->add('notice', 'Vous venez de facturer la somme de : '.number_format($versement->getMontant(), 0, '', ' ').' F CFA en attente d\'encaissement');
								return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
							}
							else
							{
								$session->set('vsm', $versement->getId());
								return $this->redirectToRoute('Mecque_Encaisser');
							}
						}
						else 
						{
							$session->getFlashBag()->add('annuleversement', 'Versement impossible, montant du package dépassé');// message dans flashbag existant annuleversement
							return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
						}
					}
				}
				$response = $this->render('Default/versement.html.twig', array('pelerin' => $pelerin, 'form' => $form->createView(), 'session' => $pelsession->getDesignation()));
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
	
	public function confirm($plr,$vsm)
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') || $this->getUser()->getCaisse() == true)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerin = $em->getRepository(Pelerin::class)->Find($plr);
				$versement = $em->getRepository(Versement::class)->findOneBy(array('id' => $vsm, 'pelerin' => $pelerin->getId(), 'session' => $session->getId()));
				$response = $this->render('Default/confirm.html.twig', array('pelerin' => $pelerin, 'versement' => $versement, 'session' => $session->getDesignation()));
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
	
	public function confirmtemp($plr,$vsm)
	{
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerin = $em->getRepository(Pelerin::class)->Find($plr);
				$versement = $em->getRepository(Versement::class)->findOneBy(array('id' => $vsm, 'pelerin' => $pelerin->getId(), 'session' => $session->getId()));
				$response = $this->render('Default/confirmtemp.html.twig', array('pelerin' => $pelerin, 'versement' => $versement));
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
	
	public function packAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pack = new Pack();
				$form = $this->createForm(PackType::class, $pack);
				$form->remove('aerien1');
				$form->remove('taxe');
				$form->remove('taxecout');
				$form->remove('mouna');
				$form->remove('mounacout');
				$form->remove('restomouna');
				$form->remove('restomounacout');
				$form->remove('assurance');
				$form->remove('assurancecout');
				$form->remove('pecule');
				$form->remove('peculecout');
				$form->remove('vaccin');
				$form->remove('vaccincout');
				$form->remove('zamzam');
				$form->remove('zamzamcout');
				$form->remove('guide');
				$form->remove('medecin');
				$form->remove('administratif');
				$form->remove('autreservice');
				if($session->getChef() == false) 
				{
					$form->remove('exploitation');
					$chef = null;
				}
				else $chef = 'chef';
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$pack->setSession($session);
						$pack->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($pack);
						$em->flush();
						return $this->redirectToRoute('Mecque_PackList');
					}
				}
				$response = $this->render('Default/packadd.html.twig', array('form' => $form->createView(), 'pack' => $pack, 'session' => $session->getDesignation(), 'chef' => $chef));
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
	
	public function packList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($session->getChef() == false) 
				{
					$chef = null;
				}
				else $chef = 'chef';
				$packs = $em->getRepository(Pack::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Default/packlist.html.twig', array('packs' => $packs, 'session' => $session->getDesignation(), 'chef' => $chef));
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
	
	public function packEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository  = $this->getDoctrine()->getManager()->getRepository(Pack::class);
				$pack = $repository->find($id);
				if($pack->getAgence()->getId() != $this->getUser()->getAgence()->getId() )
				{
					throw $this->createNotFoundException();
				}
				$form = $this->createForm(PackType::class, $pack);
				$form->remove('aerien1');
				$form->remove('taxe');
				$form->remove('taxecout');
				$form->remove('mouna');
				$form->remove('mounacout');
				$form->remove('restomouna');
				$form->remove('restomounacout');
				$form->remove('assurance');
				$form->remove('assurancecout');
				$form->remove('pecule');
				$form->remove('peculecout');
				$form->remove('vaccin');
				$form->remove('vaccincout');
				$form->remove('zamzam');
				$form->remove('zamzamcout');
				$form->remove('guide');
				$form->remove('medecin');
				$form->remove('administratif');
				$form->remove('autreservice');
				if($session->getChef() == false) 
				{
					$form->remove('exploitation');
					$chef = null;
				}
				else $chef = 'chef';
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$em->persist($pack);
						$em->flush();
						return $this->redirectToRoute('Mecque_PackList');
					}
				}
				$response = $this->render('Default/packedit.html.twig', array('form' => $form->createView(),'pack' => $pack, 'session' => $session->getDesignation(), 'chef' => $chef));
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
	
	public function packDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				if($request->isXmlHttpRequest())
				{
					$pack = $em->getRepository(Pack::class)->find($request->get('plr'));
					if($pack->getAgence()->getId() != $this->getUser()->getAgence()->getId() )
					{
						throw $this->createNotFoundException();
					}
					$em->remove($pack);
					$em->flush();
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
	
	public function SessionStart(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$previous = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(empty($previous))
			{
				$session = new Session();
				$form = $this->createForm(SessionType::class, $session);
				$form->remove('cotat');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$session->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
						$em->persist($session);
						$em->flush();
						$response = $this->redirectToRoute('Mecque_SessionConfig');
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
				return $this->render('Default/sessionstart.html.twig', array('form' => $form->createView()));
			}
			else return $this->redirectToRoute('Mecque_PreClose');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function SessionClose(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$previous = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($previous))
			{
				$previous->setCurrent(false);
				$previous->setDatefin(new \Datetime());
				$em->persist($previous);
				$em->flush();
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
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function preClose(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{	
			$em = $this->getDoctrine()->getManager();
			$previous = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($previous))
			{
				$response = $this->render('Default/preclose.html.twig', array( 'session' => $previous->getDesignation()));
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
	
	public function restart(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$previous = $em->getrepository(Session::class)->Restart();
			if(!empty($previous) && $previous->getCurrent() == false)
			{
				$today = new \Datetime();
				$dateline = new \DateInterval('P15D');
				$comp = $today->diff($previous->getDatefin());
				if($today->sub($dateline) < $previous->getDatefin())
				{
				
					$previous->setCurrent(true);
					$previous->setDatefin(null);
					$em->persist($previous);
					$em->flush();
					return $this->redirectToRoute('Mecque_homepage');
				}
				else return $this->redirectToRoute('Mecque_SessionStart');
			}
			else return $this->redirectToRoute('Mecque_PreClose');
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
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
			    $response = $this->render('Default/all.html.twig', array('pelerins' => $pelerins));
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
	
	public function allView(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$em = $this->getDoctrine()->getManager();
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$response = $this->render('Default/allview.html.twig', array('pelerins' => $pelerins));
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
	
    public function Ok(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				$pelerins= array();
				foreach($pels as $pel)
				{
						$montant = $pel->getSituation();
						if($pel->getCompte() == $montant || $pel->getExonorer() == true)
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/ok.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
    
	public function notOk(Request $request)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			$pelerins = array();
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId()));
				foreach($pels as $pel)
				{
					$montant = $pel->getSituation();
					if($pel->getCompte() < $montant && $pel->getCompte() > 0 && $pel->getExonorer() == false)
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/notok.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	
    public function pelerinListMaletemp(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId(), 'sexe' => 'Masculin'));
				$response = $this->render('Default/pelerinlistmaletemp.html.twig', array('pelerins' => $pelerins));
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
	
	public function pelerinListFemaletemp(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $session->getId(), 'sexe' => 'Feminin'));
				$response = $this->render('Default/pelerinlistfemaletemp.html.twig', array('pelerins' => $pelerins));
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
	
	public function pelerinListFemalelesstemp(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{	
				$annee = (int)date('Y') -45;
				$pelerins = $em->getRepository(Pelerin::class)->Less($session->getId(),$annee);
				$response = $this->render('Default/pelerinlistfemalelesstemp.html.twig', array('pelerins' => $pelerins));
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
	
	
	 
    public function pelerin(Request $request,$pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			if($pelerin < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				if(!$pelsession->getChef()) $pel = $em->getrepository(Pelerin::class)->findOneBy(array('id' =>$pelerin,'agence' => $this->getUser()->getAgence()->getId(), 'session' => $pelsession->getId()));
				else
				{
					$pel = $em->getrepository(Pelerin::class)->findOneBy(array('id' =>$pelerin, 'session' => $pelsession->getId()));
					if($this->getUser()->getAgence()->getId() != $pel->getAgence()->getId())
					{
						$groupe = $em->getRepository(Groupement::class)->findBy(array('agence' => $pel->getAgence()->getId(), 'session' => $pelsession->getId()));
						if(empty($groupe)) $pel = null;
					}
				}
				if(empty($pel))
				{
					throw $this->createNotFoundException();
				}
				$session = $request->getSession();
				if($session->get('new')) $session->remove('new');
				if($session->get('pelerin')) $session->remove('pelerin');
				$repository = $em->getRepository(Pelerin::class);
				$image = new Image();
				$form = $this->createForm(ImageType::class, $image);
				if($request->isMethod('POST'))
				{
					$pel = $em->getRepository(Pelerin::class)->find($pelerin);
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
						return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pel->getId()));
					}
				}
				if($pelsession->getChef() == true) $agence = $pel->getAgence()->getNom();// designation l'agence si non chef de groupe
				else $agence = null;
				$versements = $em->getRepository(Versement::class)->findBy(array('session' => $pelsession->getId(), 'pelerin' => $pel->getId()), array('date' => 'desc'));
				$facturer = 0;
				
				foreach($versements as $versement)
				{// verification annulation facturer
					if($versement->getEncaisser() == null) 
						$facturer = $versement->getId();
						break;
				}
				$response = $this->render('Default/pelerin.html.twig', array( 'pelerin' => $pel, 'versements' => $versements, 'form' => $form->createView(), 'session' => $pelsession->getDesignation(), 'agence' => $agence, 'facturer' =>$facturer));
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
	
    public function test(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
				{
				
					$idpel = "711";
					$apayer = $em->getRepository(PelerinCourtier::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'pelerin' => $idpel));
					$apayer->setPayer(true);
					$apayer->setDatesave(new \Datetime());
					$em->persist($apayer);
					// debiter la commition dans la compabilite
					$spend = new Depense();
					$spend->setSession($session);
					$spend->setMotif('Commission'.' '.$apayer->getCourtier()->getPrenom().' '.$apayer->getCourtier()->getNom());
					$spend->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
					$spend->setMontant($apayer->getCourtier()->getCommition());
					$debit = new Debit();
					$debit->setDepense($spend);
					$debit->setSession($session);
					$debit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
					$em->persist($debit);
					$em->persist($spend);
					// fin debiter
					//$em->flush();
					$res['id'] = 'ok';
				
				
				$response = new Response();
				$response->headers->set('content-type','application/json');
				$re = json_encode($res);
				$response->setContent($re);
				return $response;
			}

	}
	
	public function oldPelerin(Request $request,$pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				$session = $request->getSession();
				if($session->get('new')) $session->remove('new');
				if($session->get('pelerin')) $session->remove('pelerin');
				$repository = $em->getRepository(OldPelerin::class);
				$pel = $repository->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' =>$pelerin, 'session' => $pelsession->getId()));
				$response = $this->render('Default/oldpelerin.html.twig', array( 'pelerin' => $pel, 'session' => $pelsession->getDesignation()));
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
	
	public function reduction(Request $request, $pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$reduction = new Reduction();
				$form = $this->createForm(reductionType::class, $reduction);
				$em = $this->getDoctrine()->getManager();
				$repository = $em->getRepository(Pelerin::class);
				$plr = $repository->find($pelerin);
				if(!empty($plr->getReduction()))throw $this->createNotFoundException();
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$plr->setReduction($reduction);
						$reduction->setSession($session);
						$em->persist($reduction);
						$em->persist($plr);
						$em->flush();
						return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $plr->getId()));
					}
				}
				$response = $this->render('Default/reduction.html.twig', array('form' => $form->createView(), 'pelerin' => $plr, 'session' => $session->getDesignation()));
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
	
	public function reductionEdit(Request $request, $pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Pelerin::class);
				$plr = $repository->find($pelerin);
				if($plr->getAgence()->getId() != $this->getUser()->getAgence()->getId())
				{
					$ingroup = $em->getrepository(Groupement::class)->findOneBy(array('agence' => $plr->getAgence()->getId(), 'chef' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
					if(empty($ingroup)) throw $this->createNotFoundException();
				}
				$reduction = $em->getRepository(Reduction::class)->findOneBy(array('id' => $plr->getReduction()->getId()));
				$form = $this->createForm(reductionType::class, $reduction);
				
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em->persist($reduction);
						$em->flush();
						return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $plr->getId()));
					}
				}
				$response = $this->render('Default/reductionedit.html.twig', array('form' => $form->createView(), 'pelerin' => $plr, 'session' => $session->getDesignation()));
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
	
	public function reductionList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$pelerins= array();
				foreach($pels as $pel)
				{
						if($pel->getReduction())
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/reductionlist.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'reductions' => 'ok'));
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
	
	public function reduct(Request $request)
    {
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pels = $em->getRepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId()));
				$pelerins= array();
				foreach($pels as $pel)
				{
						if($pel->getReduction())
						$pelerins[]= $pel;
				}
				$response = $this->render('Default/reduct.html.twig', array('pelerins' => $pelerins));
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
	
    public function versementEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$versement = $em->getRepository(Versement::class)->find($id);//recuperation du versememt
				$form = $this->createForm(VersementType::class, $versement);
				$montant = $versement->getMontant();
				$type = $versement->getType();
				$form->handleRequest($request);
				if ($request->isMethod('POST'))
				{
					
					if($form->isValid())
					{
						$montant = $versement->getMontant() - $montant;// difference emtre montant versement
						$pelerin = $em->getrepository(Pelerin::class)->find($versement->getPelerin()->getId());// recuperation du pelerin
						if($montant != 0 || $type != $versement->getType())
						{
							$credit = $em->getrepository(Credit::class)->findOneby(array('versement' => $versement->getId()));// recuperation du credit
							$pelerin->setCompte($montant);//modification compte pelerin
							$credit->setMontant($versement->getMontant());
							$em->persist($pelerin);
							$em->persist($credit);
							$em->persist($versement);
							$em->flush();
						}
						return $this->redirectToRoute('Mecque_Pelerin',array('pelerin' => $pelerin->getId()));
					}
				}
				$response = $this->render('Default/versementedit.html.twig', array('form' => $form->createView(), 'pelerin' => $versement->getPelerin(), 'session' => $session->getDesignation()));
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
	
	
	
	public function sessionList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$sessions = $em->getRepository(Session::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
			$response = $this->render('Default/sessionlist.html.twig', array('sessions' => $sessions));
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
	
	public function sessionEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$repository  = $this->getDoctrine()->getManager()->getRepository(Session::class);
			$session = $repository->findOneBY(array('id' => $id, 'current' => true));
			if(!empty($session))
			{
				$form = $this->createForm(SessionType::class, $session);
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						$em = $this->getDoctrine()->getManager();
						$em->persist($session);
						$em->flush();
						return $this->redirectToRoute('Mecque_SessionList');
					}
				}
				$response = $this->render('Default/sessionedit.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function courtierAdd(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$courtier = new Courtier();
			$form = $this->createForm(CourtierType::class, $courtier);
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if($form->isValid())
				{
					$em = $this->getDoctrine()->getManager();
					$courtier->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
					$em->persist($courtier);
					$em->flush();
					return $this->redirectToRoute('Mecque_CourtierList');
				}
			}
			$response = $this->render('Default/courtieradd.html.twig', array('form' => $form->createView()));
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
	
	public function courtierList(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$courtiers = $em->getRepository(Courtier::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
			$response = $this->render('Default/coutierlist.html.twig', array('courtiers' => $courtiers));
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
	
	public function courtiers(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$courtiers = $em->getRepository(PelerinCourtier::class)->state($session);
				$response = $this->render('Default/courtiers.html.twig', array('courtiers' => $courtiers));
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
	
	public function courtierEdit(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$repository  = $this->getDoctrine()->getManager()->getRepository(Courtier::class);
			$courtier = $repository->find($id);
			if($courtier->getAgence()->getId() != $this->getUser()->getAgence()->getId())
			{
				throw $this->createNotFoundException();
			}
			$form = $this->createForm(CourtierType::class, $courtier);
			if ($request->isMethod('POST'))
			{
				$form->handleRequest($request);
				if($form->isValid())
				{
					$em = $this->getDoctrine()->getManager();
					$em->persist($courtier);
					$em->flush();
					return $this->redirectToRoute('Mecque_CourtierList');
				}
			}
		    $response = $this->render('Default/courtieredit.html.twig', array('form' => $form->createView()));
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
	
	public function selCourtier(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$courtiers = $em->getRepository(PelerinCourtier::class)->select($session);
				$response = $this->render('Default/selectcourtier.html.twig', array('courtiers' => $courtiers));
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
	
	public function payementCourtier(Request $request, $id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				
				$courtier = $em->getRepository(Courtier::class)->find($id);
				$apayers = $em->getRepository(PelerinCourtier::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' =>$session, 'courtier' => $id, 'payer' => false));
				if(empty($apayers)) return $this->redirectToRoute('Mecque_SelCourtier');
				$response = $this->render('Default/payementcourtier.html.twig', array('apayers' => $apayers, 'courtier' => $courtier));
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
	 * @Route("/PayerCourtier", name="Mecque_PayerCourtier")
	 */
	public function payerCourtier(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
				{
							
				if($request->get('pel'))
				{
					$idpel = $request->get('pel');
					$apayer = $em->getRepository(PelerinCourtier::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'pelerin' => $idpel));
					$apayer->setPayer(true);
					$apayer->setDatesave(new \Datetime());
					$em->persist($apayer);
					// debiter la commition dans la compabilite
					$spend = new Depense();
					$spend->setSession($session);
					$spend->setMotif('Commission'.' '.$apayer->getCourtier()->getPrenom().' '.$apayer->getCourtier()->getNom());
					$spend->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
					$spend->setMontant($apayer->getCourtier()->getCommition());
					$spend->setType("Espece");
					$spend->setTransfert(false);
					$debit = new Debit();
					$debit->setDepense($spend);
					$debit->setSession($session);
					$debit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
					$em->persist($debit);
					$em->persist($spend);
					// fin debiter
					$em->flush();
					$res['id'] = 'ok';
				}
				if($request->get('court'))
				{
					$idcourt = $request->get('court');
					$paycourtier = $em->getRepository(PelerinCourtier::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'courtier' => $idcourt, 'payer' => false));
					if(!empty($paycourtier))
					{
						foreach($paycourtier as $payer)
						{
							$apayer = $em->getRepository(PelerinCourtier::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'pelerin' => $payer->getPelerin()->getId()));
							$apayer->setPayer(true);
							$apayer->setDatesave(new \Datetime());
							$em->persist($apayer);
							// debiter la commition dans la compabilite
							$spend = new Depense();
							$spend->setSession($session);
							$spend->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
							$spend->setMotif('Commission'.' '.$apayer->getCourtier()->getPrenom().' '.$apayer->getCourtier()->getNom());
							$spend->setMontant($apayer->getCourtier()->getCommition());
							$spend->setType("Espece");
							$spend->setTransfert(false);
							$debit = new Debit();
							$debit->setDepense($spend);
							$debit->setAgence($em->getRepository(Agence::class)->find($this->getUser()->getAgence()->getId()));
							$debit->setSession($session);
							$em->persist($debit);
							$em->persist($spend);
							$em->flush();
							// fin debiter
						}
					}
					$res['id'] = 'ok';
				}
				$response = new Response();
				$response->headers->set('content-type','application/json');
				$re = json_encode($res);
				$response->setContent($re);
				return $response;
			}

	}
	
	public function etatCourtier(Request $request, $id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$courtier = $em->getRepository(Courtier::class)->find($id);
				$apayers = $em->getRepository(PelerinCourtier::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' =>$session, 'courtier' => $id));
				if(empty($apayers)) return $this->redirectToRoute('Mecque_SelCourtier');
				$response = $this->render('Default/etatcourtier.html.twig', array('apayers' => $apayers, 'courtier' => $courtier));
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
	
	public function courtierDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$courtier = $em->getRepository(Courtier::class)->find($request->get('plr'));
			$em = $this->getDoctrine()->getManager();
			$em->remove($courtier);
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
	
	public function tutor(Request $request,$id)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			//$id = $request->get('plr');// recuperation de la parametres elv envoye par ajax
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$repository = $em->getRepository(Pelerin::class);
				$femme45 = $repository->find($id);//
				if(!empty($femme45->getVol()))
				{
					$hommes = $em->getRepository(Pelerin::class)->findBy(array('sexe' => 'Masculin','vol' => $femme45->getVol()->getId(), 'session' => $session->getId()));
					$response = $this->render('Default/mahram.html.twig', array('femme' => $femme45, 'pelerins' =>$hommes, 'session' => $session->getDesignation()));
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
					$this->get('session')->getFlashBag()->add('notice', 'Choisir d\'abord le vol car les deux pèlerins doivent être dans le même vol');
					return $this->redirectToRoute('Mecque_TransportAerien');
				}
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function mahram(Request $request)
    {
			//$id = $request->get('plr');// recuperation de la parametres elv envoye par ajax
		$em = $this->getDoctrine()->getManager();
		$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
		if(!empty($session))
		{
			$repository = $em->getRepository(Pelerin::class);
			$femme45 = $repository->find($request->get('femme'));//
			$homme = $repository->find($request->get('homme'));//
			$femme45->setParrain($homme);
			$em->persist($femme45);
			$em->flush();
			$this->get('session')->getFlashBag()->add('mahram', 'Operation reussie');
			$res['id']= 'ok';
					
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
		}
		else return $this->redirectToRoute('Mecque_SessionStart');
	}
	
	public function mahramCancel(Request $request)
    {
		
		$em = $this->getDoctrine()->getManager();
		$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
		if(!empty($session))
		{
			$repository = $em->getRepository(Pelerin::class);
			$femme45 = $repository->find($request->get('femme'));//
			$femme45->setParrain(null);
			$em->persist($femme45);
			$em->flush();
			$this->get('session')->getFlashBag()->add('mahram', 'Annulation reussie');
			$res['id']= 'ok';
					
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
		}
		else return $this->redirectToRoute('Mecque_SessionStart');
	}
	
	public function packChangement(Request $request,$pelerin)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				
				if($pelerin < 0)
				{
					throw $this->createNotFoundHttpException();
				} 
				$repository = $em->getRepository(Pelerin::class);
				$pel = $repository->find($pelerin);
				$form = $this->createForm(PelerinType::class, $pel, ['id' => $this->getUser()->getAgence()->getId()]);
				$form->remove('email');
				$form->remove('image');
				$form->remove('reduction');
				$form->remove('prenom');
				$form->remove('nom');
				$form->remove('sexe');
				$form->remove('phone');
				$form->remove('famillyphone');
				$form->remove('adresse');
				$form->remove('numpassport');
				$form->remove('bloodgroup');
				$form->remove('numsaoudiantax');
				$form->remove('datenaiss');
				$form->remove('lieunaiss');
				$form->remove('diabete');
				$form->remove('handicap');
				$form->remove('nonvoyant');
				$form->remove('hypo');
				$form->remove('hyper');
				$form->remove('visite');
				$form->remove('photo');
				$form->remove('cin');
				$form->remove('profession');
				$form->remove('famillyname');
				$form->remove('famillylink');
				$form->remove('ethnie');
				$form->remove('region');
				$form->remove('expiredate');
				$form->remove('numdossier');
				$form->remove('remark');
				if ($request->isMethod('POST'))
				{
					$form->handleRequest($request);
					if($form->isValid())
					{
						//fin getion reduction
						$em = $this->getDoctrine()->getManager();
						$em->persist($pel);
						$em->flush();
						return $this->redirectToRoute('Mecque_Pelerin',array('pelerin' => $pel->getId()));
					}
				}
				
				$response = $this->render('Default/packchangement.html.twig', array('pelerin' => $pel,'form' => $form->createView()));
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
	
	public function sessionConfig()
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$previous = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true, 'configurer' => false));
			if(!empty($previous))
			{
				$response = $this->render('Default/groupfirst.html.twig');
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
	
    public function sessionQuota(Request $request,$group)
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			//if($group != 'no' || $group != 'yes') throw $this->createNotFoundHttpException();// verification parametres reponse si yes or no
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true, 'configurer' => false));
			if(!empty($session))
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
						return $this->redirectToRoute('Mecque_SessionGroupCreate');
					}
				}
				$response = $this->render('Default/sessionquota.html.twig', array('form' => $form->createView(), 'group' => $group));
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
	
	public function sessionGroupConfig()
	{
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$previous = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true, 'configurer' => false));
			if(!empty($previous))
			{
				$response = $this->render('Default/sessiongroupconfig.html.twig');
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
	
	public function addPelerinWating(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(),  'session' => $session->getId(), 'agence' => null));
				if(count($pelerins)< $session->getCotat($this->getUser()->getAgence()->getId()))
				{
					$pelerin = new PelerinWating();
					$form = $this->createForm(PelerinWatingType::class, $pelerin);
					if ($request->isMethod('POST'))
					{
						$form->handleRequest($request);
						if($form->isValid())
						{
							$em = $this->getDoctrine()->getManager();
							$pelerin->setSession($session);
							$pelerin->setAgence($em->getRepository(Agence::class)->findOneBy(array( 'id' => $this->getUser()->getAgence()->getId())));
							$em->persist($pelerin);
							$em->flush();
							return $this->redirectToRoute('Mecque_PelerinWating');
						}
					}
				}
				else 
				{
					$this->get('session')->getFlashBag()->add('notice', 'Quota atteint');
					return $this->redirectToRoute('Mecque_homepage');
				}
				$response = $this->render('Default/pelerinwatingadd.html.twig', array('form' => $form->createView(), 'session' => $session->getDesignation()));
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
	
	public function pelerinConfirm(Request $request,$id)
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
        {
            $em = $this->getDoctrine()->getManager();
            $sessionpel = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
            if(!empty($sessionpel))
            {
                $courtiers = $this->getDoctrine()->getManager()->getRepository(Courtier::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId()));
                if($sessionpel->getChef() == true)$groupement = $this->getDoctrine()->getManager()->getRepository(Groupement::class)->findBy(array('chef' => $this->getUser()->getAgence()->getId(), 'session' => $sessionpel->getId()));
                else $groupement = null;
                $session = $request->getSession();
                $repository = $em->getRepository(PelerinWating::class);
                $pelerinwating = $repository->find($id);
                $pelerin = new Pelerin();
                $pelerin->setPrenom($pelerinwating->getPrenom());
                $pelerin->setNom($pelerinwating->getNom());
                $pelerin->setAdresse($pelerinwating->getAdresse());
                $pelerin->setPhone($pelerinwating->getPhone());
                $pelerin->setSexe($pelerinwating->getSexe());
                $form = $this->createForm(PelerinType::class, $pelerin, ['id' => $this->getUser()->getAgence()->getId()]);
                $form->remove('numdossier');
                if(!$this->get('security.authorization_checker')->isGranted('ROLE_PROPRIETAIRE')) $form->remove('reduction');
                $form->remove('image');
                $form->remove('numsaoudiantax');
                if ($request->isMethod('POST'))
                {
                    $form->handleRequest($request);
                    if($form->isValid())
                    {
                        if($session->get('exonorer'))
                        {
                            $pelerin->setExonorer(true);
                            $session->remove('exonorer');
                        }
                        if($session->get('agence'))
                        {// traitement pelerin autres agences du groupement
                            $agencegroup = $em->getrepository(Groupement::class)->findOneby(array('agence' =>$session->get('agence'), 'session' => $sessionpel->getId()));
                            $agence = $em->getrepository(Agence::class)->find($session->get('agence'));
                            $pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId(), 'agence' => $session->get('agence')));

                            if(count($pelerins) < $agencegroup->getQuota())
                            {
                                $session->remove('agence');
                                $pelerin->setReduction(null);
                                $pelerin->setAgence($agence);
                                $pelerin->setNumdossier(count($em->getrepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId())))+1);
                                $pelerin->setSession($sessionpel);
                                $em->persist($pelerin);
                                $em->remove($pelerinwating);
                                $em->flush();
                                return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
                            }
                            else
                            {
                                $session->remove('agence');
                                $this->get('session')->getFlashBag()->add('notice', 'Quota atteint par l\'agence');
                            }
                        }
                        else
                        {
                            $pelerins = $em->getRepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId(), 'agence' => $this->getUser()->getAgence()->getId()));
                            if(count($pelerins) < $sessionpel->getCotat())
                            {//enregistrement pelerin si non groupement ou groupement etant pas chef de groupe
                                $pelerin->setSession($sessionpel);

                                //gestion de la reduction
                                if($this->get('security.authorization_checker')->isGranted('ROLE_PROPRIETAIRE'))
                                {
                                    if(!empty($pelerin->getReduction()->getMontant()))
                                    {
                                        $pelerin->getReduction()->setSession($sessionpel);
                                    }
                                    else
                                    {
                                        $pelerin->setReduction(null);
                                    }
                                }
                                //fin getion reduction
                                if($session->get('courtier'))
                                {// si le pelerin est ammene par un courtier
                                    $courtierpelerin = new PelerinCourtier();
                                    $courtier = $em->getrepository(Courtier::class)->find($session->get('courtier'));
                                    $courtierpelerin->setPelerin($pelerin);
                                    $courtierpelerin->setCourtier($courtier);
                                    $courtierpelerin->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
                                    $courtierpelerin->setSession($sessionpel);
                                    $em->persist($courtierpelerin);
                                    $session->remove('courtier');
                                }
                                $pelerin->setAgence($em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId())));
                                $pelerin->setNumdossier(count($em->getrepository(Pelerin::class)->findBy(array('session' => $sessionpel->getId())))+1);
                                $em->persist($pelerin);
                                $em->remove($pelerinwating);
                                $em->flush();
                                return $this->redirectToRoute('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
                            }
                            else
                                $this->get('session')->getFlashBag()->add('notice', 'Quota atteint');
                        }
                    }
                }
                if( $request->isXmlHttpRequest() )
                {// traitement de la requete ajax amener par un courtier
                    if($request->get('elv'))
                    {
                        $id = (int)$request->get('elv');// recuperation de la parametres elv envoye par ajax
                        if($id == -1)
                        {
                            $session->remove('courtier');
                            $res['id']= 'ok';
                        }
                        else if($id != 0)
                        {// condition verifiant le choix d'un eleve
                            $em = $this->getDoctrine()->getManager();
                            $repository = $em->getRepository(Courtier::class);
                            $courtier = $repository->find($id);//
                            if(!empty($courtier))
                            {
                                $res['id'] = $courtier->getId();
                                $res['prenom'] = $courtier->getPrenom();
                                $res['nom'] = $courtier->getNom();
                                $res['phone'] = $courtier->getPhone();
                                $session->set('courtier', $courtier->getId());// memorisaation de l'eleve choisi dans la variable de session eleve
                            }
                            else
                            {

                                $res['id'] = '';
                            }
                        }
                        else
                        {// annulation du choix
                            if($session->get('courtier')) $session->remove('courtier');
                            $res['id']= 'ok';
                        }
                    }
                    else if($request->get('agence'))
                    {
                        $id = (int)$request->get('agence');// recuperation de la parametres agence envoye par ajax
                        if($id == -1)
                        {
                            $session->remove('agence');
                            $res['id']= 'ok';
                        }
                        else if($id != 0)
                        {// condition verifiant le choix d'un eleve
                            $em = $this->getDoctrine()->getManager();
                            $repository = $em->getRepository(Agence::class);
                            $agence = $repository->find($id);//
                            if(!empty($agence))
                            {
                                $res['id'] = $agence->getId();
                                $res['nom'] = $agence->getNom();
                                $res['adresse'] = $agence->getAdresse();
                                $session->set('agence', $agence->getId());// memorisaation de l'eleve choisi dans la variable de session eleve
                            }
                            else
                            {

                                $res['id'] = '';
                            }
                        }
                        else
                        {// annulation du choix
                            $session->remove('agence');
                            $res['id']= 'ok';
                        }
                    }
                    else
                    {
                        if($request->get('exonore') == '1')
                        {
                            $session->set('exonorer', 'exonorer');
                            $res['id'] = 'ok';
                        }
                        else
                        {
                            $session->remove('exonorer');
                            $res['id'] = 'ok';
                        }
                    }

                    $response = new Response();
                    $response->headers->set('content-type','application/json');
                    $re = json_encode($res);
                    $response->setContent($re);
                    return $response;
                }
                $response = $this->render('Default/pelerinconfirm.html.twig', array('form' => $form->createView(), 'courtiers' => $courtiers, 'session' => $sessionpel->getDesignation(),'agences' => $groupement));
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
	
	public function pelerinRemark()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->remark($session->getId());
				$response = $this->render('Default/pelerinremark.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
	
	public function pelerinOther()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$pelerins = $em->getRepository(Pelerin::class)->other($this->getUser()->getAgence()->getId(), $session->getId());
				$response = $this->render('Default/pelerinothers.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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

	public function billing()
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF') || $this->getUser()->getCaisse() == true)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$versements = $em->getRepository(Versement::class)->findBy(array('agence' => $this->getUser()->getAgence()->getId(), 'session' => $session->getId(), 'encaisser' => null));
				$response = $this->render('Default/billing.html.twig', array('versements' => $versements, 'session' => $session->getDesignation()));
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
	
	public function encaisser(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') || $this->getUser()->getCaisse() == true)
		{
			$em = $this->getDoctrine()->getManager();
			$pelsession = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($pelsession))
			{
				if( $this->getUser()->getAgence()->getCaisse() == true)$versement = $em->getRepository(Versement::class)->Find($request->get('vsm'));
				else 
				{
					$versement = $em->getRepository(Versement::class)->Find($request->getSession()->get('vsm'));
					$request->getSession()->remove('vsm');
				}
				if( $versement->getEncaisser() == null)
				{
					$pelerin = $em->getRepository(Pelerin::class)->Find($versement->getPelerin()->getId());
					$agence = $em->getrepository(Agence::class)->findOneBy(array('id' => $this->getUser()->getAgence()->getId()));		
					$pelerin->setCompte($versement->getMontant());
					$versement->setEncaisser($this->getUser());
					$credit = new Credit();
					$credit->setAgence($agence);
					$credit->setVersement($versement);
					$credit->setSession($pelsession);
					$em->persist($pelerin);
					$em->persist($credit);
					$em->persist($versement);
					$em->flush();
					if($this->getUser()->getAgence()->getCaisse() == true)
					{
					//return $this->redirectToRoute('Mecque_Confirm', array('plr' => $pelerin->getId(), 'vsm' => $versement->getId()));
					$res['id']= $this->generateUrl('Mecque_Confirm', array('plr' => $pelerin->getId(), 'vsm' => $versement->getId()));
					
						$response = new Response();
						$response->headers->set('content-type','application/json');
						$re = json_encode($res);
						$response->setContent($re);
						return $response;
					}
					else return $this->redirectToRoute('Mecque_Confirm', array('plr' => $pelerin->getId(), 'vsm' => $versement->getId()));

				}
				else return $this->redirectToRoute('Mecque_Billing');
			}
			else return $this->redirectToRoute('Mecque_SessionStart');
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
    public function versementCancel(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$versement = $em->getRepository(Versement::class)->find($request->get('vsm'));
				if($this->getUser()->getId() == $versement->getFacturer()->getId() || $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
				{
					$em->remove($versement);
					$em->flush();
					$this->get('session')->getFlashBag()->add('annuleversement', 'Versement annulé');
					$res['ok']=$this->generateUrl('Mecque_Pelerin', array('pelerin' => $versement->getPelerin()->getId()));
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
				}
				else
				{
					$this->get('session')->getFlashBag()->add('annuleversement', 'Vous n\'avez pas le droit d\'annuler ce versement');
					$res['ok']=$this->generateUrl('Mecque_Pelerin', array('pelerin' => $versement->getPelerin()->getId()));
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

    public function versementDelete(Request $request)
        {
    		if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER') && $this->getUser()->getCaisse() == false)
    		{
    			$em = $this->getDoctrine()->getManager();
    			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
    			if(!empty($session))
    			{
    				$versement = $em->getRepository(Versement::class)->find($request->get('vsm'));
    				$credit = $em->getRepository(Credit::class)->findOneBy(['versement' =>$versement->getId()]);
    				if($this->getUser()->getId() == $versement->getFacturer()->getId() || $this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
    				{
    				    $em->remove($credit);
    					$em->remove($versement);
    					$em->flush();
    					$this->get('session')->getFlashBag()->add('annuleversement', 'Versement supprimé');
    					$res['ok']=$this->generateUrl('Mecque_Pelerin', array('pelerin' => $versement->getPelerin()->getId()));
    					$response = new Response();
    					$response->headers->set('content-type','application/json');
    					$re = json_encode($res);
    					$response->setContent($re);
    					return $response;
    				}
    				else
    				{
    					$this->get('session')->getFlashBag()->add('annuleversement', 'Vous n\'avez pas le droit d\'annuler ce versement');
    					$res['ok']=$this->generateUrl('Mecque_Pelerin', array('pelerin' => $versement->getPelerin()->getId()));
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
	
    public function pelerinWatingDelete(Request $request)
    {
		if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($session))
			{
				$em = $this->getDoctrine()->getManager();
				$pack = $em->getRepository(PelerinWating::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'id' => $request->get('pel')));
				$em = $this->getDoctrine()->getManager();
				$em->remove($pack);
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
	
    public function pelerinRestaurer(Request $request)
    {
		if($this->getUser() != null  && $this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
		{
			$em = $this->getDoctrine()->getManager();
			$sessionpel = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
			if(!empty($sessionpel))
			{
				$restore = $this->getDoctrine()->getManager()->getRepository(OldPelerin::class)->find($request->get('pel'));
				if($restore->getAgence()->getId() != $this->getUser()->getAgence()->getId())
				{
						$agence =$restore->getAgence()->getId();
					$pelerins = $this->getDoctrine()->getManager()->getRepository(Pelerin::class)->findBy(array( 'session' => $sessionpel->getId(), 'agence' => $agence));
					$agencegroup = $em->getrepository(Groupement::class)->findOneby(array('agence' =>$agence, 'session' => $sessionpel->getId(), 'chef' => $this->getUser()->getAgence()->getId()));
					$quota = $agencegroup->getQuota(); 
				}
				else 
				{
					$pelerins = $this->getDoctrine()->getManager()->getRepository(Pelerin::class)->findBy(array( 'session' => $sessionpel->getId(), 'agence' => $this->getUser()->getAgence()->getId()));
					$quota = $sessionpel->getCotat();
				}
				if(count($pelerins)< $quota)
				{
					
					$pelerin = new Pelerin();
					$pelerin->setPrenom($restore->getPrenom());
					$pelerin->setNom($restore->getNom());
					$pelerin->setAdresse($restore->getAdresse());
					$pelerin->setPhone($restore->getPhone());
					$pelerin->setSexe($restore->getSexe());
					$pelerin->setfamillyphone($restore->getFamillyphone());
					$pelerin->setBloodgroup($restore->getBloodgroup());
					$pelerin->setPack($em->getRepository(Pack::class)->find($restore->getPack()->getId()));
					$pelerin->setAgence($em->getRepository(Agence::class)->find($restore->getAgence()->getId()));
					$pelerin->setSession($sessionpel);
					//if($restore->getImage() != null)$pelerin->setImage($em->getRepository(Image::class)->find($restore->getImage()->getId()));
					$pelerin->setDiabete($restore->getDiabete());
					$pelerin->setHypo($restore->gethypo());
					$pelerin->setHyper($restore->gethyper());
					$pelerin->setHandicap($restore->getHandicap());
					$pelerin->setNonvoyant($restore->getNonvoyant());
					$pelerin->setRemark($restore->getRemark());
					// traitement num dossier
					$pel = $this->getDoctrine()->getManager()->getRepository(Pelerin::class)->findOneBy(array( 'numdossier' => $restore->getNumdossier(), 'session' => $sessionpel->getId()));
					if(empty($pel))
					{
						$pelerin->setNumdossier($restore->getNumdossier());
					}
					else
					{
						$pels = $this->getDoctrine()->getManager()->getRepository(Pelerin::class)->findBy(array( 'session' => $sessionpel->getId()));
						$numero = count($pels);//recuperation du nombre de chambre
						$tamp = 1;
						while( $numero != 0 && $tamp != 0){//verification si le numero de chambre nombre+1 existe 
							$numero = $numero +1;
							$tamp = count($em->getRepository(Pelerin::class)->findBy(array( 'numdossier' =>$numero, 'session' => $sessionpel->getId())));
						}
						$pelerin->setNumdossier($numero);
					}
					//fin traitement
					
					$pelerin->setPhoto($restore->getPhoto());
					$pelerin->setVisite($restore->getVisite());
					
					$em->persist($pelerin);
					$em->remove($restore);
					$em->flush();
					$res['ok']= $this->generateUrl('Mecque_Pelerin', array('pelerin' => $pelerin->getId()));
					$response = new Response();
					$response->headers->set('content-type','application/json');
					$re = json_encode($res);
					$response->setContent($re);
					return $response;
						
				}
				else 
				{
					$this->get('session')->getFlashBag()->add('notice', 'Quota atteint');
					return $this->redirectToRoute('Mecque_homepage');
				}
				$response = $this->render('Default/pelerinrestaurer.html.twig', array('form' => $form->createView(),'pelerin' => $pelerin, 'session' => $sessionpel->getDesignation(), 'courtiers' => $courtiers));
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
	
	public function exonorer(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$pelerin = $em->getRepository(Pelerin::class)->find($request->get('exonore'));
		if ($request->get('annul'))
		{
			if(!empty($pelerin))
			{
				$pelerin->setExonorer(false);
				$montant = $pelerin->getPack()->getMontant();
				if(!empty($pelerin->getReduction())) $montant = $montant - $pelerin->getReduction()->getMontant(); 
				$situation = $pelerin->getCompte() - $montant;
				$em->persist($pelerin);
				$em->flush();
				if($situation == 0 )$res['ok']= 'ok'; else $res['ok']= ' ';
				$res['compte']= number_format($situation, 0, '', ' ');
			}
			else
				$res['ok']= '';
		}
		else
		{
			if(!empty($pelerin))
			{
				$pelerin->setExonorer(true);
				$em->persist($pelerin);
				$em->flush();
				$res['ok']= 'ok';
			}
			else
				$res['ok']= '';
		}
				
		$response = new Response();
		$response->headers->set('content-type','application/json');
		$re = json_encode($res);
		$response->setContent($re);
		return $response;
    }
	
	public function reductionDelete(Request $request)
    {
		$em = $this->getDoctrine()->getManager();
		$pelerin = $em->getRepository(Pelerin::class)->find((int)$request->get('plr'));
		
			if(!empty($pelerin))
			{
				$reduction = $em->getRepository(Reduction::class)->find($pelerin->getReduction()->getId());
				$pelerin->setReduction(null);
				$montant = $pelerin->getPack()->getMontant();
				$situation = $pelerin->getCompte() - $montant;
				$em->remove($reduction);
				$em->persist($pelerin);
				$em->flush();
				$res['compte']= number_format($situation, 0, '', ' ');
				$res['ok']= 'ok';
			}
			else
				$res['ok']= '';	
		$response = new Response();
		$response->headers->set('content-type','application/json');
		$re = json_encode($res);
		$response->setContent($re);
		return $response;
    }
    
    /**
     * @Route("/PelerinExonerer", name="Mecque_Pelerin_Exonerer")
     */
    public function pelerinExonererlist()
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_CHEF'))
        {
            $em = $this->getDoctrine()->getManager();
            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
            if(!empty($session))
            {
                $pelerins = $em->getRepository(Pelerin::class)->findBy(array('exonorer' => true, 'session' => $session->getId()));
                $response = $this->render('Default/pelerinexonerer.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation(), 'exoneres' => 'ok'));
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
     * @Route("/PelerinExonererPrint", name="Mecque_Pelerin_Exonerer_Print")
     */
    public function pelerinExonererPrint()
    {
        if($this->get('security.authorization_checker')->isGranted('ROLE_EMPLOYER'))
        {
            $em = $this->getDoctrine()->getManager();
            $session = $em->getrepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence()->getId(), 'current' => true));
            if(!empty($session))
            {
                $pelerins = $em->getRepository(Pelerin::class)->findBy(array('exonorer' => true, 'session' => $session->getId()));
                $response = $this->render('Default/pelerinexonererprint.html.twig', array('pelerins' => $pelerins, 'session' => $session->getDesignation()));
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
