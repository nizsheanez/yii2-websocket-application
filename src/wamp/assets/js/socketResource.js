'use strict';

angular.module('eg.components', []).service('server', function () {
    var prefix = 'http://' + document.domain + '/api/v1/';
    var socketDefer = $.Deferred();
    // WAMP session object

    var sess = $.extend(socketDefer.promise(), {
        call: function () {
            var args = arguments;
            var beforeOnOpenDefer = $.Deferred();
            socketDefer.then(function () {
                sess.call.apply(sess, args).then(function (data) {
                    beforeOnOpenDefer.resolve(data)
                });
            });
            return beforeOnOpenDefer;
        }
    });

    function onConnect(session) {
        sess = session;
        socketDefer.resolve();

//        sess._subscribe = sess.subscribe;
//        sess.subscribe = function (id, callback) {
//            sess._subscribe(id, function (data) {
//                $rootScope.$apply(function () {
//                    callback && callback(data);
//                });
//            })
//        };
    }

    function onDisconnect(code, reason) {
        sess = null;
        console.log("Connection lost (" + code + " " + reason + ")");
    }

    // connect to WAMP server
    ab.connect('ws://' + document.domain + ':8047/', onConnect, onDisconnect, {
        'maxRetries': 60000,
        'retryDelay': 1000
    });

    sess.prefix = prefix;
    return sess;
});

angular.module('eg.components').factory('$socketResource', ['server', function (server) {
    /**
     * Create a shallow copy of an object and clear other fields from the destination
     */
    function shallowClearAndCopy(src, dst) {
        dst = dst || {};

        angular.forEach(dst, function (value, key) {
            delete dst[key];
        });

        for (var key in src) {
            if (src.hasOwnProperty(key) && key.charAt(0) !== '$' && key.charAt(1) !== '$') {
                dst[key] = src[key];
            }
        }

        return dst;
    }


    //server.subscribe(prefix + "user", onEvent);

    function publishEvent() {
        sess.publish(prefix + "user", {a: "foo", b: "bar", c: 23});
    }

    var DEFAULT_ACTIONS = {
        'get': {
            url: 'view'
        },
        'save': {
            url: 'save'
        },
        'query': {
            url: 'index',
            isArray: true
        },
        'delete': {
            url: 'delete'
        }
    };

    var noop = angular.noop,
        forEach = angular.forEach,
        extend = angular.extend,
        copy = angular.copy,
        isFunction = angular.isFunction;


    function Route(controller) {
        this.controller = controller;
    }

    Route.prototype = {
        url: function (action) {
            return server.prefix + this.controller + '/' + action;
        }
    };

    function resourceFactory(url, paramDefaults, actions) {
        var route = new Route(url);

        function Resource(value) {
            shallowClearAndCopy(value || {}, this);
        }

        actions = extend({}, DEFAULT_ACTIONS, actions);

        forEach(actions, function (action, name) {

            var value = action.isArray ? [] : new Resource();
            Resource[name] = function (url, params, callback) {
                if (!action.isArray) {
                    params = params ? params : {};
                    if (typeof params !== 'object') {
                        alert('params Must be object!');
                    }
                    angular.forEach(this, function (value, key) {
                        params[key] = angular.copy(value);
                    });
                }

                $.active++;
                var promise = server.call(route.url(url), params).then(function (response) {
                    $.active--;

                    var data = response.data,
                        promise = value.$promise;

                    if (data) {
                        // Need to convert action.isArray to boolean in case it is undefined
                        // jshint -W018
                        if (angular.isArray(data) !== (!!action.isArray)) {
                            throw $resourceMinErr('badcfg', 'Error in resource configuration. Expected ' +
                                'response to contain an {0} but got an {1}',
                                action.isArray ? 'array' : 'object', angular.isArray(data) ? 'array' : 'object');
                        }
                        // jshint +W018
                        if (action.isArray) {
                            value.length = 0;
                            forEach(data, function (item) {
                                value.push(new Resource(item));
                            });
                        } else {
                            copy(data, value);
                            value.$promise = promise;
                        }
                    }

                    value.$resolved = true;

                    response.resource = value;

//                        $rootScope.$apply(function() {
                    callback && callback(response)
//                        });

                    return response;
                });
                // we are creating instance / collection
                // - set the initial promise
                // - return the instance / collection
                value.$promise = promise;
                value.$resolved = false;
                return value;
            };

            Resource.prototype['$' + name] = function (params, success, error) {
                if (isFunction(params)) {
                    error = success;
                    success = params;
                    params = {};
                }

                var result = Resource[name].call(this, action.url, params, success);
                return result.$promise || result;
            };
        });

        return Resource;
    }

    return resourceFactory;
}]);
