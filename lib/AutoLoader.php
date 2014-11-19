<?php
class AutoLoader {
    protected static $paths = array(
        "lib",
        "widgets"
    );
    
    /**
     * Add additional paths to check for dependencies.
     * Path should be relative and without preceding and trailing slashes.
     */
    public static function addPath($path) {
        $path = realpath($path);
        if ($path) {
            self::$paths[] = $path;
        }
    }
    
    /**
     * Recursively checks list of paths for dependency.
     * Takes namespaces into account.
     */
    public static function load($class) {
        // Consider namespaces.
        $parts = explode('\\', $class);
        $class = end($parts);
        
        // Recursive search.
        foreach (self::$paths as $path) {
            $directory = new RecursiveDirectoryIterator($path);
            $recIterator = new RecursiveIteratorIterator($directory);
            $recIterator->setMaxDepth(1);
            $regex = new RegexIterator($recIterator, '/' . $class . '.php$/i');
            foreach($regex as $item) {
                if (is_file($item->getPathName())) {
                    require $item->getPathName();
                    return;
                }
            }
        }
    }
}
?>