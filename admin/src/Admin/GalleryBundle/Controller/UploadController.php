<?php

namespace Admin\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#entities
use Admin\GalleryBundle\Entity\Gallery;
use Admin\GalleryBundle\Entity\GalleryPics;
use Admin\GalleryBundle\Entity\GalleryVids;


class UploadController extends Controller
{
    
    const _galleryEntity = 'Admin\GalleryBundle\Entity\Gallery';
    
    public function uploadFileAction(Request $request)
    {
        if ($request->getMethod() === "POST") {
            
            //getting data from form
            $requestBag     = $request->request;
            $fileBag        = $request->files;
            
            //getting params container
            $uploaderParams = $this->container->getParameter('uploader');
            
            //validating request
            $token  = $requestBag->get('token');
            $time   = $requestBag->get('timestamp');
            
            if ($token !== md5($uploaderParams['token'].$time)) {
                
                return new Response(
                            json_encode(
                                $this->buildResponse(6)
                            ),
                            200
                        );
                
            }
            
            //getting needed params from request
            $galleryLocale  = $requestBag->get('galleryLocale');
            $galleryID      = $requestBag->get('galleryID');
            $galleryType    = $requestBag->get('galleryType');
            $fileData       = $fileBag->get('Filedata');
            
            //getting needed params from config.yml
            $targetFolder   = $uploaderParams['upload_dir'];
            
            //setting targetFolder and targetFile
            $targetFolder   = $targetFolder."/".$galleryLocale."/".$galleryID;
            $targetFile     = $targetFolder . '/' . $fileData->getClientOriginalName();
            
            //creating gallery dir struture
            $this->createGalleryDir($targetFolder, $galleryType, $uploaderParams['glob_options']['no_video_thumbs']);
            
            //getting name of uploaded file and generating unique filename
            $fileName = $fileData->getClientOriginalName();
            if (is_file($targetFolder."/".$fileName)) {
                
                $fileName   = $this->generateUniqueName($targetFolder, $fileName);
                $targetFile = $targetFolder."/".$fileName;
                
            }
            
            if ($this->validateFile($fileData, $uploaderParams, $galleryType) || !$this->validateFile($fileData, $uploaderParams, $galleryType)) {
                
                //moving uploaded file to destination folder
                $fileData->move($targetFolder, $fileName);
                
                //setting paths for frontend and generating image thumb
                $fileInfo = $this->generateFrontendPaths(pathinfo($targetFolder."/".$fileName));
                if ($galleryType == 1) {
                    
                    $this->gd_create_scaled_image($targetFile, '', array('auto_orient' => true), $uploaderParams['glob_options']);
                    $fileInfo['thumbPath'] = $fileInfo['dirname'] . "/thumbs/";
                    $fileInfo['thumbName'] = $fileInfo['basename'];
                    $thumbCreated = true;
                    
                } else {
                    
                    $result = $this->generateVideoThumb($fileInfo, $targetFolder, $targetFile);
                    foreach ($result as $key => $value)
                        $$key = $value;
                    
                }
                
                //checking whether image thumb created or not
                if ($thumbCreated) {
                    
                    //copying original image to /originals/ folder
                    if ($galleryType == 1)
                        $file = $targetFolder."/".$fileName;
                    else
                        $file = $targetFolder."/frames/".$fileName;
                    
                    copy($file, $targetFolder."/originals/".$fileName);
                    
                    $imageSize = getimagesize($file);
                    //if original image is > 1024x768 then scaling it down
                    if ($imageSize[0] > $uploaderParams['glob_options']['max_original_width'] || $imageSize[1] > $uploaderParams['glob_options']['max_original_height']) {
                        
                        $uploaderParams['glob_options']['max_width'] = $uploaderParams['glob_options']['max_original_width'];
                        $uploaderParams['glob_options']['max_height'] = $uploaderParams['glob_options']['max_original_height'];
                        $this->gd_create_scaled_image($file, '', array('auto_orient' => true), $uploaderParams['glob_options'], true);
                        
                    }
                }
                
                //writing to db
                try {
                    
                    $fileID = $this->writeDB($fileInfo, $galleryID, $galleryType);
                    
                } catch (DBALException $e) {
                    
                    //Error while saving to db, so delete file
                    unlink($targetFile);
                    return new Response(
                                json_encode(
                                    $this->buildResponse(4)
                                ),
                                200
                            );
                    
                }

                //generating response
                //code = 1 for img, and code = 2 for video
                return new Response(
                            json_encode(
                                $this->buildResponse(($galleryType == 1) ? 1 : 2, $fileInfo, $fileName, $fileID, $thumbCreated, '')
                            ),
                            200
                        );
                
                
                
                
            } else {
                
                //file type cannot be uploaded case
                $this->buildResponse(3);
                return new Response(
                            json_encode(
                                $this->buildResponse(3)
                            ),
                            200
                        );
            }
            
        }
        
        //request->getMethod() !== 'POST'
        return new Response(
                    json_encode(
                        $this->buildResponse(5)
                    ), 
                    200
                );
    }
    
