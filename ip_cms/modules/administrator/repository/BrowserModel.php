<?php
/**
 * @package ImpressPages
 * @copyright   Copyright (C) 2011 ImpressPages LTD.
 *
 */
namespace Modules\administrator\repository;
if (!defined('CMS')) exit;



/**
 * 
 * Functions required by files repository browser
 * 
 * @author Mangirdas
 *
 */
class BrowserModel{
    protected static $instance;

    protected function __construct()
    {

    }

    protected function __clone()
    {

    }

    /**
     * Get singleton instance
     * @return BrowserModel
     */
    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new BrowserModel();
        }

        return self::$instance;
    }

    /**
     * Get list of files for file browser
     * @param int $seek
     * @param int $limit
     * @return array
     */
    public function getAvailableFiles($seek, $limit)
    {
        $answer = array();;

        $iterator = new \DirectoryIterator(BASE_DIR.FILE_REPOSITORY_DIR);
        $iterator->seek($seek);
        while ($iterator->valid() && count($answer) < $limit) {
            if ($iterator->isFile()) {
                $answer[] = array(
                    'fileName' => $iterator->getFilename(),
                    'dir' => FILE_REPOSITORY_DIR,
                    'file' => FILE_REPOSITORY_DIR.$iterator->getFilename(),
                    'preview' => $this->createPreview(FILE_REPOSITORY_DIR.$iterator->getFilename()),
                    'modified' => $iterator->getMTime()
                );
//                echo $iterator->getMTime()."\n";
            }
            $iterator->next();
        }
        return $answer;
    }

    /**
     * Get preview file for file browser
     * @param string $file
     * @return string
     */
    private function createPreview($file)
    {
        $pathInfo = pathinfo($file);
        $ext = strtolower(isset($pathInfo['extension']) ? $pathInfo['extension'] : '');
        $baseName = $pathInfo['basename'];

        switch($ext) {
            case 'jpg':
            case 'jpeg':
            case 'gif':
            case 'png':
                $reflectionService = ReflectionService::instance();
                $transform = new Transform\ImageFit(100, 100, null, TRUE);
                $reflection = $reflectionService->getReflection($file, $baseName, $transform);
                return $reflection;
                break;
            default:
                return MODULE_DIR.'administrator/repository/public/admin/icons/general.png';
        }
    }

    
    
}