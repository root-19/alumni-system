<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserChat extends Component
{
    public $admin;
    public $messages = [];
    public $message = '';
    public $selectedAdminId;
    public $availableAdmins = [];

    public function mount()
    {
        // Initialize properties
        $this->selectedAdminId = null;
        $this->admin = null;
        $this->messages = [];
        $this->availableAdmins = collect([]);
        
        // Get all available admin users using the helper method
        try {
            $this->availableAdmins = User::getAdmins();
        } catch (\Exception $e) {
            $this->availableAdmins = collect([]);
        }
        
        // Select the first available admin
        if ($this->availableAdmins->isNotEmpty()) {
            $this->admin = $this->availableAdmins->first();
            $this->selectedAdminId = $this->admin->id;
            $this->loadMessages();
        }
    }

    public function selectAdmin($adminId)
    {
        $this->selectedAdminId = $adminId;
        $this->admin = User::find($adminId);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (!$this->selectedAdminId) {
            $this->messages = [];
            return;
        }

        $this->messages = Message::where(function ($q) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $this->selectedAdminId);
            })
            ->orWhere(function ($q) {
                $q->where('sender_id', $this->selectedAdminId)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function sendMessage()
    {
        // Validate message is not empty
        if (empty(trim($this->message))) {
            return;
        }

        // Ensure we have an admin to send to
        if (!$this->selectedAdminId) {
            return;
        }

        // Create the message
        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedAdminId,
            'message' => trim($this->message)
        ]);

        // Clear input and reload messages
        $this->message = '';
        $this->loadMessages();
    }

    public function render()
    {
        // Ensure availableAdmins is always initialized
        if (!isset($this->availableAdmins) || !$this->availableAdmins) {
            try {
                $this->availableAdmins = User::getAdmins();
            } catch (\Exception $e) {
                $this->availableAdmins = collect([]);
            }
        }
        
        return view('livewire.user-chat');
    }
}