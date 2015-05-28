<?php
/* Written by Sofina Pradhan
 * Date: 2015/05/12
 * Usage: show the contact information for all Agencies. As well as, entry form to submit inquiry
 */
require("header.php");
include("db.php");

function formatPhone($phone)   //function to format the phone/fax number
{
    $phone = preg_replace("/[^0-9]/", "", $phone);
    if(strlen($phone) == 7)  
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $phone);  //format 223-1258
    elseif(strlen($phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);  //format 403-223-2458
    else
        return $phone;
}


?>
<link href="contact.css" type="text/css" rel="stylesheet">   

<script>
    var address = "";  
	
	function phoneValidate()  /* checking the entered value for the phone number. the format should be 403-256-1458 */
	{
	   	var msg;
		var validno = new RegExp(/[0-9]{3}-[0-9]{3}-[0-9]{4}/);    
		if (!validno.test(frmcontact.phoneno.value))
		{
			msg = "The phone number you entered is not valid!";
			document.getElementById("error").innerHTML = msg;
			document.getElementById("error").style.color="red";
			return false;
		}
		else
		{
		  msg = "";
		  document.getElementById("error").innerHTML = msg;
		  document.getElementById("error").style.color="red";
		  return true;
		}
	}
	
	
	
	function changeMousePointer(i)
	{
		document.getElementById("leftcol"+ i).style.cursor = "pointer";
	}
    	 
	function mouseClick(agencyid)
	{ 
	    var newwin;							                                                                   /*passing agencyid from contact page to contactdetail page*/
		newwin = window.open("contactdetail.php?id='" + agencyid +"'",'','width=870 height=400 menubar=no');   
		newwin.moveTo(300,250);
	}

    function validate()   /* checking if the feilds are empty or not*/
	{
	     var message = "";
		 var myform = document.getElementById("frmcontact");
	     if (myform.fname.value == "")
		 {
		     message = message + "First name";
			 myform.fname.style.backgroundColor ="#F7BB86";    /* if empty, message will be shown and the color of the text box changed */
		  }
		  
		  if (myform.lname.value == "")
		  {
		    if (message != "")
		     {
		    	message = message + ",";
	         }
		      message = message + "Last Name";
			  myform.lname.style.backgroundColor ="#F7BB86";
		  }
		  
		  if (myform.email.value == "")
		  {
		     if ( message != "")
			 {
			    message = message +",";
			 }
			 message = message + "Email Address";
			 myform.email.style.backgroundColor ="#F7BB86";
		   }
		   
		   if (myform.comments.value == "")
		   {
		      if (message != "")
			 {
			    message = message +",";
			 }
			 message = message + "Comments";
			 myform.comments.style.backgroundColor ="#F7BB86";
		   }
		   
		   
		   
		   if (message == "")
		   {
		      return phoneValidate();
			  return true;
		   }
		   else
		   {
				message = message + " must not be blank!";
				document.getElementById("error").innerHTML = message;
				document.getElementById("error").style.color = "red";
				return false;
		   }  
           
		    
	
	}
</script>

<!DOCTYPE Html>


<html>
  

<body>
<main> 
	<div class="imgfeildset">
	    <p font size="2" align="right">* Click the addresses below to get the list of agents</p>
		<h3>Agencies Contact Information:</h3><br/>
		<?php
			$query = "select * from Agencies";
			$result = mysqli_query($con, $query) or die (" SQL query error");
			$i=0;
			while ($row = mysqli_fetch_assoc($result))
			{
				echo " <table>";
					echo "<tr><td width=200 >";
					$mainaddress = "";
					$phoneno = "";
					$faxno = "";
					$mainaddress  = "Address:$row[AgncyAddress]<br />";
					$mainaddress .= "$row[AgncyCity] \n";
					$mainaddress .= "$row[AgncyProv] <br />";
					$mainaddress .= "$row[AgncyPostal] \n";
					$mainaddress .= "$row[AgncyCountry] <br />";
					
					$phoneno = formatPhone("$row[AgncyPhone]");  //calling for formatphone function so that the phone number can be display in this format xxx-xxx-xxxx
					$mainaddress .= "Phone No: $phoneno <br />";
					
					$faxno = formatPhone("$row[AgncyFax]");   //calling for formatphone function so that the fax number can be display in this format xxx-xxx-xxxx
					$mainaddress .= "Fax No: $faxno <br />";
					
					echo "<p id='leftcol$i' onmouseover='changeMousePointer($i)' onClick='mouseClick($row[AgencyId])'>";
					echo "$mainaddress";
					echo "</p>";
					 // assigning iframe properties to show google maps for the addresses. The address is being retrieved fron the database. One more column needs to be added to Agency table
					$outimage = "<iframe width='500' height='150' frameborder='1' scrolling='no' marginheight='0' marginwidth='0' src='";
					$outimage .= $row["AgncyMap"];
					$outimage .= "'></iframe>";
					print("$outimage <br />");
					echo "<hr>";
					echo "</td></tr>";
					echo "<tr height=8></tr>";
					$i = $i + 1;
				echo "</table>";
			}
			mysqli_close($con);
		?>
		
	</div>
	
	
	<div class="emailfeildset"> 
	      <p><h3>If you have any questions, please call us or submit your contact information.</h3></p> 
		  		  
		  <p id="error"></p>
		  <form id="frmcontact" method="post" action="sent.php">    <!-- sending information entered in the inquiry form to sent.php page -->
		        <table cellpadding="3" cellspacing="0" border="0" width="98%" align="left">
			        <tr>
				       <td class="tblleftcol"><label for="fname">Your First Name:</td>
					   <td class="tblrightcol"><input tabindex=1 type="text" name="fname" size=30></td>
				    </tr>
				    <tr>
				      <td class="tblleftcol"><label for="lname">Your Last Name:</label></td>
					  <td class="tblrightcol"><input tabindex=2 type="text" name="lname" size=30></td>
				    </tr>
				    <tr>
				       <td class="tblleftcol"><label for="email">Your Email Address:</label></td>
					   <td class="tblrightcol"><input tabindex=3 type="email" name="email" size=30></td>
				    </tr>
				    <tr>
				       <td class="tblleftcol"><label for="phoneno">Phone Number:</label></td> 
					   <td align="left"><input type="text" tabindex=4 maxlength=12 size=30 placeholder="XXX-XXX-XXXX" name="phoneno" onBlur="phoneValidate()"></td>   
				    </tr>
				    <tr>
				       <td class="tblleftcol"><label for="comments">Comments:</label></td>
					   <td class="tblrightcol"><textarea tabindex=7 name="comments" rows="4" cols="28
					   "></textarea></td>
				    </tr>
			   	    <tr>
				      <td></td><td align="left"><input tabindex=8 type="submit" value="Submit" onclick="return validate()">  <!-- checking for field validation -->
				        <input tabindex=9 type="reset" value="Reset"  class="tblrightcol"></td>
				    </tr>
			    </table>
		  </form>
	</div>
</main> 

</body>

</html>

<?php
require ("footer.php");
?>
