<!DOCTYPE html>
<?php
include 'db.inc.php';

$db=mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD) or die('unable to connect');
mysql_select_db(MYSQL_DB,$db) or die(mysql_error($db));


define("SLASH","/");
	
	$path	= 'uploads' ;
	$q	= (isset($_POST['search']))?$_POST['search']:' ';

        $count=0;
	$tot_files=0;
        $score=0;
	function php_grep($q, $path)
        {
		global $ret,$count,$words,$tot_files,$word_pos,$string_count,$max_string;
		$fp = opendir($path);
		while($f = readdir($fp)){
			if( preg_match("#^\.+$#", $f) ) continue; // ignore symbolic links
			$file_full_path = $path.SLASH.$f;
			if(is_dir($file_full_path)) {
				$ret .= php_grep($q, $file_full_path);
			} else if( stristr(file_get_contents($file_full_path), $q) ) {
				$ret .= "$file_full_path\n";
                                
                                $words[]="$file_full_path";
                                $abc=file_get_contents($file_full_path);
                                $string_count[]=substr_count($abc, $q);
                                $word_pos[]=strpos($abc,$q);
                                
                               // $word_pos[]=$count;
                                
                                $count++;
			}
                        $tot_files++;
		}
		return $ret;
	}


	if($q){
		$results = php_grep($q, $path);
	}
        
    
        global $tot_files,$count,$score;
        
        if($count == $tot_files){ $score = 5; }
        else if($count == 0){ $score = 0;}
        else if($count > 0.7*$tot_files) { $score = 4;}
        else if($count > 0.5*$tot_files) { $score = 3;}
        else if($count > 0.3*$tot_files) { $score = 2;}
        else {$score =1;}
        
        	
    


