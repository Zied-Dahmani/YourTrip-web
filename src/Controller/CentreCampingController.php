<?php

namespace App\Controller;

use App\Entity\CentreCamping;
use App\Form\CentreCampingType;
use App\Repository\CentreCampingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Knp\Component\Pager\PaginatorInterface;
use \Twilio\Rest\Client;

/**
 * @Route("/centre/camping")
 */
class CentreCampingController extends AbstractController
{
    private $twilio;

    public function __construct(Client $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * @Route("/stats", name="centre_camping_stats")
     */
    public function statistics(CentreCampingRepository $centreCampingRepository, EntityManagerInterface $entityManager): response
    {
        $centres = $centreCampingRepository->findAll();

        $centreName = [];
        $centreCount = [];
        // On "démonte" les données pour les séparer tel qu'attendu par ChartJS
        foreach($centres as $centre){
            $centreName[] = $centre->getNom();
            $centreCount[]= count($centre->getTentes());
        }


        return $this->render('centre_camping/stats.html.twig', [
            'centreName' => json_encode($centreName),
            'centreCount' => json_encode($centreCount)

        ]);

    }


    /**
     * @Route("/", name="app_centre_camping_index", methods={"GET"})
     */
    public function index(CentreCampingRepository $centreCampingRepository,PaginatorInterface $paginator,Request $request): Response
    {
        $centres = $centreCampingRepository->findAll();

        $centrepagination = $paginator->paginate(
            $centres, // on passe les donnees
            $request->query->getInt('page', 1),// Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            5
        );
        return $this->render('centre_camping/index.html.twig', [
            'centre_campings' => $centrepagination,
        ]);
    }

    /**
     * @Route("/home", name="app_centre_camping_index2", methods={"GET"})
     */
    public function index2(CentreCampingRepository $centreCampingRepository): Response
    {
        return $this->render('centre_camping/front_index.html.twig', [
            'centre_campings' => $centreCampingRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_centre_camping_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CentreCampingRepository $centreCampingRepository, FlashyNotifier $flashy): Response
    {
        $centreCamping = new CentreCamping();
        $form = $this->createForm(CentreCampingType::class, $centreCamping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $centreCampingRepository->add($centreCamping);
            $message = $this->twilio->messages->create(
                '+21621333148', // Send text to this number
                array(
                    'from' => '+16073035785', // My Twilio phone number
                    'body' => 'Centre De Camping Ajouté: '.$centreCamping->getNom().' '.$centreCamping->getAdresse()
                ));


            $flashy->success('Centre De Camping Ajouté', 'http://your-awesome-link.com/');
            return $this->redirectToRoute('app_centre_camping_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('centre_camping/new.html.twig', [
            'centre_camping' => $centreCamping,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_centre_camping_show", methods={"GET"})
     */
    public function show(CentreCamping $centreCamping): Response
    {
        return $this->render('centre_camping/show.html.twig', [
            'centre_camping' => $centreCamping,
        ]);
    }

    /**
     * @Route("/show/{id}", name="app_centre_camping_show2", methods={"GET"})
     */
    public function show2(CentreCamping $centreCamping): Response
    {
        return $this->render('centre_camping/front_show.html.twig', [
            'centre_camping' => $centreCamping,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="app_centre_camping_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CentreCamping $centreCamping, CentreCampingRepository $centreCampingRepository): Response
    {
        $form = $this->createForm(CentreCampingType::class, $centreCamping);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $centreCampingRepository->add($centreCamping);
            return $this->redirectToRoute('app_centre_camping_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('centre_camping/edit.html.twig', [
            'centre_camping' => $centreCamping,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_centre_camping_delete", methods={"POST"})
     */
    public function delete(Request $request, CentreCamping $centreCamping, CentreCampingRepository $centreCampingRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$centreCamping->getId(), $request->request->get('_token'))) {
            $centreCampingRepository->remove($centreCamping);
        }

//        $centreCamping = $this->getDoctrine()->getRepository(CentreCamping::class)->find($id);
//        $em=$this->getDoctrine()->getManager();
//        $em->remove($centreCamping);
//        $em->flush();
        return $this->redirectToRoute('app_centre_camping_index');
    }

}
