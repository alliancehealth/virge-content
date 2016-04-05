<?php
namespace Virge\Content\Component\Twig;

use Virge\Core\Config;

/**
 * 
 */
class ConfigTwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'config';
    }
    
    public function getGlobals()
    {
        return [
            'config'    =>  [
                'app'   =>  Config::get('app'),
            ],
        ];
    }
}