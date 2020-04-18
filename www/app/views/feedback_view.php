<html>
<head>
	<meta charset="utf-8">
	<title>AJAX Form Without jQuery</title>
	<script type="text/javascript">
		/* Usage */
		window.onload = function (){
    		AJAXform('my-form', 'my-button', 'my-result', 'post' ); // AJAXform( 'your-form-id', 'button-id', 'result-element-id', 'post or get method' );
		};
	</script>
</head>
<body>
<?php
    if (isset($_SESSION['username']))
    {
        echo <<< T
        <div id="h" class="wrapper">
	<div id="h" class="content">
        <form class="box" action="/feedback" id="my-form">
        <p> your name: </p> <input type="text" name="name" value="{$_SESSION['username']}" placeholder="your name" required>
        <textarea id="message" name="message" rows="4" cols="35" placeholder="Enter your message here..." required></textarea>
    	<input type="submit" name="submit" value="Send me!" id="my-button">
    </form></div></div>
T;
    }
    else
    {
        echo <<< T
        <div class="wrapper" id="h">
	<div class="content" id="h">
    <form class="box" action="/feedback" id="my-form">
        <input type="text" name="name" value="" placeholder="your name" required>
        <input type="email" name="email" value="" placeholder="some@email.com" required>
        <textarea id="message" name="message" rows="4" cols="35" placeholder="Enter your message here..." required></textarea>
    	<input type="submit" name="submit" value="Send me!" id="my-button">
	</form></div></div>
T;
} ?>
	<div id="my-result"></div>

</body>
</html>

<script>

function AJAXform( formID, buttonID, resultID, formMethod = 'post' ){
    var selectForm = document.getElementById(formID); // Select the form by ID.
    var selectButton = document.getElementById(buttonID); // Select the button by ID.
    var selectResult = document.getElementById(resultID); // Select result element by ID.
    var formAction = document.getElementById(formID).getAttribute('action'); // Get the form action.
    var formInputs = document.getElementById(formID).querySelectorAll("input"); // Get the form inputs.

    function XMLhttp(){
        var httpRequest = new XMLHttpRequest();
        var formData = new FormData();

        for( var i=0; i < formInputs.length; i++ ){
            formData.append(formInputs[i].name, formInputs[i].value); // Add all inputs inside formData().
        }
        formData.append("message", document.getElementById("message").value);

        httpRequest.onreadystatechange = function(){
            if ( this.readyState == 4 && this.status == 200 ) {
                selectResult.innerHTML = this.responseText; // Display the result inside result element.
            }
        };

        httpRequest.open(formMethod, formAction);
        httpRequest.send(formData);
        
    }
    selectButton.onclick = function(){ // If clicked on the button.
       XMLhttp();
            document.getElementsByClassName("wrapper")[0].style.display = 'none';
            document.getElementsByClassName("content")[0].style.display = 'none';
            document.getElementsByClassName("box")[0].style.display = 'none';
    }

    selectForm.onsubmit = function(){ // Prevent page refresh
        return false;
    }
}
</script>