<?php

/**
 * Класс, измеряющий время выполнения скрипта
 */
 
class Timer {
	
    private static $start = 0; 	// время начала выполнения скрипта

    static function start(){	// начало подсчёта
		
        self::$start = microtime(true);
    }

    static function finish(){	// конец подсчёта
    
        return microtime(true) - self::$start;
    }
}