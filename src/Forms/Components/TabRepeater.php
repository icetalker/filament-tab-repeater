<?php

namespace Icetalker\FilamentTabRepeater\Forms\Components;

use Closure;
use Filament\Actions\Action;
use Filament\Forms\Components\Repeater;
use Filament\Forms\View\FormsIconAlias;
use Filament\Support\Facades\FilamentIcon;
use Filament\Support\Icons\Heroicon;

class TabRepeater extends Repeater
{
    protected string $view = 'filament-tab-repeater::tab-repeater';

    protected string | Closure | null $itemIcon = null;

    protected function setUp(): void
    {
        $this->columnSpanFull();
        parent::setUp();
    }

    // Setter for the developer to define the icon
    public function itemIcon(string | Closure | null $icon): static
    {
        $this->itemIcon = $icon;

        return $this;
    }

    // Resolver to get the icon for a specific item UUID
    public function getItemIcon(string $uuid): ?string
    {
        if ($this->itemIcon === null) {
            return null;
        }

        // We get the specific state for this item (e.g., to check a 'type' field)
        $state = $this->getRawState()[$uuid] ?? [];

        return $this->evaluate($this->itemIcon, [
            'state' => $state,
            'uuid' => $uuid,
        ]);
    }

    public function getDeleteAction(): Action
    {
        return parent::getDeleteAction()
            ->icon(FilamentIcon::resolve(FormsIconAlias::COMPONENTS_REPEATER_ACTIONS_DELETE) ?? Heroicon::OutlinedXMark);
    }

    public function getAddAction(): Action
    {
        return parent::getAddAction()
            ->icon(Heroicon::Plus)
            ->iconButton();
    }

    public function getReorderAction(): Action
    {
        return parent::getReorderAction()->icon(Heroicon::ArrowsRightLeft);
    }


}