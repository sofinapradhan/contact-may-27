<?php

/* Written by Sofina Pradhan
 * Date: 2015/05/17
 * Usage: show the contact information for all Agencies, as well as all agents under each agencies.
 */
 
   include("db.php");  
   $phoneno = "";
   $faxno = "";      
   $agentid =$_GET['id'];    //getting agentid from contact page to show the list of agents for the particular Agency
   $query = "select * from Agencies where Agencyid = $agentid ";
   $result = mysqli_query($con, $query) or die (" SQL query error");
   while ($row = mysqli_fetch_assoc($result))
   {
        $mainaddress = "";
		$mainaddress  = "<p><h4>Address: $row[AgncyAddress]<br />";
		$mainaddress .= "$row[AgncyCity] \n";
		$mainaddress .= "$row[AgncyProv] <br />";
		$mainaddress .= "$row[AgncyPostal] \n";
		$mainaddress .= "$row[AgncyCountry] <br />";
		$phoneno = formatPhone("$row[AgncyPhone]");  //calling for formatphone function so that the phone number can be display in this format (xxx) xxx-xxxx
		$mainaddress .= "Phone No: $phoneno <br />";
					
		$faxno = formatPhone("$row[AgncyFax]");   //calling for formatphone function so that the fax number can be display in this format (xxx) xxx-xxxx
		$mainaddress .= "Fax No: $faxno <br /></h4></p>";
		print($mainaddress);
   }
   
   function formatPhone($phone)   //function to format the phone/fax number
   {
    $phone = preg_replace("/[^0-9]/", "", $phone);

    if(strlen($phone) == 7)  
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);  //format should be 223-1258 if its length is 7
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);  //format should be 403-223-2458 if its length is 10
    else
        return $phone;
    }
   
?>   
	
<!DOCTYPE html>


<html>
	<head>
		<title>Agents Information</title>
		<link href="contact.css" type="text/css" rel="stylesheet">   
	</head>
	<body bgcolor=#FFCC66>  
			
					<?php   
						$phoneno = "";				
										// show the list of agents filter by Agencyid
					    $sql = "select AgtFirstName,AgtMiddleInitial,AgtLastName,AgtBusPhone,AgtEmail,AgtPosition from agents where Agencyid = $agentid order by AgtFirstName";
						$result = mysqli_query($con, $sql) or die("SQL Error");
                           
					?>
					
					<table border="1" cellpadding="5" cellspacing="0">
					   <thead>
	                     <tr>
						    <th class="tableheader">First Name</th>
							<th class="tableheader">Middle Name</th>
							<th class="tableheader">Last Name</th>
							<th class="tableheader">Business Phone Number</th>
							
							<th class="tableheader">Email Address</th>
							<th class="tableheader">Position</th>
						 </tr>
					   </thead>	 
	   
						<tbody>
						    <?php   while($row = mysqli_fetch_assoc($result))  { ?>
						    <tr>
							    <td><?php echo $row["AgtFirstName"]; ?></td>
							    <td><?php echo $row["AgtMiddleInitial"]; ?></td>
							    <td><?php echo $row["AgtLastName"]; ?></td>
							   					
								<?php $phoneno = formatPhone("$row[AgtBusPhone]"); ?>  <!-- calling for formatphone function so that the phone number can be display in this format (xxx) xxx-xxxx -->
								
								<td><?php echo $phoneno; ?></td> 
								<td><?php echo $row["AgtEmail"]; ?></td>
							    <td><?php echo $row["AgtPosition"]; ?></td>
						    </tr>
							<?php } ?>  
							<?php  mysqli_close($con); ?>
			            </tbody>
			        </table>
	</body>
	
</html> 
