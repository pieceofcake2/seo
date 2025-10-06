<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoStatusCode $SeoStatusCode
 */
class SeoStatusCodesController extends SeoAppController
{
    public $name = 'SeoStatusCodes';
    public $helpers = ['Time'];

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null)
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoStatusCode']['filter'];
        }

        $conditions = $this->SeoStatusCode->generateFilterConditions($filter);
        $this->set('seoStatusCodes', $this->paginate($conditions));
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
            $this->Session->setFlash(__('Invalid seo status code'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        $this->set('seoStatusCode', $this->SeoStatusCode->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return CakeResponse|null|void
     * @throws Exception
     */
    public function admin_add()
    {
        if (!empty($this->data)) {
            $this->SeoStatusCode->clear();
            if ($this->SeoStatusCode->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo status code has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo status code could not be saved. Please, try again.'), $badFlash);
            }
        }

        $this->set('status_codes', $this->SeoStatusCode->findCodeList());
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
            $this->Session->setFlash(__('Invalid seo status code'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->SeoStatusCode->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo status code has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo status code could not be saved. Please, try again.'), $badFlash);
            }
        }

        if (empty($this->data)) {
            $this->data = $this->SeoStatusCode->read(null, $id);
        }

        $this->set('status_codes', $this->SeoStatusCode->findCodeList());
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
            $this->Session->setFlash(__('Invalid id for seo status code'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoStatusCode->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo status code deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo status code was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }
}
