<?php

App::uses('SeoUtil', 'Seo.Lib');
App::uses('Component', 'Controller');

/**
 * @property SeoBlacklist $SeoBlacklist
 * @property SeoHoneypotVisit $SeoHoneypotVisit
 */
class BlackListComponent extends Component
{
    /**
     * CakePHP based URL to redirect the banned uesr
     */
    public $redirect = ['admin' => false, 'plugin' => 'seo', 'controller' => 'seo_blacklists', 'action' => 'banned'];

    /**
     * CakePHP based URL to the honeypot action setup in config
     */
    public $honeyPot = null;

    /**
     * Error log
     */
    public $errors = [];

    /**
     * Placeholder for the SeoBlacklist Model
     */
    public $SeoBlacklist = null;

    /**
     * Placeholder for the SeoHoneypotVisit Model
     */
    public $SeoHoneypotVisit = null;

    /**
     * Initialize the component, set the settings
     */
    public function initialize($controller, $settings = [])
    {
        $this->Controller = $controller;
        $this->_set($settings);
        $this->honeyPot = SeoUtil::getConfig('honeyPot');

        if (!$this->__isBanned()) {
            $this->__handleIfHoneyPot();
        }
    }

    /**
     * Handle the banned user, decide if banned,
     * if so, redirect the user.
     */
    public function __isBanned(): bool // phpcs:ignore CakePHP.NamingConventions.ValidFunctionName.PublicWithUnderscore
    {
        $this->loadModel('SeoBlacklist');

        if ($this->SeoBlacklist->isBanned()) {
            if ($this->Controller->here != Router::url($this->redirect)) {
                $this->Controller->redirect($this->redirect);
            }

            return true;
        }

        return false;
    }

    /**
     * Handle if honeypot action.
     *
     * @return void
     */
    public function __handleIfHoneyPot() // phpcs:ignore CakePHP.NamingConventions.ValidFunctionName.PublicWithUnderscore
    {
        if ($this->Controller->here === Router::url($this->honeyPot)) {
            $this->loadModel('SeoHoneypotVisit');
            $this->SeoHoneypotVisit->add();

            if ($this->SeoHoneypotVisit->isTriggered()) {
                $this->SeoBlacklist->addToBanned();

                $this->isBanned();
            } else {
                $this->Controller->redirect('/');
            }
        }
    }

    /**
     * Load a plugin model
     *
     * @param string|null modelname
     * @return void
     */
    private function loadModel(?string $model = null): void
    {
        if ($model && $this->$model == null) {
            $this->$model = ClassRegistry::init("Seo.$model");
        }
    }
}
