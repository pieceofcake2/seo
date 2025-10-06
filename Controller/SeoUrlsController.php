<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoUrl $SeoUrl
 */
class SeoUrlsController extends SeoAppController
{
    public $name = 'SeoUrls';

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null)
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoUrl']['filter'];
        }
        $conditions = $this->SeoUrl->generateFilterConditions($filter);
        $this->set('seoUrls', $this->paginate($conditions));
        $this->set('filter', $filter);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     */
    public function admin_view(?int $id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid seo url'));

            return $this->redirect(['action' => 'index']);
        }
        $this->set('seoUri', $this->SeoUrl->findById($id));
        $this->set('id', $id);
    }

    /**
     * @return CakeResponse|null|void
     */
    public function admin_add()
    {
        if (!empty($this->data)) {
            $this->SeoUrl->clear();
            if ($this->SeoUrl->saveAll($this->data)) {
                $this->Session->setFlash(__('The seo url has been saved'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Session->setFlash(__('The seo url could not be saved. Please, try again.'));
            }
        }
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     */
    public function admin_edit(?int $id = null)
    {
        if (!$id && empty($this->data)) {
            $this->Session->setFlash(__('Invalid seo url'));

            return $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->SeoUrl->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo url has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo url could not be saved. Please, try again.'), $badFlash);
            }
        }

        if (empty($this->data)) {
            $this->data = $this->SeoUrl->findById($id);
        }

        $this->set('id', $id);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null
     */
    public function admin_delete(?int $id = null)
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid id for seo url'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoUrl->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo url deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo url was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }

    /**
     * @param ?int $id
     * @return CakeResponse|null
     */
    public function admin_approve(?int $id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for seo url'));
        } elseif ($this->SeoUrl->setApproved($id)) {
            $this->Session->setFlash(__('Seo Uri approved'));
        }

        return $this->redirect(['admin' => true, 'action' => 'index']);
    }
}
