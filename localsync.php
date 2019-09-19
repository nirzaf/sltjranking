<?php
/**
 * @package LocalSync
 * @author Rajan V
 * @version 0.1-dev
 */

//Download ZIP
if(isset($_GET['downloadPlugin']) && $_GET['downloadPlugin']==1)
{
    $plugin_file = dirname(__FILE__)."/ls_temp/localsync.zip";

    if(is_file($plugin_file))
    {
    $file_name = basename($plugin_file);

    header("Content-Type: application/zip");
    header("Content-Disposition: attachment; filename=$file_name");
    header("Content-Length: " . filesize($plugin_file));

    readfile($plugin_file);
    
}
else
echo "Couldn't find the plugin. Please contact us at hello@localsync.io";
exit;
}

//All functions
if (function_exists('error_reporting')) {
 @error_reporting(0);
}
if (function_exists('ini_set')) {
@ini_set('memory_limit','512M');
}
if (function_exists('set_time_limit')) {
    set_time_limit(120);
}
 //@ini_set('always_populate_raw_post_data',1);
if (!function_exists('__')) {
    function __($name) {
        return $name;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters($this_name, $this_value, $ignorable = array()) {
        return $this_value;
    }
}

//WP common functions

if (!function_exists('trailingslashit')) {
    function trailingslashit($string) {
        return untrailingslashit($string) . '/';
    }
}

if (!function_exists('did_action')) {
    function did_action($string) {
        return 0;
    }
}

if (!function_exists('wp_die')) {
    function wp_die($string = '') {
        exit($string);
    }
}

if (!function_exists('untrailingslashit')) {
    function untrailingslashit($string) {
        return rtrim($string, '/\\');
    }
}

if (!function_exists('get_option')) {
    function get_option($option) {
        global $wpdb;
        $value = false;
        $row = $wpdb->get_row($wpdb->prepare("SELECT option_value FROM $wpdb->options WHERE option_name = %s LIMIT 1", $option));
        if (is_object($row) && isset($row->option_value)) {
            $value = $row->option_value;
        }
        return $value;
    }
}

if (!function_exists('get_option_wptc')) {
    function get_option_wptc($option) {
        global $wpdb;
        $value = false;
        $sql = $wpdb->prepare("SELECT value FROM ".$wpdb->base_prefix."wptc_options WHERE name = %s LIMIT 1", $option);
        $option_value = $wpdb->get_var($sql);
        if ($option_value) {
            return $option_value;
        }
        return $value;
    }
}

if (!function_exists('current_time')) {
    function current_time($type, $gmt = 0) {
        switch ($type) {
        case 'mysql':
            return ($gmt) ? gmdate('Y-m-d H:i:s') : gmdate('Y-m-d H:i:s', (time() + (get_option('gmt_offset') * HOUR_IN_SECONDS)));
        case 'timestamp':
            return ($gmt) ? time() : time() + (get_option('gmt_offset') * HOUR_IN_SECONDS);
        default:
            return ($gmt) ? date($type) : date($type, time() + (get_option('gmt_offset') * HOUR_IN_SECONDS));
        }
    }
}

if (!function_exists('set_url_scheme')) {
    function set_url_scheme($url, $scheme = null) {
        $orig_scheme = $scheme;

        if (!$scheme) {
            $scheme = is_ssl() ? 'https' : 'http';
        } elseif ($scheme === 'admin' || $scheme === 'login' || $scheme === 'login_post' || $scheme === 'rpc') {
            $scheme = is_ssl() || force_ssl_admin() ? 'https' : 'http';
        } elseif ($scheme !== 'http' && $scheme !== 'https' && $scheme !== 'relative') {
            $scheme = is_ssl() ? 'https' : 'http';
        }

        $url = trim($url);
        if (substr($url, 0, 2) === '//') {
            $url = 'http:' . $url;
        }

        if ('relative' == $scheme) {
            $url = ltrim(preg_replace('#^\w+://[^/]*#', '', $url));
            if ($url !== '' && $url[0] === '/') {
                $url = '/' . ltrim($url, "/ \t\n\r\0\x0B");
            }

        } else {
            $url = preg_replace('#^\w+://#', $scheme . '://', $url);
        }

        /**
         * Filter the resulting URL after setting the scheme.
         *
         * @since 3.4.0
         *
         * @param string $url         The complete URL including scheme and path.
         * @param string $scheme      Scheme applied to the URL. One of 'http', 'https', or 'relative'.
         * @param string $orig_scheme Scheme requested for the URL. One of 'http', 'https', 'login',
         *                            'login_post', 'admin', 'rpc', or 'relative'.
         */
        return $url;
    }
}

if (!function_exists('is_ssl')) {
    function is_ssl() {
        if (isset($_SERVER['HTTPS'])) {
            if ('on' == strtolower($_SERVER['HTTPS'])) {
                return true;
            }

            if ('1' == $_SERVER['HTTPS']) {
                return true;
            }

        } elseif (isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])) {
            return true;
        }
        return false;
    }
}

if (!function_exists('force_ssl_admin')) {
    function force_ssl_admin($force = null) {
        static $forced = false;

        if (!is_null($force)) {
            $old_forced = $forced;
            $forced = $force;
            return $old_forced;
        }

        return $forced;
    }
}

if (!function_exists('get_temp_dir')) {
    function get_temp_dir() {
        static $temp;
        if (defined('WP_TEMP_DIR')) {
            return trailingslashit(WP_TEMP_DIR);
        }

        if ($temp) {
            return trailingslashit($temp);
        }

        if (function_exists('sys_get_temp_dir')) {
            $temp = sys_get_temp_dir();
            if (@is_dir($temp) && wp_is_writable($temp)) {
                return trailingslashit($temp);
            }

        }

        $temp = ini_get('upload_tmp_dir');
        if (@is_dir($temp) && wp_is_writable($temp)) {
            return trailingslashit($temp);
        }

        $temp = WP_CONTENT_DIR . '/';
        if (is_dir($temp) && wp_is_writable($temp)) {
            return $temp;
        }

        $temp = '/tmp/';
        return $temp;
    }
}

if (!function_exists('wp_is_writable')) {
    function wp_is_writable($path) {
        if ('WIN' === strtoupper(substr(PHP_OS, 0, 3))) {
            return win_is_writable($path);
        } else {
            return @is_writable($path);
        }

    }
}

if (!function_exists('win_is_writable')) {
    function win_is_writable($path) {

        if ($path[strlen($path) - 1] == '/') // if it looks like a directory, check a random file within the directory
        {
            return win_is_writable($path . uniqid(mt_rand()) . '.tmp');
        } else if (is_dir($path)) // If it's a directory (and not a file) check a random file within the directory
        {
            return win_is_writable($path . '/' . uniqid(mt_rand()) . '.tmp');
        }

        // check tmp file for read/write capabilities
        $should_delete_tmp_file = !file_exists($path);
        $f = @fopen($path, 'a');
        if ($f === false) {
            return false;
        }

        fclose($f);
        if ($should_delete_tmp_file) {
            @unlink($path);
        }

        return true;
    }
}

if (!function_exists('mbstring_binary_safe_encoding')) {
    function mbstring_binary_safe_encoding($reset = false) {
        static $encodings = array();
        static $overloaded = null;

        if (is_null($overloaded)) {
            $overloaded = function_exists('mb_internal_encoding') && (ini_get('mbstring.func_overload') & 2);
        }

        if (false === $overloaded) {
            return;
        }

        if (!$reset) {
            $encoding = mb_internal_encoding();
            array_push($encodings, $encoding);
            mb_internal_encoding('ISO-8859-1');
        }

        if ($reset && $encodings) {
            $encoding = array_pop($encodings);
            mb_internal_encoding($encoding);
        }
    }
}

if (!function_exists('reset_mbstring_encoding')) {
    function reset_mbstring_encoding() {
        mbstring_binary_safe_encoding(true);
    }
}

if (!function_exists('wp_installing')) {
    function wp_installing($is_installing = null) {
        return false;
    }
}

if (!function_exists('update_option')) {
    function update_option($option, $value) {
        global $wpdb;

        $option = trim($option);
        if (empty($option)) {
            return false;
        }

        if (is_object($value)) {
            $value = clone $value;
        }

        $old_value = get_option($option);

        if ($value === $old_value) {
            return false;
        }

        $serialized_value = maybe_serialize($value);

        $result = $wpdb->update($wpdb->options, array('option_value' => $serialized_value), array('option_name' => $option));
        if (!$result) {
            return false;
        }

        return true;
    }
}

if (!function_exists('is_serialized')) {
    function is_serialized($data, $strict = true) {
        // if it isn't a string, it isn't serialized.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if ('N;' == $data) {
            return true;
        }
        if (strlen($data) < 4) {
            return false;
        }
        if (':' !== $data[1]) {
            return false;
        }
        if ($strict) {
            $lastc = substr($data, -1);
            if (';' !== $lastc && '}' !== $lastc) {
                return false;
            }
        } else {
            $semicolon = strpos($data, ';');
            $brace = strpos($data, '}');
            // Either ; or } must exist.
            if (false === $semicolon && false === $brace) {
                return false;
            }

            // But neither must be in the first X characters.
            if (false !== $semicolon && $semicolon < 3) {
                return false;
            }

            if (false !== $brace && $brace < 4) {
                return false;
            }

        }
        $token = $data[0];
        switch ($token) {
        case 's':
            if ($strict) {
                if ('"' !== substr($data, -2, 1)) {
                    return false;
                }
            } elseif (false === strpos($data, '"')) {
                return false;
            }
        // or else fall through
        case 'a':
        case 'O':
            return (bool) preg_match("/^{$token}:[0-9]+:/s", $data);
        case 'b':
        case 'i':
        case 'd':
            $end = $strict ? '$' : '';
            return (bool) preg_match("/^{$token}:[0-9.E-]+;$end/", $data);
        }
        return false;
    }
}

if (!function_exists('is_serialized_string')) {
    function is_serialized_string($data) {
        // if it isn't a string, it isn't a serialized string.
        if (!is_string($data)) {
            return false;
        }
        $data = trim($data);
        if (strlen($data) < 4) {
            return false;
        } elseif (':' !== $data[1]) {
            return false;
        } elseif (';' !== substr($data, -1)) {
            return false;
        } elseif ($data[0] !== 's') {
            return false;
        } elseif ('"' !== substr($data, -2, 1)) {
            return false;
        } else {
            return true;
        }
    }
}

if (!function_exists('maybe_serialize')) {
    function maybe_serialize($data) {
        if (is_array($data) || is_object($data)) {
            return serialize($data);
        }

        // Double serialization is required for backward compatibility.
        // See https://core.trac.wordpress.org/ticket/12930
        if (is_serialized($data, false)) {
            return serialize($data);
        }

        return $data;
    }
}

if (!function_exists('wp_generate_password')) {
    function wp_generate_password($length = 12, $special_chars = true, $extra_special_chars = false) {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        if ($special_chars) {
            $chars .= '!@#$%^&*()';
        }

        if ($extra_special_chars) {
            $chars .= '-_ []{}<>~`+=,.;:/?|';
        }

        $password = '';
        for ($i = 0; $i < $length; $i++) {
            $password .= substr($chars, wp_rand(0, strlen($chars) - 1), 1);
        }

        /**
         * Filter the randomly-generated password.
         *
         * @since 3.0.0
         *
         * @param string $password The generated password.
         */
        return apply_filters('random_password', $password);
    }
}

if (!function_exists('wp_rand')) {
    function wp_rand($min = 0, $max = 0) {
        global $rnd_value;

        // Reset $rnd_value after 14 uses
        // 32(md5) + 40(sha1) + 40(sha1) / 8 = 14 random numbers from $rnd_value
        if (strlen($rnd_value) < 8) {
            static $seed = '';

            $rnd_value = md5(uniqid(microtime() . mt_rand(), true) . $seed);
            $rnd_value .= sha1($rnd_value);
            $rnd_value .= sha1($rnd_value . $seed);
            $seed = md5($seed . $rnd_value);

        }

        // Take the first 8 digits for our value
        $value = substr($rnd_value, 0, 8);

        // Strip the first eight, leaving the remainder for the next call to wp_rand().
        $rnd_value = substr($rnd_value, 8);

        $value = abs(hexdec($value));

        // Some misconfigured 32bit environments (Entropy PHP, for example) truncate integers larger than PHP_INT_MAX to PHP_INT_MAX rather than overflowing them to floats.
        $max_random_number = 3000000000 === 2147483647 ? (float) "4294967295" : 4294967295; // 4294967295 = 0xffffffff

        // Reduce the value to be within the min - max range
        if ($max != 0) {
            $value = $min + ($max - $min + 1) * $value / ($max_random_number + 1);
        }

        return abs(intval($value));
    }
}

if (!function_exists('wp_unique_filename')) {
    function wp_unique_filename($dir, $filename, $unique_filename_callback = null) {
        // Sanitize the file name before we begin processing.
        $filename = sanitize_file_name($filename);

        // Separate the filename into a name and extension.
        $info = pathinfo($filename);
        $ext = !empty($info['extension']) ? '.' . $info['extension'] : '';
        $name = basename($filename, $ext);

        // Edge case: if file is named '.ext', treat as an empty name.
        if ($name === $ext) {
            $name = '';
        }

        /*
             * Increment the file number until we have a unique file to save in $dir.
             * Use callback if supplied.
        */
        if ($unique_filename_callback && is_callable($unique_filename_callback)) {
            $filename = call_user_func($unique_filename_callback, $dir, $name, $ext);
        } else {
            $number = '';

            // Change '.ext' to lower case.
            if ($ext && strtolower($ext) != $ext) {
                $ext2 = strtolower($ext);
                $filename2 = preg_replace('|' . preg_quote($ext) . '$|', $ext2, $filename);

                // Check for both lower and upper case extension or image sub-sizes may be overwritten.
                while (file_exists($dir . "/$filename") || file_exists($dir . "/$filename2")) {
                    $new_number = $number + 1;
                    $filename = str_replace("$number$ext", "$new_number$ext", $filename);
                    $filename2 = str_replace("$number$ext2", "$new_number$ext2", $filename2);
                    $number = $new_number;
                }
                return $filename2;
            }

            while (file_exists($dir . "/$filename")) {
                if ('' == "$number$ext") {
                    $filename = $filename . ++$number . $ext;
                } else {
                    $filename = str_replace("$number$ext", ++$number . $ext, $filename);
                }

            }
        }

        return $filename;
    }
}

if (!function_exists('sanitize_file_name')) {
    function sanitize_file_name($filename) {
        $filename_raw = $filename;
        $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", chr(0));
        /**
         * Filter the list of characters to remove from a filename.
         *
         * @since 2.8.0
         *
         * @param array  $special_chars Characters to remove.
         * @param string $filename_raw  Filename as it was passed into sanitize_file_name().
         */
        $special_chars = apply_filters('sanitize_file_name_chars', $special_chars, $filename_raw);
        $filename = preg_replace("#\x{00a0}#siu", ' ', $filename);
        $filename = str_replace($special_chars, '', $filename);
        $filename = str_replace(array('%20', '+'), '-', $filename);
        $filename = preg_replace('/[\r\n\t -]+/', '-', $filename);
        $filename = trim($filename, '.-_');

        // Split the filename into a base and extension[s]
        $parts = explode('.', $filename);

        // Return if only one extension
        if (count($parts) <= 2) {
            /**
             * Filter a sanitized filename string.
             *
             * @since 2.8.0
             *
             * @param string $filename     Sanitized filename.
             * @param string $filename_raw The filename prior to sanitization.
             */
            return apply_filters('sanitize_file_name', $filename, $filename_raw);
        }

        // Process multiple extensions
        $filename = array_shift($parts);
        $extension = array_pop($parts);
        $mimes = get_allowed_mime_types();

        /*
             * Loop over any intermediate extensions. Postfix them with a trailing underscore
             * if they are a 2 - 5 character long alpha string not in the extension whitelist.
        */
        foreach ((array) $parts as $part) {
            $filename .= '.' . $part;

            if (preg_match("/^[a-zA-Z]{2,5}\d?$/", $part)) {
                $allowed = false;
                foreach ($mimes as $ext_preg => $mime_match) {
                    $ext_preg = '!^(' . $ext_preg . ')$!i';
                    if (preg_match($ext_preg, $part)) {
                        $allowed = true;
                        break;
                    }
                }
                if (!$allowed) {
                    $filename .= '_';
                }

            }
        }
        $filename .= '.' . $extension;
        /** This filter is documented in wp-includes/formatting.php */
        return apply_filters('sanitize_file_name', $filename, $filename_raw);
    }
}

if (!function_exists('get_allowed_mime_types')) {
    function get_allowed_mime_types($user = null) {
        $t = wp_get_mime_types();

        unset($t['swf'], $t['exe']);

        return apply_filters('upload_mimes', $t, $user);
    }
}

if (!function_exists('wp_get_mime_types')) {
    function wp_get_mime_types() {
        return apply_filters('mime_types', array(
            // Image formats.
            'jpg|jpeg|jpe' => 'image/jpeg',
            'gif' => 'image/gif',
            'png' => 'image/png',
            'bmp' => 'image/bmp',
            'tif|tiff' => 'image/tiff',
            'ico' => 'image/x-icon',
            // Video formats.
            'asf|asx' => 'video/x-ms-asf',
            'wmv' => 'video/x-ms-wmv',
            'wmx' => 'video/x-ms-wmx',
            'wm' => 'video/x-ms-wm',
            'avi' => 'video/avi',
            'divx' => 'video/divx',
            'flv' => 'video/x-flv',
            'mov|qt' => 'video/quicktime',
            'mpeg|mpg|mpe' => 'video/mpeg',
            'mp4|m4v' => 'video/mp4',
            'ogv' => 'video/ogg',
            'webm' => 'video/webm',
            'mkv' => 'video/x-matroska',
            '3gp|3gpp' => 'video/3gpp', // Can also be audio
            '3g2|3gp2' => 'video/3gpp2', // Can also be audio
            // Text formats.
            'txt|asc|c|cc|h|srt' => 'text/plain',
            'csv' => 'text/csv',
            'tsv' => 'text/tab-separated-values',
            'ics' => 'text/calendar',
            'rtx' => 'text/richtext',
            'css' => 'text/css',
            'htm|html' => 'text/html',
            'vtt' => 'text/vtt',
            'dfxp' => 'application/ttaf+xml',
            // Audio formats.
            'mp3|m4a|m4b' => 'audio/mpeg',
            'ra|ram' => 'audio/x-realaudio',
            'wav' => 'audio/wav',
            'ogg|oga' => 'audio/ogg',
            'mid|midi' => 'audio/midi',
            'wma' => 'audio/x-ms-wma',
            'wax' => 'audio/x-ms-wax',
            'mka' => 'audio/x-matroska',
            // Misc application formats.
            'rtf' => 'application/rtf',
            'js' => 'application/javascript',
            'pdf' => 'application/pdf',
            'swf' => 'application/x-shockwave-flash',
            'class' => 'application/java',
            'tar' => 'application/x-tar',
            'zip' => 'application/zip',
            'gz|gzip' => 'application/x-gzip',
            'rar' => 'application/rar',
            '7z' => 'application/x-7z-compressed',
            'exe' => 'application/x-msdownload',
            'psd' => 'application/octet-stream',
            // MS Office formats.
            'doc' => 'application/msword',
            'pot|pps|ppt' => 'application/vnd.ms-powerpoint',
            'wri' => 'application/vnd.ms-write',
            'xla|xls|xlt|xlw' => 'application/vnd.ms-excel',
            'mdb' => 'application/vnd.ms-access',
            'mpp' => 'application/vnd.ms-project',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'docm' => 'application/vnd.ms-word.document.macroEnabled.12',
            'dotx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.template',
            'dotm' => 'application/vnd.ms-word.template.macroEnabled.12',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'xlsm' => 'application/vnd.ms-excel.sheet.macroEnabled.12',
            'xlsb' => 'application/vnd.ms-excel.sheet.binary.macroEnabled.12',
            'xltx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.template',
            'xltm' => 'application/vnd.ms-excel.template.macroEnabled.12',
            'xlam' => 'application/vnd.ms-excel.addin.macroEnabled.12',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'pptm' => 'application/vnd.ms-powerpoint.presentation.macroEnabled.12',
            'ppsx' => 'application/vnd.openxmlformats-officedocument.presentationml.slideshow',
            'ppsm' => 'application/vnd.ms-powerpoint.slideshow.macroEnabled.12',
            'potx' => 'application/vnd.openxmlformats-officedocument.presentationml.template',
            'potm' => 'application/vnd.ms-powerpoint.template.macroEnabled.12',
            'ppam' => 'application/vnd.ms-powerpoint.addin.macroEnabled.12',
            'sldx' => 'application/vnd.openxmlformats-officedocument.presentationml.slide',
            'sldm' => 'application/vnd.ms-powerpoint.slide.macroEnabled.12',
            'onetoc|onetoc2|onetmp|onepkg' => 'application/onenote',
            'oxps' => 'application/oxps',
            'xps' => 'application/vnd.ms-xpsdocument',
            // OpenOffice formats.
            'odt' => 'application/vnd.oasis.opendocument.text',
            'odp' => 'application/vnd.oasis.opendocument.presentation',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
            'odg' => 'application/vnd.oasis.opendocument.graphics',
            'odc' => 'application/vnd.oasis.opendocument.chart',
            'odb' => 'application/vnd.oasis.opendocument.database',
            'odf' => 'application/vnd.oasis.opendocument.formula',
            // WordPerfect formats.
            'wp|wpd' => 'application/wordperfect',
            // iWork formats.
            'key' => 'application/vnd.apple.keynote',
            'numbers' => 'application/vnd.apple.numbers',
            'pages' => 'application/vnd.apple.pages',
        ));
    }
}

if (!function_exists('network_admin_url')) {
    function network_admin_url( $path = '', $scheme = 'admin' ) {
        $network_admin_url = get_option_wptc('network_admin_url');
        if ($network_admin_url) {
            return $network_admin_url.$path;
        }
        return false;
    }
}

if (!function_exists('add_action')) {
    function add_action($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        return true;
    }
}

if (!function_exists('add_filter')) {
    function add_filter( $tag, $function_to_add, $priority = 10, $accepted_args = 1 ) {
        return true;
    }
}

if (!function_exists('current_user_can')) {
    function current_user_can( $capability ) {
        return true;
    }
}

if (!function_exists('is_admin')) {
    function is_admin() {
        return true;
    }
}

if (!function_exists('do_action')) {
    function do_action($tag, $arg = '') {
        return true;
    }
}

if (!function_exists('do_action')) {
    function do_action($tag, $arg = '') {
        return true;
    }
}

if (!function_exists('apply_filters')) {
    function apply_filters( $tag, $value ) {
        return true;
    }
}

if (!function_exists('wp_normalize_path')) {
    function wp_normalize_path( $path ) {
        $path = str_replace( '\\', '/', $path );
        $path = preg_replace( '|(?<=.)/+|', '/', $path );
        if ( ':' === substr( $path, 1, 1 ) ) {
            $path = ucfirst( $path );
        }
        return $path;
    }
}

if (!function_exists('home_url')) {
    function home_url() {
        return get_option_wptc('site_url_wptc');
    }
}

if (!function_exists('is_wp_error')) {
    function is_wp_error() {
    }
}

/**
 * WordPress DB Class
 *
 * Original code from {@link http://php.justinvincent.com Justin Vincent (justin@visunet.ie)}
 *
 * @package WordPress
 * @subpackage Database
 * @since 0.71
 */

/**
 * @since 0.71
 */
define( 'EZSQL_VERSION', 'WP1.25' );

/**
 * @since 0.71
 */
define( 'OBJECT', 'OBJECT' );
define( 'object', 'OBJECT' ); // Back compat.

/**
 * @since 2.5.0
 */
define( 'OBJECT_K', 'OBJECT_K' );

/**
 * @since 0.71
 */
define( 'ARRAY_A', 'ARRAY_A' );

/**
 * @since 0.71
 */
define( 'ARRAY_N', 'ARRAY_N' );

define( 'WP_DEBUG', TRUE );
define( 'WP_DEBUG_DISPLAY', TRUE );

define( 'WP_CONTENT_DIR', '/wp-contents' );


/**
 * WordPress Database Access Abstraction Object
 *
 * It is possible to replace this class with your own
 * by setting the $wpdb global variable in wp-content/db.php
 * file to your class. The wpdb class will still be included,
 * so you can extend it or simply use your own.
 *
 * @link https://codex.wordpress.org/Function_Reference/wpdb_Class
 *
 * @package WordPress
 * @subpackage Database
 * @since 0.71
 */
class wpdb {

    /**
     * Whether to show SQL/DB errors.
     *
     * Default behavior is to show errors if both WP_DEBUG and WP_DEBUG_DISPLAY
     * evaluated to true.
     *
     * @since 0.71
     * @access private
     * @var bool
     */
    var $show_errors = true;

    /**
     * Whether to suppress errors during the DB bootstrapping.
     *
     * @access private
     * @since 2.5.0
     * @var bool
     */
    var $suppress_errors = false;

    /**
     * The last error during query.
     *
     * @since 2.5.0
     * @var string
     */
    public $last_error = '';

    public $last_error_no = 0;

