<?php

$j = '';
$j = '{ "app_version":"2.2.121112 rv:010003", "primary_event":0, "etsy_analytics_version":"0.5", "data_type":"Event", "visit":{ "log_created_time_string":"14\/Nov\/2012:16:52:15 Z", "app_start_time":1352911920.4082, "session_count":"41", "session_id_time":1352911920.4083, "last_session_id_time":1352842342.3847, "log_created_time":1352911935.5224, "page_count":"8", "app_initial_start_time":1352751773.889, "session_id_time_string":"14\/Nov\/2012:16:52:00 Z", "app_foreground_time":1352911920.4374, "utma":"etsyiphone.6B53C693C87F44EAA6A759C9EFE3.1352751774.1352911920.1352842342.41", "last_session_id_time_string":"13\/Nov\/2012:21:32:22 Z", "last_session_id":"40", "session_id":"41", "domain_hash":"etsyiphone" }, "region":{ "accept-languages":"en", "time_zone":"America\/New_York", "region":"en_US"}, "network_type":"Wifi", "data":{"event_name":"scrolled", "event_value":0, "event_action":"product_image" }, "device":{ "hardware_model":"MacBookPro8, 2", "device_system_version":"6.0", "device_id":"6B53C693C87F44EAA6A759C9EFE3", "hardware_platform":"x86_64", "hardware_platform_string":"iPhone Simulator", "device_system_name":"iPhone OS" }}';

$a = (array)json_decode($j, true);

var_dump($a);
