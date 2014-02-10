<?php

namespace Admin\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#exceptions
use Doctrine\DBAL\DBALException;

#entities
use Admin\GalleryBundle\Entity\Gallery;
use Admin\GalleryBundle\Entity\GalleryPics;
use Admin\GalleryBundle\Entity\GalleryVids;

#forms
use Admin\GalleryBundle\Form\GalleryType;
use Admin\GalleryBundle\Form\GalleryEditType;

#helpers
use Helpers\ServiceBridge;

class GalleryController extends Controller
{
    
    const _mainEntityName   = 'Admin\GalleryBundle\Entity\Gallery';
    const _picsEntityName   = 'Admin\GalleryBundle\Entity\GalleryPics';
    const _vidsEntityName   = 'Admin\GalleryBundle\Entity\GalleryVids';
    const _galleryDir       = "/../../front-end/app/content/gallery/";
    
    public function createGalleryAction(Request $request)
    {
        $form = $this->createForm(new GalleryType());
        $session        = $this->get('session');
        $translator     = $this->get('translator');
        
        if ($request->getMethod() === "POST") {
            $galleryID = $this->saveGallery($request);
            
            $session->getFlashBag()->set('success', $translator->trans('AGB_GALLERY_CREATED')."!");
            return $this->redirect($this->generateUrl('admin_gallery_uploadFiles', array('galleryID' => $galleryID)));
        }
        
        return $this->render('AdminGalleryBundle:Gallery:create.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    private function saveGallery(Request $request, $type = 'new')
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $this->get('security.context')->getToken()->getUser();
        $session        = $this->get('session');
        $translator     = $this->get('translator');
        
        $form = $this->createForm(new GalleryType());
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $data = $form->getData();
            
            try {
                
                $gallery = new Gallery();
                
                if ($type === 'new') {
                    
                    $gallery->setCreator($user);
                    $gallery->setIsPublished(false);
                    $gallery->setMainPic(null);
                    
                } else {
                    $gallery->setIsPublished($data->getIsPublished());
                }
                
                $gallery->setGalleryType($data->getGalleryType());
                $gallery->setLocale($data->getLocale());
                $gallery->setName($data->getName());
                
                $em->persist($gallery);
                $em->flush();
                
                $this->setGalleryLocale($gallery->getLocale()->__toLocaleString());
                $this->createGalleryFolders($gallery->getLocale()->__toLocaleString(),$gallery->getID());
                
                return $gallery->getID();
                
            } catch (DBALException $e) {
                
                $session->getFlashBag()->set('error', $translator->trans('AGB_ERROR_WHILE_CREATING')."! ".$e->getMessage());
                return $this->redirect($this->generateUrl('admin_gallery_create'));
                
            }
            
        }
    }
    
