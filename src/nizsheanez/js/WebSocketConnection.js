var ParentWebSocketConnection = WebSocketConnection;

//auutoreconnect and callbacks queue
var WebSocketConnection = function (params) {
    var reConnectionInterval = 500;
    var wrapper  = {
        socket: {},
        connect: function() {
            wrapper.socket = new ParentWebSocketConnection(params);
            wrapper.socket.onopen = function () {
                wrapper.isOpened = true;
                wrapper.onopen();
            };
            wrapper.socket.onclose = function () {
                wrapper.isOpened = false;
                wrapper.onclose();
                setTimeout(function() {
                    wrapper.connect();
                }, reConnectionInterval);
            };
            wrapper.socket.onmessage = function(data) {
                wrapper.onmessage(data);
            };
        },
        onclose: function(){},
        onmessage: function(data){},
        onopen: function() {},
        send: function(data) {
            wrapper.socket.send(data);
        },
        isOpened: false,
        callbacks: [],
        currentCallbackId: 0,
        getCallbackId: function () {
            // This creates a new callback ID for a request
            wrapper.currentCallbackId += 1;
            if (wrapper.currentCallbackId > 10000) {
                wrapper.currentCallbackId = 0;
            }
            return wrapper.currentCallbackId;
        },
        pushCallback: function (callback) {
            var callbackId = wrapper.getCallbackId();
            wrapper.callbacks[callbackId] = {
                time: new Date(),
                callback: callback
            };
            return callbackId;
        },
        getCallback: function (id) {
            var callback = wrapper.callbacks[id].callback;
            delete wrapper.callbacks[id];
            return callback;
        }
    };
    wrapper.connect();

    return wrapper;
};

//json and deffered
var JsonWebSocket = function (params) {
    var defer = $.Deferred();

    var socket = new WebSocketConnection(params);

    var promise = $.extend(defer.promise(), {
        pushHandler: params.pushHandler,
        errorHandler: params.errorHandler,
        send: function (method, params, callback) {
            defer.then(function () {
                var callbackId = socket.pushCallback(callback);
                socket.send(JSON.stringify({
                    jsonrpc: '2.0',
                    id: callbackId,
                    method: method,
                    params: params
                }));
            });
            return defer;
        }
    });

    socket.onmessage = function (e) {
        var data = $.parseJSON(e.data);
        //run callback if it's client request, or run default handler if it's server push message
        if (data.error == undefined) {
            if (data.id) {
                var callback = socket.getCallback(data.id);
                callback(data.result);
            } else {
                promise.pushHandler(data);
            }
        } else {
            promise.errorHandler('error', data.error.message);
        }
    };

    if (socket.isOpened) {
        defer.resolve();
    } else {
        socket.onopen = function () {
            defer.resolve();
        };
    }

    return promise;
};

var AngularSocketDecorator = function (socket, $rootScope, alertService) {
    socket._send = socket.send;
    socket.send = function(route, params, callback, scope) {
        socket._send(route, params, function(data) {
            (scope ? scope : $rootScope).$apply(function() {
                callback && callback(data);
            });
        });
        return socket;
    };
    socket.errorHandler = function(status, error) {
        $rootScope.$apply(function() {
            alertService.add(status, error);
        });
    };
    return socket;
};

