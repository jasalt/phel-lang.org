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

        /** @var list<PhelFunction> $groupedPhelFns */
        $groupedPhelFns = $this->apiFacade->getPhelFunctions();

        // foreach ($groupedPhelFns as $fn) {
        //     $result[] = "## `{$fn->name()}`";
        //     $result[] = "<small><strong>Namespace</strong> `{$fn->namespace()}`</small>";
        //     $result[] = $fn->doc();
        //     if ($fn->githubUrl() !== '') {
        //         $result[] = sprintf('<small>[[View source](%s)]</small>', $fn->githubUrl());
        //     }elseif ($fn->docUrl() !== '') {
        //         $result[] = sprintf('<small>[[Read more](%s)]</small>', $fn->docUrl());
        //     }
        // }

		// TODO generated markdown fails as $fn->doc() returns markdown ``` breaking shortcode function call (?)
		foreach ($groupedPhelFns as $fn) {
			echo $fn->doc();
            $result[] = sprintf(
                '{{ api_listing_entry(fn_name="%s", namespace="%s", doc="%s", %s, %s) }}',
                $fn->name(),
                $fn->namespace(),
                str_replace('"', '\"', $fn->doc()),
                $fn->githubUrl() !== '' ? ', github_url="' . $fn->githubUrl() . '"' : '',
                $fn->docUrl() !== '' ? ', doc_url="' . $fn->docUrl() . '"' : ''
            );
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
