<?php

$verifyToken = md5('unique_salt' . $_POST['timestamp']);
$glob_options = array(
    'max_width' => 160,
    'max_height' => 160,
    'no_cache' => false,
    'no_video_thumbs' => true,
    'max_original_width' => 1024,
    'max_original_height' => 768,
    'thumbnail' => array(
        // Uncomment the following to use a defined directory for the thumbnails
        // instead of a subdirectory based on the version identifier.
        // Make sure that this directory doesn't allow execution of files if you
        // don't pose any restrictions on the type of uploaded files, e.g. by
        // copying the .htaccess file from the files directory for Apache:
        //'upload_dir' => dirname($this->get_server_var('SCRIPT_FILENAME')).'/thumb/',
        //'upload_url' => $this->get_full_url().'/thumb/',
        // Uncomment the following to force the max
        // dimensions and e.g. create square thumbnails:
        //'crop' => true,
        'max_width' => 80,
        'max_height' => 80
    )
);
$db_connection = array(
    'username' => 'root',
    'password' => '1oiHLzx70y',
    'host' => '127.0.0.1',
    'db_port' => '3306',
);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
    
        $targetFolder = $_POST['folder'];
        if (!file_exists($targetFolder."/thumbs"))
            mkdir($targetFolder."/thumbs", 0777, true);
        
        $galleryID = $_POST['galleryID'];
        $galleryType = $_POST['galleryType'];
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = getcwd() . "/". $targetFolder;
	$targetFile = rtrim($targetPath,'/') . '/' . $_FILES['Filedata']['name'];
        
        //generating unique filename
        try {
            if (file_exists($targetFile)) {
                $file = get_unique_filename($targetFile);
                $targetFile = $file['path'];
            }
        } catch(Exception $e) {
            var_dump($e->getMessage());exit;
        }
        
        //validate directory
        if (!file_exists($targetPath)) {
            mkdir($targetPath, 0777, true);
        }
        
	// Validate the file type
	$fileTypes = array('jpg','jpeg','gif','png','JPG','PNG', 'JPEG', 'GIF'); // File extensions
        $videoFiles = array('avi', 'AVI', 'wmv', 'WMV', 'mp4', 'MP4', 'mkv', 'MKV');
	$fileParts = pathinfo($_FILES['Filedata']['name']);
	
	if (in_array($fileParts['extension'],$fileTypes) && $galleryType == 1) {
            
		move_uploaded_file($tempFile,$targetFile);
                $type = 'img';
                
                $fileInfo = pathinfo($targetFile);
                var_dump($fileInfo);exit;
                if ($_SERVER['SERVER_NAME'] === 'localhost') {
                    $fileInfo['dirname'] = explode('turan-pro',realpath($fileInfo['dirname']));
                    unset($fileInfo['dirname'][0]);
                    $fileInfo['dirname'] = "/turan-pro".implode('',$fileInfo['dirname']);
                } else {
                    $fileInfo['dirname'] = explode('front-end', realpath($fileInfo['dirname']));
                    unset($fileInfo['dirname'][0]);
                    $fileInfo['dirname'] = "/front-end".implode('',$fileInfo['dirname']);
                }
                
                gd_create_scaled_image($targetFile, '', array('auto_orient' => true), $glob_options);
                $thumbCreated = true;

                $data = write_db($db_connection, $fileInfo, $galleryID);
                db_close($data['conn']);

                $fileInfo['thumbPath'] = $fileInfo['dirname'] . "/" .  'thumbs';
                $fileInfo['thumbName'] = $fileInfo['basename'];
                
                echo (
                        json_encode(
                                array(
                                    'thumb' => $fileInfo['thumbPath'] . '/' . $fileInfo['thumbName'],
                                    'name'  => $fileInfo['thumbName'],
                                    'title' => '',
                                    'fileID' => $data['id'],
                                    'type'  => $type,
                                    'thumbCreated' => $thumbCreated,
                                    'code' => 1,
                                )
                        )
                    );
                
	} else if (in_array($fileParts['extension'],$videoFiles) && $galleryType == 2) {
            
            move_uploaded_file($tempFile,$targetFile);
            $type = 'video';

            $fileInfo = pathinfo($targetFile);
            if ($_SERVER['SERVER_NAME'] === 'localhost') {
                $fileInfo['dirname'] = explode('turan-pro',realpath($fileInfo['dirname']));
                unset($fileInfo['dirname'][0]);
                $fileInfo['dirname'] = "/turan-pro".implode('',$fileInfo['dirname']);
            } else {
                $fileInfo['dirname'] = explode('front-end', realpath($fileInfo['dirname']));
                unset($fileInfo['dirname'][0]);
                $fileInfo['dirname'] = "/front-end".implode('',$fileInfo['dirname']);
            }
            
            if (extension_loaded('php_ffmpeg') && !$glob_options['no_video_thumbs']) {
                        
                $fileInfo['thumbPath'] = $fileInfo['dirname'] . "/" .  'thumbs';
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
                gd_create_scaled_image($videoThumbPath. "/" . $fileInfo['filename'].".png", '', array('auto_orient' => true), $glob_options);
                rename($videoThumbPath. "/" . $fileInfo['filename'].".png", $videoThumbPath. "/frames/" . $fileInfo['filename'].".png");
                $thumbCreated = true;

            } else {
                $fileInfo['thumbPath'] = '';
                $fileInfo['thumbName'] = '';
                $thumbCreated = false;
            }

            $data = write_db_video($db_connection, $fileInfo, $galleryID);
            db_close($data['conn']);
                
            echo (
                    json_encode(
                            array(
                                'thumb' => "../../../../../../..".$fileInfo['dirname'] . "/" . $fileInfo['filename'] .".". $fileInfo['extension'],
                                'name'  => $fileInfo['filename'],
                                'title' => '',
                                'fileID' => $data['id'],
                                'type'  => $type,
                                'thumbCreated' => $thumbCreated,
                                'code' => 2,
                            )
                    )
                );
            
	} else {
//            echo 'Invalid file type.';
            echo(
                    json_encode(
                            array(
                                'msg' => 'File type not allowed!',
                                'fType' => $fileParts['extension'],
                                'code'  => 3,
                            )
                    )
                );
        }
}

