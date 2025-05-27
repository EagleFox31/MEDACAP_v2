<?php
// cronjobs/Logger.php
class Logger
{
    // Chemin vers le fichier de log
    private static $file = __DIR__ . '/logs/pif.log';

    /**
     * Écrit une ligne de log.
     *
     * @param string $level  INFO | ERROR | DEBUG
     * @param string $message
     */
    public static function log(string $level, string $message): void
    {
        $time = (new DateTime())->format('Y-m-d H:i:s');
        $line = sprintf("[%s] %-5s %s\n", $time, $level, $message);
        file_put_contents(self::$file, $line, FILE_APPEND);
    }

    public static function info(string $msg):  void { self::log('INFO',  $msg); }
    public static function debug(string $msg): void { self::log('DEBUG', $msg); }
    public static function error(string $msg): void { self::log('ERROR', $msg); }
}
