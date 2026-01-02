<?php

if (!function_exists('get_user_image')) {
    /**
     * إرجاع رابط الصورة الصحيح للمستخدم
     *
     * @param string|null $image
     * @return string
     */
    function get_user_image(?string $image): string
    {
        if (!$image) {
            return config('app.default_user_image', "https://static.vecteezy.com/system/resources/previews/011/209/565/non_2x/user-profile-avatar-free-vector.jpg"); // لا توجد صورة
        }

        // إذا الرابط موجود بالفعل كـ https أو http
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }
        // الرابط نسبي في قاعدة البيانات
        return asset('storage/' . $image);
    }
}


if (!function_exists('get_product_image')) {
    /**
     * إرجاع رابط الصورة الصحيح للمنتج
     *
     * @param string|null $image
     * @return string
     */
    function get_product_image(?string $image): string
    {
        // صورة افتراضية للمنتج
        if (!$image) {
            return config(
                'app.default_product_image',
                'https://static.vecteezy.com/system/resources/previews/002/248/763/non_2x/box-package-icon-free-vector.jpg'
            );
        }

        // لو الصورة رابط كامل
        if (str_starts_with($image, 'http://') || str_starts_with($image, 'https://')) {
            return $image;
        }

        // صورة مخزنة محليًا
        return asset('storage/' . ltrim($image, '/'));
    }
}


if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}


if (!function_exists('parsePHPLog')) {
    function parsePHPLog($content)
    {
        $lines = explode("\n", $content);
        $errors = [];
        $currentError = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Check for new error entry (PHP format)
            if (preg_match('/^\[(.*?)\] (PHP )?(.*?): (.*)$/', $line, $matches)) {
                if ($currentError) {
                    $errors[] = $currentError;
                }

                $currentError = [
                    'timestamp' => $matches[1] ?? '',
                    'level' => $matches[3] ?? '',
                    'message' => $matches[4] ?? '',
                    'file' => '',
                    'line' => '',
                    'stack' => ''
                ];
            }
            // Check for Laravel format
            elseif (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}) (.*?): (.*)$/', $line, $matches)) {
                if ($currentError) {
                    $errors[] = $currentError;
                }

                $currentError = [
                    'timestamp' => $matches[1] ?? '',
                    'level' => $matches[2] ?? '',
                    'message' => $matches[3] ?? '',
                    'file' => '',
                    'line' => '',
                    'stack' => ''
                ];
            }
            // Stack trace or file info
            elseif ($currentError) {
                if (str_contains($line, 'Stack trace:')) {
                    $currentError['stack'] = $line;
                } elseif (preg_match('/ in (.*?) on line (\d+)/', $line, $matches)) {
                    $currentError['file'] = $matches[1] ?? '';
                    $currentError['line'] = $matches[2] ?? '';
                } elseif (str_starts_with($line, '#') && !empty($currentError['stack'])) {
                    $currentError['stack'] .= "\n" . $line;
                }
            }
        }

        if ($currentError) {
            $errors[] = $currentError;
        }

        return $errors;
    }
}



if (!function_exists('module_icon')) {
    function module_icon(string $module): string
    {
        return \App\Helpers\PermissionHelper::getModuleIcon($module);
    }
}

if (!function_exists('module_display_name')) {
    function module_display_name(string $module): string
    {
        return \App\Helpers\PermissionHelper::getModuleDisplayName($module);
    }
}

if (!function_exists('permission_type')) {
    function permission_type(string $permissionName): string
    {
        return \App\Helpers\PermissionHelper::getPermissionType($permissionName);
    }
}

if (!function_exists('permission_type_label')) {
    function permission_type_label(string $permissionName): string
    {
        return \App\Helpers\PermissionHelper::getPermissionTypeLabel($permissionName);
    }
}

if (!function_exists('permission_badge_class')) {
    function permission_badge_class(string $permissionName): string
    {
        return \App\Helpers\PermissionHelper::getPermissionBadgeClass($permissionName);
    }
}


if (!function_exists('module_icon')) {
    function module_icon(string $module): string
    {
        return \App\Helpers\PermissionHelper::getModuleIcon($module);
    }
}

if (!function_exists('module_display_name')) {
    function module_display_name(string $module): string
    {
        return \App\Helpers\PermissionHelper::getModuleDisplayName($module);
    }
}

if (!function_exists('permission_type')) {
    function permission_type(string $permissionName): string
    {
        return \App\Helpers\PermissionHelper::getPermissionType($permissionName);
    }
}

if (!function_exists('permission_type_label')) {
    function permission_type_label(string $permissionName): string
    {
        return \App\Helpers\PermissionHelper::getPermissionTypeLabel($permissionName);
    }
}

if (!function_exists('permission_badge_class')) {
    function permission_badge_class(string $permissionName): string
    {
        return \App\Helpers\PermissionHelper::getPermissionBadgeClass($permissionName);
    }
}
