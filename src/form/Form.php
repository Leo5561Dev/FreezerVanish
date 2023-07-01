<?php

namespace form;

use Administrador\Loader;
use libs\jojoe77777\FormAPI\CustomForm;
use libs\jojoe77777\FormAPI\SimpleForm;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\TextFormat as TF;

class Form
{

    public function Menu(Player $player)
    {
        $form = new SimpleForm(function (Player $player, $data = null) {
            if ($data === null) {
                return true;
            }
            switch ($data) {
                case 0:
                    $this->FREEZE($player);
                    break;

                case 1:
                    if (!Loader::getInstance()->freezeplayers) {
                        $player->sendMessage(Loader::getInstance()->prefix . TF::YELLOW . "No players are frozen!");
                    } else {
                        $this->UNFREEZE($player);
                    }
                    break;
            }
        });
        $form->setTitle("Freeze");
        $form->addButton("Freeze", 0, "textures/ui/icon_winter");
        $form->addButton("UnFreeze", 0, "textures/ui/redX1");
        $player->sendForm($form);
        return $form;
    }

    public function FREEZE(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data = null) {
            if ($data === null) {
                return true;
            }
            $playerName = [];
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                $playerName[] = $players->getName();
            }
            $target = Loader::getInstance()->getServer()->getPlayerExact($playerName[$data[0]]);
            $targetName = $target->getName();
            if ($targetName == $player->getName()) {
                $player->sendMessage(Loader::getInstance()->prefix . TF::YELLOW . "Unable to frozen yourself!");
                return true;
            }
            if (!in_array($targetName, Loader::getInstance()->freezeplayers)) {
                array_push(Loader::getInstance()->freezeplayers, $targetName);
                $player->sendMessage(Loader::getInstance()->prefix . TF::GREEN . "You have frozen " . TF::YELLOW . $targetName);
                $target->sendMessage(Loader::getInstance()->prefix . TF::GREEN . "You are frozen by " . TF::YELLOW . $player->getName());
            } else {
                $player->sendMessage(Loader::getInstance()->prefix . TF::RED . $targetName . TF::YELLOW . " is already frozen!");
            }
        });
        $form->setTitle("FREEZE");
        $playerName = [];
        foreach (Server::getInstance()->getOnlinePlayers() as $players) {
            $playerName[] = $players->getName();
        }
        $form->addDropdown("Select Players:", $playerName);
        $player->sendForm($form);
        return $form;
    }

    public function UNFREEZE(Player $player)
    {
        $form = new CustomForm(function (Player $player, $data = null) {
            if ($data === null) {
                return true;
            }
            $playerName = [];
            foreach (Loader::getInstance()->freezeplayers as $players) {
                $playerName[] = $players;
            }
            $target = Loader::getInstance()->getServer()->getPlayerExact($playerName[$data[0]]);
            if(!$target){
                $player->sendMessage(Loader::getInstance()->prefix . TF::RED . "Player not online!");
            }else {
                $targetName = $target->getName();
                array_splice(Loader::getInstance()->freezeplayers, array_search($targetName, Loader::getInstance()->freezeplayers), 1);
                $player->sendMessage(Loader::getInstance()->prefix . TF::YELLOW . $targetName . TF::GREEN . " is no longer frozen!");
                $target->sendMessage(Loader::getInstance()->prefix . TF::GREEN . "Unfrozen by " . TF::YELLOW . $player->getName());
            }
        });
        $form->setTitle("UnFreeze");
        $form->addDropdown("Select Players:", Loader::getInstance()->freezeplayers);
        $player->sendForm($form);
        return $form;
    }
}