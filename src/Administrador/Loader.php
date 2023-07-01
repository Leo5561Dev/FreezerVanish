<?php 

declare(strict_types=1);

namespace Administrador;

use Administrador\commands\VanishCommand;
use pocketmine\Server;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TE;
use Administrador\commands\FreezeCommand;
use form\Form;
use libs\jojoe77777\FormAPI\Form as FormAPI;

class Loader extends PluginBase{

    public static $instance;

    private $form;

    public $freezeplayers = [];

    public static array $vanish = [];

    public static array $online = [];

    public $prefix = TE::BOLD . TE::DARK_GRAY ."[".TE::AQUA ."Frezee".TE::DARK_GRAY ."] ". TE::RESET;

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function onEnable(): void
    {
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->getServer()->getCommandMap()->register($this->getName(), new FreezeCommand());
        $this->getServer()->getCommandMap()->register($this->getName(), new VanishCommand());
        $this->form = new Form();

        if(!class_exists(FormAPI::class)){
            $this->getLogger()->error("Libraries FormAPI not found, Please download this plugin..");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
    }

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function getForm(): Form
    {
        return $this->form;
    }
    
}
