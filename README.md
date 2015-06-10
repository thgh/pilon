# pilon

Pilon, easy wifi hotspot for Raspberry Pi, pay with bitcoin

### Required

- Raspberry Pi
- Wifi dongle [1]
- SD card
- Computer to load pilon image file to SD
- Computer to configure pilon

If you would like to build it from scratch, start from Raspbian.

[1] Your wifi dongle has to have some sort of master mode. Some call it accesspoint infrastructure mode, host mode, hostap mode, ...


## Setup from image

Download image from
https://www.dropbox.com/s/c731j2x433pqq1r/pilon.7z

Login: ```pi```

Password: ```pilon```

This image is not perfect:

1. Without thinking too much, I expanded the partition size to 8Gb.
2. The iptables config is not included.

## Setup from scratch

Start from Raspbian image. You can find it here:
http://www.raspberrypi.org/downloads

Find your pi in the local network and connect with ssh:

Login: ```pi```

Password: ```raspberry```

### Get pilon

Get the files from github, clone them into /home/pi

```
git clone https://github.com/thgh/pilon.git /home/pi/lon
```

Make these folders available to the webserver

```
sudo chmod -R 774 /home/pi/lon/etc/*
sudo chgrp -R www-data /home/pi/lon/etc /home/pi/lon/www
```

### Install webserver

Install php and nginx which will serve the captive portal

```
sudo apt-get update
sudo apt-get install php5-fpm php5-gmp nginx
```

Edit some settings

```
sudo sed -i 's/worker_processes 4/worker_processes 1/g' /etc/nginx/nginx.conf
sudo sed -i 's/application\/octet-stream/text\/html/g' /etc/nginx/nginx.conf
sudo sed -i 's/var\/log\/nginx\/access.log/home\/pi\/\/lon\/log\/nginx-access.log/g' /etc/nginx/nginx.conf
sudo sed -i 's/var\/log\/nginx\/error.log/home\/pi\/\/lon\/log\/nginx-error.log/g' /etc/nginx/nginx.conf
```

Set up a new server

```
# Clear default server
sudo sh -c 'echo "" > /etc/nginx/sites-available/default'
# Edit the new one
sudo nano /etc/nginx/sites-available/default 
```

Insert this:

```
##
# Pilon captive portal server
##
server {
  listen 80;
  server_name localhost;

  root /home/pi/lon/www;
  index index.html;

  location / {
    try_files $uri $uri/ /redirect.php;
  }

  location ~ \.php$ {
    fastcgi_pass unix:/var/run/php5-fpm.sock;
    fastcgi_index /redirect.php;
    fastcgi_param PHP_VALUE "include_path=/home/pi/lon/etc";
    include fastcgi_params;
  }
}
```

Give it a final touch with:

```
sudo /etc/init.d/nginx reload
```

Test this setup by visiting the ip address of your pi.

Use ifconfig if you're not sure what's your ip.

### Wifi dongle as bridge

Follow this guide to get your wifi dongle working and bridging:

http://www.daveconroy.com/turn-your-raspberry-pi-into-a-wifi-hotspot-with-edimax-nano-usb-ew-7811un-rtl8188cus-chipset/

I suggest to skip the first part (except for passwd) and start from installing hostapd and bridge-utils

### Redirect to captive portal

These settings should work:

```
# Start from scratch
iptables -F
iptables -X
iptables -t nat -F
iptables -t nat -X
iptables -t mangle -F
iptables -t mangle -X
iptables -P INPUT ACCEPT
iptables -P FORWARD ACCEPT
iptables -P OUTPUT ACCEPT

# Redirect to nginx server
iptables -t mangle -N internet
iptables -t mangle -A PREROUTING -p tcp --dport 80:50000 -j internet
iptables -t mangle -A internet -j MARK --set-mark 99
iptables -t nat -A PREROUTING -p tcp -m mark --mark 99 -j DNAT --to-destination $(ifconfig eth0 | grep "inet addr" | awk -F: '{print $2}' | awk '{print $1}'):80

# Whitelisting
# Coinbase
iptables -I internet 1 -t mangle -p tcp -d coinbase.com --dport 80 -j RETURN
iptables -I internet 1 -t mangle -p tcp -d coinbase.com --dport 443 -j RETURN
# blockchain.info
iptables -I internet 1 -t mangle -p tcp -d blockchain.info --dport 80 -j RETURN
iptables -I internet 1 -t mangle -p tcp -d blockchain.info --dport 443 -j RETURN
# bitcoin.org
iptables -I internet 1 -t mangle -p tcp -d bitcoin.org --dport 80 -j RETURN
iptables -I internet 1 -t mangle -p tcp -d bitcoin.org --dport 443 -j RETURN
# fonts
iptables -I internet 1 -t mangle -p tcp -d fonts.googleapis.com --dport 80 -j RETURN
iptables -I internet 1 -t mangle -p tcp -d fonts.googleapis.com --dport 443 -j RETURN

```

### Aaaaaaaaand it's done.