    /**
     * Amount of queries made
     *
     * @since 1.2.0
     * @access public
     * @var int
     */
    public $num_queries = 0;

    /**
     * Count of rows returned by previous query
     *
     * @since 0.71
     * @access public
     * @var int
     */
    public $num_rows = 0;

    /**
     * Count of affected rows by previous query
     *
     * @since 0.71
     * @access private
     * @var int
     */
    var $rows_affected = 0;

    /**
     * The ID generated for an AUTO_INCREMENT column by the previous query (usually INSERT).
     *
     * @since 0.71
     * @access public
     * @var int
     */
    public $insert_id = 0;

    /**
     * Last query made
     *
     * @since 0.71
     * @access private
     * @var array
     */
    var $last_query;

    /**
     * Results of the last query made
     *
     * @since 0.71
     * @access private
     * @var array|null
     */
    var $last_result;

    /**
     * MySQL result, which is either a resource or boolean.
     *
     * @since 0.71
     * @access protected
     * @var mixed
     */
    protected $result;

    /**
     * Cached column info, for sanity checking data before inserting
     *
     * @since 4.2.0
     * @access protected
     * @var array
     */
    protected $col_meta = array();

    /**
     * Calculated character sets on tables
     *
     * @since 4.2.0
     * @access protected
     * @var array
     */
    protected $table_charset = array();

    /**
     * Whether text fields in the current query need to be sanity checked.
     *
     * @since 4.2.0
     * @access protected
     * @var bool
     */
    protected $check_current_query = true;

    /**
     * Flag to ensure we don't run into recursion problems when checking the collation.
     *
     * @since 4.2.0
     * @access private
     * @see wpdb::check_safe_collation()
     * @var bool
     */
    private $checking_collation = false;

    /**
     * Saved info on the table column
     *
     * @since 0.71
     * @access protected
     * @var array
     */
    protected $col_info;

    /**
     * Saved queries that were executed
     *
     * @since 1.5.0
     * @access private
     * @var array
     */
    var $queries;

    /**
     * The number of times to retry reconnecting before dying.
     *
     * @since 3.9.0
     * @access protected
     * @see wpdb::check_connection()
     * @var int
     */
    protected $reconnect_retries = 5;

    /**
     * WordPress table prefix
     *
     * You can set this to have multiple WordPress installations
     * in a single database. The second reason is for possible
     * security precautions.
     *
     * @since 2.5.0
     * @access public
     * @var string
     */
    public $prefix = '';

    /**
     * WordPress base table prefix.
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
     public $base_prefix;

    /**
     * Whether the database queries are ready to start executing.
     *
     * @since 2.3.2
     * @access private
     * @var bool
     */
    var $ready = false;

    /**
     * Blog ID.
     *
     * @since 3.0.0
     * @access public
     * @var int
     */
    public $blogid = 0;

    /**
     * Site ID.
     *
     * @since 3.0.0
     * @access public
     * @var int
     */
    public $siteid = 0;

    /**
     * List of WordPress per-blog tables
     *
     * @since 2.5.0
     * @access private
     * @see wpdb::tables()
     * @var array
     */
    var $tables = array( 'posts', 'comments', 'links', 'options', 'postmeta',
        'terms', 'term_taxonomy', 'term_relationships', 'termmeta', 'commentmeta' );

    /**
     * List of deprecated WordPress tables
     *
     * categories, post2cat, and link2cat were deprecated in 2.3.0, db version 5539
     *
     * @since 2.9.0
     * @access private
     * @see wpdb::tables()
     * @var array
     */
    var $old_tables = array( 'categories', 'post2cat', 'link2cat' );

    /**
     * List of WordPress global tables
     *
     * @since 3.0.0
     * @access private
     * @see wpdb::tables()
     * @var array
     */
    var $global_tables = array( 'users', 'usermeta' );

    /**
     * List of Multisite global tables
     *
     * @since 3.0.0
     * @access private
     * @see wpdb::tables()
     * @var array
     */
    var $ms_global_tables = array( 'blogs', 'signups', 'site', 'sitemeta',
        'sitecategories', 'registration_log', 'blog_versions' );

    /**
     * WordPress Comments table
     *
     * @since 1.5.0
     * @access public
     * @var string
     */
    public $comments;

    /**
     * WordPress Comment Metadata table
     *
     * @since 2.9.0
     * @access public
     * @var string
     */
    public $commentmeta;

    /**
     * WordPress Links table
     *
     * @since 1.5.0
     * @access public
     * @var string
     */
    public $links;

    /**
     * WordPress Options table
     *
     * @since 1.5.0
     * @access public
     * @var string
     */
    public $options;

    /**
     * WordPress Post Metadata table
     *
     * @since 1.5.0
     * @access public
     * @var string
     */
    public $postmeta;

    /**
     * WordPress Posts table
     *
     * @since 1.5.0
     * @access public
     * @var string
     */
    public $posts;

    /**
     * WordPress Terms table
     *
     * @since 2.3.0
     * @access public
     * @var string
     */
    public $terms;

    /**
     * WordPress Term Relationships table
     *
     * @since 2.3.0
     * @access public
     * @var string
     */
    public $term_relationships;

    /**
     * WordPress Term Taxonomy table
     *
     * @since 2.3.0
     * @access public
     * @var string
     */
    public $term_taxonomy;

    /**
     * WordPress Term Meta table.
     *
     * @since 4.4.0
     * @access public
     * @var string
     */
    public $termmeta;

    //
    // Global and Multisite tables
    //

    /**
     * WordPress User Metadata table
     *
     * @since 2.3.0
     * @access public
     * @var string
     */
    public $usermeta;

    /**
     * WordPress Users table
     *
     * @since 1.5.0
     * @access public
     * @var string
     */
    public $users;

    /**
     * Multisite Blogs table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $blogs;

    /**
     * Multisite Blog Versions table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $blog_versions;

    /**
     * Multisite Registration Log table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $registration_log;

    /**
     * Multisite Signups table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $signups;

    /**
     * Multisite Sites table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $site;

    /**
     * Multisite Sitewide Terms table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $sitecategories;

    /**
     * Multisite Site Metadata table
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $sitemeta;

    /**
     * Format specifiers for DB columns. Columns not listed here default to %s. Initialized during WP load.
     *
     * Keys are column names, values are format types: 'ID' => '%d'
     *
     * @since 2.8.0
     * @see wpdb::prepare()
     * @see wpdb::insert()
     * @see wpdb::update()
     * @see wpdb::delete()
     * @see wp_set_wpdb_vars()
     * @access public
     * @var array
     */
    public $field_types = array();

    /**
     * Database table columns charset
     *
     * @since 2.2.0
     * @access public
     * @var string
     */
    public $charset;

    /**
     * Database table columns collate
     *
     * @since 2.2.0
     * @access public
     * @var string
     */
    public $collate;

    /**
     * Database Username
     *
     * @since 2.9.0
     * @access protected
     * @var string
     */
    protected $dbuser;

    /**
     * Database Password
     *
     * @since 3.1.0
     * @access protected
     * @var string
     */
    protected $dbpassword;

    /**
     * Database Name
     *
     * @since 3.1.0
     * @access protected
     * @var string
     */
    protected $dbname;

    /**
     * Database Host
     *
     * @since 3.1.0
     * @access protected
     * @var string
     */
    protected $dbhost;

    /**
     * Database Handle
     *
     * @since 0.71
     * @access protected
     * @var string
     */
    protected $dbh;

    /**
     * A textual description of the last query/get_row/get_var call
     *
     * @since 3.0.0
     * @access public
     * @var string
     */
    public $func_call;

    /**
     * Whether MySQL is used as the database engine.
     *
     * Set in WPDB::db_connect() to true, by default. This is used when checking
     * against the required MySQL version for WordPress. Normally, a replacement
     * database drop-in (db.php) will skip these checks, but setting this to true
     * will force the checks to occur.
     *
     * @since 3.3.0
     * @access public
     * @var bool
     */
    public $is_mysql = null;

    /**
     * A list of incompatible SQL modes.
     *
     * @since 3.9.0
     * @access protected
     * @var array
     */
    protected $incompatible_modes = array( 'NO_ZERO_DATE', 'ONLY_FULL_GROUP_BY',
        'STRICT_TRANS_TABLES', 'STRICT_ALL_TABLES', 'TRADITIONAL' );

    /**
     * Whether to use mysqli over mysql.
     *
     * @since 3.9.0
     * @access private
     * @var bool
     */
    private $use_mysqli = false;

    /**
     * Whether we've managed to successfully connect at some point
     *
     * @since 3.9.0
     * @access private
     * @var bool
     */
    private $has_connected = false;

    /**
     * Connects to the database server and selects a database
     *
     * PHP5 style constructor for compatibility with PHP5. Does
     * the actual setting up of the class properties and connection
     * to the database.
     *
     * @link https://core.trac.wordpress.org/ticket/3354
     * @since 2.0.8
     *
     * @global string $wp_version
     *
     * @param string $dbuser     MySQL database user
     * @param string $dbpassword MySQL database password
     * @param string $dbname     MySQL database name
     * @param string $dbhost     MySQL database host
     */
    public function __construct( $dbuser, $dbpassword, $dbname, $dbhost ) {
        register_shutdown_function( array( $this, '__destruct' ) );

        if ( WP_DEBUG && WP_DEBUG_DISPLAY )
            $this->show_errors();

        /* Use ext/mysqli if it exists and:
         *  - WP_USE_EXT_MYSQL is defined as false, or
         *  - We are a development version of WordPress, or
         *  - We are running PHP 5.5 or greater, or
         *  - ext/mysql is not loaded.
         */
        if ( function_exists( 'mysqli_connect' ) ) {
            if ( defined( 'WP_USE_EXT_MYSQL' ) ) {
                $this->use_mysqli = ! WP_USE_EXT_MYSQL;
            } elseif ( version_compare( phpversion(), '5.5', '>=' ) || ! function_exists( 'mysql_connect' ) ) {
                $this->use_mysqli = true;
            }
        }

        $this->dbuser = $dbuser;
        $this->dbpassword = $dbpassword;
        $this->dbname = $dbname;
        $this->dbhost = $dbhost;

        // wp-config.php creation will manually connect when ready.
        if ( defined( 'WP_SETUP_CONFIG' ) ) {
            return;
        }

        $this->db_connect();
    }

    /**
     * PHP5 style destructor and will run when database object is destroyed.
     *
     * @see wpdb::__construct()
     * @since 2.0.8
     * @return true
     */
    public function __destruct() {
        return true;
    }

    /**
     * Makes private properties readable for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name The private member to get, and optionally process
     * @return mixed The private member
     */
    public function __get( $name ) {
        if ( 'col_info' === $name )
            $this->load_col_info();

        return $this->$name;
    }

    /**
     * Makes private properties settable for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name  The private member to set
     * @param mixed  $value The value to set
     */
    public function __set( $name, $value ) {
        $protected_members = array(
            'col_meta',
            'table_charset',
            'check_current_query',
        );
        if (  in_array( $name, $protected_members, true ) ) {
            return;
        }
        $this->$name = $value;
    }

    /**
     * Makes private properties check-able for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name  The private member to check
     *
     * @return bool If the member is set or not
     */
    public function __isset( $name ) {
        return isset( $this->$name );
    }

    /**
     * Makes private properties un-settable for backward compatibility.
     *
     * @since 3.5.0
     *
     * @param string $name  The private member to unset
     */
    public function __unset( $name ) {
        unset( $this->$name );
    }

    /**
     * Set $this->charset and $this->collate
     *
     * @since 3.1.0
     */
    public function init_charset() {
        $charset = '';
        $collate = '';

        if ( function_exists('is_multisite') && is_multisite() ) {
            $charset = 'utf8';
            if ( defined( 'DB_COLLATE' ) && DB_COLLATE ) {
                $collate = DB_COLLATE;
            } else {
                $collate = 'utf8_general_ci';
            }
        } elseif ( defined( 'DB_COLLATE' ) ) {
            $collate = DB_COLLATE;
        }

        if ( defined( 'DB_CHARSET' ) ) {
            $charset = DB_CHARSET;
        }

        $charset_collate = $this->determine_charset( $charset, $collate );

        $this->charset = $charset_collate['charset'];
        $this->collate = $charset_collate['collate'];
    }

    /**
     * Determines the best charset and collation to use given a charset and collation.
     *
     * For example, when able, utf8mb4 should be used instead of utf8.
     *
     * @since 4.6.0
     * @access public
     *
     * @param string $charset The character set to check.
     * @param string $collate The collation to check.
     * @return array The most appropriate character set and collation to use.
     */
    public function determine_charset( $charset, $collate ) {
        if ( ( $this->use_mysqli && ! ( $this->dbh instanceof mysqli ) ) || empty( $this->dbh ) ) {
            return compact( 'charset', 'collate' );
        }

        if ( 'utf8' === $charset && $this->has_cap( 'utf8mb4' ) ) {
            $charset = 'utf8mb4';
        }

        if ( 'utf8mb4' === $charset && ! $this->has_cap( 'utf8mb4' ) ) {
            $charset = 'utf8';
            $collate = str_replace( 'utf8mb4_', 'utf8_', $collate );
        }

        if ( 'utf8mb4' === $charset ) {
            // _general_ is outdated, so we can upgrade it to _unicode_, instead.
            if ( ! $collate || 'utf8_general_ci' === $collate ) {
                $collate = 'utf8mb4_unicode_ci';
            } else {
                $collate = str_replace( 'utf8_', 'utf8mb4_', $collate );
            }
        }

        // _unicode_520_ is a better collation, we should use that when it's available.
        if ( $this->has_cap( 'utf8mb4_520' ) && 'utf8mb4_unicode_ci' === $collate ) {
            $collate = 'utf8mb4_unicode_520_ci';
        }

        return compact( 'charset', 'collate' );
    }

    /**
     * Sets the connection's character set.
     *
     * @since 3.1.0
     *
     * @param resource $dbh     The resource given by mysql_connect
     * @param string   $charset Optional. The character set. Default null.
     * @param string   $collate Optional. The collation. Default null.
     */
    public function set_charset( $dbh, $charset = null, $collate = null ) {
        if ( ! isset( $charset ) )
            $charset = $this->charset;
        if ( ! isset( $collate ) )
            $collate = $this->collate;
        if ( $this->has_cap( 'collation' ) && ! empty( $charset ) ) {
            $set_charset_succeeded = true;

            if ( $this->use_mysqli ) {
                if ( function_exists( 'mysqli_set_charset' ) && $this->has_cap( 'set_charset' ) ) {
                    $set_charset_succeeded = mysqli_set_charset( $dbh, $charset );
                }

                if ( $set_charset_succeeded ) {
                    $query = $this->prepare( 'SET NAMES %s', $charset );
                    if ( ! empty( $collate ) )
                        $query .= $this->prepare( ' COLLATE %s', $collate );
                    mysqli_query( $dbh, $query );
                }
            } else {
                if ( function_exists( 'mysql_set_charset' ) && $this->has_cap( 'set_charset' ) ) {
                    $set_charset_succeeded = mysql_set_charset( $charset, $dbh );
                }
                if ( $set_charset_succeeded ) {
                    $query = $this->prepare( 'SET NAMES %s', $charset );
                    if ( ! empty( $collate ) )
                        $query .= $this->prepare( ' COLLATE %s', $collate );
                    mysql_query( $query, $dbh );
                }
            }
        }
    }

    /**
     * Change the current SQL mode, and ensure its WordPress compatibility.
     *
     * If no modes are passed, it will ensure the current MySQL server
     * modes are compatible.
     *
     * @since 3.9.0
     *
     * @param array $modes Optional. A list of SQL modes to set.
     */
    public function set_sql_mode( $modes = array() ) {
        if ( empty( $modes ) ) {
            if ( $this->use_mysqli ) {
                $res = mysqli_query( $this->dbh, 'SELECT @@SESSION.sql_mode' );
            } else {
                $res = mysql_query( 'SELECT @@SESSION.sql_mode', $this->dbh );
            }

            if ( empty( $res ) ) {
                return;
            }

            if ( $this->use_mysqli ) {
                $modes_array = mysqli_fetch_array( $res );
                if ( empty( $modes_array[0] ) ) {
                    return;
                }
                $modes_str = $modes_array[0];
            } else {
                $modes_str = mysql_result( $res, 0 );
            }

            if ( empty( $modes_str ) ) {
                return;
            }

            $modes = explode( ',', $modes_str );
        }

        $modes = array_change_key_case( $modes, CASE_UPPER );

        /**
         * Filters the list of incompatible SQL modes to exclude.
         *
         * @since 3.9.0
         *
         * @param array $incompatible_modes An array of incompatible modes.
         */
        $incompatible_modes = (array) apply_filters( 'incompatible_sql_modes', $this->incompatible_modes );

        foreach ( $modes as $i => $mode ) {
            if ( in_array( $mode, $incompatible_modes ) ) {
                unset( $modes[ $i ] );
            }
        }

        $modes_str = implode( ',', $modes );

        if ( $this->use_mysqli ) {
            mysqli_query( $this->dbh, "SET SESSION sql_mode='$modes_str'" );
        } else {
            mysql_query( "SET SESSION sql_mode='$modes_str'", $this->dbh );
        }
    }

    /**
     * Sets the table prefix for the WordPress tables.
     *
     * @since 2.5.0
     *
     * @param string $prefix          Alphanumeric name for the new prefix.
     * @param bool   $set_table_names Optional. Whether the table names, e.g. wpdb::$posts, should be updated or not.
     * @return string|WP_Error Old prefix or WP_Error on error
     */
    public function set_prefix( $prefix, $set_table_names = true ) {

        if ( preg_match( '|[^a-z0-9_]|i', $prefix ) )
            return new WP_Error('invalid_db_prefix', 'Invalid database prefix' );

        $old_prefix = is_multisite() ? '' : $prefix;

        if ( isset( $this->base_prefix ) )
            $old_prefix = $this->base_prefix;

        $this->base_prefix = $prefix;

        if ( $set_table_names ) {
            foreach ( $this->tables( 'global' ) as $table => $prefixed_table )
                $this->$table = $prefixed_table;

            if ( is_multisite() && empty( $this->blogid ) )
                return $old_prefix;

            $this->prefix = $this->get_blog_prefix();

            foreach ( $this->tables( 'blog' ) as $table => $prefixed_table )
                $this->$table = $prefixed_table;

            foreach ( $this->tables( 'old' ) as $table => $prefixed_table )
                $this->$table = $prefixed_table;
        }
        return $old_prefix;
    }

    /**
     * Sets blog id.
     *
     * @since 3.0.0
     * @access public
     *
     * @param int $blog_id
     * @param int $site_id Optional.
     * @return int previous blog id
     */
    public function set_blog_id( $blog_id, $site_id = 0 ) {
        if ( ! empty( $site_id ) )
            $this->siteid = $site_id;

        $old_blog_id  = $this->blogid;
        $this->blogid = $blog_id;

        $this->prefix = $this->get_blog_prefix();

        foreach ( $this->tables( 'blog' ) as $table => $prefixed_table )
            $this->$table = $prefixed_table;

        foreach ( $this->tables( 'old' ) as $table => $prefixed_table )
            $this->$table = $prefixed_table;

        return $old_blog_id;
    }

    /**
     * Gets blog prefix.
     *
     * @since 3.0.0
     * @param int $blog_id Optional.
     * @return string Blog prefix.
     */
    public function get_blog_prefix( $blog_id = null ) {
        if ( is_multisite() ) {
            if ( null === $blog_id )
                $blog_id = $this->blogid;
            $blog_id = (int) $blog_id;
            if ( defined( 'MULTISITE' ) && ( 0 == $blog_id || 1 == $blog_id ) )
                return $this->base_prefix;
            else
                return $this->base_prefix . $blog_id . '_';
        } else {
            return $this->base_prefix;
        }
    }

    /**
     * Returns an array of WordPress tables.
     *
     * Also allows for the CUSTOM_USER_TABLE and CUSTOM_USER_META_TABLE to
     * override the WordPress users and usermeta tables that would otherwise
     * be determined by the prefix.
     *
     * The scope argument can take one of the following:
     *
     * 'all' - returns 'all' and 'global' tables. No old tables are returned.
     * 'blog' - returns the blog-level tables for the queried blog.
     * 'global' - returns the global tables for the installation, returning multisite tables only if running multisite.
     * 'ms_global' - returns the multisite global tables, regardless if current installation is multisite.
     * 'old' - returns tables which are deprecated.
     *
     * @since 3.0.0
     * @uses wpdb::$tables
     * @uses wpdb::$old_tables
     * @uses wpdb::$global_tables
     * @uses wpdb::$ms_global_tables
     *
     * @param string $scope   Optional. Can be all, global, ms_global, blog, or old tables. Defaults to all.
     * @param bool   $prefix  Optional. Whether to include table prefixes. Default true. If blog
     *                        prefix is requested, then the custom users and usermeta tables will be mapped.
     * @param int    $blog_id Optional. The blog_id to prefix. Defaults to wpdb::$blogid. Used only when prefix is requested.
     * @return array Table names. When a prefix is requested, the key is the unprefixed table name.
     */
    public function tables( $scope = 'all', $prefix = true, $blog_id = 0 ) {
        switch ( $scope ) {
            case 'all' :
                $tables = array_merge( $this->global_tables, $this->tables );
                if ( is_multisite() )
                    $tables = array_merge( $tables, $this->ms_global_tables );
                break;
            case 'blog' :
                $tables = $this->tables;
                break;
            case 'global' :
                $tables = $this->global_tables;
                if ( is_multisite() )
                    $tables = array_merge( $tables, $this->ms_global_tables );
                break;
            case 'ms_global' :
                $tables = $this->ms_global_tables;
                break;
            case 'old' :
                $tables = $this->old_tables;
                break;
            default :
                return array();
        }

        if ( $prefix ) {
            if ( ! $blog_id )
                $blog_id = $this->blogid;
            $blog_prefix = $this->get_blog_prefix( $blog_id );
            $base_prefix = $this->base_prefix;
            $global_tables = array_merge( $this->global_tables, $this->ms_global_tables );
            foreach ( $tables as $k => $table ) {
                if ( in_array( $table, $global_tables ) )
                    $tables[ $table ] = $base_prefix . $table;
                else
                    $tables[ $table ] = $blog_prefix . $table;
                unset( $tables[ $k ] );
            }

            if ( isset( $tables['users'] ) && defined( 'CUSTOM_USER_TABLE' ) )
                $tables['users'] = CUSTOM_USER_TABLE;

            if ( isset( $tables['usermeta'] ) && defined( 'CUSTOM_USER_META_TABLE' ) )
                $tables['usermeta'] = CUSTOM_USER_META_TABLE;
        }

        return $tables;
    }

    /**
     * Selects a database using the current database connection.
     *
     * The database name will be changed based on the current database
     * connection. On failure, the execution will bail and display an DB error.
     *
     * @since 0.71
     *
     * @param string        $db  MySQL database name
     * @param resource|null $dbh Optional link identifier.
     */
    public function select( $db, $dbh = null ) {
        if ( is_null($dbh) )
            $dbh = $this->dbh;

        if ( $this->use_mysqli ) {
            $success = mysqli_select_db( $dbh, $db );
        } else {
            $success = mysql_select_db( $db, $dbh );
        }
        if ( ! $success ) {
            $this->ready = false;
            if ( ! did_action( 'template_redirect' ) ) {
//              wp_load_translations_early();

                $message = '<h1>' . __( 'Can&#8217;t select database' ) . "</h1>\n";

                $message .= '<p>' . sprintf(
                    /* translators: %s: database name */
                    __( 'We were able to connect to the database server (which means your username and password is okay) but not able to select the %s database.' ),
                    '<code>' . htmlspecialchars( $db, ENT_QUOTES ) . '</code>'
                ) . "</p>\n";

                $message .= "<ul>\n";
                $message .= '<li>' . __( 'Are you sure it exists?' ) . "</li>\n";

                $message .= '<li>' . sprintf(
                    /* translators: 1: database user, 2: database name */
                    __( 'Does the user %1$s have permission to use the %2$s database?' ),
                    '<code>' . htmlspecialchars( $this->dbuser, ENT_QUOTES )  . '</code>',
                    '<code>' . htmlspecialchars( $db, ENT_QUOTES ) . '</code>'
                ) . "</li>\n";

                $message .= '<li>' . sprintf(
                    /* translators: %s: database name */
                    __( 'On some systems the name of your database is prefixed with your username, so it would be like <code>username_%1$s</code>. Could that be the problem?' ),
                    htmlspecialchars( $db, ENT_QUOTES )
                ). "</li>\n";

                $message .= "</ul>\n";

                $message .= '<p>' . sprintf(
                    /* translators: %s: support forums URL */
                    __( 'If you don&#8217;t know how to set up a database you should <strong>contact your host</strong>. If all else fails you may find help at the <a href="%s">WordPress Support Forums</a>.' ),
                    __( 'https://wordpress.org/support/' )
                ) . "</p>\n";

                $this->bail( $message, 'db_select_fail' );
            }
        }
    }

