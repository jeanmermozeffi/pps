<?php

namespace PS\GestionBundle\Service;



class Util
{
    /**
     * @param $len
     * @return mixed
     */
    public function random($len = 4, $options = [])
    {
        $alphabet = '0123456789';

        if (isset($options['password'])) {
            $alphabet .= '!"#$%&()*+,-./:;<=>?@[\]^_`{|}~';
        }

        if (isset($options['alphabet'])) {
            $alphabet .= implode('', array_merge(range('a', 'z'), range('A', 'Z')));
        }

        if ($len < 1) {
            throw new \InvalidArgumentException('Length must be a positive integer');
        }

        $str      =  $options['prefix'] ?? '';
        $alphamax = strlen($alphabet) - 1;
        if ($alphamax < 1) {
            throw new \InvalidArgumentException('Invalid alphabet');
        }

        for ($i = 0; $i < $len; ++$i) {
            $str .= $alphabet[random_int(0, $alphamax)];
        }

        return $str;
    }
}