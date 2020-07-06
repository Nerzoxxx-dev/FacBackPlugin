<?php

namespace BackPlugin\Nerzox\Listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;
use pocketmine\level\Position;
use pocketmine\level\Level;

class BackListener implements Listener {
    private $player;
    private $config;

    public function __construct($core)
    {
        $this->core = $core;
        $this->player = new Config($this->core->getDataFolder() . 'player.yml', Config::YAML);
        $this->config = new Config($this->core->getDataFolder() . 'config.yml', Config::YAML);
    }
    public function onDeath(PlayerDeathEvent $ev){
        $player = $ev->getPlayer();
        $cord = [
            'x' => $player->getX(),
            'y' => $player->getY(),
            'z' => $player->getZ(),
            'level' => $player->getLevel()->getName()
        ];

        $this->player->set($player->getName(), $cord);
        $this->player->save();
    }
    public function onQuit(PlayerQuitEvent $ev){
        $player = $ev->getPlayer();
        if($this->player->exists($player->getName())) {
            $this->player->remove($player->getName());
            $this->player->save();
        }
    }
}

