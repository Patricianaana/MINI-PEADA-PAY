<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use ProtoneMedia\Splade\Facades\Toast;
use Bavix\Wallet\Models\Wallet;

class UsersController extends Controller
{
        public function depositMoneyToWallet(Request $request)
        {
            $request->validate([
                'recnum' => ['required', 'numeric'],
                'wallet' => ['required', 'exists:wallets,id'],
                'amount' => ['required']
            ]);
            $user = User::where('number', $request->recnum)->first();
            $wallet= Wallet::find($request->wallet);
            $myWallet = $user->getWallet($wallet->slug);
            $myWallet->deposit($request->amount, ['description' => $request->notes], true);
            Toast::title($request->amount.' deposited successfully')->autoDismiss(5);
            return back();
        }

        public function withdrawMoneyFromWallet(Request $request)
        {
            $request->validate([
                'wallet' => ['required', 'exists:wallets,id'],
                'amount' => ['required']
            ]);

            $user = User::first();
            $wallet = Wallet::find($request->wallet);
            $myWallet = $user->getWallet($wallet->slug);
            $myWallet->withdraw($request->amount);

            Toast::title($request->amount.' withdrawn successfully')->autoDismiss(5);
            return back();
        }
   
        public function transferMoneyToOtherUsers(Request $request)
        {
            $request->validate([
                'recnum' => ['required', 'numeric'],
                'wallet' => ['required', 'exists:wallets,id'],
                'repnum' => ['required', 'numeric'],
                'amount' => ['required']
            ]);

    
            $sender = User::where('number', $request->recnum)->first();
            $receiver = User::where('number', $request->repnum)->first();

            $wallet = Wallet::find($request->wallet);

            $myWallet = $sender->getWallet($wallet->slug);
            // $receiverWallet = $receiver->getWallet('default');

            $myWallet->transfer($receiver->wallet, $request->amount);

            Toast::title("{$request->amount} has been sent to {$receiver->name}.")->autoDismiss(10);
            return back();
        }
        
        
    //     public function createNewWallet(Request $request)
    //     {
       
    //        $user = User::first();
    //         if ($user->hasWallet('New Wallet')) 
    //     {
    //        Toast::danger('The user already has a wallet with the specified slug.')->autoDismiss(10);
    //        return back();
    //     }

    //    $wallet = $user->createWallet([
    //       'name' => $request->name,
    //       'slug' => 'New Wallet',
    // ]);


    // Toast::title('New wallet created successfully.')->autoDismiss(10);
    // return back();
    //  }

    public function createNewWallet(Request $request)
{
    // $user = auth()->user();
    // $user = User->auth()->user();
    $user=auth()->user()->User::first();
   
    if (!$user) {
        // User is not authenticated
        Toast::danger('User is not authenticated.')->autoDismiss(10);
        return back();
    }

    if ($user->hasWallet('New Wallet')) {
        Toast::danger('The user already has a wallet with the specified slug.')->autoDismiss(10);
        return back();
    }

    $wallet = $user->createWallet([
        'name' => $request->name,
        'slug' => 'New Wallet',
    ]);

    Toast::title('New wallet created successfully.')->autoDismiss(10);
    return back();
}


     public function viewAllWallets()
     {
        $data = auth()->user()->wallets;
        return view('allWallets',compact('data'));
     }

     public function transferToOtherPersonalWallets(Request $request)
     {
        $request->validate([
            'wallet' => ['required', 'exists:wallets,id'],
            'amount' => ['required']
        ]);


        $sender = User::where('number', $request->recnum)->first();
        $receiver = User::where('number', $request->repnum)->first();

        $wallet = Wallet::find($request->wallet);

        $myWallet = $sender->getWallet($wallet->slug);
        // $receiverWallet = $receiver->getWallet('default');

        $myWallet->transfer($receiver->wallet, $request->amount);

        Toast::title("{$request->amount} has been sent to {$receiver->name}.")->autoDismiss(10);
        return back();

     }

    
}
