<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Config class
 */

class RCMS_Config {
    /**
     * @var array
     */
    private $config = array (
        array(),
        array()
    );

	public function __construct () {
		$this->addStack(0, "");
	}

    /**
     * @param $stack
     * @param $dir
     *
     * Add Stack to Config By Dir
     */
    public function addStack ($stack, $dir) {
        $dir = APP . DS . "Config" . DS . $dir;

        if (is_dir($dir))
            foreach (scandir($dir) as $file) {
                $file = $dir . DS . $file;

                if (is_file($file)) {
                    $path = pathinfo($file);
                    $this->config[$stack][$path["filename"]] = include $file;
                }
            }
    }

    /**
     * @param $stack
     * @param $type
     * @param $key
     * @return bool
     *
     * Get Config value by Stack
     */
    public function get ($stack, $type, $key) {
		return isset($this->config[$stack][$type][$key]) ? $this->config[$stack][$type][$key] : false;
	}
}