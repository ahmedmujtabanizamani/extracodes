# http://192.168.0.116/boafrm/formAuthCheck

# wiz_username=admin&wiz_password=fcad92815e544e3effbd6c1f0ac5810a

# passwd="password string"


# makNId1E0lTqWeHl0oMC,slDRGNYbCGMfARc6mu64

# challenge=makNId1E0lTqWeHl0oMC
# pubKey=slDRGNYbCGMfARc6mu64

# privKey= hex_hmac_md5(pubKey+passwd, challenge),
# hashPasswd = hex_hmac_md5(privKey, challenge);

# wiz_username=admin&wiz_password=e2305062eda10315cc7a246fb6ff9f22


# http://192.168.0.116/status.htm


# logout
# curl 'http://192.168.0.116/logout.htm' -s
# curl 'http://anonymous:null@192.168.0.116/?sling:authRequestLogin=1' -s
# curl 'http://192.168.0.116/index.html' -s
# curl 'http://192.168.0.116/boafrm/formLogout' -X POST -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'return-url=%2Fstatus.htm' -s

# script
passwd="password string"
res=$(curl http://192.168.0.116/wizard_new_tick.html)
challenge=$(echo -n $res | grep -oE "^[^,]*")
pubKey=$(echo -n $res | grep -oE ",.*" | sed s/^,//g)
privKey=$(echo -n $challenge | openssl md5 -hex -mac HMAC -macopt key:${pubKey}${passwd} | grep -oE ".{32}$")
hashPasswd=$(echo -n $challenge | openssl md5 -hex -mac HMAC -macopt key:${privKey} | grep -oE ".{32}$")

echo $hashPasswd
curl 'http://192.168.0.116/boafrm/formAuthCheck' -X POST -H 'Content-Type: application/x-www-form-urlencoded' --data-raw 'wiz_username=admin&wiz_password='${hashPasswd}