<?php

namespace FilamentTiptapEditor\Tables\Columns;

use Filament\Tables\Columns\Column;

class TiptapColumn extends Column
{
    protected string $view = 'filament-tiptap-editor::tables.columns.tiptap-column';

    /** @var array<string,string> */
    protected array $tagMap = [];

    /**
     * Supply your merge-tag replacements.
     */
    public function tagMap(array $map): static
    {
        $this->tagMap = $map;

        return $this;
    }

    /**
     * Make the tagMap available in the view (if you need it),
     * and also expose our helper.
     */
    public function getViewData(): array
    {
        return array_merge(parent::getViewData(), [
            'tagMap'       => $this->tagMap,
            'renderedHtml' => $this->renderHtml(),
        ]);
    }

    /**
     * Actually run the converter & swap your merge tags.
     */
    public function renderHtml(): string
    {
        $html = $this->getState();

        return preg_replace_callback(
            '/<span[^>]*data-type=["\']mergeTag["\'][^>]*data-id=["\'](?P<id>[^"\']+)["\'][^>]*>.*?<\/span>/',
            function (array $matches): string {
                $id = $matches['id'];
                return $this->tagMap[$id] ?? '';
            },
            $html
        );
    }
}
