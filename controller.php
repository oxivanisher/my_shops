<?php

namespace Concrete\Package\MyShops;

use Concrete\Core\Backup\ContentImporter;
use Concrete\Core\Package\Package;
use Doctrine\ORM\EntityManager;
use MyShops\Entity\Shop;

/**
 * The package controller.
 *
 * Manages the package installation, update and start-up.
 */
class Controller extends Package
{
    /**
     * The minimum concrete5 version.
     *
     * @var string
     */
    protected $appVersionRequired = '8';

    /**
     * The unique handle that identifies the package.
     *
     * @var string
     */
    protected $pkgHandle = 'my_shops';

    /**
     * The package version.
     *
     * @var string
     */
    protected $pkgVersion = '1.0.6';

    /**
     * Map folders to PHP namespaces, for automatic class autoloading.
     *
     * @var array
     */
    protected $pkgAutoloaderRegistries = [
        'src' => 'MyShops',
    ];

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Package\Package::getPackageName()
     */
    public function getPackageName()
    {
        return t('My Shops');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Package\Package::getPackageDescription()
     */
    public function getPackageDescription()
    {
        return t('Listing for shops');
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Package\Package::install()
     */
    public function install()
    {
        $pkg = parent::install();
        $this->installXml();
        $this->addInitialShops();
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Package\Package::upgrade()
     */
    public function upgrade()
    {
        parent::upgrade();
        $this->installXml();
    }

    /**
     * Install/update data from install XML file.
     */
    private function installXml()
    {
        $contentImporter = $this->app->make(ContentImporter::class);
        $contentImporter->importContentFile($this->getPackagePath() . '/install.xml');
    }

    /**
     * Add some sample shops.
     */
    private function addInitialShops()
    {
        $em = $this->app->make(EntityManager::class);
        /* @var EntityManager $em */
        $repo = $em->getRepository(Shop::class);
        $r = $repo->createQueryBuilder('s')->select('s.id')->setMaxResults(1)->getQuery()->execute();
        if (empty($r)) {
            foreach ([
                Shop::create('My super FPV shop', true, "Berne, Switzerland", "FPV and general RC", "This is the best shop ever!", "https://myshop.example.tld"),
                Shop::create('My super RC shop', false, "Zürich, Switzerland", "RC specialities", "This is the second best shop ever!", "https://myother.shop.tld"),
            ] as $shop) {
                $em->persist($shop);
            }
            $em->flush();
        }
    }
}
