<?php

namespace Concrete\Package\MyShops\Controller\Search;

use Concrete\Core\Controller\AbstractController;
use Concrete\Core\Search\StickyRequest;
use MyShops\Search\Columns\Sets\Shops as ColumnSet;
use MyShops\Search\Lists\Shops as SearchList;
use MyShops\Search\Results\Shops as SearchResult;

/**
 * Controller for the shop search.
 */
class Shops extends AbstractController
{
    /**
     * Instance of a class that holds the criteria of the last performed search.
     *
     * @var StickyRequest|null
     */
    private $stickyRequest;

    /**
     * Get the instance of a class that holds the criteria of the last performed search.
     *
     * @return StickyRequest
     */
    public function getStickyRequest()
    {
        if ($this->stickyRequest === null) {
            $this->stickyRequest = $this->app->make(StickyRequest::class, ['myshops.shops']);
        }

        return $this->stickyRequest;
    }

    /**
     * Instance of a class that defines the search list.
     *
     * @var SearchList
     */
    private $searchList;

    /**
     * Get the instance of a class that defines the search list.
     *
     * @return SearchList
     */
    protected function getSearchList()
    {
        if ($this->searchList === null) {
            $this->searchList = $this->app->make(SearchList::class, [$this->getStickyRequest()]);
        }

        return $this->searchList;
    }

    /**
     * Instance of a class that defines the search results.
     *
     * @var SearchResult|null
     */
    private $searchResult;

    /**
     * List of allowed pagination sizes.
     *
     * @return int[]
     */
    public function getAllowedPaginationSizes()
    {
        return [
            10,
            20,
            50,
            100,
            200,
            500,
            1000,
        ];
    }

    /**
     * Get the default pagination size.
     *
     * @return int
     */
    public function getDefaultPaginationSize()
    {
        $allAllowed = $this->getAllowedPaginationSizes();

        return $allAllowed[1];
    }

    /**
     * Perform the search.
     *
     * @param bool $reset Should we reset all the previous search criteria?
     */
    public function search($reset = false)
    {
        $stickyRequest = $this->getStickyRequest();
        $searchList = $this->getSearchList();
        if ($reset) {
            $stickyRequest->resetSearchRequest();
        }
        $req = $stickyRequest->getSearchRequest();

        $columnSet = new ColumnSet();
        if (!$searchList->getActiveSortColumn()) {
            $sortColumn = $columnSet->getDefaultSortColumn();
            $searchList->sanitizedSortBy($sortColumn->getColumnKey(), $sortColumn->getColumnDefaultSortDirection());
        }
        $valn = $this->app->make('helper/validation/numbers');
        /* @var \Concrete\Core\Utility\Service\Validation\Numbers $valn */
        $req = $stickyRequest->getSearchRequest();

        $q = isset($req['enabled']) ? $req['enabled'] : null;
        if ($q === 'yes') {
            $searchList->filterByEnabled(true);
        } elseif ($q === 'no') {
            $searchList->filterByEnabled(false);
        }

        $q = isset($req['name']) ? $req['name'] : null;
        if (is_string($q) && $q !== '') {
            $searchList->filterByName($q);
        }

        $q = isset($req['location']) ? $req['location'] : null;
        if (is_string($q) && $q !== '') {
            $searchList->filterByLocation($q);
        }

        $q = isset($req['products']) ? $req['products'] : null;
        if (is_string($q) && $q !== '') {
            $searchList->filterByProducts($q);
        }

        $q = isset($req['comment']) ? $req['comment'] : null;
        if (is_string($q) && $q !== '') {
            $searchList->filterByComment($q);
        }

        $q = isset($req['url']) ? $req['url'] : null;
        if (is_string($q) && $q !== '') {
            $searchList->filterByUrl($q);
        }

        $paginationSize = null;
        $q = isset($req['paginationSize']) ? $req['paginationSize'] : null;
        if ($q && $valn->integer($q)) {
            $q = (int) $q;
            $paginationSizes = $this->getAllowedPaginationSizes();
            if (in_array($q, $paginationSizes, true)) {
                $paginationSize = (int) $q;
            }
        }
        if ($paginationSize === null) {
            $paginationSize = $this->getDefaultPaginationSize();
        }
        $searchList->setItemsPerPage($paginationSize);

        $this->searchResult = new SearchResult($columnSet, $searchList, $this->app->make('url/manager')->resolve(['dashboard/shops']));
    }

    /**
     * Get the search result (once the search() method has been called).
     *
     * @return SearchResult|null
     */
    public function getSearchResultObject()
    {
        return $this->searchResult;
    }
}
