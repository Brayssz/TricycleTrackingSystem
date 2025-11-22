<?php

namespace App\Livewire\Contents;

use App\Models\Driver;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class DriverManagement extends Component
{
    public $submit_func;

    public $driver;

    public $driver_id, $name, $license_number, $phone, $address, $status;
    public $username;

    public function getDriver($driverId)
    {
        $this->driver = Driver::find($driverId);

        if ($this->driver) {
            $this->driver_id = $this->driver->driver_id;
            $this->name = $this->driver->name;
            $this->license_number = $this->driver->license_number;
            $this->phone = $this->driver->phone;
            $this->address = $this->driver->address;
            $this->status = $this->driver->status;
            $this->username = $this->driver->username;
            // Do not populate password for security reasons
        } else {
            session()->flash('error', 'Driver not found.');
        }
    }

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'license_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('drivers', 'license_number')->ignore($this->driver_id, 'driver_id'),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:255',
            'username' => 'required|string|max:255|unique:drivers,username,' . $this->driver_id . ',driver_id'
        ];
    }

    public function render()
    {
        return view('livewire.contents.driver-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'license_number', 'phone', 'address', 'status', 'username'
        ]);
    }

    public function submit_driver()
    {
        $this->validate();

        if ($this->submit_func == "add-driver") {
            Driver::create([
                'name' => $this->name,
                'license_number' => $this->license_number,
                'phone' => $this->phone,
                'address' => $this->address,
                'status' => $this->status ?? 'active',
                'username' => $this->username,
            ]);

            session()->flash('message', 'Driver successfully created.');
        } elseif ($this->submit_func == "edit-driver") {
            $this->driver->name = $this->name;
            $this->driver->license_number = $this->license_number;
            $this->driver->phone = $this->phone;
            $this->driver->address = $this->address;
            $this->driver->status = $this->status;
            $this->driver->username = $this->username;

            $this->driver->save();

            session()->flash('message', 'Driver successfully updated.');
        }

        return redirect()->route('drivers');
    }
}
