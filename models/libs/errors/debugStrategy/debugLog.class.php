<?php

/**
 * @brief Debug messages are stored in a text file.
 * 
 * @author diego.brondo
 * @version $Version$
 * @since 20/ott/2011
 * @package ErrorHandler
 * @license GNU Public License 3 (GPL3) {@link http://www.gnu.org/licenses/gpl-3.0.txt}
 */
class debugLog extends debugStrategy {
    /**
     * File dove verra' salvato l'output (default e' './debug.log').
     */

    const DEBUG_DEFAULT_LOGFILE = './debug.log';

    /**
     * Metodo di scrittura sul file di log: se a FALSE il file verra' sovrascritto ogni volta,
     * se a TRUE i nuovi messaggi sono appesi in coda ai vecchi (default TRUE).
     */
    const DEBUG_DEFAULT_LOG_APPEND = 1;

    /**
     * The possible options are:
     * - debug_level: level of debug if it's not specified it's set by debugObject to DEBUG_DEFAULT_LEVEL
     * - logfile (string): path to the logging file (Default: './debug.log').
     * - append (bool): if true new debug information are appended at the end of the file;
     *   if it's false the file is deleted and recreated each time (Default: true).
     *
     * @param array $options
     */
    public function __construct($options = null) {
        parent::__construct($options);
        if (!isset($this->_options['logfile'])) {
            $this->_options['logfile'] = self::DEBUG_DEFAULT_LOGFILE;
        }
        if (!isset($this->_options['append'])) {
            $this->_options['append'] = self::DEBUG_DEFAULT_LOG_APPEND;
        }
        if (!$this->_options['append']) {
            $fd = @fopen($this->_options['logfile'], 'w');
            fclose($fd);
        }
    }

    /**
     * (non-PHPdoc)
     * @see debugObject::__destruct()
     */
    public function __destruct() {
        parent::__destruct();
    }

    /**
     * (non-PHPdoc)
     * @see debugObject::msg()
     */
    public function msg($msg, $level = DEBUG_INFO) {
        if (!empty($msg) && ($this->_options['debug_level'] & $level)) {
            if (DEBUG_INFO & $level) {
                $pre = 'INFO: ';
            } elseif (DEBUG_ERROR & $level || DEBUG_STRICT & $level) {
                $pre = 'ERROR: ';
            } elseif (DEBUG_WARNING & $level) {
                $pre = 'WARNING: ';
            } elseif (DEBUG_NOTICE & $level) {
                $pre = 'NOTICE: ';
            } else {
                $pre = '';
            }

            list($msec, $sec) = explode(' ', microtime());
            $t = preg_match('/\.(\d.*)/', $msec, $m);
            error_log('[' . $sec . '.' . $m[1] . '] ' . $pre . filter_var((is_array($msg) ? var_export($msg, true) : $msg), FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_NO_ENCODE_QUOTES) . PHP_EOL, 3, $this->_options['logfile']);
        }
    }

}

// ~@:-]
?>