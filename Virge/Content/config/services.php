<?php
use Virge\Content\Service\AssetService;
use Virge\Content\Service\BowerService;
use Virge\Content\Service\BlockService;
use Virge\Content\Service\CapsuleService;
use Virge\Content\Service\TemplateService;
use Virge\Virge;

/**
 * 
 * @author Michael Kramer
 */
Virge::registerService(AssetService::class, new AssetService());
Virge::registerService(TemplateService::class, new TemplateService());
Virge::registerService(BowerService::class, new BowerService());
Virge::registerService(BlockService::class, new BlockService());
Virge::registerService(CapsuleService::class, new CapsuleService());