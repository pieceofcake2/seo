<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoMetaTag $SeoMetaTag
 */
class SeoMetaTagsController extends SeoAppController
{
    public $name = 'SeoMetaTags';
    public $helpers = ['Time'];

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null): void
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoMetaTag']['filter'];
        }
        $conditions = $this->SeoMetaTag->generateFilterConditions($filter);
        $this->set('seoMetaTags', $this->paginate($conditions));
        $this->set('filter', $filter);
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function admin_view(?int $id = null): void
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo meta tag'), $badFlash);
            $this->redirect(['action' => 'index']);
        }
        $this->set('seoMetaTag', $this->SeoMetaTag->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function admin_add(): void
    {
        if (!empty($this->data)) {
            $this->SeoMetaTag->clear();
            if ($this->SeoMetaTag->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo meta tag has been saved'), $goodFlash);
                $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo meta tag could not be saved. Please, try again.'), $badFlash);
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
        if (!$id && empty($this->data)) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo meta tag'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->SeoMetaTag->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo meta tag has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo meta tag could not be saved. Please, try again.'), $badFlash);
            }
        }
        if (empty($this->data)) {
            $this->data = $this->SeoMetaTag->read(null, $id);
        }
        $this->set('id', $id);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null|void
     */
    public function admin_delete(?int $id = null)
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid id for seo meta tag'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }
        if ($this->SeoMetaTag->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo meta tag deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }
        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo meta tag was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }
}
