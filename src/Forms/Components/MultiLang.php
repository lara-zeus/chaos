<?php

namespace LaraZeus\Chaos\Forms\Components;

use Filament\Forms\Components\TextInput;

class MultiLang extends TextInput
{
    protected string $view = 'zeus-chaos::forms.components.multi-lang';

    protected function setUp(): void
    {
        parent::setUp();
        $this->formatStateUsing(function () {
            $defaultDataForLang = [];

            foreach (config('app.locales') as $lang => $info) {
                if (static::getRecord() === null) {
                    $defaultDataForLang[$lang] = '';
                } else {
                    // @phpstan-ignore-next-line
                    $defaultDataForLang[$lang] = static::getRecord()->getTranslation('name', $lang);
                }
            }

            return $defaultDataForLang;
        });
    }
}
