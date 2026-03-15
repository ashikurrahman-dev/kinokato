@extends('backend.master')

@section('maincontent')
@section('title')
    {{ env('APP_NAME') }}- Maps
@endsection
<style>
    div#roleinfo_length {
        color: red;
    }

    div#roleinfo_filter {
        color: red;
    }

    div#roleinfo_info {
        color: red;
    }
    .list-group-item {
        position: relative;
        display: block;
        padding: .5rem 1rem;
        color: #585858;
        background-color: #fff;
        border: 1px solid #dddbdb;
        cursor: pointer;
    }
    .list-group-item:hover {
        color: black;
        background-color: #00ea1058;
        border: 1px solid #dddbdb;
        cursor: pointer;
    }
     #map {
            height: 600px;
        }
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

@php
use Carbon\Carbon;

$firstDate = Carbon::now()->startOfMonth()->toDateString();
$lastDate = Carbon::now()->endOfMonth()->toDateString();
@endphp

<div class="px-4 pt-4 container-fluid">
    <div class="row">
        <div class="col-sm-12 col-md-12 col-xl-12">
            <div class="p-4 rounded bg-secondary h-100">
                <div class="text-center">
                    <h3 class="mb-0">Maps Data List</h3>
                </div>
                <div class="pt-4 mt-4 row">
                    <div class="mb-4 col-12">
                        {{-- <div class="d-flex">
                            <input type="date" name="from" id="from" class="form-control" value="{{$firstDate}}"> &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp;
                            <input type="date" name="to" id="to" class="form-control" value="{{$lastDate}}">
                        </div> --}}
                    </div>
                    <div class="col-4">
                        <div class="p-3 bg-white" style="border-radius: 6px;">

                            {{-- <div class="d-flex">
                                <input type="text" placeholder="Type to search" name="search" id="search" class="form-control">
                            </div> --}}

                            <div class="mt-4 districtlist">
                                <ul class="list-group" style="height: 536px;overflow-y: scroll;">
                                    @forelse($cities as $city)
                                        <li class="list-group-item" onclick="findLocation('{{$city->cityName}} District, Bangladesh','{{$city->id}}')">{{$city->cityName}} &nbsp; <span style="color:red"><b>({{App\Models\Order::where('city_id',$city->id)->get()->count()}})</b></span></li>
                                    @empty
                                    @endforelse
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="p-3 bg-white" style="border-radius: 6px;">
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    </div>
</div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    var map = L.map('map').setView([23.685, 90.3563], 7); // Centered at Bangladesh

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    var currentMarker = null;
    var currentPolygon = null;

    function findLocation(placeName,id) {
        var order=null;
        $.ajax({
            type: 'GET',
            url: 'citydata/' + id,

            success: function(data) {
                order=data;
            },
            error: function(error) {
                console.log('error');
            }

        });
        // Step 1: Get OSM ID using Nominatim API
        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${placeName}&polygon_geojson=1`)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var lat = data[0].lat;
                    var lon = data[0].lon;
                    var osmId = data[0].osm_id;
                    var osmType = data[0].osm_type === 'relation' ? 'relation' : 'way';

                    // Remove previous marker and polygon
                    if (currentMarker) map.removeLayer(currentMarker);
                    if (currentPolygon) map.removeLayer(currentPolygon);

                    // Add marker at center
                    currentMarker = L.marker([lat, lon]).addTo(map)
                        .bindPopup(`<b><h6>${placeName}</h6></b>${order}`).openPopup();

                    // Step 2: Fetch the exact boundary from Overpass API
                    fetch(`https://overpass-api.de/api/interpreter?data=[out:json];${osmType}(${osmId});(._;>;);out body;`)
                        .then(response => response.json())
                        .then(overpassData => {
                            var geoJson = osmtogeojson(overpassData);

                            // Draw full boundary area
                            currentPolygon = L.geoJSON(geoJson, {
                                style: { color: 'green', fillOpacity: 0.3 }
                            }).addTo(map);

                            // Zoom to fit the boundary
                            map.fitBounds(currentPolygon.getBounds());
                        })
                        .catch(error => console.log("Error fetching boundary:", error));
                } else {
                    alert("Location not found!");
                }
            })
            .catch(error => console.log("Error fetching location:", error));
    }

    // Include osmtogeojson library to convert OSM data to GeoJSON
    var script = document.createElement('script');
    script.src = "https://rawgit.com/tyrasd/osmtogeojson/gh-pages/osmtogeojson.js";
    document.head.appendChild(script);
</script>

<script>
    $(document).ready(function() {
        var token = $("input[name='_token']").val();

        //edit brand
        $(document).on('click', '#editBrandBtn', function() {
            let brandId = $(this).data('id');

            $.ajax({
                type: 'GET',
                url: 'brands/' + brandId + '/edit',

                success: function(data) {
                    $('#EditBrand').find('#brand_name').val(data
                        .brand_name);
                    $('#EditBrand').find('#brand_id').val(data.id);

                    $('#previmg').html('');
                    $('#previmg').append(`
                        <img  src="../` + data.brand_icon + `" alt = "" style="height: 80px" />
                    `);

                    $('#EditBrand').attr('data-id', data.id);
                },
                error: function(error) {
                    console.log('error');
                }

            });
        });


    });
</script>

@endsection
