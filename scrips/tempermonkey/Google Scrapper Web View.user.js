// ==UserScript==
// @name         Google Scraper - High Speed (No Images)
// @namespace    http://tampermonkey.net/
// @version      5.0
// @description  Blocks images and visual bloat to extract Website + FB faster
// @author       You
// @match        https://www.google.com/search*
// @grant        none
// ==/UserScript==

(function() {
    'use strict';
    
            // Create a new meta element
        var meta = document.createElement('meta');
        // Set the name and content attributes for the viewport meta tag
        meta.setAttribute('name', 'viewport');
        meta.setAttribute('content', 'width=device-width, initial-scale=1;user-scalable=no;user-scalable=0;');
        // Append the meta element to the head section of the document
    document.head.appendChild(meta);

    let str = "";

    // --- 1. PERFORMANCE OPTIMIZER: Block images and map visuals ---
    const blockVisualBloat = () => {
        const style = document.createElement('style');
        style.innerHTML = `
            img, svg, canvas, [role="img"], .f6999e, #rhs, .Lu09S { 
                display: none !important; 
            }
            * { 
                animation: none !important; 
                transition: none !important; 
            }
            #center_col { width: 100% !important; max-width: 100% !important; }
        `;
        document.head.appendChild(style);
    };

    async function sleep(delay) {
        return new Promise((resolve) => setTimeout(resolve, delay));
    }

    const cleanUrl = (url) => {
        if (!url) return "NA";
        if (url.includes('url?q=')) {
            try {
                return decodeURIComponent(url.split('url?q=')[1].split('&')[0]);
            } catch(e) { return url; }
        }
        return url;
    };

    async function extractData() {
        str = "Name | Website | Facebook\n";
        // Select entries by data-cid (most stable selector)
        let entries = document.querySelectorAll('div[data-cid], .VkpSyc, .uMdZh');
        
        const progress = document.querySelector('#progress-element');
        progress.style.display = "block";
        progress.innerHTML = "<b>Fast Extraction Active (Images Disabled)</b><br><hr>";

        for (let i = 0, j = 0; i < entries.length; i++) {
            let x = entries[i];
            let nameEle = x.querySelector('[role="heading"], .OSrXXb, .EllIPb');
            let entryName = nameEle ? nameEle.innerText : "Unknown";

            // Click entry to trigger background data fetch
            if (nameEle) nameEle.click();
            
            // Reduced sleep because images aren't loading, text loads faster
            await sleep(800); 

            let websiteLink = "NA";
            let fbLink = "NA";

            // 1. Grab Website from the details panel
            let webBtn = document.querySelector('a[aria-label*="Website"], a[data-footer-section-id="website"], a.ab_button[href^="http"]:not([href*="google.com"])');
            if (webBtn) websiteLink = cleanUrl(webBtn.href);

            // 2. Grab FB from details panel links
            let allLinks = document.querySelectorAll('a[href*="facebook.com"]');
            if (allLinks.length > 0) {
                fbLink = cleanUrl(allLinks[allLinks.length - 1].href);
            }

            // Fallback for Website if panel failed
            if (websiteLink === "NA") {
                let fallback = x.querySelector('a[href^="http"]:not([href*="google.com"])');
                if (fallback) websiteLink = cleanUrl(fallback.href);
            }
            if(entryName.includes('My Ad Centre')){
                continue;
            }
            j++;
            progress.innerHTML += `[${j}] ${entryName}<br>🔗 ${websiteLink}<br>📘 ${fbLink}<hr>`;
            str += `${j} ${entryName} | ${websiteLink} | ${fbLink}\n`;
            progress.scrollTop = progress.scrollHeight;
        }

        document.querySelector('#copybtn').style.display = "block";
        alert("Scrape Complete!");
    }

    // --- UI Setup ---
    const setupUI = () => {
        blockVisualBloat();

        let container = document.createElement("div");
        container.style.cssText = "position: fixed; top: 10px; left: 10px; z-index: 99999; display: flex; gap: 5px;";

        let btn = document.createElement("button");
        btn.innerText = "⚡ FAST SCRAPE";
        btn.style.cssText = "background: #000; color: #0f0; border: 1px solid #0f0; padding: 10px; cursor: pointer; font-family: monospace;";
        btn.onclick = extractData;

        let copybtn = document.createElement("button");
        copybtn.innerText = "COPY CSV";
        copybtn.id = "copybtn";
        copybtn.style.cssText = "display:none; background: #0f0; color: #000; border: none; padding: 10px; cursor: pointer; font-weight: bold;";
        copybtn.onclick = () => { document.querySelector('#progress-element').style.display = 'none'; navigator.clipboard.writeText(str); alert("Copied!"); };

        container.appendChild(btn);
        container.appendChild(copybtn);
        document.body.appendChild(container);

        let progressEle = document.createElement("div");
        progressEle.id = "progress-element";
        progressEle.style.cssText = "overflow: auto; display: none; position: fixed; right: 10px; top: 10px; width: 80%; height: 90vh; background: #000; color: #0f0; padding: 15px; font-family: monospace; font-size: 11px; z-index: 10000; border: 1px solid #0f0;";
        document.body.appendChild(progressEle);
    };

    if (document.readyState === 'complete') setupUI(); else window.addEventListener('load', setupUI);
})();
