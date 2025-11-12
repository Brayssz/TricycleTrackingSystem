@extends('layouts.app-layout')

@section('title', 'Dashboard')

@section('content')
    @livewire('tracking.location-tracker')
    <div class="content">
        <div class="row">
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-users" style="color: #ffc107; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_users }}">{{ $total_users }}</span>
                        </h5>
                        <h6>Total Users</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-id-card" style="color: #28C76F; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_drivers }}">{{ $total_drivers }}</span>
                        </h5>
                        <h6>Total Drivers</h6>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-sm-6 col-12 d-flex">
                <div class="dash-widget w-100">
                    <div class="dash-widgetimg">
                        <span><i class="fas fa-motorcycle" style="color: #007bff; font-size: 1.3rem;"></i></span>
                    </div>
                    <div class="dash-widgetcontent">
                        <h5>
                            <span class="counters" data-count="{{ $total_tricycles }}">{{ $total_tricycles }}</span>
                        </h5>
                        <h6>Total Tricycles</h6>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">

            <div class="card flex-fill default-cover mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Tricycle Tracking</h4>

                </div>
                <div class="card-body">
                    <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1 mb-3">
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group ">
                                <select class="select driver_filter form-control">
                                    <option value="">Filter by Driver</option>
                                    @php
                                        $active_drivers = \App\Models\Driver::where('status', 'active')->get();
                                        $active_tricycles = \App\Models\Tricycle::where('status', 'active')->get();
                                    @endphp

                                    @foreach ($active_drivers as $driver)
                                        <option value="{{ $driver->driver_id }}">{{ $driver->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12">
                            <div class="form-group ">
                                <select class="select tricycle_filter form-control">
                                    <option value="">Filter by Tricycle</option>
                                    @foreach ($active_tricycles as $tricycle)
                                        <option value="{{ $tricycle->tricycle_id }}">{{ $tricycle->plate_number }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                     
                        <!-- Required JS/CSS for daterangepicker -->

                    </div>
                    <div class="rounded-3" id="view_map" style="height: 700px;"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function() {
            function setDateTime(val) {
                $('#exactdatetime').val(val ? val : '');
            }

            $('#exactdatetime').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: true,
                timePicker24Hour: true,
                timePickerSeconds: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY-MM-DD HH:mm:ss'
                }
            });

            $('#exactdatetime').data('daterangepicker').container.hide();

            let customBtns = `
                <div class="daterangepicker-custom-btns" style="padding: 8px 12px;">
                    <button type="button" class="btn btn-sm btn-primary me-2" id="set-current-datetime">Current Date & Time</button>
                    <button type="button" class="btn btn-sm btn-secondary" id="set-custom-datetime">Custom</button>
                </div>
            `;

            // Show custom buttons on input focus
            $('#exactdatetime').on('focus', function() {
                $('#exactdatetime').data('daterangepicker').show();
            });

            $('#exactdatetime').on('show.daterangepicker', function(ev, picker) {
                // Hide calendar and timepicker by default
                picker.container.find('.calendar-table').closest('.drp-calendar').hide();
                picker.container.find('.timepicker').hide();

                if (!$('.daterangepicker-custom-btns').length) {
                    $('.daterangepicker').prepend(customBtns);

                    $('#set-current-datetime').on('click', function() {
                        setDateTime('Current');
                        $('#exactdatetime').data('daterangepicker').hide();
                    });

                    $('#set-custom-datetime').on('click', function() {
                        picker.container.find('.calendar-table').closest('.drp-calendar').show();
                        picker.container.find('.timepicker').show();

                        picker.container.find('.drp-calendar.right').hide();
                    });
                }
            });

            $('#exactdatetime').on('apply.daterangepicker', function(ev, picker) {
                setDateTime(picker.startDate.format('YYYY-MM-DD HH:mm:ss'));
            });

            $('#exactdatetime').on('cancel.daterangepicker', function(ev, picker) {
                setDateTime('');
            });
        });
    </script>
@endpush
