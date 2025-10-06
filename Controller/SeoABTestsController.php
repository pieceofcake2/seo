<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoABTest $SeoABTest
 */
class SeoABTestsController extends SeoAppController
{
    public $name = 'SeoABTests';
    public $helpers = ['Time'];
    public $paginate = [
        'order' => 'SeoABTest.created DESC',
    ];

    /**
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        $this->set('slots', $this->SeoABTest->slots);
    }

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null): void
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoABTest']['filter'];
        }
        $conditions = $this->SeoABTest->generateFilterConditions($filter);
        $this->set('seoABTests', $this->paginate($conditions));
        $this->set('filter', $filter);
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function admin_view(?int $id = null): void
    {
        if (!$id) {
            $this->Session->setFlash(__('Invalid seo AB Test'));
            $this->redirect(['action' => 'index']);
        }
        $this->set('seoABTest', $this->SeoABTest->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return void
     * @throws Exception
     */
    public function admin_add(): void
    {
        if (!empty($this->data)) {
            $this->SeoABTest->clear();
            if ($this->SeoABTest->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo AB Test has been saved'), $goodFlash);
                $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo AB Test could not be saved. Please, try again.'), $badFlash);
            }
        }
    }

    /**
     * @param int|null $id
     * @return void
     * @throws Exception
     */
    public function admin_edit(?int $id = null): void
    {
        if (!$id && empty($this->data)) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo AB Test'), $badFlash);
            $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->SeoABTest->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo AB Test has been saved'), $goodFlash);
                $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo AB Test could not be saved. Please, try again.'), $badFlash);
            }
        }
        if (empty($this->data)) {
            $this->data = $this->SeoABTest->read(null, $id);
        }
        $this->set('id', $id);
    }

    /**
     * @param int|null $id
     * @return void
     */
    public function admin_delete(?int $id = null): void
    {
        if (!$id) {
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid id for seo AB Test'), $badFlash);
            $this->redirect(['action' => 'index']);
        }
        if ($this->SeoABTest->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo AB Test deleted'), $goodFlash);
            $this->redirect(['action' => 'index']);
        }
        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo AB Test was not deleted'), $badFlash);
        $this->redirect(['action' => 'index']);
    }
}
