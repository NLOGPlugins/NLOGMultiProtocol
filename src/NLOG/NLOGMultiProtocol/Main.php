<?php

namespace NLOG\NLOGMultiProtocol;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\Config;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\LoginPacket;


class Main extends PluginBase implements Listener {
	
   public $acceptProtocol = [];

   public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->getLogger()->info("MultiProtocol 플러그인 활성화");
		
		@mkdir($this->getDataFolder());
		$this->acceptProtocol = (new Config($this->getDataFolder()."accept.yml", Config::YAML, ["accept-protocol" => ProtocolInfo::CURRENT_PROTOCOL]))->get("accept-protocol");
		if (empty($this->acceptProtocol)) {
			$this->acceptProtocol[ProtocolInfo::CURRENT_PROTOCOL];
		}
    }
    
    public function onDataPacketRecieve (DataPacketReceiveEvent $ev) {
    	$pk = $ev->getPacket();
    	if ($pk instanceof LoginPacket) {
    		if (in_array($pk->protocol, $this->acceptProtocol)) {
    			$pk->protocol = ProtocolInfo::CURRENT_PROTOCOL;
    		}
    	}
    }
}

?>