function db_connect($db_connection)
{
    $connection = mysql_connect($db_connection['host'].":".$db_connection['db_port'], $db_connection['username'], $db_connection['password']);
    mysql_select_db('turan');
    
    return $connection;
}

function db_close($connection)
{
    try {
        mysql_close($connection);
        
        return true;
    } catch(Exception $e) {
        var_dump($e->getMessage(), mysql_error());exit;
        return false;
    }
}

function write_db($db_connection, $info, $galleryID)
{
    $connection = db_connect($db_connection);
    $path = $info['dirname'];
    $path = explode('app', $path);
    $path = $path[1];
    
    $result = mysql_query(
            "INSERT INTO gallery_pics SET name='".$info['basename']."', frontend_path='".mysql_real_escape_string($path)."', filepath='".  mysql_real_escape_string($info['dirname'])."', title=NULL, gallery_id=$galleryID"
            );
    
    $fileID = mysql_query("SELECT gp_0.id FROM gallery_pics AS gp_0 WHERE name='".$info['basename']."';");
    $file = mysql_fetch_assoc($fileID);
    
    if ($result)
        return array('conn' => $connection, 'id' => $file['id']);
    else
        return false;
}

function write_db_video($db_connection, $info, $galleryID)
{
    $connection = db_connect($db_connection);
    $path = $info['dirname'];
    $path = explode('app', $path);
    $path = $path[1];
    
    $result = mysql_query(
            "INSERT INTO gallery_vids SET name='".$info['basename']."', frontend_path='".mysql_real_escape_string($path)."', filepath='".  mysql_real_escape_string($info['dirname'])."', title=NULL, gallery_id=$galleryID"
            );
    
    $fileID = mysql_query("SELECT gv_0.id FROM gallery_vids AS gv_0 WHERE name='".$info['basename']."';");
    $file = mysql_fetch_assoc($fileID);
    
    if ($result)
        return array('conn' => $connection, 'id' => $file['id']);
    else
        return false;
}

function get_unique_filename($name) {
    while(is_dir($name)) {
        $name = upcount_name($name);
    }
    while(is_file($name)) {
        $name = upcount_name($name);
    }
    $path_exploded = explode('/', $name);
    
    return array('path' => $name, 'fileName' => ($path_exploded[count($path_exploded)-1]));
}

function upcount_name_callback($matches) {
    $index = isset($matches[1]) ? intval($matches[1]) + 1 : 1;
    $ext = isset($matches[2]) ? $matches[2] : '';
    return ' ('.$index.')'.$ext;
}

function upcount_name($name) {
    return preg_replace_callback(
        '/(?:(?: \(([\d]+)\))?(\.[^.]+))?$/',
        'upcount_name_callback',
        $name,
        1
    );
}

function get_scaled_image_file_paths($file_name, $version = '') {
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

function gd_create_scaled_image($file_name, $version = '', $options, $glob_options) {
    if (!function_exists('imagecreatetruecolor')) {
        error_log('Function not found: imagecreatetruecolor');
        return false;
    }
    list($file_path, $new_file_path) =
        get_scaled_image_file_paths($file_name, $version);
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
    $src_img = gd_get_image_object(
        $file_path,
        $src_func,
        !empty($glob_options['no_cache'])
    );
    $image_oriented = false;
    if (!empty($options['auto_orient']) && gd_orient_image(
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
    $exploded_path = explode('/', $file_path);
    $file_name = $exploded_path[count($exploded_path) - 1];
    unset($exploded_path[count($exploded_path) - 1]);
    $exploded_path[] = "thumbs";
    $exploded_path[] = $file_name;
    $path = implode('/', $exploded_path);
    
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
    gd_set_image_object($path, $new_img);
    return $success;
}

function gd_get_image_object($file_path, $func, $no_cache = false) {
    if (empty($image_objects[$file_path]) || $no_cache) {
        gd_destroy_image_object($file_path);
        $image_objects[$file_path] = $func($file_path);
    }
    return $image_objects[$file_path];
}

function gd_set_image_object($file_path, $image) {
    $image_objects = array();
    gd_destroy_image_object($file_path);
    $image_objects[$file_path] = $image;
}

function gd_destroy_image_object($file_path) {
    $image = @$image_objects[$file_path];
    return $image && imagedestroy($image);
}

function gd_imageflip($image, $mode) {
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

function gd_orient_image($file_path, $src_img) {
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
            $new_img = gd_imageflip(
                $src_img,
                defined('IMG_FLIP_VERTICAL') ? IMG_FLIP_VERTICAL : 2
            );
            break;
        case 3:
            $new_img = imagerotate($src_img, 180, 0);
            break;
        case 4:
            $new_img = gd_imageflip(
                $src_img,
                defined('IMG_FLIP_HORIZONTAL') ? IMG_FLIP_HORIZONTAL : 1
            );
            break;
        case 5:
            $tmp_img = gd_imageflip(
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
            $tmp_img = gd_imageflip(
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
    gd_set_image_object($file_path, $new_img);
    return true;
}
?>