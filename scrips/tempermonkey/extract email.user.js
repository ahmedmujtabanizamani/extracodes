// ==UserScript==
// @name         Fetch Email
// @namespace    http://tampermonkey.net/
// @version      2025-07-11
// @description  try to take over the world!
// @author       You
// @icon         data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==
// @grant        none
// ==/UserScript==

(function() {
    'use strict';

// Function to extract email addresses from the page
function extractEmails() {
  const emailRegex = /\b[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}\b/g;
  const emails = document.body.innerHTML.match(emailRegex);
  return emails;
}
function removeDuplicates(ra) {
  return [...new Set(ra)];
}
// copy to clipboard does not work unless user click and generate the event manually
    async function copyToClipboard(str){
        await navigator.clipboard.writeText(str);
        alert("copied");
        document.querySelector('#progress-element').style.display = "none";
    }
// Function to append the button to the page
function appendButton() {
  const button = document.createElement('button');
  button.textContent = 'Fetch Emails';
  
  button.style.cssText = 'background-color:yellow;top:10px; right:10px; z-index: 1000000; position :fixed; color:blue; border-radius:50px; padding:10px';
  button.onclick = () => {
    const emails = removeDuplicates(extractEmails());
    alert(emails);
    if(emails){
    copyToClipboard(emails);}
  };
  document.body.appendChild(button);
}



// Append the button to the page
appendButton();
    // Your code here...
})();