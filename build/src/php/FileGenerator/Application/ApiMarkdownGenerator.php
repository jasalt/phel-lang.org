<?php

declare(strict_types=1);

namespace PhelDocBuild\FileGenerator\Application;

use Phel\Api\ApiFacadeInterface;
use Phel\Api\Transfer\PhelFunction;

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

        /** @var list<PhelFunction> $phelFns */
        $phelFns = $this->apiFacade->getPhelFunctions();

        $groupedByNamespace = [];
        foreach ($phelFns as $fn) {
            $groupedByNamespace[$fn->namespace()][] = $fn;
        }

        foreach ($groupedByNamespace as $namespace => $fns) {

            $result[] = "";
            $result[] = "---";
            $result[] = "";
            $result[] = "## `{$namespace}`";

            foreach ($fns as $fn) {
                $result[] = "### `{$fn->nameWithNamespace()}`";  // NOTE: required for TOC rendering

                $result[] = '{{ api_listing_entry(
                  fn_name="' . $fn->nameWithNamespace() . '"
                  fn_signature="' . $fn->signature() . '"
                  github_url="' . $fn->githubUrl() . '"
                  doc_url="' . $fn->docUrl() . '"
                  docstring="' . $fn->description() . '") }}';
            }
        }

        return $result;
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
