<?php

namespace TheNote\BlockPower;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use pocketmine\world\particle\FlameParticle;

class Main extends PluginBase implements Listener
{
    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveResource("Config.yml", false);
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $player = $event->getPlayer();
        $x = $player->getLocation()->getX();
        $y = $player->getLocation()->getY();
        $z = $player->getLocation()->getZ();
        $world = $player->getWorld();
        $block = $world->getBlock($player->getPosition()->getSide(0));
        if ($block->getID() == $config->get("Block")) {
            $direction = $player->getDirectionVector();
            $dx = $direction->getX();
            $dz = $direction->getZ();
            if ($config->get("FlameParticle") === true) {
                $world->addParticle(new Vector3($x - 0.3, $y, $z), new FlameParticle);
                $world->addParticle(new Vector3($x, $y, $z - 0.3), new FlameParticle);
                $world->addParticle(new Vector3($x + 0.3, $y, $z), new FlameParticle);
                $world->addParticle(new Vector3($x, $y, $z + 0.3), new FlameParticle);
            }
            $player->knockBack($dx, $dz, $config->get("KnockbackPower"), 0);
        }
    }
}
