## Laravel Chat App
This is a laravel based chat app with video calling feature.
# Install needed packages
```composer require pusher/pusher-php-server "~4.0"```
```npm install --save laravel-echo pusher-js simple-peer```

# Installing Coturn

```sudo apt-get -y update```
```sudo apt-get -y install coturn```

# Start the Coturn Daemon at Startup

```sudo nano /etc/default/coturn```
Uncomment the following line by removing the # at the beginning to run Coturn as an automatic system service daemon
```TURNSERVER_ENABLED=1```
