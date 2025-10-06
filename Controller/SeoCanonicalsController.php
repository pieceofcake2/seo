<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoCanonical $SeoCanonical
 */
class SeoCanonicalsController extends SeoAppController
{
    public $name = 'SeoCanonicals';

    public $helpers = ['Time'];

    /**
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null)
    {
        if (!empty($this->data)) {
            $filter = $this->data['SeoCanonical']['filter'];
        }
        $conditions = $this->SeoCanonical->generateFilterConditions($filter);
        $this->set('seoCanonicals', $this->paginate($conditions));
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
            $this->Session->setFlash(__('Invalid seo canonical'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        $this->set('seoCanonical', $this->SeoCanonical->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return CakeResponse|null|void
     * @throws Exception
     */
    public function admin_add()
    {
        if (!empty($this->data)) {
            $this->SeoCanonical->clear();

            if ($this->SeoCanonical->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo canonical has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo canonical could not be saved. Please, try again.'), $badFlash);
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
            $this->Session->setFlash(__('Invalid seo canonical'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->SeoCanonical->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo canonical has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo canonical could not be saved. Please, try again.'), $badFlash);
            }
        }

        if (empty($this->data)) {
            $this->data = $this->SeoCanonical->read(null, $id);
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
            $this->Session->setFlash(__('Invalid id for seo canonical'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoCanonical->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo canonical deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo canonical was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }
}
