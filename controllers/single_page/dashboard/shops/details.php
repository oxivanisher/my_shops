<?php

namespace Concrete\Package\MyShops\Controller\SinglePage\Dashboard\Shops;

use Concrete\Core\Http\ResponseFactoryInterface;
use Concrete\Core\Page\Controller\DashboardPageController;
use MyShops\Entity\Shop;

defined('C5_EXECUTE') or die('Access Denied.');

/**
 * Controller of the /dashboard/shops/details page.
 */
class Details extends DashboardPageController
{
    /**
     * Default method called when viewing the page.
     *
     * @param mixed $id
     */
    public function view($id = null)
    {
        $shop = $this->idToEntity($id);
        if ($shop === null) {
            $this->flash('error', t('Unable to find the specified shop.'));

            return $this->redirect('/dashboard/shops');
        }
        $this->set('shop', $shop);
    }

    /**
     * Method called when saving the entity.
     *
     * @param mixed $id
     */
    public function save($id = null)
    {
        $entity = $this->idToEntity($id);
        if ($entity === null) {
            $this->flash('error', t('Unable to find the specified shop.'));

            return $this->redirect('/dashboard/shops');
        }
        $errors = $this->app->make('error');
        /* @var \Concrete\Core\Error\ErrorList\ErrorList $errors */
        if (!$this->token->validate('myshops-shops-details-' . $id)) {
            $errors->add($this->token->getErrorMessage());
        } else {
            $valn = $this->app->make('helper/validation/numbers');
            /* @var \Concrete\Core\Utility\Service\Validation\Numbers $valn */
            $post = $this->request->request;
            $value = $post->get('name');
            $value = is_string($value) ? trim($value) : '';
            if ($value === '') {
                $errors->add(t('Please specify the name of the shop.'));
            } else {
                $entity->setName($value);
            }
            $value = $post->get('enabled');
            if ($valn->integer($value, 0, 1)) {
                $entity->setIsEnabled($value);
            } else {
                $errors->add(t('Please specify if the shop is enabled.'));
            }
            $value = $post->get('location');
            $value = is_string($value) ? trim($value) : '';
            if ($value === '') {
                $errors->add(t('Please specify the location of the shop.'));
            } else {
                $entity->setLocation($value);
            }
            $value = $post->get('products');
            $value = is_string($value) ? trim($value) : '';
            if ($value === '') {
                $errors->add(t('Please specify the products of the shop.'));
            } else {
                $entity->setProducts($value);
            }

            $value = $post->get('comment');
            $value = is_string($value) ? trim($value) : '';
            $entity->setComment($value);

            $value = $post->get('url');
            $value = is_string($value) ? trim($value) : '';
            if ($value === '') {
                $errors->add(t('Please specify the url of the shop.'));
            } else {
                $entity->setUrl($value);
            }
        }
        if ($errors->has()) {
            $this->view($id);
            $this->flash('error', $errors);
        } else {
            $this->entityManager->persist($entity);
            $this->entityManager->flush($entity);
            $this->flash('success', $id === 'new' ? t('The new shop has been added.') : t('The shop has been updated.'));

            return $this->redirect('/dashboard/shops');
        }
    }

    /**
     * Method called via ajax requests to delete the currently editing entity.
     *
     * @param null|mixed $id
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function delete($id = null)
    {
        $errors = $this->app->make('error');
        /* @var \Concrete\Core\Error\ErrorList\ErrorList $errors */
        $rf = $this->app->make(ResponseFactoryInterface::class);
        /* @var ResponseFactoryInterface $rf */
        if (!$this->token->validate('myshops-shops-details-delete-' . $id)) {
            $errors->add($this->token->getErrorMessage());
        } else {
            $entity = $this->idToEntity($id);
            if ($entity === null || $entity->getId() === null) {
                $errors->add(t('Unable to find the specified shop.'));
            } else {
                $this->flash('success', t('The shop has been deleted.'));
                $this->entityManager->remove($entity);
                $this->entityManager->flush($entity);

                return $rf->json(true);
            }
        }

        return $rf->json($errors->jsonSerialize());
    }

    /**
     * Get the entity gived its id (or 'new').
     *
     * @param int|string|mixed $id
     * @param bool $redirectIfNotFound
     *
     * @return Shop|null
     */
    private function idToEntity($id)
    {
        $result = null;
        if ($id === 'new') {
            $result = Shop::create('');
        } else {
            $valn = $this->app->make('helper/validation/numbers');
            /* @var \Concrete\Core\Utility\Service\Validation\Numbers $valn */
            if ($valn->integer($id, 1)) {
                $result = $this->entityManager->find(Shop::class, $id);
            }
        }

        return $result;
    }
}
