<?php

namespace WolfDen133\ServerSettings;

use InvalidArgumentException;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\ModalFormResponsePacket;
use pocketmine\network\mcpe\protocol\ServerSettingsRequestPacket;
use pocketmine\network\mcpe\protocol\ServerSettingsResponsePacket;
use pocketmine\plugin\Plugin;

class PacketListener implements Listener
{
    private static ?Plugin $registrant = null;

    public static function isRegistered(): bool
    {
        return self::$registrant instanceof Plugin;
    }

    public static function getRegistrant(): Plugin
    {
        return self::$registrant;
    }

    public static function unregister(): void
    {
        self::$registrant = null;
    }

    public static function register(Plugin $plugin): void
    {
        if (self::isRegistered()) return;

        self::$registrant = $plugin;
        $plugin->getServer()->getPluginManager()->registerEvents(new self, $plugin);
    }

    public function onDataPacketReceiveEvent (DataPacketReceiveEvent $e) : void
    {
        if ($e->getPacket() instanceof ServerSettingsRequestPacket) $this->onServerSettingsRequestPacket($e);
        if ($e->getPacket() instanceof ModalFormResponsePacket) $this->onModalFormResponsePacket($e);
    }

    private function onServerSettingsRequestPacket (DataPacketReceiveEvent $e) : void
    {
        if (!($e->getPacket() instanceof ServerSettingsRequestPacket)) throw new InvalidArgumentException(get_class($e->getPacket()) . " is not a " . ServerSettingsRequestPacket::class);
        /** @var ServerSettingsRequestPacket $pk */

        $e->getOrigin()->sendDataPacket(ServerSettingsResponsePacket::create(ServerSettings::FORM_ID, API::getServerSettings()->getData()));
    }

    private function onModalFormResponsePacket (DataPacketReceiveEvent $e) : void
    {
        if (!($pk = $e->getPacket()) instanceof ModalFormResponsePacket) throw new InvalidArgumentException(get_class($e->getPacket()) . " is not a " . ModalFormResponsePacket::class);

        /** @var ModalFormResponsePacket $pk */
        if ($pk->formId != ServerSettings::FORM_ID) return;
        API::getServerSettings()->getCallable()($e->getOrigin()->getPlayer(), json_decode($pk->formData));
    }
}