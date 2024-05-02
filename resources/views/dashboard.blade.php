<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>



    <section class="py-10 bg-blue-50 leading-6 text-blue-900 sm:py-16 lg:py-24">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">      
          <div class="mt-8 grid  grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-5 md:gap-8 lg:mt-16">
            
            <div class="relative overflow-hidden rounded-xl shadow border-t-4 border-blue-200 bg-white">
              <div class="py-10 px-6">
                <div class="flex flex-col space-y-2 items-center">
                  <span class="ml-3 text-base font-medium capitalize">Balance</span>
                  <div class="font-semibold text-xl">
                    Gh&cent;{{ auth()->user()->wallet->balance }}
                  </div>
                </div>
              </div>
            </div>
      
            <a href="transferoptions">
              <div class="relative overflow-hidden rounded-xl shadow border-t-4 border-blue-200 bg-white">
                <div class="py-10 px-6">
                  <div class="flex items-center">
                    <span class="ml-3 text-base font-medium capitalize">Transfer</span>
                  </div>
                </div>
              </div>
            </a>
      
            <a href="user">
              <div class="relative overflow-hidden rounded-xl shadow border-t-4 border-blue-200 bg-white">
                <div class="py-10 px-6">
                  <div class="flex items-center">
                    <span class="ml-3 text-base font-medium capitalize">Deposit</span>
                  </div>  
                </div>
              </div>
            </a>
            
            <a href="money">
              <div class="relative overflow-hidden rounded-xl shadow border-t-4 border-blue-200 bg-white">
                <div class="py-10 px-6">
                  <div class="flex items-center">
                    <span class="ml-3 text-base font-medium capitalize">Withdrawal</span>
                  </div>
                </div>
              </div>
            </a>
            
            <a href="card">
              <div class="relative overflow-hidden rounded-xl shadow border-t-4 border-blue-200 bg-white">
                <div class="py-10 px-6">
                  <div class="flex items-center mb-2">
                    <span class="ml-3 text-base font-medium capitalize">View Wallets</span>
                  </div>
                </div>
              </div>
            </a>
            
          </div>
        </div>
      </section>
</x-app-layout>
