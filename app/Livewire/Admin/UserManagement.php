<?php

namespace App\Livewire\Admin;

use App\Livewire\AdminComponent;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\WithPagination;

class UserManagement extends AdminComponent
{
    use WithPagination;

    public $search = '';
    public $roleFilter = '';
    
    // Form fields
    public $userId;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $isAdmin = false;
    public $isActive = true;

    public $isModalOpen = false;
    public $isConfirmModalOpen = false;
    public $isDetailsModalOpen = false;
    public $isEditMode = false;
    public $targetUserId;
    public $selectedUser;

    protected $queryString = ['search', 'roleFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->firstname = '';
        $this->lastname = '';
        $this->username = '';
        $this->email = '';
        $this->password = '';
        $this->isAdmin = false;
        $this->isActive = true;
        $this->isEditMode = false;
        $this->resetErrorBag();
    }

    public function openConfirmModal($id)
    {
        $this->targetUserId = $id;
        $this->isConfirmModalOpen = true;
    }

    public function closeConfirmModal()
    {
        $this->isConfirmModalOpen = false;
        $this->targetUserId = null;
    }

    public function createUser()
    {
        $this->isEditMode = false;
        $this->openModal();
    }

    public function openDetailsModal(User $user)
    {
        $this->selectedUser = $user;
        $this->isDetailsModalOpen = true;
    }

    public function closeDetailsModal()
    {
        $this->isDetailsModalOpen = false;
        $this->selectedUser = null;
    }

    public function editUser(User $user)
    {
        $this->isEditMode = true;
        $this->userId = $user->id;
        $this->firstname = $user->firstname;
        $this->lastname = $user->lastname;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->isAdmin = $user->isAdmin;
        $this->isActive = $user->isActive;
        $this->password = ''; // Don't show password
        $this->isDetailsModalOpen = false; // Close details if coming from there
        $this->isModalOpen = true;
    }

    public function saveUser()
    {
        $rules = [
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
            'username' => [
                'required', 'string', 'max:50',
                Rule::unique('users')->ignore($this->userId),
            ],
            'email' => [
                'required', 'email', 'max:50',
                Rule::unique('users')->ignore($this->userId),
            ],
            'isAdmin' => 'boolean',
        ];

        if (!$this->isEditMode || $this->password) {
            $rules['password'] = 'required|string|min:8|max:80';
        }

        $validated = $this->validate($rules);

        if ($this->isEditMode) {
            $user = User::find($this->userId);
            $user->update([
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'username' => $this->username,
                'email' => $this->email,
                'isAdmin' => $this->isAdmin,
                'isActive' => $this->isActive,
            ]);

            if ($this->password) {
                $user->update(['password' => Hash::make($this->password)]);
            }

            session()->flash('success', 'User updated successfully.');
        } else {
            User::create([
                'firstname' => $this->firstname,
                'lastname' => $this->lastname,
                'username' => $this->username,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'isAdmin' => $this->isAdmin,
            ]);

            session()->flash('success', 'User created successfully.');
        }

        $this->closeModal();
    }

    public function toggleUserStatus()
    {
        $user = User::find($this->targetUserId);

        if ($user->id === auth()->id()) {
            session()->flash('error', 'You cannot deactivate yourself.');
            $this->closeConfirmModal();
            return;
        }

        $user->isActive = !$user->isActive;
        $user->save();

        session()->flash('success', 'User ' . ($user->isActive ? 'activated' : 'deactivated') . ' successfully.');
        $this->closeConfirmModal();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('firstname', 'like', '%' . $this->search . '%')
                        ->orWhere('lastname', 'like', '%' . $this->search . '%')
                        ->orWhere('username', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->roleFilter !== '', function ($query) {
                $query->where('isAdmin', $this->roleFilter === 'admin');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.user-management', [
            'users' => $users
        ])->layout('components.layouts.admin');
    }
}
