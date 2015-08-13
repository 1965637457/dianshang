<?php
namespace Admin\Controller;
class EditorController extends CommonController{

    public function elfinder(){
        $this->display();
    }
    public function elfinderforspecimage(){
        $this->display();
    }
    public function connectElfinder(){
        import("Com.Elfinder.elFinderConnector");
        import("Com.Elfinder.elFinder");
        import("Com.Elfinder.elFinderVolumeDriver");
        import("Com.Elfinder.elFinderVolumeLocalFileSystem");
        // Required for MySQL storage connector
        // include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeMySQL.class.php';
        // Required for FTP connector support
        // include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'elFinderVolumeFTP.class.php';


        /**
        * Simple function to demonstrate how to control file access using "accessControl" callback.
        * This method will disable accessing files/folders starting from  '.' (dot)
        *
        * @param  string  $attr  attribute name (read|write|locked|hidden)
        * @param  string  $path  file path relative to volume root directory started with directory separator
        * @return bool|null
        **/
        function access($attr, $path, $data, $volume) {
                return strpos(basename($path), '.') === 0       // if file/folder begins with '.' (dot)
                        ? !($attr == 'read' || $attr == 'write')    // set read+write to false, other (locked+hidden) set to true
                        :  null;                                    // else elFinder decide it itself
        }

        $opts = array(
            // 'debug' => true,
            'roots' => array(
                array(
                    'driver' => 'LocalFileSystem', // driver for accessing file system (REQUIRED)
                    'path' => './Uploads/editor/', // path to files (REQUIRED)
                    'URL' => '/Uploads/editor/', // URL to files (REQUIRED)
                    'accessControl' => 'access',             // disable and hide dot starting files (OPTIONAL)
                    'copyOverwrite' => false,
                    'uploadOverwrite' => false,
                )
            )
        );

        // run elFinder
        $connector = new \elFinderConnector(new \elFinder($opts));
        $connector->run();
    }
}

?>
