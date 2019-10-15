<?php

namespace Concrete\Package\MyShops\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Package\MyShops\Controller\Search\Shops as SearchController;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Controller of the /dashboard/shops page.
 */
class Shops extends DashboardPageController
{
    /**
     * {@inheritdoc}
     *
     * @see \Concrete\Core\Page\Controller\DashboardPageController::on_start()
     */
    public function on_start()
    {
        parent::on_start();
        $this->addHeaderItem(<<<EOT
<style>
table.ccm-search-results-table tr.shop {
    cursor: pointer;
}
table.ccm-search-results-table tr.shop-disabled td {
    background-color: #fee;
}
</style>
EOT
        );
    }

    /**
     * Default method called when viewing the page.
     */
    public function view()
    {
        $resetSearch = false;
        if ($this->request->isPost()) {
            if (!$this->token->validate('myshops-shops-search')) {
                $this->error->add($this->token->getErrorMessage());
            } else {
                $resetSearch = true;
            }
        }
        $searchController = $this->app->make(SearchController::class);
        $searchController->search($resetSearch);
        $result = $searchController->getSearchResultObject();
        $this->set('result', $result);
        $allowedPaginationSizes = array_combine($searchController->getAllowedPaginationSizes(), $searchController->getAllowedPaginationSizes());
        $params = $searchController->getStickyRequest()->getSearchRequest();
        $this->set('name', isset($params['name']) ? $params['name'] : '');
        $this->set('enabled', isset($params['enabled']) ? $params['enabled'] : '');
        if (isset($params['paginationSize']) && isset($allowedPaginationSizes[$params['paginationSize']])) {
            $paginationSize = (int) $params['paginationSize'];
        } else {
            $paginationSize = $searchController->getDefaultPaginationSize();
        }
        $this->set('paginationSize', $paginationSize);
        $this->set('allowedPaginationSizes', $allowedPaginationSizes);
    }
}
