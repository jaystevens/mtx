<?php
   include('dbms.data');

   $link = mysql_connect($mysql_host,$mysql_user,$mysql_password)
        or die("Could not connect");
   mysql_select_db($mysql_dbms) or die("Could not select database");

  /* Okay, see if they submitted anything: */
  if ( "$l_enabled" != "" ) {
     /* create a MySQL insert statement: */
     $ld_enabled=(int)$l_enabled;
     $ld_worked=(int)$l_worked;
     $ld_osname=mysql_escape_string($l_osname);
     $ld_osversion=mysql_escape_string($l_osversion);
     $ld_description=mysql_escape_string($l_description);
     $ld_vendorid=mysql_escape_string($l_vendorid);
     $ld_productid=mysql_escape_string($l_productid);
     $ld_revision=mysql_escape_string($l_revision);
     $ld_barcodes=mysql_escape_string($l_barcodes);
     $ld_eaap=mysql_escape_string($l_eaap);
     $ld_transports=(int)$l_transports;
     $ld_slots=(int)$l_slots;
     $ld_transfers=(int)$l_transfers;
     $ld_imports=(int)$l_imports;
     $ld_tgdp=mysql_escape_string($l_tgdp);
     $ld_canxfer=mysql_escape_string($l_canxfer);
     $ld_serialnum=mysql_escape_string($l_serialnum);
     $ld_email=mysql_escape_string($l_email);
     $ld_name=mysql_escape_string($l_name);
      /* 'contributed' is calculated by 'now()' function */

     if ($ld_canxfer=="Yes") {
        $ld_canxfer=1;
     } else {
        $ld_canxfer=0;
     }

     if ($ld_eaap=="Yes") {
       $ld_eaap=1;
     } else {
        $ld_eaap=0;
     }	 
     if ($ld_tgdp=="Yes") {
	$ld_tgdp=1;
     } else {
        $ld_tgdp=0;
     }
     if ($ld_barcodes=="Yes") {
        $ld_barcodes=1;
     } else { 
        $ld_barcodes=0;
     } 

     $query_str="insert into loaders (enabled, worked,osname,osversion,description,vendorid,productid,revision,barcodes,eaap,transports,slots,imports,transfers,tgdp,canxfer,serialnum,email,name,contributed) values ( 1, 1, '$ld_osname', '$ld_osversion', '$ld_description', '$ld_vendorid', '$ld_productid', '$ld_revision', $ld_barcodes, $ld_eaap, $ld_transports, $ld_slots, $ld_imports, $ld_transfers, $ld_tgdp, $ld_canxfer, '$ld_serialnum', '$ld_email', '$ld_name', now())";
 
    /* now to insert it: */
    
    $result=mysql_query($query_str,$link);

    /* Now send mail to Eric telling him that it's been added.  */
    mail("eric@badtux.org","New Addition to MTX Compatibility List",$query_str);
    /* Now to go back to index.html: */
     header("Location: http://mtx.badtux.net/");
     exit();  

  }
   
?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
  <head>

    <title>Contribute to MTX compatibility list</title>

<style type="text/css">
  <!--
    TH { bgcolor: cyan;  }
    TR { background-color: white; }
    H1 { text-align: center; }
    BODY { background-color: white; }
     A:link { color: blue; text-decoration: none ; }

     A:active { text-decoration: underline ; }
     A:hover { text-decoration: underline ; }

     A:visited { color: violet; text-decoration: none ; }
  -->
</style>
  </head>

<body bgcolor="white">
<h1> Contribute to MTX Compatibility List </h1>

WARNING: This application is currently in beta test, and may be buggy. 
Please only use it to submit entries that you have verified as being
compatible with 'mtx'. 
<p>
You will need the following information:
<ol> 
<li> Your operating system type and version number (e.g. "cat /proc/version" 
on Linux, or "uname -v" on FreeBSD)
<li> Your MTX version ( mtx --version )
<li> The result of 'tapeinfo' on your loader
<li> The result of 'loaderinfo' on your loader
</ol> 
Note that your EMAIL address will *NOT* be published, but will only be used
by me if I have a question about your entry. 
<p>
<form action="contrib.php" method="POST">
<input type="hidden" name="l_enabled" value="1">
<input type="hidden" name="l_worked" value="1">

<!-- now for the table: -->
<table border=1 width="100%">
<tr>
   <th colspan=4 bgcolor="cyan">
         OS and General Info
    </th>
