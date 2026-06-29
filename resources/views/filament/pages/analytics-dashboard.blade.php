<x-filament-panels::page>
    <x-filament-widgets::widgets
        :widgets="$this->getHeaderWidgets()"
        :columns="$this->getHeaderWidgetsColumns()"
    />

    <x-filament::section>
        {{ $this->table }}
    </x-filament::section>
</x-filament-panels::page>
