<?php 
  
namespace Administrador\commands;

use pocketmine\player\Player;
use Administrador\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;

class VanishCommand extends Command
{
    public function __construct()
    {
        parent::__construct("vanish", "Activated\Desactivated Vanish", "/vanish", ["v"]);
        $this->setPermission("vanish.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            return;
        }
        if ($sender instanceof Player) {
            if (!in_array($sender->getName(), Loader::$vanish)) {
            $this->vanish($sender);
        }else{
            $this->unvanish($sender);
             }
        }    
    }

    public function unvanish(Player $player)
    {
        unset(Loader::$vanish[array_search($player->getName(), Loader::$vanish)]);
        Loader::$online[] = $player->getName();
            $player->setAllowFlight(false);
            $player->setFlying(false);
            $player->setInvisible(false);
            $player->sendMessage("Vanish Desactivated");
    }

    public function vanish(Player $player)
    {
        Loader::$vanish[] = $player->getName();
        unset(Loader::$online[array_search($player->getName(), Loader::$online, True)]);
        $player->setInvisible(true);
        $player->setFlying(true);
        $player->setAllowFlight(true);
        $player->sendMessage("Vanish Activated");
    }
}