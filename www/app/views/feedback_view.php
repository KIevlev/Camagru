<main role="main">

<section>

    <h2>Your Details</h2>

    <div id="response"></div>

    <form name="contact-form" id="contact">
<?php if(isset($_SESSION['username']))
    {
        echo <<< T
     <label for="nickname">nickname - {$_SESSION['username']}</label>
     <label for="website">Website (Optional)</label>
        <input id="website" type="url" placeholder="Website" title="Please enter a valid URL (must begin with 'http://' or 'https://')." />
        <label for="message">Message</label>
        <textarea id="message" placeholder="Enter your message here..." form="contact" required="required"></textarea>
        <input type="button" value="Vote now" onclick="javascript:AJAXPost(this)">
T;
    }
    else{
        echo <<< T
        <label for="name">First Name</label>
        <input id="name" type="text" placeholder="Name" pattern="[A-Za-z\- ]+" title="Please enter a valid first name." required="required" />

        <label for="email">Email Address</label>
        <input id="email" type="email" placeholder="Email Address" required="required" />

        <label for="website">Website (Optional)</label>
        <input id="website" type="url" placeholder="Website" title="Please enter a valid URL (must begin with 'http://' or 'https://')." />

        <label for="message">Message</label>
        <textarea id="message" placeholder="Enter your message here..." form="contact" required="required"></textarea>

        <button type="submit">Submit</button>
T;
    }
?>
        
    </form>

</section>

</main>

</body>
</html>
<script>


function AJAXPost(formsubmission){

	var Name = encodeURIComponent(document.getElementById("name").value);
	var email = encodeURIComponent(document.getElementById("email").value);
	var website = encodeURIComponent(document.getElementById("website").value);
	var message = encodeURIComponent(document.getElementById("message").value);

	// Checks if fields are filled-in or not, returns response "<p>Please enter your details.</p>" if not.
	if(Name == "" || email == "" || message == ""){
		document.getElementById("response").innerHTML = "<p>Please enter your details.</p>";
		return;
	}

	// Parameters to send to PHP script. The bits in the "quotes" are the POST indexes to be sent to the PHP script.
	var params = "name=" + firstName + "&email=" + email + "&message=" + message;

	var http = new XMLHttpRequest();
	http.open("POST","/feedback",true);

	// Set headers
	http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	http.setRequestHeader("Content-length", params.length);
	http.setRequestHeader("Connection", "close");

	http.onreadystatechange = function(){
		if(http.readyState == 4 && http.status == 200){
			document.getElementById("response").innerHTML = http.responseText;
		}
	}
	http.send(params);
	formsubmission.preventDefault();
}

</script>