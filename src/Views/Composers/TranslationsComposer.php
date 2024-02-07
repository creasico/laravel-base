<?php

namespace Creasi\Base\Views\Composers;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class TranslationsComposer
{
    public function compose(View $view)
    {
        /** @var \Illuminate\Contracts\Translation\Loader */
        $loader = app('translation.loader');
        $messages = $this->loadMessages($loader, \resource_path('lang'));

        foreach ($loader->namespaces() as $namespace => $path) {
            foreach ($this->loadMessages($loader, $path, $namespace) as $locale => $vendorMessages) {
                $messages[$locale] = array_merge($messages[$locale], $vendorMessages);
            }
        }

        $view->with('translations', $messages);
    }

    /**
     * @return array<string, \Illuminate\Support\Collection<string, string>>
     */
    private function loadMessages(Loader $loader, string $path, ?string $namespace = null)
    {
        $messages = [];

        foreach (File::directories($path) as $dir) {
            $locale = \basename($dir);
            $trans = collect([]);

            foreach (File::files($dir) as $file) {
                $group = $file->getBasename('.php');
                $key = $namespace ? $namespace.'::'.$group : $group;

                $trans[$key] = $loader->load($locale, $group, $namespace);
            }

            $messages[$locale] = $trans->dot()->filter(fn ($i) => ! empty($i))->toArray();
        }

        return $messages;
    }
}
