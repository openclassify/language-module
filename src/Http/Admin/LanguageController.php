<?php namespace Visiosoft\LanguageModule\Http\Admin;

use Anomaly\Streams\Platform\Http\Controller\AdminController;
use Visiosoft\LanguageModule\Helpers\DirectoryHelper;

class LanguageController extends AdminController
{
    public function index()
    {
        $modules = DirectoryHelper::getLanguages(DirectoryHelper::getVendorPath(), ['anomaly', 'visiosoft']);
        return $this->view->make('visiosoft.module.language::modules', ['modules' => $modules]);
    }

    public function edit()
    {
        $params = [
            'module' => request()->module,
            'language' => request()->language,
            'file' => request()->file
        ];

        if ($content = request()->file_content) {
            DirectoryHelper::setLanguageFileContent($params['module'], $params['language'], $params['file'], $content);
            return $this->redirect->back()->with('success', ['message' => "Content Updated"]);
        }

        $params['content'] = DirectoryHelper::getLanguageFileContent($params['module'], $params['language'], $params['file']);

        return $this->view->make('visiosoft.module.language::edit', $params);
    }


}