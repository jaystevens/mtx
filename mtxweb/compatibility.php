<?php
   /* set these to what you need for your installation */
   $mysql_host="dbms.inhouse";
   $mysql_user="mtxuser";
   $mysql_password="password";
   $mysql_dbms="mtx";

   $link = mysql_connect($mysql_host,$mysql_user,$mysql_password)
        or die("Could not connect");
   mysql_select_db($mysql_dbms) or die("Could not select database");

   if ("$SORT_ORDER" == "" ) {
	$SORT_ORDER="vendorid";
   }

   $query_str="select * from loaders order by $SORT_ORDER";
   $result=mysql_query($query_str,$link) or die("Invalid query '$query_str'");
   $num_rows=mysql_num_rows($result);   

?><!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML//EN">
<html>
  <head>

    <title>MTX compatibility list</title>

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

<center>
<h1> MTX compatibility list </h1>
Sorted by: <?php
	print $SORT_ORDER; ?>
<p>
</center>

This application is currently in beta test, and may be buggy. The database
that it feeds off of is currently very incomplete. Please 
<a href="contrib.php">contribute</a> new entries so that others can
benefit. 

Sort by:<p>
<table border=1 width=100%">
<tr>
<td> <a href="compatibility.php?SORT_ORDER=vendor">Vendor</a></td>
<td> <a href="compatibility.php?SORT_ORDER=osname,osversion">OS</a></td>
<td> <a href="compatibility.php?SORT_ORDER=description">Description</a></td>
<td> <a href="compatibility.php?SORT_ORDER=slots">Number of Slots</a></td>
<td> <a href="compatibility.php?SORT_ORDER=worked">Compatible</a></td>
</tr>
</table>

<table border=1 width="100%">
<tr>
  <th bgcolor="cyan">OS Info</th>
  <th bgcolor="cyan">Loader </th>
  <th bgcolor="cyan">Loader Info</th>
  <th bgcolor="cyan">Loader Capabilities</th>
  <th bgcolor="cyan">Contributor</th>
</tr>
<?php
   if ($num_rows==0) {
?>
<tr> 
  <th> NO RECORDS IN DATABASE </th>
</tr>
<?php 
  } else { 
    while ($row = mysql_fetch_assoc($result)) {
	extract($row);
	
?>
      <tr>
      <td> <?php print "$osname $osversion"; ?> </td>
      <td>
        <table>
           <tr>
	             <th colspan=2 bgcolor="cyan"> $description </th>
           </tr>
           <tr>
             <th bgcolor="cyan" align="right"> Vendor ID: </th>
             <td> '<?php print "$vendorid"; ?>' </td>
           </tr>
           <tr>
             <th bgcolor="cyan" align="right"> Product ID: </th>
             <td> '<?php print "$productid"; ?>' </td>
           </tr>        
           <tr>
             <th bgcolor="cyan" align="right"> Revision: </th>
             <td> '<?php print "$revision"; ?>' </td>
           </tr>        
           <tr>
             <th bgcolor="cyan" align="right"> Serial Number: </th>
             <td> '<?php print "$serialnum"; ?>' </td>
           </tr>
	</table>
      </td>
      <td> <!-- <th bgcolor="cyan">Loader Info</th> -->
       <table border=1>
          <tr>
            <th bgcolor="cyan" align="right"> Media Slots: </th>
            <td> <?php print "$slots"; ?> </td>
          </tr>
          <tr>
            <th bgcolor="cyan" align="right"> Import/Export Slots: </th>
            <td> <?php print "$imports"; ?> </td>
          </tr>
          <tr> 
            <th bgcolor="cyan" align="right"> Drives: </th>               
            <td> <?php print "$transfers"; ?> </td>
          </tr>
          <tr>
            <th bgcolor="cyan" align="right"> Robot Arms: </th>    
            <td> <?php print "$transports"; ?> </td>
          </tr>
        </table>
      </td>
      <td> <!--  <th bgcolor="cyan">Loader Capabilities</th> -->
          <tr>
            <th bgcolor="cyan" align="right"> Element Address Assignment Page (EAAP) </th>
            <td> <?php if (eaap == "1") { ?>
                      Yes
                 <?php } else { ?>
                      No
                 <?php } ?>
            </td>
          </tr>
      </td>
  </tr>
  <?php }
  } ?>
</table>

</body>
</html>
