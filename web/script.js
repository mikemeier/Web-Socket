(function(window){
    function Socket(url){
        this.url = url;
        this.ws = null;
        this.reconnectInterval = null;
        this.callbacks = {};
    }

    Socket.prototype = {
        /**
         * Connect WebSocket
         * @param {String} url
         */
        connect: function(){
            var self = this;

            window.setTimeout(function(){
                var ws = new WebSocket(self.url);

                ws.onopen = function(e){
                    self.dispatch('open');
                    window.clearInterval(self.reconnectInterval);
                    self.reconnectInterval = null;
                };

                ws.onerror = function(e){
                    self.dispatch('error');
                    self.tryReconnect();
                };

                ws.onclose = function(e){
                    self.dispatch('close');
                    self.tryReconnect();
                };

                ws.onmessage = function(e){
                    console.log(e.data);
                    var data = JSON.parse(e.data);
                    self.dispatch(data.event, data.arguments);
                };

                self.ws = ws;
            }, 500);
        },

        tryReconnect: function(){
            this.ws = null;
            var self = this;
            if(self.reconnectInterval == null){
                this.reconnectInterval = window.setInterval(function(){
                    self.connect();
                    self.dispatch('reconnect');
                }, 2500);
            }
        },

        /**
         * Dispatch event
         * @param {String} event
         * @param {Array} [arguments]
         */
        dispatch: function(event, arguments){
            if(!Array.isArray(arguments)){
                arguments = [];
            }

            if(Array.isArray(this.callbacks[event])){
                for(var i = 0; i < this.callbacks[event].length; i++){
                    this.callbacks[event][i].apply(this, arguments);
                }
            }
        },

        /**
         * Send message
         * @param event
         * @param arguments
         */
        send: function(event, arguments){
            if(!Array.isArray(arguments)){
                arguments = [];
            }

            this.ws.send(JSON.stringify({
                event: event,
                arguments: arguments
            }));
        },

        /**
         * @param {string} event
         * @param {function} callback
         */
        on: function(event, callback){
            if(!Array.isArray(this.callbacks[event])){
                this.callbacks[event] = [];
            }
            this.callbacks[event].push(callback);
        }
    };

    window.SocketConsole = Socket;

})(window);