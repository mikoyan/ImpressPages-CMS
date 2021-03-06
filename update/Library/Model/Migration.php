<?php
/**
 * @package ImpressPages
 * @copyright   Copyright (C) 2011 ImpressPages LTD.
 *
 */

namespace IpUpdate\Library\Model;


class Migration
{
    private $scripts;

    public function __construct()
    {
    }
    
    /**
     * @param string $sourceVersion
     * @return IpUpdate\Library\Migration\General
     */
    public function getDestinationScript($sourceVersion) 
    {
        $scripts = $this->getScriptsFromVersion($sourceVersion);
        return array_pop($scripts);
    }
    
    /**
     * 
     * @param string $fromVersion
     * @return \IpUpdate\Library\Migration\General[]
     */
    public function getScriptsFromVersion($fromVersion){
        $answer = array();
        $currentScript = false;
        while($currentScript = $this->getScriptFromVersion($fromVersion)){
            $answer[] = $currentScript;
            $fromVersion = $currentScript->getDestinationVersion();
        }

        return $answer;
    }

    public function getScriptFromVersion($fromVersion){
        $answer = false;

        foreach ($this->getScripts() as $script) {
            if ($script->getSourceVersion() == $fromVersion){
                $answer = $script;
            }
        }

        return $answer;
    }
    
    /**
     * 
     * @param string $version
     * @return \IpUpdate\Library\Migration\General
     */
    public function getScriptToVersion($version)
    {
        $scripts = $this->getScripts();
        foreach ($scripts as $script) {
            if ($script->getDestinationVersion() == $version) {
                return $script;
            }
        }
        throw new \IpUpdate\Library\UpdateException("Can't find script to ".$version, \IpUpdate\Library\UpdateException::UNKNOWN);
    }

    
    /**
     * @return \IpUpdate\Library\Migration\General[]
     */
    private function getScripts()
    {
        if (!$this->scripts) {
            $scripts = array();
            
            $migrationDirListing = scandir(IUL_BASE_DIR.IUL_MIGRATION_DIR);
            foreach ($migrationDirListing as $dir) {
                if ($dir == '.' || $dir == '..') {
                    continue;
                }
                if (is_dir(IUL_BASE_DIR.IUL_MIGRATION_DIR.$dir) && file_exists(IUL_BASE_DIR.IUL_MIGRATION_DIR.$dir.'/Script.php')) {
                    $scriptName = 'IpUpdate\\Library\\Migration\\'.$dir.'\\Script';
                    $scripts[] = new $scriptName();
                }
            } 
            $this->scripts = $scripts;
        }
        return $this->scripts;
    }


}





