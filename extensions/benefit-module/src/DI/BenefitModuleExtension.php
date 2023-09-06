<?php

namespace Crm\BenefitModule\DI;

use Contributte\Translation\DI\TranslationProviderInterface;
use Nette\DI\CompilerExtension;

class BenefitModuleExtension extends CompilerExtension implements TranslationProviderInterface
{
    public function loadConfiguration(): void
    {
        // load services from config and register them to Nette\DI Container
        $this->compiler->loadDefinitionsFromConfig(
            $this->loadFromFile(__DIR__ . '/../config/config.neon')['services']
        );
    }

    public function getTranslationResources(): array
    {
        return [__DIR__ . '/../lang/'];
    }
}
