<x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
    @php
        $containers = $getChildComponentContainers();//$containers = $getItems();
        $addAction = $getAction($getAddActionName());
        $cloneAction = $getAction($getCloneActionName());
        $deleteAction = $getAction($getDeleteActionName());
        $reorderAction = $getAction($getReorderActionName());

        $isAddable = $isAddable();
        $isCloneable = $isCloneable();
        $isDeletable = $isDeletable();
        $isReorderable = $isReorderable();
        // $isReorderableWithButtons = $isReorderableWithButtons();
        $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop();

        $label = $getLabel();
    @endphp
 
    <div
        x-data="{
            state: $wire.$entangle('{{ $getStatePath() }}'),
            activeTab: null,
            init() {
                if (! this.activeTab && this.state) {
                    let keys = Object.keys(this.state);
                    if (keys.length > 0) { this.activeTab = keys[0]; }
                }
                $watch('state', (value, oldValue) => {
                    if (value && oldValue) {
                        let newKeys = Object.keys(value);
                        let oldKeys = Object.keys(oldValue);
                        if (newKeys.length > oldKeys.length) {
                            let diff = newKeys.filter(x => !oldKeys.includes(x));
                            if(diff.length > 0) { this.activeTab = diff[0]; }
                        }else{
                            //fall back to active the first tab after delete
                            this.activeTab = newKeys[0];
                        }
                    }
                        
                     
                })
            },

            isActive(uuid) { return this.activeTab === uuid; },
            setActive(uuid) { this.activeTab = uuid; }
        }"
        class="it-tabs-repeater"
    >
    <div class="it-tabs-repeater-header">
            @if (count($containers))
            <nav 
                class="it-tabs"
                aria-label="Tabs"
                x-sortable
                x-on:end.stop="
                    let tab_items = Array.from($event.target.children)
                    let items =tab_items.map(el => el.getAttribute('x-sortable-item'))
                        .filter(Boolean);
                    
                    $wire.mountAction('reorder', { items: items }, { schemaComponent: '{{ $getKey() }}' });
                "
            >

                @foreach ($containers as $uuid => $item)
                    {{-- Resolve the Icon --}}
                    @php
                        $icon = $item->getParentComponent()->getItemIcon($uuid);
                    @endphp

                    <div x-bind:class="{
                            'it-tabs-item':true,
                            'it-active': isActive('{{ $uuid }}'),
                        }" 
                        aria-label="Tabs"
                        wire:key="{{ $item->getLivewireKey() }}.item"
                        x-sortable-item="{{ $uuid }}"
                        x-sortable-handle
                    >
                        <button type="button"
                            class="it-tabs-item-btn"
                            x-sort:ignore
                            x-on:click="setActive('{{ $uuid }}')"
                        >
                            @if ($icon)
                                {{ \Filament\Support\generate_icon_html($icon) }}
                            @endif

                            <span class="it-tabs-item-label">
                                {{ $item->getParentComponent()->getItemLabel($uuid) ?? "$label " . $loop->iteration }}
                            </span>

                        </button>

                        @if ($cloneAction && $cloneAction->isVisible())
                        <div class="it-tabs-clone-btn">
                            {{ $cloneAction(['item' => $uuid]) }}
                        </div>
                        @endif
                        @if ($isReorderableWithDragAndDrop)
                        <div
                            x-sortable-handle
                            class="it-tabs-reorder-btn"
                        >
                            {{ $reorderAction }}
                        </div>
                        @endif
                        
                        @if($isDeletable && $deleteAction->isVisible())
                        <div class="it-tabs-delete-btn">
                            {{ $deleteAction(['item' => $uuid]) }}
                        </div>
                        @endif

                    </div>
                @endforeach
            </nav>
            @else
                <div></div>
            @endif
            @if ($isAddable && $addAction->isVisible())
                <div class="it-tabs-add-btn">
                    {{ $addAction }}
                </div>
            @endif
        </div>

        {{-- Tab Bodies (Keep exactly as before) --}}
        <div class="it-tabs-content">
            @forelse ($containers as $uuid => $item)
                <div x-show="isActive('{{ $uuid }}')" x-cloak wire:key="{{ $item->getLivewireKey() }}.tab-content" class="ring-1 ring-gray-950/5 dark:ring-white/10 rounded-lg p-6 bg-white dark:bg-gray-900 shadow-sm relative">
                    {{ $item }}
                </div>
            @empty
                <div class="it-empty-state">
                    <div>{{ __('filament-tab-repeater::components.empty_state', ['item'=>$label]) }}</div>
                </div>
            @endforelse
        </div>
        
    </div>
</x-dynamic-component>