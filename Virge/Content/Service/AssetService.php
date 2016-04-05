<?php
namespace Virge\Content\Service;

use Virge\Core\Config;
use Virge\IO;
use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
class AssetService {
    
    protected $required = [];
    
    protected $urls = [];
    
    protected $js = [];
    
    /**
     * Load a JSX Script
     * @param string $script
     */
    public function getJsx($script) {
        $this->urls = array_merge($this->getJsUrl($script), $this->urls);
    }
    
    /**
     * Load JS
     * @param string $script
     */
    public function getJs($script) {
        //$this->js = array_merge($this->js, $this->getJsUrl($script));
        
        return $this->getTemplateService()->render('@WorkshiftLayout/component/asset/js.html.twig', [
            'scripts'  =>    $this->getJsUrl($script),
        ]);
    }
    
    public function renderJsx() {
        
        return $this->getTemplateService()->render('@WorkshiftLayout/component/asset/jsx.html.twig', [
            'scripts'  =>    array_unique($this->urls),
        ]);
    }
    
    public function renderJs() {
        return null;
        return $this->getTemplateService()->render('@WorkshiftLayout/component/asset/js.html.twig', [
            'scripts'  =>    array_unique($this->js),
        ]);
    }
    
    protected function getJsUrl($script) {

        $this->required[] = $script;
        $publicPath = IO::publicCache('js') . '/';
        IO::checkWriteable($publicPath);
        //move scripts to a public folder
        
        $filename = md5($script) . '.js';
        //if(!is_file($publicPath . $filename)) {
        
        if(!is_file(Config::path($script))){
            return [];
        }
        
        $contents = file_get_contents(Config::path($script));
        
        $urls = [];
        
        $required = $this->getRequired($contents);
        foreach($required as $additional) {
            if(!in_array($additional, $this->required)) {
                
                $urls = array_merge($urls, $this->getJsUrl($additional));
            }
        }
        
        file_put_contents($publicPath . $filename, $contents);
        $urls[] = 'cache/js/' . $filename;
        return array_unique($urls);
    }
    
    /**
     * @return TemplateService
     */
    protected function getTemplateService() {
        return Virge::service(TemplateService::class);
    }
    
    /**
     * Parse annotations
     *
     * @param  string $docblock
     * @return array parsed annotations params
     */
    protected static function getRequired($docblock)
    {
        $requires = array();
        $docblock = substr($docblock, 3, -2);
        if (preg_match_all('/@(?<name>[Require]+)[\s\t]*\((?<args>.*)\)[\s\t]*\r?$/m', $docblock, $matches)) {
            $numMatches = count($matches[0]);
            for ($i = 0; $i < $numMatches; ++$i) {
                // annotations has arguments
                if (isset($matches['args'][$i])) {
                    $value = trim($matches['args'][$i]);
                } else {
                    $value = array();
                }
                
                
                $requires[] = $value;
            }
        }
        return $requires;
    }
}