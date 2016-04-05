<?php
namespace Virge\Content\Controller;
use Virge\Content\Service\TemplateService;

use Virge\Virge;

/**
 * Base controller for front-end stuff to extend from, allows easy access
 * to the templating service (twig)
 */
class BaseController {
    
    protected $twig;
    
    public function render($name, $data = []) {
        return $this->getTemplating()->render($name, $data);
    }
    
    /**
     * @return TemplateService
     */
    protected function getTemplating() {
        return Virge::service(TemplateService::class);
    }
}