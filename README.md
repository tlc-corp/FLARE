![Image Unavailable](https://raw.githubusercontent.com/tlc-corp/FLARE/master/fL.png "FLARE Logo")
# FLARE
Better than PocketMine!
## Features:
- Biome generation
- Plugin API
- Mob AI
- Weather implentation
- Even more!


## Plugin API
Example:
```
namespace ExamplePlugin;

use Flare\Server;

class ExamplePlugin extends PluginBase implents Listener{

public function onEnable(){
$this->getLogger()->info("Example!");
}
}
```
