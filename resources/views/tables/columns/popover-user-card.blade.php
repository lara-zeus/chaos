<div class="w-[300px] px-2 py-4">
    <div class="flex flex-col items-center">
        <img alt="avatar" lazy
             class="bg-white -mt-16 object-cover object-center !w-24 !h-24 mb-3 rounded-full shadow-xl"
             src="{{ $user?->avatar }}"
        />

        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{ $user?->name }}</h5>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $user?->email }}</span>
        <div class="flex mt-4 md:mt-6 gap-4">

            <x-filament::button color="secondary" size="xs">
                view profile
            </x-filament::button>

            <x-filament::button size="xs">
                Message
            </x-filament::button>

        </div>
    </div>
</div>