    /**
     * Do not use, deprecated.
     *
     * Use esc_sql() or wpdb::prepare() instead.
     *
     * @since 2.8.0
     * @deprecated 3.6.0 Use wpdb::prepare()
     * @see wpdb::prepare
     * @see esc_sql()
     * @access private
     *
     * @param string $string
     * @return string
     */
    function _weak_escape( $string ) {
        if ( func_num_args() === 1 && function_exists( '_deprecated_function' ) )
            _deprecated_function( __METHOD__, '3.6.0', 'wpdb::prepare() or esc_sql()' );
        return addslashes( $string );
    }

    /**
     * Real escape, using mysqli_real_escape_string() or mysql_real_escape_string()
     *
     * @see mysqli_real_escape_string()
     * @see mysql_real_escape_string()
     * @since 2.8.0
     * @access private
     *
     * @param  string $string to escape
     * @return string escaped
     */
    function _real_escape( $string ) {
        if ( $this->dbh ) {
            if ( $this->use_mysqli ) {
                return mysqli_real_escape_string( $this->dbh, $string );
            } else {
                return mysql_real_escape_string( $string, $this->dbh );
            }
        }

        $class = get_class( $this );
        if ( function_exists( '__' ) ) {
            /* translators: %s: database access abstraction class, usually wpdb or a class extending wpdb */
            _doing_it_wrong( $class, sprintf( __( '%s must set a database connection for use with escaping.' ), $class ), '3.6.0' );
        } else {
            _doing_it_wrong( $class, sprintf( '%s must set a database connection for use with escaping.', $class ), '3.6.0' );
        }
        return addslashes( $string );
    }

    /**
     * Escape data. Works on arrays.
     *
     * @uses wpdb::_real_escape()
     * @since  2.8.0
     * @access public
     *
     * @param  string|array $data
     * @return string|array escaped
     */
    public function _escape( $data ) {
        if ( is_array( $data ) ) {
            foreach ( $data as $k => $v ) {
                if ( is_array( $v ) ) {
                    $data[$k] = $this->_escape( $v );
                } else {
                    $data[$k] = $this->_real_escape( $v );
                }
            }
        } else {
            $data = $this->_real_escape( $data );
        }

        return $data;
    }

    /**
     * Do not use, deprecated.
     *
     * Use esc_sql() or wpdb::prepare() instead.
     *
     * @since 0.71
     * @deprecated 3.6.0 Use wpdb::prepare()
     * @see wpdb::prepare()
     * @see esc_sql()
     *
     * @param mixed $data
     * @return mixed
     */
    public function escape( $data ) {
        if ( func_num_args() === 1 && function_exists( '_deprecated_function' ) )
            _deprecated_function( __METHOD__, '3.6.0', 'wpdb::prepare() or esc_sql()' );
        if ( is_array( $data ) ) {
            foreach ( $data as $k => $v ) {
                if ( is_array( $v ) )
                    $data[$k] = $this->escape( $v, 'recursive' );
                else
                    $data[$k] = $this->_weak_escape( $v, 'internal' );
            }
        } else {
            $data = $this->_weak_escape( $data, 'internal' );
        }

        return $data;
    }

    /**
     * Escapes content by reference for insertion into the database, for security
     *
     * @uses wpdb::_real_escape()
     *
     * @since 2.3.0
     *
     * @param string $string to escape
     */
    public function escape_by_ref( &$string ) {
        if ( ! is_float( $string ) )
            $string = $this->_real_escape( $string );
    }

    /**
     * Prepares a SQL query for safe execution. Uses sprintf()-like syntax.
     *
     * The following directives can be used in the query format string:
     *   %d (integer)
     *   %f (float)
     *   %s (string)
     *   %% (literal percentage sign - no argument needed)
     *
     * All of %d, %f, and %s are to be left unquoted in the query string and they need an argument passed for them.
     * Literals (%) as parts of the query must be properly written as %%.
     *
     * This function only supports a small subset of the sprintf syntax; it only supports %d (integer), %f (float), and %s (string).
     * Does not support sign, padding, alignment, width or precision specifiers.
     * Does not support argument numbering/swapping.
     *
     * May be called like {@link https://secure.php.net/sprintf sprintf()} or like {@link https://secure.php.net/vsprintf vsprintf()}.
     *
     * Both %d and %s should be left unquoted in the query string.
     *
     *     $wpdb->prepare( "SELECT * FROM `table` WHERE `column` = %s AND `field` = %d", 'foo', 1337 );
     *     $wpdb->prepare( "SELECT DATE_FORMAT(`field`, '%%c') FROM `table` WHERE `column` = %s", 'foo' );
     *
     * @link https://secure.php.net/sprintf Description of syntax.
     * @since 2.3.0
     *
     * @param string      $query    Query statement with sprintf()-like placeholders
     * @param array|mixed $args     The array of variables to substitute into the query's placeholders if being called like
     *                              {@link https://secure.php.net/vsprintf vsprintf()}, or the first variable to substitute into the query's placeholders if
     *                              being called like {@link https://secure.php.net/sprintf sprintf()}.
     * @param mixed       $args,... further variables to substitute into the query's placeholders if being called like
     *                              {@link https://secure.php.net/sprintf sprintf()}.
     * @return string|void Sanitized query string, if there is a query to prepare.
     */
    public function prepare( $query, $args ) {
        if ( is_null( $query ) )
            return;

        // This is not meant to be foolproof -- but it will catch obviously incorrect usage.
        if ( strpos( $query, '%' ) === false ) {
            _doing_it_wrong( 'wpdb::prepare', sprintf( __( 'The query argument of %s must have a placeholder.' ), 'wpdb::prepare()' ), '3.9.0' );
        }

        $args = func_get_args();
        array_shift( $args );
        // If args were passed as an array (as in vsprintf), move them up
        if ( isset( $args[0] ) && is_array($args[0]) )
            $args = $args[0];
        $query = str_replace( "'%s'", '%s', $query ); // in case someone mistakenly already singlequoted it
        $query = str_replace( '"%s"', '%s', $query ); // doublequote unquoting
        $query = preg_replace( '|(?<!%)%f|' , '%F', $query ); // Force floats to be locale unaware
        $query = preg_replace( '|(?<!%)%s|', "'%s'", $query ); // quote the strings, avoiding escaped strings like %%s
        array_walk( $args, array( $this, 'escape_by_ref' ) );
        return @vsprintf( $query, $args );
    }

    /**
     * First half of escaping for LIKE special characters % and _ before preparing for MySQL.
     *
     * Use this only before wpdb::prepare() or esc_sql().  Reversing the order is very bad for security.
     *
     * Example Prepared Statement:
     *
     *     $wild = '%';
     *     $find = 'only 43% of planets';
     *     $like = $wild . $wpdb->esc_like( $find ) . $wild;
     *     $sql  = $wpdb->prepare( "SELECT * FROM $wpdb->posts WHERE post_content LIKE '%s'", $like );
     *
     * Example Escape Chain:
     *
     *     $sql  = esc_sql( $wpdb->esc_like( $input ) );
     *
     * @since 4.0.0
     * @access public
     *
     * @param string $text The raw text to be escaped. The input typed by the user should have no
     *                     extra or deleted slashes.
     * @return string Text in the form of a LIKE phrase. The output is not SQL safe. Call $wpdb::prepare()
     *                or real_escape next.
     */
    public function esc_like( $text ) {
        return addcslashes( $text, '_%\\' );
    }

    /**
     * Print SQL/DB error.
     *
     * @since 0.71
     * @global array $EZSQL_ERROR Stores error information of query and error string
     *
     * @param string $str The error to display
     * @return false|void False if the showing of errors is disabled.
     */
    public function print_error( $str = '' ) {
        global $EZSQL_ERROR;



        if ( !$str ) {
            if ( $this->use_mysqli ) {

                $str = mysqli_error( $this->dbh );

            } else {
                $str = mysql_error( $this->dbh );
            }
        }


        $EZSQL_ERROR[] = array( 'query' => $this->last_query, 'error_str' => $str );

        if ( $this->suppress_errors )
            return false;

//      wp_load_translations_early();

        if ( $caller = $this->get_caller() ) {
            /* translators: 1: Database error message, 2: SQL query, 3: Name of the calling function */
            $error_str = sprintf( __( 'WordPress database error %1$s for query %2$s made by %3$s' ), $str, $this->last_query, $caller );
        } else {
            /* translators: 1: Database error message, 2: SQL query */
            $error_str = sprintf( __( 'WordPress database error %1$s for query %2$s' ), $str, $this->last_query );
        }

        error_log( $error_str );

        // Are we showing errors?
        if ( ! $this->show_errors )
            return false;

        // If there is an error then take note of it
        if ( FALSE ) {
            $msg = sprintf(
                "%s [%s]\n%s\n",
                __( 'WordPress database error:' ),
                $str,
                $this->last_query
            );

            if ( defined( 'ERRORLOGFILE' ) ) {
                error_log( $msg, 3, ERRORLOGFILE );
            }
            if ( defined( 'DIEONDBERROR' ) ) {
                wp_die( $msg );
            }
        } else {
            $str   = htmlspecialchars( $str, ENT_QUOTES );
            $query = htmlspecialchars( $this->last_query, ENT_QUOTES );

            printf(
                '<div id="error"><p class="wpdberror"><strong>%s</strong> [%s]<br /><code>%s</code></p></div>',
                __( 'WordPress database error:' ),
                $str,
                $query
            );
        }
    }

    /**
     * Enables showing of database errors.
     *
     * This function should be used only to enable showing of errors.
     * wpdb::hide_errors() should be used instead for hiding of errors. However,
     * this function can be used to enable and disable showing of database
     * errors.
     *
     * @since 0.71
     * @see wpdb::hide_errors()
     *
     * @param bool $show Whether to show or hide errors
     * @return bool Old value for showing errors.
     */
    public function show_errors( $show = true ) {
        $errors = $this->show_errors;
        $this->show_errors = $show;
        return $errors;
    }

    /**
     * Disables showing of database errors.
     *
     * By default database errors are not shown.
     *
     * @since 0.71
     * @see wpdb::show_errors()
     *
     * @return bool Whether showing of errors was active
     */
    public function hide_errors() {
        $show = $this->show_errors;
        $this->show_errors = false;
        return $show;
    }

    /**
     * Whether to suppress database errors.
     *
     * By default database errors are suppressed, with a simple
     * call to this function they can be enabled.
     *
     * @since 2.5.0
     * @see wpdb::hide_errors()
     * @param bool $suppress Optional. New value. Defaults to true.
     * @return bool Old value
     */
    public function suppress_errors( $suppress = true ) {
        $errors = $this->suppress_errors;
        $this->suppress_errors = (bool) $suppress;
        return $errors;
    }

    /**
     * Kill cached query results.
     *
     * @since 0.71
     */
    public function flush() {
        $this->last_result = array();
        $this->col_info    = null;
        $this->last_query  = null;
        $this->rows_affected = $this->num_rows = 0;
        $this->last_error  = '';

        if ( $this->use_mysqli && $this->result instanceof mysqli_result ) {
            mysqli_free_result( $this->result );
            $this->result = null;

            // Sanity check before using the handle
            if ( empty( $this->dbh ) || !( $this->dbh instanceof mysqli ) ) {
                return;
            }

            // Clear out any results from a multi-query
            while ( mysqli_more_results( $this->dbh ) ) {
                mysqli_next_result( $this->dbh );
            }
        } elseif ( is_resource( $this->result ) ) {
            mysql_free_result( $this->result );
        }
    }

    /**
     * Connect to and select database.
     *
     * If $allow_bail is false, the lack of database connection will need
     * to be handled manually.
     *
     * @since 3.0.0
     * @since 3.9.0 $allow_bail parameter added.
     *
     * @param bool $allow_bail Optional. Allows the function to bail. Default true.
     * @return bool True with a successful connection, false on failure.
     */
    public function db_connect( $allow_bail = true ) {
        $this->is_mysql = true;

        /*
         * Deprecated in 3.9+ when using MySQLi. No equivalent
         * $new_link parameter exists for mysqli_* functions.
         */
        $new_link = defined( 'MYSQL_NEW_LINK' ) ? MYSQL_NEW_LINK : true;
        $client_flags = defined( 'MYSQL_CLIENT_FLAGS' ) ? MYSQL_CLIENT_FLAGS : 0;

        if ( $this->use_mysqli ) {
            $this->dbh = mysqli_init();

            // mysqli_real_connect doesn't support the host param including a port or socket
            // like mysql_connect does. This duplicates how mysql_connect detects a port and/or socket file.
            $port = null;
            $socket = null;
            $host = $this->dbhost;
            $port_or_socket = strstr( $host, ':' );
            if ( ! empty( $port_or_socket ) ) {
                $host = substr( $host, 0, strpos( $host, ':' ) );
                $port_or_socket = substr( $port_or_socket, 1 );
                if ( 0 !== strpos( $port_or_socket, '/' ) ) {
                    $port = intval( $port_or_socket );
                    $maybe_socket = strstr( $port_or_socket, ':' );
                    if ( ! empty( $maybe_socket ) ) {
                        $socket = substr( $maybe_socket, 1 );
                    }
                } else {
                    $socket = $port_or_socket;
                }
            }

            if ( WP_DEBUG ) {
                mysqli_real_connect( $this->dbh, $host, $this->dbuser, $this->dbpassword, null, $port, $socket, $client_flags );
            } else {
                @mysqli_real_connect( $this->dbh, $host, $this->dbuser, $this->dbpassword, null, $port, $socket, $client_flags );
            }

            if ( $this->dbh->connect_errno ) {
                $this->dbh = null;

                /* It's possible ext/mysqli is misconfigured. Fall back to ext/mysql if:
                 *  - We haven't previously connected, and
                 *  - WP_USE_EXT_MYSQL isn't set to false, and
                 *  - ext/mysql is loaded.
                 */
                $attempt_fallback = true;

                if ( $this->has_connected ) {
                    $attempt_fallback = false;
                } elseif ( defined( 'WP_USE_EXT_MYSQL' ) && ! WP_USE_EXT_MYSQL ) {
                    $attempt_fallback = false;
                } elseif ( ! function_exists( 'mysql_connect' ) ) {
                    $attempt_fallback = false;
                }

                if ( $attempt_fallback ) {
                    $this->use_mysqli = false;
                    return $this->db_connect( $allow_bail );
                }
            }
        } else {
            if ( WP_DEBUG ) {
                $this->dbh = mysql_connect( $this->dbhost, $this->dbuser, $this->dbpassword, $new_link, $client_flags );
            } else {
                $this->dbh = @mysql_connect( $this->dbhost, $this->dbuser, $this->dbpassword, $new_link, $client_flags );
            }
        }

        if ( ! $this->dbh && $allow_bail ) {

            // Load custom DB error template, if present.
            if ( file_exists( WP_CONTENT_DIR . '/db-error.php' ) ) {
                require_once( WP_CONTENT_DIR . '/db-error.php' );
                die();
            }

            $message = '<h1>' . __( 'Error establishing a database connection' ) . "</h1>\n";

            $message .= '<p>' . sprintf(
                /* translators: 1: wp-config.php. 2: database host */
                __( 'This either means that the username and password information in your %1$s file is incorrect or we can&#8217;t contact the database server at %2$s. This could mean your host&#8217;s database server is down.' ),
                '<code>wp-config.php</code>',
                '<code>' . htmlspecialchars( $this->dbhost, ENT_QUOTES ) . '</code>'
            ) . "</p>\n";

            $message .= "<ul>\n";
            $message .= '<li>' . __( 'Are you sure you have the correct username and password?' ) . "</li>\n";
            $message .= '<li>' . __( 'Are you sure that you have typed the correct hostname?' ) . "</li>\n";
            $message .= '<li>' . __( 'Are you sure that the database server is running?' ) . "</li>\n";
            $message .= "</ul>\n";

            $message .= '<p>' . sprintf(
                /* translators: %s: support forums URL */
                __( 'If you&#8217;re unsure what these terms mean you should probably contact your host. If you still need help you can always visit the <a href="%s">WordPress Support Forums</a>.' ),
                __( 'https://wordpress.org/support/' )
            ) . "</p>\n";

            $this->bail( $message, 'db_connect_fail' );

            return false;
        } elseif ( $this->dbh ) {
            if ( ! $this->has_connected ) {
                $this->init_charset();
            }

            $this->has_connected = true;

            $this->set_charset( $this->dbh );

            $this->ready = true;
            $this->set_sql_mode();
            $this->select( $this->dbname, $this->dbh );

            return true;
        }

        return false;
    }

    /**
     * Checks that the connection to the database is still up. If not, try to reconnect.
     *
     * If this function is unable to reconnect, it will forcibly die, or if after the
     * the {@see 'template_redirect'} hook has been fired, return false instead.
     *
     * If $allow_bail is false, the lack of database connection will need
     * to be handled manually.
     *
     * @since 3.9.0
     *
     * @param bool $allow_bail Optional. Allows the function to bail. Default true.
     * @return bool|void True if the connection is up.
     */
    public function check_connection( $allow_bail = true ) {
        if ( $this->use_mysqli ) {
            if ( ! empty( $this->dbh ) && mysqli_ping( $this->dbh ) ) {
                return true;
            }
        } else {
            if ( ! empty( $this->dbh ) && mysql_ping( $this->dbh ) ) {
                return true;
            }
        }

        $error_reporting = false;

        // Disable warnings, as we don't want to see a multitude of "unable to connect" messages
        if ( WP_DEBUG ) {
            $error_reporting = error_reporting();
            error_reporting( $error_reporting & ~E_WARNING );
        }

        for ( $tries = 1; $tries <= $this->reconnect_retries; $tries++ ) {
            // On the last try, re-enable warnings. We want to see a single instance of the
            // "unable to connect" message on the bail() screen, if it appears.
            if ( $this->reconnect_retries === $tries && WP_DEBUG ) {
                error_reporting( $error_reporting );
            }

            if ( $this->db_connect( false ) ) {
                if ( $error_reporting ) {
                    error_reporting( $error_reporting );
                }

                return true;
            }

            sleep( 1 );
        }

        // If template_redirect has already happened, it's too late for wp_die()/dead_db().
        // Let's just return and hope for the best.
        if ( did_action( 'template_redirect' ) ) {
            return false;
        }

        if ( ! $allow_bail ) {
            return false;
        }

        wp_load_translations_early();

        $message = '<h1>' . __( 'Error reconnecting to the database' ) . "</h1>\n";

        $message .= '<p>' . sprintf(
            /* translators: %s: database host */
            __( 'This means that we lost contact with the database server at %s. This could mean your host&#8217;s database server is down.' ),
            '<code>' . htmlspecialchars( $this->dbhost, ENT_QUOTES ) . '</code>'
        ) . "</p>\n";

        $message .= "<ul>\n";
        $message .= '<li>' . __( 'Are you sure that the database server is running?' ) . "</li>\n";
        $message .= '<li>' . __( 'Are you sure that the database server is not under particularly heavy load?' ) . "</li>\n";
        $message .= "</ul>\n";

        $message .= '<p>' . sprintf(
            /* translators: %s: support forums URL */
            __( 'If you&#8217;re unsure what these terms mean you should probably contact your host. If you still need help you can always visit the <a href="%s">WordPress Support Forums</a>.' ),
            __( 'https://wordpress.org/support/' )
        ) . "</p>\n";

        // We weren't able to reconnect, so we better bail.
        $this->bail( $message, 'db_connect_fail' );

        // Call dead_db() if bail didn't die, because this database is no more. It has ceased to be (at least temporarily).
        dead_db();
    }

    /**
     * Perform a MySQL database query, using current database connection.
     *
     * More information can be found on the codex page.
     *
     * @since 0.71
     *
     * @param string $query Database query
     * @return int|false Number of rows affected/selected or false on error
     */
    public function query( $query ) {
        if ( ! $this->ready ) {
            $this->check_current_query = true;
            return false;
        }

        /**
         * Filters the database query.
         *
         * Some queries are made before the plugins have been loaded,
         * and thus cannot be filtered with this method.
         *
         * @since 2.1.0
         *
         * @param string $query Database query.
         */
        $query = apply_filters( 'query', $query );

        $this->flush();

        // Log how the function was called
        $this->func_call = "\$db->query(\"$query\")";

        // If we're writing to the database, make sure the query will write safely.
        /*if ( $this->check_current_query && ! $this->check_ascii( $query ) ) {
            $stripped_query = $this->strip_invalid_text_from_query( $query );
            // strip_invalid_text_from_query() can perform queries, so we need
            // to flush again, just to make sure everything is clear.
            $this->flush();
            if ( $stripped_query !== $query ) {
                $this->insert_id = 0;
                $this->last_error = 'Not safe to execute this query';
                return false;
            }
        }*/

        $this->check_current_query = true;

        // Keep track of the last query for debug.
        $this->last_query = $query;

        $this->_do_query( $query );


        // MySQL server has gone away, try to reconnect.
        $mysql_errno = 0;
        if ( ! empty( $this->dbh ) ) {
            if ( $this->use_mysqli ) {
                if ( $this->dbh instanceof mysqli ) {
                    $mysql_errno = mysqli_errno( $this->dbh );
                } else {
                    // $dbh is defined, but isn't a real connection.
                    // Something has gone horribly wrong, let's try a reconnect.
                    $mysql_errno = 2006;
                }
            } else {
                if ( is_resource( $this->dbh ) ) {
                    $mysql_errno = mysql_errno( $this->dbh );
                } else {
                    $mysql_errno = 2006;
                }
            }
        }

        if ( empty( $this->dbh ) || 2006 == $mysql_errno ) {
            if ( $this->check_connection() ) {
                $this->_do_query( $query );
            } else {
                $this->insert_id = 0;
                return false;
            }
        }

        // If there is an error then take note of it.
        if ( $this->use_mysqli ) {
            if ( $this->dbh instanceof mysqli ) {

                $this->last_error = mysqli_error( $this->dbh );
                  $this->last_error_no = mysqli_errno( $this->dbh );
                //echo "check11".$this->last_error;
                //exit;
            } else {
                $this->last_error = __( 'Unable to retrieve the error message from MySQL' );
            }
        } else {
            if ( is_resource( $this->dbh ) ) {
                $this->last_error = mysql_error( $this->dbh );
                $this->last_error_no = mysql_errno( $this->dbh );
            } else {
                $this->last_error = __( 'Unable to retrieve the error message from MySQL' );
            }
        }

        if ( $this->last_error ) {

            // Clear insert_id on a subsequent failed insert.
            if ( $this->insert_id && preg_match( '/^\s*(insert|replace)\s/i', $query ) )
                $this->insert_id = 0;

        //  $this->print_error();
            return false;
        }

        if ( preg_match( '/^\s*(create|alter|truncate|drop)\s/i', $query ) ) {
            $return_val = $this->result;
        } elseif ( preg_match( '/^\s*(insert|delete|update|replace)\s/i', $query ) ) {
            if ( $this->use_mysqli ) {
                $this->rows_affected = mysqli_affected_rows( $this->dbh );
            } else {
                $this->rows_affected = mysql_affected_rows( $this->dbh );
            }
            // Take note of the insert_id
            if ( preg_match( '/^\s*(insert|replace)\s/i', $query ) ) {
                if ( $this->use_mysqli ) {
                    $this->insert_id = mysqli_insert_id( $this->dbh );
                } else {
                    $this->insert_id = mysql_insert_id( $this->dbh );
                }
            }
            // Return number of rows affected
            $return_val = $this->rows_affected;
        } else {
            $num_rows = 0;
            if ( $this->use_mysqli && $this->result instanceof mysqli_result ) {
                while ( $row = mysqli_fetch_object( $this->result ) ) {
                    $this->last_result[$num_rows] = $row;
                    $num_rows++;
                }
            } elseif ( is_resource( $this->result ) ) {
                while ( $row = mysql_fetch_object( $this->result ) ) {
                    $this->last_result[$num_rows] = $row;
                    $num_rows++;
                }
            }

            // Log number of rows the query returned
            // and return number of rows selected
            $this->num_rows = $num_rows;
            $return_val     = $num_rows;
        }

        return $return_val;
    }

    /**
     * Internal function to perform the mysql_query() call.
     *
     * @since 3.9.0
     *
     * @access private
     * @see wpdb::query()
     *
     * @param string $query The query to run.
     */
    private function _do_query( $query ) {
        if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
            $this->timer_start();
        }

        if ( ! empty( $this->dbh ) && $this->use_mysqli ) {
//file_put_contents("_test1.php",$query."\r\n",FILE_APPEND);

            $this->result = mysqli_query( $this->dbh, $query );




        } elseif ( ! empty( $this->dbh ) ) {
            $this->result = mysql_query( $query, $this->dbh );
        }
        $this->num_queries++;

