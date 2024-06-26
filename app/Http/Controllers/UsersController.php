<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use ProtoneMedia\Splade\Facades\Toast;
use Bavix\Wallet\Models\Wallet;


class UsersController extends Controller
{

        public function makeDepositsToDefaultWallet(Request $request)
        {
            $request->validate([
                'amount'=>['required'],
                'notes'=>['required']
            ]);
            $user = User::find(auth()->id());
            if ($user)
            {
                $user->deposit($request->amount, ['description' => $request->notes], true);
                Toast::title($request->amount.' deposited successfully')->autoDismiss(10);
                return back();
            }
            Toast::danger($request->amount.' transaction failed')->autoDismiss(10);
            return back();
        }

        public function makeMultiWalletDeposits(Request $request)
        {
            $request->validate([
                'wallet' => ['required', 'exists:wallets,id'],
                'amount' => ['required'],
                'notes'=>['required']
            ]);
            $user = User::find(auth()->id());
            if ($user)
            {
                $wallet= Wallet::find($request->wallet);
                $myWallet = $user->getWallet($wallet->slug);
                $myWallet->deposit($request->amount, ['description' => $request->notes], true);

                Toast::title($request->amount.' deposited successfully')->autoDismiss(10);
                return back();
            }
            Toast::danger($request->amount.' transaction failed')->autoDismiss(10);
            return back();

        }

        public function withdrawMoneyFromWallet(Request $request)
        {
            $request->validate([
                'wallet' => ['required', 'exists:wallets,id'],
                'amount' => ['required']
            ]);

            $user = User::first();
            if($user)
            {
               $wallet = Wallet::find($request->wallet);

               if($request->amount >= $user->balance)
               {
                return Toast::title('insufficient funds')->warning()->autoDismiss(10);
               }
               else
               {
                $myWallet = $user->getWallet($wallet->slug);
                $myWallet->withdraw($request->amount);
               }
               
               Toast::title($request->amount.' withdrawn successfully')->autoDismiss(10);
               return back();
            }
            Toast::danger($request->amount.' transaction failed')->autoDismiss(10);
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
            
            if(!$receiver){
                return Toast::title("receiver number does not exist")->warning()->autoDismiss(10);
            }

            $wallet = Wallet::find($request->wallet);
            if($request->amount > $sender->balance)
            {
                return Toast::title('insufficient funds')->warning()->autoDismiss(10);
            }
            else
            {
                $myWallet = $sender->getWallet($wallet->slug);
                // $receiverWallet = $receiver->getWallet('default');

                $myWallet->transfer($receiver->wallet, $request->amount);

            }
           

            

            Toast::title("{$request->amount} has been sent to {$receiver->name}.")->autoDismiss(10);
            return back();
        }
        

        public function createNewWallet(Request $request)
        {

            $request->validate([
                'slug'=>['required'],
                'name'=>['required']
            ]);
           $user = User::find(auth()->id());
           if ($user->wallets()->where('slug', $request->slug)->exists()) 
           {
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

        if($user)
        {


            $receiverWallet= Wallet::find($request->receiver_wallet);
            $senderWallet = Wallet::find($request->sender_wallet);
            
           if( $receiverWallet->id === $senderWallet->id)
           {
               return  Toast::title(" sorry, you cannot transfer money to the same wallet")->warning()->autoDismiss(10);
           }


            if($request->amount > $senderWallet->balance)
            {
                return Toast::title("insufficient funds")->warning()->autoDismiss(10);
            }
            else
            {
                $yourWallet = $user->getWallet($receiverWallet->slug);
                $yourWallet->deposit($request->amount);

                $myWallet = $user->getWallet($senderWallet->slug);
                $myWallet->withdraw($request->amount);
            }

            
           Toast::title("{$request->amount} has been sent to {$receiverWallet->name}.")->autoDismiss(10);
           return back();
       }
     }

        
}
