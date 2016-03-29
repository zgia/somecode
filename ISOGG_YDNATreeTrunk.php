<?php

/**
 * Save data from http://isogg.org/tree/ISOGG_YDNATreeTrunk.html
 * 
 * Transfer '• • • •' to '1.2.3.1' and so on.
 *
 */

// 由文件生成的内容
$text = readFromFile("./ISOGG_YDNATreeTrunk.txt");

// 生成序号
$num = calc(array_column($text, 'count'));

// 把序号加到内容中
$i = 0;
foreach ($text as &$v) {
    $v['num'] = $num[$i];
    $i++;

    //echo $v['num'] . PHP_EOL;
}

print_r($text);

function readFromFile($file)
{
    $text = [];

    $handle = @fopen($file, "r");
    if ($handle) {
        while (($buffer = fgets($handle)) !== false) {
            $tmp = trim($buffer);

            $count = substr_count($tmp, '•');
            if (!$count)
            {
                continue;
            }

            $content = preg_replace('/•(\s+)/i', '', $tmp);

            $text[] = ['count'=>$count, 'content'=>$content];

        }
        if (!feof($handle)) {
            echo "Error: unexpected fgets() fail\n";
        }
        fclose($handle);
    }   

    return $text;
}

function calc($_c)
{
    $p = [];
    $i = 0;
    $last = 1;
    $lastStr = '';

    $y = 0;
    foreach ($_c as $c)
    {
        $p[$y] = $i;

        if ($c == $last)
        {
            $i ++;
            $p[$y] = $lastStr ? $lastStr . '.' . $i : $i;

        }
        else if ($c > $last)
        {
            $i = 1;
            $p[$y] = $p[count($p) - 2] . '.' . $i;

            $lastStr = $p[$y - 1];
        }
        else
        {
            $minux = $last - $c;
            $_t = explode('.', $lastStr);

            for($i=0;$i<=$minux;$i++)
            {
                $_tt = array_pop($_t);
            }

            $i = $_tt + 1;
            $p[$y] = implode('.', $_t ) . '.' .$i;
            
            $lastStr = implode('.', $_t );
        }

        $last = $c;

        $y ++;

    }

    return $p;
}
