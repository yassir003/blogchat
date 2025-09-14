<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Follow;
use Livewire\Component;

class Addfollow extends Component
{
    public $username;

    
    public function save(){
        if (!auth()->check()) {
            abort(403, 'unauthorized');
        }

        $user = User::where('username', $this->username)->first();

        // user don't follow himself
        if ($user->id == auth()->user()->id) {
            return back()->with('error', 'You cannot follow yourself.');
        }

        // user cannot follow a user more than 1 time
        $existCheck = Follow::where([['user_id', '=', auth()->user()->id] , ['followeduser', '=', $user->id]])->count();

        if ($existCheck) {
            return back()->with('error', 'You are already following that user');
        }

        $newFollow = new Follow;
        $newFollow->user_id = auth()->user()->id;
        $newFollow->followeduser = $user->id;
        $newFollow->save();

        session()->flash('success', 'User successfully followed');
        return $this->redirect("/profile/{$this->username}", navigate: true);
    }


    public function render()
    {
        return view('livewire.addfollow');
    }
}
