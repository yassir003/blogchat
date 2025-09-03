<div x-data="{ isOpen: false }">
    <span x-on:click="isOpen = true; document.querySelector('.chat-field').focus()" class="text-white mr-2 header-chat-icon" title="Chat" data-toggle="tooltip" data-placement="bottom"><i class="fas fa-comment"></i></span>


    <div data-username="{{auth()->user()->username}}" data-avatar="{{auth()->user()->avatar}}" id="chat-wrapper" x-bind:class="isOpen ? 'chat--visible' : ''" class="chat-wrapper chat-wrapper--ready shadow border-top border-left border-right">
        <div class="chat-title-bar">Chat <span x-on:click="isOpen = false" class="chat-title-bar-close"><i class="fas fa-times-circle"></i></span></div>
        <div id="chat" class="chat-log">
            @if (count($chatlog) > 0)
            @foreach ($chatlog as $chat)
            @if ($chat['selfmessage'] == true)
            <div class="chat-self">
                <div class="chat-message">
                <div class="chat-message-inner">
                    {{$chat['textvalue']}}
                </div>
                </div>
                <img class="chat-avatar avatar-tiny" src="{{$chat['avatar']}}" />
            </div>
            @else
            <div class="chat-other">
        <a href="/profile/{{$chat['username']}}r-tiny" src="{{$chat['avatar']}}" ></a><img class="chat-avatar avatar-tiny" src="{{$chat['avatar']}}" />
        <div class="chat-message"><div class="chat-message-inner">
          <a href="/profile/{{$chat['username']}}"><strong>{{$chat['username']}}:</strong></a>
          {{$chat['textvalue']}}
        </div></div>
      </div>
            @endif
            @endforeach
            @endif
        </div>
        
        <form wire:submit="send" id="chatForm" class="chat-form border-top">
            <input wire:model="textvalue" type="text" class="chat-field" id="chatField" placeholder="Type a message…" autocomplete="off">
        </form>
    </div>
</div>

<script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