    /**
     * Deletes whole gallery object and objecs for its pics, also deletes pic files and folder
     * 
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param type $galleryID
     * @return type
     */
    public function deleteGalleryAction(Request $request, $galleryID)
    {
        $em             = $this->getDoctrine()->getManager();
        $user           = $this->get('security.context')->getToken()->getUser();
        $session        = $this->get('session');
        $translator     = $this->get('translator');
        $repo           = $em->getRepository(self::_mainEntityName);
        $picsRepo       = $em->getRepository(self::_picsEntityName);
        $gallery        = $repo->find($galleryID);
        $galleryPics    = $picsRepo->getGalleryPics($galleryID);
        
        try {
            
            if ($request->server->get('SERVER_NAME') === 'localhost') {
                $prefix = getcwd() . "/../../..";
            } else {
                $prefix = getcwd() . "/../..";
            }
            
            if (count($galleryPics) > 0) {
                foreach ($galleryPics as $pic) {

                    $filepath = $prefix . $pic->getPicture();
                    $thumbpath = $prefix . $pic->getThumb();

                    unlink($filepath);
                    unlink($thumbpath);

                }
            }
            
            $galLocale  = $gallery->getLocale()->__toLocaleString();
            $galID      = $gallery->getID();
            $galPath    = getcwd().'/../../front-end/app/img/gallery/'.$galLocale.'/'.$galID.'/';
            $thumbPath  = $galPath."thumbs/";
            
            $em->remove($gallery);
            $em->flush();
            
            
            $OS = $this->defineOS();
            
            if (!$OS) {
            
                chmod($thumbPath, 0777);
                unlink($thumbPath);
                chmod($galPath, 0777);
                unlink($galPath);
                
            } else {
                $lines = array();
                exec("rmdir /s/Q ".realpath($thumbPath), $lines, $deleteError);
                exec("rmdir /s/Q ".realpath($galPath), $lines, $deleteError);
            }
            
        } catch (Exception $e) {
            $session->getFlashBag()->set('error', $translator->trans('AGB_ERROR_WHILE_DELETING')."! ".$e->getMessage());
            return $this->redirect($this->generateUrl('admin_gallery_show_list'));
        }

        $session->getFlashBag()->set('success', $translator->trans('AGB_GALLERY_DELETED'));
        return $this->redirect($this->generateUrl('admin_gallery_show_list'));
    }
    
    private function defineOS()
    {
        $tmp = dirname(__FILE__);
        if (strpos($tmp, '/', 0)!==false) {
            define('WINDOWS_SERVER', false);
        } else {
            define('WINDOWS_SERVER', true);
        }
        
        return WINDOWS_SERVER;
    }
    
    public function showGalleryListAction()
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $this->get('security.context')->getToken()->getUser();
        $repo   = $em->getRepository(self::_mainEntityName);
        $locale = $this->get('session')->get('_locale');
        
        $galleries = $repo->getGalleries($locale);
        
