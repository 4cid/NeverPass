var Location = function (heading, latitude, longitude, accuracy) {
    this.hdg = heading;
    this.lat = latitude;
    this.lon = longitude;
    this.acc = accuracy;
};

var Channel = function (param) {
    this.location = {};
    this.id = '';
    this.url = '';
    this.users = [];
    this.locations = [];
    this.intervall = null;

    this.onUpdate = function () {
    };

    if (param instanceof Location) {
        this.location = param;
    } else if (param) {
        this.id = param;
    }

    // first init!
    this.update();

    return this;
};

Channel.prototype.update = function () {
    console.log('Channel update...');
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
            this.onUpdate(this);
        }, this));
};

Channel.prototype.setLocation = function (location) {
    location || (location = {});
    this.location = location;
};

Channel.prototype.start = function () {
    this.stop();
    this.intervall = window.setInterval(jQuery.proxy(function () {
        this.update()
    }, this), 5000);
};

Channel.prototype.stop = function () {
    console.log('Channel stop..');
    if (this.intervall != null) {
        window.clearInterval(this.intervall);
    }
};

Channel.prototype.onUpdate = function (func) {
    if (typeof func == 'function') {
        this.onUpdate = func;
    }
};