<?php

namespace Admin\NewsBundle\Controller;

use Admin\NewsBundle\Entity\News;
use Admin\NewsBundle\Form\VacancyType;
use Admin\NewsBundle\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\HttpFoundation\Request;

class VacancyController extends Controller
{

    const vacanciesClass         = 'Admin\NewsBundle\Entity\News';
    const vacanciesParentDir     = '/../../front-end/app/content/';
    const vacanciesDirName       = 'vacancies';
    const vacanciesDir           = '/../../front-end/app/content/vacancies';

    public function indexAction()
    {
        return $this->render('AdminNewsBundle:Vacancy:index.html.twig');
    }

    public function createVacancyAction(Request $request)
    {
        $user       = $this->get('security.context')->getToken()->getUser();
        $form       = $this->createForm(new VacancyType());
        $em         = $this->getDoctrine()->getManager();
        $session    = $this->get('session');
        /** @var Translator $translator */
        $translator = $this->get('translator');

        if ($request->getMethod() === 'POST') {

            $form->handleRequest($request);
            $data = $form->getData();

            if ($form->isValid()) {

                try {

                    $vacancy = new News();
                    $catName = 'Вакансии';

                    $vacancy->setTitle($data->getTitle());
                    $vacancy->setCreator($user);
                    $vacancy->setCreatedAt(new \DateTime());
                    $vacancy->setBody($data->getBody());
                    $vacancy->setIsPublished($data->getIsPublished());
                    $vacancy->setNewsCategories($em->getRepository('Admin\NewsBundle\Entity\NewsCategories')->findOneBy(array('name' => $catName)));
                    $vacancy->setLocale($data->getLocale());

                    $em->persist($vacancy);
                    $em->flush();

                    $this->generateJSON($vacancy);
//                    $this->generateVacancyListJSON();

                } catch(DBALException $e) {

                    $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_CREATING'));
                    return $this->redirect($this->generateUrl('admin_vacancies_create'));

                }

                $session->getFlashBag()->set('success', $translator->trans('ANB_VACANCY_ADDED'));
                return $this->redirect($this->generateUrl('admin_vacancies_index'));

            } else {

                $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
                return $this->redirect($this->generateUrl('admin_vacancies_create'));

            }

        }

        return $this->render('AdminNewsBundle:Vacancy:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listVacanciesAction()
    {
        $em = $this->getDoctrine()->getManager();
        /** @var NewsRepository $repo */
        $repo = $em->getRepository(self::vacanciesClass);

        $vacanciesList = $repo->getVacancies($this->get('session')->get('_locale'));

        return $this->render('AdminNewsBundle:Vacancy:list.html.twig', array(
            'vacanciesList' => $vacanciesList,
        ));
    }

    public function editVacancyAction(Request $request, $vacancyID)
    {
        $em         = $this->getDoctrine()->getManager();
        $repo       = $em->getRepository(self::vacanciesClass);
        $vacancy    = $repo->find($vacancyID);
        $form       = $this->createForm(new VacancyType(), $vacancy);

        return $this->render('AdminNewsBundle:Vacancy:edit.html.twig', array(
            'form' => $form->createView(),
            'vacancyID' => $vacancy->getId(),
        ));
    }

    public function saveVacancyAction(Request $request, $vacancyID)
    {
        $user = $this->get('security.context')->getToken()->getUser();
        $request    = $this->getRequest();
        $form       = $this->createForm(new VacancyType());
        $em         = $this->getDoctrine()->getManager();
        $session    = $this->get('session');
        $translator = $this->get('translator');

        $form->handleRequest($request);

        if ($form->isValid()) {

            try {

                $data = $form->getData();

                $vacancy = $em->getRepository(self::vacanciesClass)->find($vacancyID);

                $vacancy->setBody($data->getBody());
                $vacancy->setTitle($data->getTitle());
                $vacancy->setIsPublished($data->getIsPublished());
//                $vacancy->setNewsCategories($em->getRepository('Admin\NewsBundle\Entity\NewsCategories')->find($data->getNewsCategories()->getId()));
                $vacancy->setLocale($data->getLocale());
                $vacancy->setModifier($user);
                $vacancy->setUpdatedAt(new \DateTime());

                $em->persist($vacancy);
                $em->flush();

                $this->generateJSON($vacancy);
//                if (
//                    $data->getIsPublished() !== $oldPublishFlag
//                    || $data->getTitle() !== $oldTitle ) {
//                    $this->generatePaginationJSON();
//                }
//                $this->generateLastNewsJson();

            } catch(DBALException $e) {

                $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_EDITING'));
                return $this->redirect($this->generateUrl('admin_vacancies_edit'));

            }

            $session->getFlashBag()->set('success', $translator->trans('ANB_VACANCY_SAVED'));
            return $this->redirect($this->generateUrl('admin_vacancies_list'));

        } else {

            $session->getFlashBag()->set('error', $translator->trans('ANB_FORM_NOT_VALID'));
            return $this->redirect($this->generateUrl('admin_vacancies_edit'));

        }
    }

    public function viewVacancyAction()
    {

    }

    public function removeVacancyAction(Request $request, $vacancyID)
    {
        $em = $this->getDoctrine()->getManager();
        $newsRepo = $em->getRepository(self::vacanciesClass);
        $session    = $this->get('session');
        $translator = $this->get('translator');
        $vacancy = $newsRepo->find($vacancyID);

        try {

            $id = $vacancy->getId();

            $em->remove($vacancy);
            $em->flush();

            $this->removeVacancyJson($id.".json");

        } catch (DBALException $e) {

            $session->getFlashBag()->set('error', $translator->trans('ANB_ERROR_WHILE_DELETING'));
            return $this->redirect($this->generateUrl('admin_vacancies_list'));

        }

        $session->getFlashBag()->set('success', $translator->trans('ANB_VACANCY_DELETED'));
        return $this->redirect($this->generateUrl('admin_vacancies_list'));
    }

    /**
     * Generates json file for a single news
     * needs try/catch block
     *
     * @param \Admin\NewsBundle\Entity\News $news
     */
    private function generateJSON(\Admin\NewsBundle\Entity\News $news, $caseModifier = 'id')
    {

        $newsArray = $news->__toArray();

        if (!file_exists(getcwd().self::vacanciesDir)) {
            if (!$this->generateVacanciesDirStructure()) {
                var_dump(false);exit;
            }
        }

        switch ($caseModifier) {
            case 'id':
                file_put_contents(getcwd().self::vacanciesDir."/".$news->getLocale()->__toLocaleString()."/".$news->getId().".json", json_encode($newsArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
                break;
            case 'title':
                file_put_contents(getcwd().self::vacanciesDir."/".$news->getLocale()->__toLocaleString()."/".$news->getTitle().".json", json_encode($newsArray, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
                break;
        }
    }

    /**
     * Generates directory structure under AngularJS's content folder for news.
     * needs try/catch block
     *
     * @return boolean
     */
    private function generateVacanciesDirStructure()
    {
        $flag = false;

        while(!$flag) {

            if (!file_exists(getcwd().self::vacanciesDir)) {

                mkdir(getcwd().self::vacanciesDir."/en", 0777, true);
                mkdir(getcwd().self::vacanciesDir."/ru", 0777, true);

            }

            $flag = true;

        }

        return true;
    }

    /**
     * Removes json file for deleted news
     *
     * @param type $fileName
     * @return boolean
     */
    private function removeVacancyJson($fileName)
    {
        $locale = $this->get('session')->get('_locale');
        $dir = getcwd().self::vacanciesDir."/".$locale;

        if (is_file($dir.DIRECTORY_SEPARATOR.$fileName))
            unlink($dir.DIRECTORY_SEPARATOR.$fileName);

        return true;
    }

}
