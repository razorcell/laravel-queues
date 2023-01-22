  <?php
    function convert($size)
    {
        $unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
        return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
    }

    function getMemoryUsage()
    {
        return convert(memory_get_usage(true));
    }

    function get_all_lines($file_handle)
    {
        while (!feof($file_handle)) {
            yield fgets($file_handle);
        }
    }
