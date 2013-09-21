var Location = function (heading, latitude, longitude, accuracy) {
    this.hdg = heading;
    this.lat = latitude;
    this.lon = longitude;
    this.acc = accuracy;
};

var Channel = function (id, location) {
    this.id = id == undefined ? '' : id;
    this.location = location == undefined ? {} : location;
    this.url = '';
    this.users = [];
    this.locations = [];

    // first init!
    this.update();
};

Channel.prototype.update = function () {
    var data = {};
    if (this.id.length) {
        jQuery.extend(data, {id: this.id});
    }
    jQuery.extend(data, this.location);
    $.ajax({
        dataType: 'JSON',
        type: 'GET',
        url: '/api/channel',
        data: data
    })
        .done(jQuery.proxy(function (json) {
            jQuery.extend(this, json);
        }, this));
};

Channel.prototype.updateLocation = function (location) {
    location || (location = {});
    this.location = location;
};