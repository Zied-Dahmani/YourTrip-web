<?php

namespace App\Controller;

use App\Entity\Tente;
use App\Form\TenteType;
use App\Repository\TenteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/tente")
 */
class TenteController extends AbstractController
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    /**
     * @Route("/pdf", name="tente_pdf", methods={"GET"})
     */
    public function pdf(EntityManagerInterface $entityManager): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        $tentes = $entityManager
            ->getRepository(Tente::class)
            ->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('tente/pdf.html.twig', [
            'tentes' => $tentes,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("Tente.pdf", [
            "Attachment" => true
        ]);

        return $this->redirectToRoute('app_tente_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/", name="app_tente_index", methods={"GET"})
     */
    public function index(TenteRepository $tenteRepository): Response
    {
        return $this->render('tente/index.html.twig', [
            'tentes' => $tenteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/index/{id}", name="app_tente_index2", methods={"GET"})
     */
    public function index2(TenteRepository $tenteRepository,$id): Response
    {
        return $this->render('tente/front_index.html.twig', [
            'tentes' => $tenteRepository->findBy(['centreCamping' => $id]),
        ]);
    }

    /**
     * @Route("/new", name="app_tente_new", methods={"GET", "POST"})
     */
    public function new(Request $request, TenteRepository $tenteRepository): Response
    {
        $tente = new Tente();
        $form = $this->createForm(TenteType::class, $tente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tenteRepository->add($tente);
            return $this->redirectToRoute('app_tente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tente/new.html.twig', [
            'tente' => $tente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_tente_show", methods={"GET"})
     */
    public function show(Tente $tente): Response
    {
        return $this->render('tente/show.html.twig', [
            'tente' => $tente,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tente_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tente $tente, TenteRepository $tenteRepository): Response
    {
        $form = $this->createForm(TenteType::class, $tente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tenteRepository->add($tente);
            return $this->redirectToRoute('app_tente_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tente/edit.html.twig', ['tente' => $tente, 'form' => $form->createView(),]);
    }

    /**
     * @Route("/{id}", name="app_tente_delete", methods={"POST"})
     */
    public function delete(Request $request, Tente $tente, TenteRepository $tenteRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tente->getId(), $request->request->get('_token'))) {
            $tenteRepository->remove($tente);
        }

        return $this->redirectToRoute('app_tente_index', [], Response::HTTP_SEE_OTHER);
    }




    /**
     * @return Response
     * @Route("/email/{id}/{to}", name="sendEmail")
     */
    public function sendmail(Request $request,TenteRepository $rep,$id,$to)
    {
        //$form=$this->createForm(\App\Form\EmailType::class);
        //$form->handleRequest($request);
        //if($form->isSubmitted() && $form->isValid())
        //{
            //$var = $form->get('message')->getData();
            $var = "Réservation";

            $email = (new TemplatedEmail())
                ->from('ghub2441@gmail.com')
                ->to($to)
                ->subject('Réservation')

                // path of the Twig template to render
                ->html('<p>'.$var.'</p>');

            ;

            $this->mailer->send($email);
            return $this->redirectToRoute('app_tente_index2',['id'=>$id]);


        //}
        //return $this->render('/tente/email.html.twig',['formail'=> $form->createView(),]);
    }


}
