@php
    $tools = $getTools();
    $bubbleMenuTools = $getBubbleMenuTools();
    $floatingMenuTools = $getFloatingMenuTools();
    $statePath = $getStatePath();
    $isDisabled = $isDisabled();
    $blocks = $getBlocks();
    $mergeTags = $getMergeTags();
    $options = $getOptions();
    $shouldSupportBlocks = $shouldSupportBlocks();
    $shouldShowMergeTagsInBlocksPanel = $shouldShowMergeTagsInBlocksPanel();
    $customDocument = $getCustomDocument();
    $nodePlaceholders = $getNodePlaceholders();
    $showOnlyCurrentPlaceholder = $getShowOnlyCurrentPlaceholder();
    // Mentions
    $mentionItems = $getMentionItems();
    $emptyMentionItemsMessage = $getEmptyMentionItemsMessage();
    $mentionItemsPlaceholder = $getMentionItemsPlaceholder();
    $mentionItemsLoading = $getMentionItemsLoading();
    $getMentionItemsUsingEnabled = $getMentionItemsUsingEnabled();
    $maxMentionItems = $getMaxMentionItems();
    $mentionTrigger = $getMentionTrigger();
    $mentionDebounce = $getMentionDebounce();
    $mentionSearchStrategy = $getMentionSearchStrategy();
    $tippyPlacement = $getTippyPlacement();


@endphp