</tr>      
<tr>
    <!-- do a pulldown for operating system name:  -->
   <th align="right" bgcolor="cyan">Operating System:   
   <?php 
         $query_str="select osname from hosts group by osname order by osname";
         $result=mysql_query($query_str,$link) or die("</th></tr></table>Invalid query string '$query_str'");
    ?>
    </th>          
    <td> <select name="l_osname">
         <?php 
            while ($row=mysql_fetch_assoc($result)) {
               extract($row);
               if ("$osname" == "$l_osname") {
	           $selected="SELECTED";
               } else { 
                   $selected="";
               }
               print("<option value='$osname' $selected >$osname</option>");
            }
          ?> 
         </select>
     </td>
     <th align="right" bgcolor="cyan">OS Version: </th>
     <td>
      <input name="l_osversion' type="text" value="<?php print "$l_osversion"; ?>" size="40">
     </td>
   </tr>
   <tr> 
     <th align="right" bgcolor="cyan"> Loader Description:</th>
     <td colspan=3>
         <input name="l_description' type="text" value="<?php print "$l_description"; ?>" size="80">
     </td>
   </tr>
   <tr>
      <th colspan=4 bgcolor="cyan">TapeInfo output</th>
   </tr>
   <tr>
       <th align="right" bgcolor="cyan">Vendor ID: </th>
       <td> <input name="l_vendorid" type="text" value="<?php print "$l_vendorid"; ?>" size="40"> </td>
       <th align="right" bgcolor="cyan">Product ID: </th>
          <td> <input name="l_productid" type="text" value="<?php print "$l_productid"; ?>" size="40"> </td>
    </tr>
    <tr>
       <th align="right" bgcolor="cyan">Revision: </th>
       <td> <input name="l_revision" type="text" value="<?php print "$l_revision"; ?>" size="40"> </td>
       <th align="right" bgcolor="cyan">SerialNumber: </th>
       <td> <input name="l_serialnum" type="text" value="<?php print "$l_serialnum"; ?>" size="40"> </td>
    </tr>
    <tr>
       <th colspan=4 bgcolor="cyan">LoaderInfo output</th>
    </tr>
    <tr>
    <th align="right" bgcolor="cyan">Barcode Reader: </th>
    <td> <select name="l_barcodes">
         <option value="Yes" <?php if ($ld_barcodes==1) { print "SELECTED"; }  ?> >Yes</a>
         <option value="No" <?php if ($ld_barcodes==0) { print "SELECTED"; } ?> >No</a>
         </select>
    </td>
     <th align="right" bgcolor="cyan">Element Address Assignment Page: </th>
    <td> <select name="l_eaap">
         <option value="Yes" <?php if ($ld_eaap==1) { print "SELECTED"; } ?> >Yes</a>
         <option value="No" <?php if ($ld_eaap==0) { print "SELECTED"; } ?> >No</a>
         </select>
     </td>
   </tr>     
   <tr>
   <th align="right" bgcolor="cyan">Transfer Geometry Descriptor Page: </th>
    <td> <select name="l_tgdp">
         <option value="Yes" <?php if ($ld_tgdp==1) { print "SELECTED"; } ?> >Yes</a>
         <option value="No" <?php if ($ld_tgdp==0) { print "SELECTED"; } ?> >No</a>
         </select>
     </td>
   <th align="right" bgcolor="cyan">Can Transfer: </th>
    <td> <select name="l_tgdp">
         <option value="Yes" <?php if ($ld_tgdp==1) { print "SELECTED"; } ?> >Yes</a>
         <option value="No" <?php if ($ld_tgdp==0) { print "SELECTED"; } ?> >No</a>
         </select>
     </td>
  </tr>
  <tr> 
   <th align="right" bgcolor="cyan">Number of Medium Transport Elements: </th>
   <td> <input name="l_transports" type="text" value="<?php print "$l_transports"; ?>" size=15> </td>
   <th align="right" bgcolor="cyan">Number of Storage Elements: </th>       
   <td> <input name="l_slots" type="text" value="<?php print "$l_slots"; ?>" size=15> </td>
  </tr>
  <tr>
   <th align="right" bgcolor="cyan">Number of Import/Export Elements: </th> 
   <td> <input name="l_imports" type="text" value="<?php print "$l_imports"; ?>" size=15> </td>  
 
   <th align="right" bgcolor="cyan">Number of Data Transfer Elements: </th> 
   <td> <input name="l_transfers" type="text" value="<?php print "$l_transfers"; ?>" size=15> </td>  
  </tr>
  <tr>
    <th colspan=4>Personal Data</a>

  </tr>
  <tr>
   <th align="right" bgcolor="cyan">Your Name: </th> 
   <td> <input name="l_name" type="text" value="<?php print "$l_name"; ?>" size=30> </td>  
   <th align="right" bgcolor="cyan">Your EMAIL Address: </th> 
   <td> <input name="l_email" type="text" value="<?php print "$l_email"; ?>" size=30> </td>     
  </tr>
  <tr>
    <td colspan=2 align="right">
       <input type="submit" name="Save" value="Save">
     </td> </form>
     <form action="index.html" method="POST"> 
     <td>
     <input type="submit" name="Cancel" value="Cancel">
    </td>
    </form>
  </tr>
</table>

       
</body>
</html>


