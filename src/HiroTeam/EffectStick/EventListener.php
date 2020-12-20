<?php

namespace HiroTeam\EffectStick;

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;

class EventListener implements Listener
{

    /**
     * @var array
     */
    private $sticks;

    /**
     * @var array
     */
    private $cooldown = [];

    public function __construct()
    {
        $this->sticks = [
            '424:0' => [new EffectInstance(
                Effect::getEffect(Effect::HEALING),
                1,
                3,
                false
            ), '§aTu as bien été heal'],
            '353:0' => [new EffectInstance(
                Effect::getEffect(Effect::SPEED),
                12 * 20,
                3,
                false
            ), '§aTu as bien été speed']
        ];
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $item = $event->getItem();
        $idMeta = $item->getId() .':'. $item->getDamage();
        if (isset($this->sticks[$idMeta])) {
            $player = $event->getPlayer();
            $LastPlayerTime = $this->cooldown[$player->getName()] ?? 0;
            $timeNow = time();
            if($timeNow - $LastPlayerTime >= 5){
                $player->addEffect($this->sticks[$idMeta][0]);
                $this->cooldown[$player->getName()] = time();
                $player->getInventory()->removeItem($item->setCount(1));
                $player->sendPopup($this->sticks[$idMeta][1]);
            }
        }

    }
}