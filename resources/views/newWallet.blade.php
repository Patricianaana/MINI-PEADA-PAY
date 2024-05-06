<x-app-layout>
    <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <div>
                    <a href="card"> {{ __('Wallets') }}</a>
                </div>
            </h2>
    </x-slot>

    <section class="py-10 bg-blue-50 leading-6 text-blue-900 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <x-splade-form action="{{ route('wal') }}" class="space-y-4 mb-4">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    <x-splade-input id="name" type="text" name="name" :label="__('Wallet Name')" required />
                    <x-splade-input id="slug" type="text" name="slug" :label="__('Wallet slug')" placeholder="e.g. Second Wallet" required />

                  
                <div class="flex items-center justify-center mt-2">
                    <x-splade-submit class="ml-4" :label="__('Add')" />
                </div>
                </div>
                
            </x-splade-form>
        </div>
    </section>

</x-app-layout>