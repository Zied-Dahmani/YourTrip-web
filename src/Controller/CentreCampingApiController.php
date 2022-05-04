<?php

namespace App\Controller;

use App\Entity\CentreCamping;
use App\Entity\Tente;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @Route("/api")
 */

class CentreCampingApiController extends AbstractController
{
    /**
     * @Route("/index", name="app_centre_camping_api")
     */
    public function index(): Response
    {
        return $this->render('centre_camping_api/index.html.twig', [
            'controller_name' => 'CentreCampingApiController',
        ]);
    }



    /**
     * @Route("/centres", name="api_centre")
     */
    public function allCentresCamping(NormalizerInterface $normalizer): Response
    {
        $centres = $this->getDoctrine()->getRepository(CentreCamping::class)->findAll();
        $jsonContent = $normalizer->normalize($centres, 'json', ['groups' => 'api:centre']);

        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }




    /**
     * @Route("/centre/add", name="api_centre_add", methods={"POST"})
     */
    public function add(Request $request, NormalizerInterface $normalizer): Response
    {

        $em = $this->getDoctrine()->getManager();
        $centre = new CentreCamping();
        //$centre->setUser($this->getDoctrine()->getRepository(User::class)->findOneBy(["username"=>$request->request->get('username')]));
        $centre->setNom($request->request->get('nom'));
        $centre->setAdresse($request->request->get('adresse'));
        $centre->setEmail($request->request->get('email'));
        $centre->setDescription($request->request->get('description'));
        $em->persist($centre);
        $em->flush();
        return new Response(
            "{\"response\": \"Centre De Camping created.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/centre/update", name="api_centre_update", methods={"POST"})
     */
    public function update(Request $request, NormalizerInterface $normalizer): Response
    {
        $em = $this->getDoctrine()->getManager();
        $centre = $this->getDoctrine()->getRepository(CentreCamping::class)->find(intval($request->request->get('id')));

        $centre->setNom($request->request->get('nom'));
        $centre->setAdresse($request->request->get('adresse'));
        $centre->setEmail($request->request->get('email'));
        $centre->setDescription($request->request->get('description'));

        $em->persist($centre);
        $em->flush();
        return new Response(
            "{\"response\": \"Centre updated.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/centre/delete", name="api_centre_delete" )
     */

    public function delete(Request $request, NormalizerInterface $normalizer): Response
    {
//
//        if (!$request->query->get('username'))
//            return new Response(
//                '{"error": "Missing username."}',
//                400, ['Accept' => 'application/json',
//                'Content-Type' => 'application/json']);
        $centre = $this->getDoctrine()->getRepository(CentreCamping::class)->find(intval($request->request->get('id')));


        $em = $this->getDoctrine()->getManager();
        $em->remove($centre);
        $em->flush();
        return new Response(
            "{\"response\": \" Centre deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }
}
