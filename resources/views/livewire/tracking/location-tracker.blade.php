<div>
    @push('scripts')
        <script>
            let view_map;
            let markers = [];
            let markerData = [];

            $(document).ready(function() {
                initializeSelect2();

                setInterval(() => {
                    getTricycleLocations();
                }, 500);

                view_map = initMap();
            });

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

            $('.driver_filter').on('change', function() {
                const driverId = $(this).val();
                getTricycleLocations(driverId, null);

                if (markerData.length > 0) {
                    const firstTricycle = markerData[0];
                    view_map.setView([firstTricycle.lat, firstTricycle.lng], 15);
                }
            });

            $('.tricycle_filter').on('change', function() {
                const tricycleId = $(this).val();

                getTricycleLocations(null, tricycleId);

                if (markerData.length > 0) {
                    const firstTricycle = markerData[0];
                    view_map.setView([firstTricycle.lat, firstTricycle.lng], 15);
                }
            });

            $('.search-plate').on('keyup', function() {
                const searchValue = $(this).val();

                if (searchValue.length > 0) {
                    searchMarkerByPlate(searchValue);
                }
            });

            function getTricycleLocations(driver_id = null, tricycle_id = null) {
                @this.call('getTricycleLocations', driver_id, tricycle_id).then(response => {
                    updateMarkers(view_map, response);
                });
            }

            function initMap() {
                const view_map = L.map('view_map').setView([6.497396, 124.847160], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                }).addTo(view_map);

                L.Control.geocoder({
                    defaultMarkGeocode: false
                }).on('markgeocode', function(e) {
                    const latlng = e.geocode.center;
                    view_map.setView(latlng, 11);
                }).addTo(view_map);

                return view_map;
            }

            function updateMarkers(view_map, tricycles = []) {
                markerData = tricycles;

                // Clear existing circles
                view_map.eachLayer(layer => {
                    if (layer instanceof L.Circle) {
                        view_map.removeLayer(layer);
                    }
                });

                tricycles.forEach(tricycle => {
                    const existingMarker = markers.find(marker => marker.tricycleId === tricycle.plate_number);

                    if (existingMarker) {
                        animateMarker(existingMarker.marker, [tricycle.lat, tricycle.lng]);
                    } else {
                        const newMarker = createMarker(view_map, tricycle);
                        markers.push({
                            tricycleId: tricycle.plate_number,
                            marker: newMarker
                        });
                    }

                    const markerColor = tricycle.status === 'ontravel' ? 'red' : '#3388ff';
                    L.circle([tricycle.lat, tricycle.lng], {
                        color: markerColor,
                        fillColor: markerColor,
                        opacity: 0.2,
                        fillOpacity: 0.2,
                        radius: 200
                    }).addTo(view_map);
                });
            }

            function createMarker(view_map, tricycle) {
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
                }).addTo(view_map);

                marker.bindTooltip(tricycle.plate_number, {
                    permanent: false,
                    direction: 'top'
                });

                marker.bindPopup(`<b>Plate: ${tricycle.plate_number}</b><br>Driver: ${tricycle.driver_name ?? 'N/A'}`);

                return marker;
            }

            function animateMarker(marker, newLatLng) {
                const duration = 500;
                const frames = 20;
                const interval = duration / frames;

                const startLatLng = marker.getLatLng();
                const deltaLat = (newLatLng[0] - startLatLng.lat) / frames;
                const deltaLng = (newLatLng[1] - startLatLng.lng) / frames;

                let frame = 0;

                const animation = setInterval(() => {
                    if (frame < frames) {
                        const lat = startLatLng.lat + deltaLat * frame;
                        const lng = startLatLng.lng + deltaLng * frame;
                        marker.setLatLng([lat, lng]);
                        frame++;
                    } else {
                        clearInterval(animation);
                        marker.setLatLng(newLatLng);
                    }
                }, interval);
            }

            function searchMarkerByPlate(plate) {
                const tricycle = markerData.find(tricycle => tricycle.plate_number.toLowerCase().trim().includes(plate.toLowerCase().trim()));
                if (tricycle) {
                    view_map.setView([tricycle.lat, tricycle.lng], 15);

                    const marker = markers.find(marker => marker.marker.getLatLng && marker.marker.getLatLng().lat === tricycle.lat && marker.marker.getLatLng().lng === tricycle.lng);
                    if (marker) {
                        marker.marker.openTooltip();
                    }
                }
            }
        </script>
    @endpush
</div>
