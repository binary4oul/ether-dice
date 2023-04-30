<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\User; // Import the User model
use App\Models\DiceGame; 
use App\Models\House; 
use App\Models\CoinflipGame; 
use App\Models\JackpotGame; 
use Illuminate\Http\Request;
use App\Models\Transaction;


class AdminController extends Controller
{
    public function index()
{

    $withdrawals = Withdrawal::where('status', '<>', 'approved')->get();

    $totalUserCoins = User::sum('coins'); // Get the sum of all coins in User table
    $totalTransactionCoinValue = Transaction::sum('coin_value'); // Get the sum of all coin_value in Transaction table
    $siteProfit = $totalTransactionCoinValue - $totalUserCoins;


    $total_users = User::count(); // Get the count of all users
    $diceGamesCount = DiceGame::count();
    $coinflipGamesCount = CoinflipGame::count();
    $jackpotGamesCount = JackpotGame::count();
    $totalGames = $diceGamesCount + $coinflipGamesCount + $jackpotGamesCount;
    return view('admin.index', compact('withdrawals', 'total_users', 'totalGames', 'siteProfit'));

}
public function approveWithdrawal($id)
{
    $withdrawal = Withdrawal::findOrFail($id);
    $withdrawal->status = 'approved';
    $withdrawal->save();
    
    return redirect()->back()->with('success', 'Withdrawal request approved!');
}

public function rejectWithdrawal($id)
{
    $withdrawal = Withdrawal::findOrFail($id);
    $withdrawal->status = 'rejected';
    $withdrawal->save();
    
    return redirect()->back()->with('success', 'Withdrawal request rejected!');
}
public function updateMaxBet(Request $request)
{
    // Validate the request
    $request->validate([
        'max_bet' => 'required|integer|min:1' // Adjust validation rules as necessary
    ]);

    // Retrieve the house model (assuming there's only one)
    $house = House::where('name', 'DiceHouse')->first();

    // If the house doesn't exist, you might want to throw an exception or return an error response
    if (!$house) {
        return response()->json(['error' => 'House not found'], 404);
    }

    // Update the max_bet attribute
    $house->max_bet = $request->input('max_bet');

    // Save the changes
    $house->save();

    // Return a successful response (or redirect, etc.)
    return redirect()->back()->with('message', 'Max bet updated successfully!');
}
public function admindice()
{
    $house = House::where('name', 'DiceHouse')->first();
    $maxBet = $house ? $house->max_bet : null;

    $withdrawals = Withdrawal::where('status', '<>', 'approved')->get();

    $totalUserCoins = User::sum('coins'); // Get the sum of all coins in User table
    $totalTransactionCoinValue = Transaction::sum('coin_value'); // Get the sum of all coin_value in Transaction table
    $siteProfit = $totalTransactionCoinValue - $totalUserCoins;


    $total_users = User::count(); // Get the count of all users
    $diceGamesCount = DiceGame::count();
    $coinflipGamesCount = CoinflipGame::count();
    $jackpotGamesCount = JackpotGame::count();
    $totalGames = $diceGamesCount + $coinflipGamesCount + $jackpotGamesCount;

    return view('admin.admindice', compact('withdrawals', 'total_users', 'totalGames', 'siteProfit', 'maxBet'));

}
}