?>
<html>
    <head>
        <title>Multiple files summarization</title>
        <link rel="stylesheet" href="css/style.css" />
    </head>
    <body>
        <div id="header">
                <h1>Multidocument Summarization</h1>
            </div>
        <div id="big_wrapper">
            
            <div id="left_side" style="min-height: 300px; text-align: left;">
                <ul id="found_files">
                <?php
                if(isset($_POST['search'])){
                    if($count > 0)
                        {
                            echo '<p id="desc">The word "'.$_POST['search'].'" you entered is found in following files :</p>';
                            if(isset($words))
                            {
                                    $max=max($string_count);
                                    $min=min($string_count);
                                    $d=count($string_count);
                                    $c=count($words);
                                    for($i=0;$i<$c;$i++)
                                    {
                                        $name[$i]=substr($words[$i],8);
                                        echo '<li>'.$name[$i].'</li>';
                                        //echo '<li>'.$name[$i].'&nbsp; - Word present in the position - '.$word_pos[$i].'</li><br />';
                                        echo 'The number of occurences of the word :'.$string_count[$i].'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                       
                                        if($string_count[$i] == $max)
                                        {
                                            echo 'Score of file is 5';
                                        }
                                        else if($string_count[$i] == $min)
                                        {
                                            echo 'Score of file is 1<br /><br />';
                                        }
                                        else if($string_count > 0.7*$max)
                                        {
                                           echo 'Score of file is 4<br /><br />';
                                        }
                                        else if($string_count > 0.4*$max)
                                        {
                                            echo 'Score of file is 3<br /><br />';
                                        }
                                        else if($string_count > 0.1*$max)
                                        {
                                            echo 'Score of file is 2<br /><br />';
                                        }
                                        else{
                                            echo 'no score';
                                        }
                                    }
                    
                            }
                           include 'wordpos.php';
                            
                            $query17='SELECT *FROM scores WHERE score_word="'.$_POST['search'].'"';
                            $result17=mysql_query($query17,$db) or die(mysql_error($db));
                            $row=mysql_fetch_assoc($result17);
                            $num_rows=mysql_num_rows($result17);
                            $score_word=$row['score_word'];
                            
                            
                            if($num_rows > 0)
                            {
                                
                                $score_occ=$row['score_occ'] + 1;
                                
                                $query2='UPDATE scores SET score_occ="'.$score_occ.'" WHERE score_word="'.$score_word.'"';
                                $results=mysql_query($query2,$db) or die(mysql_error($db));
                                
                                if($score_occ > 50)
                                {
                                    $query22='UPDATE scores SET score=5 WHERE score_word="'.$score_word.'"';
                                $results=mysql_query($query22,$db) or die(mysql_error($db));
                                }
                                else if($score_occ > 40)
                                 {
                                    $query32='UPDATE scores SET score=4 WHERE score_word="'.$score_word.'"';
                                $results=mysql_query($query32,$db) or die(mysql_error($db));
                                }
                                else if($score_occ > 30)
                                 {
                                    $query42='UPDATE scores SET score=3 WHERE score_word="'.$score_word.'"';
                                $results=mysql_query($query42,$db) or die(mysql_error($db));
                                }
                                else if($score_occ > 20)
                                 {
                                    $query52='UPDATE scores SET score=2 WHERE score_word="'.$score_word.'"';
                                $results=mysql_query($query52,$db) or die(mysql_error($db));
                                }
                                else if($score_occ > 10)
                                 {
                                    $query62='UPDATE scores SET score=1 WHERE score_word="'.$score_word.'"';
                                $results=mysql_query($query62,$db) or die(mysql_error($db));
                                }
                                
                                
                            }
                            else{
                                $query3='INSERT INTO scores(score_word) VALUES ("'.$_POST['search'].'")';
                                $result1=mysql_query($query3,$db) or die(mysql_error($db));
                            }
                            
                        }
                        else {
                            echo 'no words found';
                        }
                        echo '<br />';
                        echo '<h2>Total number of files that contains the word "'.$_POST['search'].'" you entered is : '.$count.'</h2>';
                        
            
                }
                else{
                    echo '<center><h2>Enter keyword to search or Select any file </h2></center>';
                }
                
                
                ?>
                </ul>
            </div>
            <div id="right_side" style="min-height: 300px;">
                <form method="post" action="manipulate.php" name="sear" onsubmit="return validate()">
                    <label for="search">SEARCH: </label>
                    <input type="text" name="search"  id="sear"/>
                    <input type="submit" value="search" name="submit" />
                    <?php if(isset($_POST['search'])){ echo '<h3>'.$_POST['search'].'</h3>';}  ?>
                    <h1>Files</h1>
                    <ul>
                    <?php
                    $query='SELECT * FROM filedetails';
                            $result=mysql_query($query,$db) or die(mysql_error($db));
                            
                            while($row=mysql_fetch_assoc($result))
                            {
                                    $files[]=$row['url'];
                                    echo '<li>';
                                   echo'<a id="file_list" href="manipulate.php?name='.$row['name'].'">';
                                    echo $row['name'];
                                    echo '</a>';
                                   echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;--';
                                   echo'<a id="file_list" href="home2.php?name='.$row['name'].'">';
                                   echo 'View details';
                                    echo '</a>';
                                   
                                    echo '</li>';
                                
                                
                            }
                    ?>
                    </ul>
                    <?php
                    if(isset($_POST['submit']))
                    {
                    $queryscore='SELECT *FROM scores where score_word="'.$_POST['search'].'"';
                    $resultscore=mysql_query($queryscore,$db) or die(mysql_error($db));
                    while($rows=mysql_fetch_assoc($resultscore))
                    {
                    echo '<h2 id="another_file" >Score of the keyword is : '.$score.'</h2>';
                    }
                    }
                ?>
                </form>
                <div id="another_file">
                    <a href="home2.php"><h3 id="another_file">Upload file to server</h3></a>
                </div>
            </div>
            
        </div>
        
       <!-- displaying words and sentences in the file --> 
       <?php 
        if(isset($_GET['name'])){
       ?>
         <div id="down">
            
            <form method="post" action="manipulate.php">
            <h4 style="display: inline;">Words: </h4>
            
            <select name="search">    
            <?php
                    
                    
                                $file_full_path=(isset($_GET['name']))?trim($_GET['name']):'';
                                
                                $file_path="uploads/".$_GET['name'];
				//$file=file_get_contents($file_full_path);
                                $file=file_get_contents($file_path);
                                $temp=explode(" ",$file);
                                $sentences=array();
                                foreach($temp as $value)
                                {
                                    if($value == "." || $value ==" "){continue;}
                                    else{
                                    echo '<option>';
                                    echo $value;
                                    echo '</option>';
                                    }
                                        
                                }
?>
                
            
            
            
            </select>
            <input type="submit" value="search" name="submit"/>
            </form>
             
             
            <form method="post" action="manipulate.php">
            <h4 style="display: inline;">Sentences: </h4>
            
            <select name="search">    
            <?php
                    
                    
                                $file_full_path=(isset($_GET['name']))?trim($_GET['name']):'';
                                
                                $file_path="uploads/".$_GET['name'];
				//$file=file_get_contents($file_full_path);
                                $file=file_get_contents($file_path);
                                $temp=explode(".",$file);
                                $sentences=array();
                                foreach($temp as $value)
                                {
                                    if($value == "." || $value ==" "){continue;}
                                    else{
                                    echo '<option>';
                                    echo $value;
                                    echo '</option>';
                                    }
                                        
                                }
?>
                
            
            
            
            </select>
            <input type="submit" value="search" name="submit"/>
            </form>
             
             
             <?php 
        }
             ?>
             <!-- end of displaying words in the file -->
          <script type="text/javascript" src="js/jquery-1.9.1.min.js" ></script>
        <script type="text/javascript" src="js/load.js"></script>
       
        </div>
        
    </body>
</html>


