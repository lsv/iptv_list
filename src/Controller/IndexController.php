<?php

namespace App\Controller;

use App\Entity\Channel;
use App\Entity\Link;
use App\Form\InputType;
use App\FormModel\InputForm;
use App\Parser\InjectParsedLinks;
use App\Repository\ChannelRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class IndexController extends Controller
{

    /**
     * @Route("/", name="index_index")
     *
     * @param ChannelRepository $repository
     * @param Request $request
     *
     * @return Response
     */
    public function index(ChannelRepository $repository, Request $request): Response
    {
        $countries = $repository->findCountries();

        $channels = $repository->findChannelsByCountry($request->query->get('country', 'DK'));

        return $this->render('index/index.html.twig', [
            'countries' => $countries,
            'channels' => $channels,
        ]);
    }

    /**
     * @Route("/upload", name="index_upload")
     *
     * @param InjectParsedLinks $injector
     * @param Request $request
     *
     * @return Response
     */
    public function upload(InjectParsedLinks $injector, Request $request): Response
    {
        $data = new InputForm();
        $form = $this->createForm(InputType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($data->getUploadedFile()) {
                $dir = $this->getParameter('kernel.project_dir') . '/var/tmp';
                $filename = md5(uniqid('', true));
                $data->getUploadedFile()->move(
                    $dir,
                    $filename
                );

                $data->setData(file_get_contents($dir . '/' . $filename));
                unlink($dir . '/' . $filename);
            }

            if ($data->getInputData()) {
                $data->setData($data->getInputData());
            }

            if (($parser = $data->getParserObject()) && !$parser->isValidData($data->getData())) {
                $form->addError(new FormError('Data is not valid for this parser'));
            }

            if ($form->isValid()) {
                $injector->inject($parser->parseData($data->getData()));
                $this->addFlash('success', 'Data is parsed');
                return $this->redirectToRoute('index_index');
            }
        }

        return $this->render('index/upload.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
