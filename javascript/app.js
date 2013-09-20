(function(){
    "use strict";

    google.maps.visualRefresh = true;
    var map = new google.maps.Map(document.getElementById("the-map"), {
        zoom: 12,
        center: new google.maps.LatLng(50.736083, 7.100932),
        mapTypeId: google.maps.MapTypeId.ROADMAP
    });

    var geoSuccess = function(position) {
        console.log(position);
        $('#log').text(JSON.stringify(position.coords));
    };

    var geoError = function(error) {
        console.log(error);
    };

    if (!!navigator.geolocation) {
        var geoWatchId = navigator.geolocation.watchPosition(geoSuccess, geoError, {enableHighAccuracy : true});
    }

}).call(this);