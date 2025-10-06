<?php

App::uses('SeoAppController', 'Seo.Controller');

/**
 * @property SeoBlacklist $SeoBlacklist
 * @property Auth $Auth
 */
class SeoBlacklistsController extends SeoAppController
{
    public $name = 'SeoBlacklists';
    public $helpers = ['Time'];

    /**
     * @return void
     */
    public function beforeFilter()
    {
        parent::beforeFilter();
        if (isset($this->Auth)) {
            $this->Auth->allow('banned');
        }
    }

    /**
     * Banned action
     */
    public function banned()
    {
        $this->layout = 'banned';
    }

    /**
     * Admin actions
     *
     * @param string|null $filter
     * @return void
     */
    public function admin_index(?string $filter = null)
    {
        if (!empty($this->data)) {
            $filter = $this->data['Location']['filter'];
        }

        $conditions = $this->SeoBlacklist->generateFilterConditions($filter);
        $this->set('seoBlacklists', $this->paginate($conditions));
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
            $this->Session->setFlash(__('Invalid seo blacklist'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        $this->set('seoBlacklist', $this->SeoBlacklist->read(null, $id));
        $this->set('id', $id);
    }

    /**
     * @return CakeResponse|null|void
     */
    public function admin_add()
    {
        if (!empty($this->data)) {
            $this->SeoBlacklist->clear();
            if ($this->SeoBlacklist->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo blacklist has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo blacklist could not be saved. Please, try again.'), $badFlash);
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
            $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
            $this->Session->setFlash(__('Invalid seo blacklist'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }
        if (!empty($this->data)) {
            if ($this->SeoBlacklist->save($this->data)) {
                $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
                $this->Session->setFlash(__('The seo blacklist has been saved'), $goodFlash);

                return $this->redirect(['action' => 'index']);
            } else {
                $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
                $this->Session->setFlash(__('The seo blacklist could not be saved. Please, try again.'), $badFlash);
            }
        }
        if (empty($this->data)) {
            $this->data = $this->SeoBlacklist->read(null, $id);
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
            $this->Session->setFlash(__('Invalid id for seo blacklist'), $badFlash);

            return $this->redirect(['action' => 'index']);
        }

        if ($this->SeoBlacklist->delete($id)) {
            $goodFlash = $this->_getViewObject()->elementExists('goodFlash') ? 'goodFlash' : 'default';
            $this->Session->setFlash(__('Seo blacklist deleted'), $goodFlash);

            return $this->redirect(['action' => 'index']);
        }

        $badFlash = $this->_getViewObject()->elementExists('badFlash') ? 'badFlash' : 'default';
        $this->Session->setFlash(__('Seo blacklist was not deleted'), $badFlash);

        return $this->redirect(['action' => 'index']);
    }
}
