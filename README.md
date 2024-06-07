![Logo](https://github.com/alojzjakob/EspSee/assets/17972823/3219c134-cddb-4f32-b06e-7e2d64b2d088)

---
# EspSee
EspSee is ESP32-CAM heartbeat listener and camera list plugin for WordPress.

This is the initial version of the plugin I wrote to build [espsee.com](https://www.espsee.com/).

EspSee.com was built to utilize Heartbeat functionality that I contributed to [ESP32-CAM_MJPEG2SD](https://github.com/s60sc/ESP32-CAM_MJPEG2SD), an awesome firmware for ESP32-CAM by [s60sc](https://github.com/s60sc).

---
# What it does

The plugin’s main purpose is to provide a camera hub, a space where all your cameras are bundled together for easy access, organizing and monitoring of online status.

In short, it provides a way for you to keep track of your cameras behind routers with dynamic IP addresses, so you dont need to use DDNS services.

To enable External Heartbeat, under Edit Config -> Others tab, enter fields:

- **Heartbeat receiver domain or IP** `www.espsee.com`
- **Heartbeat receiver URI** `/heartbeat/`
- **Heartbeat receiver port** `443`
- **Heartbeat receiver auth token** `xx-xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx`
- Then set **External Heartbeat Server enabled**

Heartbeat will be send every 30 (default) seconds. It will do a POST request to defined domain/URI (i.e. https://www.espsee.com/heartbeat/?token=[your_token]) with JSON body, containing useful information about your camera allowing this website to connect it to your user account and provide a way to easily access your camera(s) without the need for DDNS.

If you want to have multiple cameras accessible from the same external IP (behind router) you might need to do port forwarding and set ports on EspSee camera entries accordingly.

---
# Setup guide and advices

-coming soon-
