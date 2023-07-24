<?php

namespace PS\GestionBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;
use Twig\Extension\AbstractExtension;

use Symfony\Component\Form\FormRendererInterface;
use Symfony\Component\Form\FormView;

class CustomExtension extends AbstractExtension
{

    /**
     * @var mixed
     */
    private $request;
    /**
     * @var mixed
     */
    private $bundleDir;

    private $renderer;

    /**
     * @param RequestStack $request
     * @param $bundleDir
     */
    public function __construct(RequestStack $request, $bundleDir, FormRendererInterface $renderer)
    {
        $this->request   = $request;
        $this->bundleDir = $bundleDir;
        $this->renderer = $renderer;
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('localized_file', [$this, 'localizedFile']),
            new \Twig_SimpleFunction('data_get', [$this, 'dataGet']),
            new \Twig_SimpleFunction('form_row_inline', [$this, 'formRowInline'], ['is_safe' => ['html']]),
        ];
    }

    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('truncate', [$this, 'truncate']),
            new \Twig_SimpleFilter('format_money', [$this, 'formatMoney']),
        ];
    }


    /**
     * @param FormView $view
     * @param array $variables
     * @return mixed
     */
    public function formRowInline(FormView $view, array $variables = [])
    {
        return $this->renderer->renderBlock($view, 'form_row_inline', $variables);
    }


    public function dataGet($target, $key, $default = null)
    {


        if (is_null($key)) {
            return $target;
        }

        foreach (explode('.', $key) as $segment) {
            if (is_array($target)) {
                if (!array_key_exists($segment, $target)) {
                    return value($default);
                }

                $target = $target[$segment];
            } elseif ($target instanceof ArrayAccess) {
                if (!isset($target[$segment])) {
                    return value($default);
                }

                $target = $target[$segment];
            } elseif (is_object($target)) {

                $method = 'get' . ucfirst($segment);




                if (!method_exists($target, $method) || !$target->{$method}()) {

                    return value($default);
                }





                $target = $target->{$method}();
            } else {
                return value($default);
            }
        }

        return $target;
    }


    /**
     * Converti un nombre (.) en nombre à virgule(,)
     * @param  mixed $number
     * @return string
     */
    public function formatNumber($number, $space = true)
    {
        if (strpos((string) $number, '.') !== false) {
            list($real, $decimal) = explode('.', $number);
            if ($decimal == 0) {
                $round = 0;
            } else {
                $round = 2;
            }
        } else {
            $round = 0;
        }

        $number = floatval($number);
        $space  = $space === false ? '' : ' ';
        return number_format($number, $round, ',', $space);
    }

    /**
     * @param $number
     * @param $zero
     * @param false $devise
     */
    public function formatMoney($number, $zero = false, $devise = 'FCFA')
    {

        $number = abs($number);
        if (in_array($devise, ['EUR', 'EURO'])) {
            $number /= 655.95;
            $devise = '€';
        } else {
            $devise = 'FCFA';
        }

        if ($number > 0) {
            $result = $this->formatNumber($number) . ' ' . $devise;
        } else {
            if ($zero === true) {
                $result = "0 {$devise}";
            } else {
                $result = 'Gratuit';
            }
        }
        return $result;
    }

    /**
     * @param $file
     * @param $defaultFile
     * @return mixed
     */
    public function localizedFile($file, $defaultFile = '')
    {
        $locale            = $this->request->getCurrentRequest()->getLocale();
        $pathInfo          = pathinfo($file);
        $dirname           = $pathInfo['dirname'];
        $realFileName      = $pathInfo['basename'];
        $localizedFileName = $pathInfo['filename'] . '_' . $locale . '.' . $pathInfo['extension'];
        $basePath          = $this->bundleDir . '/' . $dirname;

        if (file_exists($basePath . '/' . $localizedFileName)) {
            return $dirname . '/' . $localizedFileName;
        } elseif (file_exists($basePath . '/' . $realFileName)) {
            return $dirname . '/' . $realFileName;
        }

        return $dirname . '/' . $defaultFile;
    }

    /**
     * @param $string
     * @param $length
     * @param $etc
     * @param $breakWords
     * @param false $middle
     * @return mixed
     */
    public function truncate($string, $length = 80, $etc = '...', $breakWords = false, $middle = false)
    {
        if ($length == 0) {
            return '';
        }

        if (true) {
            if (mb_strlen($string, 'utf-8') > $length) {
                $length -= min($length, mb_strlen($etc, 'utf-8'));
                if (!$breakWords && !$middle) {
                    $string = preg_replace(
                        '/\s+?(\S+)?$/u',
                        '',
                        mb_substr($string, 0, $length + 1, 'utf-8')
                    );
                }
                if (!$middle) {
                    return mb_substr($string, 0, $length, 'utf-8') . $etc;
                }

                return mb_substr($string, 0, $length / 2, 'utf-8') . $etc .
                    mb_substr($string, -$length / 2, $length, 'utf-8');
            }

            return $string;
        }

        // no MBString fallback
        if (isset($string[$length])) {
            $length -= min($length, strlen($etc));
            if (!$breakWords && !$middle) {
                $string = preg_replace('/\s+?(\S+)?$/', '', substr($string, 0, $length + 1));
            }
            if (!$middle) {
                return substr($string, 0, $length) . $etc;
            }

            return substr($string, 0, $length / 2) . $etc . substr($string, -$length / 2);
        }

        return $string;
    }
}
