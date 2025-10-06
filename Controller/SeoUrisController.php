<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoUri $SeoUri
 */
class SeoUrisController extends SeoAppController
{
    public $name = 'SeoUris';
    public $helpers = ['Time'];
    public $uses = ['Seo.SeoUri'];

    /**
     * @return void
     */
    private function clearAssociatesIfEmpty()
    {
        foreach ($this->request->data['SeoMetaTag'] as $key => $metatag) {
            if (isset($metatag['name']) && empty($metatag['name'])) {
                unset($this->request->data['SeoMetaTag'][$key]);
            }
        }
        if (empty($this->request->data['SeoMetaTag'])) {
            unset($this->request->data['SeoMetaTag']);
        }
        if (isset($this->request->data['SeoTitle']['title']) && empty($this->request->data['SeoTitle']['title'])) {
            unset($this->request->data['SeoTitle']);
        }
    }

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null)
    {
        if (!empty($this->request->data)) {
            $filter = $this->request->data['SeoUri']['filter'];
        }

        $conditions = $this->SeoUri->generateFilterConditions($filter);
        $this->set('seoUris', $this->paginate($conditions));
        $this->set('filter', $filter);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null
     */
    public function admin_urlencode(?int $id = null)
    {
        if ($this->SeoUri->urlEncode($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash('uri Successfully Url Encoded.', $goodFlash);
        } else {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash('Erorr URL Encoding uri', $badFlash);
        }

        return $this->redirect(['action' => 'edit', $id]);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     */
    public function admin_view(?int $id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid seo uri'));

            return $this->redirect(['action' => 'index']);
        }
        $this->set('seoUri', $this->SeoUri->findForViewById($id));
        $this->set('id', $id);
    }

    /**
     * @return CakeResponse|null|void
     */
    public function admin_add()
    {
        if (!empty($this->request->data)) {
            $this->SeoUri->clear();
            $this->clearAssociatesIfEmpty();

            if ($this->SeoUri->saveAll($this->request->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo uri has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo uri could not be saved. Please, try again.'), $badFlash);
            }
        }
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     * @throws Exception
     */
    public function admin_edit(?int $id = null)
    {
        if (!$id && empty($this->request->data)) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo uri'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if (!empty($this->request->data)) {
            $this->clearAssociatesIfEmpty();
            if ($this->SeoUri->save($this->request->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo uri has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo uri could not be saved. Please, try again.'), $badFlash);
            }
        }

        if (empty($this->request->data)) {
            $this->request->data = $this->SeoUri->findForViewById($id);
        }
        $this->set('status_codes', $this->SeoUri->SeoStatusCode->findCodeList());
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
            $this->Session->setFlash(__('Invalid id for seo uri'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoUri->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo uri deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo uri was not deleted'));

        return $this->redirect(['action' => 'index'], $badFlash);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null
     */
    public function admin_approve(?int $id = null)
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid id for seo uri'));
        } elseif ($this->SeoUri->setApproved($id)) {
            $this->Session->setFlash(__('Seo Uri approved'));
        }

        return $this->redirect(['admin' => true, 'action' => 'index']);
    }
}
