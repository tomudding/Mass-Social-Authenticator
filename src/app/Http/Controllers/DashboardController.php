<?php

namespace App\Http\Controllers;

use App\Models\LinkedAccount;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function dashboard(): View
    {
        /** @var User $user */
        $user = auth()->user();
        $linkedAccounts = $user->accounts()->get();

        $orderedLinkedAccounts = [];
        foreach ($linkedAccounts as $account) {
            $orderedLinkedAccounts[$account->provider_name][] = $account;
        }

        return view('dashboard', ['linkedAccounts' => $orderedLinkedAccounts]);
    }
}
