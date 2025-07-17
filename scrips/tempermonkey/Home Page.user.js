// ==UserScript==
// @name         Home Page
// @namespace    http://tampermonkey.net/
// @version      2024-06-17
// @description  try to take over the world!
// @author       You
// @match        https://firstaidmadeeasy.com.pk/Student/MyCourses
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

        let topicsData = ['5, First Aid Step-1 Video Lectures','6, First Aid Step 2 - Ck Video Lectures','7, 3. KAPLAN Surgery Video Lectures'
                          ,'8, M.B.B.S - 1st Year Video Lectures','9, 2. FCPS PAST PAPERS VIDEO LECTURES','10, M.B.B.S - 2nd Year Video Lectures'
                          ,'11, M.B.B.S - 3rd Year Video Lectures','12, M.B.B.S - 4th Year Video Lectures','13, M.B.B.S - FINAL Year Video Lectures'
                          ,'14, Ophthalmology Video Lectures ( JATOI )','15, 4. NRE MOCK TEST VIDEO LECTURES','16, OPHTHALMOLOGY ( HIGH YIELD ) VIDEO LECTURES'
                          ,'17, Otorhinolaryngology ( E.N.T HIGH YIELD ) VIDEO LECTURES','18, ANATOMY SHELF NOTES','19, COVID - 19 VIDEO LECTURES'
                          ,'20, Obstetrics &amp; Gynecology VIDEO LECTURES','21, Obstetrics &amp; Gynecology ( UWORD Q-BANK ) VIDEO LECTURES','22, GENERAL PATHOLOGY ( PATHOMA )'
                          ,'23, Special Pathology VIDEO LECTURES','24, Pediatric Medicine Video Lectures','25, Medicine Video Lectures','26, Community Medicine Video Lectures'
                          ,'27, Mock Test - 5 Days Free Trial','28, 1. FIRST AID STEP 1 LECTURES for FCPS','29, First Aid Step 2 - Ck Video Lectures (N)','30, CNS and Respiratory system'
                          ,'31, First Aid Step-1 (J)','32, First Aid Step-2 (J)','33, Repid Review First Aid Step 1','34, MRCS course by Dr. Kashif','35, Bio-chemistery','36,  MD Paper 2'
                          ,'37, MS Paper 2','38, Anaesthesiology Video Lectures','39, Short Snell Anatomy','40, Breast Surgery','41, Cardiology','42, Critical Care','43, Dermatology'
                          ,'44, Plab','45, Endocrinology','46, ENT','47, Epidemiology','48, Ethics','49, Gastroenterology','50, Palliative Care','51, Perioperative Medicine','52, Pharmacology'
                          ,'53, Psychiatry','54, Radiology','55, Respiratory Medicine','56, Rheumatology','57, Urology','58, Vascular Surgery','59, General Surgery','60, Genetics','61, Genitourinary Medicine'
                          ,'62, Geriatric Medicine','63, Haematology','64, Human Factors and Quality Improvement','65, Infectious Disease','66, Maxillofacial Surgery','67, Nephrology','68, Neurology'
                          ,'69, Obstetrics and Gynaecology','70, Oncology','71, Ophthalmology','72, Orthopaedics','73, Paediatrics','74, Psychiatry','75, CNS','76, RENAL LECTURE','77, PLAB - Video Lectures'
                          ,'78, Plab Lectures (NRE)','79, First Aid Step 1 - 9 Organ systems Only - FAME Free Subscription','80, PLAB | Paediatric + ENT + Gyne Obs - FAME Free Subscription','81, NRE -2 Medicine'];

        let containerDiv = document.querySelector('#NoMessage');

        //flush existing container
        containerDiv.innerHTML = "";
        containerDiv.classList = "";
        document.querySelector('#kt_sliders_widget_2_slider').innerHTML = "";

        topicsData.forEach(i => {
            containerDiv.innerHTML += "<div style='border:green 2px solid; font-size:10px; width:100%; padding:5px' class='searchDiv'><a target='_blank' href='https://firstaidmadeeasy.com.pk/Home/Course_Preview/"+i.match('^\\d*')[0]+"'>"+i+"</a></div>";
        });

    }

    // Your code here...
})();