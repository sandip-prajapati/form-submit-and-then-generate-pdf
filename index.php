<?php
if(isset($_POST['submit'])){

    //collect form data
    $title = $_POST['title'];
    $email = $_POST['email'];
    $Description = $_POST['Description'];
    $footer = $_POST['footer'];

    //check name is set
    if($title ==''){
        $error[] = 'Name is required';
    }
    
	$target_dir = "upload/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$actual_link = 'http://'.$_SERVER['HTTP_HOST'].'workshop_frompdf/'.$_FILES["fileToUpload"]["name"];
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "<p style='color:#ff0000'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<p style='color:#ff0000'>Sorry, your file was not uploaded.</p>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
      //  echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "<p style='color:#ff0000'>Sorry, there was an error uploading your file.</p>";
    }
}
	
    //check for a valid email address
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
         $error[] = 'Please enter a valid email address';
    }
    //check Description is set
     if($Description ==''){
        $error[] = 'Description is required';
    }
    //check Footer is set
     if($footer ==''){
        $error[] = 'Footer Message is required';
    }
	
    //if no errors carry on
    if(!isset($error)){

        //create html of the data
        ob_start();
        ?>

        <h1>Data from form</h1>
        <p>Title: <?php echo $title;?></p>
        <p><img src="<?php echo $target_dir . basename($_FILES["fileToUpload"]["name"]); ?>"/></p>
        <p>Email: <?php echo $email;?></p>
        <p>Description: <?php echo $Description;?></p>
       <!-- <p>Footer Message: <?php echo $footer;?></p>-->
        

        <?php 
        
        $body = ob_get_clean();

        $body = iconv("UTF-8","UTF-8//IGNORE",$body);

        include("mpdf/mpdf.php");
        
 
        $mpdf=new \mPDF('c','A4','','' , 0, 0, 0, 0, 0, 0); 
        
		// PDF footer content  
        $mpdf->SetHTMLFooter('<div class="pdf-footer">'.$footer.'</div>');
        //write html to PDF
        $mpdf->WriteHTML($body);
 header('Location: '.$_SERVER['PHP_SELF']);
        //output pdf
        $res = $mpdf->Output('demo.pdf','D');
        
}
}
if(isset($_POST['submitemail'])){
	
	 //collect form data
    $title = $_POST['title'];
    $email = $_POST['email'];
    $Description = $_POST['Description'];
    $footer = $_POST['footer'];

    //check name is set
    if($title ==''){
        $error[] = 'Name is required';
    }
	
	$target_dir = "upload/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$actual_link = 'http://'.$_SERVER['HTTP_HOST'].'workshop_frompdf/'.$_FILES["fileToUpload"]["name"];
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "<p style='color:#ff0000'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</p>";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "<p style='color:#ff0000'>Sorry, your file was not uploaded.</p>";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
       // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "<p style='color:#ff0000'>Sorry, there was an error uploading your file.</p>";
    }
}

    //check for a valid email address
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
         $error[] = 'Please enter a valid email address';
    }
    //check Description is set
     if($Description ==''){
        $error[] = 'Description is required';
    }
    //check Footer is set
     if($footer ==''){
        $error[] = 'Footer Message is required';
    }

    //if no errors carry on
    if(!isset($error)){

        //create html of the data
        ob_start();
        ?>

        <h1>Data from form</h1>
        <p>Title: <?php echo $title;?></p>
        <p><img src="<?php echo $target_dir . basename($_FILES["fileToUpload"]["name"]); ?>"/></p>
        <p>Email: <?php echo $email;?></p>
        <p>Description: <?php echo $Description;?></p>
       
        

        <?php 
        $body = ob_get_clean();

        $body = iconv("UTF-8","UTF-8//IGNORE",$body);

        include("mpdf/mpdf.php");
        $mpdf=new \mPDF('c','A4','','' , 0, 0, 0, 0, 0, 0); 

        //write html to PDF
        // PDF footer content  
        $mpdf->SetHTMLFooter('<div class="pdf-footer">'.$footer.'</div>');
        $mpdf->WriteHTML($body);
       
 
        //output pdf
        //$mpdf->Output('demo.pdf','D');
	
        //save to server
        //$mpdf->Output("mydata.pdf",'F');
     		
     		$content = $mpdf->Output('', 'S'); // Saving pdf to attach to email 
$content = chunk_split(base64_encode($content));

// Email settings
$mailto = "sandipprajapati7720@gmail.com"; 
$from_mail = "sandipprajapati68@yahoo.com"; 
$from_name = 'LUBUS PDF Test';
//$from_mail = 'email@domain.com';
$replyto = 'email@domain.com';
$uid = md5(uniqid(time())); 
$subject = 'mdpf email with PDF';
$message = 'Download the attached pdf';
$filename = 'lubus_mpdf_demo.pdf';

$header = "From: ".$from_name." <".$from_mail.">\r\n";
$header .= "Reply-To: ".$replyto."\r\n";
$header .= "MIME-Version: 1.0\r\n";
$header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";
$header .= "This is a multi-part message in MIME format.\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-type:text/plain; charset=iso-8859-1\r\n";
$header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
$header .= $message."\r\n\r\n";
$header .= "--".$uid."\r\n";
$header .= "Content-Type: application/pdf; name=\"".$filename."\"\r\n";
$header .= "Content-Transfer-Encoding: base64\r\n";
$header .= "Content-Disposition: attachment; filename=\"".$filename."\"\r\n\r\n";
$header .= $content."\r\n\r\n";
$header .= "--".$uid."--";
$is_sent = @mail($mailto, $subject, "", $header);

//$mpdf->Output(); // For sending Output to browser
//$mpdf->Output('lubus_mdpf_demo.pdf','D'); // For Download
}
if($is_sent)
{
	echo "mail send";
}			
else
{
echo "mail not send";	
}
			


    }


//if their are errors display them
if(isset($error)){
    foreach($error as $error){
        echo "<p style='color:#ff0000'>$error</p>";
    }
}
?> 

<form action='' method='post' enctype="multipart/form-data">
<p><label>Title</label><br><input type='text' name='title' value=''></p> 
    <input type="file" name="fileToUpload" id="fileToUpload">
<p><label>Email</label><br><input type='text' name='email' value=''></p> 
<p><label>Description</label><br><input type='text' name='Description' value=''></p> 
<p><label>Footer Message</label><br><input type='text' name='footer' value=''></p> 
<p><input type='submit' name='submit' value='Generate PDF'></p> 
<p><input type='submit' name='submitemail' value='eMail submit form with PDF'></p> 
</form>