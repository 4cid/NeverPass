(function () {
    "use strict";

    // Load map
    google.maps.visualRefresh = true;
    var map = new google.maps.Map(document.getElementById("the-map"), {
        zoom: 8,
        center: new google.maps.LatLng(37.389971, -122.082712),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var markerController = function (map) {

        var markerBuffer = {};
        return {
            setMarker: function (lat, lon, img, text, id) {
                var marker = markerBuffer[id];
                if (img != null) {
                    img = {
                        url: img,
                        size: new google.maps.Size(50, 50),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(0, 49)
                    };
                }
                if (marker == null) {
                    marker = new google.maps.Marker({
                        position: new google.maps.LatLng(lat, lon),
                        map: map,
                        icon: img,
                        title: text
                    });
                } else {
                    marker.position = new google.maps.LatLng(lat, lon);
                }
            },
            removeAllMarkers: function () {
                var marker;
                for (var markerId in markerBuffer) {
                    if (!markerBuffer.hasOwnProperty(markerId))
                        continue;
                    markerBuffer[markerId].setMap();
                    delete markerBuffer[markerId];
                }
            }
        };
    }(map);


    var currentPos = null;
    var activeChannel = null;

    var setLatLon = function (positionObject) {
        if (currentPos == null) {
            map.setCenter(new google.maps.LatLng(
                positionObject.coords.latitude, positionObject.coords.longitude
            ));
        }

        //if (currentPos.lat != positionObject.coords.latitude || currentPos.lon != positionObject.coords.longitude) {
        //
        //}

        currentPos = new Location(
            0,
            positionObject.coords.latitude,
            positionObject.coords.longitude,
            Math.round(positionObject.coords.accuracy)
        );
    };


    var geoSuccess = function (position) {
        console.log(position);
        $('#log').text(JSON.stringify(position.coords));
        setLatLon(position);
        if (activeChannel != null) {
            activeChannel.setLocation(currentPos);
            activeChannel.update();
        }
    };

    var geoError = function (error) {
        console.log(error);
    };

    if (!!navigator.geolocation) {
        var geoWatchId = navigator.geolocation.watchPosition(geoSuccess, geoError, { enableHighAccuracy: true, maximumAge: 100, timeout: 60000 });
    }

    var startChannelUpdate = function () {
        activeChannel = new Channel(app.channelId != '' ? app.channelId : null, currentPos);
        activeChannel.onUpdate(
            function (channel) {
                if (app.channelId == '') {
                    app.channelId = channel.id;
                    History.pushState(null, null, "/" + channel.id);
                    var btn = $('#g-share-btn').find('button');
                    btn.attr('data-contenturl', btn.attr('data-contenturl') + channel.id);
                    btn.attr('data-calltoactionurl', btn.attr('data-calltoactionurl') + channel.id);
                }
                if (!channel.locations.length) {
                    return
                }
                var locationIndex = channel.locations.length - 1;
                do {
                    var loc = channel.locations[locationIndex];
                    var user = channel.users[loc.userId];
                    markerController.setMarker(
                        loc.latitude,
                        loc.longitude,
                        user != null ? user.imageUrl : null,
                        user != null ? user.displayName : null,
                        loc.userId
                    );
                } while (locationIndex--);
            }
        );
        activeChannel.onNotAuthorized(
            function () {
                $('#modal-notLoggedIn')
                    .modal('show')
                    .on('hide.bs.modal', function (ev) {
                        console.log(ev);
                        ev.preventDefault();
                    });
            }
        );
        activeChannel.start();
        $('#btn-start').prop('disabled', true).hide();
    };


    // Autostart
    if (app.channelId != '') {
        startChannelUpdate();
    }

    // Connecting controls

    $('#btn-start').on('click', function () {
        $('#g-share-btn').removeClass('hide');
        startChannelUpdate();
    });

}).call(this);