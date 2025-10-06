<?php

/**
 * @property SeoUrl $SeoUrl
 */
class SeoUrlsShell extends Shell
{
    public $uses = ['Seo.SeoUrl'];

    /**
     * @return void
     */
    public function main(): void
    {
        $this->out('SeoUrl Shell');
        $this->hr();
        $this->help();
    }

    /**
     * @return void
     */
    public function help(): void
    {
        $this->out(' cake seo_urls import                  Import from the source in config');
        $this->out(' cake seo_urls add <url> <priorty>     Add a url to use as levenshtien');
    }

    /**
     * @return void
     */
    public function import(): void
    {
        $this->out('Importing.');
        $count = $this->SeoUrl->import(null, true, true);
        $this->out();
        $this->out("Import finished. $count Imported.");
    }

    /**
     * @return void
     */
    public function add(): void
    {
        $url = array_shift($this->args);
        $priority = array_shift($this->args);
        if (!$url) {
            $this->errorAndExit('Url not set, please set a url.');
        }
        if (!$priority) {
            $this->errorAndExit("Priority not set, please set a priority.\n\n cake seo_urls add $url 1");
        }
        $save_data = [
            'url' => $url,
            'priority' => $priority,
        ];
        if ($this->SeoUrl->hasAny(['SeoUrl.url' => $url])) {
            $save_data['id'] = $this->SeoUrl->field('id', ['SeoUrl.url' => $url]);
        }
        $this->SeoUrl->clear();
        if ($this->SeoUrl->save($save_data)) {
            $this->out("$url $priority added.");
        } else {
            $this->out('Errors');
            print_r($this->SeoUrl->validationErrors);
            $this->out();
        }
    }

    /**
     * Private method to output the error and exit(1)
     *
     * @param string $message message to output
     * @return never
     */
    protected function errorAndExit($message)
    {
        $this->out("Error: $message");
        exit(1);
    }
}