    private function buildResponse($code, $fileInfo = array(), $fileName = '', $fileID = '', $thumbCreated = true, $fileTitle = '')
    {
        $res = array();
        
        if ($code < 3) {
            
            if ($code == 2 && !$thumbCreated)
                $res['thumb']       = "/../..".$fileInfo['dirname'] . "/" . $fileName;
            else if ($code == 2)
                $res['thumb']       = "/../..".$fileInfo['thumbPath'] . $fileName;
            else
                $res['thumb']       = $fileInfo['thumbPath'] . $fileName;
            
            $res['name']            = $fileName;
            $res['title']           = $fileTitle;
            $res['fileID']          = $fileID;
            $res['type']            = ($code == 1) ? 'img' : 'video';
            $res['thumbCreated']    = $thumbCreated;
            $res['code']            = $code;
            
        } else {
            
            $translator = $this->get('translator');
            
            $res['status']          = 'FAIL';
            $res['code']            = $code;
            if ($code === 4)
                $res['msg']         = $translator->trans('AGB_FILE_SAVE_ERROR');
            else if ($code === 5)
                $res['msg']         = $translator->trans('AGB_REQUEST_METHOD_NOT_POST');
            else if ($code === 6)
                $res['msg']         = $translator->trans('AGB_UPLOAD_TOKEN_DOES_NOT_MATCH');
        }
        
        return $res;
    }
    
    private function writeDB($fileData, $galleryID, $galleryType = 1)
    {
        if ($galleryType == 1) {
            $fileID = $this->writePic($fileData, $galleryID);
        } else {
            $fileID = $this->writeVideo($fileData, $galleryID);
        }
        
        return $fileID;
    }
    
    private function writePic($fileData, $galleryID)
    {
        $em             = $this->getDoctrine()->getManager();
        $gallery        = $em->getRepository(self::_galleryEntity)->find($galleryID);
        
        $frontendPath   = $fileData['dirname'];
        $frontendPath   = explode('app', $frontendPath);
        $frontendPath   = $frontendPath[1];
        
        $file           = new GalleryPics();
        
        $file->setFilepath($fileData['dirname']);
        $file->setFrontendPath($frontendPath);
        $file->setGallery($gallery);
        $file->setName($fileData['basename']);
        $file->setTitle(NULL);
        
        $em->persist($file);
        $em->flush();
        
        return $file->getId();
    }
    
    private function writeVideo($fileData, $galleryID)
    {
        $em             = $this->getDoctrine()->getManager();
        $gallery        = $em->getRepository(self::_galleryEntity)->find($galleryID);
        
        $frontendPath   = $fileData['dirname'];
        $frontendPath   = explode('app', $frontendPath);
        $frontendPath   = $frontendPath[1];
        
        $file           = new GalleryVids();
        
        $file->setFilepath($fileData['dirname']);
        $file->setFrontendPath($frontendPath);
        $file->setGallery($gallery);
        $file->setName($fileData['basename']);
        $file->setTitle(NULL);
        
        $em->persist($file);
        $em->flush();
        
        return $file->getID();
    }
    
