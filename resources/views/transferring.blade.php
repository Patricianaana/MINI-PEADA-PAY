<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Transfer') }}
        </h2>
    </x-slot>

    <section class="py-10 bg-blue-50 leading-6 text-blue-900 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <x-splade-form action="{{ route('trans') }}" class="space-y-4 mb-4">
                <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                    {{-- <x-splade-input id="recnum" type="number" name="recnum" :label="__('Your Number')" required /> --}}
                    <x-splade-select name="wallet" :label="__('Wallet')" required>
                        <option selected disabled>Select Wallet</option>
                        @forelse (auth()->user()->wallets as $data)
                            <option value="{{ $data->id }}">{{ $data->name }} ({{ $data->balance }})</option>
                        @empty
                            <option selected disabled>No wallet found.</option>
                        @endforelse
                    </x-splade-select>
                    
                    <x-splade-input id="repnum" type="number" name="repnum" :label="__('Recipient Number')" required />
                   <x-splade-input id="amount" type="number" name="amount" :label="__('Enter Amount ')" required />
                  
                <div class="flex items-center justify-center mt-2">
                    <x-splade-submit class="ml-4" :label="__('Send')" />
                </div>
                </div>
                
            </x-splade-form>
        </div>
    </section>

</x-app-layout>