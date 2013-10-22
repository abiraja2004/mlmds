<?php
/*
for($j=0;$j<$count;$j++)
{*/
    /*for($k=0;$k<$max_string;$k++)
    {
        echo $words_pos[$k].'<br />';
    }
//}*/
/*
$pathh='uploads';
$q=(isset($_POST['search']))?trim($_POST['search']):'';
echo $pathh;
$fpp=  opendir($pathh);
while($rr=  readdir($fpp))
{
    if( preg_match("#^\.+$#", $rr) ) continue; // ignore symbolic links
    $file_full_pathh = $pathh.'/'.$rr;
    if(is_dir($file_full_pathh))
        {
	$ret .= php_grep($q, $file_full_pathh);
	} 
    else if( stristr(file_get_contents($file_full_pathh), $q) )
    {
        $files[]=$file_full_path;
        $contents= file_get_contents($file_full_pathh);
        $max_x=strlen($contents);
        
        for($cc=0;$cc<$max_x;$c++)
        {
            $wordsss[$files][]=strpos($contents,$q,$cc);
        }
    }
}

$count_files=count($files);

for($abc=0;$abc<$count_files;$abc++)
{
    for($bcd=0;$bcd<count($wordsss);$bcd++)
    {
        echo $wordsss[$abc][$bcd].'<br />';
    }
}*/

$fpp=  opendir($path);
while($rr=  readdir($fpp))
{
    if( preg_match("#^\.+$#", $rr) ) continue; // ignore symbolic links
    $file_full_pathh = $path.'/'.$rr;
    if(is_dir($file_full_pathh))
        {
	$ret .= php_grep($q, $file_full_pathh);
	} 
    else if( stristr(file_get_contents($file_full_pathh), $q) )
    {
        $files[]="$file_full_pathh";
        $contents= file_get_contents($file_full_pathh);
        $max_x[]=strlen($contents);
        
        $counter=0;
        $offset=0;
        
        $wordsss[]=  strpos($contents,$q,0);
        echo "The word is present in the file -" .$file_full_pathh." in  the following positions &nbsp;&nbsp;&nbsp;";
                if(strpos($contents, $q) == 0){
                $counter++;
                echo "0 &nbsp; ";
                   }

        // Check the rest of the string for 5's
        
              while($offset = strpos($contents, $q, $offset+1)){
                    $counter++;
                    echo "$offset &nbsp;&nbsp;&nbsp;";
                   
                }
                echo '<br /><br />';
      /*
        for($cc=0;$cc<$max_x;$c++)
        {
            $wordsss[]=strpos($contents,$q,$cc);
        } */
    }
}
/*
$count_files=count($files);
echo $count_files.'<br /><br />';
echo count($max_x);
print_r($max_x);
print_r($wordsss);*/
/*
for($abc=0;$abc<$count_files;$abc++)
{
    for($bcd=0;$bcd<count($wordsss);$bcd++)
    {
        echo $wordsss[$abc][$bcd].'<br />';
    }
}
*/
?>
