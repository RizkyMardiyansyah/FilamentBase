<div
    id="{{ $record->getKey() }}"
    wire:click="recordClicked('{{ $record->getKey() }}', {{ @json_encode($record) }})"
    class="record bg-white dark:bg-black-700 rounded-lg px-4 py-2 cursor-grab font-medium "
    @if($record->timestamps && now()->diffInSeconds($record->{$record::UPDATED_AT}) < 3)
        x-data
        x-init="
            $el.classList.add('animate-pulse-twice', 'bg-primary-100', 'dark:bg-primary-800')
            $el.classList.remove('bg-white', 'dark:bg-gray-700')
            setTimeout(() => {
                $el.classList.remove('bg-primary-100', 'dark:bg-primary-800')
                $el.classList.add('bg-white', 'dark:bg-gray-700')
            }, 3000)
        "
    @endif
>
    <h3 class="text-lg font-semibold text-black ">
        {{ ucfirst($record->{static::$recordTitleAttribute}) }}
    </h3>
    <p class="px-4 text-sm text-gray-500 dark:text-gray-300 mt-2">
        {{ ucfirst($record->description) }}
    </p>
    <p class="text-sm text-gray-500 dark:text-gray-300 mt-2 text-right">
        <x-heroicon-o-calendar class="inline-block w-4 h-4 mr-1" /> {{ $record->updated_at->format('Y/m/d') }}
        <x-heroicon-o-clock class="inline-block w-4 h-4 mr-1 ml-4" /> {{ $record->updated_at->format('H:i') }}
    </p>
    
</div>
