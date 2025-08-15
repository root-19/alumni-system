<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;

class AdminChat extends Component
{
    public $users;            // All regular users
    public $selectedUserId;   // Currently selected user
    public $messages = [];    // Messages with selected user
    public $message;          // New message input

    public function mount()
    {
        $this->users = User::getRegularUsers();
        $this->selectedUserId = null;
        $this->messages = [];
        $this->message = '';
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $this->loadMessages();
    }

    // Load messages between admin and selected user
    public function loadMessages()
    {
        if (!$this->selectedUserId) return;

        $this->messages = Message::where(function($q) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $this->selectedUserId);
            })
            ->orWhere(function($q) {
                $q->where('sender_id', $this->selectedUserId)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    // Send a new message
    public function sendMessage()
    {
        if (empty(trim($this->message)) || !$this->selectedUserId) return;

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUserId,
            'message' => trim($this->message),
        ]);

        $this->message = '';
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.admin-chat');
    }
}
