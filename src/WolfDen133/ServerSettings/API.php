<?php

namespace WolfDen133\ServerSettings;

use pocketmine\plugin\Plugin;

class API
{
    private static ServerSettings $serverSettings;

    public static function register (Plugin $plugin) : void
    {
        PacketListener::register($plugin);
        self::$serverSettings = new ServerSettings();
    }

    public static function getServerSettings() : ServerSettings
    {
        return self::$serverSettings;
    }
}
