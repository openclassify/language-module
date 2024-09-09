<?php

namespace Visiosoft\LanguageModule\Helpers;

use function Laravel\Prompts\select;

class DirectoryHelper
{
    public static function getVendorPath(): string
    {
        return base_path() . "/vendor";
    }

    public static function getLanguageFilePath(string $module, string $language, string $file): string
    {
        return self::getVendorPath() . "/" . $module . "/resources/lang/" . $language . "/" . $file;
    }

    public static function getDirectoryContents($directory): false|array
    {
        return array_diff(scandir($directory), array('.', '..'));
    }

    public static function getLanguageFileContent(string $module, string $language, string $file): false|string
    {
        return file_get_contents(self::getLanguageFilePath($module, $language, $file));
    }

    public static function setLanguageFileContent(string $module, string $language, string $file, string $content): false|int
    {
        return file_put_contents(self::getLanguageFilePath($module, $language, $file), $content);
    }

    public static function getLanguages(string $baseDirectory, array $directories): array
    {
        $result = [];

        foreach ($directories as $directory) {
            $directoryPath = $baseDirectory . '/' . $directory;
            if (is_dir($directoryPath)) {
                $addons = self::getDirectoryContents($directoryPath);

                foreach ($addons as $addon) {
                    $addonFullPath = $directoryPath . "/" . $addon;
                    $resourcesPath = $addonFullPath . '/resources';
                    if (is_dir($resourcesPath)) {
                        $langPath = $resourcesPath . '/lang';
                        $langs = self::getDirectoryContents($langPath);
                        $langsWithSubDirectories = [];
                        foreach ($langs as $lang) {
                            $langDirectories = self::getDirectoryContents($langPath . "/" . $lang);
                            $langsWithSubDirectories[$lang] = $langDirectories;
                        }
                        if (is_dir($langPath)) {
                            $result[] = [
                                'name' => $directory . "/" . $addon,
                                'lang_path' => $langPath,
                                'languages' => $langsWithSubDirectories
                            ];
                        }
                    }
                }
            }
        }
        return $result;
    }
}