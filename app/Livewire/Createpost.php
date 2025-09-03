<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;

class Createpost extends Component
{
    public $title;
    public $body;

    public function create(){
        if (!auth()->check()) {
            abort(403,'Unauthorized');
        }

        $incomingFields = $this->validate([
            'title'=> 'required',
            'body'=> 'required'
        ]);

        $incomingFields['title'] = strip_tags($incomingFields['title']);
        $incomingFields['body'] = strip_tags($incomingFields['body']);
        $incomingFields['user_id'] = auth()->id();

        $newpost = Post::create($incomingFields);

        session()->flash('success', 'New post successfully created.');

        return $this->redirect("/post/{$newpost->id}", navigate: true);

    }

    public function render()
    {
        return view('livewire.createpost');
    }
}
