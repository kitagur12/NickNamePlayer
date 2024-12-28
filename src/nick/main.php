<?php

namespace nick;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\Server;

class Main extends PluginBase implements Listener
{

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
    }

    public function join($event): void
    {
        $config = $this->getConfig();
        $player = $event->getPlayer();
        $special = $config->get("special_players", []);
        $playername = $player->getName();
        if (in_array($playername, $special)) {
            $name = $config->get("special_name", "<player>");
        } else {
            $name = $config->get("defalt_name", "<player>");
        }
        $name = str_replace("<player>", $playername, $name);
        $player->setNameTag($name); //setDisplayName
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
            if ($command->getName() === "nick") {
                if (count($args) >= 2) {
                    $server = Server::getInstance();
                    $specificPlayer = $server->getPlayerByPrefix($args[0]);
                    if ($specificPlayer === null) {
                        $sender->sendMessage("§cPlayer not found");
                        return true;
                    }
                    $specificPlayer->setDisplayName($args[1]);
                    $specificPlayer->setNameTag($args[1]);
                    $sender->sendMessage("§aPlayer's nickname has been changed to: " . $args[1]);
                    return true;
                } else {
                    $sender->sendMessage("§cNot enough arguments! Usage: /nick <player> <nickname>");
                    return false;
                }
            }
        return false;
    }
}
