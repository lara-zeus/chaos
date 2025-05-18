<?php

namespace LaraZeus\Chaos\Forms\Components;

use Closure;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Contracts\Support\Htmlable;

class MultiLang extends \Filament\Schemas\Components\Tabs
{
    public string $theMainKeyThingy = '';

    public static function make(string | Htmlable | Closure | null $label = null): static
    {
        static::configureUsing(function ($component) use ($label) {
            $component->theMainKeyThingy = $label;
        });

        return parent::make($label);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->tabs(function (MultiLang $multiLangComponent) {
                $tabs = [];
                foreach (config('app.locales') as $lang => $info) {
                    $tabs[] = Tab::make('tab-' . $lang)
                        ->statePath($this->getLangKey())
                        ->label($info['name'])
                        // no need for this, cause issues on repeaters, seems filament will handle the array state from spatie
                        /*->formatStateUsing(function (): array {
                            $defaultDataForLang = [];

                            foreach (config('app.locales') as $lang => $info) {
                                if (static::getRecord() === null) {
                                    $defaultDataForLang[$lang] = '';
                                } else {
                                    $defaultDataForLang[$lang] = static::getRecord()->getTranslation($this->getLangKey(), $lang);
                                }
                            }

                            return $defaultDataForLang;
                        })*/
                        ->schema(fn (Tab $tabComponent) => [
                            TextInput::make($lang)
                                ->required(fn () => app()->getLocale() === $lang)
                                ->label(fn () => $multiLangComponent->getLabel()),
                        ]);
                }

                return $tabs;
            });
    }

    public function getLangKey(): string
    {
        return $this->theMainKeyThingy;
    }
}
