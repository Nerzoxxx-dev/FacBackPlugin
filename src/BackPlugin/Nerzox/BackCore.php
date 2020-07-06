<?php

namespace BackPlugin\Nerzox;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use BackPlugin\Nerzox\Listener\BackListener;
use pocketmine\event\Listener;

class BackCore extends PluginBase implements Listener {
    public function onEnable(){
        $this->getLogger()->info('This plugin is enabled !');
        @mkdir($this->getDataFolder());
        if(!file_exists($this->getDataFolder() . 'config.yml')){
            file_put_contents($this->getDataFolder() . 'config.yml', $this->getResource('config.yml'));
        }
        if(!file_exists($this->getDataFolder() . 'player.yml')){
            $this->saveDefaultConfig('player.yml');
        }
        $this->getServer()->getCommandMap()->registerAll('Commands', [
            new Commands\BackCommand($this, new Config($this->getDataFolder() . 'config.yml', Config::YAML)),
        ]);
        $this->getServer()->getPluginManager()->registerEvents(new BackListener($this), $this);
    }
    public function onDisable(){
        $this->getLogger()->info('This plugin is disabled');
    }
}