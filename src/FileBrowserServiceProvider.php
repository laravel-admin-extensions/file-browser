<?php

namespace Encore\FileBrowser;

use Illuminate\Support\ServiceProvider;

class FileBrowserServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(FileBrowser $extension)
    {
        if (! FileBrowser::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'file-browser');
        }
    }
}