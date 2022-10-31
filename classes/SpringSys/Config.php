<?php
/**
 * Config
 *  - given a config file (with $cfg[] defined)
 *  - load config
 *  - get a config value
 *
 * Code Challenge
 * @author Jim A Kinsman <relipse@gmail.com>
 * @copyright 2022 Jim A Kinsman
 */

namespace SpringSys;

class Config {
    public array $cfg = [];

    /**
     * Constructor
     * @param string $file
     * @throws \Exception
     */
    public function __construct(string $file = __DIR__.'/../../config/cfg.php'){
        if (!file_exists($file)){
            throw new \Exception("Config file does not exist: $file");
        }
        $cfg = [];
        include($file);
        if (empty($cfg)){
            throw new \Exception("No configuration values");
        }
        $this->cfg = $cfg;
    }

    /**
     * Get the config value
     * @param string $key
     * @param $default
     * @return mixed
     */
    public function get(string $key, $default = ''): mixed{
        if (isset($this->cfg[$key])){
            return $this->cfg[$key];
        }else{
            return $default;
        }
    }
}
