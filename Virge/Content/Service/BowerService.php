<?php
namespace Virge\Content\Service;

use Virge\Core\Config;

/**
 * 
 * @author Michael Kramer
 */
class BowerService {
    
    /**
     * If the js exists in the cache, use that, otherwise pull over the cache
     * @param type $name
     */
    public function getPathToAsset($name) {
        
        $url = Config::get('app', 'url') ? Config::get('app', 'url') : '/';
        
        return $url . 'bower_components/' . $name;
    }
}