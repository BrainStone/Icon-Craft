<?php
/**
 * Lossy less compression for png files. 
 * It uses optiPNG, advpng and advdef for the compression. 
 * @author Ananda Raj Pandey <vendornepala@gmail.com>
 * 
 * @example 
 * To compress the image by lossyless:
 * LossylessHelper::lossylessImg('\img\example.png');
 * 
 * To compress the images in the folder:
 * LossylessHelper::lossylessFolder('\img');
 * 
 * @copyright     Copyright (c) Ananda Raj Pandey <vendornepala@gmail.com>.
 * Redistributions of files must retain the above copyright notice.
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 * Git hub repo  https://github.com/anandarajpandey/losssylesscompressionPHP.git
 */
class LossylessHelper {

    const CMD = 'optipng -o3 {$file} && advpng -z -4 {$file} && advdef -z -4 {$file}';
   
    /**
     * Lossy less compression for image.
     * @param string $imgName full path for the image.
     * @param bool $log enable the log for Yii application, if you are not using yii you can set to false.
     *
     *      * and also comment the two lines.
     * if ($log)
            Yii::log($log);
     */
    public static function lossylessImg($imgName, $log = false) {

        $log = "Starting lossyless compression for $imgName.\n";

        if (self::_checkExtension($imgName) == 'png') {
            $log.='before size: ' . filesize($imgName) . '\n';
            $cmd = str_replace('{$file}', $imgName, self::CMD);
            $log.= shell_exec($cmd);
            $log.='After size: ' . filesize($imgName) . '\n';
        } else {
            $log.='Not a png file\n';
        }
        $log.="Lossyless compression of $imgName finished.\n\n";
        
        // if ($log)
        //    Yii::log($log);
    }
    /**
     * Lossy less compression for images in a recursive path.
     * @param string $path full path for the images.
     * @param bool $log enable the log for Yii application, if you are not using yii you can set to false.
     * and also comment the two lines.
     * if ($log)
            Yii::log($log);
     */
    public static function lossylessFolder($path, $log = false) {
        $log = "Starting lossy less compression for folder $path";
        $path.= (substr($path, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR);
        $directory = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::CURRENT_AS_SELF);
        $iterator = new RecursiveIteratorIterator($directory, RecursiveIteratorIterator::CHILD_FIRST);
        $countFiles = 0;
        $beforeSize = 0;
        $afterSize =0;
        foreach ($iterator as $item):
            $nPath = $path . $item->getSubPathname();
            if ($item->isFile() && self::_checkExtension($nPath) == 'png') {
                $countFiles++;
                $beforeSize+=filesize($nPath);
                self::lossylessImg($nPath, $log);
                $afterSize+=filesize($nPath);
            }
        endforeach;
        $log.="Total files Procceed $countFiles\n";
        $log.="Total before files size: $beforeSize\n";
        $log.="Total after files size: $afterSize\n";
        $saved=(($afterSize-$beforeSize)*100)/$beforeSize;
        $log.="Total saved: $saved\n";
        
        // if ($log)
        //    Yii::log($log);
    }
   
    /**
     * Private static function to check the extenstion of a file.
     * @param string $path full path of the file.
     * @return string extension
     */
    private static function _checkExtension($path) {
        return pathinfo($path, PATHINFO_EXTENSION);
    }

}

?>
