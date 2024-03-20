@php
    $statePath = $getStatePath();
    $id = $getId();
    $label = $getLabel();
@endphp

<div class="fi-contained rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <div x-data="{ activeTab: 'tab-ar' }">
        <x-filament::tabs class="!py-1 !px-2 !flex-none !m-0" :contained="true">
            <div class="w-full flex items-center justify-between gap-10">
                <x-filament::tabs.item icon="tabler-language-katakana" class="!px-2 !py-0.5">
                    <span class="!text-sm !text-primary-600">
                        {{ $label }}
                    </span>
                </x-filament::tabs.item>

                <div class="flex">
                    @foreach(config('app.locales') as $lang => $info)
                        <x-filament::tabs.item
                            class="!px-2 !py-0.5"
                            alpine-active="activeTab === 'tab-{{ $lang }}'"
                            x-on:click="activeTab = 'tab-{{ $lang }}'"
                        >
                            {{ $lang }}
                        </x-filament::tabs.item>
                    @endforeach
                </div>
            </div>
        </x-filament::tabs>

        @foreach(config('app.locales') as $lang => $info)
            <div x-show="activeTab === 'tab-{{ $lang }}'" class="p-2">
                <x-filament::input.wrapper :valid="! $errors->has($statePath)">
                    <x-filament::input
                        wire:model="{{ $statePath }}.{{ $lang }}"
                       :attributes="
                            \Filament\Support\prepare_inherited_attributes($getExtraInputAttributeBag())
                                ->merge([
                                    'id' => $id.'.'.$lang,
                                    'name' => $id.'.'.$lang,
                                    'placeholder' => $getPlaceholder(),
                                    'required' => $isRequired(),
                                    'type' => 'text',
                                ], escape: false)
                        "
                    />
                </x-filament::input.wrapper>
            </div>
        @endforeach
    </div>
</div>
