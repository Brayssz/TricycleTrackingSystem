@extends('layouts.app-layout')

@section('title', 'Dashboard')

@section('content')

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

            <div class="card flex-fill default-cover mb-4" >
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Tricycle Tracking</h4>
                       
                    </div>
                    <div class="card-body">
                        <div class="row mt-sm-3 mt-xs-3 mt-lg-0 w-sm-100 flex-grow-1 mb-3">
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group ">
                                    <select class="select status_filter form-control">
                                        <option value="">Filter by Driver</option>
                                        @php
                                            $active_drivers = \App\Models\Driver::where('status', 'active')->get();
                                            $active_tricycles = \App\Models\Tricycle::where('status', 'active')->get();
                                        @endphp

                                        @foreach($active_drivers as $driver)
                                            <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-3 col-sm-12">
                                <div class="form-group ">
                                    <select class="select status_filter form-control">
                                        <option value="">Filter by Tricycle</option>
                                        @foreach($active_tricycles as $tricycle)
                                            <option value="{{ $tricycle->id }}">{{ $tricycle->plate_number }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="rounded-3" id="view_map" style="height: 700px;"></div>
                    </div>
                </div>
            
        </div>


    </div>
@endsection
@push('scripts')
    
    <script>
        $(document).ready(function() {
            // Koronadal City coordinates: 6.5004° N, 124.8467° E
            var map = L.map('view_map').setView([6.5004, 124.8467], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
        });
    </script>
@endpush
