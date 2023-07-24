<?php

namespace PS\GestionBundle\Twig;

class VisitorCounterExtension extends \Twig_Extension
{
    private $kernelRootDir;

    public function __construct($kernelRootDir)
    {
        
        $this->kernelRootDir = $kernelRootDir;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('visitor_counter', [$this, 'count']),
        ];
    }

    /**
     * @return mixed
     */
    public function count($countUnique = 1, $uniqueHours = 24, $minDigits = 0, $thousandsSeparator = ' ')
    {

        // Tell browsers not to cache the file output so we can count all hits
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");

        $page = 'visits';
        // Set values for cookie and log file names
        $cname   = 'tcountUnique_' . $page;
        $logfile = $this->kernelRootDir . '/logs/' . $page . '.log';
        $date = date('Y-m-d');

        // Open log file for reading and writing
        if ($fp = fopen($logfile, 'r+')) {
            // Lock log file from other scripts
            $locked = flock($fp, LOCK_EX);

            // Lock successful?
            if ($locked) {
                $filesize = filesize($logfile);


                // Let's read current count
                $count = intval(trim(fread($fp, $filesize ?: 1024)));

                // If counting unique hits is enabled make sure it's a unique hit
                if ($countUnique == 0 || !isset($_COOKIE[$cname])) {
                    // Update count by 1 and write the new value to the log file
                    $count += 1;
                    rewind($fp);
                    fwrite($fp, $count);

                    // Print the Cookie and P3P compact privacy policy
                    header('P3P: CP="NOI NID"');
                    setcookie($cname, 1, time() + 60 * 60 * $uniqueHours);
                }
            } else {
                // Lock not successful. Better to ignore than to damage the log file
                $count = 1;
            }

            // Release file lock and close file handle
            flock($fp, LOCK_UN);
            fclose($fp);
        } else {
            throw new \LogicException(__CLASS__.": PHP needs permission to write to file $logfile");
        }

        // Is zero-padding enabled? If yes, add zeros if required
        if ($minDigits) {
            $count = sprintf('%0' . $minDigits . 's', $count);
        }

        // Format thousands?
        if (strlen($thousandsSeparator)) {
            $count = number_format($count, 0, '', $thousandsSeparator);
        }

        return $count;
    }
}
