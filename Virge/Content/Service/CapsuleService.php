<?php
namespace Virge\Content\Service;

use Virge\Core\BaseService;
use Virge\Core\Config;

/**
 * 
 */
class CapsuleService extends BaseService
{
    /**
     * @return self
     */
    public static function instance()
    {
        return self::service(self::class);
    }
    
    
    public function getPathFromShortName($shortName)
    {
        $capsules = $this->getCapsuleData();
        
        return isset($capsules[$shortName]) ? $capsules[$shortName]['path'] : null;
    }
    
    public function getDataFromShortName($shortName)
    {
        $capsules = $this->getCapsuleData();
        
        return isset($capsules[$shortName]) ? $capsules[$shortName] : null;
    }
    
    protected function getCapsuleData()
    {
        if(null !== ($capsules = CacheService::instance()->get('workshift_capsules'))) {
            return $capsules;
        }
        
        global $reactor;
        $capsules = [];
        foreach($reactor->getCapsules() as $capsule) {
            
            $namespaceData = explode('\\', $className = get_class($capsule));
            
            array_pop($namespaceData);
            $classStart = implode('\\', $namespaceData);
            
            $path = Config::path($classStart);
            
            $namespace = str_replace("\\", '', $classStart);
            
            
            $capsules[$namespace] = [
                'className'     =>  $className,
                'classStart'    =>  $classStart,
                'path'          =>  $path
            ];
        }
        
        return $capsules;
    }
}