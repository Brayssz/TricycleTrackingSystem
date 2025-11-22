<div>
    @push('scripts')
        <script>
            let view_map;
            let markers = [];
            let markerData = [];
            let koronadalPolygon = null;


            $(document).ready(function() {
                initializeSelect2();

                // Store current filters
                let currentDriverId = null;
                let currentTricycleId = null;
                let currentPlateSearch = "";

                setInterval(() => {

                    getTricycleLocations(currentDriverId, currentTricycleId, currentPlateSearch, false);
                }, 1000);

                view_map = initMap();

                // Driver filter
                $('.driver_filter').on('change', function() {
                    currentDriverId = $(this).val() || null; // convert empty string to null
                    console.log('Driver filter changed:', currentDriverId);

                    getTricycleLocations(currentDriverId, currentTricycleId, currentPlateSearch, true);
                });

                // Tricycle filter
                $('.tricycle_filter').on('change', function() {
                    currentTricycleId = $(this).val();
                    console.log('Tricycle filter changed:', currentTricycleId);
                    getTricycleLocations(currentDriverId, currentTricycleId, currentPlateSearch, true);
                });

                // Plate search
                $('.search-plate').on('keyup', function() {
                    currentPlateSearch = $(this).val();
                    getTricycleLocations(currentDriverId, currentTricycleId, currentPlateSearch, true);
                });
            });

            const koronadalBoundary = [
                [6.40, 124.78],
                [6.40, 124.97],
                [6.60, 124.97],
                [6.60, 124.78],
                [6.40, 124.78]
            ];

            function isOutsideKoronadal(lat, lng) {
                const point = L.latLng(lat, lng);
                return !koronadalPolygon.getBounds().contains(point);
            }

            function focusFirstMarker() {
                console.log(markerData);
                if (markerData.length > 0) {
                    const firstTricycle = markerData[0];
                    view_map.setView([firstTricycle.lat, firstTricycle.lng], 15);
                }
            }

            function initializeSelect2() {
                $('.select2-department').select2({
                    placeholder: "Filter by department",
                    allowClear: true
                });
                $('.select2-driver').select2({
                    placeholder: "Filter by driver",
                    allowClear: true
                });
            }

            function getTricycleLocations(driver_id = null, tricycle_id = null, plate_search = "", isfiltered = false) {
                @this.call('getTricycleLocations', driver_id, tricycle_id).then(response => {
                    // Apply plate search filter on client-side if needed
                    let filtered = response;
                    if (plate_search && plate_search.trim().length > 0) {
                        filtered = filtered.filter(tricycle =>
                            tricycle.plate_number.toLowerCase().includes(plate_search.toLowerCase())
                        );
                    }

                    updateMarkers(view_map, filtered, isfiltered);
                });
            }

            function initMap() {
                const map = L.map('view_map').setView([6.497396, 124.847160], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(map);

                L.Control.geocoder({
                    defaultMarkGeocode: false
                }).on('markgeocode', function(e) {
                    const latlng = e.geocode.center;
                    map.setView(latlng, 11);
                }).addTo(map);

                koronadalPolygon = L.polygon(koronadalBoundary, {
                    color: "green",
                    fillOpacity: 0.05
                });

                return map;
            }

            function updateMarkers(map, tricycles = [], isfiltered = false) {
                console.log('Updating markers with data:', tricycles);
                markerData = [];
                markerData = tricycles;

                // Remove existing circles and markers
                map.eachLayer(layer => {
                    if (layer instanceof L.Circle) map.removeLayer(layer);
                });

                markers.forEach(m => map.removeLayer(m.marker));
                markers = [];

                tricycles.forEach(tricycle => {
                    const newMarker = createMarker(map, tricycle);

                    const outside = isOutsideKoronadal(tricycle.lat, tricycle.lng);

                    markers.push({
                        tricycleId: tricycle.plate_number,
                        marker: newMarker
                    });

                    const markerColor = tricycle.status === 'ontravel' ? 'red' : '#3388ff';
                    L.circle([tricycle.lat, tricycle.lng], {
                        color: markerColor,
                        fillColor: markerColor,
                        opacity: 0.2,
                        fillOpacity: 0.2,
                        radius: 200
                    }).addTo(map);

                    if (outside) {
                        console.warn(`⚠ TRICYCLE ${tricycle.plate_number} LEFT KORONADAL CITY!`);
                        notifyTricycle(tricycle.plate_number);
                        @this.call('logOutside', tricycle.tricycle_id, tricycle.driver_id, tricycle.lat, tricycle.lng)
                        // You may trigger sound, send to Livewire, or show alert
                    }
                });

                if (isfiltered) {
                    focusFirstMarker();
                }

            }

            const notifiedTricycles = new Set();

            function notifyTricycle(plateNumber) {
                // Only notify if this plate number hasn't been notified yet
                if (notifiedTricycles.has(plateNumber)) return;

                notifiedTricycles.add(plateNumber);

                // Play a default beep using Web Audio API
                const context = new(window.AudioContext || window.webkitAudioContext)();
                const o = context.createOscillator();
                const g = context.createGain();
                o.type = 'sine';
                o.frequency.value = 440; // frequency in Hz
                o.connect(g);
                g.connect(context.destination);
                o.start();
                o.stop(context.currentTime + 0.2);

                // Show Toastr notification
                toastr.options = {
                    "closeButton": true,
                    "newestOnTop": true,
                    "progressBar": true,
                    "positionClass": "toast-bottom-right",
                    "timeOut": "20000", // 20 seconds
                    "extendedTimeOut": "20000", // 20 seconds if hovered
                    "showMethod": "fadeIn",
                    "hideMethod": "fadeOut"
                };

                toastr.warning(`⚠ TRICYCLE ${plateNumber} LEFT KORONADAL CITY!`, "Tricycle Alert");
            }



            function createMarker(map, tricycle) {
                const initials = tricycle.plate_number.split(' ')
                    .map(word => word[0])
                    .join('');
                const markerColor = tricycle.status === 'ontravel' ? 'red' : '#3388ff';

                const icon = L.divIcon({
                    className: 'custom-div-icon',
                    html: `<div style="width: 45px; height: 45px; background-color: ${markerColor}; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 12px; padding: 10px; border: 4px solid white; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                                ${initials}
                           </div>`,
                    iconSize: [45, 45]
                });

                const marker = L.marker([tricycle.lat, tricycle.lng], {
                    icon: icon
                }).addTo(map);

                marker.bindTooltip(tricycle.plate_number, {
                    permanent: false,
                    direction: 'top'
                });

                marker.bindPopup(`
                    <div style="min-width:200px; font-family: 'Segoe UI', Arial, sans-serif; padding: 0;">
                        <div style="display: flex; align-items: center; margin-bottom: 10px;">
                            <div style="width: 38px; height: 38px; background: linear-gradient(135deg, #007bff 60%, #3388ff 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 700; font-size: 16px; margin-right: 12px;">
                                ${initials}
                            </div>
                            <div>
                                <div style="font-size: 15px; font-weight: 600; color: #222;">
                                    ${tricycle.plate_number}
                                </div>
                                <div style="font-size: 12px; color: #888;">
                                    Plate Number
                                </div>
                            </div>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <span style="font-weight: 500; color: #555;">Driver:</span>
                            <span style="color: #222;">${tricycle.driver_name ?? 'N/A'}</span>
                        </div>
                        <div style="font-size: 12px; color: #888;">
                            <span style="font-weight: 500;">Last Location Update:</span>
                            <span>${tricycle.last_update ?? 'N/A'}</span>
                        </div>
                    </div>
                `);

                return marker;
            }
        </script>
    @endpush
</div>
