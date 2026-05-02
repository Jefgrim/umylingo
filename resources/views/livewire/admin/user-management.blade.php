<div>
    <style>
        .show-mobile { display: none !important; }
        
        @media (max-width: 768px) {
            /* Aggressive overrides ONLY for mobile */
            html, body { overflow-x: hidden; width: 100vw; position: relative; }
            
            .main-container { height: auto !important; min-height: 100vh; display: block !important; width: 100vw !important; overflow-x: hidden; }
            .main { 
                display: block !important; 
                width: 100% !important; 
                max-width: 100% !important; 
                padding: 15px !important; 
                margin: 0 !important;
                overflow-x: hidden !important;
                box-sizing: border-box !important;
            }

            .um-header { flex-direction: column; align-items: flex-start !important; gap: 10px; width: 100%; box-sizing: border-box; }
            .um-filters { flex-direction: column; align-items: stretch !important; gap: 10px; width: 100%; box-sizing: border-box; }
            .um-filter-item { width: 100% !important; flex: none !important; margin: 0 !important; }
            .um-modal-grid { grid-template-columns: 1fr !important; }
            
            .hide-mobile { display: none !important; }
            .show-mobile { display: block !important; }
            
            .um-table-container { 
                margin: 0 !important; 
                width: 100% !important;
                border-radius: 4px !important;
                overflow-x: hidden !important; 
                box-sizing: border-box !important;
            }
            
            table { width: 100% !important; min-width: 100% !important; table-layout: fixed; }
            td, th { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        }
    </style>

    <div class="main-container-header um-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="margin: 0;">User Management</h2>
        <button wire:click="createUser" class="btn-filter" style="width: auto; padding: 10px 20px;">
            + Add User
        </button>
    </div>

    @if (session()->has('success'))
        <div style="background-color: #d1e7dd; color: #0f5132; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #badbcc;">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div style="background-color: #f8d7da; color: #842029; padding: 10px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c2c7;">
            {{ session('error') }}
        </div>
    @endif

    <div class="logs-filter-section um-filters" style="display: flex; gap: 15px; align-items: center; margin-bottom: 20px; width: 100%; box-sizing: border-box;">
        <div class="date-input-group um-filter-item" style="flex: 1;">
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search by name, username or email..." class="date-input" style="width: 100%; box-sizing: border-box;">
        </div>
        <div class="date-input-group um-filter-item" style="width: 200px;">
            <select wire:model.live="roleFilter" class="date-input" style="width: 100%; box-sizing: border-box;">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="learner">Learner</option>
            </select>
        </div>
    </div>

    <div class="table-card um-table-container" style="padding: 0; overflow-x: auto; background: white; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
        <table style="width: 100%; border-collapse: collapse; text-align: left; min-width: 800px;">
            <thead>
                <tr style="background-color: #f8fafb; border-bottom: 2px solid #eeeeee;">
                    <th style="padding: 15px; color: #0c5894;">Name</th>
                    <th style="padding: 15px; color: #0c5894;">Role</th>
                    <th class="hide-mobile" style="padding: 15px; color: #0c5894;">Username</th>
                    <th class="hide-mobile" style="padding: 15px; color: #0c5894;">Email</th>
                    <th class="hide-mobile" style="padding: 15px; color: #0c5894;">Status</th>
                    <th style="padding: 15px; color: #0c5894;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr style="border-bottom: 1px solid #eeeeee; {{ !$user->isActive ? 'background-color: #f9f9f9; opacity: 0.8;' : '' }}">
                        <td style="padding: 15px;">{{ $user->firstname }} {{ $user->lastname }}</td>
                        <td style="padding: 15px;">
                            <span style="font-size: 0.85rem; padding: 4px 10px; border-radius: 4px; background: #f0f4f8; color: #0c5894; border: 1px solid #d1d9e6;">
                                {{ $user->isAdmin ? 'Admin' : 'Learner' }}
                            </span>
                        </td>
                        <td class="hide-mobile" style="padding: 15px;">{{ $user->username }}</td>
                        <td class="hide-mobile" style="padding: 15px;">{{ $user->email }}</td>
                        <td class="hide-mobile" style="padding: 15px;">
                            <button wire:click="openConfirmModal({{ $user->id }})" 
                                    class="deck-pill" 
                                    style="border: none; cursor: pointer; background-color: {{ $user->isActive ? '#10b981' : '#6b7280' }}; color: white; transition: transform 0.2s;"
                                    onmouseover="this.style.transform='scale(1.05)'" 
                                    onmouseout="this.style.transform='scale(1)'">
                                {{ $user->isActive ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td style="padding: 15px;">
                            <button wire:click="editUser({{ $user->id }})" 
                                    class="deck-pill hide-mobile" 
                                    style="border: none; cursor: pointer; background-color: #0c5894; color: white; transition: transform 0.2s;"
                                    onmouseover="this.style.transform='scale(1.05)'" 
                                    onmouseout="this.style.transform='scale(1)'">
                                Edit
                            </button>
                            <button wire:click="openDetailsModal({{ $user->id }})" 
                                    class="deck-pill show-mobile" 
                                    style="border: none; cursor: pointer; background-color: #0c5894; color: white; padding: 6px 12px; font-size: 0.8rem;">
                                Manage
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="padding: 30px; text-align: center; color: #6b7280;">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 20px;">
        {{ $users->links() }}
    </div>

    <!-- User Modal -->
    <div class="deck-modal" style="{{ $isModalOpen ? 'display: flex;' : 'display: none;' }}">
        <div class="deck-modal-content" style="max-width: 600px;">
            <div class="deck-modal-header">
                <div>
                    <h3 class="deck-modal-title">{{ $isEditMode ? 'Edit User' : 'Add New User' }}</h3>
                    <p class="deck-modal-desc">{{ $isEditMode ? 'Update account details for ' . $username : 'Create a new account for the platform.' }}</p>
                </div>
                <button wire:click="closeModal" class="deck-modal-close">&times;</button>
            </div>

            <form wire:submit.prevent="saveUser" style="display: flex; flex-direction: column; gap: 15px;">
                <div class="um-modal-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                    <div class="date-input-group">
                        <label>First Name</label>
                        <input type="text" wire:model="firstname" class="date-input">
                        @error('firstname') <span style="color: #ad3324; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                    <div class="date-input-group">
                        <label>Last Name</label>
                        <input type="text" wire:model="lastname" class="date-input">
                        @error('lastname') <span style="color: #ad3324; font-size: 12px;">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="date-input-group">
                    <label>Username</label>
                    <input type="text" wire:model="username" class="date-input">
                    @error('username') <span style="color: #ad3324; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="date-input-group">
                    <label>Email Address</label>
                    <input type="email" wire:model="email" class="date-input">
                    @error('email') <span style="color: #ad3324; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div class="date-input-group">
                    <label>Password {{ $isEditMode ? '(Leave blank to keep current)' : '' }}</label>
                    <input type="password" wire:model="password" class="date-input">
                    @error('password') <span style="color: #ad3324; font-size: 12px;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 20px; margin-top: 10px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" wire:model="isAdmin" id="isAdminCheckbox" style="width: 20px; height: 20px; cursor: pointer;">
                        <label for="isAdminCheckbox" style="cursor: pointer; font-weight: 600; color: #0c5894;">Admin Access</label>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" wire:model="isActive" id="isActiveCheckbox" style="width: 20px; height: 20px; cursor: pointer;">
                        <label for="isActiveCheckbox" style="cursor: pointer; font-weight: 600; color: #0c5894;">Account Active</label>
                    </div>
                </div>

                <div class="deck-modal-grid um-modal-grid" style="margin-top: 10px;">
                    <button type="button" wire:click="closeModal" class="btn-clear">Cancel</button>
                    <button type="submit" class="btn-filter">
                        {{ $isEditMode ? 'Update User' : 'Create User' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="deck-modal" style="{{ $isConfirmModalOpen ? 'display: flex;' : 'display: none;' }}">
        <div class="deck-modal-content" style="max-width: 400px; text-align: center;">
            <div class="deck-modal-header" style="justify-content: center;">
                <h3 class="deck-modal-title">Confirm Action</h3>
            </div>
            <p style="margin-bottom: 25px; color: #4b5563;">
                Are you sure you want to <strong>{{ $targetUserId && \App\Models\User::find($targetUserId)?->isActive ? 'deactivate' : 'activate' }}</strong> this user?
            </p>
            <div style="display: flex; gap: 15px; justify-content: center;">
                <button type="button" wire:click="closeConfirmModal" class="btn-clear" style="margin: 0; flex: 1;">Cancel</button>
                <button type="button" wire:click="toggleUserStatus" class="btn-filter" style="margin: 0; flex: 1; background-color: {{ $targetUserId && \App\Models\User::find($targetUserId)?->isActive ? '#ad3324' : '#10b981' }}; border-color: {{ $targetUserId && \App\Models\User::find($targetUserId)?->isActive ? '#ad3324' : '#10b981' }};">
                    Confirm
                </button>
            </div>
        </div>
    </div>
    <!-- User Details Modal (Mobile Only) -->
    <div class="deck-modal" style="{{ $isDetailsModalOpen ? 'display: flex;' : 'display: none;' }}">
        <div class="deck-modal-content" style="max-width: 450px;">
            <div class="deck-modal-header">
                <div>
                    <h3 class="deck-modal-title">User Details</h3>
                    <p class="deck-modal-desc">Quick management for {{ $selectedUser?->firstname }}</p>
                </div>
                <button wire:click="closeDetailsModal" class="deck-modal-close">&times;</button>
            </div>

            @if($selectedUser)
                <div style="background: #f8fafb; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                    <div style="display: flex; flex-direction: column; gap: 12px;">
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                            <span style="color: #6b7280; font-size: 0.9rem;">Full Name:</span>
                            <span style="font-weight: 600; color: #111827;">{{ $selectedUser->firstname }} {{ $selectedUser->lastname }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                            <span style="color: #6b7280; font-size: 0.9rem;">Username:</span>
                            <span style="font-weight: 600; color: #111827;">{{ $selectedUser->username }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 8px;">
                            <span style="color: #6b7280; font-size: 0.9rem;">Email:</span>
                            <span style="font-weight: 600; color: #111827;">{{ $selectedUser->email }}</span>
                        </div>
                        <div style="display: flex; justify-content: space-between; border-bottom: 1px solid #eee; padding-bottom: 8px; align-items: center;">
                            <span style="color: #6b7280; font-size: 0.9rem;">Role:</span>
                            <span class="deck-pill" style="padding: 4px 12px; font-size: 0.8rem; background-color: {{ $selectedUser->isAdmin ? '#ad3324' : '#10b981' }}; color: white; border: none;">
                                {{ $selectedUser->isAdmin ? 'Admin' : 'Learner' }}
                            </span>
                        </div>
                        <div style="display: flex; justify-content: space-between;">
                            <span style="color: #6b7280; font-size: 0.9rem;">Status:</span>
                            <span class="deck-pill" style="padding: 2px 8px; font-size: 0.8rem; background-color: {{ $selectedUser->isActive ? '#10b981' : '#6b7280' }}; color: white; border: none;">
                                {{ $selectedUser->isActive ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div style="display: flex; flex-direction: column; gap: 10px;">
                    <button wire:click="editUser({{ $selectedUser->id }})" class="btn-filter" style="margin: 0; background-color: #0c5894; border-color: #0c5894;">
                        Edit User Profile
                    </button>
                    @if($selectedUser->id !== auth()->id())
                        <button wire:click="openConfirmModal({{ $selectedUser->id }})" class="btn-filter" style="margin: 0; background-color: {{ $selectedUser->isActive ? '#ad3324' : '#10b981' }}; border-color: {{ $selectedUser->isActive ? '#ad3324' : '#10b981' }};">
                            {{ $selectedUser->isActive ? 'Deactivate User' : 'Activate User' }}
                        </button>
                    @endif
                    <button wire:click="closeDetailsModal" class="btn-clear" style="margin: 0;">Close</button>
                </div>
            @endif
        </div>
    </div>
</div>
