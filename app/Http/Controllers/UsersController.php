<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use ProtoneMedia\Splade\Facades\Toast;
use Bavix\Wallet\Models\Wallet;
use Bavix\Wallet\Services\WalletService;

class UsersController extends Controller
{
        public function depositMoneyToWallet(Request $request)
        {
            $request->validate([
                'wallet' => ['required', 'exists:wallets,id'],
                'amount' => ['required']
            ]);
            $user = User::find(auth()->id());
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
                'wallet' => ['required', 'exists:wallets,id'],
                'repnum' => ['required', 'numeric'],
                'amount' => ['required']
            ]);

            $sender = User::find(auth()->id());
            $receiver = User::where('number', $request->repnum)->first();

            $wallet = Wallet::find($request->wallet);

            $myWallet = $sender->getWallet($wallet->slug);
            // $receiverWallet = $receiver->getWallet('default');

            $myWallet->transfer($receiver->wallet, $request->amount);

            Toast::title("{$request->amount} has been sent to {$receiver->name}.")->autoDismiss(10);
            return back();
        }
        

    public function createNewWallet(Request $request)
{

    $user = User::find(auth()->id());
    if ($user->wallets()->where('slug', $request->slug)->exists()) {
        Toast::danger('A wallet with the specified slug already exists.')->autoDismiss(10);
        return back();
    }

    if ($user->wallets()->where('name', $request->name)->exists())
    {
        Toast::danger('A wallet with the special name already exists.')->autoDismiss(10);
        return back();
    }

    $wallet = $user->createWallet([
        'name' => $request->name,
        'slug' => $request->slug,
    ]);

    Toast::title('New wallet created successfully.')->autoDismiss(10);
    return back();
}


     public function viewAllWallets()
     {
        $data = auth()->user()->wallets;
        return view('allWallets',compact('data'));
     }

     public function walletToWalletMoneyTransfer (Request $request)
     {
        $request->validate([
            'receiver_wallet' => ['required', 'exists:wallets,id'],
            'sender_wallet' => ['required', 'exists:wallets,id'],
            'amount' => ['required']
        ]);

        $user = User::find(auth()->id());

        $receiverWallet= Wallet::find($request->receiver_wallet);
        $senderWallet = Wallet::find($request->sender_wallet);

        $yourWallet = $user->getWallet($receiverWallet->slug);
        $yourWallet->deposit($request->amount);

        $myWallet = $user->getWallet($senderWallet->slug);
        $myWallet->withdraw($request->amount);

        Toast::title("{$request->amount} has been sent to {$receiverWallet->name}.")->autoDismiss(10);
        return back();
     }

}