    private function generateFrontendPaths($fileInfo)
    {
        if ($_SERVER['SERVER_NAME'] === 'localhost') {
            $fileInfo['dirname'] = explode('turan-pro',realpath($fileInfo['dirname']));
            unset($fileInfo['dirname'][0]);
            $fileInfo['dirname'] = "/turan-pro".implode('',$fileInfo['dirname']);
        } else {
            $fileInfo['dirname'] = explode('front-end', realpath($fileInfo['dirname']));
            unset($fileInfo['dirname'][0]);
            $fileInfo['dirname'] = "/front-end".implode('',$fileInfo['dirname']);
        }
        
        return $fileInfo;
    }
    
    private function validateFile($file, $options = array(), $galleryType = 1)
    {
        $mimeType = $this->get_mime_type($file->getPathName());
        $mime_exploded = explode('/', $mimeType);
        $ext = $file->getClientOriginalExtension();
        
        if ($mime_exploded[0] == 'image' && $galleryType == 1) {
            
            if (!empty($options))
                $options = $options['img_types'];
            
            if (in_array($ext, $options))
                return true;
            
        } else if ($mime_exploded[0] == 'video' && $galleryType == 2) {
            
            if (!empty($options))
                $options = $options['video_types'];
            
            if (in_array($ext, $options))
                return true;
            
        } else
            return false;
    }
    
    /**
     * 
     * @param type $targetFolder
     * @param type $galleryType
     * @param type $no_video_thumbs defines whether generate thumbs for video files or no, default value  TRUE
     * @return boolean
     */
    private function createGalleryDir($targetFolder, $galleryType, $no_video_thumbs = true)
    {
        $path           = null;
        $originalsPath  = null;
        $framesPath     = null;
        
        //checking whether create or no folder for thumbs (it persists from galleryType && no_video_thumbs option)
        if ($galleryType == 1 || ($galleryType == 2 && !$no_video_thumbs)) {
            
            $path           = $targetFolder."/thumbs";
            $originalsPath  = $targetFolder."/originals";
            
            if ($galleryType == 2)
                $framesPath = $targetFolder."/frames";
            
        } elseif ($galleryType == 2 && $no_video_thumbs)
            $path = $targetFolder;
        
        //creating dir structure
        if (!is_dir($path))
            mkdir($path, 0755, true);
        else if ($originalsPath != null && !is_dir($originalsPath))
            mkdir($originalsPath, 0755);
        else if ($framesPath != null && !is_dir($framesPath))
            mkdir($framesPath, 0755);
        else
            return false;
        
        return true;
    }
    
    /**
     * 
     * @param type $targetFolder
     * @param type $fileName
     */
    private function generateUniqueName($targetFolder, $fileName)
    {
        $targetPath = getcwd() . "/". $targetFolder;
        $targetFile = rtrim($targetPath,'/') . '/' . $fileName;
        
        try {
            if (file_exists($targetFile)) {
                $file = $this->get_unique_filename($targetFile);
//                $targetFile = $file['path'];
            }
        } catch(Exception $e) {
            var_dump($e->getMessage());exit;
        }
        
        return $file;
    }

    private function get_unique_filename($name) {
        while(is_dir($name)) {
            $name = $this->upcount_name($name);
        }
        while(is_file($name)) {
            $name = $this->upcount_name($name);
        }
        
        $path_exploded = explode('/', $name);
        $fileName = $path_exploded[count($path_exploded)-1];
        unset($path_exploded[count($path_exploded) - 1]);
        $path = implode('/', $path_exploded);

//        return array('path' => $path, 'fileName' => $fileName);
        return $fileName;
    }

    private function upcount_name_callback($matches) {
        $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
        $ext = isset($matches[2]) ? $matches[2] : '';
        return ' ('.$index.')'.$ext;
    }

    private function upcount_name($name) {
        return preg_replace_callback(
            '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
            array($this, 'upcount_name_callback'),
            $name,
            1
        );
    }
    
    private function get_mime_type($file)
    {
        $finfoDB    = finfo_open(FILEINFO_MIME);
        $info       = finfo_file($finfoDB, $file);
        
        $info = explode(';', $info);
        
        return $info[0];
    }
    
