<div>
    <div style="margin-top:32px; margin-bottom:-25px">
        @php
            $projectId = request()->query('projectId');
            $projectName = App\Models\ProjectName::find($projectId)?->title;
            $projectName = $projectName ? ucfirst($projectName) : null;
            $breadcrumbs = [
                '/admin/project-names' => 'Project',
                "/admin/board?projectId={$projectId}" => $projectName ?? 'Tasks',
                "" => 'Board',
            ];
        @endphp

        <x-filament::breadcrumbs :breadcrumbs="$breadcrumbs" />
    </div>

    <x-filament-panels::page>
        <div x-data wire:ignore.self class="md:flex overflow-x-auto overflow-y-hidden gap-4 pb-4">
            @foreach($statuses as $status)
                @include(static::$statusView)
            @endforeach

            <div wire:ignore>
                @include(static::$scriptsView)
            </div>
        </div>

        @unless($disableEditModal)
            <x-filament-kanban::edit-record-modal />
        @endunless
    </x-filament-panels::page>
</div>
