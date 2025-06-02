<div class="modal fade" id="add-driver-modal" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-xl custom-modal-two">
        <div class="modal-content">
            <div class="page-wrapper-new p-0">
                <div class="content">
                    <div class="modal-header border-0 custom-modal-header">
                        <div class="page-title">
                            @if ($submit_func == 'add-driver')
                                <h4>Add Driver</h4>
                            @else
                                <h4>Edit Driver</h4>
                            @endif
                        </div>
                        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="submit_driver">
                            @csrf
                            <div class="card mb-0">
                                <div class="card-body">
                                    <div class="new-employee-field">
                                        <div class="card-title-head" wire:ignore>
                                            <h6>
                                                <span><i data-feather="info" class="feather-edit"></i></span>
                                                Driver Information
                                            </h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="name">Name</label>
                                                    <input type="text" class="form-control" placeholder="Enter name"
                                                        id="name" wire:model.lazy="name">
                                                    @error('name')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="license_number">License Number</label>
                                                    <input type="text" class="form-control" placeholder="Enter license number"
                                                        id="license_number" wire:model.lazy="license_number">
                                                    @error('license_number')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="phone">Phone</label>
                                                    <input type="text" class="form-control" placeholder="Enter phone"
                                                        id="" wire:model.lazy="phone">
                                                    @error('phone')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="address">Address</label>
                                                    <input type="text" class="form-control" placeholder="Enter address"
                                                        id="address" wire:model.lazy="address">
                                                    @error('address')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if ($submit_func == 'edit-driver')
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
                                            <div class="col-lg-6 col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="username">Username</label>
                                                    <input type="text" class="form-control" placeholder="Enter username"
                                                        id="username" wire:model.lazy="username">
                                                    @error('username')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="pass-info">
                                            <div class="card-title-head" wire:ignore>
                                                <h6>
                                                    <span><i data-feather="info" class="feather-edit"></i></span>
                                                    Password
                                                </h6>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6 input-blocks">
                                                    <label for="password">Password</label>
                                                    <div class="mb-3 pass-group">
                                                        <input type="password" class="pass-input" id="password"
                                                            wire:model.lazy="password"
                                                            placeholder="Enter password">
                                                        <span class="fas toggle-password fa-eye-slash"></span>
                                                    </div>
                                                    @error('password')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                <div class="col-lg-6 col-md-6 input-blocks">
                                                    <label for="password_confirmation">Confirm Password</label>
                                                    <div class="mb-3 pass-group">
                                                        <input type="password" class="pass-inputa"
                                                            id="password_confirmation"
                                                            wire:model.lazy="password_confirmation"
                                                            placeholder="Confirm password">
                                                        <span class="fas toggle-passworda fa-eye-slash"></span>
                                                    </div>
                                                    @error('password_confirmation')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            </div>
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
                handleDriverActions();
            });

            function initSelect() {
                $('.select').select2({
                    minimumResultsForSearch: -1,
                    width: '100%'
                });
            }
            function handleDriverActions() {
                $(document).on('change', '[id]', handleInputChange);
                $(document).on('click', '.add-driver', openAddDriverModal);
                $(document).on('click', '.edit-driver', openEditDriverModal);
            }

            function handleInputChange(e) {
                if ($(e.target).is('select') || $(e.target).is('.not_pass')) {
                    const property = e.target.id;
                    const value = e.target.value;
                    @this.set(property, value);

                    console.log(`${property}: ${value}`);
                }
            }

            function openAddDriverModal() {
                @this.set('submit_func', 'add-driver');

                @this.call('resetFields').then(() => {
                    initSelectVal("");
                    $('#add-driver-modal').modal('show');
                });
            }

            function openEditDriverModal() {
                const driverId = $(this).data('driverid');

                @this.set('submit_func', 'edit-driver');
                @this.call('getDriver', driverId).then(() => {
                    populateEditForm();
                    $('#add-driver-modal').modal('show');
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