<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div>
        <div class="flex gap-3">
        {{-- Add Select Tree --}}
            <link
                rel="stylesheet"
                href="https://cdn.jsdelivr.net/npm/treeselectjs@0.13.1/dist/treeselectjs.css"
            />
            <div id="category-tree" class="form-control"></div>
            <input type="hidden" name="category_ids" id="category_ids" value="">


        </div>
        <div class="flex gap-3">
            <div class="flex-1">
                <div
                    @class([
                        'tiptap-editor rounded-md relative text-gray-950 bg-white shadow-sm ring-1 dark:bg-white/5 dark:text-white',
                        'ring-gray-950/10 dark:ring-white/20' => ! $errors->has($statePath),
                        'ring-danger-600 dark:ring-danger-600' => $errors->has($statePath),
                    ])
                    x-data="{}"
                    @if (! $shouldDisableStylesheet())
                        x-load-css="[@js(\Filament\Support\Facades\FilamentAsset::getStyleHref('tiptap', 'akramghaleb/tiptap-editor'))]"
                    @endif
                >
                    <div
                        wire:ignore
                        x-load
                        x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('tiptap', 'akramghaleb/tiptap-editor') }}"
                        class="relative z-0 tiptap-wrapper rounded-md bg-white dark:bg-gray-900 focus-within:ring focus-within:ring-primary-500 focus-within:z-10"
                        x-bind:class="{ 'tiptap-fullscreen': fullScreenMode }"
                        x-data="tiptap({
                            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$statePath}')", isOptimisticallyLive: true) }},
                            statePath: '{{ $statePath }}',
                            tools: @js($tools),
                            disabled: @js($isDisabled),
                            locale: '{{ app()->getLocale() }}',
                            bubbleMenuTools: @js($bubbleMenuTools),
                            floatingMenuTools: @js($floatingMenuTools),
                            placeholder: @js($getPlaceholder()),
                            mergeTags: @js($mergeTags),
                            customDocument: @js($customDocument),
                            nodePlaceholders: @js($nodePlaceholders),
                            showOnlyCurrentPlaceholder: @js($showOnlyCurrentPlaceholder),
                            debounce: @js($getLiveDebounce()),
                            mentionItems: @js($mentionItems),
                            emptyMentionItemsMessage: @js($emptyMentionItemsMessage),
                            mentionItemsPlaceholder: @js($mentionItemsPlaceholder),
                            mentionItemsLoading: @js($mentionItemsLoading),
                            maxMentionItems: @js($maxMentionItems),
                            mentionTrigger: @js($mentionTrigger),
                            livewireId: @js($this->getId()),
                            getMentionItemsUsingEnabled: @js($getMentionItemsUsingEnabled),
                            getSearchResultsUsing: async (search) => {
                              return await $wire.getMentionsItems(@js($statePath), search)
                            },
                            mentionDebounce: @js($mentionDebounce),
                            tippyPlacement: @js($tippyPlacement),
                            mentionSearchStrategy: @js($mentionSearchStrategy),
                            linkProtocols: @js(config('filament-tiptap-editor.link_protocols')),
                        })"
                        x-init="$nextTick(() => { init(); window.tiptapEditor = editor(); })"
                        x-on:click.away="blur()"
                        x-on:keydown.escape="fullScreenMode = false"
                        x-on:insert-content.window="insertContent($event)"
                        x-on:unset-link.window="$event.detail.statePath === '{{ $statePath }}' ? unsetLink() : null"
                        x-on:update-editor-content.window="updateEditorContent($event)"
                        x-on:refresh-tiptap-editors.window="refreshEditorContent()"
                        x-on:dragged-block.stop="$wire.mountFormComponentAction('{{ $statePath }}', 'insertBlock', {
                            type: $event.detail.type,
                            coordinates: $event.detail.coordinates,
                        })"
                        x-on:dragged-merge-tag.stop="insertMergeTag($event)"
                        x-on:insert-block.window="insertBlock($event)"
                        x-on:update-block.window="updateBlock($event)"
                        x-on:open-block-settings.window="openBlockSettings($event)"
                        x-on:delete-block.window="deleteBlock($event)"
                        x-on:locale-change.window="updateLocale($event)"
                        x-trap.noscroll="fullScreenMode"
                    >
                        @if (! $isDisabled && ! $isToolbarMenusDisabled() && $tools)
                            <template x-if="editor()">
                                <div>
                                    <button type="button" x-on:click="editor().chain().focus()" class="z-20 rounded sr-only focus:not-sr-only focus:absolute focus:py-1 focus:px-3 focus:bg-white focus:text-gray-900">{{ trans('filament-tiptap-editor::editor.skip_toolbar') }}</button>

                                    <div class="tiptap-toolbar text-gray-800 border-b border-gray-950/10 bg-gray-50 divide-x divide-gray-950/10 rounded-t-md z-[1] relative flex flex-col md:flex-row dark:text-gray-300 dark:border-white/20 dark:bg-gray-950 dark:divide-white/20">

                                        <div class="flex flex-wrap items-center flex-1 gap-1 p-1 tiptap-toolbar-left">
                                            <x-dynamic-component component="filament-tiptap-editor::tools.paragraph" :state-path="$statePath" />
                                            @foreach ($tools as $tool)
                                                @if ($tool === '|')
                                                    <div class="border-l border-gray-950/10 dark:border-white/20 h-5"></div>
                                                @elseif ($tool === '-')
                                                    <div class="border-t border-gray-950/10 dark:border-white/20 w-full"></div>
                                                @elseif (is_array($tool))
                                                    @if(array_key_exists('button', $tool) && !is_null($tool['button']))
                                                    <x-dynamic-component component="{{ $tool['button'] }}" :state-path="$statePath" />
                                                    @endif
                                                @elseif ($tool === 'blocks')
                                                    @if ($blocks && $shouldSupportBlocks)
                                                        <x-filament-tiptap-editor::tools.blocks :blocks="$blocks" :state-path="$statePath" />
                                                    @endif
                                                @else
                                                    <x-dynamic-component component="filament-tiptap-editor::tools.{{ $tool }}" :state-path="$statePath" :editor="$field" />
                                                @endif
                                            @endforeach
                                        </div>

                                        <div class="flex flex-wrap items-start self-stretch gap-1 p-1 pl-2 tiptap-toolbar-right">
                                            <x-filament-tiptap-editor::tools.undo />
                                            <x-filament-tiptap-editor::tools.redo />
                                            <x-filament-tiptap-editor::tools.erase />
                                            <x-filament-tiptap-editor::tools.fullscreen />
                                        </div>
                                    </div>
                                </div>
                            </template>
                        @endif

                        @if (! $isDisabled && ! $isBubbleMenusDisabled())
                        <template x-if="editor()">
                            <div>
                                <div x-ref="bubbleMenu" class="tiptap-editor-bubble-menu-wrapper">
                                    <x-filament-tiptap-editor::menus.default-bubble-menu :state-path="$statePath" :tools="$bubbleMenuTools"/>
                                    <x-filament-tiptap-editor::menus.link-bubble-menu :state-path="$statePath" :tools="$tools"/>
                                    <x-filament-tiptap-editor::menus.image-bubble-menu :state-path="$statePath" :tools="$tools"/>
                                    <x-filament-tiptap-editor::menus.table-bubble-menu :state-path="$statePath" :tools="$tools"/>
                                </div>
                            </div>
                        </template>
                        @endif

                        @if (! $isFloatingMenusDisabled() && filled($floatingMenuTools))
                        <template x-if="editor()">
                            <div>
                                <div x-ref="floatingMenu" class="tiptap-editor-floating-menu-wrapper">
                                    <x-filament-tiptap-editor::menus.default-floating-menu
                                        :state-path="$statePath"
                                        :tools="$floatingMenuTools"
                                        :blocks="$blocks"
                                        :should-support-blocks="$shouldSupportBlocks"
                                        :editor="$field"
                                    />
                                </div>
                            </div>
                        </template>
                        @endif

                        <div class="flex h-full">
                            <div @class([
                                'tiptap-prosemirror-wrapper mx-auto w-full max-h-[40rem] min-h-[56px] h-auto overflow-y-scroll overflow-x-hidden rounded-b-md',
                                match ($getMaxContentWidth()) {
                                    'sm' => 'prosemirror-w-sm',
                                    'md' => 'prosemirror-w-md',
                                    'lg' => 'prosemirror-w-lg',
                                    'xl' => 'prosemirror-w-xl',
                                    '2xl' => 'prosemirror-w-2xl',
                                    '3xl' => 'prosemirror-w-3xl',
                                    '4xl' => 'prosemirror-w-4xl',
                                    '6xl' => 'prosemirror-w-6xl',
                                    '7xl' => 'prosemirror-w-7xl',
                                    'full' => 'prosemirror-w-none',
                                    default => 'prosemirror-w-5xl',
                                }
                            ])>
                                {{--Main Content is here--}}
                                <div
                                    x-ref="element"
                                    {{ $getExtraInputAttributeBag()->class([
                                        'tiptap-content min-h-full'
                                    ]) }}
                                ></div>
                            </div>

                            @if ((! $isDisabled) && ($shouldSupportBlocks || ($shouldShowMergeTagsInBlocksPanel && filled($mergeTags))))
                                <div
                                    x-data="{
                                        isCollapsed: @js($shouldCollapseBlocksPanel()),
                                    }"
                                    class="hidden shrink-0 space-y-2 max-w-sm md:flex flex-col h-full"
                                    x-bind:class="{
                                        'bg-gray-50 dark:bg-gray-950/20': ! isCollapsed,
                                        'h-full': ! isCollapsed && fullScreenMode,
                                        'px-2': ! fullScreenMode,
                                        'px-3': fullScreenMode
                                    }"
                                >
                                    <div class="flex items-center mt-2">
                                        <p class="text-xs font-bold" x-show="! isCollapsed">
                                            @if ($shouldSupportBlocks)
                                                {{ trans('filament-tiptap-editor::editor.blocks.panel') }}
                                            @else
                                                {{ trans('filament-tiptap-editor::editor.merge_tags.panel') }}
                                            @endif
                                        </p>

                                        <button x-on:click="isCollapsed = false" x-show="isCollapsed" x-cloak type="button" class="ml-auto">
                                            <x-filament::icon
                                                icon="heroicon-m-bars-3"
                                                class="w-5 h-5"
                                            />
                                        </button>

                                        <button x-on:click="isCollapsed = true" x-show="! isCollapsed" type="button" class="ml-auto">
                                            <x-filament::icon
                                                icon="heroicon-m-x-mark"
                                                class="w-5 h-5"
                                            />
                                        </button>
                                    </div>

                                    <div x-show="! isCollapsed" class="overflow-y-auto space-y-1 h-full pb-2">
                                        @if ($shouldShowMergeTagsInBlocksPanel)
                                            @foreach ($mergeTags as $mergeTag)
                                                <div
                                                    draggable="true"
                                                    x-on:dragstart="$event?.dataTransfer?.setData('mergeTag', @js($mergeTag))"
                                                    class="cursor-move grid-col-1 flex items-center gap-2 rounded border text-xs px-3 py-2 bg-white dark:bg-gray-800 dark:border-gray-700"
                                                >
                                                    &lcub;&lcub; {{ $mergeTag }} &rcub;&rcub;
                                                </div>
                                            @endforeach
                                        @endif

                                        @foreach ($blocks as $block)
                                            <div
                                                draggable="true"
                                                x-on:dragstart="$event?.dataTransfer?.setData('block', @js($block->getIdentifier()))"
                                                class="cursor-move grid-col-1 flex items-center gap-2 rounded border text-xs px-3 py-2 bg-white dark:bg-gray-800 dark:border-gray-700"
                                            >
                                                @if ($block->getIcon())
                                                    <x-filament::icon
                                                        :icon="$block->getIcon()"
                                                        class="h-5 w-5"
                                                    />
                                                @endif

                                                {{ $block->getLabel() }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/treeselectjs@0.13.1/dist/treeselectjs.umd.js"></script>

    <script>
        // dummy static options
        const options = @json($options);

        function findName(options, val) {
            // inner recursive lookup
            function recurse(nodes) {
                for (const node of nodes) {
                    if (node.value === val) {
                        return node.name;
                    }
                    if (node.children && node.children.length) {
                        const found = recurse(node.children);
                        if (found !== null) {
                            return found;
                        }
                    }
                }
                return null;
            }

            const result = recurse(options);
            return result === null ? val : result;
        }



        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('category-tree');

            // if using CDN, Treeselect is on window; otherwise import it above
            const Treeselect = window.Treeselect || require('treeselectjs').default;

            const tree = new Treeselect({
                parentHtmlContainer: container,
                options: options,
                value: [],             // pre-selected values, e.g. [4]
                placeholder: 'Search…'
            });

            // capture selections into the hidden input
            tree.srcElement.addEventListener('input', (e) => {
                const selected = e.detail;
                document.getElementById('category_ids').value = selected;

                // console.log('Selected values:', e);

                const tiptapEl = document.querySelector('.ProseMirror');

                // test
                //window.tiptapEditor.chain().focus().insertContent("<h1>Akram</h1>").run();


                if (selected.length > 1) {
                    const table = document.createElement('table');
                    const thead = document.createElement('thead');
                    const headerRow = document.createElement('tr');

                    selected.forEach(id => {
                        const th = document.createElement('th');
                        th.textContent = findName(options, id);
                        //th.textContent = id;
                        headerRow.appendChild(th);
                    });

                    thead.appendChild(headerRow);
                    table.appendChild(thead);

                    const tbody = document.createElement('tbody');
                    const bodyRow = document.createElement('tr');

                    selected.forEach(id => {
                        const td = document.createElement('td');
                        const span = document.createElement('span');

                        span.setAttribute('data-type', 'mergeTag');
                        span.setAttribute('data-id', id);
                        span.textContent = id;

                        td.appendChild(span);
                        bodyRow.appendChild(td);
                    });

                    tbody.appendChild(bodyRow);
                    table.appendChild(tbody);
                    //tiptapEl.appendChild(table);
                    window.tiptapEditor.chain().focus().insertContent(table.outerHTML).run();
                }else{
                    selected.forEach(id => {
                        const span = document.createElement('span');

                        span.setAttribute('data-type', 'mergeTag');
                        span.setAttribute('data-id', id);
                        span.textContent = id;

                        //tiptapEl.appendChild(span);
                        window.tiptapEditor.chain().focus().insertContent(span.outerHTML).run();
                    });
                }

                tree.updateValue([]);
                tree.mount();
            });
        });
    </script>
</x-dynamic-component>
