/**
 * Location Obj
 * @param heading
 * @param latitude
 * @param longitude
 * @param accuracy
 * @constructor
 */
var Location = function (heading, latitude, longitude, accuracy) {
    this.hdg = heading;
    this.lat = latitude;
    this.lon = longitude;
    this.acc = accuracy;
};

/**
 * Channel Obj
 * @param {String} [channelId] channelId
 * @param {Location} [location] Location-Parameter
 * @returns {Channel}
 * @constructor
 */
var Channel = function (channelId, location) {
    this.location = {};
    this.id = '';
    this.url = '';
    this.users = [];
    this.locations = [];
    this.timeout = null;
    this.timestamp = 0;

    this.onUpdateHandler = function () {};
    this.onNotAuthorizedHandler = function () {};

    for (var i = 0; i < arguments.length; i++) {
        var param = arguments[i];
        if (param instanceof Location) {
            this.location = param;
        } else if (param) {
            this.id = param;
        }
    }

    // first init!
    this.update();

    return this;
};

Channel.prototype.update = function () {
    console.log('Channel update...');
    var data = {};
    if (this.id.length) {
        data.id = this.id;
    }
    jQuery.extend(data, this.location);
    if (this.timestamp) {
        data.timestamp = this.timestamp;
    }
    $.ajax({
        dataType: 'JSON',
        type: 'GET',
        url: '/api/channel',
        data: data
    })
        .done(jQuery.proxy(function (json) {
            jQuery.extend(this, json);
            this.onUpdateHandler(this);
            this.start();
        }, this))
        .fail(jQuery.proxy(function(xhr) {
            this.stop();
            if (xhr.status == 401) {
                this.onNotAuthorizedHandler();
            }
        }, this));
};

/**
 * @param location
 */
Channel.prototype.setLocation = function (location) {
    if (location instanceof Location) {
        this.location = location;
    }
};

Channel.prototype.start = function () {
    this.stop();
    this.timeout = window.setTimeout(jQuery.proxy(function () {
        this.update()
    }, this), 10);
};

Channel.prototype.stop = function () {
    console.log('Channel stop..');
    if (this.timeout != null) {
        window.clearInterval(this.timeout);
    }
};

/**
 * Set onUpdate listener
 * @param func
 * @returns {Channel}
 */
Channel.prototype.onUpdate = function (func) {
    if (typeof func == 'function') {
        this.onUpdateHandler = func;
    }
    return this;
};

/**
 * Set onNotAuthorized listener
 * @param func
 * @returns {Channel}
 */
Channel.prototype.onNotAuthorized = function (func) {
    if (typeof func == 'function') {
        this.onNotAuthorizedHandler = func;
    }
    return this;
};