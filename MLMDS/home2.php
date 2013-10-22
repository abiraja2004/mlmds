<?php
session_start();
include 'db.inc.php';
$db=mysql_connect(MYSQL_HOST,MYSQL_USER,MYSQL_PASSWORD) or die('unable to connect ..');
mysql_select_db(MYSQL_DB,$db) or die(mysql_error($db));

function manipulate()
{
        global $file,$content,$numchars,$linescount,$handle,$filename,$numwords;
        
        $content=file_get_contents($file);
        
        $numchars=strlen($content);
        
        $numwords=str_word_count($content);
        
        $linescount=0;
        $handle= fopen($file,"r");
        while(!feof($handle))
        {
            $numlines[]=fgets($handle);
            $linescount++;
        }
        fclose($handle);
}

if(isset($_GET['name']))
{
            $query='SELECT url,name FROM filedetails where name="'.$_GET['name'].'"';
        $result=mysql_query($query,$db) or die(mysql_error($db));
        
        $files=mysql_fetch_assoc($result);
      
        $file = $files['url'];
        $filename = $files['name'];

        
    manipulate();
    
}

if(isset($_POST['submit']))
{
     $file=(isset($_POST['file']))?trim($_POST['file']):'';
    $uploaddir='uploads/';
    $file=$uploaddir.basename($_FILES['file']['name']);
    $url='uploads/'.$_FILES["file"]["name"];
    $filename=$_FILES["file"]["name"];
    if(move_uploaded_file($_FILES['file']['tmp_name'],$file))
    {
        $query='INSERT IGNORE INTO filedetails(name,url) values("'.$_FILES['file']['name'].'","'.$url.'")';
        $result=mysql_query($query,$db) or die(mysql_error($db));
        
        //reading the contents of the files
        
        manipulate();
        
        
    }
    else
    {
        echo 'upload failed';
    }
}

?>
<html>
    <head>
        <title>Document summarization</title>
        <link rel="stylesheet" href="css/style.css" />
        <script type="text/javascript" src="js/jquery-1.9.1.min.js" ></script>
        <script type="text/javascript">
            function dummy(){
            //jQuery.facebox('whatever you want to ask');
            alert("Uploading files .....");
            }
        </script>
        
    </head>
    <body>
         <div id="header">
                <h1>Multidocument Summarization</h1>
            </div>
        <div id="big_wrapper">
           
            <div id="left_side">
                <div id="top">
                    <div id="top_left">
                       
                        <form  enctype="multipart/form-data" name="upload" method="post" action="home2.php">
                            <?php if(!isset($file))
                            {
                            ?>
                            <style>
                                #left_side{
                                    height: 300px;
                                    width: 700px;
                                    float: left;
                                    padding-top: 30px;
                                    -moz-transition-duration: 2s;
                                    -webkit-transition-duration: 2s;
                                }
                                #right_side{
                                    position: absolute;
                                    right: 160px;
                                    top: 70px;
                                }
                            </style>
                            
                            <h2 >Select document for summarization</h2><br />
                            <form name="up" onsubmit="return validateForm()">
                            <input type="file" name="file" />
                            <input type="image" src="up.png" style="height: 50px; width: 50px;" value="upload" name="submit" onclick="dummy()" />
                            </form>
                        
                        <?php }
                            else{
                                echo '<center>';
                                echo '<h1>';
                                //echo $_FILES["file"]["name"];
                                echo $filename;
                                echo '</h1>';
                                echo '</center>';
                            }
                        ?>
                        
                        </form>
                    </div>
                    <div id="top_right">
                       
                       <?php if(isset($numchars))
                        {
                       ?>
                       
                        <h3>DOCUMENT STATUS</h3>
                            <div id="status">
                            <h4>Characters:  <?php if(isset($numchars)){ echo $numchars; } ?>&nbsp;&nbsp;
                                 Words: &nbsp; <?php if(isset($numwords)){ echo $numwords; } ?>&nbsp;&nbsp;
                                 Lines: &nbsp; <?php if(isset($linescount)){ echo $linescount; } ?>
                            </h4>
                        </div>
       
                            <pre id="text"><?php if(isset($file)){ echo $content;}  ?>
                            </pre>
          
                    </div>
                </div>
                    
                <a href="home2.php"  id="another_file"><h2 >Click to add another file</h2></a>
                <?php
                        }
                        else{
                            ?>
                            <style>
                                #top{
                                    text-align: center;
                                }
                            </style>
                            
                            <?php
                                
                                
                        }
                    ?>
                </div>    
                <div id="right_side">
                    <h1 id="files">FILES</h1>
                    <ul>
                    <?php
                        $query='SELECT * FROM filedetails';
                            $result=mysql_query($query,$db) or die(mysql_error($db));
                            
                            while($row=mysql_fetch_assoc($result))
                            {
                                    $files[]=$row['url'];
                                    echo '<li>';
                                   echo'<a id="file_list" href="home2.php?name='.$row['name'].'">';
                                    echo $row['name'].'<br />';
                                    echo '</a>';
                                    echo '</li>';
                                
                                
                            }
                            
                    
                    ?>
                    </ul>
                    <a href="manipulate.php" id="another_file"><h2>Summarize these multiple documents</h2></a>
                </div>
        </div>
        <script type="text/javascript" src="js/jquery-1.9.1.min.js" ></script>
        <script type="text/javascript" src="js/load.js"></script>
        <script type="text/javascript" src="js/unload.js"></script>
       
    </body>
</html>