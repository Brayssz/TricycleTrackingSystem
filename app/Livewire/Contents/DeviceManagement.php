<?php

namespace App\Livewire\Contents;

use Livewire\Component;
use App\Models\Device;
use Illuminate\Validation\Rule;

class DeviceManagement extends Component
{
    public $submit_func;

    public $device;

    public $device_id, $device_name, $device_identifier, $status, $sim_number;

    public function getDevice($deviceId)
    {
        $this->device = Device::find($deviceId);

        if ($this->device) {
            $this->device_id = $this->device->device_id;
            $this->device_name = $this->device->device_name;
            $this->device_identifier = $this->device->device_identifier;
            $this->sim_number = $this->device->sim_number;
            $this->status = $this->device->status;
        } else {
            session()->flash('error', 'Device not found.');
        }
    }

    protected function rules()
    {
        return [
            'device_name' => 'required|string|max:255',
            'device_identifier' => [
                'required',
                'string',
                'max:255',
                Rule::unique('devices', 'device_identifier')->ignore($this->device_id, 'device_id'),
            ],
            'status' => 'nullable|string|max:255',
            'sim_number' => 'nullable|string|max:255',
        ];
    }

    public function resetFields()
    {
        $this->reset([
            'device_name', 'device_identifier', 'status', 'sim_number'
        ]);
    }

    public function submit_device()
    {
        $this->validate();

        if ($this->submit_func == "add-device") {
            Device::create([
                'device_name' => $this->device_name,
                'device_identifier' => $this->device_identifier,
                'status' => $this->status ?? 'active',
                'sim_number' => $this->sim_number,
            ]);

            session()->flash('message', 'Device successfully created.');
        } elseif ($this->submit_func == "edit-device") {
            $this->device->device_name = $this->device_name;
            $this->device->device_identifier = $this->device_identifier;
            $this->device->status = $this->status;
            $this->device->sim_number = $this->sim_number;
            $this->device->save();

            session()->flash('message', 'Device successfully updated.');
        }

        return redirect()->route('devices');
    }

    public function render()
    {
        return view('livewire.contents.device-management');
    }
}
