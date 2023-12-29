<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use App\Entity\CourtierPelerin;
use App\Entity\Courtier;
use App\Entity\Pelerin;
use App\Entity\OldPelerin;
use App\Entity\Reduction;
use App\Entity\Depense;
use App\Entity\Session;
use App\Entity\Compte;
use App\Entity\Debit;
use App\Entity\Credit;
use App\Entity\Gain;
use App\Entity\Pack;
use App\Entity\Organ;
use App\Entity\Medecin;
use App\Entity\Imam;

class ArchiveController extends AbstractController
{
   
	public function pelerinList(Request $request,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pelerins = $em->getRepository(pelerin::class)->findBy(array('session' => $session->getId()));
			return $this->render('Archive/pelerinlist.html.twig', array('pelerins' => $pelerins, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function pelerinOldList(Request $request, $saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pelerins = $em->getRepository(OldPelerin::class)->findBy(array('session' => $session->getId()));
			return $this->render('Archive/pelerinoldlist.html.twig', array('pelerins' => $pelerins, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function session(Request $request,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			$pelerins = $em->getRepository(pelerin::class)->findBy(array('session' => $session->getId()));
			$enregle = array();
			$dossier = 0;
			foreach($pelerins as $pelerin)
			{
				$montant = $pelerin->getPack()->getMontant();
				if($pelerin->getReduction()) $montant = $montant - $pelerin->getReduction()->getMontant();
				if($pelerin->getCompte() == $montant)
				{
					$enregle []= $pelerin;
				}
				if(!$pelerin->Dossier())
				{
					$dossier = $dossier+1;
				}
			}
			return $this->render('Archive/session.html.twig',array('nombre' => count($pelerins), 'enregle' => count($enregle), 'pasenregle' => count($pelerins) - count($enregle), 'dossier' => $dossier, 'session' => $session->getDesignation() ));
		
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function pack(Request $request,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$packs = $em->getRepository(Pack::class)->findBy(array('session' => $session->getId()));
			return $this->render('Archive/pack.html.twig', array('packs' => $packs, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function spend(Request $request,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$spends = $em->getRepository('MecqueBundle:Depense')->findBy(array('session' => $session->getId()));
			return $this->render('Archive/spend.html.twig', array('result' => $spends, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function gain(Request $request,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$gains = $em->getRepository(Gain::class)->findBy(array('session' => $session->getId()));
			return $this->render('Archive/gain.html.twig', array('result' => $gains, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	 public function pelerin(Request $request,$pelerin,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$repository = $em->getRepository(pelerin::class);
			$pelerin = $repository->findOneBy(array('id' =>$pelerin, 'session' => $session->getId()));
			if(empty($pelerin))throw $this->createNotFoundException();
			$versements = $em->getRepository(Versement::class)->findBy(array('pelerin' => $pelerin->getId(), 'session' => $session->getId()), array('date' => 'desc'));
			return $this->render('Archive/pelerin.html.twig', array('pelerin' => $pelerin, 'versements' => $versements, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function oldPelerin(Request $request,$pelerin,$saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$repository = $em->getRepository(OldPelerin::class);
			$pel = $repository->findOneBy(array('id' => $pelerin, 'session' => $session->getId()));
			if(empty($pel))throw $this->createNotFoundException();
			return $this->render('Archive/oldpelerin.html.twig', array( 'pelerin' => $pel, 'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
		
    }
	
	public function pelerinListMale(Request $request, $saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pelerins = $em->getRepository(pelerin::class)->findBy(array('session' => $session->getId(), 'sexe' => 'Masculin'));
			return $this->render('Archive/pelerinlistmale.html.twig', array('pelerins' => $pelerins,'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));	
    }
	
	public function pelerinListFemale(Request $request, $saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pelerins = $em->getRepository(pelerin::class)->findBy(array('session' => $session->getId(), 'sexe' => 'Feminin'));
			return $this->render('Archive/pelerinlistfemale.html.twig', array('pelerins' => $pelerins,'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function pelerinListFemaleless45(Request $request, $saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pelerins = $em->getRepository(pelerin::class)->ArchiveLess($session->getId());
			return $this->render('Archive/pelerinlistfemaleless45.html.twig', array('pelerins' => $pelerins,'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function reductionList(Request $request, $saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pels = $em->getRepository(pelerin::class)->findBy(array('session' => $session->getId()));
			$pelerins= array();
			foreach($pels as $pel)
			{
					if($pel->getReduction())
					$pelerins[]= $pel;
			}
			return $this->render('Archive/reductionlist.html.twig', array('pelerins' => $pelerins,'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function pelerinCourtierList(Request $request, $saison)
    {
		if($this->getUser() != null)
		{
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$pelerins = $em->getRepository(PelerinCourtier::class)->findBy(array('session' => $session->getId()));
			return $this->render('Archive/pelerincourtierlist.html.twig', array('pelerins' => $pelerins,'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function solde(Request $request, $saison)
	{
		if($this->getUser() != null)
		{
		
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			if(empty($session))throw $this->createNotFoundException();
			$credits = $em->getRepository(Credit::class)->findBy(array('session' => $session->getId()), array('date' => 'desc'));
			$debits = $em->getRepository(Debit::class)->findBy(array('session' => $session->getId()), array('date' => 'desc'));
			$solde = $em->getRepository(Compte::class)->findBy(array('session' => $session->getId()));
			
			return $this->render('Archive/solde.html.twig', array('credits' => $credits, 'solde' => $solde, 'debits' => $debits,'session' => $saison));
		}
		else return $this->redirect($this->generateUrl('security_login'));
	}
	
	public function organisateurList($saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
				$organisateurs = $em->getrepository(Organ::class)->findBy(array('session' => $session->getId()));
				return $this->render('Archive/organisateurlist.html.twig',array('organisateurs' => $organisateurs, 'session' => $session->getDesignation()));
			
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	 public function organisateur($organisateur,$saison)
    {
		if($this->getUser() != null)
		{
			if($organisateur < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			$repository = $em->getRepository(Organ::class);
			$pel = $repository->findOneBy(array('id' =>$organisateur, 'session' => $session->getId()));
			return $this->render('Archive/organisateur.html.twig', array( 'organisateur' => $pel, 'session' => $session->getDesignation()));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	public function medecinList($saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			$medecins = $em->getrepository(Medecin::class)->findBy(array('session' => $session->getId()));
			return $this->render('Archive/medecinlist.html.twig',array('medecins' => $medecins,'session' => $session->getDesignation()));
			
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
	 public function medecin($medecin,$saison)
    {
		if($this->getUser() != null)
		{
			if($medecin < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			$repository = $em->getRepository(Medecin::class);
			$pel = $repository->findOneBy(array('id' =>$medecin, 'session' => $session->getId()));
			return $this->render('Archive/medecin.html.twig', array( 'medecin' => $pel, 'session' => $session->getDesignation()));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	public function imamList($saison)
    {
		if($this->getUser() != null)
		{
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
				$imams = $em->getrepository(Imam::class)->findBy(array('session' => $session->getId()));
				return $this->render('Archive/imamlist.html.twig',array('imams' => $imams, 'session' => $session->getDesignation()));
			
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	 public function imam($imam,$saison)
    {
		if($this->getUser() != null)
		{
			if($imam < 0)
			{
				throw $this->createNotFoundHttpException();
			}
			$em = $this->getDoctrine()->getManager();
			$session = $em->getRepository(Session::class)->findOneBy(array('agence' => $this->getUser()->getAgence(),'designation' => $saison));
			$repository = $em->getRepository(Imam::class);
			$pel = $repository->findOneBy(array('id' =>$imam, 'session' => $session->getId()));
			return $this->render('Archive/imam.html.twig', array( 'imam' => $pel, 'session' => $session->getDesignation()));
		}
		else return $this->redirect($this->generateUrl('security_login'));
    }
	
}
