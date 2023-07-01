<?php

namespace Administrador;

use Administrador\Loader;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\utils\TextFormat as TE;
use pocketmine\world\Position;
use pocketmine\event\entity\EntityCombustEvent;

class EventListener implements Listener
{

    public function onMove(PlayerMoveEvent $event)
    {
        $playerName = $event->getPlayer()->getName();
        if (in_array($playerName, Loader::getInstance()->freezeplayers)) {
            $event->cancel();
        }
    }

    public function onHit(EntityDamageByEntityEvent $event)
    {
        $damager = $event->getDamager();
        $player = $event->getEntity();
        if ($player instanceof Player && $damager instanceof Player) {
            if (in_array($player->getName(), Loader::getInstance()->freezeplayers)) {
                $event->cancel();
                $damager->sendMessage(Loader::getInstance()->prefix . TE::YELLOW . "You can't hit players while frozen!");
            }
        }
    }

    public function onBreak(BlockBreakEvent $event): void 
    {
        $player = $event->getPlayer()->getName();
        if (in_array($player, Loader::getInstance()->freezeplayers)) {
            $event->cancel();
        }
    }

    public function onPlace(BlockPlaceEvent $event): void 
    {
        $player = $event->getPlayer()->getName();
        if (in_array($player, Loader::getInstance()->freezeplayers)) {
            $event->cancel();
        }
    }

    public function pickUp(EntityItemPickupEvent $event) {
        if ($event->getEntity() instanceof Player) {
        if (in_array($event->getEntity()->getName(), Loader::getInstance()->freezeplayers)) {
        if (in_array($event->getEntity()->getName(), Loader::$vanish)) {
                $event->cancel();
         }
       }
     }
    }

    public function onDamage(EntityDamageEvent $event) {
        $player = $event->getEntity();
        if($player instanceof Player) {
            $name = $player->getName();
            if (in_array($player, Loader::getInstance()->freezeplayers)) {
                if (in_array($event->getEntity()->getName(), Loader::$vanish)) {
                    $event->cancel();
                }
            }
        }
    }

    public function onPlayerBurn(EntityCombustEvent $event) {
        $player = $event->getEntity();
        if($player instanceof Player) {
            $name = $player->getName();
            if (in_array($player, Loader::getInstance()->freezeplayers)) {
                if (in_array($event->getEntity()->getName(), Loader::$vanish)) {
                        $event->cancel();
                }
            }
        }
    }

    public function onExhaust(PlayerExhaustEvent $event) {
        $player = $event->getPlayer();
        if (in_array($event->getEntity()->getName(), Loader::$vanish)) {
                    $event->cancel();
        }
    }

    public function onQuery(QueryRegenerateEvent $event) {
        $event->getQueryInfo()->setPlayerList(Loader::$online);
        foreach(Server::getInstance()->getOnlinePlayers() as $p) {
            if(in_array($p->getName(), Loader::$vanish)) {
                $online = $event->getQueryInfo()->getPlayerCount();
                $event->getQueryInfo()->setPlayerCount($online - 1);
            }
        }
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $playerName = $event->getPlayer()->getName();
        if (in_array($playerName, Loader::getInstance()->freezeplayers)) {
            $this->check($event->getPlayer());
        }
    }

    public function check(Player $player)
    {
        $pos = $player->getPosition();
        $world = $player->getWorld();
        if (!$player->isOnGround()) {
            $newY = $world->getHighestBlockAt($pos->getFloorX(), $pos->getFloorZ());
            $player->teleport(new Position($pos->getFloorX(), $newY + 1, $pos->getFloorZ(), $world));
        }

        if ($player->isUnderwater()) {
            $newY = $player->getWorld()->getHighestBlockAt($pos->getFloorX(), $pos->getFloorZ());
            $player->teleport(new Position($pos->getFloorX(), $newY + 1, $pos->getFloorZ(), $world));
        }
    }

}

