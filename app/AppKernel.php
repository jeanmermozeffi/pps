<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            //new AppBundle\AppBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Avanzu\AdminThemeBundle\AvanzuAdminThemeBundle(),
            new PS\ParametreBundle\ParametreBundle(),
            new PS\SiteBundle\SiteBundle(),
            new PS\UtilisateurBundle\UtilisateurBundle(),
            new PS\GestionBundle\GestionBundle(),
            new APY\DataGridBundle\APYDataGridBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
            //new PUGX\AutocompleterBundle\PUGXAutocompleterBundle(),
            //new Tetranz\Select2EntityBundle\TetranzSelect2EntityBundle(),
            new Ensepar\Html2pdfBundle\EnseparHtml2pdfBundle(),
            new Ob\HighchartsBundle\ObHighchartsBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new PS\ApiBundle\PSApiBundle(),
            new Nelmio\CorsBundle\NelmioCorsBundle(),
            new Lexik\Bundle\JWTAuthenticationBundle\LexikJWTAuthenticationBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new Ivory\Base64FileBundle\IvoryBase64FileBundle(),
            new SC\DatetimepickerBundle\SCDatetimepickerBundle(),
            new FOS\JsRoutingBundle\FOSJsRoutingBundle(),
            new Yectep\PhpSpreadsheetBundle\PhpSpreadsheetBundle(),
            new JMS\I18nRoutingBundle\JMSI18nRoutingBundle(),
            new JMS\TranslationBundle\JMSTranslationBundle(),
            new PS\SpecialiteBundle\PSSpecialiteBundle(),
            new PS\MobileBundle\MobileBundle()

        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        date_default_timezone_set('UTC');
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
