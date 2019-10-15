<?php

namespace MyShops\Search\Lists;

use Concrete\Core\Application\Application;
use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Search\ItemList\Database\ItemList;
use Concrete\Core\Search\Pagination\Pagination;
use Doctrine\ORM\EntityManager;
use MyShops\Entity\Shop;
use Pagerfanta\Adapter\DoctrineDbalAdapter;

/**
 * Class that manages the criterias of the shop searches.
 */
class Shops extends ItemList implements ApplicationAwareInterface
{
    /**
     * The application container.
     *
     * @var Application
     */
    protected $app;

    /**
     * {@inheritdoc}
     *
     * @see ApplicationAwareInterface::setApplication()
     */
    public function setApplication(Application $app)
    {
        $this->app = $app;
    }

    /**
     * The parameter name to be used for pagination.
     *
     * @var string
     */
    protected $paginationPageParameter = 'shops_page';

    /**
     * The columns that can be sorted via the web interface.
     *
     * @var array
     */
    protected $autoSortColumns = [
        's.name',
        's.enabled',
        's.location',
        's.products',
        's.comment',
        's.url',
    ];

    /**
     * {@inheritdoc}
     *
     * @see ItemList::createQuery()
     */
    public function createQuery()
    {
        $this->query->select('s.id')
            ->from('Shops', 's')
        ;
    }

    /**
     * Filter the results by part of the shop name.
     *
     * @param string $name
     */
    public function filterByName($name)
    {
        $name = (string) $name;
        if ($name !== '') {
            $this->query->andWhere($this->query->expr()->like('s.name', $this->query->createNamedParameter('%' . addcslashes($name, '%_\\') . '%')));
        }
    }

    /**
     * Filter the enabled/disabled shops.
     *
     * @param bool $enabled
     */
    public function filterByEnabled($enabled)
    {
        $this->query->andWhere($this->query->expr()->eq('s.enabled', $enabled ? 1 : 0));
    }

    /**
     * Filter the results by part of the shop location.
     *
     * @param string $location
     */
    public function filterByLocation($location)
    {
        $location = (string) $location;
        if ($location !== '') {
            $this->query->andWhere($this->query->expr()->like('s.location', $this->query->createNamedParameter('%' . addcslashes($location, '%_\\') . '%')));
        }
    }

    /**
     * Filter the results by part of the shop products.
     *
     * @param string $products
     */
    public function filterByProducts($products)
    {
        $products = (string) $products;
        if ($products !== '') {
            $this->query->andWhere($this->query->expr()->like('s.products', $this->query->createNamedParameter('%' . addcslashes($products, '%_\\') . '%')));
        }
    }

    /**
     * Filter the results by part of the shop comment.
     *
     * @param string $comment
     */
    public function filterByComment($comment)
    {
        $comment = (string) $comment;
        if ($comment !== '') {
            $this->query->andWhere($this->query->expr()->like('s.comment', $this->query->createNamedParameter('%' . addcslashes($comment, '%_\\') . '%')));
        }
    }

    /**
     * Filter the results by part of the shop url.
     *
     * @param string $url
     */
    public function filterByUrl($url)
    {
        $url = (string) $url;
        if ($url !== '') {
            $this->query->andWhere($this->query->expr()->like('s.url', $this->query->createNamedParameter('%' . addcslashes($url, '%_\\') . '%')));
        }
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Search\ItemList\ItemList::getTotalResults()
     */
    public function getTotalResults()
    {
        $query = $this->deliverQueryObject();
        $query
            ->resetQueryParts(['groupBy', 'orderBy'])
            ->select('count(distinct s.id)')
            ->setMaxResults(1);
        $result = $query->execute()->fetchColumn();

        return (int) $result;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Search\ItemList\ItemList::createPaginationObject()
     */
    protected function createPaginationObject()
    {
        $adapter = new DoctrineDbalAdapter(
            $this->deliverQueryObject(),
            function (\Doctrine\DBAL\Query\QueryBuilder $query) {
                $query
                    ->resetQueryParts(['groupBy', 'orderBy'])
                    ->select('count(distinct s.id)')
                    ->setMaxResults(1);
            }
        );
        $pagination = new Pagination($this, $adapter);

        return $pagination;
    }

    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Search\ItemList\ItemList::getResult()
     */
    public function getResult($queryRow)
    {
        $entityManager = $this->app->make(EntityManager::class);
        /* @var EntityManager $entityManager */
        return $entityManager->find(Shop::class, $queryRow['id']);
    }
}
