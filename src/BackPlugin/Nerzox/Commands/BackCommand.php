<?php

namespace BackPlugin\Nerzox\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\Config;
use BackPlugin\Nerzox\BackCore;

class BackCommand extends Command {
public $config;
public $core;
public $player;
    public function __construct(BackCore $core, $config)
    {
        parent::__construct('back', 'A back command', '/back [option]', []);
        $this->core = $core;
        $this->player = new Config($this->core->getDataFolder() . 'player.yml', Config::YAML);
        $this->config = $config;
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
        if($player instanceof  Player) {
            if ($player->hasPermission($this->config->get('backPermission')) || $player->hasPermission($this->config->get('allPermission'))) {
                if (empty($args)) {

                    if ($this->player->exists($player->getName())) {
                        $pos = $this->player->get($player->getName());
                        $x = (int)$pos['x'];
                        $y = (int)$pos['y'];
                        $z = (int)$pos['z'];
                        $level = $this->core->getServer()->getLevelByName($pos['level']);
                        $player->teleport(new Position($x, $y, $z, $level));
                    } else {
                        var_dump($this->player->get($player->getName()));
                        $player->sendMessage($this->config->get('noBack'));
                    }
                }elseif ($args[0] === 'reset'){
                    if($player->hasPermission($this->config->get('resetPermission')) || $player->hasPermission($this->config->get('allPermission'))){
                        if(isset($args[1])){
                            $player->sendMessage($this->config->get('usageReset'));
                        }else{
                            $target = $this->core->getServer()->getPlayer($args[1]);
                            if(is_null($target)){
                                $player->sendMessage($this->config->get('targetNoConnect'));
                            }else{
                                if($this->player->exists($target->getName())){
                                    $this->player->remove($target->getName());
                                    $this->player->save();
                                }else{
                                    $player->sendMessage($this->config->get('targetNoBack'));
                                }
                            }
                        }
                    }else{
                        $player->sendMessage($this->config->get('noPermission'));
                    }
                }else{
                    $player->sendMessage($this->config->get('usageHelp'));
                }
            } else{
                $player->sendMessage($this->config->get('noPermission'));
            }
        }else {
            $player->sendMessage($this->config->get('noPlayingGame'));
        }
    }
}
