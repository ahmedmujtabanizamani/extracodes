// ==UserScript==
// @name         Show Lecture
// @namespace    http://tampermonkey.net/
// @version      2024-06-17
// @description  try to take over the world!
// @author       You
// @match        https://firstaidmadeeasy.com.pk/Home/Course_Preview/*
// @icon         https://www.google.com/s2/favicons?sz=64&domain=firstaidmadeeasy.com.pk
// @grant        none
// ==/UserScript==

(function() {
    'use strict';

    let disclaimer = "";
    disclaimer += "Disclaimer for This Script\n\n";
    disclaimer += "This script is purely created for study purpose, by using this script you are bypassing some security features of this website.\n\n";
    disclaimer += "- Do not Share this Script\n";
    disclaimer += "- The use of this script is only for study purpose\n";
    disclaimer += "- Do not Share this script\n";
    disclaimer += "- The information is not belongs to us\n";
    disclaimer += "- Any legal action taken against you for using this script is all on you, we are not responsible for this\n\n";
    disclaimer += "Consent\n\n";
    disclaimer += "By using our Script, you hereby consent to our disclaimer and agree to its terms.";

    let agreedDiclaimer = confirm(disclaimer);
    if(agreedDiclaimer){

        let vidplayer = '<div class="v_container card h-xl-"><iframe style="height:30vh" id="MyIframe" class="vframe" src="https://cdn.pixabay.com/photo/2024/03/27/17/23/ai-generated-8659546_640.jpg" allow="fullscreen; accelerometer; gyroscope; encrypted-media; picture-in-picture;"></iframe></div>';
        let containerElement = document.querySelector('#kt_content_container');
        // Video IDs
        let hidden=document.querySelectorAll('[data-id].btnplayVideo');

        // set video player
        containerElement.innerHTML = vidplayer;
        // Video Element
        var videoElement = document.querySelector('#MyIframe');
        let dataid='\n';
        hidden.forEach(i=>{
            i.parentNode.addEventListener('click', e=>setVideo(videoElement,i.getAttribute('data-id')));
            dataid+=i.innerHTML+' '+i.getAttribute('data-id')+'\n';
            i.parentNode.childNodes[1].innerHTML= '';
            i.parentNode.style.cssText='border:green 2px solid; font-size:10px; width:100%; padding:2px;';
        });

        //Heading Highlight
        document.querySelectorAll('[data-target]').forEach(i=>{
            i.childNodes[1].style.cssText="border:red 2px solid; font-size:20px";
            i.childNodes[3].style.cssText="display:none";
        });
        // visible hidden videos
        document.querySelectorAll('div[style]').forEach(i=>{
            if(i.style.display == 'none')
            {
                i.style.cssText='style="!important display:block;"';
            }
        });
        // Hide purchase Detail
        document.querySelectorAll('#btnUnlockSec').forEach(i=>{i.style.display='none'});
        //document.querySelector('#course-toc-2').style.display='none';
        document.querySelector('#btnUnlockCo').parentNode.parentNode.parentNode.style.display='none';


    }else{
        alert("Thank you!!\n\nyou are not using any script now.");
    }
    // Your code here...

    async function setVideo(videoEle, num) {

        new window.swal({
            title: "Loading..."+event.target.innerHTML,
            text: "Please wait",
            //imageUrl: ,
            showConfirmButton: false,
            allowOutsideClick: false
        });

        videoEle.setAttribute('src', "https://firstaidmadeeasy.com.pk/Areas/Landing/assets/images/logo/fame-logo.png");
        const response = await fetch('https://firstaidmadeeasy.com.pk/Student/GetVideoPath?VideoID='+num+'&SecID=0');
        const videoInfo = await response.json();
        console.log(videoInfo);
        let videoURL = videoInfo.Video.Video_Path;
        videoEle.setAttribute('src', videoURL);

        new window.swal({
            title: "Finished!",
            showConfirmButton: false,
            timer: 1000
        });
        videoEle.scrollIntoView({behavior:'smooth'});
    }

})();