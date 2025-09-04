<div class="modal fade" id="add-tricycle-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            @if ($submit_func == 'add-tricycle')
                                <h4>Add Tricycle</h4>
                            @else
                                <h4>Edit Tricycle</h4>
                            @endif
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit_tricycle">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-employee-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6>
                                                <span><i data-feather="info" class="feather-edit"></i></span>
                                                Tricycle Information
                                            </h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="plate_number">Plate Number</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter plate number" id="plate_number"
                                                        wire:model.lazy="plate_number">
                                                    @error('plate_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="motorcycle_model">Motorcycle
                                                        Model</label>
                                                    <input type="text" class="form-control" placeholder="Enter model"
                                                        id="motorcycle_model" wire:model.lazy="motorcycle_model">
                                                    @error('motorcycle_model')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="color">Color</label>
                                                    <input type="text" class="form-control" placeholder="Enter color"
                                                        id="color" wire:model.lazy="color">
                                                    @error('color')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="driver_id">Driver</label>
                                                    <div wire:ignore>
                                                        <select class="select" id="driver_id" name="driver_id"
                                                            wire:model="driver_id">
                                                            <option value="">Choose</option>
                                                            @foreach (\App\Models\Driver::all() as $driver)
                                                                <option value="{{ $driver->driver_id }}">
                                                                    {{ $driver->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('driver_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="device_id">Device</label>
                                                    <div wire:ignore>
                                                        <select class="select" id="device_id" name="device_id" wire:model="device_id">
                                                            <option value="">Choose</option>
                                                            @foreach (\App\Models\Device::where('status', 'active')->get() as $device)
                                                                <option value="{{ $device->device_id }}">
                                                                    {{ $device->device_name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    @error('device_id')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if ($submit_func == 'edit-tricycle')
                                                <div class="col-lg-6 col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label" for="status">Status</label>
                                                        <div wire:ignore>
                                                            <select class="select" id="status" name="status"
                                                                wire:model="status">
                                                                <option value="">Choose</option>
                                                                <option value="active">Active</option>
                                                                <option value="inactive">Inactive</option>
                                                            </select>
                                                        </div>
                                                        @error('status')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer-btn mb-4 mt-0">
                                <button type="button" class="btn btn-cancel me-2"
                                    data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-submit">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                handleTricycleActions();
            });

            function initSelect() {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }

            function handleTricycleActions() {
                $(document).on('change', '[id]', handleInputChange);
                $(document).on('click', '.add-tricycle', openAddTricycleModal);
                $(document).on('click', '.edit-tricycle', openEditTricycleModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                }
            }

            function openAddTricycleModal() {
                @this.set('submit_func', 'add-tricycle');
                @this.call('resetFields').then(() => {
                    initSelectVal("");
                    $('#add-tricycle-modal').modal('show');
                });
            }

            function openEditTricycleModal() {
                const tricycleId = $(this).data('tricycleid');
                @this.set('submit_func', 'edit-tricycle');
                @this.call('getTricycle', tricycleId).then(() => {
                    populateEditForm();
                    $('#add-tricycle-modal').modal('show');
                });
            }

            function initSelectVal(status) {
                $('#status').val(status).change();
            }

            function populateEditForm() {
                const status = @this.get('status');
                initSelect();
                initSelectVal(status);
            }
        </script>
    @endpush
</div>
