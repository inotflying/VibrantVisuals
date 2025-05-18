<?php

declare(strict_types=1);

namespace inotflying\VibrantVisuals;

use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\ResourcePackStackPacket;
use pocketmine\network\mcpe\protocol\StartGamePacket;
use pocketmine\network\mcpe\protocol\types\Experiments;
use pocketmine\plugin\PluginBase;

use function array_merge;

final class VibrantVisuals extends PluginBase implements Listener
{
    protected function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    /** @noinspection PhpUnused */
    public function onDataPacketSend(DataPacketSendEvent $event): void
    {
        foreach ($event->getPackets() as $packet) {
            if ($packet instanceof StartGamePacket) {
                $packet->levelSettings->experiments = $this->enableExperimentalGraphics($packet->levelSettings->experiments);
            }
            if ($packet instanceof ResourcePackStackPacket) {
                $packet->experiments = $this->enableExperimentalGraphics($packet->experiments);
            }
        }
    }

    private function enableExperimentalGraphics(Experiments $experiments): Experiments
    {
        return new Experiments(
            array_merge($experiments->getExperiments(), ["experimental_graphics" => true]),
            $experiments->hasPreviouslyUsedExperiments()
        );
    }
}
