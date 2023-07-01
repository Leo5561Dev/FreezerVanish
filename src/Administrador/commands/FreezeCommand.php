<?php 

namespace Administrador\commands;

use Administrador\Loader;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginOwned;
use pocketmine\player\Player;

class FreezeCommand extends Command implements PluginOwned
{

    public function __construct()
    {
        parent::__construct("freeze", "Open FreezeUI Menu!", "/freeze", ["fui"]);
        $this->setPermission("freezeui.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): void
    {
        if (!$this->testPermission($sender)) {
            return;
        }
        if (!$sender instanceof Player) {
            return;
        }
        $this->getOwningPlugin()->getForm()->Menu($sender);
    }

    public function getOwningPlugin(): Loader
    {
        return Loader::getInstance();
    }
}