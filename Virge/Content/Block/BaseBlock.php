<?php
namespace Virge\Content\Block;

use Virge\Virge;
use Virge\Content\Service\TemplateService;

/**
 * 
 */

abstract class BaseBlock
{
    public abstract function render();
    
    /**
     * @return TemplateService
     */
    protected function getTemplateService()
    {
        return Virge::service(TemplateService::class);
    }
}