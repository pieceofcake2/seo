<?php

App::uses('SeoAppController', 'Seo.Controller');

class SeoRedirectsController extends SeoAppController
{
    public $name = 'SeoRedirects';
    public $helpers = ['Time'];

    /**
     * @param mixed $filter
     * @return void
     */
    public function admin_index($filter = null)
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoRedirect']['filter'];
        }
        $conditions = $this->SeoRedirect->generateFilterConditions($filter);
        $this->set('seoRedirects', $this->paginate($conditions));
        $this->set('filter', $filter);
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function admin_view($id = null)
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo redirect'), $badFlash);
            $this->redirect(['action' => 'index']);
        }
        $this->set('seoRedirect', $this->SeoRedirect->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return void
     */
    public function admin_add()
    {
        if (!empty($this->data)) {
            $this->SeoRedirect->clear();
            if ($this->SeoRedirect->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo redirect has been saved'), $goodFlash);
                $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo redirect could not be saved. Please, try again.'), $badFlash);
            }
        }
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function admin_edit($id = null)
    {
        if (!$id && empty($this->data)) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo redirect'), $badFlash);
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->SeoRedirect->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo redirect has been saved'), $goodFlash);
                $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo redirect could not be saved. Please, try again.'), $badFlash);
            }
        }
        if (empty($this->data)) {
            $this->data = $this->SeoRedirect->read(null, $id);
        }
        $this->set('id', $id);
    }

    /**
     * @param int|null $id
     * @return CakeResponse|null
     */
    public function admin_delete($id = null)
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid id for seo redirect'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoRedirect->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo redirect deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo redirect was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }
}