        return $this->render('AdminGalleryBundle:Gallery:list.html.twig', array(
            'galleries' => $galleries,
        ));
    }
    
    public function showGalleryInfoAction(Request $request, $galleryID)
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $this->get('security.context')->getToken()->getUser();
        $repo   = $em->getRepository(self::_mainEntityName);
        
        $gallery = $repo->find($galleryID);
        
        return $this->render('AdminGalleryBundle:Gallery:showInfo.html.twig', array(
            'gallery'   => $gallery,
            'galleryID' => $galleryID,
        ));
    }
    
    public function editGalleryInfoAction(Request $request, $galleryID)
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $this->get('security.context')->getToken()->getUser();
        $repo   = $em->getRepository(self::_mainEntityName);
        
        $gallery    = $repo->find($galleryID);
        $form       = $this->createForm(new GalleryEditType(), $gallery);
        
        return $this->render('AdminGalleryBundle:Gallery:edit.html.twig', array(
            'form' => $form->createView(),
            'galleryID' => $galleryID,
        ));
    }
    
    public function saveGalleryInfoAction(Request $request, $galleryID)
    {
        $em     = $this->getDoctrine()->getManager();
        $user   = $this->get('security.context')->getToken()->getUser();
        $repo   = $em->getRepository(self::_mainEntityName);
        $session        = $this->get('session');
        $translator     = $this->get('translator');
        
        $gallery    = $repo->find($galleryID);
        $form       = $this->createForm(new GalleryEditType(), $gallery);
        
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            
            $data = $form->getData();
            
            try {
                
                $em->persist($data);
                $em->flush();
                
                if ($data->getIsPublished())
                    $this->generateGalleryJSON($galleryID, $data);
                
            } catch (DBALException $e) {
                
                $session->getFlashBag()->set('error', $translator->trans('AGB_ERROR_WHILE_UPDATING')."! ".$e->getMessage());
                return $this->redirect($this->generateUrl('admin_gallery_edit_info', array('galleryID' => galleryID)));
                
            }
            
            $session->getFlashBag()->set('success', $translator->trans('AGB_UPDATED_SUCCESFULLY')."!");
            return $this->redirect($this->generateUrl('admin_gallery_show_info', array('galleryID' => $galleryID)));
            
        } else {
            $session->getFlashBag()->set('success', $translator->trans('AGB_FORM_NOT_VALID')."!");
            return $this->redirect($this->generateUrl('admin_gallery_edit_info', array('galleryID' => $galleryID)));
        }
    }
    
    private function setGalleryLocale($galleryLocale)
    {
        $session = $this->get('session');
        
        $session->set('galleryLocale', $galleryLocale);
    }
    
    private function getGalleryLocale()
    {
        return $this->get('session')->get('galleryLocale');
    }
    
    public function changeFileTitleAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            
            $newTitle   = $request->request->get('title');
            $fileID     = $request->request->get('id');
            $em         = $this->getDoctrine()->getManager();
            $repo       = $em->getRepository(self::_picsEntityName);
            $file       = $repo->find($fileID);
            $translator = $this->get('translator');
            
            try {
                
                $file->setTitle($newTitle);
                $em->persist($file);
                $em->flush();
                
                return new Response(json_encode(array('status' => 'OK', 'msg' => $translator->trans('AGB_FILE_TITLE_CHANGED'))),200);
                
            } catch(DBALException $e) {
                return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_FILE_TITLE_NOT_CHANGED').". ". $e->getMessage())),200);
            }
            
        }
        
        return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_REQUEST_NOT_VALID_XHR'))), 200);
    }
    
    public function setGalleryCoverAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            
            $fileID     = $request->request->get('id');
            $galleryID  = $request->request->get('galleryID');
            $em         = $this->getDoctrine()->getManager();
            $galRepo    = $em->getRepository(self::_mainEntityName);
            $fileRepo   = $em->getRepository(self::_picsEntityName);
            $gallery    = $galRepo->find($galleryID);
            $file       = $fileRepo->find($fileID);
            $translator = $this->get('translator');
            
            try {
                
                $gallery->setMainPic($file);
                $em->persist($gallery);
                $em->flush();
                
                return new Response(json_encode(array('status' => 'OK', 'msg' => $translator->trans('AGB_GALLERY_MAIN_PIC_CHANGED'))),200);
                
            } catch(DBALException $e) {
                return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_GALLERY_MAIN_PIC_NOT_CHANGED').'. '. $e->getMessage())),200);
            }
            
        }
        
        return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_REQUEST_NOT_VALID_XHR'))), 200);
    }
    
    public function deleteFileAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            
            $fileID     = $request->request->get('id');
            $em         = $this->getDoctrine()->getManager();
            $fileRepo   = $em->getRepository(self::_picsEntityName);
            $file       = $fileRepo->find($fileID);
            $translator = $this->get('translator');
            
            try {
                
                if ($request->server->get('SERVER_NAME') === 'localhost') {
                    $filepath = getcwd() . "/../../.." . $file->getPicture();
                    $thumbpath = getcwd() . "/../../.." . $file->getThumb();
                } else {
                    $filepath = getcwd() . "/../.." . $file->getPicture();
                    $thumbpath = getcwd() . "/../.." . $file->getThumb();
                }
                
                unlink($filepath);
                unlink($thumbpath);
                $em->remove($file);
                $em->flush();
                
                return new Response(json_encode(array('status' => 'OK', 'msg' => $translator->trans('AGB_PICTURE_REMOVED'))),200);
                
            } catch(DBALException $e) {
                return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_PICTURE_NOT_REMOVED').'. '. $e->getMessage())),200);
            }
            
        }
        
        return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_REQUEST_NOT_VALID_XHR'))), 200);
    }
    
    public function updateGalleryJsonAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            
            $galleryID  = $request->request->get('galleryID');
            $translator = $this->get('translator');
            
            if ($this->generateGalleryJSON($galleryID))
                return new Response(json_encode(array('status' => 'OK', 'msg' => $translator->trans('AGB_JSON_UPDATED'))), 200);
            
        }
        
        return new Response(json_encode(array('status' => 'FAIL', 'msg' => $translator->trans('AGB_REQUEST_NOT_VALID_XHR'))), 200);
    }
    
    private function createGalleryFolders($locale, $galleryID)
    {
        $path = getcwd()."/../../front-end/app/img/gallery/".$locale."/".$galleryID."/thumbs/";
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
    }
    
    public function uploadFilesAction($galleryID)
    {
        $em = $this->getDoctrine()->getManager();
        $galRepo = $em->getRepository(self::_mainEntityName);
        $gallery = $galRepo->find($galleryID);
        $galleryType = $gallery->getGalleryType()->getId();
        $files = null;
        $mainPic = null;
        
        if ($galleryType == 1) {
        
            $repo = $em->getRepository(self::_picsEntityName);
            $files = $repo->getGalleryPics($galleryID);
        
        } else {
            
            $repo = $em->getRepository(self::_vidsEntityName);
            $files = $repo->getGalleryVids($galleryID);
            
        }
        
        $mainPic = $gallery->getMainPic();
        if ($mainPic != null)
            $mainPic = $mainPic->getId();
        
        
        return $this->render('AdminGalleryBundle:Gallery:upload.html.twig', array(
            'galleryID'     => $galleryID,
            'galleryLocale' => $this->getGalleryLocale(),
            'files' => $files,
            'mainPic' => $mainPic,
            'galleryType' => $galleryType,
            'isPublished' => $gallery->getIsPublished(),
        ));
    }
    
    private function generateJSONDirStructure($galleryID, $galleryLocale)
    {
        $path = getcwd().self::_galleryDir."/".$galleryLocale."/".$galleryID;
        if(!is_dir($path)){
            mkdir($path, 0755, true);
        }
    }
    
    private function generateGalleryJSON($galleryID, $data = null)
    {
        $em             = $this->getDoctrine()->getManager();
        $picsRepo       = $em->getRepository(self::_picsEntityName);
        $vidsRepo       = $em->getRepository(self::_vidsEntityName);
        
        if ($data === null) {
            $galleryRepo = $em->getRepository(self::_mainEntityName);
            $data = $galleryRepo->find($galleryID);
        }
        
        if ($data->getGalleryType()->getId() == 1) {
            $files = $picsRepo->getGalleryPics($galleryID);
        } else {
            $files = $vidsRepo->getGalleryVids($galleryID);
        }
        
        $gallery            = array();
        $gallery['files']   = array();
        
        $gallery['name'] = $data->getName();
        $gallery['isPublished'] = $data->getIsPublished();
        
        if ($data->getMainPic() != null)
            $gallery['cover'] = array(
                'filename'  => $data->getMainPic()->getPicture(),
                'filepath'  => $data->getFrontendPath(),
                'thumb'     => array(
                    'filename' => $data->getMainPic()->getName(),
                    'filepath' => $data->getMainPic()->getFrontendThumb(),
                )
            );
        
        if (!empty($files) && $files != null) {
            
            $i=0;
            foreach ($files as $file) {
                
                $gallery['files'][$i] = array(
                    'filename' => $file->getName(),
                    'filepath' => $file->getFrontendPath(),
                    'title'    => $file->getTitle(),
                );
                
                if ($file instanceof GalleryPics) {
                    $gallery['files'][$i]['thumb'] = array(
                        'filename' => $file->getName(),
                        'filepath' => $file->getFrontendThumb(),
                    );
                }
                
                $i++;
                
            }
            
        }
        
        $this->generateJSONDirStructure($data->getId(), $data->getLocale()->__toLocaleString());
        
        file_put_contents(getcwd().self::_galleryDir.$data->getLocale()->__toLocaleString()."/".$data->getId()."/gallery.json", json_encode($gallery, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE), LOCK_EX);
        
        return true;
    }
    
}
