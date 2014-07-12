<?php
/**
 * @author RCMS<rcms@rev1lz.com>
 * @version 1.o
 *
 * RCMS Lang class
 */

class RCMS_Lang {
    /**
     * @var object
     */

    private $_config;
    /**
     * @var array
     */
    private $_lang = array (
        array(),
        array()
    );

	public function __construct () {
        $this->_config = RCMS_Registry::getInstance()
            ->getObject("Config");

		$this->addStack(0, "");
	}

    /**
     * @param $stack
     * @param $dir
     * @throws Exception
     *
     * Add Stack to Config By Dir
     */
    public function addStack ($stack, $dir) {
        $dir =  APP . DS . "Lang" . DS . $this->_config->get(0, "Site", "Language") . DS . $dir;
        if (is_dir($dir))
            foreach (scandir($dir) as $file) {
                $file = $dir . DS . $file;

                if (is_file($file)) {
                    if ($lang = @parse_ini_file($file, true)) {
                        $path = pathinfo($file);
                        $this->_lang[$stack][$path["filename"]] = $lang;
                    } else
                        throw new Exception("Lang error: error parsing file {$file}");
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
		return isset($this->_lang[$stack][$type][$key]) ? $this->_lang[$stack][$type][$key] : false;
	}
}