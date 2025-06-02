<?php

namespace App\Livewire\Contents;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    public $submit_func;

    public $user;

    public $user_id, $name, $email, $position, $status, $password, $password_confirmation;

    public function getUser($userId)
    {
        $this->user = User::find($userId);

        if ($this->user) {
            $this->user_id = $this->user->id;
            $this->name = $this->user->name;
            $this->email = $this->user->email;
            $this->position = $this->user->position;
            $this->status = $this->user->status;

            $this->password = null;
            $this->password_confirmation = null;
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    protected function rules()
    {
        $passwordRules = $this->user_id
            ? 'nullable|string|min:8|confirmed'
            : 'required|string|min:8|confirmed';

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user_id, 'id'),
            ],
            // 'position' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'password' => $passwordRules,
        ];
    }

    public function render()
    {
        return view('livewire.contents.user-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'email', 'position', 'status', 'password', 'password_confirmation'
        ]);
    }

    public function submit_user()
    {
        $this->validate();

        if ($this->submit_func == "add-user") {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'position' => 'admin', // Default position for new users
                'status' => 'active',
                'password' => bcrypt($this->password),
            ]);

            session()->flash('message', 'User successfully created.');
        } elseif ($this->submit_func == "edit-user") {
            $this->user->name = $this->name;
            $this->user->email = $this->email;
            // $this->user->position = $this->position;
            $this->user->status = $this->status;

            if (!empty($this->password)) {
                $this->user->password = bcrypt($this->password);
            }

            $this->user->save();

            session()->flash('message', 'User successfully updated.');
        }

        return redirect()->route('users');
    }
}
