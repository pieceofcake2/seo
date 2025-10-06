<?php

/**
 * @property SeoTitle $SeoTitle
 */
class SeoTitlesController extends SeoAppController
{
    public $name = 'SeoTitles';
    public $helpers = ['Time'];

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null)
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoTitle']['filter'];
        }
        $conditions = $this->SeoTitle->generateFilterConditions($filter);
        $this->set('seoTitles', $this->paginate($conditions));
        $this->set('filter', $filter);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     */
    public function admin_view(?int $id = null)
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo title'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        $this->set('seoTitle', $this->SeoTitle->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return CakeResponse|null|void
     * @throws Exception
     */
    public function admin_add()
    {
        if (!empty($this->data)) {
            $this->SeoTitle->clear();
            if ($this->SeoTitle->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo title has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo title could not be saved. Please, try again.'), $badFlash);
            }
        }

        $seoUris = $this->SeoTitle->SeoUri->find('list');
        $this->set(compact('seoUris'));
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     * @throws Exception
     */
    public function admin_edit(?int $id = null)
    {
        if (!$id && empty($this->data)) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo title'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if (!empty($this->data)) {
            if ($this->SeoTitle->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo title has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo title could not be saved. Please, try again.'), $badFlash);
            }
        }

        if (empty($this->data)) {
            $this->data = $this->SeoTitle->read(null, $id);
        }
        $seoUris = $this->SeoTitle->SeoUri->find('list');
        $this->set(compact('seoUris'));
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
            $this->Session->setFlash(__('Invalid id for seo title'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoTitle->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo title deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo title was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }
}