    private function generateVideoThumb($fileInfo, $targetFolder, $targetFile)
    {
        $uploaderParams = $this->container->getParameter('uploader');
        
        if (extension_loaded('php_ffmpeg') && !$uploaderParams['glob_options']['no_video_thumbs']) {

            $fileInfo['thumbPath'] = $fileInfo['dirname'] . "/thumbs/";
            $fileInfo['thumbName'] = $fileInfo['filename'].".png";
            $videoThumbPath = $targetFolder;

            $frame = 30;
            $mov = new ffmpeg_movie(realpath($targetFile));
            $frame = $mov->getFrame($frame);

            if ($frame) {
                $gd_image = $frame->toGDImage();
                if ($gd_image) {
                    imagepng($frame, $videoThumbPath. "/" . $fileInfo['filename'].".png");
                    imagedestroy($gd_image);
                }
            }
            gd_create_scaled_image($videoThumbPath. "/" . $fileInfo['filename'].".png", '', array('auto_orient' => true), $uploaderParams['glob_options']);
            rename($videoThumbPath. "/" . $fileInfo['filename'].".png", $videoThumbPath. "/frames/" . $fileInfo['filename'].".png");
            $thumbCreated = true;

        } else {
            $fileInfo['thumbPath'] = '';
            $fileInfo['thumbName'] = '';
            $thumbCreated = false;
        }
        
        return array('fileInfo' => $fileInfo, 'thumbCreated' => $thumbCreated);
    }
    
    private function get_scaled_image_file_paths($file_name, $version = '') {
        $file_path = $file_name;
        if (!empty($version)) {
            $version_dir = $this->get_upload_path(null, $version);
            if (!is_dir($version_dir)) {
                mkdir($version_dir, $this->options['mkdir_mode'], true);
            }
            $new_file_path = $version_dir.'/'.$file_name;
        } else {
            $new_file_path = $file_path;
        }
        return array($file_path, $new_file_path);
    }

    private function gd_create_scaled_image($file_name, $version = '', $options, $glob_options, $scaling_original = false) {
        if (!function_exists('imagecreatetruecolor')) {
            error_log('Function not found: imagecreatetruecolor');
            return false;
        }
        list($file_path, $new_file_path) =
            $this->get_scaled_image_file_paths($file_name, $version);
        $type = strtolower(substr(strrchr($file_name, '.'), 1));
        switch ($type) {
            case 'jpg':
            case 'jpeg':
                $src_func = 'imagecreatefromjpeg';
                $write_func = 'imagejpeg';
                $image_quality = isset($options['jpeg_quality']) ?
                    $options['jpeg_quality'] : 75;
                break;
            case 'gif':
                $src_func = 'imagecreatefromgif';
                $write_func = 'imagegif';
                $image_quality = null;
                break;
            case 'png':
                $src_func = 'imagecreatefrompng';
                $write_func = 'imagepng';
                $image_quality = isset($options['png_quality']) ?
                    $options['png_quality'] : 9;
                break;
            default:
                return false;
        }
        $src_img = $this->gd_get_image_object(
            $file_path,
            $src_func,
            !empty($glob_options['no_cache'])
        );
        $image_oriented = false;
        if (!empty($options['auto_orient']) && $this->gd_orient_image(
                $file_path,
                $src_img
            )) {
            $image_oriented = true;
            $src_img = $this->gd_get_image_object(
                $file_path,
                $src_func
            );
        }
        $max_width = $img_width = imagesx($src_img);
        $max_height = $img_height = imagesy($src_img);
        if (!empty($glob_options['max_width'])) {
            $max_width = $glob_options['max_width'];
        }
        if (!empty($glob_options['max_height'])) {
            $max_height = $glob_options['max_height'];
        }
        $scale = min(
            $max_width / $img_width,
            $max_height / $img_height
        );
        
        if ($scale >= 1) {
            if ($image_oriented) {
                return $write_func($src_img, $new_file_path, $image_quality);
            }

            if ($file_path !== $new_file_path) {
                return copy($file_path, $new_file_path);
            } else {
                $fp = explode('/', $new_file_path);
                $name = $fp[count($fp) - 1];
                unset($fp[count($fp) - 1]);
                $fp[] = "thumbs/".$name;
                $new_file_path = implode('/', $fp);
                return copy($file_path, $new_file_path);
            }
        }
        if (empty($options['crop'])) {
            $new_width = $img_width * $scale;
            $new_height = $img_height * $scale;
            $dst_x = 0;
            $dst_y = 0;
            $new_img = imagecreatetruecolor($new_width, $new_height);
        } else {
            if (($img_width / $img_height) >= ($max_width / $max_height)) {
                $new_width = $img_width / ($img_height / $max_height);
                $new_height = $max_height;
            } else {
                $new_width = $max_width;
                $new_height = $img_height / ($img_width / $max_width);
            }
            $dst_x = 0 - ($new_width - $max_width) / 2;
            $dst_y = 0 - ($new_height - $max_height) / 2;
            $new_img = imagecreatetruecolor($max_width, $max_height);
        }
        // Handle transparency in GIF and PNG images:
        switch ($type) {
            case 'gif':
            case 'png':
                imagecolortransparent($new_img, imagecolorallocate($new_img, 0, 0, 0));
            case 'png':
                imagealphablending($new_img, false);
                imagesavealpha($new_img, true);
                break;
        }
        if (!$scaling_original) {
            
            $exploded_path = explode('/', $file_path);
            $file_name = $exploded_path[count($exploded_path) - 1];
            unset($exploded_path[count($exploded_path) - 1]);
            $exploded_path[] = "thumbs";
            $exploded_path[] = $file_name;
            $path = implode('/', $exploded_path);
            
        } else {
            $path = $file_path;
        }
        
        $success = imagecopyresampled(
            $new_img,
            $src_img,
            $dst_x,
            $dst_y,
            0,
            0,
            $new_width,
            $new_height,
            $img_width,
            $img_height
        ) && $write_func($new_img, $path, $image_quality);
        $this->gd_set_image_object($path, $new_img);
        return $success;
    }

