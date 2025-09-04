<?php

namespace App\Livewire\Contents;

use App\Models\Tricycle;
use Livewire\Component;
use Illuminate\Validation\Rule;

class TricycleManagement extends Component
{
    public $submit_func;

    public $tricycle;

    public $tricycle_id, $plate_number, $motorcycle_model, $color, $driver_id, $status, $device_id;

    public function getTricycle($tricycleId)
    {
        $this->tricycle = Tricycle::find($tricycleId);

        if ($this->tricycle) {
            $this->tricycle_id = $this->tricycle->tricycle_id;
            $this->plate_number = $this->tricycle->plate_number;
            $this->motorcycle_model = $this->tricycle->motorcycle_model;
            $this->color = $this->tricycle->color;
            $this->driver_id = $this->tricycle->driver_id;
            $this->status = $this->tricycle->status;
            $this->device_id = $this->tricycle->device_id;
        } else {
            session()->flash('error', 'Tricycle not found.');
        }
    }

    protected function rules()
    {
        return [
            'plate_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tricycles', 'plate_number')->ignore($this->tricycle_id, 'tricycle_id'),
            ],
            'motorcycle_model' => 'required|string|max:255',
            'color' => 'required|string|max:255',
            'driver_id' => 'nullable|integer|exists:drivers,driver_id',
            'status' => 'nullable|string|max:255',
            'device_id' => 'nullable|string|max:255',
        ];
    }

    public function render()
    {
        return view('livewire.contents.tricycle-management');
    }

    public function resetFields()
    {
        $this->reset([
            'plate_number', 'motorcycle_model', 'color', 'driver_id', 'status', 'device_id'
        ]);
    }

    public function submit_tricycle()
    {
        $this->validate();

        if ($this->submit_func == "add-tricycle") {
            Tricycle::create([
                'plate_number' => $this->plate_number,
                'motorcycle_model' => $this->motorcycle_model,
                'color' => $this->color,
                'driver_id' => $this->driver_id,
                'status' => $this->status ?? 'active',
                'device_id' => $this->device_id,
            ]);

            session()->flash('message', 'Tricycle successfully created.');
        } elseif ($this->submit_func == "edit-tricycle") {
            $this->tricycle->plate_number = $this->plate_number;
            $this->tricycle->motorcycle_model = $this->motorcycle_model;
            $this->tricycle->color = $this->color;
            $this->tricycle->driver_id = $this->driver_id;
            $this->tricycle->status = $this->status;
            $this->tricycle->device_id = $this->device_id;

            $this->tricycle->save();

            session()->flash('message', 'Tricycle successfully updated.');
        }

        return redirect()->route('tricycles');
    }
}
