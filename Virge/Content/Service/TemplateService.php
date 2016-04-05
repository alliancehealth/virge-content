<?php
namespace Virge\Content\Service;

use Virge\Content\Component\Twig\ConfigTwigExtension;

use Virge\Core\Config;
use Virge\Virge;

/**
 * 
 */
class TemplateService {
    
    /**
     *
     * @var \Twig_Environment 
     */
    protected $twig;
    
    /**
     * 
     * @param string $path
     * @param array $vars
     * @return type
     */
    public function render($path, $vars = []){
        return $this->getTwig()->render($path, $vars);
    }
    
    /**
     * 
     * @global type $reactor
     * @return \Twig_Environment
     */
    protected function getTwig() {
        if(isset($this->twig)) {
            return $this->twig;
        }
        $loader = new \Twig_Loader_Filesystem([]);
        
        global $reactor;
        foreach($reactor->getCapsules() as $capsule) {
            
            $namespaceData = explode('\\', get_class($capsule));
            
            array_pop($namespaceData);
            $namespace = implode('\\', $namespaceData);
            
            $path = Config::path($namespace);
            
            $namespace = str_replace("\\", '', $namespace);
            
            if(is_dir($path. 'resources/template/')){
                //add paths for each registered capsule
                $loader->addPath($path. 'resources/template/', $namespace);
            }
        }
        
        $twigConfig = [];
        
        if(Config::get('app', 'cache')) {
            $twigConfig['cache']    =   Config::get('base_path') . 'storage/cache';
        }
        
        $this->twig = new \Twig_Environment($loader, $twigConfig);
        
        $this->twig->addExtension(new ConfigTwigExtension());
        
        $this->setupFunctions($this->twig);
        $this->setupFilters($this->twig);
        
        return $this->twig;
    }
    
    protected function setupFilters(\Twig_Environment $twig) {
        
        $twig->addFilter(new \Twig_SimpleFilter('callback', function($input, $serviceId, $method) {
            if(func_num_args() > 3) {
                $params = array_slice(func_get_args(), 3);
            } else {
                $params = [];
            }
            
            array_unshift($params, $input);
            return call_user_func_array([Virge::service($serviceId), $method], $params);
        }));
    }
    
    protected function setupFunctions(\Twig_Environment $twig) {
        $twig->addFunction(new \Twig_SimpleFunction('url', function ($path = '') {
            $url = Config::get('app', 'url') ? Config::get('app', 'url') : '/';
            return $url . $path;
        }));
        
        $bowerService = $this->getBowerService();
        
        $twig->addFunction(new \Twig_SimpleFunction('bower', function ($path = '') use($bowerService){
            return $bowerService->getPathToAsset($path);
        }));
        
        $twig->addGlobal('jsx', []);
        
        $assetService = $this->getAssetService();
        $twig->addFunction(new \Twig_SimpleFunction('jsx', function ($path = '') use($assetService, $twig){
            $assetService->getJsx($path);
            return;
        }, [
            'is_safe'   =>      ['html']
        ]));
        
        $assetService = $this->getAssetService();
        $twig->addFunction(new \Twig_SimpleFunction('js', function ($path = '') use($assetService, $twig){
            return $assetService->getJs($path);
        }, [
            'is_safe'   =>      ['html']
        ]));
        
        $twig->addFunction(new \Twig_SimpleFunction('include_jsx', function ($path = '') use($assetService, $twig){
            return $assetService->renderJsx();
        }, [
            'is_safe'   =>      ['html']
        ]));
        
        $twig->addFunction(new \Twig_SimpleFunction('include_js', function ($path = '') use($assetService, $twig){
            return $assetService->renderJs();
        }, [
            'is_safe'   =>      ['html']
        ]));
        
        $blockService = Virge::service(BlockService::class);
        
        $twig->addFunction(new \Twig_SimpleFunction('render', function ($blockName) use($blockService, $twig){
            return $blockService->render($blockName);
        }, [
            'is_safe'   =>      ['html']
        ]));
    }
    
    /**
     * @return AssetService
     */
    protected function getAssetService() {
        return Virge::service(AssetService::class);
    }
    
    /**
     * @return BowerService
     */
    protected function getBowerService() {
        return Virge::service(BowerService::class);
    }
}