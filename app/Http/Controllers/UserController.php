<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use App\Events\ExampleEvent;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\View;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{

    public function showCorrectHomepage() {
        if (auth()->check()) {
            return view('homepage-feed', ['posts' => auth()->user()->feedPosts()->latest()->paginate(5)]);
        } else {
            return view('homepage');
        }
    }

    public function login(Request $request){
        $incomingFields = $request ->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required'
        ]);

        if (auth()->attempt(['username' => $incomingFields['loginusername'],'password' =>$incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'login']));
            return redirect('/')->with("success", "You have successfuly logged in.");
        }else{
            return redirect('/')->with("error", "Invalid login.");
        }
    }

    public function logout() {
        event(new ExampleEvent(['username' => auth()->user()->username, 'action' => 'logout']));
        auth()->logout();
        return redirect('/')->with('success', 'You are now logged out.');
    }


    public function register(Request $request){
        $incomingFields = $request ->validate([
            'username' => ['required', 'min:3', 'max:20', Rule::unique('users','username')],
            'email' => ['required', 'email', Rule::unique('users','email')],
            'password' => ['required', 'min:8', 'confirmed']
        ]);
        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect('/')->with('success',"You successfuly created an account.");
    }

    private function getSharedData($user) {
        $currentFollowing = 0;

        if (auth()->check()) {
            $currentFollowing = Follow::where([['user_id', '=', auth()->user()->id], ['followeduser', '=', $user->id]])->count();

        }

        View::share('sharedData', ['currentFollowing'=> $currentFollowing, 'avatar'=> $user->avatar ,'username'=> $user->username, 'postCount' => $user->posts()->count(), 'followersCount' => $user->followers()->count(), 'followingCount' => $user->followingUsers()->count()]);
    }

    public function profile(User $user){
        $this->getSharedData($user);
        return view('profile-posts',['posts' =>$user->posts()->latest()->get()]);
    }

    public function profileFollowers(User $user){
        $this->getSharedData($user);
        return view('profile-followers',['followers' =>$user->followers()->latest()->get()]);
    }

    public function profileFollowing(User $user){
        $this->getSharedData($user);
        return view('profile-following',['following' =>$user->followingUsers()->latest()->get()]);
    }

    public function showAvatarForm (){
        return view('avatar-form');
    }

    public function storeAvatar(Request $request) {
        $request->validate([
            'avatar' => 'required|image|max:2000'
        ]);

        $user = auth()->user();

        $filename = $user->id . '-' . uniqid() . '.jpg';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file("avatar"));
        $imgData = $image->cover(120, 120)->toJpeg();
        Storage::disk('public')->put("avatars/" . $filename, $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if ($oldAvatar != "/fallback-avatar.jpg") {
            Storage::disk('public')->delete("avatars/" . basename($oldAvatar));
        }

        return back()->with('success', 'Congrats on the new avatar.');
    }
}
