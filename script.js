document.getElementById("chat-form").addEventListener("submit", function(event){
   event.preventDefault(); //Prevent default form submission
   const userInput = document.getElementById("user-input").value.trim();
   if(userInput === ""){
    alert("Please enter a dream");
    return;
   }

   //Clear
   document.getElementById("user-input").value = "";

//Display User Messages
displayMessage(userInput, true);

//Use AJAX to make request to the Backend
const xhr = new XMLHttpRequest();
xhr.open('POST', 'api.php', true);

xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
xhr.onreadystatechange = function(){
    if(xhr.readyState === XMLHttpRequest.DONE){
        if(xhr.status === 200){   
            
          const response = JSON.parse(xhr.responseText);
           console.log("Raw response: ", xhr.responseText); // Log raw response
   
          if(!response.error && response.reply){
             
            displayMessage(response.reply, false); //Display Bot response
          } else{
              console.error("Error from Backend: ", response.error);
              displayMessage("Error fetching data. Please try again later.", false);
          }
        }else{
            console.error("HTTP error: ", xhr.status);
            displayMessage("Error fetching data. Please try again later.", false);
        }
    }
};
xhr.send('chat=' + encodeURIComponent(userInput));
});

function displayMessage(message, isUser){
    const chatMessages = document.getElementById("chat-messages");
    const messageElement = document.createElement('div');

    messageElement.textContent = message;
    messageElement.classList.add(isUser ? "user-message" : "bot-message");

chatMessages.appendChild(messageElement);

//Scroll to bottom of chat container
chatMessages.scrollTop = chatMessages.scrollHeight;
}
