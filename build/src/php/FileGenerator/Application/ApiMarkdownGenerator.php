<?php

declare(strict_types=1);

namespace PhelDocBuild\FileGenerator\Application;

use Phel\Api\ApiFacadeInterface;

final readonly class ApiMarkdownGenerator
{
    public function __construct(
        private ApiFacadeInterface $apiFacade
    ) {
    }

    /**
     * @return list<string>
     */
    public function generate(): array
    {
        $result = $this->zolaHeaders();
        $groupedPhelFns = $this->apiFacade->getPhelFunctions();

        foreach ($groupedPhelFns as $fn) {
            $result[] = "## `{$fn->fnName()}`";
            $result[] = "**Namespace:** `" . $this->extractNamespace($fn->fnName()) . "`";
            $result[] = $fn->doc();
            if ($fn->url() !== '') {
                $result[] = sprintf('Read more [here](%s).', $fn->url());
            }
        }

        return $result;
    }

    /**
     * Extract namespace from function name
     */
    private function extractNamespace(string $fnName): string
    {
        $parts = explode('/', $fnName);
        if (count($parts) > 1) {
            array_pop($parts); // Remove the function name part
            return implode('/', $parts); // Join remaining parts as namespace
        }
        
        return 'core'; // Default namespace
    }

    /**
     * @return list<string>
     */
    private function zolaHeaders(): array
    {
        $result = [];
        $result[] = '+++';
        $result[] = 'title = "API"';
        $result[] = 'weight = 110';
        $result[] = 'template = "page-api.html"';
        $result[] = 'aliases = [ "/api" ]';
        $result[] = '+++';
        $result[] = '';

        return $result;
    }
}