        if ( defined( 'SAVEQUERIES' ) && SAVEQUERIES ) {
            $this->queries[] = array( $query, $this->timer_stop(), $this->get_caller() );
        }
    }

    /**
     * Insert a row into a table.
     *
     *     wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
     *     wpdb::insert( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
     *
     * @since 2.5.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string       $table  Table name
     * @param array        $data   Data to insert (in column => value pairs).
     *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
     * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
     *                             If string, that format will be used for all of the values in $data.
     *                             A format is one of '%d', '%f', '%s' (integer, float, string).
     *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @return int|false The number of rows inserted, or false on error.
     */
    public function insert( $table, $data, $format = null ) {
        return $this->_insert_replace_helper( $table, $data, $format, 'INSERT' );
    }

    /**
     * Replace a row into a table.
     *
     *     wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 'bar' ) )
     *     wpdb::replace( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( '%s', '%d' ) )
     *
     * @since 3.0.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string       $table  Table name
     * @param array        $data   Data to insert (in column => value pairs).
     *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
     * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
     *                             If string, that format will be used for all of the values in $data.
     *                             A format is one of '%d', '%f', '%s' (integer, float, string).
     *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @return int|false The number of rows affected, or false on error.
     */
    public function replace( $table, $data, $format = null ) {
        return $this->_insert_replace_helper( $table, $data, $format, 'REPLACE' );
    }

    /**
     * Helper function for insert and replace.
     *
     * Runs an insert or replace query based on $type argument.
     *
     * @access private
     * @since 3.0.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string       $table  Table name
     * @param array        $data   Data to insert (in column => value pairs).
     *                             Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     *                             Sending a null value will cause the column to be set to NULL - the corresponding format is ignored in this case.
     * @param array|string $format Optional. An array of formats to be mapped to each of the value in $data.
     *                             If string, that format will be used for all of the values in $data.
     *                             A format is one of '%d', '%f', '%s' (integer, float, string).
     *                             If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @param string $type         Optional. What type of operation is this? INSERT or REPLACE. Defaults to INSERT.
     * @return int|false The number of rows affected, or false on error.
     */
    function _insert_replace_helper( $table, $data, $format = null, $type = 'INSERT' ) {
        $this->insert_id = 0;

        if ( ! in_array( strtoupper( $type ), array( 'REPLACE', 'INSERT' ) ) ) {
            return false;
        }

        $data = $this->process_fields( $table, $data, $format );
        if ( false === $data ) {
            return false;
        }

        $formats = $values = array();
        foreach ( $data as $value ) {
            if ( is_null( $value['value'] ) ) {
                $formats[] = 'NULL';
                continue;
            }

            $formats[] = $value['format'];
            $values[]  = $value['value'];
        }

        $fields  = '`' . implode( '`, `', array_keys( $data ) ) . '`';
        $formats = implode( ', ', $formats );

        $sql = "$type INTO `$table` ($fields) VALUES ($formats)";

        $this->check_current_query = false;
        return $this->query( $this->prepare( $sql, $values ) );
    }

    /**
     * Update a row in the table
     *
     *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 'bar' ), array( 'ID' => 1 ) )
     *     wpdb::update( 'table', array( 'column' => 'foo', 'field' => 1337 ), array( 'ID' => 1 ), array( '%s', '%d' ), array( '%d' ) )
     *
     * @since 2.5.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string       $table        Table name
     * @param array        $data         Data to update (in column => value pairs).
     *                                   Both $data columns and $data values should be "raw" (neither should be SQL escaped).
     *                                   Sending a null value will cause the column to be set to NULL - the corresponding
     *                                   format is ignored in this case.
     * @param array        $where        A named array of WHERE clauses (in column => value pairs).
     *                                   Multiple clauses will be joined with ANDs.
     *                                   Both $where columns and $where values should be "raw".
     *                                   Sending a null value will create an IS NULL comparison - the corresponding format will be ignored in this case.
     * @param array|string $format       Optional. An array of formats to be mapped to each of the values in $data.
     *                                   If string, that format will be used for all of the values in $data.
     *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
     *                                   If omitted, all values in $data will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
     *                                   If string, that format will be used for all of the items in $where.
     *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
     *                                   If omitted, all values in $where will be treated as strings.
     * @return int|false The number of rows updated, or false on error.
     */
    public function update( $table, $data, $where, $format = null, $where_format = null ) {
        if ( ! is_array( $data ) || ! is_array( $where ) ) {
            return false;
        }

        $data = $this->process_fields( $table, $data, $format );
        if ( false === $data ) {
            return false;
        }
        $where = $this->process_fields( $table, $where, $where_format );
        if ( false === $where ) {
            return false;
        }

        $fields = $conditions = $values = array();
        foreach ( $data as $field => $value ) {
            if ( is_null( $value['value'] ) ) {
                $fields[] = "`$field` = NULL";
                continue;
            }

            $fields[] = "`$field` = " . $value['format'];
            $values[] = $value['value'];
        }
        foreach ( $where as $field => $value ) {
            if ( is_null( $value['value'] ) ) {
                $conditions[] = "`$field` IS NULL";
                continue;
            }

            $conditions[] = "`$field` = " . $value['format'];
            $values[] = $value['value'];
        }

        $fields = implode( ', ', $fields );
        $conditions = implode( ' AND ', $conditions );

        $sql = "UPDATE `$table` SET $fields WHERE $conditions";

        $this->check_current_query = false;
        return $this->query( $this->prepare( $sql, $values ) );
    }

    /**
     * Delete a row in the table
     *
     *     wpdb::delete( 'table', array( 'ID' => 1 ) )
     *     wpdb::delete( 'table', array( 'ID' => 1 ), array( '%d' ) )
     *
     * @since 3.4.0
     * @see wpdb::prepare()
     * @see wpdb::$field_types
     * @see wp_set_wpdb_vars()
     *
     * @param string       $table        Table name
     * @param array        $where        A named array of WHERE clauses (in column => value pairs).
     *                                   Multiple clauses will be joined with ANDs.
     *                                   Both $where columns and $where values should be "raw".
     *                                   Sending a null value will create an IS NULL comparison - the corresponding format will be ignored in this case.
     * @param array|string $where_format Optional. An array of formats to be mapped to each of the values in $where.
     *                                   If string, that format will be used for all of the items in $where.
     *                                   A format is one of '%d', '%f', '%s' (integer, float, string).
     *                                   If omitted, all values in $where will be treated as strings unless otherwise specified in wpdb::$field_types.
     * @return int|false The number of rows updated, or false on error.
     */
    public function delete( $table, $where, $where_format = null ) {
        if ( ! is_array( $where ) ) {
            return false;
        }

        $where = $this->process_fields( $table, $where, $where_format );
        if ( false === $where ) {
            return false;
        }

        $conditions = $values = array();
        foreach ( $where as $field => $value ) {
            if ( is_null( $value['value'] ) ) {
                $conditions[] = "`$field` IS NULL";
                continue;
            }

            $conditions[] = "`$field` = " . $value['format'];
            $values[] = $value['value'];
        }

        $conditions = implode( ' AND ', $conditions );

        $sql = "DELETE FROM `$table` WHERE $conditions";

        $this->check_current_query = false;
        return $this->query( $this->prepare( $sql, $values ) );
    }

    /**
     * Processes arrays of field/value pairs and field formats.
     *
     * This is a helper method for wpdb's CRUD methods, which take field/value
     * pairs for inserts, updates, and where clauses. This method first pairs
     * each value with a format. Then it determines the charset of that field,
     * using that to determine if any invalid text would be stripped. If text is
     * stripped, then field processing is rejected and the query fails.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param string $table  Table name.
     * @param array  $data   Field/value pair.
     * @param mixed  $format Format for each field.
     * @return array|false Returns an array of fields that contain paired values
     *                    and formats. Returns false for invalid values.
     */
    protected function process_fields( $table, $data, $format ) {
        $data = $this->process_field_formats( $data, $format );
        if ( false === $data ) {
            return false;
        }

        $data = $this->process_field_charsets( $data, $table );
        if ( false === $data ) {
            return false;
        }

        $data = $this->process_field_lengths( $data, $table );
        if ( false === $data ) {
            return false;
        }

        $converted_data = $this->strip_invalid_text( $data );

        if ( $data !== $converted_data ) {
            return false;
        }

        return $data;
    }

    /**
     * Prepares arrays of value/format pairs as passed to wpdb CRUD methods.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param array $data   Array of fields to values.
     * @param mixed $format Formats to be mapped to the values in $data.
     * @return array Array, keyed by field names with values being an array
     *               of 'value' and 'format' keys.
     */
    protected function process_field_formats( $data, $format ) {
        $formats = $original_formats = (array) $format;

        foreach ( $data as $field => $value ) {
            $value = array(
                'value'  => $value,
                'format' => '%s',
            );

            if ( ! empty( $format ) ) {
                $value['format'] = array_shift( $formats );
                if ( ! $value['format'] ) {
                    $value['format'] = reset( $original_formats );
                }
            } elseif ( isset( $this->field_types[ $field ] ) ) {
                $value['format'] = $this->field_types[ $field ];
            }

            $data[ $field ] = $value;
        }

        return $data;
    }

    /**
     * Adds field charsets to field/value/format arrays generated by
     * the wpdb::process_field_formats() method.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param array  $data  As it comes from the wpdb::process_field_formats() method.
     * @param string $table Table name.
     * @return array|false The same array as $data with additional 'charset' keys.
     */
    protected function process_field_charsets( $data, $table ) {
        foreach ( $data as $field => $value ) {
            if ( '%d' === $value['format'] || '%f' === $value['format'] ) {
                /*
                 * We can skip this field if we know it isn't a string.
                 * This checks %d/%f versus ! %s because its sprintf() could take more.
                 */
                $value['charset'] = false;
            } else {
                $value['charset'] = $this->get_col_charset( $table, $field );
                if ( is_wp_error( $value['charset'] ) ) {
                    return false;
                }
            }

            $data[ $field ] = $value;
        }

        return $data;
    }

    /**
     * For string fields, record the maximum string length that field can safely save.
     *
     * @since 4.2.1
     * @access protected
     *
     * @param array  $data  As it comes from the wpdb::process_field_charsets() method.
     * @param string $table Table name.
     * @return array|false The same array as $data with additional 'length' keys, or false if
     *                     any of the values were too long for their corresponding field.
     */
    protected function process_field_lengths( $data, $table ) {
        foreach ( $data as $field => $value ) {
            if ( '%d' === $value['format'] || '%f' === $value['format'] ) {
                /*
                 * We can skip this field if we know it isn't a string.
                 * This checks %d/%f versus ! %s because its sprintf() could take more.
                 */
                $value['length'] = false;
            } else {
                $value['length'] = $this->get_col_length( $table, $field );
                if ( is_wp_error( $value['length'] ) ) {
                    return false;
                }
            }

            $data[ $field ] = $value;
        }

        return $data;
    }

    /**
     * Retrieve one variable from the database.
     *
     * Executes a SQL query and returns the value from the SQL result.
     * If the SQL result contains more than one column and/or more than one row, this function returns the value in the column and row specified.
     * If $query is null, this function returns the value in the specified column and row from the previous SQL result.
     *
     * @since 0.71
     *
     * @param string|null $query Optional. SQL query. Defaults to null, use the result from the previous query.
     * @param int         $x     Optional. Column of value to return. Indexed from 0.
     * @param int         $y     Optional. Row of value to return. Indexed from 0.
     * @return string|null Database query result (as string), or null on failure
     */
    public function get_var( $query = null, $x = 0, $y = 0 ) {
        $this->func_call = "\$db->get_var(\"$query\", $x, $y)";

        if ( $this->check_current_query && $this->check_safe_collation( $query ) ) {
            $this->check_current_query = false;
        }

        if ( $query ) {
            $this->query( $query );
        }

        // Extract var out of cached results based x,y vals
        if ( !empty( $this->last_result[$y] ) ) {
            $values = array_values( get_object_vars( $this->last_result[$y] ) );
        }

        // If there is a value return it else return null
        return ( isset( $values[$x] ) && $values[$x] !== '' ) ? $values[$x] : null;
    }

    /**
     * Retrieve one row from the database.
     *
     * Executes a SQL query and returns the row from the SQL result.
     *
     * @since 0.71
     *
     * @param string|null $query  SQL query.
     * @param string      $output Optional. The required return type. One of OBJECT, ARRAY_A, or ARRAY_N, which correspond to
     *                            an stdClass object, an associative array, or a numeric array, respectively. Default OBJECT.
     * @param int         $y      Optional. Row to return. Indexed from 0.
     * @return array|object|null|void Database query result in format specified by $output or null on failure
     */
    public function get_row( $query = null, $output = OBJECT, $y = 0 ) {
        $this->func_call = "\$db->get_row(\"$query\",$output,$y)";

        if ( $this->check_current_query && $this->check_safe_collation( $query ) ) {
            $this->check_current_query = false;
        }

        if ( $query ) {
            $this->query( $query );
        } else {
            return null;
        }

        if ( !isset( $this->last_result[$y] ) )
            return null;

        if ( $output == OBJECT ) {
            return $this->last_result[$y] ? $this->last_result[$y] : null;
        } elseif ( $output == ARRAY_A ) {
            return $this->last_result[$y] ? get_object_vars( $this->last_result[$y] ) : null;
        } elseif ( $output == ARRAY_N ) {
            return $this->last_result[$y] ? array_values( get_object_vars( $this->last_result[$y] ) ) : null;
        } elseif ( strtoupper( $output ) === OBJECT ) {
            // Back compat for OBJECT being previously case insensitive.
            return $this->last_result[$y] ? $this->last_result[$y] : null;
        } else {
            $this->print_error( " \$db->get_row(string query, output type, int offset) -- Output type must be one of: OBJECT, ARRAY_A, ARRAY_N" );
        }
    }

    /**
     * Retrieve one column from the database.
     *
     * Executes a SQL query and returns the column from the SQL result.
     * If the SQL result contains more than one column, this function returns the column specified.
     * If $query is null, this function returns the specified column from the previous SQL result.
     *
     * @since 0.71
     *
     * @param string|null $query Optional. SQL query. Defaults to previous query.
     * @param int         $x     Optional. Column to return. Indexed from 0.
     * @return array Database query result. Array indexed from 0 by SQL result row number.
     */
    public function get_col( $query = null , $x = 0 ) {
        if ( $this->check_current_query && $this->check_safe_collation( $query ) ) {
            $this->check_current_query = false;
        }

        if ( $query ) {
            $this->query( $query );
        }

        $new_array = array();
        // Extract the column values
        for ( $i = 0, $j = count( $this->last_result ); $i < $j; $i++ ) {
            $new_array[$i] = $this->get_var( null, $x, $i );
        }
        return $new_array;
    }

    /**
     * Retrieve an entire SQL result set from the database (i.e., many rows)
     *
     * Executes a SQL query and returns the entire SQL result.
     *
     * @since 0.71
     *
     * @param string $query  SQL query.
     * @param string $output Optional. Any of ARRAY_A | ARRAY_N | OBJECT | OBJECT_K constants.
     *                       With one of the first three, return an array of rows indexed from 0 by SQL result row number.
     *                       Each row is an associative array (column => value, ...), a numerically indexed array (0 => value, ...), or an object. ( ->column = value ), respectively.
     *                       With OBJECT_K, return an associative array of row objects keyed by the value of each row's first column's value.
     *                       Duplicate keys are discarded.
     * @return array|object|null Database query results
     */
    public function get_results( $query = null, $output = OBJECT ) {
        $this->func_call = "\$db->get_results(\"$query\", $output)";

        if ( $this->check_current_query && $this->check_safe_collation( $query ) ) {
            $this->check_current_query = false;
        }

        if ( $query ) {
            $this->query( $query );
        } else {
            return null;
        }

        $new_array = array();
        if ( $output == OBJECT ) {
            // Return an integer-keyed array of row objects
            return $this->last_result;
        } elseif ( $output == OBJECT_K ) {
            // Return an array of row objects with keys from column 1
            // (Duplicates are discarded)
            foreach ( $this->last_result as $row ) {
                $var_by_ref = get_object_vars( $row );
                $key = array_shift( $var_by_ref );
                if ( ! isset( $new_array[ $key ] ) )
                    $new_array[ $key ] = $row;
            }
            return $new_array;
        } elseif ( $output == ARRAY_A || $output == ARRAY_N ) {
            // Return an integer-keyed array of...
            if ( $this->last_result ) {
                foreach ( (array) $this->last_result as $row ) {
                    if ( $output == ARRAY_N ) {
                        // ...integer-keyed row arrays
                        $new_array[] = array_values( get_object_vars( $row ) );
                    } else {
                        // ...column name-keyed row arrays
                        $new_array[] = get_object_vars( $row );
                    }
                }
            }
            return $new_array;
        } elseif ( strtoupper( $output ) === OBJECT ) {
            // Back compat for OBJECT being previously case insensitive.
            return $this->last_result;
        }
        return null;
    }

    /**
     * Retrieves the character set for the given table.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param string $table Table name.
     * @return string|WP_Error Table character set, WP_Error object if it couldn't be found.
     */
    protected function get_table_charset( $table ) {
        $tablekey = strtolower( $table );

        /**
         * Filters the table charset value before the DB is checked.
         *
         * Passing a non-null value to the filter will effectively short-circuit
         * checking the DB for the charset, returning that value instead.
         *
         * @since 4.2.0
         *
         * @param string $charset The character set to use. Default null.
         * @param string $table   The name of the table being checked.
         */
        $charset = apply_filters( 'pre_get_table_charset', null, $table );
        if ( null !== $charset ) {
            return $charset;
        }

        if ( isset( $this->table_charset[ $tablekey ] ) ) {
            return $this->table_charset[ $tablekey ];
        }

        $charsets = $columns = array();

        $table_parts = explode( '.', $table );
        $table = '`' . implode( '`.`', $table_parts ) . '`';
        $results = $this->get_results( "SHOW FULL COLUMNS FROM $table" );
        if ( ! $results ) {
            return new WP_Error( 'wpdb_get_table_charset_failure' );
        }

        foreach ( $results as $column ) {
            $columns[ strtolower( $column->Field ) ] = $column;
        }

        $this->col_meta[ $tablekey ] = $columns;

        foreach ( $columns as $column ) {
            if ( ! empty( $column->Collation ) ) {
                list( $charset ) = explode( '_', $column->Collation );

                // If the current connection can't support utf8mb4 characters, let's only send 3-byte utf8 characters.
                if ( 'utf8mb4' === $charset && ! $this->has_cap( 'utf8mb4' ) ) {
                    $charset = 'utf8';
                }

                $charsets[ strtolower( $charset ) ] = true;
            }

            list( $type ) = explode( '(', $column->Type );

            // A binary/blob means the whole query gets treated like this.
            if ( in_array( strtoupper( $type ), array( 'BINARY', 'VARBINARY', 'TINYBLOB', 'MEDIUMBLOB', 'BLOB', 'LONGBLOB' ) ) ) {
                $this->table_charset[ $tablekey ] = 'binary';
                return 'binary';
            }
        }

        // utf8mb3 is an alias for utf8.
        if ( isset( $charsets['utf8mb3'] ) ) {
            $charsets['utf8'] = true;
            unset( $charsets['utf8mb3'] );
        }

        // Check if we have more than one charset in play.
        $count = count( $charsets );
        if ( 1 === $count ) {
            $charset = key( $charsets );
        } elseif ( 0 === $count ) {
            // No charsets, assume this table can store whatever.
            $charset = false;
        } else {
            // More than one charset. Remove latin1 if present and recalculate.
            unset( $charsets['latin1'] );
            $count = count( $charsets );
            if ( 1 === $count ) {
                // Only one charset (besides latin1).
                $charset = key( $charsets );
            } elseif ( 2 === $count && isset( $charsets['utf8'], $charsets['utf8mb4'] ) ) {
                // Two charsets, but they're utf8 and utf8mb4, use utf8.
                $charset = 'utf8';
            } else {
                // Two mixed character sets. ascii.
                $charset = 'ascii';
            }
        }

        $this->table_charset[ $tablekey ] = $charset;
        return $charset;
    }

    /**
     * Retrieves the character set for the given column.
     *
     * @since 4.2.0
     * @access public
     *
     * @param string $table  Table name.
     * @param string $column Column name.
     * @return string|false|WP_Error Column character set as a string. False if the column has no
     *                               character set. WP_Error object if there was an error.
     */
    public function get_col_charset( $table, $column ) {
        $tablekey = strtolower( $table );
        $columnkey = strtolower( $column );

        /**
         * Filters the column charset value before the DB is checked.
         *
         * Passing a non-null value to the filter will short-circuit
         * checking the DB for the charset, returning that value instead.
         *
         * @since 4.2.0
         *
         * @param string $charset The character set to use. Default null.
         * @param string $table   The name of the table being checked.
         * @param string $column  The name of the column being checked.
         */
        $charset = apply_filters( 'pre_get_col_charset', null, $table, $column );
        if ( null !== $charset ) {
            return $charset;
        }

        // Skip this entirely if this isn't a MySQL database.
        if ( empty( $this->is_mysql ) ) {
            return false;
        }

        if ( empty( $this->table_charset[ $tablekey ] ) ) {
            // This primes column information for us.
            $table_charset = $this->get_table_charset( $table );
            if ( is_wp_error( $table_charset ) ) {
                return $table_charset;
            }
        }

        // If still no column information, return the table charset.
        if ( empty( $this->col_meta[ $tablekey ] ) ) {
            return $this->table_charset[ $tablekey ];
        }

        // If this column doesn't exist, return the table charset.
        if ( empty( $this->col_meta[ $tablekey ][ $columnkey ] ) ) {
            return $this->table_charset[ $tablekey ];
        }

        // Return false when it's not a string column.
        if ( empty( $this->col_meta[ $tablekey ][ $columnkey ]->Collation ) ) {
            return false;
        }

        list( $charset ) = explode( '_', $this->col_meta[ $tablekey ][ $columnkey ]->Collation );
        return $charset;
    }

    /**
     * Retrieve the maximum string length allowed in a given column.
     * The length may either be specified as a byte length or a character length.
     *
     * @since 4.2.1
     * @access public
     *
     * @param string $table  Table name.
     * @param string $column Column name.
     * @return array|false|WP_Error array( 'length' => (int), 'type' => 'byte' | 'char' )
     *                              false if the column has no length (for example, numeric column)
     *                              WP_Error object if there was an error.
     */
    public function get_col_length( $table, $column ) {
        $tablekey = strtolower( $table );
        $columnkey = strtolower( $column );

        // Skip this entirely if this isn't a MySQL database.
        if ( empty( $this->is_mysql ) ) {
            return false;
        }

        if ( empty( $this->col_meta[ $tablekey ] ) ) {
            // This primes column information for us.
            $table_charset = $this->get_table_charset( $table );
            if ( is_wp_error( $table_charset ) ) {
                return $table_charset;
            }
        }

        if ( empty( $this->col_meta[ $tablekey ][ $columnkey ] ) ) {
            return false;
        }

        $typeinfo = explode( '(', $this->col_meta[ $tablekey ][ $columnkey ]->Type );

        $type = strtolower( $typeinfo[0] );
        if ( ! empty( $typeinfo[1] ) ) {
            $length = trim( $typeinfo[1], ')' );
        } else {
            $length = false;
        }

        switch( $type ) {
            case 'char':
            case 'varchar':
                return array(
                    'type'   => 'char',
                    'length' => (int) $length,
                );

            case 'binary':
            case 'varbinary':
                return array(
                    'type'   => 'byte',
                    'length' => (int) $length,
                );

            case 'tinyblob':
            case 'tinytext':
                return array(
                    'type'   => 'byte',
                    'length' => 255,        // 2^8 - 1
                );

            case 'blob':
            case 'text':
                return array(
                    'type'   => 'byte',
                    'length' => 65535,      // 2^16 - 1
                );

            case 'mediumblob':
            case 'mediumtext':
                return array(
                    'type'   => 'byte',
                    'length' => 16777215,   // 2^24 - 1
                );

            case 'longblob':
            case 'longtext':
                return array(
                    'type'   => 'byte',
                    'length' => 4294967295, // 2^32 - 1
                );

            default:
                return false;
        }
    }

    /**
     * Check if a string is ASCII.
     *
     * The negative regex is faster for non-ASCII strings, as it allows
     * the search to finish as soon as it encounters a non-ASCII character.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param string $string String to check.
     * @return bool True if ASCII, false if not.
     */
    protected function check_ascii( $string ) {
        if ( function_exists( 'mb_check_encoding' ) ) {
            if ( mb_check_encoding( $string, 'ASCII' ) ) {
                return true;
            }
        } elseif ( ! preg_match( '/[^\x00-\x7F]/', $string ) ) {
            return true;
        }

        return false;
    }

    /**
     * Check if the query is accessing a collation considered safe on the current version of MySQL.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param string $query The query to check.
     * @return bool True if the collation is safe, false if it isn't.
     */
    protected function check_safe_collation( $query ) {
        if ( $this->checking_collation ) {
            return true;
        }

        // We don't need to check the collation for queries that don't read data.
        $query = ltrim( $query, "\r\n\t (" );
        if ( preg_match( '/^(?:SHOW|DESCRIBE|DESC|EXPLAIN|CREATE)\s/i', $query ) ) {
            return true;
        }

        // All-ASCII queries don't need extra checking.
        if ( $this->check_ascii( $query ) ) {
            return true;
        }

        $table = $this->get_table_from_query( $query );
        if ( ! $table ) {
            return false;
        }

        $this->checking_collation = true;
        $collation = $this->get_table_charset( $table );
        $this->checking_collation = false;

        // Tables with no collation, or latin1 only, don't need extra checking.
        if ( false === $collation || 'latin1' === $collation ) {
            return true;
        }

        $table = strtolower( $table );
        if ( empty( $this->col_meta[ $table ] ) ) {
            return false;
        }

        // If any of the columns don't have one of these collations, it needs more sanity checking.
        foreach ( $this->col_meta[ $table ] as $col ) {
            if ( empty( $col->Collation ) ) {
                continue;
            }

            if ( ! in_array( $col->Collation, array( 'utf8_general_ci', 'utf8_bin', 'utf8mb4_general_ci', 'utf8mb4_bin' ), true ) ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Strips any invalid characters based on value/charset pairs.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param array $data Array of value arrays. Each value array has the keys
     *                    'value' and 'charset'. An optional 'ascii' key can be
     *                    set to false to avoid redundant ASCII checks.
     * @return array|WP_Error The $data parameter, with invalid characters removed from
     *                        each value. This works as a passthrough: any additional keys
     *                        such as 'field' are retained in each value array. If we cannot
     *                        remove invalid characters, a WP_Error object is returned.
     */
    protected function strip_invalid_text( $data ) {
        $db_check_string = false;

        foreach ( $data as &$value ) {
            $charset = $value['charset'];

            if ( is_array( $value['length'] ) ) {
                $length = $value['length']['length'];
                $truncate_by_byte_length = 'byte' === $value['length']['type'];
            } else {
                $length = false;
                // Since we have no length, we'll never truncate.
                // Initialize the variable to false. true would take us
                // through an unnecessary (for this case) codepath below.
                $truncate_by_byte_length = false;
            }

            // There's no charset to work with.
            if ( false === $charset ) {
                continue;
            }

            // Column isn't a string.
            if ( ! is_string( $value['value'] ) ) {
                continue;
            }

            $needs_validation = true;
            if (
                // latin1 can store any byte sequence
                'latin1' === $charset
            ||
                // ASCII is always OK.
                ( ! isset( $value['ascii'] ) && $this->check_ascii( $value['value'] ) )
            ) {
                $truncate_by_byte_length = true;
                $needs_validation = false;
            }

            if ( $truncate_by_byte_length ) {
                mbstring_binary_safe_encoding();
                if ( false !== $length && strlen( $value['value'] ) > $length ) {
                    $value['value'] = substr( $value['value'], 0, $length );
                }
                reset_mbstring_encoding();

                if ( ! $needs_validation ) {
                    continue;
                }
            }

            // utf8 can be handled by regex, which is a bunch faster than a DB lookup.
            if ( ( 'utf8' === $charset || 'utf8mb3' === $charset || 'utf8mb4' === $charset ) && function_exists( 'mb_strlen' ) ) {
                $regex = '/
                    (
                        (?: [\x00-\x7F]                  # single-byte sequences   0xxxxxxx
                        |   [\xC2-\xDF][\x80-\xBF]       # double-byte sequences   110xxxxx 10xxxxxx
                        |   \xE0[\xA0-\xBF][\x80-\xBF]   # triple-byte sequences   1110xxxx 10xxxxxx * 2
                        |   [\xE1-\xEC][\x80-\xBF]{2}
                        |   \xED[\x80-\x9F][\x80-\xBF]
                        |   [\xEE-\xEF][\x80-\xBF]{2}';

                if ( 'utf8mb4' === $charset ) {
                    $regex .= '
                        |    \xF0[\x90-\xBF][\x80-\xBF]{2} # four-byte sequences   11110xxx 10xxxxxx * 3
                        |    [\xF1-\xF3][\x80-\xBF]{3}
                        |    \xF4[\x80-\x8F][\x80-\xBF]{2}
                    ';
                }

                $regex .= '){1,40}                          # ...one or more times
                    )
                    | .                                  # anything else
                    /x';
                $value['value'] = preg_replace( $regex, '$1', $value['value'] );


                if ( false !== $length && mb_strlen( $value['value'], 'UTF-8' ) > $length ) {
                    $value['value'] = mb_substr( $value['value'], 0, $length, 'UTF-8' );
                }
                continue;
            }

            // We couldn't use any local conversions, send it to the DB.
            $value['db'] = $db_check_string = true;
        }
        unset( $value ); // Remove by reference.

        if ( $db_check_string ) {
            $queries = array();
            foreach ( $data as $col => $value ) {
                if ( ! empty( $value['db'] ) ) {
                    // We're going to need to truncate by characters or bytes, depending on the length value we have.
                    if ( 'byte' === $value['length']['type'] ) {
                        // Using binary causes LEFT() to truncate by bytes.
                        $charset = 'binary';
                    } else {
                        $charset = $value['charset'];
                    }

                    if ( $this->charset ) {
                        $connection_charset = $this->charset;
                    } else {
                        if ( $this->use_mysqli ) {
                            $connection_charset = mysqli_character_set_name( $this->dbh );
                        } else {
                            $connection_charset = mysql_client_encoding();
                        }
                    }

                    if ( is_array( $value['length'] ) ) {
                        $queries[ $col ] = $this->prepare( "CONVERT( LEFT( CONVERT( %s USING $charset ), %.0f ) USING $connection_charset )", $value['value'], $value['length']['length'] );
                    } else if ( 'binary' !== $charset ) {
                        // If we don't have a length, there's no need to convert binary - it will always return the same result.
                        $queries[ $col ] = $this->prepare( "CONVERT( CONVERT( %s USING $charset ) USING $connection_charset )", $value['value'] );
                    }

                    unset( $data[ $col ]['db'] );
                }
            }

            $sql = array();
            foreach ( $queries as $column => $query ) {
                if ( ! $query ) {
                    continue;
                }

                $sql[] = $query . " AS x_$column";
            }

            $this->check_current_query = false;
            $row = $this->get_row( "SELECT " . implode( ', ', $sql ), ARRAY_A );
            if ( ! $row ) {
                return new WP_Error( 'wpdb_strip_invalid_text_failure' );
            }

            foreach ( array_keys( $data ) as $column ) {
                if ( isset( $row["x_$column"] ) ) {
                    $data[ $column ]['value'] = $row["x_$column"];
                }
            }
        }

        return $data;
    }

    /**
     * Strips any invalid characters from the query.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param string $query Query to convert.
     * @return string|WP_Error The converted query, or a WP_Error object if the conversion fails.
     */
    protected function strip_invalid_text_from_query( $query ) {
        // We don't need to check the collation for queries that don't read data.
        $trimmed_query = ltrim( $query, "\r\n\t (" );
        if ( preg_match( '/^(?:SHOW|DESCRIBE|DESC|EXPLAIN|CREATE)\s/i', $trimmed_query ) ) {
            return $query;
        }

        $table = $this->get_table_from_query( $query );
        if ( $table ) {
            $charset = $this->get_table_charset( $table );
            if ( is_wp_error( $charset ) ) {
                return $charset;
            }

            // We can't reliably strip text from tables containing binary/blob columns
            if ( 'binary' === $charset ) {
                return $query;
            }
        } else {
            $charset = $this->charset;
        }

        $data = array(
            'value'   => $query,
            'charset' => $charset,
            'ascii'   => false,
            'length'  => false,
        );

        $data = $this->strip_invalid_text( array( $data ) );
        if ( is_wp_error( $data ) ) {
            return $data;
        }

        return $data[0]['value'];
    }

    /**
     * Strips any invalid characters from the string for a given table and column.
     *
     * @since 4.2.0
     * @access public
     *
     * @param string $table  Table name.
     * @param string $column Column name.
     * @param string $value  The text to check.
     * @return string|WP_Error The converted string, or a WP_Error object if the conversion fails.
     */
    public function strip_invalid_text_for_column( $table, $column, $value ) {
        if ( ! is_string( $value ) ) {
            return $value;
        }

        $charset = $this->get_col_charset( $table, $column );
        if ( ! $charset ) {
            // Not a string column.
            return $value;
        } elseif ( is_wp_error( $charset ) ) {
            // Bail on real errors.
            return $charset;
        }

        $data = array(
            $column => array(
                'value'   => $value,
                'charset' => $charset,
                'length'  => $this->get_col_length( $table, $column ),
            )
        );

        $data = $this->strip_invalid_text( $data );
        if ( is_wp_error( $data ) ) {
            return $data;
        }

        return $data[ $column ]['value'];
    }

    /**
     * Find the first table name referenced in a query.
     *
     * @since 4.2.0
     * @access protected
     *
     * @param string $query The query to search.
     * @return string|false $table The table name found, or false if a table couldn't be found.
     */
    protected function get_table_from_query( $query ) {
        // Remove characters that can legally trail the table name.
        $query = rtrim( $query, ';/-#' );

        // Allow (select...) union [...] style queries. Use the first query's table name.
        $query = ltrim( $query, "\r\n\t (" );

        // Strip everything between parentheses except nested selects.
        $query = preg_replace( '/\((?!\s*select)[^(]*?\)/is', '()', $query );

        // Quickly match most common queries.
        if ( preg_match( '/^\s*(?:'
                . 'SELECT.*?\s+FROM'
                . '|INSERT(?:\s+LOW_PRIORITY|\s+DELAYED|\s+HIGH_PRIORITY)?(?:\s+IGNORE)?(?:\s+INTO)?'
                . '|REPLACE(?:\s+LOW_PRIORITY|\s+DELAYED)?(?:\s+INTO)?'
                . '|UPDATE(?:\s+LOW_PRIORITY)?(?:\s+IGNORE)?'
                . '|DELETE(?:\s+LOW_PRIORITY|\s+QUICK|\s+IGNORE)*(?:.+?FROM)?'
                . ')\s+((?:[0-9a-zA-Z$_.`-]|[\xC2-\xDF][\x80-\xBF])+)/is', $query, $maybe ) ) {
            return str_replace( '`', '', $maybe[1] );
        }

        // SHOW TABLE STATUS and SHOW TABLES WHERE Name = 'wp_posts'
        if ( preg_match( '/^\s*SHOW\s+(?:TABLE\s+STATUS|(?:FULL\s+)?TABLES).+WHERE\s+Name\s*=\s*("|\')((?:[0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF])+)\\1/is', $query, $maybe ) ) {
            return $maybe[2];
        }

        // SHOW TABLE STATUS LIKE and SHOW TABLES LIKE 'wp\_123\_%'
        // This quoted LIKE operand seldom holds a full table name.
        // It is usually a pattern for matching a prefix so we just
        // strip the trailing % and unescape the _ to get 'wp_123_'
        // which drop-ins can use for routing these SQL statements.
        if ( preg_match( '/^\s*SHOW\s+(?:TABLE\s+STATUS|(?:FULL\s+)?TABLES)\s+(?:WHERE\s+Name\s+)?LIKE\s*("|\')((?:[\\\\0-9a-zA-Z$_.-]|[\xC2-\xDF][\x80-\xBF])+)%?\\1/is', $query, $maybe ) ) {
            return str_replace( '\\_', '_', $maybe[2] );
        }

        // Big pattern for the rest of the table-related queries.
        if ( preg_match( '/^\s*(?:'
                . '(?:EXPLAIN\s+(?:EXTENDED\s+)?)?SELECT.*?\s+FROM'
                . '|DESCRIBE|DESC|EXPLAIN|HANDLER'
                . '|(?:LOCK|UNLOCK)\s+TABLE(?:S)?'
                . '|(?:RENAME|OPTIMIZE|BACKUP|RESTORE|CHECK|CHECKSUM|ANALYZE|REPAIR).*\s+TABLE'
                . '|TRUNCATE(?:\s+TABLE)?'
                . '|CREATE(?:\s+TEMPORARY)?\s+TABLE(?:\s+IF\s+NOT\s+EXISTS)?'
                . '|ALTER(?:\s+IGNORE)?\s+TABLE'
                . '|DROP\s+TABLE(?:\s+IF\s+EXISTS)?'
                . '|CREATE(?:\s+\w+)?\s+INDEX.*\s+ON'
                . '|DROP\s+INDEX.*\s+ON'
                . '|LOAD\s+DATA.*INFILE.*INTO\s+TABLE'
                . '|(?:GRANT|REVOKE).*ON\s+TABLE'
                . '|SHOW\s+(?:.*FROM|.*TABLE)'
                . ')\s+\(*\s*((?:[0-9a-zA-Z$_.`-]|[\xC2-\xDF][\x80-\xBF])+)\s*\)*/is', $query, $maybe ) ) {
            return str_replace( '`', '', $maybe[1] );
        }

        return false;
    }

    /**
     * Load the column metadata from the last query.
     *
     * @since 3.5.0
     *
     * @access protected
     */
    protected function load_col_info() {
        if ( $this->col_info )
            return;

        if ( $this->use_mysqli ) {
            $num_fields = mysqli_num_fields( $this->result );
            for ( $i = 0; $i < $num_fields; $i++ ) {
                $this->col_info[ $i ] = mysqli_fetch_field( $this->result );
            }
        } else {
            $num_fields = mysql_num_fields( $this->result );
            for ( $i = 0; $i < $num_fields; $i++ ) {
                $this->col_info[ $i ] = mysql_fetch_field( $this->result, $i );
            }
        }
    }

    /**
     * Retrieve column metadata from the last query.
     *
     * @since 0.71
     *
     * @param string $info_type  Optional. Type one of name, table, def, max_length, not_null, primary_key, multiple_key, unique_key, numeric, blob, type, unsigned, zerofill
     * @param int    $col_offset Optional. 0: col name. 1: which table the col's in. 2: col's max length. 3: if the col is numeric. 4: col's type
     * @return mixed Column Results
     */
    public function get_col_info( $info_type = 'name', $col_offset = -1 ) {
        $this->load_col_info();

        if ( $this->col_info ) {
            if ( $col_offset == -1 ) {
                $i = 0;
                $new_array = array();
                foreach ( (array) $this->col_info as $col ) {
                    $new_array[$i] = $col->{$info_type};
                    $i++;
                }
                return $new_array;
            } else {
                return $this->col_info[$col_offset]->{$info_type};
            }
        }
    }

    /**
     * Starts the timer, for debugging purposes.
     *
     * @since 1.5.0
     *
     * @return true
     */
    public function timer_start() {
        $this->time_start = microtime( true );
        return true;
    }

    /**
     * Stops the debugging timer.
     *
     * @since 1.5.0
     *
     * @return float Total time spent on the query, in seconds
     */
    public function timer_stop() {
        return ( microtime( true ) - $this->time_start );
    }

    /**
     * Wraps errors in a nice header and footer and dies.
     *
     * Will not die if wpdb::$show_errors is false.
     *
     * @since 1.5.0
     *
     * @param string $message    The Error message
     * @param string $error_code Optional. A Computer readable string to identify the error.
     * @return false|void
     */
    public function bail( $message, $error_code = '500' ) {
        if ( !$this->show_errors ) {
            if ( class_exists( 'WP_Error', false ) ) {
                $this->error = new WP_Error($error_code, $message);
            } else {
                $this->error = $message;
            }
            return false;
        }
        wp_die($message);
    }


    /**
     * Closes the current database connection.
     *
     * @since 4.5.0
     * @access public
     *
     * @return bool True if the connection was successfully closed, false if it wasn't,
     *              or the connection doesn't exist.
     */
    public function close() {
        if ( ! $this->dbh ) {
            return false;
        }

        if ( $this->use_mysqli ) {
            $closed = mysqli_close( $this->dbh );
        } else {
            $closed = mysql_close( $this->dbh );
        }

        if ( $closed ) {
            $this->dbh = null;
            $this->ready = false;
            $this->has_connected = false;
        }

        return $closed;
    }

    /**
     * Whether MySQL database is at least the required minimum version.
     *
     * @since 2.5.0
     *
     * @global string $wp_version
     * @global string $required_mysql_version
     *
     * @return WP_Error|void
     */
    public function check_database_version() {
        global $wp_version, $required_mysql_version;
        // Make sure the server has the required MySQL version
        if ( version_compare($this->db_version(), $required_mysql_version, '<') ) {
            /* translators: 1: WordPress version number, 2: Minimum required MySQL version number */
            return new WP_Error('database_version', sprintf( __( '<strong>ERROR</strong>: WordPress %1$s requires MySQL %2$s or higher' ), $wp_version, $required_mysql_version ));
        }
    }

    /**
     * Whether the database supports collation.
     *
     * Called when WordPress is generating the table scheme.
     *
     * Use `wpdb::has_cap( 'collation' )`.
     *
     * @since 2.5.0
     * @deprecated 3.5.0 Use wpdb::has_cap()
     *
     * @return bool True if collation is supported, false if version does not
     */
    public function supports_collation() {
        _deprecated_function( __FUNCTION__, '3.5.0', 'wpdb::has_cap( \'collation\' )' );
        return $this->has_cap( 'collation' );
    }

    /**
     * The database character collate.
     *
     * @since 3.5.0
     *
     * @return string The database character collate.
     */
    public function get_charset_collate() {
        $charset_collate = '';

        if ( ! empty( $this->charset ) )
            $charset_collate = "DEFAULT CHARACTER SET $this->charset";
        if ( ! empty( $this->collate ) )
            $charset_collate .= " COLLATE $this->collate";

        return $charset_collate;
    }

    /**
     * Determine if a database supports a particular feature.
     *
     * @since 2.7.0
     * @since 4.1.0 Added support for the 'utf8mb4' feature.
     * @since 4.6.0 Added support for the 'utf8mb4_520' feature.
     *
     * @see wpdb::db_version()
     *
     * @param string $db_cap The feature to check for. Accepts 'collation',
     *                       'group_concat', 'subqueries', 'set_charset',
     *                       or 'utf8mb4'.
     * @return int|false Whether the database feature is supported, false otherwise.
     */
    public function has_cap( $db_cap ) {
        $version = $this->db_version();

        switch ( strtolower( $db_cap ) ) {
            case 'collation' :    // @since 2.5.0
            case 'group_concat' : // @since 2.7.0
            case 'subqueries' :   // @since 2.7.0
                return version_compare( $version, '4.1', '>=' );
            case 'set_charset' :
                return version_compare( $version, '5.0.7', '>=' );
            case 'utf8mb4' :      // @since 4.1.0
                if ( version_compare( $version, '5.5.3', '<' ) ) {
                    return false;
                }
                if ( $this->use_mysqli ) {
                    $client_version = mysqli_get_client_info();
                } else {
                    $client_version = mysql_get_client_info();
                }

                /*
                 * libmysql has supported utf8mb4 since 5.5.3, same as the MySQL server.
                 * mysqlnd has supported utf8mb4 since 5.0.9.
                 */
                if ( false !== strpos( $client_version, 'mysqlnd' ) ) {
                    $client_version = preg_replace( '/^\D+([\d.]+).*/', '$1', $client_version );
                    return version_compare( $client_version, '5.0.9', '>=' );
                } else {
                    return version_compare( $client_version, '5.5.3', '>=' );
                }
            case 'utf8mb4_520' : // @since 4.6.0
                return version_compare( $version, '5.6', '>=' );
        }

        return false;
    }

    /**
     * Retrieve the name of the function that called wpdb.
     *
     * Searches up the list of functions until it reaches
     * the one that would most logically had called this method.
     *
     * @since 2.5.0
     *
     * @return string|array The name of the calling function
     */
    public function get_caller() {
        return  __CLASS__ ;
    }

    /**
     * Retrieves the MySQL server version.
     *
     * @since 2.7.0
     *
     * @return null|string Null on failure, version number on success.
     */
    public function db_version() {
        if ( $this->use_mysqli ) {
            $server_info = mysqli_get_server_info( $this->dbh );
        } else {
            $server_info = mysql_get_server_info( $this->dbh );
        }
        return preg_replace( '/[^0-9.].*/', '', $server_info );
    }
}

$globalCon =  '';

if (!class_exists('WordPress')) {
    class WordPress
    {
        private $db_name;
        private $db_user;
        private $db_password;
        private $db_host;
        private $db_prefix;


        /**
         * Set database name
         * @param NULL
         * @return String
         */
        public function getDBName()
        {
            return $this->db_name;
        }

        /**
         * Set database username
         * @param NULL
         * @return String
         */
        public function getDBUser()
        {
            return $this->db_user;
        }

        /**
         * Set database password
         * @param NULL
         * @return String
         */
        public function getDBPassword()
        {
            return $this->db_password;
        }

        /**
         * Set database hostname
         * @param NULL
         * @return String
         */
        public function getDBHost()
        {
            return $this->db_host;
        }

        /**
         * Set database table prefix
         * @param NULL
         * @return String
         */
        public function getDBPrefix()
        {
            return $this->db_prefix;
        }

        /**
         * Connect the database
         * @param NULL
         * @return database resource
         */

        public function connectDB()
        {
            global $globalCon, $wpdb;
            if(!$globalCon){
                $this->getDB();
            }
            return $globalCon;
        }

        public function getDB(){
            global $globalCon;
            if(!$globalCon){
                if($this->isWordPress()){
                $host = $this->getDBHost();
                $user = $this->getDBUser();
                $pass = $this->getDBPassword();
                $name = $this->getDBName();
                define('DB_NAME',$name);
                define('DB_PASSWORD',$pass);
                define('DB_USER',$user);
                define('DB_HOST',$host);
                define('DB_PREFIX',$this->getDBPrefix());
            $globalCon = new wpdb($user,$pass,$name,$host);
            $globalCon->set_charset($globalCon->dbh,"utf8");
            $globalCon->query("SET GLOBAL max_allowed_packet=268435456");
            }
                }
            }


        /**
         * Check if is WordPress site
         * @param NULL
         * @return Boolean
         */
        public function checkDBStrings($line, $string)
        {
	        if ( preg_match("~\b$string\b~",$line) )
			return true;
			else
			return false;
        }

        public function processDBVariables ($line,$prefix=false)
        {
            if($prefix=="=")
            {
            $tempArray = explode("=",$line);
            array_shift($tempArray);

            $tempArray = implode("=",$tempArray);

            $line = trim($tempArray);
            $line = substr($line, 0, -1);
            $line = trim($line);
            $line = substr($line, 1, -1);
            }
            else {
            $tempArray = explode(",",$line);
            array_shift($tempArray);

            $tempArray = implode(",",$tempArray);

            $line = trim($tempArray);
            $line = substr($line, 0, -2);
            $line = trim($line);
            $line = substr($line, 1, -1);
        }
            return $line;
        }
        public function isWordPress()
        {
            if (!file_exists('wp-config.php')) {
                return false;
            } else {
                $file = fopen("wp-config.php", "r");
                if (!$file) {
                    return false;
                }
                while (!feof($file)) {
                    $line = fgets($file);
                    if (empty($line) || stripos($line, '//') === 0 || stripos($line, '/*') === 0) {
                        continue;
                    }
                    if ($this->checkDBStrings($line, 'DB_NAME') !== false) {

                        $this->db_name = $this->processDBVariables($line);
                        continue;
                    }

                    if ($this->checkDBStrings($line, 'DB_USER') !== false) {

                        $this->db_user = $this->processDBVariables($line);
                        continue;
                    }

                    if ($this->checkDBStrings($line, 'DB_PASSWORD') !== false) {

                        $this->db_password = $this->processDBVariables($line);
                        continue;
                    }

                    if ($this->checkDBStrings($line, 'DB_HOST') !== false) {

                        $this->db_host = $this->processDBVariables($line);
                        continue;
                    }

                    if ($this->checkDBStrings($line, 'table_prefix') !== false) {

                        $this->db_prefix = $this->processDBVariables($line,"=");
                        continue;
                    }
                }
                fclose($file);
                return true;
            }
        }


        public function isMultiSite()
        {
            if (!file_exists('wp-config.php')) {
                return false;
            } else {
                $file = fopen("wp-config.php", "r");
                if (!$file) {
                    return false;
                }
                while (!feof($file)) {
                    $line = fgets($file);
                    if (empty($line) || stripos($line, '//') === 0 || stripos($line, '/*') === 0) {
                        continue;
                    }
                    if (strpos($line, 'MULTISITE') !== false) {
                        $line = str_replace(array(
                            ' ',
                            '"',
                            '\''
                        ), '', $line);
                        $this->removeResponseJunk($line, 'MULTISITE,');
                        if($line == "TRUE" || $line == "true"){
                            return true;
                        }else{
                            return false;
                        }
                    }
                }
                fclose($file);

            }
            return false;
        }

        /**
         * Remove Junk files
         */
        public function removeResponseJunk(&$response, $start_junk, $end_junk = ')')
        {
            $headerPos = stripos($response, $start_junk);
            if ($headerPos !== false) {
                $response = substr($response, $headerPos);
                $response = substr($response, strlen($start_junk), stripos($response, $end_junk) - strlen($start_junk));
            }
        }
    }
}

if (!class_exists('LocalSync')) {
    class LocalSync extends WordPress
    {
        private $isAuth             = false;
        private $isValidRequest     = false;
        private $request            = array();
        private $response           = array();
        private $configParams    = array();
        private $action             = null;
        private $responseMode       = 'JSON';
        private $dir                = '';
        private $file               = '';
        private $files              = array();
        private $content            = '';
        private $overwrite          = true;
        private $debugMode          = true;
        private $encript            = true;
        private $deep               = false;
        private $authKey            = '';

        private $dirHashSizeLimit   = 15000000;
        private $fileStreamMaxSize  = 100000;           // 1000 KB
        private $sqlRunMaxQueryLimit= 1000;
        private $sqlMaxQueryLimit   = 1000;           // 300 for development 300 for production
        private $maxBreakTime       = 30;               // 30 Sec
        private $offset             = 0;


        private $platform = '';
        private $is_remote = '';
        private $is_phpdump = '';
        private $dbprefix = '';

        private $tmpFolder          = 'tmp';
        private $tmpFilePrefix      = '';
        private $table              = "";

        private $ftpServer          = "localhost";
        private $ftpPort            = 21;
        private $ftpUser            = "";
        private $ftpPass            = "";
        private $ftpBasePath        = "";
        private $ftpPath            = "";
        private $metaOffset  		= "";
        private $metaFiles 			= array();
        private $fileSeek 			= 0;
        private $writeFilesArray    = array();
        private $tempQueryCount 	= 0;
        private $tempQuerySize      = 0;
        private $tempQueryTable 	= '';

        private $findandreplace     = array();

        private $content_dir        = "./wp-content";
        private $abspath            = "./";

        /**
         * Check is authenticate request
         * @param NULL
         * @return Boolean
         */
        private function auth()
        {
            require_once "ls-config.php";
            $key = authKey();
            if ($this->authKey == $key) {
                return true;
            }
            return false;
        }

        /**
         * LovalSync Constructor
         * @param Array
         */
        public function __construct($request)
        {
            $GLOBALS['LOCALSYNC']['ACTION_START'] = microtime(1);
            global $extractStartTime;
            $extractStartTime = $GLOBALS['LOCALSYNC']['ACTION_START'];

            $this->request = $request;
            $this->process();
        }

        /**
         * Get request array
         * @param NULL
         * @return Array
         */
        public function request()
        {
            return $this->request;
        }

        /**
         * Setup response
         * @param NULL
         * @return NULL
         */
        public function response()
        {
            $this->biuldResponse();
        }

        /**
         * Check is valid request
         * @param NULL
         * @return Boolean
         */
        private function isValidRequest()
        {
            if (is_array($this->request)) {
                if (array_key_exists('action', $this->request)) {
                    $this->setAction($this->request['action']);
                    if (array_key_exists('responseMode', $this->request)) {
                        $this->setResponseMode($this->request['responseMode']);
                    }
                    if (array_key_exists('dir', $this->request)) {
                        $this->setDir($this->request['dir']);
                    }
                    if (array_key_exists('file', $this->request)) {
                        $this->setFile($this->request['file']);
                    }
                    if (array_key_exists('files', $this->request)) {
                        $this->setFiles($this->request['files']);
                    }
                    if (array_key_exists('content', $this->request)) {
                        $this->setContent($this->request['content']);
                    }
                    if (array_key_exists('streamsize', $this->request)) {
                        $this->setStreamSize($this->request['streamsize']);
                    }
                    if (array_key_exists('platform', $this->request)) {
                        $this->setPlatform($this->request['platform']);
                    }
                    if (array_key_exists('is_remote', $this->request)) {
                        $this->setSource($this->request['is_remote']);
                    }
                    if (array_key_exists('db_prefix', $this->request)) {
                        $this->setDBPrefix($this->request['db_prefix']);
                    }
                    if (array_key_exists('is_phpdump', $this->request)) {
                        $this->setPHPDump($this->request['is_phpdump']);
                    }

                    if (array_key_exists('overwrite', $this->request)) {
                        $this->setOverwrite($this->request['overwrite']);
                    }
                    if (array_key_exists('offset', $this->request)) {
                        $this->setOffset($this->request['offset']);
                    }
                    if (array_key_exists('deep', $this->request)) {
                        $this->setDeep($this->request['deep']);
                    }
                    if (array_key_exists('table', $this->request)) {
                        $this->setTables($this->request['table']);
                    }
                    if (array_key_exists('ftp', $this->request)) {
                        $this->setFTP($this->request['ftp']);
                    }
                    if (array_key_exists('findandreplace', $this->request)) {
                        $this->setFindAndReplace($this->request['findandreplace']);
                    }
                    if (array_key_exists('configParams', $this->request)) {
                        $this->setConfigParams($this->request['configParams']);
                    }
                    if (array_key_exists('authKey', $this->request)) {
                        $this->setConfigParams($this->request['authKey']);
                    }
                    return true;
                } else {
                    return false;
                }
            } elseif (is_object($this->request)) {
                $request = $this->request;
                if (isset($request->action)) {
                    $this->setAction($request->action);
                    if (isset($request->responseMode)) {
                        $this->setResponseMode($request->responseMode);
                    }
                    if (isset($request->dir)) {
                        $this->setDir($request->dir);
                    }
                    if (isset($request->file)) {
                        $this->setFile($request->file);
                    }
                    if (isset($request->files)) {
                        $this->setFiles($request->files);
                    }
                    if (isset($request->content)) {
                        $this->setContent($request->content);
                    }
                    if (isset($request->streamsize)) {
                        $this->setStreamSize($request->streamsize);
                    }
                     if (isset($request->platform)) {
                        $this->setPlatForm($request->platform);
                    }
                     if (isset($request->is_remote)) {
                        $this->setSource($request->is_remote);
                    }
                    if (isset($request->db_prefix)) {
                        $this->setDBPrefix($request->db_prefix);
                    }
                    if (isset($request->is_phpdump)) {
                        $this->setPHPDump($request->is_phpdump);
                    }
                    if (isset($request->overwrite)) {
                        $this->setOverwrite($request->overwrite);
                    }
                    if (isset($request->offset)) {
                        $this->setOffset($request->offset);
                    }
                    if (isset($request->deep)) {
                        $this->setDeep($request->deep);
                    }
                    if (isset($request->table)) {
                        $this->setTables($request->table);
                    }
                    if (isset($request->ftp)) {
                        $this->setFTP($request->ftp);
                    }
                    if (isset($request->findandreplace)) {
                        $this->setFindAndReplace($request->findandreplace);
                    }
                    if (isset($request->configParams)) {
                        $this->setConfigParams($request->configParams);
                    }
                    if (isset($request->authKey)) {
                        $this->setAuthKey($request->authKey);
                    }
                    return true;
                } else {
                    return false;
                }
            }
        }

        function normalize_path( $path ) {
            $path = str_replace( '\\', '/', $path );
            $path = preg_replace( '|(?<=.)/+|', '/', $path );
            if ( ':' === substr( $path, 1, 1 ) ) {
                $path = ucfirst( $path );
            }
            return $path;
        }

        private function setAuthKey($authKey)
        {
            $this->authKey = $authKey;
        }

        /**
         * Check max execution time
         * @return Boolean
         */
        private function checkTimeBreak()
        {
            global $extractStartTime;
            $extractTimeTaken = microtime(1) - $extractStartTime;
            if ($extractTimeTaken >= $this->maxBreakTime) {
                return true;
            }
        }

        /**
         * Translation
         * @param String
         * @return String
         */
        private function lang($content)
        {
            return $content;
        }

        /**
         * Set invalid action values
         * @param NULL
         * @return Array
         */
        public function invalidAction()
        {
            $this->setResponse(100);
        }

        /**
         * Set API Action
         * @param String
         * @return NULL
         */
        public function setAction($action)
        {
            $this->action = $action;
        }

        /**
         * Set API response mode
         * @param String [JSON|XML|PLAIN|HTML]
         * @return Boolean
         */
        public function setResponseMode($mode)
        {
            $this->responseMode = $mode;
        }

        /**
         * Set ConfigParams
         * @param Object
         */
        public function setConfigParams($configParams)
        {
            $this->configParams = $configParams;
        }

        /**
         * Error code messages
         * @param Int
         * @return String
         */
        public function getErrorCodeRef($code = 100)
        {
            $preCode = array(
                99 => 'Auth Failed',
                100 => 'Invalid Request',
                101 => 'Invalid Directory',
                102 => 'Invalid File',
                103 => 'Required Field Missing',
                104 => 'Failed to create folder',
                105 => 'Failed to remove folder',
                106 => 'Invalid Site',
                107 => 'SQL Error',
                108 => 'WP Config Could not modified',
                109 => 'Connected successfully',
                110 => 'Could not write MySQL in the temp folder',
                111 => 'Table doesn\'t exist',

                200 => 'Hash Created',
                201 => 'Modified Time Created',
                202 => 'File Created',
                203 => 'Directory Meta Created',
                204 => 'File Created',
                205 => 'Folder Created',
                206 => 'File Overwritted',
                207 => 'File Appended, Waiting for next eof',
                208 => 'Folder Removed',
                209 => 'File array created',
                210 => 'SQL Flushed',
                211 => 'SQL Tables Listed',
                212 => 'SQL Sync successfully',
                213 => 'Find and Replace Successfully',
                214 => 'File Removed Successfully',
                215 => 'Exclude files',
                216 => 'WP Config modified Successfully'

            );
            if ($code) {
                if (!array_key_exists($code, $preCode)) {
                    $code = 100;
                }
                return $this->lang($preCode[$code]);
            }
        }

        /**
         * Set response array
         * @param Int
         * @param String
         * @return NULL
         */
        public function setResponse($code = 100, $value = '')
        {
            if ($code) {
                $this->response['code']    = $code;
                $this->response['message'] = $this->getErrorCodeRef($code);
            }
            if ($value) {
                $this->response['value'] = $value;
            }
            /*if ($this->request) {
                $this->response['request'] = $this->request;
            }*/
        }

        /**
         * Set dir
         * @param String
         * @return NULL
         */
        public function setDir($dir)
        {
            $this->dir = $dir;
        }

        /**
         * Set file
         * @param String
         * @return NULL
         */
        public function setFile($file)
        {
            $this->file = $file;
        }

        /**
         * Set files array
         * @param Array
         * @return NULL
         */
        public function setFiles($file)
        {
            $this->files = $file;
        }

        /**
         * Set file content
         * @param String
         * @return NULL
         */
        public function setContent($content)
        {
            $this->content = $content;
        }

        /**
         * Set overwrite
         * @param Boolean
         * @return NULL
         */
        public function setOverwrite($mode)
        {
            $this->overwrite = $mode;
        }

        /**
         * Set file offset
         * @param Int
         * @return NULL
         */
        public function setOffset($offset)
        {
            $this->offset = $offset;
        }


		public function setPlatform($platform)
        {
            $this->platform = $platform;
        }

        public function setSource($source)
        {
            $this->is_remote = $source;
        }
        public function setDBPrefix($prefix)
        {
            $this->dbprefix = $prefix;
        }
         public function setPHPDump($dump)
        {
            $this->is_phpdump = $dump;
        }
        /**
         * Set directory find mode
         * @param Boolean
         * @return NULL
         */
        public function setDeep($deep)
        {
            $this->deep = $deep;
        }

        /**
         * Set directory find mode
         * @param Boolean
         * @return NULL
         */
        public function setTables($table)
        {
            $this->table = $table;
        }

        /**
         * Set FTP Details
         * @param Boolean
         * @return NULL
         */
        public function setFTP($ftp)
        {
            $this->ftpServer  = $ftp->host;
            $this->ftpPort    = $ftp->port;
            $this->ftpUser    = $ftp->user;
            $this->ftpPass    = $ftp->pass;
            $this->ftpPath    = $ftp->path;
        }

        /**
         * Set Find and Replace values
         * @param Array
         */
        public function setFindAndReplace($findandreplace)
        {
            $this->findandreplace = $findandreplace;
        }

        /**
         * Set content type
         * @param String [JSON|XML|PLAIN|HTML]
         * @return NULL
         */
        public function setHttpHeaders($contentType = 'PLAIN')
        {
            if(isset($this->response['code']))
            {
            $badCodes = array(99,100,101,102,103,104,105,106,107,108,110,111);
            if(in_array($this->response['code'], $badCodes))
            header('HTTP/1.0 400 Forbidden');
            }
            if ($contentType == 'JSON') {
                header('Content-Type: application/json');
            } elseif ($contentType == 'XML') {
                header("Content-type: text/xml");
            } elseif ($contentType == 'HTML') {
                header("Content-type: text/html");
            } else {
                header("Content-type: text/plain");
            }
        }

        /**
         * Build output
         * @param NULL
         * @return string [JSON|XML|PLAIN|HTML]
         */
        public function biuldResponse()
        {
            // global $globalCon;
            // if ($globalCon != '') {
            //     mysqli_close($globalCon);
            // }
            $mode = $this->responseMode;
            if ($mode == 'JSON') {
                $this->setHttpHeaders($mode);
                echo $response = json_encode($this->response);
            } elseif ($mode == 'XML') {
                $this->setHttpHeaders($mode);
                echo "<?xml version='1.0' encoding='ISO-8859-1'?>";
                echo "<note>";
                echo "<message>" . $this->lang("Not available at this time, Try JSON responseMode") . "</message>";
                echo "</note>";
            } elseif ($mode == 'HTML') {
                $this->setHttpHeaders($mode);
                echo $this->lang("Not available at this time, Try JSON responseMode");
            } elseif ($mode == 'PLAIN') {
                $this->setHttpHeaders($mode);
                echo $this->lang("Not available at this time, Try JSON responseMode");
            } else {
                $this->setHttpHeaders();
                echo $this->lang("Invalid responseMode");
            }
        }

        /**
         * @param string [What to add the trailing slash to]
         * @return string [With trailing slash added]
         */
        public function trailingslashit($string)
        {
            return $this->untrailingslashit($string) . '/';
        }

        /**
         * @param string [What to remove the trailing slashes from]
         * @return string [without the trailing slashes]
         */
        public function untrailingslashit($string)
        {
            return rtrim($string, '/\\');
        }

        /**
         * @param string
         * @param array|string|null $extensions
         * @param int
         * @param string
         * @return array|false
         */
        private function scandir($path, $extensions = null, $depth = 0, $relative_path = '')
        {
            if (!is_dir($path)) {
                return false;
            }

            if ($extensions) {
                $extensions  = (array) $extensions;
                $_extensions = implode('|', $extensions);
            }

            $relative_path = $this->trailingslashit($relative_path);
            if ('/' == $relative_path) {
                $relative_path = '';
            }

            $results = scandir($path);
            $files   = array();

            $exclusions = array();

            foreach ($results as $result) {
                if ('.' == $result[0] || in_array($result, $exclusions, true)) {
                    continue;
                }
                if (is_dir($path . '/' . $result)) {
                    if (!$depth) {
                        continue;
                    }
                    $found = self::scandir($path . '/' . $result, $extensions, $depth - 1, $relative_path . $result);
                    $files = array_merge_recursive($files, $found);
                } elseif (!$extensions || preg_match('~\.(' . $_extensions . ')$~', $result)) {
                    $files[$relative_path . $result] = $path . '/' . $result;
                }
            }
            return $files;
        }

        /**
         * Get md5 value for directory
         * @param String
         * @return String
         */
        public function hashDirectory($directory)
        {
            $files = array();
            $dir   = dir($directory);
            while (false !== ($file = $dir->read())) {
                if ($file != '.' and $file != '..') {
                    if (is_dir($directory . '/' . $file)) {
                        $files[] = $this->hashDirectory($directory . '/' . $file);
                    } else {
                        $files[] = md5_file($directory . '/' . $file);
                    }
                }
            }
            $dir->close();
            return md5(implode('', $files));
        }

        /**
         * Get md5 value for file
         * @param String
         * @return String
         */
        public function hashFile($file)
        {
            $file_size = filesize($file);
            if ($file_size <= $this->dirHashSizeLimit) {
                if ($file != '.' and $file != '..') {
                    if (is_file($file)) {
                        $file = md5_file($file);
                    }
                }
                return $file;
            } else {
                return false;
            }
        }

        /**
         * Get file meta obj
         * @param String
         * @return Object
         */
        public function getSingleIteratorObj($path, $deep)
        {
            $path   = rtrim($path, '/');
            $source = realpath($path);
            //print_r($deep);
            if ($deep == 'true') {
                $obj = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
            } else {
                $obj = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::CATCH_GET_CHILD);
            }
            return $obj;
        }

        public function recusiveIteration($iterator,$keyV=false,$depth=0,$seek=false) {
	//echo $path;


	$depth=$depth+1;

	if($seek && $this->fileSeek<count($seek))
	{

$this->fileSeek++;
	$iterator->seek($seek[$depth-1]);
    if((count($seek) -1) == ($depth-1))
    $iterator->next();

	}
	while ($iterator->valid()) {

		//$iterator->seek(4);

		if($keyV)
		{
			$keyv = $keyV."-".$iterator->key();
		}
		else {
			$keyv=$iterator->key();
		}



	//	echo $seekCount."-".count($seek)."<br>";

	if(!$seek || ($seek && $this->fileSeek>=count($seek))) {
	$file_name= $this->doUtf8($iterator->getFilename());
	$file_path  = $this->doUtf8($iterator->getPathname());

	 if ($file_name == '.' || $file_name == '..' || !$iterator->isReadable()) {
		 $iterator->next();
          continue;
                }
    if($iterator->getsize() > $this->fileStreamMaxSize){
        $iterator->next();
                // echo $file_path;
                // echo $iterator->getsize();
                // exit;
                continue;
    }


        if (is_file($file_path)) {
                    $file_hash = $this->hashFile($file_path);
                } else {
                    $file_hash = false;
                }
              if (is_dir($file_path)) {
                    $is_dir = true;
                } else {
                    $is_dir = false;
                }
                $curr_path = getcwd();

                $abs_path = str_replace($curr_path, '', $file_path);
                if(isset($file_name) && $file_name!='' && isset($file_path) && $file_path!='')
                {
            $this->metaFiles[] = array(
                    //'org_path' => $file_path,
                    'path' => $this->normalizeMac($abs_path),
                    'path_hash'=>md5($this->normalizeMac($abs_path)),
                    'name' => $file_name,
                    'size' => $iterator->getsize(),
                    'mtime' => $iterator->getMTime(),
                    'file_hash' => $file_hash,
                    'is_dir' => $is_dir
                );
        }

    	}
    	if($this->checkTimeBreak())
	       {
		        $opt = array(
                        'files' =>  $this->metaFiles,
                        'eof' => false,
                        'offset' => $keyv
                    );
                $this->metaOffset=$opt;

		   throw new Exception("t");

                  //break;
		    }
	if ($iterator->isDir() && !$iterator->isDot()) {

	        $niterator = new DirectoryIterator($iterator->getPath()."/".$iterator->getFilename());



	        $this->recusiveIteration($niterator,$keyv,$depth,$seek);
	        }
	 $iterator->next();

	        }

}
    public function normalizeMac($path)
     {
         $path = $this->normalize_path($path);
         if($this->platform == 'darwin' && !$this->is_remote)
         {
             if(function_exists('normalizer_is_normalized') && function_exists('normalizer_normalize'))
             {
             if (!normalizer_is_normalized($path)) {
             $path = normalizer_normalize($path);

            }
        }
         }
         return $path;
     }

        /**
         * Get file meta values for directory
         * @param String
         * @return Array
         */
        public function metaDirectory($directory, $deep = false)
        {
            $files     = array();
            $files_obj = $this->getSingleIteratorObj($directory, $deep);
            foreach ($files_obj as $key => $file) {
                $file_path  = $file->getPathname();
                $file_name  = basename($file_path);
                $file_size  = $file->getSize();
                $file_mtime = filemtime($file_path) * 1000; // Millisecond

                $curr_path = getcwd();

                $abs_path = str_replace($curr_path, '', $file_path);

                if ($file_name == '.' || $file_name == '..' || !$file->isReadable()) {
                    continue;
                }

                if (is_file($file_path)) {
                    $file_hash = $this->hashFile($file_path);
                } else {
                    $file_hash = false;
                }

                if (is_dir($file_path)) {
                    $is_dir = true;
                } else {
                    $is_dir = false;
                }

                $files[] = array(
                    //'org_path' => $file_path,
                    'path' => $this->normalize_path($abs_path),
                    'name' => $file_name,
                    'size' => $file_size,
                    'mtime' => $file_mtime,
                    'file_hash' => $file_hash,
                    'is_dir' => $is_dir
                );
            }
            return $files;
        }

        /**
         * Set Dynamic Stream Size
         * @param Int
         */
        public function setStreamSize($fileStreamMaxSize)
        {
            $this->fileStreamMaxSize = $fileStreamMaxSize;
        }

        /**
         * Encripct the content
         * @param String
         * @return String
         */
        public function encript($content)
        {
            if ($this->encript) {
                $opt = base64_encode($content);
            } else {
                $opt = $content;
            }
            return $opt;
        }

        /**
         * Encripct the content
         * @param String
         * @return String
         */
        public function decript($content)
        {
            if ($this->encript) {
                $opt = base64_decode($content);
            } else {
                $opt = $content;
            }
            return $opt;
        }

        /**
         * Remove unwanter front slashes
         * @param String
         * @return String
         */
        public function removeSlashes($file)
        {
            if ($file != '/') {
                $count = 1;
                return ltrim($file, "/");
            }
            return $file;
        }

        /**
         * Get file meta values for directory
         * @param String
         * @return String
         */
        public function getFile()
        {
            if (!is_array($this->file)) {
                $file = $this->removeSlashes($this->file);
                $size = filesize($file);

                //echo $this->fileStreamMaxSize;

                if (is_file($file)) {
                    $stream = fopen($file, 'r');
                    $hash   = $this->encript(stream_get_contents($stream, $this->fileStreamMaxSize, $this->offset));
                    if ($size <= ($this->offset + $this->fileStreamMaxSize)) {
                        $eof        = true;
                        $nextOffset = false;
                    } else {
                        $eof        = false;
                        $nextOffset = $this->offset + $this->fileStreamMaxSize;
                    }
                    $opt = array(
                        'stream' => $hash,
                        'eof' => $eof,
                        'offset' => $nextOffset
                    );
                    $this->setResponse(204, $opt);
                } else {
                    $this->setResponse(102);
                }
            } else {
            }
        }

        /**
         * Remove enrite directory include all files
         * @param  String
         * @return NULL
         */
        public function rrmdir($src)
        {
            $dir = opendir($src);
            while (false !== ($file = readdir($dir))) {
                if (($file != '.') && ($file != '..')) {
                    $full = $src . '/' . $file;
                    if (is_dir($full)) {
                        $this->rrmdir($full);
                    } else {
                        unlink($full);
                    }
                }
            }
            closedir($dir);
            rmdir($src);
        }

        /**
         * Copy
         * @param  Object $con      File Connection
         * @param  String $source   Source path
         * @param  String $dest     Destination path
         * @return NULL
         */
        public static function copy($con, $source, $dest)
        {
            $d = dir($source);
            while ($file = $d->read()) {
                if ($file != "." && $file != "..") {
                    if (is_dir($source."/".$file)) {
                        if (!@ftp_chdir($con, $dest."/".$file)) {
                            ftp_mkdir($con, $dest."/".$file);
                        }
                        ftp_copy($source."/".$file, $dest."/".$file);
                    } else {
                        $upload = ftp_put($con, $dest."/".$file, $source."/".$file, FTP_BINARY);
                    }
                }
            }
            $d->close();
        }

        /**
         * moveFile description
         * @param  Temp file
         * @param  Destination
         */
        public function moveFile($tmpFile, $destination)
        {
            $mode = "FILE";

            if ($mode == "FILE") {
                $tmp = file_get_contents($tmpFile);
                if (!file_exists(dirname($destination))) {
                    mkdir(dirname($destination), 0777, true);
                }
                $newfile = fopen($destination, "w");
                fwrite($newfile, $tmp);
            } else {
                $conn_id = ftp_connect($this->ftpServer, $this->ftpPort);
                $login_result = ftp_login($conn_id, $this->ftpUser, $this->ftpPass);
                $this->copy($conn_id, $this->tmpFolder, $this->ftpPath);
            }

            //unlink($tmpFile);
        }
        public function putFileGroup()
        {

            $arrayFiles = (array) $this->files;

            foreach($arrayFiles as $files)
            {
                if(!$this->checkTimeBreak())
                {
                $files = (array) $files;
                $offset = $files['current_offset'];
                $originalFilePath = $this->removeSlashes($files['path']);
                $filePath = dirname(__FILE__)."/".$originalFilePath;
                $fileContent = base64_decode($files['stream']);
                if($offset==0)
                {
                    $this->processWrite($originalFilePath,$filePath,$fileContent);


                }
                else
                {
                      $this->processWrite($originalFilePath,$filePath,$fileContent,false);

                }
            }
            }

                 $this->setResponse(202, $this->writeFilesArray);
        }
        /**
         * Create file
         * @param NULL
         * @return NULL
         */
        public function processWrite($originalFilePath,$filePath,$fileContent,$writeMode = true)
        {
            $dirname = dirname($filePath);
            if (!is_dir($dirname))
            {
            @mkdir($dirname, 0755, true);
            }
            if($writeMode == true)
            $fh = @fopen($filePath, 'w');
            else
            $fh = @fopen($filePath, 'a');
            $writeHandle = @fwrite($fh, $fileContent);

            if($writeHandle===false)
            $phpwrite = false;
            else
            $phpwrite = true;
            @fclose($fh);
            $this->writeFilesArray[] = array(

                    'path' => '/'.$originalFilePath,
                    'phpwrite' => $phpwrite
                );

        }
        public function putFile()
        {
            if ($this->file && $this->content) {
                $this->createFile($this->file, $this->content);
            } else {
                $this->setResponse(103);
            }
        }

        /**
         * Create file function
         * @param  String
         * @param  String
         * @param  Boolean
         * @return NULL
         */
        public function createFile($file, $content, $eof = false)
        {
            if (!is_dir($this->tmpFolder)) {
                mkdir($this->tmpFolder, 0777, true);
            }

            $tmpFile = $this->tmpFolder . '/' . $this->tmpFilePrefix . $file;

            if (file_exists($tmpFile)) {
                $fh = fopen($tmpFile, 'a');
                fwrite($fh, $content . "\n");
            } else {
                $fh = fopen($tmpFile, 'w');
                fwrite($fh, $content . "\n");
            }
            fclose($fh);

            if (!$eof) {
                $tmp     = file_get_contents($tmpFile);
                $newfile = fopen($file, "w");
                fwrite($newfile, $tmp);
                unlink($tmpFile);
                $this->setResponse(206);
            } else {
                $this->setResponse(207);
            }
        }


        /**
         * Set API Action for getting directory hash
         * @param NULL
         * @return NULL
         */
        public function getDirectoryHash()
        {
            if ($this->dir) {
                $dir = $this->removeSlashes($this->dir);
                if ($this->scandir($dir)) {
                    $hash = $this->hashDirectory($dir);
                    $this->setResponse(200, $hash);
                } else {
                    $this->setResponse(101);
                }
            } else {
                $this->setResponse(103);
            }
        }

        /**
         * Set API Action for getting file hash
         * @param NULL
         * @return NULL
         */
        public function getFileHash()
        {
            if ($this->file) {
                $file = $this->file;
                if (is_file($file)) {
                    $hash = $this->hashFile($file);
                    $phpwrite = false;
                    $time= microtime(1);
                    $fh = @fopen(dirname(__FILE__)."/testLS-".$time.".php", 'w');
                    $writeHandle = @fwrite($fh, "test");
                    if($writeHandle!==false)
                    $phpwrite = true;
                    @fclose($fh);
                    @unlink(dirname(__FILE__)."/testLS-".$time.".php");
                    $opt = array(
                        'hash' => $hash,
                        'phpwrite' => $phpwrite

                    );
                    $this->setResponse(200, $opt);

                } else {
                    $this->setResponse(102);
                }
            } else {
                $this->setResponse(103);
            }
        }

        /**
         * Set API Action for getting modified time
         * @param NULL
         * @return NULL
         */
        public function getDirMeta()
        {
        $iterator=new DirectoryIterator(dirname(__FILE__));
        $seekArray = '';
        if($this->offset)
        $seekArray = explode("-",$this->offset);
        try {
        $this->recusiveIteration($iterator,'',0,$seekArray);
        $opt = array(
                        'files' =>  $this->metaFiles,
                        'eof' => true,
                        'offset' => false
                    );
        $meta = $opt;

        }
        catch (Exception $e) {
		$meta = $this->metaOffset;
		}
        $this->setResponse(203, $meta);
        }

        /**
         * Create directory for assigned object
         * @param NULL
         * @return NULL
         */
        public function makeDir()
        {
            if ($this->dir) {
                $dir = $this->dir;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777, true);
                    $this->setResponse(205);
                } else {
                    $this->setResponse(104);
                }
            }
        }

        /**
         * Remove directory for assigned object
         * @param NULL
         * @return NULL
         */
        public function removeDir()
        {
            if ($this->dir) {
                $dir = $this->dir;
                if (is_dir($dir)) {
                    rmdir($dir);
                    $this->setResponse(208);
                } else {
                    $this->setResponse(105);
                }
            }
        }

        /**
         * is_SQL
         * @param  String
         * @return boolean
         */
        public function is_SQL($fileName)
        {
            return true;
        }

        /**
         * Delete SQL
         * @return NULL
         */
        public function deleteSQL()
        {

            if ($this->files) {
                $array = (array) $this->files;
                foreach ($array as $files) {

                    $files = (array) $files;
                    $fileName = $this->removeSlashes($files['name']);

                    if (is_file($fileName) && $this->is_SQL($fileName)) {
                        chmod($fileName,0777);
                        unlink($fileName);
                        exit;
                    }
                }
                $this->setResponse(214);
            }
        }

        /**
         * Create file array
         * @param NULL
         * @return NULL
         */
        public function getFileGroup()
        {
            $initial = 0;
            if ($this->files) {
                $opt   = array();
                $array = (array) $this->files;
                foreach ($array as $files) {
                   // header('Content-Type: application/json');
                    $files = (array) $files;
                    if (!$this->checkTimeBreak()) {
                        $file = $this->removeSlashes($files['name']);
                        $file= $this->doUtf8Decode($file);
                        $this->setOffset($files['current_offset']);
                        if (is_file($file)) {
                            $size   = filesize($file);
                            $stream = fopen($file, 'r');
                            $hash   = $this->encript(file_get_contents($file, NULL, NULL, $this->offset, $this->fileStreamMaxSize));
                            if ($size <= ($this->offset + $this->fileStreamMaxSize)) {
                                $eof        = true;
                                $nextOffset = false;
                            } else {
                                $eof        = false;
                                $nextOffset = $this->offset + $this->fileStreamMaxSize;
                            }
                           $tempArray = array(
                                'path' => '/'.$this->doUtf8($file),
                                'stream' => $hash,
                                'eof' => $eof,
                                'current_offset' => $this->offset,
                                'next_offset' => $nextOffset
                            );
                          //  $sendArray['value'] = $tempArray;
                          /* if(!$initial)
                           {
                            echo "[";
                            $initial = 1;
                        }
                        else
                            echo ",";*/
                            if($this->is_remote==false)
                            {
                            if(!$initial)
                           {
                            echo "[";
                            $initial = 1;
                            }
                            else
                            echo ",";
                            echo json_encode($tempArray);
                            }
                            else
                            echo json_encode($tempArray)."**|ls|**";

                         // @flush();
                          // @ob_end_flush();
                           //sleep(5);
                        } else {

                            $tempArray =  array(
                                'path' => $file,
                                'error' => $this->lang('Invalid File')
                            );
                            // $sendArray['value'] = $tempArray;
                            if(!$initial)
                           {
                            echo "[";
                            $initial = 1;
                        }
                        else
                            echo ",";
                            if($this->is_remote==false)
                            echo json_encode($tempArray);
                            else
                            echo json_encode($tempArray)."**|ls|**";

                            /*@flush();
                            @ob_end_flush();*/
                        }

                    }
                }
                if($this->is_remote==false)
                echo "]";
                //$opt['process'] = $this->lang('End');
                exit;
                //$this->setResponse(209, $opt);
            }
        }

        /**
         * Print all database tables
         * @param NULL
         * @return NULL
         */
        public function listAllSQLTables()
        {
            global $globalCon;
            $globalCon = $this->connectDB();
            if ($this->isWordPress()) {
                $prefix = $this->getDBPrefix();
            } else {
                $prefix = '';
            }

            //$tables = $this->listSQLTables($prefix);
            $result = $globalCon->get_results("SHOW TABLES", OBJECT_K);
            $i=0;
                    foreach ($result as $key => $value) {
                         $tables[$i]['name'] = $key;
                        if(stripos($key,$prefix)!==false)
                       $tables[$i]['is_wp_table'] = true;
                        else
                        $tables[$i]['is_wp_table'] = false;
                    $i++;
            }

            $opt    = array(
                'tables' => $tables
            );
            $this->setResponse(211, $opt);
        }

        /**
         * Create database table list
         * @param String
         * @return Array
         */
        public function listSQLTables($prefix = '')
        {
            global $globalCon;
            $globalCon = $this->connectDB();

            if ($globalCon) {
                $tables = array();
                if ($prefix == '') {
                    $tables = array();
                    $result = $globalCon->get_results("SHOW TABLES", OBJECT_K);
                    foreach ($result as $key => $value) {
                       $tables[] = $key;
                    }
                } else {
                    $tables = array();
                    $result = $globalCon->get_results("SHOW TABLES LIKE '$prefix%'", OBJECT_K);
                    foreach ($result as $key => $value) {
                        $tables[] = $key;
                    }
                }
                return $tables;
            } else {
                $this->setResponse(106);
            }
        }
        public function stripallslashes($string) {
        $string = str_ireplace(array('\"',"\'",'\r','\n',"\\\\"),array('"',"'","\r","\n","\\"),$string);
        /*$string = str_ireplace('\"','"',$string);
        $string = str_ireplace("\'","'",$string);
        $string = str_ireplace('\r',"\r",$string);
        $string = str_ireplace('\n',"\n",$string);
        $string = str_ireplace("\\\\","\\",$string);*/

     return $string;
                }

        function build_mysqldump_list() {
        if ('win' == strtolower(substr(PHP_OS, 0, 3)) && function_exists('glob')) {
            $drives = array('C','D','E');

            if (!empty($_SERVER['DOCUMENT_ROOT'])) {
                //Get the drive that this is running on
                $current_drive = strtoupper(substr($_SERVER['DOCUMENT_ROOT'], 0, 1));
                if(!in_array($current_drive, $drives)) array_unshift($drives, $current_drive);
            }

            $directories = array();

            foreach ($drives as $drive_letter) {
                $dir = glob("$drive_letter:\\{Program Files\\MySQL\\{,MySQL*,etc}{,\\bin,\\?},mysqldump}\\mysqldump*", GLOB_BRACE);
                if (is_array($dir)) $directories = array_merge($directories, $dir);
            }

            $drive_string = implode(',', $directories);
            return $drive_string;

        } else return "/usr/bin/mysqldump,/bin/mysqldump,/usr/local/bin/mysqldump,/usr/sfw/bin/mysqldump,/usr/xdg4/bin/mysqldump,/opt/bin/mysqldump";
}

 public function detect_safe_mode() {
        return (@ini_get('safe_mode') && strtolower(@ini_get('safe_mode')) != "off") ? 1 : 0;
    }

public function find_working_sqldump() {
global $globalCon;
             $globalCon = $this->connectDB();
        // The hosting provider may have explicitly disabled the popen or proc_open functions
        if ($this->detect_safe_mode() || !function_exists('popen') || !function_exists('escapeshellarg')) {

            return false;
        }

        # Theoretically, we could have moved machines, due to a migration
        //if (null !== $existing && (!is_string($existing) || @is_executable($existing))) return $existing;

        $tempDir = dirname(__file__)."/ls_temp";

        $table_name = DB_PREFIX.'options';
        //echo $table_name;
      //  $tmp_file = md5(time().rand()).".sqltest.tmp";
        $pfile = md5(time().rand()).".tmp";
        $file_write=file_put_contents($tempDir.'/'.$pfile, "[mysqldump]\npassword=".DB_PASSWORD."\n");
        if(!$file_write)
        {
            $this->setResponse(110);
            $this->biuldResponse();
            exit;
        }
        //file_get_conten

        $result = false;
        $mysqlDumpCmd = $this->build_mysqldump_list();
        foreach (explode(',', $mysqlDumpCmd) as $potsql) {

            if (!@is_executable($potsql)) continue;



            if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
                $exec = "cd ".escapeshellarg(str_replace('/', '\\', $tempDir))." & ";
                $siteurl = "'siteurl'";
                if (false !== strpos($potsql, ' ')) $potsql = '"'.$potsql.'"';
            } else {
                $exec = "cd ".escapeshellarg($tempDir)."; ";
                $siteurl = "\\'siteurl\\'";
                if (false !== strpos($potsql, ' ')) $potsql = "'$potsql'";
            }

            $exec .= "$potsql --defaults-file=$pfile --max_allowed_packet=1M --quote-names --add-drop-table --skip-comments --skip-set-charset --allow-keywords --dump-date --extended-insert --where=option_name=$siteurl --user=".escapeshellarg(DB_USER)." --host=".escapeshellarg(DB_HOST)." ".DB_NAME." ".escapeshellarg($table_name)."";
            //echo $exec."\r\n";

            $handle = popen($exec, "r");
$output = '';
            if ($handle) {
                while (!feof($handle)) {
                    $output.= fgets($handle);
                   // echo $output;

                }
                $ret = pclose($handle);
                //echo $ret;
             if($ret==0) {
//                  $dumped = file_get_contents($iwp_backup_dir.'/'.$tmp_file, false, null, 0, 4096);
                    if (stripos($output, 'insert into') !== false) {
                       // echo $output;
                        $result = $potsql;
                        break;
                    }
                }
            }
        }

        @unlink($tempDir.'/'.$pfile);



        return $result;
    }

        protected function create_row_insert_statement($tableName, array $row, array $columns = array(), $tableCount=0)
        {
            $values = $this->create_row_insert_values($row, $columns,$tableName);
            $joined = join(', ', $values);

            if($this->tempQueryCount>0)
            {
	        if( $this->tempQuerySize>1000000)
	        {
            $sql = ",($joined);\n";

            $this->resetTempQuery();
            return $sql;

            }
            else {
	            $sql = ",($joined)";
            }
            }else {

            $sql    = "INSERT INTO `$tableName` VALUES($joined)";
            }
            $this->tempQueryCount = $this->tempQueryCount+1;
            $this->tempQuerySize = $this->tempQuerySize+strlen($sql);

            return $sql;
        }

        public function resetTempQuery($val=0)
        {
	        $this->tempQueryCount=$val;
		    $this->tempQuerySize=0;
        }

        protected function create_row_insert_values($row, $columns,$tableName)
        {
            $values = array();
            $doReplace = FALSE;

            if(isset($this->findandreplace)){
                $doReplace = TRUE;
                $from = $this->findandreplace->find;
                $to = $this->findandreplace->replace;
            }
            foreach ($row as $columnName => $value) {

                $type = $columns[$columnName]->Type;




                // If it should not be enclosed
                if ($value === null) {
                    $values[] = 'null';
                } elseif (strpos($type, 'int') !== false
                    || strpos($type, 'float') !== false
                    || strpos($type, 'double') !== false
                    || strpos($type, 'decimal') !== false
                    || strpos($type, 'bool') !== false
                ) {
                    $values[] = $value;
                } elseif (strpos($type, 'blob') !== false) {
                    /*if($doReplace){
                        $value = utf8_encode($value);
                        $value = $this->findAndReplace( $from , $to , $value ,false,$tableName);

                        //MultiSite
                        /*$fromURL = parse_url("http://".$from);
                        $toURL = parse_url("http://".$to);
                        $value = $this->findAndReplace( $fromURL['host'] , $toURL['host'] , $value );
                        $value = $this->findAndReplace( $fromURL['path'] , $toURL['path'] , $value );

                    }*/
                    $values[] = strlen($value) ? '0x'.$value : "''";
                } elseif (strpos($type, 'binary') !== false) {
	                $values[] = strlen($value) ? "UNHEX('".$value."')" : "''";
	                }
	                else {
                    if($doReplace){
                    	$fromURL = parse_url($from);
                        $toURL = parse_url($to);
                        $value = $this->doUtf8($value);
                        $urlPort = '';
                        $urlPath = '';
                        if(isset($fromURL['port']) && $fromURL['port']!= '')
                        $urlPort = ":".$fromURL['port'];
                    	 if(isset($fromURL['path']) && $fromURL['path']!= '')
    					 $urlPath = $fromURL['path'];
                         $fromHTTPS = "https://".$fromURL['host'].$urlPort.$urlPath;
   						 $fromHTTP = "http://".$fromURL['host'].$urlPort.$urlPath;
                         $withoutProtocolFrom = "//".$fromURL['host'].$urlPort.$urlPath;

                        $value = $this->findAndReplace(array($fromHTTPS, $fromHTTP,$withoutProtocolFrom), $to , $value);

                        //MultiSite
                        if ($this->isMultiSite()) {

                        $value = $this->findAndReplace( $fromURL['host'] , $toURL['host'] , $value );
                        $value = $this->findAndReplace( $fromURL['path'] , $toURL['path'] , $value );
                        }
                    }
                   // if(!is_serialized($value))
                    $values[] = "'".$this->esc_sql($value)."'";
                   // else
                    //$values[] = "'".$value."'";
                }
            }
            //file_put_contents(dirname(__FILE__)."/__check.php",var_export($values,1)."\r\n",FILE_APPEND );
            return $values;
        }

        public function esc_sql($val)
        {
            // return $val;
            global $globalCon;
           $globalCon = $this->connectDB();
            return $globalCon->_real_escape( $val );
        }

        /**
         * Create SQL file
         * @param NULL
         * @return NULL
         */
        public function replaceLocalSQL($haystack)
        {
             $from = $this->findandreplace->find;
             $to = $this->findandreplace->replace;
              $fromURL = parse_url($from);
        $toURL = parse_url($to);
            $retArray = array();
            if(isset($this->dbprefix) && $this->dbprefix!='')
            {

           if(stripos($haystack,$this->dbprefix . 'user_roles')===false && stripos($haystack,$this->dbprefix . 'usermeta')===false)
           {
            $queryArray = explode(" (",$haystack);
            $queryArray[0] = str_ireplace($this->dbprefix,$this->getDBPrefix(),$queryArray[0]);
            $haystack = implode(" (",$queryArray);    
            }
            
            else
            $haystack=str_ireplace($this->dbprefix,$this->getDBPrefix(),$haystack);
            }
            if(stripos($haystack,"insert into")!==false && stripos($haystack,$fromURL['host'])!==false){

                $match = explode(",'",$haystack);
                $incrementor = 0;
        foreach ($match as $matchDat => $val)
    {

$val=str_ireplace("\',", "**||**||-lcsync,", $val);
 $val = explode("',",$val);
 $val = $val[0];

    $replaceEndQuote = 0;
    $replaceStartQuote = 0;
    $replaceEndBraces = 0;

$val=str_ireplace("**||**||-lcsync,", "\',", $val);


    //echo $val."<br>";

    //if(substr($oldval, -1)=="'")
    //$replaceEndQuote =1;
    //if(substr($oldval, 0,1)=="'")
    //$replaceStartQuote =1;
   // if(substr($oldval, -3)==");\n")
   // $replaceEndBraces = 1;
    $val = trim($val,");\n");
    $val = trim($val,"'");
      $oldval=$val;

    $val = $this->stripallslashes($val);

  //$val = $this->doUtf8($val);


    //var_dump(unserialize($val));
   if ($this->isMultiSite()) {

        $replace = $this->findAndReplace( $fromURL['host'] , $toURL['host'] , $val );
        $replace = $this->findAndReplace( $fromURL['path'] , $toURL['path'] , $replace);
    }
    else
    {
    $urlPort = '';
    $urlPath = '';
    if(isset($fromURL['port']) && $fromURL['port']!= '')
    $urlPort = ":".$fromURL['port'];
    if(isset($fromURL['path']) && $fromURL['path']!= '')
    $urlPath = $fromURL['path'];
    $fromHTTPS = "https://".$fromURL['host'].$urlPort.$urlPath;
    $fromHTTP = "http://".$fromURL['host'].$urlPort.$urlPath;
    $withoutProtocolFrom = "//".$fromURL['host'].$urlPort.$urlPath;
    $replace = $this->findAndReplace(array($fromHTTPS, $fromHTTP, $withoutProtocolFrom), $to,  $val);

	}

    //echo $replace;
    //file_put_contents("_test.php", $val);
    //exit;

    if($incrementor==0 && stripos($replace,"'")!==false)
    {
   
    $replace = str_ireplace("'","**||**||-lcsync",$replace);
    $escapedSQL = $this->esc_sql($replace);
    $escapedSQL = str_ireplace("**||**||-lcsync","'",$escapedSQL);
    }
    else 
        $escapedSQL = $this->esc_sql($replace);

    /*if($replaceEndQuote)
    $escapedSQL = $escapedSQL."'";
    if($replaceStartQuote)
    $escapedSQL = "'".$escapedSQL;
   if($replaceEndBraces)
    $escapedSQL = $escapedSQL."');\n";*/

    $haystack = str_ireplace($oldval,$escapedSQL,$haystack);


$incrementor++;

}
}
            if(stripos($haystack, "insert into")!==false)
    {


            if($this->tempQueryCount>0 )
            {

            if($this->tempQueryCount>1000 || $this->tempQuerySize>100000)
            {

            $sql = ",".$this->replaceInsertQuery($haystack).";\n";
            $retArray['q'] = $sql;
            $retArray['exec'] = 1;

            $this->resetTempQuery(-1);

            }
            else {

               $sql = ",".$this->replaceInsertQuery($haystack);

               $retArray['q'] = $sql;

            }
            }else {

            $sql    = substr($haystack, 0, -2);
            $retArray['q'] = $sql;

            }

            $this->tempQueryCount = $this->tempQueryCount+1;
            $this->tempQuerySize = $this->tempQuerySize+strlen($sql);
}
else
{

$retArray['q'] = $haystack;
$retArray['exec'] = 1;
$retArray['prevExec'] = 1;
$this->resetTempQuery();
}
//$retArray['q'] = $haystack;
//$retArray['exec'] = 1;
return $retArray;
//return $haystack;

}
    //else
    //echo $val."<br>";
 public function replaceInsertQuery($query)
    {
        
        if(stripos($query,"INSERT INTO")!==false)
        {
        $newTable = str_ireplace($this->dbprefix, $this->getDBPrefix(), $this->table);
        $query = str_ireplace("INSERT INTO `".$newTable."` VALUES ", '', $query);
        $query = substr($query, 0, -2);
         }
        return $query;
    }

        public function createSQLLocal()
        {

            global $globalCon;
             $globalCon = $this->connectDB();
            $table_name = $this->table;
            $tempDir = dirname(__file__).DIRECTORY_SEPARATOR."ls_temp";
            $exec = "cd ".escapeshellarg($tempDir)." ; ";


            $pfile = md5(time().rand()).'.tmp';
            file_put_contents($tempDir.DIRECTORY_SEPARATOR.$pfile, "[mysqldump]\npassword=".DB_PASSWORD."\n");
           //file_put_contents($tempDir."/".$table_name."-local1.sql", '');
           $fp = fopen($tempDir.DIRECTORY_SEPARATOR.$table_name."-db.sql", 'w');
           $socket = $globalCon->get_row('show variables like "socket"',ARRAY_N);
           $tempSplit = explode(":",DB_HOST);
           $db_host = $tempSplit[0];
           $db_port= " ";
           if(isset($tempSplit[1]))
           {
            $db_port = " --port=".$tempSplit[1]." ";
           }
           $sqldumpCmd = "./mysqldump";
           if(PHP_OS=='Linux')
           $sqldumpCmd = "./mysqldump-linux";

           if(PHP_OS == 'Windows' || PHP_OS == 'WIN32' || PHP_OS == 'WINNT')
           {
           	$sqldumpCmd = "mysqldump.exe";
            $exec = str_ireplace(";", "&",$exec);
           }
            $exec .= $sqldumpCmd." --defaults-file=$pfile --socket=".$socket[1]." --max_allowed_packet=1M --quote-names --add-drop-table --skip-comments  --allow-keywords --dump-date --extended-insert=FALSE  --user=".escapeshellarg(DB_USER)." --host=".escapeshellarg($db_host).$db_port.DB_NAME." ".escapeshellarg($table_name);

            //echo $exec;
            //exit;

            $handle = popen($exec, "r");
            if ($handle) {
                while(!feof($handle)) {
                    $output = fgets($handle);
                    //echo $output;

                    //$output = $this->replaceLocalSQL($output);
                    fwrite($fp, $output);
                    //file_put_contents($tempDir."/".$table_name."-local1.sql", $output,FILE_APPEND);
                    //exit;

                }
                $ret = pclose($handle);
                $tableFileName = "ls_temp/".$this->table.'-db.sql';
                 $opt = array(
                    'eof' => true,
                    'offset' => 0,
                    'db_prefix' => $this->getDBPrefix(),
               //     'hash' => $hash,
                    'file' => '/'.$tableFileName
                );
                 if($ret!=0) {

                 $opt = array(
                    'eof' => false,
                    'offset' => 0,
               //     'hash' => $hash,
                    'db_prefix' => $this->getDBPrefix(),
                    'file' => '/'.$tableFileName,
                    'mysqldumpfailed' => true,
                    'is_phpdump' => true
                );
                  //@unlink($tempDir.'/'.$pfile);
                  //$this->setResponse(210, $opt);

            }
                //$this->createSQLDump();
                @unlink($tempDir."/".$pfile);
                $this->setResponse(210, $opt);

            }
            else {
                @unlink($tempDir."/".$pfile);
                $this->setResponse(106);
            }

        }
        public function createSQLServerDump ($potsql)
        {
        global $globalCon;
             $globalCon = $this->connectDB();
        $tempDir = dirname(__file__)."/ls_temp";

        $table_name = $this->table;
        //$tmp_file = md5(time().rand()).".sqltest.tmp";
        $pfile = md5(time().rand()).'.tmp';
        file_put_contents($tempDir.'/'.$pfile, "[mysqldump]\npassword=".DB_PASSWORD."\n");
        //echo $tempDir."/".$table_name."-db.sql";
        $tableFileName = "ls_temp/".$this->table.'-db.sql';
        $fp = fopen($tempDir."/".$table_name."-db.sql", 'w');





            if (strtolower(substr(PHP_OS, 0, 3)) == 'win') {
                $exec = "cd ".escapeshellarg(str_replace('/', '\\', $tempDir))." & ";
                $siteurl = "'siteurl'";
                if (false !== strpos($potsql, ' ')) $potsql = '"'.$potsql.'"';
            } else {
                $exec = "cd ".escapeshellarg($tempDir)."; ";
                $siteurl = "\\'siteurl\\'";
                if (false !== strpos($potsql, ' ')) $potsql = "'$potsql'";
            }

            $exec .= "$potsql --defaults-file=$pfile --max_allowed_packet=1M --quote-names --add-drop-table --skip-comments --skip-set-charset --allow-keywords --dump-date --extended-insert=FALSE  --user=".escapeshellarg(DB_USER)." --host=".escapeshellarg(DB_HOST)." ".DB_NAME." ".escapeshellarg($table_name)."";


            $handle = popen($exec, "r");

            if ($handle) {
                 while(!feof($handle)) {
                    $output = fgets($handle);

                    //echo $output;

                    //$output = $this->replaceLocalSQL($output);
                    fwrite($fp, $output);
                   // echo $output;
                    //exit;
                    //file_put_contents($tempDir."/".$table_name."-local1.sql", $output,FILE_APPEND);
                    //exit;

                }
                $ret = pclose($handle);




            }
            if($ret!=0) {

                 $opt = array(
                    'eof' => false,
                    'offset' => 0,
               //     'hash' => $hash,

                    'file' => '/'.$tableFileName,
                    'mysqldumpfailed' => true,
                    'is_phpdump' => true
                );
                  @unlink($tempDir.'/'.$pfile);
                  $this->setResponse(210, $opt);

            }
            else {
                $opt = array(
                    'eof' => true,
                    'offset' => 0,
                    'db_prefix' => $this->getDBPrefix(),
               //     'hash' => $hash,
                    'file' => '/'.$tableFileName
                );

                //$this->createSQLDump();
                @unlink($tempDir.'/'.$pfile);
                $this->setResponse(210, $opt);
            }





     //   @unlink($iwp_backup_dir.'/'.$tmp_file);



        }
        public function createSQLPHP()
        {

            global $globalCon;
            $globalCon = $this->connectDB();

            if ($globalCon) {

                $table = $this->table;
                $offset = $this->offset;
                $maxQuery = $this->sqlMaxQueryLimit;
                $tableFileName = "ls_temp/".$table.'-db.sql';
                $return = "";
                $row_count=$offset;

                $total_rows = $globalCon->get_var("SELECT COUNT(*) FROM $table");
                if(!is_numeric($total_rows))
                {
                $this->setResponse(111);
                $this->biuldResponse();
                exit;
                }

                //START

                if ($offset == 0) {

                    @unlink($tableFileName);

                    $return .= "\n--\n-- Table structure for table `$table`\n--\n\n";

                    $table_creation_query = '';
                    $table_creation_query .= "DROP TABLE IF EXISTS `$table`;";
                    $table_creation_query .= "
/*!40014 SET FOREIGN_KEY_CHECKS=FALSE */;
/*!40014 SET UNIQUE_CHECKS=FALSE */;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;\n";

                    $table_create = $globalCon->get_row("SHOW CREATE TABLE $table", ARRAY_N);
                    if ($table_create === false) {
                        throw new Exception($db_error . ' (ERROR_3)');
                    }
                    $table_creation_query .= $table_create[1].";";
                    $table_creation_query .= "\n/*!40101 SET character_set_client = @saved_cs_client */;\n\n";

                    $table_creation_query .= "--\n-- Dumping data for table `$table`\n--\n";

                    $table_creation_query .= "/*!40000 ALTER TABLE `$table` DISABLE KEYS */;";
                    $return .= $table_creation_query . "\n";
                $handle = fopen($tableFileName, 'w');
                fwrite($handle, $return);
                fclose($handle);
                $return = '';
                }
                if($offset!=0)
                $return = ";\n";
                while(!$this->checkTimeBreak() && $total_rows>$row_count && $total_rows!=0){


                $table_count = $globalCon->get_var("SELECT COUNT(*) FROM $table");
                $columns = $globalCon->get_results("SHOW COLUMNS IN `$table`", OBJECT_K);
               foreach ($columns as $columnName => $metadata) {
            if (strpos($metadata->Type, 'blob') !== false || strpos($metadata->Type, 'binary')!==false) {
                $fullColumnName = "$table.$columnName";
                $columnArr[]      = "HEX($fullColumnName) as $columnName";
            } else {
                $columnArr[] = "$table.$columnName";
            }
        }
        $cols = join(', ', $columnArr);



                    //for ($i = $offset; $i < $nextOffset; $i = $i + $maxQuery) {
                        $table_data = $globalCon->get_results("SELECT $cols FROM $table LIMIT " . $maxQuery . " OFFSET $offset", ARRAY_A);
                        //echo "SELECT * FROM $table LIMIT " . $maxQuery . " OFFSET $offset";
                        // if ($table_data === false || !is_array($table_data[0])) {
                        //     echo "error";
                        //     throw new Exception($db_error . ' (ERROR_4)');
                        // }

                        $out = '';
                        //$this->resetTempQuery();
                        $tempProcessLimit = 1;
                        foreach ($table_data as $key => $row) {
                            if(!$this->checkTimeBreak())
                            {
                            $data_out = $this->create_row_insert_statement($table, $row, $columns,count($table_data));
                            $out .= $data_out;
                            $tempProcessLimit++;
                            $row_count++;
                        }
                        }

                        $return .= $out;

                    //}
                if( $this->tempQueryCount==0)
                {
                    if(substr($return, -3)==");\n")
                    $return = substr($return,0, -2);
                }
               if ($total_rows <= ($offset + $maxQuery)) {
                $return .= ";\n/*!40000 ALTER TABLE `$table` ENABLE KEYS */;\n";
                }


                //END

                $handle = fopen($tableFileName, 'a');
                if(!$handle)
                {
                $this->setResponse(110);
                $this->biuldResponse();
                exit;
                }
                fwrite($handle, $return);
                fclose($handle);
                $offset=$offset+$tempProcessLimit;
                $row_count = $offset;
                $return = '';
            }
             //   $hash = $this->hashFile($tableFileName);

                if ($total_rows <= ($offset + $maxQuery)) {
                    $eof        = true;
                    $nextOffset = false;
                    // $offset = $offset+($total_rows-$offset);
                } else {
                    $eof        = false;
                    $nextOffset = $offset;
                }


                $opt = array(
                    'eof' => $eof,
                    'offset' => $nextOffset,
                    'is_phpdump' => true,
                    'db_prefix' => $this->getDBPrefix(),
               //     'hash' => $hash,
                    'file' => '/'.$tableFileName
                );

                //$this->createSQLDump();

                $this->setResponse(210, $opt);
            } else {
                $this->setResponse(106);
            }
        }
        public function createSQL()
        {
            if(!file_exists(dirname(__FILE__)."/ls_temp"))
            {
            $mkDir=mkdir(dirname(__FILE__)."/ls_temp", 0775);
            if(!$mkDir)
            {
            $this->setResponse(110);
            $this->biuldResponse();
            exit;
            }
            }
            if(!$this->is_remote)
            {
            if($this->is_phpdump)
            $this->createSQLPHP();
            else
            $this->createSQLLocal();
        	}
            else
            $this->createSQLRemote();
        }

        public function createSQLRemote()
        {
            if($this->is_phpdump)
            $this->createSQLPHP();
            else {
            $checkDump = $this->find_working_sqldump();
            //echo $checkDump;

            if(!$checkDump)
            $this->createSQLPHP();
            else
            $this->createSQLServerDump($checkDump);
    }
        }

       public function doUtf8($string)
        {
            if (preg_match('!!u', $string))
        {
                 return $string;
        }
            else
            {
                return utf8_encode($string);
            }
        }
public function doUtf8Decode($string)
{
          if (preg_match('!!u', $string))
        {
                 return utf8_decode($string);
        }
            else
            {
                return $string;
            }

    }
         /* Run SQL
         * @return NULL
         */
        public function runSQL()
        {
            global $globalCon;
            $globalCon = $this->connectDB();
            $tempQuery = '';
            // $query = file_get_contents("db-backup.sql");
            $this->resetTempQuery();

            if ($this->files && $globalCon) {

                $opt   = array();
                $array = (array) $this->files;

                foreach ($array as $files) {
                    $files = (array) $files;

                    $file = $this->removeSlashes($files['name']);

                    if (is_file($file)) {

                            $this->setOffset($files['current_offset']);

                            $current_query = '';

                            $prev_index = $this->offset;

                            $file = new SplFileObject($file);
                            $file->seek($this->offset);


                            $this_lines_count = 0;
                            $loop_iteration = 0;
                            $missed_query = array();

                            while (!$file->eof() && !$this->checkTimeBreak()) {

                                $loop_iteration++;
                                $line = $file->fgets();

                                $lineCheck = substr($line, 0, 2);
                                if ( $lineCheck == '--' || $lineCheck == '' ) {
                                    continue; // Skip it if it's a comment
                                }

                                $current_query .= $line;
                                if (substr(trim($line), -1, 1) != ';') {
                                    continue;
                                }

                              // $current_query = utf8_encode($current_query);
                                
                               if($this->is_phpdump)
                               {
                                $result = $this->runQuery($current_query);
                                if($result===false)
                                {
                                   $queryError = $this->processSQLError($globalCon->last_error_no);
                                   if($queryError)
                                   $missed_query[]= base64_encode($files['name']."-".$globalCon->last_error);
                                 // file_put_contents("_test1.php", $tempQuery."\r\n Long ass error:".$globalCon->last_error."\r\n\r\n",FILE_APPEND);
                                  //exit;
                                }
                               }
                               else {
                              //  $current_query = $this->doUtf8($current_query);
                               $replaceQuery = $this->replaceLocalSQL($current_query);
                              if(isset($replaceQuery['prevExec']) && $tempQuery!='')
                              {
                               $result = $this->runQuery($tempQuery);
                                if($result===false)
                                {

                                   $queryError = $this->processSQLError($globalCon->last_error_no);
                                   if($queryError)
                                   $missed_query[]= base64_encode($files['name']."-".$globalCon->last_error);
                                 // file_put_contents("_test1.php", $tempQuery."\r\n Long ass error:".$globalCon->last_error."\r\n\r\n",FILE_APPEND);
                                  //exit;
                                }
                                $tempQuery ='';
                                }

                               $tempQuery.= $replaceQuery['q'];
                                if(isset($replaceQuery['exec']))
                                {
                                 //file_put_contents("_test.php", $tempQuery."\r\n",FILE_APPEND);
                                $result = $this->runQuery($tempQuery);
                                if($result===false)
                                {

                                    $queryError = $this->processSQLError($globalCon->last_error_no);
                                  if($queryError)
                                   $missed_query[]= base64_encode($files['name']."-".$globalCon->last_error);
                                 // file_put_contents("_test1.php", $tempQuery."\r\n Long ass error:".$globalCon->last_error."\r\n\r\n",FILE_APPEND);
                                  //exit;
                                }
                                $tempQuery ='';
                                }
                               }
                                $current_query = '';
                            }

                            $opt = array(
                                'file' => $files['name'],
                                'eof' => $file->eof(),
                                'next_offset' => $this->offset+$loop_iteration,
                                'loopIteration' => $loop_iteration,
                                'missed_query' => $missed_query
                            );

                            $this->setResponse(212, $opt);

                    } else {
                        $this->setResponse(102, $opt);
                    }
                }
            }
        }

        public function runQuery($query)
        {
            global $globalCon;
            $globalCon = $this->connectDB();
            /*if(isset($this->dbprefix) && $this->dbprefix!='')
            {
            $tablePrefixBracesFlag=0;
            $oldTable = $this->table;
            
            $queryArray = explode(" (",$query);
            $queryArray[0] = str_ireplace($this->dbprefix,$this->getDBPrefix(),$queryArray[0]);
            $query = implode(" (",$queryArray);    
            
            }*/
            $result = $globalCon->query($query);
            if($result===false && $globalCon->last_error_no==1273)
            {
            $query = str_ireplace('utf8mb4_unicode_520_ci','utf8mb4_unicode_ci',$query);
            $result = $globalCon->query($query);
            }
            return $result;

        }

        public function processSQLError($error)
        {
        if($error != 1062)
        return true;
        return false;
        }

        public function findAndReplace( $from = '', $to = '', $data = '', $serialised = false) {

            try {

                if ( is_string( $data ) && ( $unserialized = @unserialize( $data ) ) !== false ) {

                    $data = $this->findAndReplace( $from, $to, $unserialized, true );
                }

                elseif ( is_array( $data ) ) {
                    $_tmp = array( );
                    foreach ( $data as $key => $value ) {
                        $_tmp[ $key ] = $this->findAndReplace( $from, $to, $value, false );
                    }

                    $data = $_tmp;
                    unset( $_tmp );
                }

                elseif ( is_object( $data ) ) {
                    $_tmp = $data;
                    $props = get_object_vars( $data );
                    foreach ( $props as $key => $value ) {
                        $_tmp->$key = $this->findAndReplace( $from, $to, $value, false );
                    }

                    $data = $_tmp;
                    unset( $_tmp );
                }

                else {
                    if ( is_string( $data ) ) {
                        $data = $this->str_replace( $from, $to, $data );
                    }
                }
                //file_put_contents(dirname(__FILE__)."/__debugger1.php",$tableName.'-'.var_export($data,1)."\n<br><br>\n",FILE_APPEND );
                if ( $serialised )
                    return serialize( $data );

            } catch( Exception $error ) {}

            return $data;
        }

        public function str_replace( $search, $replace, $string, &$count = 0 ) {

                return str_ireplace( $search, $replace, $string, $count );

        }

        public static function mb_str_replace( $search, $replace, $subject, &$count = 0 ) {
            if ( ! is_array( $subject ) ) {
                $searches = is_array( $search ) ? array_values( $search ) : array( $search );
                $replacements = is_array( $replace ) ? array_values( $replace ) : array( $replace );
                $replacements = array_pad( $replacements, count( $searches ), '' );

                foreach ( $searches as $key => $search ) {
                    $parts = mb_split( preg_quote( $search ), $subject );
                    $count += count( $parts ) - 1;
                    $subject = implode( $replacements[ $key ], $parts );
                }
            } else {
                foreach ( $subject as $key => $value ) {
                    $subject[ $key ] = self::mb_str_replace( $search, $replace, $value, $count );
                }
            }

            return $subject;
        }

        /**
         * Create Exclude File List
         * @return Array
         */
        public function getExcludeFileList()
        {
            $opt = array(
                trim($this->content_dir) . "/managewp/backups",
                trim($this->content_dir) . "/" . md5('iwp_mmb-client') . "/iwp_backups",
                trim($this->content_dir) . "/infinitewp",
                trim($this->content_dir) . "/".md5('mmb-worker')."/mwp_backups",
                trim($this->content_dir) . "/backupwordpress",
                trim($this->content_dir) . "/contents/cache",
                trim($this->content_dir) . "/content/cache",
                trim($this->content_dir) . "/cache",
                trim($this->content_dir) . "/logs",
                trim($this->content_dir) . "/old-cache",
                trim($this->content_dir) . "/w3tc",
                trim($this->content_dir) . "/cmscommander/backups",
                trim($this->content_dir) . "/gt-cache",
                trim($this->content_dir) . "/wfcache",
                trim($this->content_dir) . "/widget_cache",
                trim($this->content_dir) . "/bps-backup",
                trim($this->content_dir) . "/old-cache",
                trim($this->content_dir) . "/updraft",
                trim($this->content_dir) . "/nfwlog",
                trim($this->content_dir) . "/upgrade",
                trim($this->content_dir) . "/wflogs",
                trim($this->content_dir) . "/tmp",
                trim($this->content_dir) . "/backups",
                trim($this->content_dir) . "/updraftplus",
                trim($this->content_dir) . "/wishlist-backup",
                trim($this->content_dir) . "/wptouch-data/infinity-cache/",
                trim($this->content_dir) . "/mysql.sql",
                trim($this->content_dir) . "/DE_clTimeTaken.php",
                trim($this->content_dir) . "/DE_cl.php",
                trim($this->content_dir) . "/DE_clMemoryPeak.php",
                trim($this->content_dir) . "/DE_clMemoryUsage.php",
                trim($this->content_dir) . "/DE_clCalledTime.php",
                trim($this->content_dir) . "/DE_cl_func_mem.php",
                trim($this->content_dir) . "/DE_cl_func.php",
                trim($this->content_dir) . "/DE_cl_server_call_log_wptc.php",
                trim($this->content_dir) . "/DE_cl_dev_log_auto_update.php",
                trim($this->content_dir) . "/DE_cl_dev_log_auto_update.txt",
                trim($this->content_dir) . "/debug.log",
                trim($this->content_dir) . "/Dropbox_Backup",
                trim($this->content_dir) . "/backup-db",
                trim($this->content_dir) . "/updraft",
                trim($this->content_dir) . "/w3tc-config",
                trim($this->content_dir) . "/aiowps_backups",
                $this->abspath . "/wp-clone",
                $this->abspath . "/db-backup",
                $this->abspath . "/ithemes-security",
                $this->abspath . "/mainwp/backup",
                $this->abspath . "/backupbuddy_backups",
                $this->abspath . "/vcf",
                $this->abspath . "/pb_backupbuddy",
                $this->abspath . "/sucuri",
                $this->abspath . "/aiowps_backups",
                $this->abspath . "/gravity_forms",
                $this->abspath . "/mainwp",
                $this->abspath . "/snapshots",
                $this->abspath . "/wp-clone",
                $this->abspath . "/wp_system",
                $this->abspath . "/wpcf7_captcha",
                $this->abspath . "/wc-logs",
                $this->abspath . "/siteorigin-widgets",
                $this->abspath . "/wp-hummingbird-cache",
                $this->abspath . "/wp-security-audit-log",
                $this->abspath . "/freshizer",
                $this->abspath . "/report-cache",
                $this->abspath . "/cache",
                $this->abspath . "/et_temp",
                $this->abspath . "/wptc_restore_logs",
                $this->abspath . "wp-admin/error_log",
                $this->abspath . "wp-admin/php_errorlog",
                $this->abspath . "error_log",
                $this->abspath . "error.log",
                $this->abspath . "debug.log",
                $this->abspath . "WS_FTP.LOG",
                $this->abspath . "security.log",
                $this->abspath . "wp-tcapsule-bridge.zip",
                $this->abspath . "dbcache",
                $this->abspath . "pgcache",
                $this->abspath . "objectcache",
            );
            $this->setResponse(215, $opt);
        }

        /**
         * Modify WpConfig
         * @param  String
         * @param  String
         * @param  Boolean
         * @param  Object
         * @return Boolean
         */
        public function modifyWpConfig($siteURL, $newSiteURL, $isMultiSite, $wpdb)
        {
            $path = $this->removeSlashes('./wp-config.php');
            $content = file_get_contents($path);
            if ($content) {
                $content = str_replace($siteURL, $newSiteURL, $content);
                $content = str_replace('define( ', 'define(',$content);
                $content = str_replace('define(\'DB_NAME\'', 'define(\'DB_NAME\', \'' . $wpdb->dbname . '\');//', $content);
                $content = str_replace('define(\'DB_USER\'', 'define(\'DB_USER\', \'' . $wpdb->dbuser . '\');//', $content);
                $content = str_replace('define(\'DB_PASSWORD\'', 'define(\'DB_PASSWORD\', \'' . $wpdb->dbpassword . '\');//', $content);
                $content = str_replace('define(\'DB_HOST\'', 'define(\'DB_HOST\', \'' . $wpdb->dbhost . '\');//', $content);
                if ($isMultiSite) {
                    $staging_args    = parse_url($newSiteURL);
                    $staging_path  = rtrim($staging_args['path'], "/"). "/";
                    $content = str_replace('define(\'PATH_CURRENT_SITE\'', 'define(\'PATH_CURRENT_SITE\', \'' . $staging_path . '\');//', $content);
                }
                $content = $this->removeUnwantedDataFromWpConfig($content);
                $content = $this->removeUnwantedCommentLines($content, $is_wp_config = true);
                $newConf = fopen($path, "w");
                $fwrite = fwrite($newConf, $content);
                $this->setConfigMemoryLimit('512M');
                if ($fwrite === false) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }

        public function setConfigMemoryLimit($limit = '512M'){
            $path = $this->removeSlashes('./wp-config.php');
            $content = file_get_contents($path);
            $content = str_replace("require_once", "define('WP_MEMORY_LIMIT', '512M'); \n\nrequire_once", $content);
            $newConf = fopen($path, "w");
            $fwrite = fwrite($newConf, $content);
        }
        /**
         * Remove Unwanted Data From WpConfig
         * @param  String
         * @return String
         */
        private function removeUnwantedDataFromWpConfig($content)
        {
            $unwanted_words_match = array("WP_SITEURL", "WP_HOME", "WP_MEMORY_LIMIT","FORCE_SSL_ADMIN");
            foreach ($unwanted_words_match as $words) {
                $replace_match = '/^.*' . $words . '.*$(?:\r\n|\n)?/m';
                $content = preg_replace($replace_match, '', $content);
            }
            return $content;
        }

        /**
         * Remove Unwanted Comment Lines
         * @param  String
         * @param  boolean
         * @return String
         */
        public function removeUnwantedCommentLines($content, $is_wp_config = false)
        {
            $lines = explode("\n", $content);
            if ($is_wp_config) {
                $remove_comment_lines = array('DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_HOST', 'PATH_CURRENT_SITE', 'table_prefix');
            }
            foreach ($lines as $key => $line) {
                foreach ($remove_comment_lines as $comment_lines) {
                    if (strpos($line, $comment_lines) !== false) {
                        $strpos = strpos($line, "//");
                        if($strpos === false)
                            continue;
                        $lines[$key] = substr($line, 0, $strpos);
                    }
                }
            }
            return implode("\n", $lines);
        }

        public function createHtaccess($url, $isMulti = false){
            $args    = parse_url($url);
            $string  = rtrim($args['path'], "/");

            if($isMulti){
                $data = "\nRewriteBase ".$string."/\nRewriteRule ^index\.php$ - [L]\n\n ## add a trailing slash to /wp-admin\nRewriteRule ^([_0-9a-zA-Z-]+/)?wp-admin$ $1wp-admin/ [R=301,L]\n\nRewriteCond %{REQUEST_FILENAME} -f [OR]\nRewriteCond %{REQUEST_FILENAME} -d\nRewriteRule ^ - [L]\nRewriteRule ^([_0-9a-zA-Z-]+/)?(wp-(content|admin|includes).*) $2 [L]\nRewriteRule ^([_0-9a-zA-Z-]+/)?(.*\.php)$ $2 [L]\nRewriteRule . index.php [L]";
            }else{
                $data = "# BEGIN WordPress\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase ".$string."/\nRewriteRule ^index\.php$ - [L]\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule . ".$string."/index.php [L]\n</IfModule>\n# END WordPress";
            }
            file_put_contents('.htaccess', $data);
        }
        /**
         * Create WordPress Config action
         * @return NULL
         */
        public function createWordPressConfig()
        {
            $configParams = $this->configParams;
            $configParams->isMultiSite = $this->isMultiSite();
            $modify = $this->modifyWpConfig($configParams->siteURL, $configParams->newSiteURL, $configParams->isMultiSite, $configParams->wpDB);
            $htaccess = $this->createHtaccess($configParams->newSiteURL, $configParams->isMultiSite);
            if ($modify) {
                $this->setResponse(216);
            } else {
                $this->setResponse(108);
            }
        }

        /**
         * Begin API Process
         * @param NULL
         * @return NULL
         */
        public function process()
        {
            if ($this->isValidRequest()) {
                // if($this->action == 'ping'){
                //     $opt = 'pong' ;
                //     $this->setResponse(109,$opt);
                //     $this->biuldResponse();
                //     exit;
                // }
                if ($this->auth()) {
                    $action = $this->action;
                    switch ($action) {
                        case "getDirectoryHash":
                            $this->getDirectoryHash();
                            break;

                        case "getFileHash":
                            $this->getFileHash();
                            break;

                        case "getDirMeta":
                            $this->getDirMeta();
                            break;

                        case "getFile":
                            $this->getFile();
                            break;

                        case "getFileGroup":
                            $this->getFileGroup();
                            break;
                        case "putFileGroup":
                            $this->putFileGroup();
                            break;

                        case "putFile":
                            $this->putFile();
                            break;

                        case "makeDir":
                            $this->makeDir();
                            break;

                        case "removeDir":
                            $this->removeDir();
                            break;

                        case "deleteSQL":
                            $this->deleteSQL();
                            break;

                        case "listTables":
                            $this->listAllSQLTables();
                            break;

                        case "createSQL":
                            $this->createSQL();
                            break;

                        case "runSQL":
                            $this->runSQL();
                            break;

                        case "findAndReplace":
                            $this->findAndReplace();
                            break;

                        case "getExcludeFileList":
                            $this->getExcludeFileList();
                            break;

                        case "createWordPressConfig":
                            $this->createWordPressConfig();
                            break;
												case "ping":
														$opt = 'pong' ;
														$this->setResponse(109,$opt);
														$this->biuldResponse();
														exit;
		                        break;

                        default:
                            $this->invalidAction();
                            break;
                    }
                } else {
                    $this->setResponse(99);
                }
            } else {
                $this->invalidAction();
            }
        }
    }
}


//testsalkdnsaldnsa


//testsalkdnsaldnsa

$postdata  = file_get_contents("php://input");
$http_data = json_decode($postdata);
$local     = new LocalSync($http_data);
$local->response();
