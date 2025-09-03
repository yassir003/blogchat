<?php

namespace App\Livewire;

use Livewire\Component;
use App\Events\ChatMessage;

class Chat extends Component
{

    public $textvalue = '';
    public $chatlog = array();

    public function getListeners(){
        return [
            "echo-private:chatchannel,ChatMessage" => 'notifyNewMessage'
        ];
    }

    public function notifyNewMessage($data){
        array_push($this->chatlog, $data['chat']);
    }

    public function send(){
        if (!auth()->check()) {
            abort(403, 'Unauthorized,You must be logged in to send messages.');
        }

        if (trim(strip_tags($this->textvalue)) == '') {
            return;
        }

        array_push($this->chatlog, [
            'selfmessage' => true,
            'username' => auth()->user()->username,
            'avatar' => auth()->user()->avatar,
            'textvalue' => trim(strip_tags($this->textvalue))
        ]);
        
        broadcast(new ChatMessage([
            'selfmessage' => false,
            'username' => auth()->user()->username,
            'avatar' => auth()->user()->avatar,
            'textvalue' => trim(strip_tags($this->textvalue))
        ]))->toOthers();
        $this->textvalue = '';
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
