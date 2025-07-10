# Login
res=$(curl 'http://192.168.0.1/HNAP1/' -X POST -H 'Content-Type: text/xml' -H 'SOAPAction: "http://purenetworks.com/HNAP1/Login"' --data-raw '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><Login xmlns="http://purenetworks.com/HNAP1/"><Action>request</Action><Username>Admin</Username><LoginPassword></LoginPassword><Captcha></Captcha></Login></soap:Body></soap:Envelope>' -s)

# parameters retrive from first request
pass="password string"
challenge=$(echo $res | grep -oP "Challenge>[^<]*" | sed "s/Challenge>//g" | sed "s/[\r\n\t ]//g")
pubkey=$(echo $res | grep -oP "PublicKey>[^<]*" | sed "s/PublicKey>//g" | sed "s/[\r\n\t ]//g")
cookie=$(echo $res | grep -oP "Cookie>[^<]*" | sed "s/Cookie>//g" | sed "s/[\r\n\t ]//g")
# login preps
loginKey=$pubkey$pass
keya=$(echo -n $challenge | openssl md5 -hex -mac HMAC -macopt key:$loginKey | grep -oE ".{32}$")
keyb=$(echo -n $challenge | openssl md5 -hex -mac HMAC -macopt key:${keya^^} | grep -oE ".{32}$")
hashedPass=${keyb^^}
echo "hashed Password = ${hashedPass}"
echo "cookie = ${cookie}"
echo "Private Key = ${keya^^}"

curl 'http://192.168.0.1/HNAP1/' -X POST -H 'Content-Type: text/xml' -H 'SOAPAction: "http://purenetworks.com/HNAP1/Login"' -H "Cookie: uid=${cookie}" --data-raw '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><Login xmlns="http://purenetworks.com/HNAP1/"><Action>login</Action><Username>Admin</Username><LoginPassword>'${hashedPass}'</LoginPassword><Captcha></Captcha></Login></soap:Body></soap:Envelope>'


# Sample
# 88FB59B7F9EA4EA63C4AA490EE8AE6B1

# GetMultipleHNAPs

# HNAP_AUTH 07F523E702CB23DE1F96EE12AE683BFD 1739556214


# // Commands
# openssl md5 -hex -mac HMAC -macopt key:"a" bb.txt
currAPI="GetClientInfo"
ts=$(date +"%s")
msg=${ts}'"http://purenetworks.com/HNAP1/'${currAPI}'"'
pkey=${keya^^}

hkey=$(echo -n ${msg} | openssl md5 -hex -mac HMAC -macopt key:${pkey} | grep -oE ".{32}$")


# APIs
# GetClientInfo
# GetWanSettings

# curl 'http://192.168.0.1/hnap/GetMultipleHNAPs.xml?v=20190621180447' -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: */*' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Statistics.html' -H "Cookie: uid=${cookie}"

# curl 'http://192.168.0.1/HNAP1/' -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: text/xml' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml' -H 'SOAPACTION: "http://purenetworks.com/HNAP1/GetMultipleHNAPs"' -H "HNAP_AUTH: ${hkey^^} ${ts}" -H 'Origin: http://192.168.0.1' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Statistics.html' -H "Cookie: uid=${cookie}" -H 'Sec-GPC: 1' --data-raw $'<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">\n<soap:Body>\n<GetMultipleHNAPs>\n<GetInterfaceStatistics><Interface>WAN</Interface></GetInterfaceStatistics><GetInterfaceStatistics><Interface>LAN</Interface></GetInterfaceStatistics><GetInterfaceStatistics><Interface>WLAN2.4G</Interface></GetInterfaceStatistics><GetInterfaceStatistics><Interface>WLAN5G</Interface></GetInterfaceStatistics></GetMultipleHNAPs>\n</soap:Body>\n</soap:Envelope>'

# curl 'http://192.168.0.1/hnap/GetWLanRadioSettings.xml?v=20190621180447' -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: */*' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Internet.html' -H "Cookie: uid=${cookie}"

# curl 'http://192.168.0.1/HNAP1/' -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: text/xml' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml' -H 'SOAPACTION: "http://purenetworks.com/HNAP1/GetWLanRadioSettings"' -H "HNAP_AUTH: ${hkey^^} ${ts}" -H 'Origin: http://192.168.0.1' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Internet.html' -H "Cookie: uid=${cookie}" -H 'Sec-GPC: 1' --data-raw $'<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">\n<soap:Body>\n<GetWLanRadioSettings xmlns="http://purenetworks.com/HNAP1/">\n<RadioID>RADIO_5GHz</RadioID>\n</GetWLanRadioSettings>\n</soap:Body>\n</soap:Envelope>'

# curl 'http://192.168.0.1/hnap/GetMultipleHNAPs.xml?v=20190621180448' -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: */*' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Home.html' -H "Cookie: uid=${cookie}"

# curl 'http://192.168.0.1/HNAP1/' -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: */*' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml; charset=utf-8' -H 'SOAPAction: "http://purenetworks.com/HNAP1/GetWanStatus"' -H "HNAP_AUTH: ${hkey^^} ${ts}" -H 'X-Requested-With: XMLHttpRequest' -H 'Origin: http://192.168.0.1' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Home.html' -H "Cookie: uid=${cookie}" -H 'Sec-GPC: 1' --data-raw '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><GetWanStatus xmlns="http://purenetworks.com/HNAP1/" /></soap:Body></soap:Envelope>'

curl 'http://192.168.0.1/HNAP1/' -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: */*' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml; charset=utf-8' -H 'SOAPAction: "http://purenetworks.com/HNAP1/'${currAPI}'"' -H "HNAP_AUTH: ${hkey^^} ${ts}" -H 'X-Requested-With: XMLHttpRequest' -H 'Origin: http://192.168.0.1'  -H 'Referer: http://192.168.0.1/Home.html' -H "Cookie: uid=${cookie}" -H 'Sec-GPC: 1' --data-raw '<?xml version="1.0" encoding="utf-8"?><soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/"><soap:Body><GetWanSettings xmlns="http://purenetworks.com/HNAP1/" /></soap:Body></soap:Envelope>'



# curl 'http://192.168.0.1/HNAP1/' -X POST -H 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:135.0) Gecko/20100101 Firefox/135.0' -H 'Accept: text/xml' -H 'Accept-Language: en-US,fr-FR;q=0.7,en;q=0.3' -H 'Accept-Encoding: gzip, deflate' -H 'Content-Type: text/xml' -H 'SOAPACTION: "http://purenetworks.com/HNAP1/GetMultipleHNAPs"' -H "HNAP_AUTH: ${hkey^^} ${ts}" -H 'Origin: http://192.168.0.1' -H 'Connection: keep-alive' -H 'Referer: http://192.168.0.1/Statistics.html' -H "Cookie: uid=${cookie}" -H 'Sec-GPC: 1' --data-raw $'<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">\n<soap:Body>\n<GetMultipleHNAPs>\n<GetInterfaceStatistics><Interface>WAN</Interface></GetInterfaceStatistics><GetInterfaceStatistics><Interface>LAN</Interface></GetInterfaceStatistics><GetInterfaceStatistics><Interface>WLAN2.4G</Interface></GetInterfaceStatistics><GetInterfaceStatistics><Interface>WLAN5G</Interface></GetInterfaceStatistics></GetMultipleHNAPs>\n</soap:Body>\n</soap:Envelope>'