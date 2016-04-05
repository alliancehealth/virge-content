<?php
namespace Virge\Content\Service;

/**
 * 
 */
class BlockService
{
    public function render($blockName, ...$args)
    {
        $blockData = explode('::', $blockName);
        
        $blockClass = $blockData[1];
        
        $capsuleData = CapsuleService::instance()->getDataFromShortName($blockData[0]);
        
        $className = $capsuleData['classStart'] . '\\Block\\' . $blockClass;
        
        $block = new $className($args);
        
        return $block->render();
    }
}