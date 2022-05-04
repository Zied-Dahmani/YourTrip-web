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

class TenteApiController extends AbstractController
{
    /**
     * @Route("/tentes", name="api_tentes")
     */
    public function allTentes(NormalizerInterface $normalizer): Response
    {
        $tentesList = $this->getDoctrine()->getRepository(Tente::class)->findAll();
        $jsonContent = $normalizer->normalize($tentesList, 'json', ['groups' => 'api:tente']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/tente/add", name="api_add_tente")
     */
    public function add(NormalizerInterface $normalizer,Request $request): Response
    {

        $tente = new Tente();

        $tente->setNom($request->request->get('nom'));
        $tente->setPrix($request->request->get('prix'));
        $tente->setDescription($request->request->get('description'));
        $tente->setCentreCamping($this->getDoctrine()->getRepository(CentreCamping::class)->find(intval($request->request->get('centre_camping_id'))));

//        $file=new File($request->request->get('image'));
//        $fileName = md5(uniqid()) . '.jpg';
//        $tente->setImage($fileName);
//        $file->move($this->getParameter('tente_image_directory'), $fileName);

        $em=$this->getDoctrine()->getManager();
        $em->persist($tente);
        $em->flush();
        $jsonContent = $normalizer->normalize($tente, 'json', ['groups' => 'api:tente']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/tente/update", name="api_update_tente")
     */
    public function update(NormalizerInterface $normalizer,Request $request): Response
    {

        $tente = $this->getDoctrine()->getRepository(Tente::class)->find(intval($request->request->get('id')));
        $tente->setNom($request->request->get('nom'));
        $tente->setDescription($request->request->get('description'));
        $tente->setPrix(intval($request->request->get('prix')));

        $em=$this->getDoctrine()->getManager();
        $em->persist($tente);
        $em->flush();
        $jsonContent = $normalizer->normalize($tente, 'json', ['groups' => 'api:tente']);
        return new Response(
            json_encode($jsonContent),
            200,
            ['Accept' => 'application/json',
                'Content-Type' => 'application/json']);
    }

    /**
     * @Route("/tente/delete", name="api_tente_delete")
     */
    public function delete(Request $request, NormalizerInterface $normalizer): Response
    {

        $tente = $this->getDoctrine()->getRepository(Tente::class)->find(intval($request->request->get('id')));

        $em = $this->getDoctrine()->getManager();
        $em->remove($tente);
        $em->flush();
        return new Response(
            "{\"response\": \"Tente deleted.\"}",
            200, ['Accept' => 'application/json',
            'Content-Type' => 'application/json']);
    }
}