    private function gd_get_image_object($file_path, $func, $no_cache = false) {
        if (empty($image_objects[$file_path]) || $no_cache) {
            $this->gd_destroy_image_object($file_path);
            $image_objects[$file_path] = $func($file_path);
        }
        return $image_objects[$file_path];
    }

    private function gd_set_image_object($file_path, $image) {
        $image_objects = array();
        $this->gd_destroy_image_object($file_path);
        $image_objects[$file_path] = $image;
    }

    private function gd_destroy_image_object($file_path) {
        $image = @$image_objects[$file_path];
        return $image && imagedestroy($image);
    }

    private function gd_imageflip($image, $mode) {
        if (function_exists('imageflip')) {
            return imageflip($image, $mode);
        }
        $new_width = $src_width = imagesx($image);
        $new_height = $src_height = imagesy($image);
        $new_img = imagecreatetruecolor($new_width, $new_height);
        $src_x = 0;
        $src_y = 0;
        switch ($mode) {
            case '1': // flip on the horizontal axis
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                break;
            case '2': // flip on the vertical axis
                $src_x  = $new_width - 1;
                $src_width = -$new_width;
                break;
            case '3': // flip on both axes
                $src_y = $new_height - 1;
                $src_height = -$new_height;
                $src_x  = $new_width - 1;
                $src_width = -$new_width;
                break;
            default:
                return $image;
        }
        imagecopyresampled(
            $new_img,
            $image,
            0,
            0,
            $src_x,
            $src_y,
            $new_width,
            $new_height,
            $src_width,
            $src_height
        );
        return $new_img;
    }

    private function gd_orient_image($file_path, $src_img) {
        if (!function_exists('exif_read_data')) {
            return false;
        }
        $exif = @exif_read_data($file_path);
        if ($exif === false) {
            return false;
        }
        $orientation = intval(@$exif['Orientation']);
        if ($orientation < 2 || $orientation > 8) {
            return false;
        }
        switch ($orientation) {
            case 2:
                $new_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                break;
            case 3:
                $new_img = imagerotate($src_img, 180, 0);
                break;
            case 4:
                $new_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                break;
            case 5:
                $tmp_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 6:
                $new_img = imagerotate($src_img, 270, 0);
                break;
            case 7:
                $tmp_img = $this->gd_imageflip(
                    $src_img,
                    defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
                );
                $new_img = imagerotate($tmp_img, 270, 0);
                imagedestroy($tmp_img);
                break;
            case 8:
                $new_img = imagerotate($src_img, 90, 0);
                break;
            default:
                return false;
        }
        $this->gd_set_image_object($file_path, $new_img);
        return true;
    }
    
}
